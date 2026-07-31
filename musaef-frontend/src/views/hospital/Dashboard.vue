<template>
  <HospitalLayout>
    <div class="hospital-dashboard container-fluid px-2 px-md-3">

      <!-- مؤشر التحميل -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">جاري التحميل...</span>
        </div>
        <p class="text-muted mt-2 fs-8">جاري تحميل بيانات لوحة تحكم المستشفى...</p>
      </div>

      <template v-else>
        <!-- عنوان القسم العلوي مع مؤشر الحالات الحرجة (Emergency Priority AI) -->
        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-2">
          <h4 class="fw-bold text-dark mb-0 fs-5 fs-md-4">الإحصائيات العامة للمستشفى</h4>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fs-8 fw-bold">
              <i class="bi bi-exclamation-octagon-fill me-1"></i> الحالات الحرجة النشطة: {{ dashboardData.stats?.critical_cases || 7 }}
            </span>
          </div>
        </div>

        <!-- 1. البطاقات الإحصائية العلوية -->
        <StatsCards :stats="dashboardData.stats || {}" />

        <!-- 2. الرسوم البيانية (Donation Analytics AI) -->
        <div class="row g-3 g-md-4 mb-3 mb-md-4">
          <div class="col-12 col-lg-5">
            <BloodDistribution :distribution="dashboardData.blood_distribution || []" />
          </div>
          <div class="col-12 col-lg-7">
            <MonthlyRequestsChart :chart-data="dashboardData.monthly_requests || []" />
          </div>
        </div>

        <!-- 3. التنبيهات وتوقعات الذكاء الاصطناعي (Blood Demand Forecast AI) -->
        <div class="row g-3 g-md-4">
          <div class="col-12 col-lg-6">
            <StockAlerts :alerts="dashboardData.inventory_alerts || []" />
          </div>
          <div class="col-12 col-lg-6">
            <AiPredictions :prediction="dashboardData.ai_prediction || {}" />
          </div>
        </div>
      </template>

    </div>
  </HospitalLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '@/api/axios';
import HospitalLayout from '@/layouts/HospitalLayout.vue';
import StatsCards from '@/components/hospital/dashboard/StatsCards.vue';
import BloodDistribution from '@/components/hospital/dashboard/BloodDistribution.vue';
import MonthlyRequestsChart from '@/components/hospital/dashboard/MonthlyRequestsChart.vue';
import StockAlerts from '@/components/hospital/dashboard/StockAlerts.vue';
import AiPredictions from '@/components/hospital/dashboard/AiPredictions.vue';

const loading = ref(true);
const dashboardData = ref({});

const fetchDashboardData = async () => {
  loading.value = true;
  try {
    const res = await apiClient.get('/hospital/dashboard');
    if (res && res.data) {
      dashboardData.value = res.data.data || res.data;
    }
  } catch (error) {
    console.error('خطأ في جلب بيانات لوحة تحكم المستشفى:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchDashboardData();
});
</script>

<style scoped>
.hospital-dashboard {
  padding-bottom: 20px;
  direction: rtl;
  text-align: right;
  overflow-x: hidden;
}
.bg-danger-subtle {
  background-color: #fee2e2 !important;
}
</style>
