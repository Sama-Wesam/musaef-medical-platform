<template>
  <AdminLayout>
    <div
      class="analytics-view container-fluid px-2 px-md-3"
      :dir="langStore.dir"
    >
      <!-- 1. المربعات الإحصائية العلوية -->
      <AnalyticsStatsCards :kpi="analyticsStore.kpi || {}" />

      <!-- 2. مخطط الطلب حسب فصيلة الدم (Donation Analytics AI) -->
      <BloodDemandChart :bloodDemand="analyticsStore.bloodDemand || []" />

      <!-- 3. قسم آخر التنبيهات + أكثر المستشفيات احتياجاً -->
      <div class="row g-3 g-lg-4 mb-3 mb-md-4">
        <div class="col-12 col-lg-6">
          <RecentAlertsTable :alertsList="analyticsStore.recentAlerts || []" />
        </div>
        <div class="col-12 col-lg-6">
          <TopHospitalsList :topHospitals="analyticsStore.neediestHospitals || []" />
        </div>
      </div>

      <!-- 4. قسم خريطة مستشفيات قطاع غزة + إحصائيات الأداء -->
      <div class="row g-3 g-lg-4">
        <div class="col-12 col-lg-7">
          <HospitalsGazaMap />
        </div>
        <div class="col-12 col-lg-5">
          <PerformanceStatsCard :performance="analyticsStore.performance || {}" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { onMounted } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { useAnalyticsStore } from '@/stores/analyticsStore';
import { useLangStore } from '@/stores/langStore';

import AnalyticsStatsCards from '@/components/admin/analytics/AnalyticsStatsCards.vue';
import BloodDemandChart from '@/components/admin/analytics/BloodDemandChart.vue';
import RecentAlertsTable from '@/components/admin/analytics/RecentAlertsTable.vue';
import TopHospitalsList from '@/components/admin/analytics/TopHospitalsList.vue';
import HospitalsGazaMap from '@/components/admin/analytics/HospitalsGazaMap.vue';
import PerformanceStatsCard from '@/components/admin/analytics/PerformanceStatsCard.vue';

const langStore = useLangStore();
const analyticsStore = useAnalyticsStore();

onMounted(() => {
  analyticsStore.fetchAnalyticsData();
});
</script>

<style scoped>
.analytics-view {
  font-family: Arial, sans-serif;
  padding-bottom: 24px;
}
</style>
