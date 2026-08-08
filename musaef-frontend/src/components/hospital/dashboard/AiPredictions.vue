<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLocale === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex align-items-center gap-2 mb-3">
      <span class="fs-5">🧠</span>
      <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('title') }}</h6>
    </div>

    <!-- عرض بيانات التنبؤ القادمة ديناميكياً -->
    <div class="p-3 bg-light rounded-3 mb-3 border-start border-4 border-danger">
      <p class="fw-bold text-dark fs-8 mb-1">
        {{ prediction?.title || t('predictionText') }}
      </p>
      <small class="text-muted fs-9">
        {{ prediction?.description || t('recommendation') }}
      </small>
    </div>

    <div class="position-relative mb-3" style="height: 90px;">
      <svg class="w-100 h-100" viewBox="0 0 300 80" preserveAspectRatio="none">
        <path d="M 10,60 Q 75,65 150,55 T 290,15" fill="none" stroke="#DC2626" stroke-width="3" stroke-dasharray="4" />
        <circle cx="290" cy="15" r="5" fill="#DC2626" />
      </svg>
      <div class="d-flex justify-content-between text-muted fs-10 px-1">
        <span>{{ t('now') }}</span>
        <span>{{ t('h24') }}</span>
        <span>{{ t('h48') }}</span>
        <span>{{ t('h72') }}</span>
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

    <!-- Modal التقرير الشامل لملف الذكاء الاصطناعي -->
    <div v-if="showModal" class="modal-backdrop-custom d-flex align-items-center justify-content-center">
      <div class="modal-card bg-white p-4 rounded-4 shadow-lg text-start" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'" style="max-width: 500px; width: 90%;">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-danger fs-6">🤖 {{ t('modalTitle') }}</h5>
          <button type="button" class="btn-close" @click="showModal = false"></button>
        </div>
        <hr class="my-2" />
        <div class="my-3 fs-8 text-secondary">
          <p class="mb-2"><strong>{{ t('targetGroup') }}:</strong> <span class="badge bg-danger">{{ reportData?.predicted_group || prediction?.predicted_group || 'O-' }}</span></p>
          <p class="mb-2"><strong>{{ t('title') }}:</strong> {{ reportData?.title || prediction?.title }}</p>
          <p class="mb-0"><strong>{{ t('recommendation') }}:</strong> {{ reportData?.description || prediction?.description }}</p>
        </div>
        <div class="mt-4 text-end">
          <button class="btn btn-secondary btn-sm rounded-pill px-4" @click="showModal = false">{{ t('close') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import apiClient from '@/api/axios';

const props = defineProps({
  prediction: {
    type: Object,
    default: () => ({})
  }
});

const isLoading = ref(false);
const showModal = ref(false);
const reportData = ref(null);
const currentLocale = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    title: 'توقعات الذكاء الاصطناعي (Blood Demand Forecast AI)',
    predictionText: 'تم التنبؤ بارتفاع الطلب على فصيلة O+ خلال 72 ساعة القادمة.',
    recommendation: 'زيادة حملات التبرع لهذه الفصيلة لضمان توفر المخزون الحرج.',
    now: 'الان', h24: '24 ساعة', h48: '48 ساعة', h72: '72 ساعة',
    analyzing: 'جاري تحليل التقرير...',
    viewReport: 'عرض التقرير الكامل للذكاء الاصطناعي 📊',
    modalTitle: 'تقرير الذكاء الاصطناعي الشامل',
    targetGroup: 'الفصيلة المستهدفة بالنقص',
    close: 'إغلاق'
  },
  en: {
    title: 'AI Predictions (Blood Demand Forecast AI)',
    predictionText: 'High demand predicted for O+ blood type in the next 72 hours.',
    recommendation: 'Increase donation campaigns for this group to ensure critical stock.',
    now: 'Now', h24: '24 Hours', h48: '48 Hours', h72: '72 Hours',
    analyzing: 'Analyzing report...',
    viewReport: 'View Full AI Report 📊',
    modalTitle: 'Comprehensive AI Report',
    targetGroup: 'Targeted Shortage Blood Type',
    close: 'Close'
  }
};

const t = (key) => dictionary[currentLocale.value === 'en' ? 'en' : 'ar'][key] || key;

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
.fs-9 { font-size: 0.72rem; }
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
</style>
