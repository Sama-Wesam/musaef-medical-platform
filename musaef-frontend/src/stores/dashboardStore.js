import { defineStore } from 'pinia';
import analyticsApi from '@/api/analytics';

export const useDashboardStore = defineStore('dashboard', {
  state: () => ({
    kpiStats: {
      totalDonations: 3580,
      activeRequests: 14,
      registeredDonors: 1240,
      hospitalsCount: 28
    },
    quickMetrics: {
      monthlyResponseRate: '94%',
      avgResponseTime: '12 دقيقة'
    },
    aiPredictions: [],
    loading: false
  }),

  actions: {
    async fetchDashboardData() {
      this.loading = true;
      try {
        const stats = await analyticsApi.getDashboardStats();
        if (stats) {
          this.kpiStats = { ...this.kpiStats, ...stats };
        }
      } catch (err) {
        // الحفاظ على القيم الأولى عند أي تعذر
      } finally {
        this.loading = false;
      }
    },

    async fetchAIPredictions() {
      try {
        const predictions = await analyticsApi.getAIPredictions();
        this.aiPredictions = predictions || [];
      } catch (err) {
        this.aiPredictions = [];
      }
    },

    updateKPIs(newStats) {
      this.kpiStats = { ...this.kpiStats, ...newStats };
    }
  }
});
