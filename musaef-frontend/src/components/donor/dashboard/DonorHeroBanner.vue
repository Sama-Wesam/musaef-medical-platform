<template>
  <div class="card border-0 rounded-4 shadow-sm p-3 p-md-4 mb-3 mb-md-4 hero-status-banner position-relative overflow-hidden">
    <div class="pink-heart-bg heart-1">💖</div>
    <div class="pink-heart-bg heart-2">💕</div>
    <div class="pink-heart-bg heart-3">💖</div>
    <div class="pink-heart-bg heart-4">✨</div>

    <div class="row align-items-center gy-3 gy-md-4 position-relative z-2">
      <!-- قسم العداد الدائري الديناميكي -->
      <div class="col-12 col-md-4 col-lg-3 text-center order-2 order-md-1">
        <div class="d-flex flex-column align-items-center justify-content-center mx-auto">
          <div
            class="figma-red-progress-ring position-relative d-flex flex-column align-items-center justify-content-center shadow-sm"
            :style="progressRingStyle"
          >
            <div class="inner-circle bg-white rounded-circle d-flex flex-column align-items-center justify-content-center">
              <i class="bi bi-calendar3 text-danger fs-6 fs-md-5 mb-1"></i>
              <span class="fs-3 fs-md-2 fw-black text-dark lh-1">{{ stats?.daysUntilNextDonation ?? 0 }}</span>
              <span class="fs-9 fw-bold text-dark mt-1">{{ t('daysRemaining') }}</span>
            </div>
          </div>
          <small class="text-muted fs-9 mt-2 text-center d-block fw-bold text-danger">{{ t('nextDonation') }}</small>
        </div>
      </div>

      <!-- الجزء الأوسط: النصوص والنقاط والشارات المحدثة حياً -->
      <div class="col-12 col-md-4 col-lg-6 text-center order-1 order-md-2">
        <h2 class="fw-black mb-2 hero-status-title" :class="isDonorEligible ? 'text-success' : 'text-danger'">
          {{ getLocalizedStatusTitle() }}
        </h2>

        <h5 class="fw-bold text-dark mb-2 fs-6 fs-md-5">
          {{ getLocalizedStatusDesc() }}
        </h5>

        <p class="text-secondary fs-8 mb-2 mb-md-3">
          {{ getLocalizedStatusMsg() }}
        </p>

        <!-- ربط مباشر لعدد النقاط والشارات الحية -->
        <div class="text-secondary fs-8 mb-3 d-inline-block px-2 py-1 rounded-3">
          {{ t('youHave') }} <strong class="text-danger fw-bold fs-7">{{ totalPoints }}</strong> {{ t('pointsAnd') }} <strong class="text-danger fw-bold fs-7">{{ totalBadgesCount }}</strong> {{ t('badges') }}
        </div>

        <div class="d-flex align-items-center justify-content-center gap-2 gap-sm-3">
          <div class="badge-icon-item yellow-circle shadow-sm">
            <img :src="starIcon" alt="نجمة" class="badge-img" />
          </div>
          <div class="badge-icon-item pink-circle shadow-sm">
            <img :src="bloodIcon" alt="قطرة" class="badge-img" />
          </div>
          <div class="badge-icon-item pink-circle shadow-sm">
            <img :src="heartIcon" alt="قلب" class="badge-img" />
          </div>
        </div>
      </div>

      <!-- جهة اليسار: قطرة الدم وشعار الأهلية -->
      <div class="col-12 col-md-4 col-lg-3 text-center text-md-end order-3">
        <div class="hero-left-drop-wrapper position-relative d-inline-block">
          <img :src="vectorIcon" alt="نبض" class="pulse-vector-bg" />
          <img :src="bloodShieldImg" alt="مؤهل" class="drop-shield-img position-relative z-2" @error="handleHeroDropFallback" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { useDonorStore } from '@/stores/donorStore';

import starIcon from '@/assets/icons/star.png';
import bloodIcon from '@/assets/icons/blood-icon.png';
import heartIcon from '@/assets/icons/heart.png';
import vectorIcon from '@/assets/icons/Vector 9.png';
import bloodShieldImg from '@/assets/icons/blood.png';

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({})
  },
  currentLanguage: {
    type: String,
    default: 'ar'
  }
});

const donorStore = useDonorStore();
const activeLang = ref(props.currentLanguage || localStorage.getItem('musaef_lang') || 'ar');
const localAcceptedCount = ref(0);

const updateLocalData = () => {
  const localAccepted = JSON.parse(localStorage.getItem('musaef_accepted_donations') || '[]');
  localAcceptedCount.value = localAccepted.length;
};

onMounted(() => {
  updateLocalData();
  window.addEventListener('storage', updateLocalData);
  window.addEventListener('musaef_donation_accepted', updateLocalData);
});

onUnmounted(() => {
  window.removeEventListener('storage', updateLocalData);
  window.removeEventListener('musaef_donation_accepted', updateLocalData);
});

const totalPoints = computed(() => {
  return (props.stats?.points ?? 0) + (localAcceptedCount.value * 50);
});

const totalBadgesCount = computed(() => {
  const baseBadges = props.stats?.badgesCount ?? 0;
  const totalDonations = (props.stats?.donationsCount ?? 0) + localAcceptedCount.value;
  let dynamicBadges = 0;
  if (totalDonations >= 1) dynamicBadges++;
  if (totalDonations >= 5) dynamicBadges++;
  if (totalDonations >= 10) dynamicBadges++;
  return Math.max(baseBadges, dynamicBadges, 1);
});

const currentLang = computed(() => props.currentLanguage || activeLang.value);

const isDonorEligible = computed(() => {
  if (typeof donorStore?.healthEligibility?.isEligible === 'boolean') {
    return donorStore.healthEligibility.isEligible;
  }
  if (typeof props.stats?.isEligible === 'boolean') {
    return props.stats.isEligible;
  }
  return true;
});

const progressRingStyle = computed(() => {
  const days = props.stats?.daysUntilNextDonation ?? 0;
  const maxDays = 90;
  const percentage = Math.min(Math.max((days / maxDays) * 100, 0), 100);
  const degrees = (percentage / 100) * 360;
  return {
    background: `conic-gradient(#dc2626 0deg ${degrees}deg, #f1f5f9 ${degrees}deg 360deg)`
  };
});

const translations = {
  ar: {
    daysRemaining: 'يوماً متبقية',
    nextDonation: 'حتى التبرع التالي',
    eligibleTitle: 'مؤهل للتبرع',
    notEligibleTitle: 'غير مؤهل حالياً',
    eligibleDesc: 'حالتك الصحية مؤهلة للتبرع',
    notEligibleDesc: 'حالتك الصحية تتطلب الانتظار',
    eligibleMsg: 'يمكنك التبرع الآن، صحتك تسمح بذلك.',
    notEligibleMsg: 'يرجى الانتظار حتى اكتمال فترة التعافي لتتمكن من التبرع من جديد.',
    youHave: 'لديك',
    pointsAnd: 'نقطة و',
    badges: 'شارات'
  },
  en: {
    daysRemaining: 'Days remaining',
    nextDonation: 'Until next donation',
    eligibleTitle: 'Eligible for Donation',
    notEligibleTitle: 'Currently Ineligible',
    eligibleDesc: 'Your health status is eligible for donation',
    notEligibleDesc: 'Your health status requires waiting',
    eligibleMsg: 'You can donate now, your health permits it.',
    notEligibleMsg: 'Please wait until recovery period is complete to donate again.',
    youHave: 'You have',
    pointsAnd: 'points &',
    badges: 'badges'
  }
};

const t = (key) => {
  const lang = currentLang.value === 'en' ? 'en' : 'ar';
  return translations[lang]?.[key] || key;
};

const getLocalizedStatusTitle = () => {
  if (currentLang.value === 'en') {
    return isDonorEligible.value ? translations.en.eligibleTitle : translations.en.notEligibleTitle;
  }
  return isDonorEligible.value ? translations.ar.eligibleTitle : translations.ar.notEligibleTitle;
};

const getLocalizedStatusDesc = () => {
  if (currentLang.value === 'en') {
    return isDonorEligible.value ? translations.en.eligibleDesc : translations.en.notEligibleDesc;
  }
  return isDonorEligible.value ? translations.ar.eligibleDesc : translations.ar.notEligibleDesc;
};

const getLocalizedStatusMsg = () => {
  if (currentLang.value === 'en') {
    return isDonorEligible.value ? translations.en.eligibleMsg : translations.en.notEligibleMsg;
  }
  return isDonorEligible.value ? translations.ar.eligibleMsg : translations.ar.notEligibleMsg;
};

const handleHeroDropFallback = (e) => {
  if (e?.target) e.target.src = bloodShieldImg;
};
</script>

<style scoped>
.hero-status-banner {
  font-family: Arial, sans-serif !important;
  background: linear-gradient(135deg, #fff5f5 0%, #fdecec 100%);
  border: 1px solid #fca5a5 !important;
}
.pink-heart-bg { position: absolute; font-size: 1.2rem; opacity: 0.25; pointer-events: none; z-index: 1; }
.heart-1 { top: 15%; left: 10%; }
.heart-2 { top: 70%; left: 25%; }
.heart-3 { top: 20%; right: 40%; }
.heart-4 { top: 75%; right: 15%; }
.figma-red-progress-ring { width: 105px; height: 105px; border-radius: 50%; padding: 6px; }
@media (min-width: 768px) { .figma-red-progress-ring { width: 120px; height: 120px; padding: 8px; } }
.figma-red-progress-ring .inner-circle { width: 100%; height: 100%; }
.hero-left-drop-wrapper { position: relative; min-height: 130px; display: flex; align-items: center; justify-content: center; }
@media (min-width: 768px) { .hero-left-drop-wrapper { min-height: 160px; } }
.pulse-vector-bg { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 130%; max-width: 280px; height: auto; z-index: 1; pointer-events: none; }
.drop-shield-img { max-height: 120px; width: auto; filter: drop-shadow(0 10px 15px rgba(220, 38, 38, 0.15)); }
@media (min-width: 768px) { .drop-shield-img { max-height: 160px; } }
.hero-status-title { font-size: 24px; }
@media (min-width: 768px) { .hero-status-title { font-size: 32px; } }
.badge-icon-item { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
@media (min-width: 768px) { .badge-icon-item { width: 44px; height: 44px; } }
.badge-img { width: 20px; height: 20px; object-fit: contain; }
@media (min-width: 768px) { .badge-img { width: 24px; height: 24px; } }
.pink-circle { background-color: #fdecec; }
.yellow-circle { background-color: #fef9c3; }
.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.fw-black { font-weight: 900; }
</style>
