<template>
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative h-100 map-card" :dir="langStore.dir">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d108928.261234!2d34.45!3d31.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14fd7f0800000000%3A0x100!2sGaza!5e0!3m2!1sen!2s!4v1600000000000!5m2!1sen!2s"
      width="100%"
      height="100%"
      class="map-iframe"
      style="border:0;"
      allowfullscreen
      loading="lazy">
    </iframe>

    <div
      class="position-absolute bg-white px-2.5 px-md-3 py-1.5 rounded-3 shadow-sm text-dark fw-bold fs-9 fs-md-8 title-badge"
      :class="langStore.isRtl ? 'badge-rtl' : 'badge-ltr'"
    >
      {{ t('mapTitle') }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useLangStore } from '@/stores/langStore';

const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: { mapTitle: 'خريطة مستشفيات قطاع غزة' },
  en: { mapTitle: 'Gaza Strip Hospitals Map' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;
</script>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }

.map-card, .map-iframe {
  min-height: 260px;
}

@media (min-width: 768px) {
  .map-card, .map-iframe {
    min-height: 320px;
  }
}

.title-badge {
  top: 12px;
  z-index: 10;
}

.badge-rtl {
  right: 12px;
}

.badge-ltr {
  left: 12px;
}

@media (min-width: 768px) {
  .badge-rtl {
    right: 16px;
    top: 16px;
  }
  .badge-ltr {
    left: 16px;
    top: 16px;
  }
}
</style>
