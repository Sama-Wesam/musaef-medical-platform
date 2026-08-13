import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

export const useAdminDashboardStore = defineStore('adminDashboard', {
  state: () => ({
    stats: {
      donorsCount: 0,
      hospitalsCount: 0,
      requestsCount: 0,
      donationsCount: 0,
      criticalCasesCount: 0,
    },
    bloodDistribution: [],
    radarPoints: [],
    emergencyTrend: [],
    weeklyActivity: [],
    recentActivities: [],

    loading: false,
    pollingTimer: null,
    error: null
  }),

  actions: {
    async fetchDashboardData() {
      this.loading = true;
      try {
        const response = await apiClient.get('/admin/dashboard');
        const data = response.data?.data || response.data;

        if (data) {
          if (data.stats) {
            this.stats = {
              donorsCount: data.stats.donors_count ?? data.stats.donorsCount ?? 0,
              hospitalsCount: data.stats.hospitals_count ?? data.stats.hospitalsCount ?? 0,
              requestsCount: data.stats.total_requests ?? data.stats.requestsCount ?? 0,
              donationsCount: data.stats.total_donations ?? data.stats.donationsCount ?? 0,
              criticalCasesCount: data.stats.critical_cases ?? data.stats.criticalCasesCount ?? 0,
            };
          }

          if (data.blood_distribution) {
            this.bloodDistribution = data.blood_distribution;
          }

          if (data.radar_points) {
            this.radarPoints = data.radar_points;
          }

          if (data.emergency_trend) {
            this.emergencyTrend = data.emergency_trend;
          }

          if (data.weekly_activity) {
            this.weeklyActivity = data.weekly_activity;
          }

          if (data.recent_activities) {
            this.recentActivities = data.recent_activities;
          }
        }
        this.error = null;
      } catch (err) {
        console.error('خطأ في جلب بيانات لوحة تحكم الإدارة:', err);
        this.error = err;
      } finally {
        this.loading = false;
      }
    },

    async fetchLiveStats() {
      try {
        const response = await apiClient.get('/admin/dashboard/live-stats');
        const data = response.data?.data || response.data;
        if (data) {
          this.stats = {
            donorsCount: data.donors_count ?? this.stats.donorsCount,
            hospitalsCount: data.hospitals_count ?? this.stats.hospitalsCount,
            requestsCount: data.total_requests ?? this.stats.requestsCount,
            donationsCount: data.total_donations ?? this.stats.donationsCount,
            criticalCasesCount: data.critical_cases ?? this.stats.criticalCasesCount,
          };
        }
      } catch (err) {
        console.error('خطأ في جلب الإحصائيات المباشرة:', err);
      }
    },

    startPolling(intervalMs = 5000) {
      this.fetchDashboardData();

      if (this.pollingTimer) {
        clearInterval(this.pollingTimer);
      }

      this.pollingTimer = setInterval(() => {
        this.fetchLiveStats();
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
