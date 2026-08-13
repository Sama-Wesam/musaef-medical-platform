<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <h5 class="fw-bold text-dark mb-3 border-bottom pb-3 fs-6 fs-md-5 d-flex align-items-center">
      <i class="bi bi-gear text-danger" :class="currentLanguage === 'ar' ? 'me-2' : 'me-2'"></i> {{ t('accountSettings') }}
    </h5>
    <div class="p-3 bg-light rounded-3 mb-3">
      <h6 class="fw-bold text-dark fs-8 mb-2">{{ t('notificationsAvailability') }}</h6>
      <div class="form-check form-switch d-flex align-items-center justify-content-between p-0 flex-wrap gap-2" dir="ltr">
        <label class="form-check-label fs-8 text-secondary flex-grow-1 min-w-0" :class="currentLanguage === 'ar' ? 'text-end me-2' : 'text-start'" for="notifSwitch">{{ t('receiveUrgentNotifs') }}</label>
        <input
          class="form-check-input ms-0 custom-switch flex-shrink-0"
          type="checkbox"
          id="notifSwitch"
          v-model="isAvailable"
          @change="updateAvailability"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import apiClient from '@/api/axios';

const props = defineProps({
  settings: {
    type: Object,
    default: () => ({ is_available: true })
  }
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: {
    accountSettings: 'إعدادات الحساب',
    notificationsAvailability: 'الإشعارات والتنبيهات والتوفر',
    receiveUrgentNotifs: 'استقبال إشعارات وتنبيهات الحالات العاجلة (متاح للتبرع)',
    successUpdate: 'تم تحديث حالة التوفر والإشعارات بنجاح!',
    errorUpdate: 'حدث خطأ أثناء حفظ الإعدادات.'
  },
  en: {
    accountSettings: 'Account Settings',
    notificationsAvailability: 'Notifications, Alerts & Availability',
    receiveUrgentNotifs: 'Receive urgent case notifications and alerts (Available to donate)',
    successUpdate: 'Availability and notifications updated successfully!',
    errorUpdate: 'An error occurred while saving settings.'
  }
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const isAvailable = ref(props.settings?.is_available ?? true);

watch(() => props.settings, (newVal) => {
  if (newVal) {
    isAvailable.value = newVal.is_available ?? true;
  }
}, { deep: true });

const updateAvailability = async () => {
  try {
    await apiClient.post('/donor/profile/update', { is_available: isAvailable.value });
    alert(t('successUpdate'));
  } catch (error) {
    console.error('خطأ في تحديث الإعدادات:', error);
    alert(t('errorUpdate'));
  }
};
</script>

<style scoped>
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
.custom-switch { accent-color: #dc2626; width: 40px; height: 20px; cursor: pointer; }
.fs-8 { font-size: 0.82rem; }
</style>
