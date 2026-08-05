<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white flex-grow-1" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <h6 class="fw-bold text-dark mb-3 fs-7">{{ t('title') }}</h6>
    <div class="d-flex flex-column gap-2 mb-3 fs-8">
      <div class="d-flex align-items-center justify-content-between p-1 border-bottom-dashed">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <span class="text-truncate">{{ translateName('أحمد خالد', 'Ahmed Khaled') }}</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9 flex-shrink-0">+O</span>
        </div>
        <small class="text-muted fs-9 flex-shrink-0">{{ t('time1024') }}</small>
      </div>

      <div class="d-flex align-items-center justify-content-between p-1 border-bottom-dashed">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <span class="text-truncate">{{ translateName('سارة القحطاني', 'Sara Al-Qahtani') }}</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9 flex-shrink-0">+A</span>
        </div>
        <small class="text-muted fs-9 flex-shrink-0">{{ t('time0958') }}</small>
      </div>

      <div class="d-flex align-items-center justify-content-between p-1 border-bottom-dashed">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <span class="text-truncate">{{ translateName('فهد المطيري', 'Fahad Al-Mutairi') }}</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9 flex-shrink-0">+B</span>
        </div>
        <small class="text-muted fs-9 flex-shrink-0">{{ t('time0915') }}</small>
      </div>

      <div class="d-flex align-items-center justify-content-between p-1 border-bottom-dashed">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <span class="text-truncate">{{ translateName('ريم الشمري', 'Reem Al-Shammari') }}</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9 flex-shrink-0">+AB</span>
        </div>
        <small class="text-muted fs-9 flex-shrink-0">{{ t('time0847') }}</small>
      </div>

      <div class="d-flex align-items-center justify-content-between p-1">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <span class="text-truncate">{{ translateName('عبدالله القحطاني', 'Abdullah Al-Qahtani') }}</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9 flex-shrink-0">-O</span>
        </div>
        <small class="text-muted fs-9 flex-shrink-0">{{ t('time0830') }}</small>
      </div>
    </div>
    <button class="btn btn-light bg-light text-secondary btn-sm w-100 rounded-pill fs-8 mt-auto fw-bold" @click="handleViewAllOperations">
      {{ t('viewAllBtn') }}
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    title: 'عمليات التبرع الأخيرة',
    viewAllBtn: 'عرض جميع العمليات',
    time1024: '10:24 ص',
    time0958: '09:58 ص',
    time0915: '09:15 ص',
    time0847: '08:47 ص',
    time0830: '08:30 ص'
  },
  en: {
    title: 'Recent Donation Operations',
    viewAllBtn: 'View All Operations',
    time1024: '10:24 AM',
    time0958: '09:58 AM',
    time0915: '09:15 AM',
    time0847: '08:47 AM',
    time0830: '08:30 AM'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;
const translateName = (arName, enName) => currentLanguage.value === 'en' ? enName : arName;

const handleViewAllOperations = () => {
  alert(currentLanguage.value === 'en'
    ? "📋 Opening complete donation operations log archive..."
    : "📋 جاري فتح سجل عمليات التبرع بالكامل للاطلاع على الأرشيف التفصيلي...");
};
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.border-bottom-dashed { border-bottom: 1px dashed #e5e7eb; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
