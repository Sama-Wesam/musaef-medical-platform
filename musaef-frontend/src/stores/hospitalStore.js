import { defineStore } from 'pinia';
import hospitalApi from '@/api/hospital';

export const useHospitalStore = defineStore('hospital', {
  state: () => ({
    stats: {
      total_units: 1248,
      valid_units: 856,
      low_stock_count: 254,
      critical_count: 3
    },
    inventory: [
      { type: 'O-', available: 256, minRequired: 100, status: 'طبيعي', statusClass: 'bg-success-subtle text-success', percentage: 256, progressClass: 'bg-success' },
      { type: 'A+', available: 2, minRequired: 10, status: 'حرج', statusClass: 'bg-danger-subtle text-danger', percentage: 20, progressClass: 'bg-danger' },
      { type: 'B-', available: 180, minRequired: 80, status: 'طبيعي', statusClass: 'bg-success-subtle text-success', percentage: 255, progressClass: 'bg-success' },
      { type: 'AB+', available: 20, minRequired: 20, status: 'طبيعي', statusClass: 'bg-success-subtle text-success', percentage: 175, progressClass: 'bg-success' },
      { type: 'O+', available: 60, minRequired: 60, status: 'طبيعي', statusClass: 'bg-success-subtle text-success', percentage: 236, progressClass: 'bg-success' },
      { type: 'A-', available: 15, minRequired: 15, status: 'منخفض', statusClass: 'bg-warning-subtle text-warning-emphasis', percentage: 33, progressClass: 'bg-warning' },
      { type: 'B+', available: 30, minRequired: 30, status: 'طبيعي', statusClass: 'bg-success-subtle text-success', percentage: 227, progressClass: 'bg-success' },
      { type: 'AB-', available: 10, minRequired: 10, status: 'منخفض', statusClass: 'bg-warning-subtle text-warning-emphasis', percentage: 60, progressClass: 'bg-warning' }
    ],
    loading: false
  }),

  actions: {
    async fetchInventory() {
      this.loading = true;
      try {
        const response = await hospitalApi.getBloodInventory();
        const rawData = response.data?.data || response.data;

        const items = rawData?.inventory || rawData;
        if (Array.isArray(items) && items.length > 0) {
          this.inventory = items.map(item => ({
            type: item.blood_type?.name || item.blood_type || item.type || 'O+',
            available: item.available_units ?? item.available ?? item.units ?? 0,
            minRequired: item.min_limit || item.minRequired || 10,
            status: item.status_text || item.status || 'طبيعي',
            statusClass: item.status === 'critical' || item.status === 'حرج' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success',
            percentage: item.coverage_percentage || item.percentage || 100,
            progressClass: item.status === 'critical' || item.status === 'حرج' ? 'bg-danger' : 'bg-success'
          }));
        }

        if (rawData?.stats) {
          this.stats = rawData.stats;
        }
      } catch (err) {
        console.error('خطأ في جلب مخزون بنك الدم:', err);
      } finally {
        this.loading = false;
      }
    },

    async updateStockOperation(payload) {
      try {

        if (typeof hospitalApi.updateInventory === 'function') {
          await hospitalApi.updateInventory(payload);
        } else if (typeof hospitalApi.updateStock === 'function') {
          await hospitalApi.updateStock(payload);
        }
        await this.fetchInventory();
        return true;
      } catch (e) {
        console.error('خطأ في تحديث المخزون:', e);
        return false;
      }
    }
  }
});
