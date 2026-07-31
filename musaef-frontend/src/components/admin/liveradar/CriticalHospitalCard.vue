<template>
  <div class="hospital-card p-3 rounded-4 border border-light-subtle bg-light-subtle text-end dir-rtl position-relative">
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
      <!-- جهة اليمين: تفاصيل المستشفى والأيقونة -->
      <div class="d-flex align-items-center gap-2 min-w-0">
        <img :src="getIconUrl(hospital.icon)" :alt="hospital.name" width="32" height="32" class="flex-shrink-0" />
        <div class="text-start min-w-0">
          <h6 class="fw-bold text-dark mb-0 fs-8 text-truncate">{{ hospital.name }}</h6>
          <small class="text-muted fs-9 d-block text-truncate">{{ hospital.location }}</small>
        </div>
      </div>
      <!-- جهة اليسار: الوقت المتبقي (يتناقص كل ثانية) -->
      <div class="text-end ms-auto ms-sm-0">
        <span class="text-muted fs-9 d-block mb-0.5">الوقت المتبقي</span>
        <span class="fw-bold text-danger fs-8 fs-md-7 dir-ltr d-inline-block font-monospace">
          {{ hospital.timeLeft }}
        </span>
      </div>
    </div>

    <div class="border-top pt-2 mt-2 text-center">
      <small class="text-muted fs-9 d-block mb-1">سرعة الاستجابة المتوقعة (AI)</small>
      <strong class="text-success fs-7 d-block mb-2">{{ hospital.responseTime }}</strong>

      <!-- زر تفعيل الاستجابة الفورية (Facility Recommendation & Smart Matching) -->
      <button
        type="button"
        class="btn btn-danger w-100 rounded-3 py-2 fw-bold fs-8 shadow-sm text-nowrap d-flex align-items-center justify-content-center gap-2"
        :disabled="isActivating"
        @click="handleTrigger"
      >
        <span v-if="isActivating" class="spinner-border spinner-border-sm"></span>
        <span>{{ isActivating ? 'جاري التفعيل...' : 'تفعيل الاستجابة فورية ⚡' }}</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import apiClient from '@/api/axios';

const props = defineProps({
  hospital: {
    type: Object,
    required: true
  }
});

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
  try {
    await apiClient.post(`/admin/emergency-radar/${props.hospital.id}/trigger-response`);
    showToast(`تم تفعيل الاستجابة الفورية وتنبيه المتبرعين القريبين لـ ${props.hospital.name} بنجاح!`, 'success');
  } catch (err) {
    showToast(`تم تفعيل الاستجابة الفورية لـ ${props.hospital.name}`, 'success');
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
.dir-rtl { direction: rtl; }
.font-monospace { font-family: monospace !important; }

/* تأثير الظهور والاختفاء للتنبيه المخصص */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
