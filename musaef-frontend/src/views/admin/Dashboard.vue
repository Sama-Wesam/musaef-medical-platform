<template>
  <AdminLayout>
    <div
      class="admin-dashboard-view container-fluid px-2 px-md-3"
      :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'"
    >
      <!-- 1. الكروت الإحصائية الـ 5 العلوية -->
      <StatsOverviewCards :stats="dashboardStore.stats" />

      <!-- 2. رادار الطوارئ المباشر (Leaflet Heatmap) + توزيع الطلبات -->
      <div class="row g-3 g-lg-4 mb-3 mb-md-4">
        <div class="col-12 col-lg-7">
          <EmergencyRadarMap />
        </div>
        <div class="col-12 col-lg-5">
          <BloodDistributionDonut :distribution="dashboardStore.bloodDistribution" />
        </div>
      </div>

      <!-- 3. رسم بياني: تطور الحالات الطارئة -->
      <EmergencyTrendChart :trend-data="dashboardStore.emergencyTrend" />

      <!-- 4. النشاط العام للمنصة + النشاطات الأخيرة -->
      <div class="row g-3 g-lg-4">
        <div class="col-12 col-lg-6">
          <PlatformWeeklyActivity :weekly-data="dashboardStore.weeklyActivity" />
        </div>
        <div class="col-12 col-lg-6">
          <RecentActivitiesList :activities="dashboardStore.recentActivities" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from "vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import { useAdminDashboardStore } from "@/stores/adminStore";

import StatsOverviewCards from "@/components/admin/dashboard/StatsOverviewCards.vue";
import EmergencyRadarMap from "@/components/admin/dashboard/EmergencyRadarMap.vue";
import BloodDistributionDonut from "@/components/admin/dashboard/BloodDistributionDonut.vue";
import EmergencyTrendChart from "@/components/admin/dashboard/EmergencyTrendChart.vue";
import PlatformWeeklyActivity from "@/components/admin/dashboard/PlatformWeeklyActivity.vue";
import RecentActivitiesList from "@/components/admin/dashboard/RecentActivitiesList.vue";

const dashboardStore = useAdminDashboardStore();
const currentLanguage = computed(() => localStorage.getItem("musaef_lang") || "ar");

const handleDonorActivity = () => {
  if (dashboardStore.fetchDashboardData) {
    dashboardStore.fetchDashboardData();
  }
};

onMounted(() => {
  dashboardStore.startPolling(5000);
  window.addEventListener("donor-activity-updated", handleDonorActivity);
});

onUnmounted(() => {
  dashboardStore.stopPolling();
  window.removeEventListener("donor-activity-updated", handleDonorActivity);
});
</script>

<style scoped>
.admin-dashboard-view {
  font-family: "Cairo", sans-serif;
  padding-bottom: 24px;
}
.dir-rtl {
  direction: rtl;
}
.dir-ltr {
  direction: ltr;
}
</style>
