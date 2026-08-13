import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

export const useEmergencyRadarStore = defineStore('emergencyRadar', {
  state: () => ({
    cases: [],
    emergencyRequests: [],
    selectedRequest: null,
    responders: [],
    filter: 'all',
    loading: false,
    pollingTimer: null,
    countdownTimer: null
  }),

  getters: {
    filteredCases: (state) => {
      if (state.filter === 'all') return state.cases;
      return state.cases.filter(item => item.urgency === state.filter);
    },
    activeRequests: (state) => {
      return state.emergencyRequests.filter(r => r.status === 'قيد التغطية' || r.status === 'active' || r.status === 'pending');
    },
    criticalRequests: (state) => {
      return state.emergencyRequests.filter(r => r.urgency_level === 'حرج جدًا' || r.urgency_level === 'critical');
    }
  },

  actions: {
    formatTime(totalSeconds) {
      if (!totalSeconds || totalSeconds <= 0) return '00:00:00';
      const hrs = Math.floor(totalSeconds / 3600);
      const mins = Math.floor((totalSeconds % 3600) / 60);
      const secs = Math.floor(totalSeconds % 60);
      return `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    },

    decrementCountdowns() {
      this.cases.forEach(item => {
        if (item.remaining_seconds > 0) {
          item.remaining_seconds--;
          item.timeLeft = this.formatTime(item.remaining_seconds);
        }
      });
    },

    async fetchRadarData() {
      this.loading = true;
      try {
        const response = await apiClient.get('/admin/emergency-radar', {
          params: { urgency: this.filter }
        });
        const data = response.data?.data || response.data;
        if (Array.isArray(data)) {
          this.cases = data.map(item => ({
            id: item.id,
            name: item.hospital?.facility_name || item.name || 'مستشفى معتمد',
            location: item.hospital?.address || item.location || 'قطاع غزة',
            lat: parseFloat(item.hospital?.latitude || item.lat || 31.35),
            lng: parseFloat(item.hospital?.longitude || item.lng || 34.32),
            remaining_seconds: item.remaining_seconds || 300,
            timeLeft: this.formatTime(item.remaining_seconds || 300),
            responseTime: item.expected_response_time || '6 دقائق',
            urgency: item.urgency_level || item.urgency || 'critical',
            icon: item.icon || 'Group 1000002306.png',
            is_activating: false
          }));
        }
      } catch (err) {
        console.error('خطأ في جلب بيانات رادار الطوارئ:', err);
      } finally {
        this.loading = false;
      }
    },

    async triggerResponse(hospitalId) {
      const targetCase = this.cases.find(c => c.id === hospitalId);
      if (targetCase) targetCase.is_activating = true;

      try {
        await apiClient.post(`/admin/emergency-radar/${hospitalId}/trigger-response`);
        await this.fetchRadarData();
      } catch (err) {
        console.error('خطأ في تفعيل الاستجابة الفورية:', err);
      } finally {
        if (targetCase) targetCase.is_activating = false;
      }
    },

    async fetchActiveEmergencies(params = {}) {
      this.loading = true;
      try {
        const response = await apiClient.get('/hospital/requests', { params });
        const data = response.data?.data || response.data;
        if (Array.isArray(data)) {
          this.emergencyRequests = data;
        }
      } catch (err) {
        console.error('خطأ في جلب طلبات الطوارئ:', err);
      } finally {
        this.loading = false;
      }
    },

    startPolling(intervalMs = 5000) {
      this.fetchRadarData();
      this.fetchActiveEmergencies();

      if (this.pollingTimer) clearInterval(this.pollingTimer);
      this.pollingTimer = setInterval(() => {
        this.fetchRadarData();
        this.fetchActiveEmergencies();
      }, intervalMs);

      if (this.countdownTimer) clearInterval(this.countdownTimer);
      this.countdownTimer = setInterval(() => {
        this.decrementCountdowns();
      }, 1000);
    },

    stopPolling() {
      if (this.pollingTimer) {
        clearInterval(this.pollingTimer);
        this.pollingTimer = null;
      }
      if (this.countdownTimer) {
        clearInterval(this.countdownTimer);
        this.countdownTimer = null;
      }
    },

    selectRequest(request) {
      this.selectedRequest = request;
    },

    async acceptSelectedRequest() {
      if (this.selectedRequest) {
        this.selectedRequest.status = 'مكتملة';
      }
    },

    async rejectSelectedRequest() {
      if (this.selectedRequest) {
        this.selectedRequest.status = 'ملغاة';
      }
    }
  }
});

export const useEmergencyStore = useEmergencyRadarStore;
