<template>
  <HospitalLayout>
    <div class="blood-bank-inventory container-fluid px-2 px-md-3 dir-rtl text-end" dir="rtl">

      <!-- الهيدر وعنوان الصفحة -->
      <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1 fs-5 fs-md-4">إدارة بنك الدم</h4>
        <p class="text-muted fs-8 mb-0">متابعة وتحديث مخزون وحدات الدم بشكل لحظي</p>
      </div>

      <!-- 1. الكروت الإحصائية العلوية -->
      <InventoryStatsCards :stats="hospitalStore.stats" />

      <!-- 2. جدول المخزون والمربعات الجانبية -->
      <div class="row g-3 g-lg-4 mb-4">
        <div class="col-12 col-lg-8">
          <MainInventoryTable :items="hospitalStore.inventory" :loading="hospitalStore.loading" />
        </div>

        <div class="col-12 col-lg-4 d-flex flex-column gap-3 gap-md-4">
          <UrgentAlertsCard />
          <RecentDonationsCard />
        </div>
      </div>

      <!-- 3. نماذج العمليات (إضافة/سحب وحدات) -->
      <StockOperationsForm @refresh="hospitalStore.fetchInventory()" />

    </div>
  </HospitalLayout>
</template>

<script setup>
import { onMounted } from 'vue';
import HospitalLayout from '@/layouts/HospitalLayout.vue';
import { useHospitalStore } from '@/stores/hospitalStore';

import InventoryStatsCards from '@/components/hospital/bloodinventory/InventoryStatsCards.vue';
import MainInventoryTable from '@/components/hospital/bloodinventory/MainInventoryTable.vue';
import UrgentAlertsCard from '@/components/hospital/bloodinventory/UrgentAlertsCard.vue';
import RecentDonationsCard from '@/components/hospital/bloodinventory/RecentDonationsCard.vue';
import StockOperationsForm from '@/components/hospital/bloodinventory/StockOperationsForm.vue';

const hospitalStore = useHospitalStore();

onMounted(() => {
  hospitalStore.fetchInventory();
});
</script>

<style scoped>
.blood-bank-inventory {
  font-family: 'Cairo', sans-serif;
  padding-bottom: 24px;
}
.dir-rtl { direction: rtl; }
</style>
