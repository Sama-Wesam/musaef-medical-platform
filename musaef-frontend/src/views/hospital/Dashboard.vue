<template>
  <HospitalLayout>
    <div
      class="hospital-dashboard container-fluid px-2 px-md-3"
      :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'"
    >
      <!-- مؤشر التحميل الأولي -->
      <div v-if="loading && !dashboardData.stats" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">{{ t("loading") }}</span>
        </div>
        <p class="text-muted mt-2 fs-8">{{ t("loading") }}</p>
      </div>

      <template v-else>
        <!-- شريط العنوان الإحصائي العلوي -->
        <div
          class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-2"
        >
          <h4 class="fw-bold text-dark mb-0 fs-5 fs-md-4">{{ t("dashboardTitle") }}</h4>
          <div class="d-flex align-items-center gap-2">
            <span
              class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fs-8 fw-bold border border-danger-subtle"
            >
              <i class="bi bi-exclamation-octagon-fill me-1"></i>
              {{ t("criticalBadge") }} {{ dashboardData.stats?.critical_cases || 0 }}
            </span>
          </div>
        </div>

        <!-- 1. المربعات الإحصائية الأربعة -->
        <StatsCards :stats="dashboardData.stats || {}" />

        <!-- 2. قسم توزيع فصائل الدم + مخطط الطلبات الشهري -->
        <div class="row g-3 g-md-4 mb-3 mb-md-4">
          <div class="col-12 col-lg-5">
            <BloodDistribution :distribution="dashboardData.blood_distribution || []" />
          </div>
          <div class="col-12 col-lg-7">
            <MonthlyRequestsChart :chart-data="dashboardData.monthly_requests || []" />
          </div>
        </div>

        <!-- 3. تنبيهات المخزون الفورية + توقعات الذكاء الاصطناعي -->
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
import { ref, onMounted, onUnmounted } from "vue";
import apiClient from "@/api/axios";
import HospitalLayout from "@/layouts/HospitalLayout.vue";
import StatsCards from "@/components/hospital/dashboard/StatsCards.vue";
import BloodDistribution from "@/components/hospital/dashboard/BloodDistribution.vue";
import MonthlyRequestsChart from "@/components/hospital/dashboard/MonthlyRequestsChart.vue";
import StockAlerts from "@/components/hospital/dashboard/StockAlerts.vue";
import AiPredictions from "@/components/hospital/dashboard/AiPredictions.vue";

const currentLocale = ref(localStorage.getItem("musaef_lang") || "ar");
let pollingTimer = null;

const updateLocale = () => {
  currentLocale.value = localStorage.getItem("musaef_lang") || "ar";
};

const dictionary = {
  ar: {
    dashboardTitle: "الإحصائيات العامة للمستشفى",
    criticalBadge: "الحالات الحرجة النشطة:",
    loading: "جاري تحميل بيانات لوحة تحكم المستشفى...",
  },
  en: {
    dashboardTitle: "Hospital General Statistics",
    criticalBadge: "Active Critical Cases:",
    loading: "Loading hospital dashboard data...",
  },
};

const t = (key) => dictionary[currentLocale.value === "en" ? "en" : "ar"][key] || key;

const loading = ref(true);
const dashboardData = ref({});

const fetchDashboardData = async (isBackground = false) => {
  if (!isBackground) loading.value = true;
  try {
    const res = await apiClient.get("/hospital/dashboard");
    if (res && res.data && res.data.data) {
      dashboardData.value = res.data.data;
    }
  } catch (error) {
    console.error("Error fetching hospital dashboard data:", error);
  } finally {
    loading.value = false;
  }
};

const handleDonorActivity = () => {
  fetchDashboardData(true);
};

onMounted(() => {
  window.addEventListener("storage", updateLocale);
  window.addEventListener("language-changed", updateLocale);
  window.addEventListener("donor-activity-updated", handleDonorActivity);

  fetchDashboardData();

  pollingTimer = setInterval(() => {
    fetchDashboardData(true);
  }, 5000);
});

onUnmounted(() => {
  window.removeEventListener("storage", updateLocale);
  window.removeEventListener("language-changed", updateLocale);
  window.removeEventListener("donor-activity-updated", handleDonorActivity);

  if (pollingTimer) clearInterval(pollingTimer);
});
</script>

<style scoped>
.hospital-dashboard {
  padding-bottom: 20px;
  overflow-x: hidden;
}
.bg-danger-subtle {
  background-color: #fee2e2 !important;
}
.fs-8 {
  font-size: 0.8rem;
}
</style>
