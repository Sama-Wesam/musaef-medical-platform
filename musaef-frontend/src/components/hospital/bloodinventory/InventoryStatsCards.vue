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
          <h2 class="fw-bold text-dark mb-0 fs-3">{{ formattedStats.totalUnits }}</h2>
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
          <h2 class="fw-bold text-dark mb-0 fs-3">{{ formattedStats.validUnits }}</h2>
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
          <h2 class="fw-bold text-dark mb-0 fs-3">{{ formattedStats.lowStockUnits }}</h2>
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
          <h2 class="fw-bold text-danger mb-0 fs-3">{{ formattedStats.criticalTypesCount }}</h2>
          <small class="text-muted fs-9">{{ t('typesCount') }}</small>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({})
  }
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');
const localEmergencyCount = ref(0);

const dictionary = {
  ar: {
    totalUnits: 'إجمالي الوحدات',
    validUnits: 'الوحدات الصالحة',
    lowStock: 'مخزون منخفض',
    criticalCases: 'الحالات الحرجة',
    bloodUnit: 'وحدة دم',
    typesCount: 'حالات / فصائل'
  },
  en: {
    totalUnits: 'Total Units',
    validUnits: 'Valid Units',
    lowStock: 'Low Stock',
    criticalCases: 'Critical Cases',
    bloodUnit: 'Blood Units',
    typesCount: 'Cases / Types'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const loadLocalEmergencyRequests = () => {
  try {
    const saved = localStorage.getItem('musaef_emergency_requests');
    if (saved) {
      const parsed = JSON.parse(saved);
      if (Array.isArray(parsed)) {
        // فلترة النداءات الطارئة القائمة والنشطة
        const activeRequests = parsed.filter(req => req.status !== 'completed' && req.status !== 'مكتملة');
        localEmergencyCount.value = activeRequests.length;
      }
    }
  } catch (e) {
    console.error('Error loading local emergency requests:', e);
  }
};

const handleStorageChange = (e) => {
  if (e.key === 'musaef_emergency_requests' || !e.key) {
    loadLocalEmergencyRequests();
  }
};

onMounted(() => {
  loadLocalEmergencyRequests();
  window.addEventListener('musaef_emergency_updated', loadLocalEmergencyRequests);
  window.addEventListener('storage', handleStorageChange);
});

onUnmounted(() => {
  window.removeEventListener('musaef_emergency_updated', loadLocalEmergencyRequests);
  window.removeEventListener('storage', handleStorageChange);
});

const formattedStats = computed(() => {
  const baseCritical = props.stats?.criticalTypesCount ?? 0;
  const totalCritical = baseCritical + localEmergencyCount.value;

  return {
    totalUnits: (props.stats?.totalUnits ?? 0).toLocaleString(),
    validUnits: (props.stats?.validUnits ?? 0).toLocaleString(),
    lowStockUnits: (props.stats?.lowStockUnits ?? 0).toLocaleString(),
    criticalTypesCount: totalCritical.toLocaleString()
  };
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
