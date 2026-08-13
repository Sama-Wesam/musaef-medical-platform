<template>
  <AdminLayout>
    <div class="radar-view container-fluid px-2 px-md-3" :dir="langStore.dir">
      <div class="row g-3 g-lg-4">
        <!-- 1. قائمة الحالات الحرجة المباشرة -->
        <div class="col-12 col-lg-5 col-xl-4 order-1 order-lg-1">
          <CriticalCasesList
            v-model:filter="radarStore.filter"
            :hospitals="filteredHospitals"
            @refresh="radarStore.fetchRadarData"
          />
        </div>

        <!-- 2. الخريطة الجغرافية ودليل الخطورة -->
        <div class="col-12 col-lg-7 col-xl-8 order-2 order-lg-2">
          <RadarMap :heat-data="heatMapPoints" :hospitals="filteredHospitals" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import apiClient from "@/api/axios";
import echo from "@/utils/echo";
import { useLangStore } from "@/stores/langStore";
import { useEmergencyRadarStore } from "@/stores/emergencyRadarStore";

import CriticalCasesList from "@/components/admin/liveradar/CriticalCasesList.vue";
import RadarMap from "@/components/admin/liveradar/RadarMap.vue";

const langStore = useLangStore();
const radarStore = useEmergencyRadarStore();
const currentLanguage = computed(() => langStore.currentLang);

const heatMapPoints = ref([]);

const hospitalNames = {
  "مستشفى الكويتي": { ar: "مستشفى الكويتي", en: "Kuwaiti Hospital" },
  "مستشفى العودة": { ar: "مستشفى العودة", en: "Al-Awda Hospital" },
  "مستشفى ناصر": { ar: "مستشفى ناصر", en: "Nasser Hospital" },
};

const locationNames = {
  "الجنوب - رفح": { ar: "الجنوب - رفح", en: "South - Rafah" },
  "وسطى - النصيرات": { ar: "وسطى - النصيرات", en: "Central - Nuseirat" },
  "جنوب - خانيونس": { ar: "جنوب - خانيونس", en: "South - Khan Younis" },
};

const getEtaUnit = () => (currentLanguage.value === "en" ? "mins" : "دقائق");

const filteredHospitals = computed(() => {
  return radarStore.filteredCases.map((h) => ({
    ...h,
    translatedName: hospitalNames[h.name]
      ? hospitalNames[h.name][currentLanguage.value === "en" ? "en" : "ar"]
      : h.name,
    translatedLocation: locationNames[h.location]
      ? locationNames[h.location][currentLanguage.value === "en" ? "en" : "ar"]
      : h.location,
    responseTime: `${h.responseTimeVal || 6} ${getEtaUnit()}`,
  }));
});

const fetchHeatMapData = async () => {
  try {
    const res = await apiClient.get("/admin/analytics/heat-map");
    const data = res.data?.data || res.data;
    if (Array.isArray(data)) {
      heatMapPoints.value = data;
    }
  } catch (err) {
    console.warn("استخدام إحداثيات الخريطة الحرارية المتاحة.");
  }
};

watch(
  () => radarStore.filter,
  () => {
    radarStore.fetchRadarData();
  }
);

onMounted(() => {
  radarStore.startPolling(5000);
  fetchHeatMapData();

  try {
    echo.channel("emergencies.live").listen(".new.emergency", () => {
      radarStore.fetchRadarData();
    });
  } catch (err) {
    console.warn("تعذر الاتصال بـ Echo المباشر، الاستطلاع الذاتي يعمل بدلاً عنه.");
  }
});

onUnmounted(() => {
  radarStore.stopPolling();
  try {
    echo.leaveChannel("emergencies.live");
  } catch (e) {}
});
</script>

<style scoped>
.radar-view {
  font-family: "Cairo", sans-serif;
  padding-bottom: 24px;
}
</style>
