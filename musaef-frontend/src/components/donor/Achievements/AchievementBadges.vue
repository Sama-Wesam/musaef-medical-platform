<template>
  <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm font-arial" :class="currentLanguage === 'ar' ? 'dir-rtl' : 'dir-ltr'">
    <h5 class="fw-bold text-dark mb-3 mb-md-4 text-center position-relative d-inline-block mx-auto section-title-line fs-6 fs-md-5">
      {{ t('achievementBadges') }}
    </h5>

    <div class="row g-3 g-md-4 text-center">
      <div v-for="badge in translatedBadges" :key="badge.id" class="col-12 col-sm-6 col-xl-3">
        <div class="p-3 border rounded-4 bg-white h-100 shadow-2xs d-flex flex-column align-items-center justify-content-center">
          <img :src="getIconUrl(badge.image)" :alt="badge.title" class="badge-card-img mb-2 mb-md-3" />
          <h6 class="fw-bold text-dark mb-1 fs-7">{{ badge.title }}</h6>
          <small class="text-muted fs-9 d-block mb-1 text-truncate w-100">{{ badge.desc }}</small>
          <small class="text-secondary fw-bold fs-9">{{ translateDate(badge.date) }}</small>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  badges: {
    type: Array,
    default: () => []
  }
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: { achievementBadges: 'شارات الإنجاز' },
  en: { achievementBadges: 'Achievement Badges' }
};

const badgeDict = {
  'منقذ حياة': { title: 'Life Saver', desc: 'Saved more than 10 cases' },
  '10 تبرعات': { title: '10 Donations', desc: 'Completed 10 donations' },
  '5 تبرعات': { title: '5 Donations', desc: 'Completed 5 donations' },
  'أول تبرع': { title: 'First Donation', desc: 'Completed first donation' }
};

const dateDict = {
  '1 يونيو 2024': '1 June 2024',
  '20 مايو 2025': '20 May 2025',
  '10 أبريل 2024': '10 April 2024',
  '15 مارس 2024': '15 March 2024'
};

const t = (key) => currentLanguage.value === 'en' ? translations.en[key] : translations.ar[key];

const translateDate = (date) => {
  if (!date) return '';
  if (currentLanguage.value === 'en') {
    return dateDict[date] || date;
  }
  return date;
};

const translatedBadges = computed(() => {
  return props.badges.map(b => {
    if (currentLanguage.value === 'en' && badgeDict[b.title]) {
      return {
        ...b,
        title: badgeDict[b.title].title,
        desc: badgeDict[b.title].desc
      };
    }
    return b;
  });
});

const getIconUrl = (fileName) => {
  if (!fileName) return '';
  if (fileName.startsWith('http') || fileName.startsWith('data:')) {
    return fileName;
  }
  try {
    return new URL(`../../../assets/icons/${fileName}`, import.meta.url).href;
  } catch (e) {
    return '';
  }
};
</script>

<style scoped>
.font-arial {
  font-family: Arial, Helvetica, sans-serif !important;
}

.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
.badge-card-img { width: 64px; height: 64px; object-fit: contain; }
@media (min-width: 768px) { .badge-card-img { width: 80px; height: 80px; } }

.section-title-line { border-bottom: 2px solid #dc2626; padding-bottom: 4px; }
.fs-6 { font-size: 1.05rem; }
.fs-7 { font-size: 0.92rem; }
.fs-9 { font-size: 0.72rem; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
</style>
