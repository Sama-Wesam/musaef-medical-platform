<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 fs-9 fw-bold">
        {{ t('emergency.cards.priority') }}
      </span>
      <span class="text-muted fs-9">{{ request.created_at || t('emergency.cards.justNow') }}</span>
    </div>
    <h5 class="fw-bold text-dark mb-1 fs-6">{{ translateHospital(request.hospital_name) }}</h5>
    <p class="text-muted fs-8 mb-3">📍 {{ translateLocation(request.location) }}</p>

    <div class="row g-2 text-center bg-light p-2.5 rounded-3 fs-8 mb-2">
      <div class="col-6 border-end">
        <small class="text-muted d-block fs-9">{{ t('emergency.cards.requiredBloodType') }}</small>
        <strong class="text-danger fs-6" dir="ltr">{{ request.bloodType || request.blood_type }}</strong>
      </div>
      <div class="col-6">
        <small class="text-muted d-block fs-9">{{ t('emergency.cards.requiredUnits') }}</small>
        <strong class="text-dark fs-6">{{ request.units || request.units_required }} {{ t('emergency.cards.units') }}</strong>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    "emergency.cards.priority": "أولوية قصوى (Emergency Priority AI)",
    "emergency.cards.justNow": "منذ قليل",
    "emergency.cards.requiredBloodType": "الفصيلة المطلوبة",
    "emergency.cards.requiredUnits": "الوحدات المطلوبة",
    "emergency.cards.units": "وحدات"
  },
  en: {
    "emergency.cards.priority": "Emergency Priority AI",
    "emergency.cards.justNow": "Just now",
    "emergency.cards.requiredBloodType": "Required Blood Type",
    "emergency.cards.requiredUnits": "Required Units",
    "emergency.cards.units": "Units"
  }
};

const hospitalDict = {
  'مستشفى الشفاء الطبي': 'Al-Shifa Medical Complex',
  'مستشفى القدس الطبي': 'Al-Quds Medical Hospital',
  'مستشفى العودة': 'Al-Awda Hospital'
};

const locationDict = {
  'غزة - الرمال': 'Gaza - Rimal',
  'غزة - تل الهوى': 'Gaza - Tel Al-Hawa',
  'شمال غزة': 'North Gaza'
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;
const translateHospital = (h) => currentLanguage.value === 'en' ? (hospitalDict[h] || h) : h;
const translateLocation = (l) => currentLanguage.value === 'en' ? (locationDict[l] || l) : l;

defineProps({
  request: {
    type: Object,
    required: true
  }
});
</script>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
