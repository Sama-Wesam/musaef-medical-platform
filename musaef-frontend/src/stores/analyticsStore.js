import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

export const useAnalyticsStore = defineStore('analytics', {
  state: () => ({
    kpi: {
      critical_cases: 236,
      response_rate: '92.7%',
      total_requests: '1,248',
      total_donors: '8,765'
    },
    bloodDemand: [
      { type: 'O+', count: 452, color: '#D32F2F' },
      { type: 'O-', count: 298, color: '#D32F2F' },
      { type: 'A+', count: 215, color: '#F97316' },
      { type: 'A-', count: 142, color: '#F97316' },
      { type: 'B+', count: 98,  color: '#F59E0B' },
      { type: 'B-', count: 69,  color: '#F59E0B' },
      { type: 'AB+', count: 45, color: '#16A34A' },
      { type: 'AB-', count: 29, color: '#16A34A' }
    ],
    neediestHospitals: [
      { name: 'مستشفى ناصر', percent: 78, color: '#DC2626' },
      { name: 'مستشفى القدس', percent: 62, color: '#F59E0B' },
      { name: 'مستشفى الأوروبي', percent: 45, color: '#EA580C' },
      { name: 'مستشفى الشفاء', percent: 30, color: '#16A34A' },
      { name: 'مستشفى القدس', percent: 18, color: '#16A34A' }
    ],
    recentAlerts: [
      { status: 'عاجل', statusBadge: 'bg-danger-subtle text-danger', type: 'A+', hospital: 'مستشفى ناصر', time: '10:30 ص' },
      { status: 'متوسط', statusBadge: 'bg-warning-subtle text-warning-emphasis', type: 'B+', hospital: 'مستشفى القدس', time: '09:45 ص' },
      { status: 'مستقر', statusBadge: 'bg-success-subtle text-success', type: 'O-', hospital: 'مستشفى الأوروبي', time: '08:30 ص' },
      { status: 'مستقر', statusBadge: 'bg-success-subtle text-success', type: 'AB-', hospital: 'مستشفى الشفاء', time: '07:10 ص' }
    ],
    performance: {
      avg_response_time: '18:24 دقيقة',
      fulfillment_rate: '92.6%',
      daily_donation_rate: '1,234 وحدة'
    },
    loading: false
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
        console.warn('استخدام البيانات الافتراضية لمركز التحليلات.');
      } finally {
        this.loading = false;
      }
    }
  }
});
