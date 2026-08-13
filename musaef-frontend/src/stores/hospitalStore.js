import { defineStore } from 'pinia';
import hospitalApi from '@/api/hospital';

export const useHospitalStore = defineStore('hospital', {
  state: () => ({
    stats: {
      totalUnits: 0,
      validUnits: 0,
      lowStockUnits: 0,
      criticalTypesCount: 0
    },
    inventory: [],
    urgentAlerts: [],
    recentDonations: [],
    loading: false,
    pollingTimer: null
  }),

  actions: {
    async fetchInventory() {
      this.loading = true;
      try {
        const response = await hospitalApi.getBloodInventory();
        const rawData = response.data?.data || response.data;

        const items = rawData?.inventory || rawData;
        if (Array.isArray(items)) {
          this.inventory = items.map(item => ({
            id: item.id || item.blood_type_id,
            type: item.type || item.blood_type?.name || item.blood_type || 'O+',
            available: item.available ?? item.units ?? item.available_units ?? 0,
            minRequired: item.minRequired ?? item.min_limit ?? 10,
            statusRaw: item.statusRaw || item.status || 'طبيعي',
            percentage: item.percentage ?? Math.round(((item.available ?? item.units ?? 0) / (item.minRequired ?? item.min_limit ?? 10)) * 100)
          }));
        }

        if (rawData?.stats) {
          this.stats = {
            totalUnits: rawData.stats.totalUnits ?? rawData.stats.total_units ?? 0,
            validUnits: rawData.stats.validUnits ?? rawData.stats.valid_units ?? 0,
            lowStockUnits: rawData.stats.lowStockUnits ?? rawData.stats.low_stock_count ?? 0,
            criticalTypesCount: rawData.stats.criticalTypesCount ?? rawData.stats.critical_count ?? 0
          };
        }

        if (Array.isArray(rawData?.urgentAlerts)) {
          this.urgentAlerts = rawData.urgentAlerts;
        }
        if (Array.isArray(rawData?.recentDonations)) {
          this.recentDonations = rawData.recentDonations;
        }

      } catch (err) {
        console.error('خطأ في جلب مخزون بنك الدم:', err);
      } finally {
        this.loading = false;
      }
    },

    startPolling(intervalMs = 5000) {
      this.fetchInventory();
      if (this.pollingTimer) clearInterval(this.pollingTimer);
      this.pollingTimer = setInterval(() => {
        this.fetchInventory();
      }, intervalMs);
    },

    stopPolling() {
      if (this.pollingTimer) {
        clearInterval(this.pollingTimer);
        this.pollingTimer = null;
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
        throw e;
      }
    }
  }
});
