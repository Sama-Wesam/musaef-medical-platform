<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-start d-flex flex-column justify-content-between position-relative" :dir="langStore.dir">
    <!-- نافذة إشعار مخصصة أنيقة -->
    <transition name="fade">
      <div
        v-if="toast.show"
        class="toast-banner position-absolute top-0 start-0 end-0 p-2.5 rounded-top-4 text-center fs-9 fw-bold z-3 bg-dark text-white shadow-sm"
      >
        <span>{{ toast.message }}</span>
        <button type="button" class="btn-close btn-close-white ms-2 fs-9 align-middle" @click="toast.show = false"></button>
      </div>
    </transition>

    <div>
      <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
        <span class="fs-5">🔔</span>
        <h5 class="fw-bold text-dark mb-0 fs-6">{{ t('title') }}</h5>
      </div>

      <!-- جدول التنبيهات -->
      <div class="table-responsive">
        <table class="table align-middle text-center border-0 fs-8 mb-0 min-w-table">
          <thead class="text-muted fw-normal bg-light-subtle">
            <tr>
              <th class="py-2 text-start">{{ t('thTime') }}</th>
              <th class="py-2 text-start">{{ t('thHospital') }}</th>
              <th class="py-2">{{ t('thType') }}</th>
              <th class="py-2 text-start">{{ t('thStatus') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(notif, idx) in alertsList" :key="idx">
              <td class="text-muted py-2.5 text-start fs-9 text-nowrap">{{ formatTime(notif.time) }}</td>
              <td class="fw-bold text-dark py-2.5 text-start text-nowrap">{{ getHospitalName(notif.hospital) }}</td>
              <td class="fw-bold text-danger py-2.5 text-nowrap">{{ notif.type }}</td>
              <td class="py-2.5 text-start text-nowrap">
                <span :class="['badge rounded-pill px-2.5 px-md-3 py-1 fs-9', notif.statusBadge]">
                  ● {{ getStatusText(notif.status) }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- زر تفاعلي لعرض جميع التنبيهات -->
    <a href="#" @click.prevent="handleViewAllAlerts" class="text-danger text-decoration-none fs-8 fw-bold mt-3 d-inline-block text-center cursor-pointer">
      {{ isLoading ? t('loading') : t('viewAll') }}
    </a>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import apiClient from '@/api/axios';
import { useLangStore } from '@/stores/langStore';

defineProps({
  alertsList: {
    type: Array,
    default: () => []
  }
});

const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: {
    title: 'آخر التنبيهات (AI Real-time)',
    thTime: 'الوقت',
    thHospital: 'المستشفى',
    thType: 'الفصيلة',
    thStatus: 'الحالة',
    viewAll: 'عرض جميع التنبيهات >',
    loading: 'جاري جلب كافة التنبيهات...',
    successToast: '🔔 تم جلب كافة سجلات التنبيهات الفورية بنجاح!',
    fallbackToast: '🔔 أرشيف التنبيهات الذكية الفورية.',
    urgent: 'عاجل',
    moderate: 'متوسط',
    stable: 'مستقر'
  },
  en: {
    title: 'Recent Alerts (AI Real-time)',
    thTime: 'Time',
    thHospital: 'Hospital',
    thType: 'Type',
    thStatus: 'Status',
    viewAll: 'View All Alerts >',
    loading: 'Fetching all alerts...',
    successToast: '🔔 Real-time AI alerts retrieved successfully!',
    fallbackToast: '🔔 Smart Real-time Alerts Archive.',
    urgent: 'Urgent',
    moderate: 'Moderate',
    stable: 'Stable'
  }
};

// قاموس لترجمة أسماء المستشفيات ديناميكياً
const hospitalNames = {
  'مستشفى ناصر': { ar: 'مستشفى ناصر', en: 'Nasser Hospital' },
  'مستشفى القدس': { ar: 'مستشفى القدس', en: 'Al-Quds Hospital' },
  'مستشفى الأوروبي': { ar: 'مستشفى الأوروبي', en: 'European Hospital' },
  'مستشفى الشفاء': { ar: 'مستشفى الشفاء', en: 'Al-Shifa Hospital' },
  'مستشفى الأندونيسي': { ar: 'مستشفى الأندونيسي', en: 'Indonesian Hospital' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const getHospitalName = (name) => {
  if (hospitalNames[name]) {
    return hospitalNames[name][currentLanguage.value === 'en' ? 'en' : 'ar'];
  }
  return name;
};

const formatTime = (timeStr) => {
  if (!timeStr) return '';
  if (currentLanguage.value === 'en') {
    return timeStr.replace('ص', 'AM').replace('م', 'PM');
  }
  return timeStr;
};

const getStatusText = (status) => {
  if (status === 'عاجل') return t('urgent');
  if (status === 'متوسط') return t('moderate');
  if (status === 'مستقر') return t('stable');
  return status;
};

const isLoading = ref(false);
const toast = ref({ show: false, message: '' });

const showNotification = (msg) => {
  toast.value = { show: true, message: msg };
  setTimeout(() => {
    toast.value.show = false;
  }, 5000);
};

const handleViewAllAlerts = async () => {
  isLoading.value = true;
  try {
    await apiClient.get('/admin/analytics/all-alerts');
    showNotification(t('successToast'));
  } catch (err) {
    showNotification(t('fallbackToast'));
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
.fs-6 { font-size: 1.05rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.cursor-pointer { cursor: pointer; }

.min-w-table {
  min-width: 380px;
}

.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.bg-light-subtle { background-color: #f9fafb !important; }

/* تأثير الانتقال للتنبيه */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
