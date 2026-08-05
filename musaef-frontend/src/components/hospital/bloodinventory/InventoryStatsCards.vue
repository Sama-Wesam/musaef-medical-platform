<template>
  <div class="row g-3 mb-4" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <!-- المربع الأول: إجمالي الوحدات -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center h-100 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span class="text-muted fs-8">{{ t('totalUnits') }}</span>
          <img src="@/assets/icons/Frame 2147225613.png" alt="Total units" class="stat-img" />
        </div>
        <div class="text-center">
          <h2 class="fw-bold text-dark mb-0 fs-3">1,248</h2>
          <small class="text-muted fs-9">{{ t('bloodUnit') }}</small>
        </div>
      </div>
    </div>

    <!-- المربع الثاني: الوحدات الصالحة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center h-100 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span class="text-muted fs-8">{{ t('validUnits') }}</span>
          <img src="@/assets/icons/Frame 2147225866.png" alt="Valid units" class="stat-img" />
        </div>
        <div class="text-center">
          <h2 class="fw-bold text-dark mb-0 fs-3">856</h2>
          <small class="text-muted fs-9">{{ t('bloodUnit') }}</small>
        </div>
      </div>
    </div>

    <!-- المربع الثالث: مخزون منخفض -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center h-100 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span class="text-muted fs-8">{{ t('lowStock') }}</span>
          <img src="@/assets/icons/Frame 2147225868.png" alt="Low stock" class="stat-img" />
        </div>
        <div class="text-center">
          <h2 class="fw-bold text-dark mb-0 fs-3">254</h2>
          <small class="text-muted fs-9">{{ t('bloodUnit') }}</small>
        </div>
      </div>
    </div>

    <!-- المربع الرابع: الحالات الحرجة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center h-100 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span class="text-muted fs-8">{{ t('criticalCases') }}</span>
          <img src="@/assets/icons/Frame 2147225871.png" alt="Critical cases" class="stat-img" />
        </div>
        <div class="text-center">
          <h2 class="fw-bold text-danger mb-0 fs-3">3</h2>
          <small class="text-muted fs-9">{{ t('typesCount') }}</small>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    totalUnits: 'إجمالي الوحدات',
    validUnits: 'الوحدات الصالحة',
    lowStock: 'مخزون منخفض',
    criticalCases: 'الحالات الحرجة',
    bloodUnit: 'وحدة دم',
    typesCount: 'فصائل'
  },
  en: {
    totalUnits: 'Total Units',
    validUnits: 'Valid Units',
    lowStock: 'Low Stock',
    criticalCases: 'Critical Cases',
    bloodUnit: 'Blood Units',
    typesCount: 'Types'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

defineProps({
  stats: {
    type: Object,
    default: () => ({})
  }
});
</script>

<style scoped>
.fs-8 { font-size: 0.85rem; }
.fs-9 { font-size: 0.75rem; }

.stat-img {
  width: 40px;
  height: 40px;
}

@media (min-width: 768px) {
  .stat-img {
    width: 48px;
    height: 48px;
  }
}
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
