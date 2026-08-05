<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('title') }}</h6>
      <span class="badge bg-danger text-white rounded-pill px-2 py-1 fs-9">{{ t('aiBadge') }}</span>
    </div>
    <div class="d-flex flex-column gap-2 mb-3">
      <div class="p-2 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8 border-start border-4 border-danger">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark" dir="ltr">-O</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 fs-9">{{ t('veryLowCritical') }}</span>
        </div>
        <small class="text-muted fs-9">{{ t('available2Units') }}</small>
      </div>

      <div class="p-2 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8 border-start border-4 border-warning">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark" dir="ltr">-B</span>
          <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1 fs-9">{{ t('low') }}</span>
        </div>
        <small class="text-muted fs-9">{{ t('available5Units') }}</small>
      </div>

      <div class="p-2 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8 border-start border-4 border-warning">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark" dir="ltr">+A</span>
          <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1 fs-9">{{ t('low') }}</span>
        </div>
        <small class="text-muted fs-9">{{ t('available3Units') }}</small>
      </div>
    </div>
    <button class="btn btn-light bg-light text-secondary btn-sm w-100 rounded-pill fs-8 fw-bold" @click="handleEmergencyAction">
      {{ t('actionBtn') }}
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    title: 'تنبيهات فورية (Blood Demand Forecast AI)',
    aiBadge: 'ذكاء اصطناعي',
    veryLowCritical: 'منخفض جداً (حرج)',
    low: 'منخفض',
    available2Units: 'المتوفر: 2 وحدة فقط',
    available5Units: 'المتوفر: 5 وحدة',
    available3Units: 'المتوفر: 3 وحدة فقط',
    actionBtn: 'إطلاق نداء طارئ أو التواصل مع بنوك مجاورة'
  },
  en: {
    title: 'Instant Alerts (Blood Demand Forecast AI)',
    aiBadge: 'AI Powered',
    veryLowCritical: 'Very Low (Critical)',
    low: 'Low',
    available2Units: 'Available: 2 units only',
    available5Units: 'Available: 5 units',
    available3Units: 'Available: 3 units only',
    actionBtn: 'Dispatch Emergency Call or Contact Nearby Banks'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const handleEmergencyAction = () => {
  const isEn = currentLanguage.value === 'en';
  const action = confirm(isEn
    ? "Would you like to dispatch an instant emergency call to donors via Smart Matching AI, or contact nearby blood banks?\n\nClick 'OK' for Emergency Call, or 'Cancel' to contact nearby banks."
    : "هل تريد إطلاق نداء طارئ فوري للمتبرعين عبر نظام Smart Matching AI، أم التواصل مع بنوك الدم المجاورة؟\n\nاضغط 'موافق' لإطلاق النداء الطارئ، أو 'إلغاء' للتواصل مع بنوك الدم المجاورة.");
  if (action) {
    alert(isEn ? "🚨 Emergency call dispatched successfully for type (O-) and nearby donors notified!" : "🚨 تم إطلاق النداء الطارئ بنجاح لفصيلة (-O) وتم تنبيه المتبرعين القريبين!");
  } else {
    alert(isEn ? "📞 Opening communication channel with regional blood banks..." : "📞 جاري فتح نافذة الاتصال والتنسيق مع بنوك الدم الإقليمية المجاورة...");
  }
};
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.text-warning-emphasis { color: #b45309 !important; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
