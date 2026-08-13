<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 d-flex flex-column" :class="currentLocale === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div class="d-flex align-items-center gap-2">
        <span class="fs-5">🧠</span>
        <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('title') }}</h6>
      </div>
      <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill fs-10 px-2 py-0.5">
        AI Live Forecast
      </span>
    </div>

    <!-- عرض بيانات التنبؤ المباشر -->
    <div class="p-3 bg-light rounded-3 mb-3 border-start border-4 border-danger">
      <p class="fw-bold text-dark fs-8 mb-1">
        {{ displayTitle }}
      </p>
      <small class="text-muted fs-9 d-block">
        {{ displayDescription }}
      </small>
    </div>

    <!-- المخطط البياني المنحني التفاعلي -->
    <div class="position-relative my-2" style="height: 85px;">
      <svg class="w-100 h-100 overflow-visible" viewBox="0 0 300 60" preserveAspectRatio="none">
        <path
          :d="isRtl ? 'M 290,48 Q 150,38 10,12' : 'M 10,48 Q 150,38 290,12'"
          fill="none"
          stroke="#DC2626"
          stroke-width="3"
          stroke-dasharray="5,5"
        />

        <circle
          v-for="(point, pIdx) in calculatedPoints"
          :key="pIdx"
          :cx="point.cx"
          :cy="point.cy"
          :r="activePoint === pIdx ? '6.5' : '4.5'"
          :fill="activePoint === pIdx ? '#991b1b' : '#DC2626'"
          class="cursor-pointer transition-all"
          @mouseenter="activePoint = pIdx"
          @mouseleave="activePoint = null"
        >
          <title>{{ point.label }}: {{ point.forecastText }}</title>
        </circle>
      </svg>

      <!-- النصوص الزمنية -->
      <div class="d-flex justify-content-between text-muted fs-9 px-1 position-relative" style="margin-top: -12px; z-index: 2;">
        <span
          v-for="(point, pIdx) in calculatedPoints"
          :key="pIdx"
          class="cursor-pointer transition-all"
          :class="{ 'fw-bold text-danger': activePoint === pIdx }"
          @mouseenter="activePoint = pIdx"
          @mouseleave="activePoint = null"
        >
          {{ point.label }}
        </span>
      </div>
    </div>

    <button
      class="btn btn-outline-danger w-100 rounded-pill py-2 fs-8 fw-bold text-danger bg-white shadow-sm mt-auto"
      :disabled="isLoading"
      @click="fetchAiForecastReport"
    >
      <span v-if="isLoading" class="spinner-border spinner-border-sm me-1"></span>
      <span>{{ isLoading ? t('analyzing') : t('viewReport') }}</span>
    </button>

    <!-- Modal التقرير الشامل -->
    <div v-if="showModal" class="modal-backdrop-custom d-flex align-items-center justify-content-center">
      <div class="modal-card bg-white p-4 rounded-4 shadow-lg" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'" style="max-width: 500px; width: 90%;">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-danger fs-6">🤖 {{ t('modalTitle') }}</h5>
          <button type="button" class="btn-close" @click="showModal = false"></button>
        </div>
        <hr class="my-2" />
        <div class="my-3 fs-8 text-secondary">
          <p class="mb-2"><strong>{{ t('targetGroup') }}:</strong> <span class="badge bg-danger ms-1">{{ activePrediction?.predicted_group || 'O-' }}</span></p>
          <p class="mb-2"><strong>{{ t('titleLabel') }}:</strong> {{ displayTitle }}</p>
          <p class="mb-0"><strong>{{ t('recommendation') }}:</strong> {{ displayDescription }}</p>
        </div>
        <div class="mt-4 text-end">
          <button class="btn btn-secondary btn-sm rounded-pill px-4" @click="showModal = false">{{ t('close') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import apiClient from '@/api/axios';

const props = defineProps({
  prediction: {
    type: Object,
    default: () => ({})
  }
});

const activePoint = ref(null);
const isLoading = ref(false);
const showModal = ref(false);
const reportData = ref(null);
const currentLocale = ref(localStorage.getItem('musaef_lang') || 'ar');

const updateLocale = () => {
  currentLocale.value = localStorage.getItem('musaef_lang') || 'ar';
};

onMounted(() => {
  window.addEventListener('storage', updateLocale);
  window.addEventListener('language-changed', updateLocale);
});

onUnmounted(() => {
  window.removeEventListener('storage', updateLocale);
  window.removeEventListener('language-changed', updateLocale);
});

const isRtl = computed(() => currentLocale.value === 'ar');

const dictionary = {
  ar: {
    title: 'توقعات الذكاء الاصطناعي (Blood Demand Forecast AI)',
    now: 'الآن', h24: '24 ساعة', h48: '48 ساعة', h72: '72 ساعة',
    analyzing: 'جاري تحليل التقرير...',
    viewReport: 'عرض التقرير الكامل للذكاء الاصطناعي 📊',
    modalTitle: 'تقرير الذكاء الاصطناعي الشامل',
    targetGroup: 'الفصيلة المستهدفة للنقص',
    titleLabel: 'العنوان',
    recommendation: 'التوصية',
    close: 'إغلاق',
    defaultTitle: 'مخزون الدم في وضع مستقر',
    defaultDesc: 'التحليلات تشير لمعدلات استهلاك طبيعية ولا توجد مخاطر نقص حادة حالياً.'
  },
  en: {
    title: 'AI Predictions (Blood Demand Forecast AI)',
    now: 'Now', h24: '24 Hours', h48: '48 Hours', h72: '72 Hours',
    analyzing: 'Analyzing report...',
    viewReport: 'View Full AI Report 📊',
    modalTitle: 'Comprehensive AI Report',
    targetGroup: 'Targeted Shortage Blood Type',
    titleLabel: 'Title',
    recommendation: 'Recommendation',
    close: 'Close',
    defaultTitle: 'Blood inventory is in stable state',
    defaultDesc: 'Analytics indicate normal consumption rates with no acute shortage risks.'
  }
};

const t = (key) => dictionary[currentLocale.value === 'en' ? 'en' : 'ar'][key] || key;

const activePrediction = computed(() => reportData.value || props.prediction || {});

const displayTitle = computed(() => {
  const p = activePrediction.value;
  if (currentLocale.value === 'en') {
    return p.title_en || p.title || dictionary.en.defaultTitle;
  }
  return p.title || p.title_ar || dictionary.ar.defaultTitle;
});

const displayDescription = computed(() => {
  const p = activePrediction.value;
  if (currentLocale.value === 'en') {
    return p.description_en || p.description || dictionary.en.defaultDesc;
  }
  return p.description || p.description_ar || dictionary.ar.defaultDesc;
});

const calculatedPoints = computed(() => {
  const pointsData = activePrediction.value.timeline_points;

  if (pointsData && Array.isArray(pointsData) && pointsData.length === 4) {
    const coords = isRtl.value
      ? [{ cx: 290, cy: 48 }, { cx: 196, cy: 39 }, { cx: 103, cy: 26 }, { cx: 10, cy: 12 }]
      : [{ cx: 10, cy: 48 }, { cx: 103, cy: 39 }, { cx: 196, cy: 26 }, { cx: 290, cy: 12 }];

    return pointsData.map((pt, idx) => ({
      ...coords[idx],
      label: pt.label || (idx === 0 ? t('now') : `${idx * 24} ${t('h24')}`),
      forecastText: pt.text || pt.forecastText || ''
    }));
  }

  if (isRtl.value) {
    return [
      { cx: 290, cy: 48, label: t('now'), forecastText: 'مستقر حالياً' },
      { cx: 196, cy: 39, label: t('h24'), forecastText: 'توقع انخفاض طفيف' },
      { cx: 103, cy: 26, label: t('h48'), forecastText: 'تنسيق متوقع للحاجة' },
      { cx: 10,  cy: 12, label: t('h72'), forecastText: 'تنبيه طوارئ محتمل' }
    ];
  } else {
    return [
      { cx: 10,  cy: 48, label: t('now'), forecastText: 'Stable now' },
      { cx: 103, cy: 39, label: t('h24'), forecastText: 'Slight drop expected' },
      { cx: 196, cy: 26, label: t('h48'), forecastText: 'Demand coordination' },
      { cx: 290, cy: 12, label: t('h72'), forecastText: 'Potential alert' }
    ];
  }
});

const fetchAiForecastReport = async () => {
  isLoading.value = true;
  try {
    const res = await apiClient.get('/hospital/ai-forecast-report');
    if (res && res.data && res.data.success) {
      reportData.value = res.data.data;
    } else {
      reportData.value = props.prediction;
    }
  } catch (err) {
    reportData.value = props.prediction;
  } finally {
    isLoading.value = false;
    showModal.value = true;
  }
};
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.75rem; }
.fs-10 { font-size: 0.65rem; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }

.modal-backdrop-custom {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1050;
}

.cursor-pointer { cursor: pointer; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
</style>
