<template>
  <HospitalLayout>
    <div class="blood-bank-inventory container-fluid px-2 px-md-3" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">

      <!-- الهيدر وعنوان الصفحة -->
      <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <div>
          <h4 class="fw-bold text-dark mb-1 fs-5 fs-md-4">{{ t('pageTitle') }}</h4>
          <p class="text-muted fs-8 mb-0">{{ t('pageSubtitle') }}</p>
        </div>
        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" @click="refreshInventoryData">
          <i class="bi bi-arrow-clockwise me-1" :class="{ 'spin-icon': hospitalStore.loading }"></i>
          {{ currentLanguage === 'en' ? 'Refresh' : 'تحديث المخزون' }}
        </button>
      </div>

      <!-- 1. الكروت الإحصائية العلوية -->
      <InventoryStatsCards :stats="hospitalStore.stats" />

      <!-- 2. جدول المخزون والمربعات الجانبية -->
      <div class="row g-3 g-lg-4 mb-4">
        <div class="col-12 col-lg-8">
          <MainInventoryTable :items="hospitalStore.inventory" :loading="hospitalStore.loading" />
        </div>

        <div class="col-12 col-lg-4 d-flex flex-column gap-3 gap-md-4">
          <UrgentAlertsCard :alerts="hospitalStore.urgentAlerts" />
          <RecentDonationsCard :donations="hospitalStore.recentDonations" />
        </div>
      </div>

      <!-- 3. نماذج العمليات (إضافة/سحب وحدات) -->
      <StockOperationsForm @refresh="refreshInventoryData" />

    </div>
  </HospitalLayout>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue';
import HospitalLayout from '@/layouts/HospitalLayout.vue';
import { useHospitalStore } from '@/stores/hospitalStore';

import InventoryStatsCards from '@/components/hospital/bloodinventory/InventoryStatsCards.vue';
import MainInventoryTable from '@/components/hospital/bloodinventory/MainInventoryTable.vue';
import UrgentAlertsCard from '@/components/hospital/bloodinventory/UrgentAlertsCard.vue';
import RecentDonationsCard from '@/components/hospital/bloodinventory/RecentDonationsCard.vue';
import StockOperationsForm from '@/components/hospital/bloodinventory/StockOperationsForm.vue';

const hospitalStore = useHospitalStore();
const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');
let autoRefreshTimer = null;

const dictionary = {
  ar: {
    pageTitle: 'إدارة بنك الدم',
    pageSubtitle: 'متابعة وتحديث مخزون وحدات الدم بشكل لحظي'
  },
  en: {
    pageTitle: 'Blood Bank Management',
    pageSubtitle: 'Real-time tracking and updating of blood unit inventory'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const refreshInventoryData = async () => {
  if (hospitalStore.fetchInventory) {
    await hospitalStore.fetchInventory();
  }
};

onMounted(() => {
  refreshInventoryData();
  autoRefreshTimer = setInterval(refreshInventoryData, 5000);
});

onUnmounted(() => {
  if (autoRefreshTimer) clearInterval(autoRefreshTimer);
});
</script>

<style scoped>
.blood-bank-inventory {
  font-family: 'Cairo', sans-serif;
  padding-bottom: 24px;
}
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
.fs-8 { font-size: 0.82rem; }

.spin-icon {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  100% { transform: rotate(360deg); }
}
</style>
