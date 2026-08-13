import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

export const useAnalyticsStore = defineStore('analytics', {
  state: () => ({
    kpi: {
      critical_cases: 0,
      response_rate: '0%',
      total_requests: '0',
      total_donors: '0'
    },
    bloodDemand: [],
    neediestHospitals: [],
    recentAlerts: [],
    performance: {
      avg_response_time: '0:00 دقيقة',
      fulfillment_rate: '0%',
      daily_donation_rate: '0 وحدة'
    },
    loading: false,
    pollingTimer: null
  }),

  actions: {
    async fetchAnalyticsData() {
      this.loading = true;
      try {
        const res = await apiClient.get('/admin/analytics');
        const data = res.data?.data || res.data;
        if (data) {
          if (data.kpi) this.kpi = data.kpi;
          if (data.blood_demand) this.bloodDemand = data.blood_demand;
          if (data.neediest_hospitals) this.neediestHospitals = data.neediest_hospitals;
          if (data.recent_alerts) this.recentAlerts = data.recent_alerts;
          if (data.performance) this.performance = data.performance;
        }
      } catch (err) {
        console.error('خطأ في جلب بيانات مركز التحليلات المباشرة:', err);
      } finally {
        this.loading = false;
      }
    },

    async fetchRecentAlerts() {
      try {
        const response = await apiClient.get('/admin/analytics/all-alerts');
        const data = response.data?.data || response.data;
        if (Array.isArray(data)) {
          this.recentAlerts = data;
        }
      } catch (err) {
        console.error('خطأ في تحديث التنبيهات الذكية:', err);
      }
    },

    startPolling(intervalMs = 5000) {
      this.fetchAnalyticsData();

      if (this.pollingTimer) {
        clearInterval(this.pollingTimer);
      }

      this.pollingTimer = setInterval(() => {
        this.fetchAnalyticsData();
      }, intervalMs);
    },

    stopPolling() {
      if (this.pollingTimer) {
        clearInterval(this.pollingTimer);
        this.pollingTimer = null;
      }
    }
  }
});
