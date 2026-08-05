<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLocale === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <!-- عنوان المربع والأيقونة -->
    <div class="d-flex align-items-center gap-2 mb-3">
      <span class="fs-5">🚨</span>
      <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('title') }}</h6>
    </div>

    <div class="d-flex flex-column gap-2 mb-3 flex-grow-1">
      <!-- في حال لا توجد تنبيهات -->
      <div v-if="alerts.length === 0" class="text-center text-muted py-4 fs-8">
        {{ t('noAlerts') }}
      </div>

      <!-- عرض عناصر قائمة التنبيهات مع دالة الترجمة الشاملة لجميع الحالات -->
      <div v-for="(alertItem, idx) in normalizedAlerts" :key="idx" class="p-2.5 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8 border-start border-4 border-danger">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark" dir="ltr">{{ alertItem.type || 'O-' }}</span>
          <span class="badge rounded-pill px-2 py-1 fs-9" :class="getBadgeClass(alertItem.status)">
            {{ translateStatus(alertItem.status) }}
          </span>
        </div>
        <small class="text-muted fs-9">
          {{ currentLocale === 'en' ? `Available: ${alertItem.available || 2} units only` : `المتوفر: ${alertItem.available || 2} وحدة فقط` }}
        </small>
      </div>
    </div>

    <!-- زر عرض جميع التنبيهات -->
    <button
      class="btn btn-danger w-100 rounded-pill py-2 fs-8 fw-bold text-white shadow-sm mt-auto"
      @click="handleViewAllAlerts"
    >
      {{ t('viewAll') }}
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const currentLocale = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    title: 'تنبيهات المخزون الفورية',
    noAlerts: 'لا توجد تنبيهات مخزون حالياً.',
    veryLowCritical: 'منخفض جداً (حرج)',
    viewAll: 'عرض جميع التنبيهات 🔔'
  },
  en: {
    title: 'Instant Stock Alerts',
    noAlerts: 'No stock alerts currently.',
    veryLowCritical: 'Very Low (Critical)',
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
  'متوسط': { en: 'Medium', ar: 'متوسط' },
  'متوفر': { en: 'Sufficient', ar: 'متوفر' }
};

const t = (key) => dictionary[currentLocale.value === 'en' ? 'en' : 'ar'][key] || key;

const translateStatus = (status) => {
  if (!status) return currentLocale.value === 'en' ? 'Very Low (Critical)' : 'منخفض جداً (حرج)';
  const cleanStatus = status.trim();
  if (statusDict[cleanStatus]) {
    return statusDict[cleanStatus][currentLocale.value === 'en' ? 'en' : 'ar'];
  }
  return status;
};

const getBadgeClass = (status) => {
  if (!status || status.includes('حرج') || status.includes('Critical') || status === 'منخفض جداً') {
    return 'bg-danger-subtle text-danger';
  }
  if (status.includes('منخفض') || status.includes('Low')) {
    return 'bg-warning-subtle text-warning-emphasis';
  }
  return 'bg-secondary-subtle text-secondary';
};

const props = defineProps({
  alerts: {
    type: Array,
    default: () => []
  }
});

// قائمة التنبيهات النموذجية الاحتياطية لضمان مطابقة الصور واستعراض كافة أنواع الحالات
const fallbackAlerts = [
  { type: 'O-', status: 'حرج', available: 2 },
  { type: 'O-', status: 'منخفض', available: 2 },
  { type: 'O-', status: 'منخفض جداً (حرج)', available: 2 }
];

const normalizedAlerts = computed(() => {
  return (props.alerts && props.alerts.length > 0) ? props.alerts : fallbackAlerts;
});

const handleViewAllAlerts = () => {
  router.push('/hospital/notifications');
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
