<template>
  <div class="row g-3 mb-3 mb-md-4" :dir="langStore.dir">
    <!-- 1.1 الحالات الحرجة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-start h-100 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">{{ t('criticalCases') }}</span>
          <div class="icon-badge bg-purple-subtle rounded-circle p-2 d-flex align-items-center justify-content-center">
            <img :src="getIconUrl('Frame 2147225613 (2).png')" :alt="t('criticalCases')" class="stat-icon" />
          </div>
        </div>
        <h3 class="fw-black text-dark mb-1 fs-3">{{ kpi?.critical_cases || 236 }}</h3>
        <div class="d-flex align-items-center gap-1 text-danger fs-9 fw-bold flex-wrap">
          <span>+18.6%</span>
          <span class="text-muted fw-normal">{{ t('fromLastMonth') }}</span>
        </div>
      </div>
    </div>

    <!-- 1.2 نسبة الاستجابة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-start h-100 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">{{ t('responseRate') }}</span>
          <div class="icon-badge bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center">
            <img :src="getIconUrl('Frame 2147225866 (2).png')" :alt="t('responseRate')" class="stat-icon" />
          </div>
        </div>
        <h3 class="fw-black text-dark mb-1 fs-3">{{ kpi?.response_rate || '92.7%' }}</h3>
        <div class="d-flex align-items-center gap-1 text-success fs-9 fw-bold flex-wrap">
          <span>+7.6%</span>
          <span class="text-muted fw-normal">{{ t('fromLastMonth') }}</span>
        </div>
      </div>
    </div>

    <!-- 1.3 إجمالي الطلبات -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-start h-100 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">{{ t('totalRequests') }}</span>
          <div class="icon-badge bg-danger-subtle text-danger rounded-circle p-2 d-flex align-items-center justify-content-center">
            <img :src="getIconUrl('Frame 2147225871 (2).png')" :alt="t('totalRequests')" class="stat-icon" />
          </div>
        </div>
        <h3 class="fw-black text-dark mb-1 fs-3">{{ kpi?.total_requests || '1,248' }}</h3>
        <div class="d-flex align-items-center gap-1 text-danger fs-9 fw-bold flex-wrap">
          <span>+14.5%</span>
          <span class="text-muted fw-normal">{{ t('fromLastMonth') }}</span>
        </div>
      </div>
    </div>

    <!-- 1.4 إجمالي المتبرعين -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-start h-100 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">{{ t('totalDonors') }}</span>
          <div class="icon-badge bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center">
            <img :src="getIconUrl('Frame 2147225868 (2).png')" :alt="t('totalDonors')" class="stat-icon" />
          </div>
        </div>
        <h3 class="fw-black text-dark mb-1 fs-3">{{ kpi?.total_donors || '8,765' }}</h3>
        <div class="d-flex align-items-center gap-1 text-success fs-9 fw-bold flex-wrap">
          <span>+12.4%</span>
          <span class="text-muted fw-normal">{{ t('fromLastMonth') }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useLangStore } from '@/stores/langStore';

defineProps({
  kpi: {
    type: Object,
    default: () => ({})
  }
});

const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: {
    criticalCases: 'الحالات الحرجة',
    responseRate: 'نسبة الاستجابة',
    totalRequests: 'إجمالي الطلبات',
    totalDonors: 'إجمالي المتبرعين',
    fromLastMonth: 'عن الشهر السابق'
  },
  en: {
    criticalCases: 'Critical Cases',
    responseRate: 'Response Rate',
    totalRequests: 'Total Requests',
    totalDonors: 'Total Donors',
    fromLastMonth: 'vs last month'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

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
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.fw-black { font-weight: 900; }

.icon-badge {
  width: 36px;
  height: 36px;
  flex-shrink: 0;
}

@media (min-width: 768px) {
  .icon-badge {
    width: 38px;
    height: 38px;
  }
}

.stat-icon {
  width: 20px;
  height: 20px;
}

@media (min-width: 768px) {
  .stat-icon {
    width: 22px;
    height: 22px;
  }
}

.bg-purple-subtle { background-color: #f3e8ff !important; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-primary-subtle { background-color: #dbeafe !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
</style>
