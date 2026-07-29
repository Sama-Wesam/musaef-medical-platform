<template>
  <AdminLayout>
    <div class="admin-dashboard-view container-fluid px-2 px-md-3 dir-rtl" dir="rtl">

      <!-- 1. الكروت الإحصائية الـ 5 العلوية -->
      <StatsOverviewCards :stats="dashboardStore.stats" />

      <!-- 2. رادار الطوارئ المباشر (يمين) + توزيع الطلبات (يسار) -->
      <div class="row g-3 g-lg-4 mb-3 mb-md-4">
        <div class="col-12 col-lg-7">
          <EmergencyRadarMap />
        </div>
        <div class="col-12 col-lg-5">
          <BloodDistributionDonut :distribution="dashboardStore.bloodDistribution" />
        </div>
      </div>

      <!-- 3. رسم بياني: تطور الحالات الطارئة (آخر 6 أشهر) -->
      <EmergencyTrendChart />

      <!-- 4. النشاط العام للمنصة (يمين) + النشاطات الأخيرة (يسار) -->
      <div class="row g-3 g-lg-4">
        <div class="col-12 col-lg-6">
          <PlatformWeeklyActivity />
        </div>
        <div class="col-12 col-lg-6">
          <RecentActivitiesList :activities="dashboardStore.recentActivities" />
        </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { onMounted } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { useAdminDashboardStore } from '@/stores/adminStore';

import StatsOverviewCards from '@/components/admin/dashboard/StatsOverviewCards.vue';
import EmergencyRadarMap from '@/components/admin/dashboard/EmergencyRadarMap.vue';
import BloodDistributionDonut from '@/components/admin/dashboard/BloodDistributionDonut.vue';
import EmergencyTrendChart from '@/components/admin/dashboard/EmergencyTrendChart.vue';
import PlatformWeeklyActivity from '@/components/admin/dashboard/PlatformWeeklyActivity.vue';
import RecentActivitiesList from '@/components/admin/dashboard/RecentActivitiesList.vue';

const dashboardStore = useAdminDashboardStore();

onMounted(() => {
  dashboardStore.fetchDashboardData();
});
</script>

<style scoped>
.admin-dashboard-view {
  font-family: 'Cairo', sans-serif;
  padding-bottom: 24px;
}
.dir-rtl { direction: rtl; }
</style>
