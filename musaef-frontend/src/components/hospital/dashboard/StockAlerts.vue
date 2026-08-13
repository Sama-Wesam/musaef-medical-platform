<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLocale === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div class="d-flex align-items-center gap-2">
        <span class="fs-5">🚨</span>
        <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('title') }}</h6>
      </div>
      <span class="badge bg-danger text-white rounded-pill fs-10 px-2 py-0.5">
        {{ normalizedAlerts.length }} {{ currentLocale === 'en' ? 'Alerts' : 'تنبيهات' }}
      </span>
    </div>

    <div class="d-flex flex-column gap-2 mb-3 flex-grow-1">
      <div v-if="normalizedAlerts.length === 0" class="text-center text-muted py-4 fs-8">
        {{ t('noAlerts') }}
      </div>

      <!-- عناصر التنبيهات من قاعدة البيانات -->
      <div
        v-for="(alertItem, idx) in normalizedAlerts"
        :key="idx"
        class="p-2.5 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8 border-start border-4 alert-item-hover cursor-pointer"
        :class="getBorderClass(alertItem.status)"
        @click="handleAlertClick(alertItem)"
      >
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark" dir="ltr">{{ alertItem.blood_type || alertItem.type || 'O-' }}</span>
          <span class="badge rounded-pill px-2 py-1 fs-9" :class="getBadgeClass(alertItem.status)">
            {{ translateStatus(alertItem.status) }}
          </span>
        </div>
        <div class="d-flex align-items-center gap-1">
          <small class="text-muted fs-9">
            {{ currentLocale === 'en' ? `Available: ${alertItem.units ?? alertItem.available ?? 0} units` : `المتوفر: ${alertItem.units ?? alertItem.available ?? 0} وحدة` }}
          </small>
          <i class="bi bi-chevron-left fs-9 text-secondary" :class="{ 'rotate-180': currentLocale === 'en' }"></i>
        </div>
      </div>
    </div>

    <button
      class="btn btn-danger w-100 rounded-pill py-2 fs-8 fw-bold text-white shadow-sm mt-auto transition-all"
      @click="handleViewAllAlerts"
    >
      {{ t('viewAll') }}
    </button>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const props = defineProps({
  alerts: {
    type: Array,
    default: () => []
  }
});

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

const dictionary = {
  ar: {
    title: 'تنبيهات المخزون الفورية',
    noAlerts: 'لا توجد تنبيهات مخزون حالياً.',
    viewAll: 'عرض جميع التنبيهات 🔔'
  },
  en: {
    title: 'Instant Stock Alerts',
    noAlerts: 'No stock alerts currently.',
    viewAll: 'View All Alerts 🔔'
  }
};

const statusDict = {
  'حرج': { en: 'Critical', ar: 'حرج' },
  'حرجة': { en: 'Critical', ar: 'حرجة' },
  'منخفض': { en: 'Low', ar: 'منخفض' },
  'منخفضة': { en: 'Low', ar: 'منخفضة' },
  'منخفض جداً': { en: 'Very Low', ar: 'منخفض جداً' },
  'منخفض جداً (حرج)': { en: 'Very Low (Critical)', ar: 'منخفض جداً (حرج)' },
  'Critical': { en: 'Critical', ar: 'حرج' },
  'Low': { en: 'Low', ar: 'منخفض' }
};

const t = (key) => dictionary[currentLocale.value === 'en' ? 'en' : 'ar'][key] || key;

const translateStatus = (status) => {
  if (!status) return currentLocale.value === 'en' ? 'Low' : 'منخفض';
  const cleanStatus = status.toString().trim();
  return statusDict[cleanStatus] ? statusDict[cleanStatus][currentLocale.value === 'en' ? 'en' : 'ar'] : status;
};

const getBadgeClass = (status) => {
  const str = String(status || '');
  if (str.includes('حرج') || str.includes('Critical') || str.includes('منخفض جداً')) {
    return 'bg-danger-subtle text-danger';
  }
  return 'bg-warning-subtle text-warning-emphasis';
};

const getBorderClass = (status) => {
  const str = String(status || '');
  if (str.includes('حرج') || str.includes('Critical') || str.includes('منخفض جداً')) {
    return 'border-danger';
  }
  return 'border-warning';
};

const normalizedAlerts = computed(() => {
  return props.alerts || [];
});

const handleAlertClick = (alertItem) => {
  router.push({ path: '/hospital/notifications', query: { type: alertItem.blood_type || alertItem.type } });
};

const handleViewAllAlerts = () => {
  router.push('/hospital/notifications');
};
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.fs-10 { font-size: 0.65rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.text-warning-emphasis { color: #b45309 !important; }
.border-danger { border-color: #dc2626 !important; }
.border-warning { border-color: #f59e0b !important; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }

.alert-item-hover {
  transition: all 0.2s ease;
}
.alert-item-hover:hover {
  background-color: #f1f5f9 !important;
  transform: translateX(-3px);
}
.dir-ltr .alert-item-hover:hover {
  transform: translateX(3px);
}
.rotate-180 { transform: rotate(180deg); }
.cursor-pointer { cursor: pointer; }
</style>
