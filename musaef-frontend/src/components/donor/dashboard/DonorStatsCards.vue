<template>
  <div class="row g-3 mb-3 mb-md-4 donor-stats-cards">
    <!-- عدد التبرعات -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 text-center">
        <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3 w-100">
          <span class="text-dark fw-bold fs-8 fs-md-6">{{ t('totalDonations') }}</span>
          <div class="stat-icon-wrapper bg-pink-light">
            <img :src="bloodIcon" alt="تبرعات" class="stat-icon-img" />
          </div>
        </div>
        <h3 class="fw-black text-danger mb-1 mb-md-2 fs-4 fs-md-3">{{ stats.donationsCount || 0 }}</h3>
        <small class="text-muted fs-8 text-center d-block text-truncate">
          {{ getLocalizedLastDonationText() }}
        </small>
      </div>
    </div>

    <!-- النقاط -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 text-center">
        <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3 w-100">
          <span class="text-dark fw-bold fs-8 fs-md-6">{{ t('totalPoints') }}</span>
          <div class="stat-icon-wrapper bg-yellow-light">
            <img :src="starIcon" alt="نقاط" class="stat-icon-img" />
          </div>
        </div>
        <h3 class="fw-black text-warning mb-1 mb-md-2 fs-4 fs-md-3">{{ stats.points || 0 }}</h3>
        <small class="text-muted fs-8 text-center d-block text-truncate">
          {{ t('level') }} : {{ getLocalizedLevel() }}
        </small>
      </div>
    </div>

    <!-- الطلبات القريبة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 text-center">
        <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3 w-100">
          <span class="text-dark fw-bold fs-8 fs-md-6">{{ t('nearbyRequests') }}</span>
          <div class="stat-icon-wrapper bg-blue-light">
            <img :src="locationIcon" alt="موقع" class="stat-icon-img" />
          </div>
        </div>
        <h3 class="fw-black text-primary mb-1 mb-md-2 fs-4 fs-md-3">{{ stats.nearbyRequestsCount || 0 }}</h3>
        <small class="text-muted fs-8 text-center d-block lh-sm text-truncate">
          {{ t('thereAre') }} {{ stats.nearbyRequestsCount || 0 }} {{ t('requestsNearYou') }}
        </small>
      </div>
    </div>

    <!-- حالة الأهلية للتبرع -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 text-center">
        <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3 w-100">
          <span class="text-dark fw-bold fs-8 fs-md-6">{{ t('eligibilityStatus') }}</span>
          <div class="stat-icon-wrapper bg-green-light">
            <img :src="eligibilityIcon" alt="أهلية" class="stat-icon-img" />
          </div>
        </div>
        <h3 class="fw-bold mb-1 mb-md-2 fs-5 fs-md-4" :class="stats.isEligible ? 'text-success' : 'text-danger'">
          {{ stats.isEligible ? t('eligible') : t('notEligible') }}
        </h3>
        <small class="text-muted fs-8 text-center d-block text-truncate">
          {{ stats.isEligible ? t('canDonateNow') : t('waitingRecovery') }}
        </small>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

// استيراد الأيقونات المباشر
import bloodIcon from '@/assets/icons/blood-icon.png';
import starIcon from '@/assets/icons/star.png';
import locationIcon from '@/assets/icons/Frame 2147225248.png';
import eligibilityIcon from '@/assets/icons/Frame 2147225275.png';

const props = defineProps({
  stats: {
    type: Object,
    required: true
  }
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: {
    totalDonations: 'عدد التبرعات',
    noPreviousDonations: 'لم تقم بالتبرع سابقاً',
    lastDonationPrefix: 'آخر تبرع منذ',
    daysAgo: 'يوم',
    totalPoints: 'النقاط',
    level: 'المستوى',
    beginner: 'مبتدئ',
    advanced: 'متقدم',
    nearbyRequests: 'الطلبات القريبة',
    thereAre: 'يوجد',
    requestsNearYou: 'طلبات قريبة منك',
    eligibilityStatus: 'حالة الأهلية للتبرع',
    eligible: 'مؤهل',
    notEligible: 'غير مؤهل',
    canDonateNow: 'يمكنك التبرع الآن',
    waitingRecovery: 'بانتظار اكتمال فترة التعافي'
  },
  en: {
    totalDonations: 'Total Donations',
    noPreviousDonations: 'No previous donations',
    lastDonationPrefix: 'Last donation',
    daysAgo: 'days ago',
    totalPoints: 'Points',
    level: 'Level',
    beginner: 'Beginner',
    advanced: 'Advanced',
    nearbyRequests: 'Nearby Requests',
    thereAre: 'There are',
    requestsNearYou: 'requests near you',
    eligibilityStatus: 'Eligibility Status',
    eligible: 'Eligible',
    notEligible: 'Ineligible',
    canDonateNow: 'You can donate now',
    waitingRecovery: 'Awaiting recovery completion'
  }
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const getLocalizedLastDonationText = () => {
  if (currentLanguage.value === 'en') {
    if (!props.stats?.lastDonationText) return translations.en.noPreviousDonations;
    return props.stats.lastDonationText.replace('آخر تبرع منذ', 'Last donation').replace('يوم', 'days ago');
  }
  return props.stats?.lastDonationText || translations.ar.noPreviousDonations;
};

const getLocalizedLevel = () => {
  if (props.stats?.level === 'متقدم' || props.stats?.level === 'Advanced') {
    return currentLanguage.value === 'en' ? 'Advanced' : 'متقدم';
  }
  return currentLanguage.value === 'en' ? 'Beginner' : 'مبتدئ';
};
</script>

<style scoped>
.donor-stats-cards {
  font-family: Arial, sans-serif !important;
}

.stat-icon-wrapper { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
@media (min-width: 768px) { .stat-icon-wrapper { width: 48px; height: 48px; } }

.stat-icon-img { width: 22px; height: 22px; object-fit: contain; }
@media (min-width: 768px) { .stat-icon-img { width: 26px; height: 26px; } }

.bg-pink-light { background-color: #fdecec; }
.bg-yellow-light { background-color: #fef9c3; }
.bg-blue-light { background-color: #e0f2fe; }
.bg-green-light { background-color: #dcfce7; }
.fs-8 { font-size: 0.82rem; }
.fw-black { font-weight: 900; }
</style>
