<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <strong class="text-dark fs-8 d-block mb-2">{{ t('locationMap') }}</strong>
    <div class="rounded-3 overflow-hidden border position-relative mb-3" style="height: 120px;">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d108928.261234!2d34.45!3d31.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14fd7f0800000000%3A0x100!2sGaza!5e0!3m2!1sen!2s!4v1600000000000!5m2!1sen!2s"
        width="100%"
        height="100%"
        style="border:0;"
        allowfullscreen=""
        loading="lazy">
      </iframe>
    </div>

    <div class="d-flex gap-2">
      <button @click="$emit('accept')" class="btn btn-danger flex-fill rounded-3 py-2 fw-bold fs-8 text-nowrap">
        {{ t('acceptRequest') }}
      </button>
      <button @click="$emit('reject')" class="btn btn-outline-danger flex-fill rounded-3 py-2 fw-bold fs-8 text-nowrap">
        {{ t('rejectRequest') }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    locationMap: "الموقع على الخريطة",
    acceptRequest: "قبول الطلب",
    rejectRequest: "رفض الطلب"
  },
  en: {
    locationMap: "Location on Map",
    acceptRequest: "Accept Request",
    rejectRequest: "Reject Request"
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

defineEmits(['accept', 'reject']);
</script>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
