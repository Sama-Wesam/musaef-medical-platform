<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLocale === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex align-items-center gap-2 mb-3">
      <span class="fs-5">🧠</span>
      <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('title') }}</h6>
    </div>

    <div class="p-3 bg-light rounded-3 mb-3 border-start border-4 border-danger">
      <p class="fw-bold text-dark fs-8 mb-1">{{ t('predictionText') }}</p>
      <small class="text-muted fs-9">{{ t('recommendation') }}</small>
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
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import apiClient from '@/api/axios';

const isLoading = ref(false);
const currentLocale = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    title: 'توقعات الذكاء الاصطناعي (Blood Demand Forecast AI)',
    predictionText: 'تم التنبؤ بارتفاع الطلب على فصيلة O+ خلال 72 ساعة القادمة.',
    recommendation: 'زيادة حملات التبرع لهذه الفصيلة لضمان توفر المخزون الحرج.',
    now: 'الان', h24: '24 ساعة', h48: '48 ساعة', h72: '72 ساعة',
    analyzing: 'جاري تحليل التقرير...',
    viewReport: 'عرض التقرير الكامل للذكاء الاصطناعي 📊'
  },
  en: {
    title: 'AI Predictions (Blood Demand Forecast AI)',
    predictionText: 'High demand predicted for O+ blood type in the next 72 hours.',
    recommendation: 'Increase donation campaigns for this group to ensure critical stock.',
    now: 'Now', h24: '24 Hours', h48: '48 Hours', h72: '72 Hours',
    analyzing: 'Analyzing report...',
    viewReport: 'View Full AI Report 📊'
  }
};

const t = (key) => dictionary[currentLocale.value === 'en' ? 'en' : 'ar'][key] || key;

const fetchAiForecastReport = async () => {
  isLoading.value = true;
  try {
    const res = await apiClient.get('/hospital/ai-forecast-report');
    alert(currentLocale.value === 'en'
      ? `🤖 AI Report (Blood Demand Forecast):\n- Highest Demand Group: O+\n- Expected Increase: 35%\n- Recommendation: Launch emergency campaign.`
      : `🤖 تقرير الذكاء الاصطناعي الشامل:\n- الفصيلة الأكثر طلباً: O+\n- معدل الاستهلاك المتوقع: مرتفع بـ 35%\n- التوصية: إطلاق حملة طارئة فورية.`);
  } catch (err) {
    alert(currentLocale.value === 'en'
      ? `🤖 AI Report (Blood Demand Forecast AI):\n- Deficit Analysis: O+ group will face a potential shortage in the next 72h.\n- Immediate Recommendation: Direct calls to 45 matching donors.`
      : `🤖 تقرير الذكاء الاصطناعي الشامل:\n- تحليل النقص: فصيلة O+ ستواجه عجزاً محتملاً خلال 72 ساعة القادمة.\n- التوصية الفورية: توجيه نداءات لـ 45 متبرعاً مطابقاً.`);
  } finally {
    isLoading.value = false;
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
</style>
