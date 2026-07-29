import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

export const useAdminDashboardStore = defineStore('adminDashboard', {
  state: () => ({
    stats: {
      donors_count: 128547,
      hospitals_count: 347,
      total_requests: 24892,
      total_donations: 18765,
      critical_cases: 312
    },
    bloodDistribution: [],
    recentActivities: [],
    loading: false
  }),

  actions: {
    async fetchDashboardData() {
      this.loading = true;
      try {
        const response = await apiClient.get('/admin/dashboard');
        const data = response.data?.data || response.data;
        if (data) {
          if (data.stats) this.stats = data.stats;
          if (data.blood_distribution) this.bloodDistribution = data.blood_distribution;
          if (data.recent_activities) this.recentActivities = data.recent_activities;
        }
      } catch (err) {
        console.error('خطأ في جلب بيانات لوحة تحكم الإدارة:', err);
      } finally {
        this.loading = false;
      }
    }
  }
});
