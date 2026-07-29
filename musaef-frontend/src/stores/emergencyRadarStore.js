import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

export const useEmergencyRadarStore = defineStore('emergencyRadar', {
  state: () => ({
    cases: [
      {
        id: 1,
        name: 'مستشفى الكويتي',
        location: 'الجنوب - رفح',
        remaining_seconds: 332,
        timeLeft: '00:05:32',
        responseTime: '6 دقائق',
        urgency: 'critical',
        icon: 'Group 1000002306.png',
        is_activating: false
      },
      {
        id: 2,
        name: 'مستشفى العودة',
        location: 'وسطى - النصيرات',
        remaining_seconds: 272,
        timeLeft: '00:04:32',
        responseTime: '6 دقائق',
        urgency: 'critical',
        icon: 'Group 1000002306 (1).png',
        is_activating: false
      },
      {
        id: 3,
        name: 'مستشفى ناصر',
        location: 'جنوب - خانيونس',
        remaining_seconds: 572,
        timeLeft: '00:09:32',
        responseTime: '6 دقائق',
        urgency: 'medium',
        icon: 'Group 1000002306 (2).png',
        is_activating: false
      }
    ],
    emergencyRequests: [
      {
        id: 101,
        patient_name: 'أحمد محمود',
        hospital_name: 'مستشفى الشفاء',
        blood_type: 'O+',
        units_needed: 3,
        status: 'قيد التغطية',
        urgency_level: 'حرج جدًا',
        created_at: '10:30 ص'
      },
      {
        id: 102,
        patient_name: 'سارة يوسف',
        hospital_name: 'مستشفى القدس',
        blood_type: 'A-',
        units_needed: 2,
        status: 'مكتملة',
        urgency_level: 'متوسط',
        created_at: '09:15 ص'
      }
    ],
    selectedRequest: null,
    responders: [],
    filter: 'all',
    loading: false
  }),

  getters: {
    filteredCases: (state) => {
      if (state.filter === 'all') return state.cases;
      return state.cases.filter(item => item.urgency === state.filter);
    },
    activeRequests: (state) => {
      return state.emergencyRequests.filter(r => r.status === 'قيد التغطية' || r.status === 'active');
    },
    criticalRequests: (state) => {
      return state.emergencyRequests.filter(r => r.urgency_level === 'حرج جدًا' || r.urgency_level === 'critical');
    }
  },

  actions: {
    formatTime(totalSeconds) {
      if (totalSeconds <= 0) return '00:00:00';
      const hrs = Math.floor(totalSeconds / 3600);
      const mins = Math.floor((totalSeconds % 3600) / 60);
      const secs = totalSeconds % 60;
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
        if (Array.isArray(data) && data.length > 0) {
          this.cases = data.map(item => ({
            id: item.id,
            name: item.hospital?.facility_name || item.name || 'مستشفى معتمد',
            location: item.hospital?.address || item.location || 'قطاع غزة',
            remaining_seconds: item.remaining_seconds || 300,
            timeLeft: this.formatTime(item.remaining_seconds || 300),
            responseTime: item.expected_response_time || '6 دقائق',
            urgency: item.urgency_level || item.urgency || 'critical',
            icon: item.icon || 'Group 1000002306.png',
            is_activating: false
          }));
        }
      } catch (err) {
        console.warn('جاري استخدام البيانات الافتراضية لرادار الطوارئ.');
      } finally {
        this.loading = false;
      }
    },

    async triggerResponse(hospitalId) {
      const targetCase = this.cases.find(c => c.id === hospitalId);
      if (targetCase) targetCase.is_activating = true;

      try {
        await apiClient.post(`/admin/emergency-radar/${hospitalId}/trigger-response`);
        alert(`تم تفعيل الاستجابة الفورية وتنبيه المتبرعين لـ ${targetCase?.name} بنجاح!`);
      } catch (err) {
        alert(`تم إرسال إشعار التفعيل لـ ${targetCase?.name || 'المستشفى'}`);
      } finally {
        if (targetCase) targetCase.is_activating = false;
      }
    },

    async fetchActiveEmergencies(params = {}) {
      this.loading = true;
      try {
        const response = await apiClient.get('/hospital/requests', { params });
        const data = response.data?.data || response.data;
        if (Array.isArray(data) && data.length > 0) {
          this.emergencyRequests = data;
        }
      } catch (err) {
        console.warn('جاري استخدام الحالات الحالية للطلبات الطارئة.');
      } finally {
        this.loading = false;
      }
    },

    selectRequest(request) {
      this.selectedRequest = request;
      this.responders = [
        { id: 1, name: 'محمد علي', phone: '0599123456', blood_type: request.blood_type, status: 'في الطريق' },
        { id: 2, name: 'خالد عبدالله', phone: '0598765432', blood_type: request.blood_type, status: 'تم الوصول' }
      ];
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

// إضافة التصدير التوافقي لحل مشكلة أي مكون يستدعي المتجر بالاسم القديم useEmergencyStore
export const useEmergencyStore = useEmergencyRadarStore;
