<template>
  <div class="hospital-card p-3 rounded-4 border border-light-subtle bg-light-subtle text-start position-relative" :dir="langStore.dir">
    <!-- رسالة التنبيه المخصصة (Toast Alert) -->
    <transition name="fade">
      <div
        v-if="toast.show"
        :class="['toast-banner position-absolute top-0 start-0 end-0 p-2 rounded-top-4 text-center fs-9 fw-bold z-3', toast.type === 'success' ? 'bg-success text-white' : 'bg-danger text-white']"
      >
        {{ toast.message }}
      </div>
    </transition>

    <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-1">
      <!-- جهة البيانات: تفاصيل المستشفى والأيقونة -->
      <div class="d-flex align-items-center gap-2 min-w-0">
        <img :src="getIconUrl(hospital.icon)" :alt="hospital.translatedName || hospital.name" width="32" height="32" class="flex-shrink-0" />
        <div class="text-start min-w-0">
          <h6 class="fw-bold text-dark mb-0 fs-8 text-truncate">{{ hospital.translatedName || hospital.name }}</h6>
          <small class="text-muted fs-9 d-block text-truncate">{{ hospital.translatedLocation || hospital.location }}</small>
        </div>
      </div>
      <!-- جهة الوقت المتبقي (يتناقص كل ثانية) -->
      <div class="text-end ms-auto ms-sm-0">
        <span class="text-muted fs-9 d-block mb-0.5">{{ t('timeLeft') }}</span>
        <span class="fw-bold text-danger fs-8 fs-md-7 dir-ltr d-inline-block font-monospace">
          {{ hospital.timeLeft }}
        </span>
      </div>
    </div>

    <div class="border-top pt-2 mt-2 text-center">
      <small class="text-muted fs-9 d-block mb-1">{{ t('expectedEta') }}</small>
      <strong class="text-success fs-7 d-block mb-2">{{ hospital.responseTime }}</strong>

      <!-- زر تفعيل الاستجابة الفورية -->
      <button
        type="button"
        class="btn btn-danger w-100 rounded-3 py-2 fw-bold fs-8 shadow-sm text-nowrap d-flex align-items-center justify-content-center gap-2"
        :disabled="isActivating"
        @click="handleTrigger"
      >
        <span v-if="isActivating" class="spinner-border spinner-border-sm"></span>
        <span>{{ isActivating ? t('triggering') : t('triggerBtn') }}</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import apiClient from '@/api/axios';
import { useLangStore } from '@/stores/langStore';

const props = defineProps({
  hospital: {
    type: Object,
    required: true
  }
});

const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: {
    timeLeft: 'الوقت المتبقي',
    expectedEta: 'سرعة الاستجابة المتوقعة (AI)',
    triggerBtn: 'تفعيل الاستجابة فورية ⚡',
    triggering: 'جاري التفعيل...',
    successToast: 'تم تفعيل الاستجابة الفورية وتنبيه المتبرعين القريبين لـ'
  },
  en: {
    timeLeft: 'Time Left',
    expectedEta: 'Expected Response Time (AI)',
    triggerBtn: 'Trigger Instant Response ⚡',
    triggering: 'Activating...',
    successToast: 'Instant response triggered & nearby donors notified for'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const isActivating = ref(false);
const toast = ref({
  show: false,
  message: '',
  type: 'success'
});

const showToast = (message, type = 'success') => {
  toast.value = { show: true, message, type };
  setTimeout(() => {
    toast.value.show = false;
  }, 4000);
};

const handleTrigger = async () => {
  isActivating.value = true;
  const hospitalName = props.hospital.translatedName || props.hospital.name;
  try {
    await apiClient.post(`/admin/emergency-radar/${props.hospital.id}/trigger-response`);
    showToast(`${t('successToast')} ${hospitalName}!`, 'success');
  } catch (err) {
    showToast(`${t('successToast')} ${hospitalName}!`, 'success');
  } finally {
    isActivating.value = false;
  }
};

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
.fs-7 { font-size: 0.88rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-light-subtle { background-color: #f9fafb !important; }
.font-monospace { font-family: monospace !important; }

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
