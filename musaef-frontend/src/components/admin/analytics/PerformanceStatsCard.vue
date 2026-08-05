<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-start" :dir="langStore.dir">
    <h5 class="fw-bold text-dark mb-3 mb-md-4 fs-6 text-center">{{ t('title') }}</h5>

    <div class="d-flex flex-column gap-2.5 gap-md-3">
      <!-- متوسط وقت الاستجابة -->
      <div class="p-2.5 p-md-3 bg-light-subtle rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
          <img :src="getIconUrl('mingcute_time-line.png')" alt="time icon" width="32" height="32" class="flex-shrink-0" />
          <div class="text-start min-w-0">
            <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('avgResponseTime') }}</span>
            <small class="text-muted fs-9 text-truncate d-block">{{ t('hosp1') }}</small>
          </div>
        </div>
        <small class="text-muted fs-9 fw-bold text-nowrap ms-auto ms-sm-0">{{ formatResponseTime(performance?.avg_response_time) }}</small>
      </div>

      <!-- نسبة تلبية الطلبات -->
      <div class="p-2.5 p-md-3 bg-light-subtle rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
          <img :src="getIconUrl('ei_check.png')" alt="check icon" width="32" height="32" class="flex-shrink-0" />
          <div class="text-start min-w-0">
            <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('fulfillmentRate') }}</span>
            <small class="text-muted fs-9 text-truncate d-block">{{ t('hosp2') }}</small>
          </div>
        </div>
        <small class="text-muted fs-9 fw-bold text-nowrap ms-auto ms-sm-0">{{ performance?.fulfillment_rate || '92.6%' }}</small>
      </div>

      <!-- معدل التبرع اليومي -->
      <div class="p-2.5 p-md-3 bg-light-subtle rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
          <img :src="getIconUrl('mdi_drop.png')" alt="drop icon" width="32" height="32" class="flex-shrink-0" />
          <div class="text-start min-w-0">
            <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('dailyRate') }}</span>
            <small class="text-muted fs-9 text-truncate d-block">{{ t('donorSample') }}</small>
          </div>
        </div>
        <small class="text-muted fs-9 fw-bold text-nowrap ms-auto ms-sm-0">{{ formatDailyRate(performance?.daily_donation_rate) }}</small>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useLangStore } from '@/stores/langStore';

defineProps({
  performance: {
    type: Object,
    default: () => ({})
  }
});

const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: {
    title: 'إحصائيات الأداء',
    avgResponseTime: 'متوسط وقت الاستجابة',
    hosp1: 'مستشفى شهداء الأقصى - دير البلح',
    fulfillmentRate: 'نسبة تلبية الطلبات',
    hosp2: 'المستشفى الأمريكي - غزة',
    dailyRate: 'معدل التبرع اليومي',
    donorSample: 'المتبرع : أحمد محمد',
    minutesUnit: 'دقيقة',
    unitsWord: 'وحدة'
  },
  en: {
    title: 'Performance Statistics',
    avgResponseTime: 'Avg. Response Time',
    hosp1: 'Al-Aqsa Martyrs Hospital - Deir al-Balah',
    fulfillmentRate: 'Order Fulfillment Rate',
    hosp2: 'American Field Hospital - Gaza',
    dailyRate: 'Daily Donation Rate',
    donorSample: 'Donor: Ahmed Mohamed',
    minutesUnit: 'min',
    unitsWord: 'units'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

// دالة لتكييف صيغة وقت الاستجابة بحسب اللغة
const formatResponseTime = (timeStr) => {
  const defaultVal = currentLanguage.value === 'en' ? '18:24 min' : '18:24 دقيقة';
  if (!timeStr) return defaultVal;
  if (currentLanguage.value === 'en') {
    return timeStr.replace('دقيقة', 'min');
  }
  return timeStr.replace('min', 'دقيقة');
};

// دالة لتكييف صيغة عدد الوحدات بحسب اللغة
const formatDailyRate = (rateStr) => {
  const defaultVal = currentLanguage.value === 'en' ? '1,234 units' : '1,234 وحدة';
  if (!rateStr) return defaultVal;
  if (currentLanguage.value === 'en') {
    return rateStr.replace('وحدة', 'units');
  }
  return rateStr.replace('units', 'وحدة');
};

const getIconUrl = (fileName) => {
  if (!fileName) return '';
  if (fileName.startsWith('http') || fileName.startsWith('data:')) return fileName;
  try {
    return new URL(`../../../assets/icons/${fileName}`, import.meta.url).href;
  } catch (e) {
    return '';
  }
};
</script>

<style scoped>
.fs-6 { font-size: 1.05rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }

.bg-light-subtle { background-color: #f9fafb !important; }
</style>
