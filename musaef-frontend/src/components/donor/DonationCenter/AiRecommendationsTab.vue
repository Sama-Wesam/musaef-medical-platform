<template>
  <div class="ai-recommendations-tab">
    <!-- عنوان ووصف التبويب -->
    <div class="mb-3 mb-md-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
      <h5 class="fw-bold text-dark mb-1 fs-6 fs-md-5">{{ t('aiRecommendationsTitle') }}</h5>
      <p class="text-muted fs-8 mb-0">
        {{ t('aiRecommendationsDesc') }}
      </p>
    </div>

    <!-- حالة التحميل -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-danger mb-2" role="status"></div>
      <p class="text-muted fs-8">{{ t('loadingRecommendations') }}</p>
    </div>

    <!-- لا توجد توصيات -->
    <div v-else-if="sortedAiRequests.length === 0" class="text-center py-5 bg-white rounded-4 shadow-2xs mb-4">
      <i class="bi bi-stars fs-1 text-warning d-block mb-2"></i>
      <p class="text-muted fs-8 mb-0">{{ t('noRecommendations') }}</p>
    </div>

    <!-- شبكة كروت التوصيات -->
    <div v-else class="row g-3 g-md-4 mb-4">
      <div v-for="aiItem in sortedAiRequests" :key="aiItem.id" class="col-12 col-md-6 col-lg-4">
        <div
          class="card border-0 rounded-4 p-3 bg-white shadow-2xs h-100 d-flex flex-column justify-content-between interactive-card cursor-pointer"
          @click="$emit('select-request', aiItem)"
        >
          <div>
            <!-- شارة التطابق ومستوى الخطورة -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
              <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-9 fw-bold text-truncate">
                ✨ {{ t('matchScore') }} {{ aiItem.matchScore }}%
              </span>
              <span
                class="badge rounded-pill px-2.5 py-1 fs-9 fw-bold flex-shrink-0"
                :class="getUrgencyBadgeClass(aiItem.urgency)"
              >
                {{ translateUrgency(aiItem.urgency) }}
              </span>
            </div>

            <!-- تفاصيل المستشفى والمدينة -->
            <div class="d-flex align-items-center gap-2 mb-3 min-w-0" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <img :src="aiItem.img" alt="مستشفى" class="hospital-box-img rounded-3 flex-shrink-0" @error="handleHospitalFallback" />
              <div class="min-w-0">
                <h6 class="fw-bold text-dark mb-0 fs-7 text-truncate">{{ translateHospital(aiItem.hospital) }}</h6>
                <small class="text-muted fs-9 d-block text-truncate">{{ translateLocation(aiItem.location) }}</small>
              </div>
            </div>

            <!-- التفاصيل الأساسية -->
            <div class="row text-center align-items-center my-3 py-2 bg-light-subtle rounded-3 g-0 border border-light">
              <div class="col-4">
                <small class="text-muted fs-9 d-block mb-1">{{ t('distance') }}</small>
                <span class="fw-bold text-dark fs-8">{{ aiItem.distance }} {{ t('km') }}</span>
              </div>
              <div class="col-4 border-start border-end">
                <small class="text-muted fs-9 d-block mb-1">{{ t('units') }}</small>
                <span class="fw-bold text-dark fs-8">{{ aiItem.units }}</span>
              </div>
              <div class="col-4">
                <small class="text-muted fs-9 d-block mb-1">{{ t('bloodTypeLabel') }}</small>
                <span class="fw-black text-danger fs-6" dir="ltr">{{ aiItem.bloodType }}</span>
              </div>
            </div>

            <!-- سبب الترشيح -->
            <div class="p-2 bg-success bg-opacity-10 text-success rounded-3 fs-9 text-center mb-3">
              <i class="bi bi-check-circle-fill me-1"></i> {{ translateRecommendation(aiItem.recommendationText) }}
            </div>
          </div>

          <!-- زر عرض التفاصيل -->
          <button class="btn btn-outline-danger w-100 py-2 fw-bold fs-8 border-danger-subtle figma-btn-radius">
            {{ t('viewDetails') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  requests: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
});

defineEmits(['select-request']);

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: {
    aiRecommendationsTitle: 'توصيات الذكاء الاصطناعي لك',
    aiRecommendationsDesc: 'تم ترتيب هذه الطلبات بناءً على توافقك وقربك وسرعة الاستجابة المتوقعة.',
    loadingRecommendations: 'جاري تحليل البيانات وتوليد التوصيات الذكية...',
    noRecommendations: 'لا توجد توصيات متاحة حالياً.',
    matchScore: 'تطابق الذكاء الاصطناعي',
    distance: 'المسافة',
    km: 'كم',
    units: 'الوحدات',
    bloodTypeLabel: 'الفصيلة',
    viewDetails: 'عرض التفاصيل'
  },
  en: {
    aiRecommendationsTitle: 'AI Recommendations for You',
    aiRecommendationsDesc: 'These requests are ranked based on your compatibility, proximity, and expected response speed.',
    loadingRecommendations: 'Analyzing data and generating smart recommendations...',
    noRecommendations: 'No recommendations currently available.',
    matchScore: 'AI Match',
    distance: 'Distance',
    km: 'km',
    units: 'Units',
    bloodTypeLabel: 'Type',
    viewDetails: 'View Details'
  }
};

const hospitalDict = {
  'بنك الدم المركزي - وزارة الصحة': 'Central Blood Bank - Ministry of Health',
  'جمعية بنك الدم المركزي': 'Central Blood Bank Society',
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex',
  'المستشفى الإندونيسي': 'Indonesian Hospital',
  'مستشفى الأهلي العربي (المعمداني)': 'Al-Ahli Arab Hospital (Baptist)',
  'مستشفى أصلان/أبو يوسف النجار': 'Abu Yousef Al-Najjar Hospital',
  'مستشفى أبو يوسف النجار': 'Abu Yousef Al-Najjar Hospital',
  'مستشفى كمال عدوان': 'Kamal Adwan Hospital',
  'مستشفى القدس - الهلال الأحمر': 'Al-Quds Hospital - Red Crescent',
  'مجمع ناصر الطبي': 'Nasser Medical Complex',
  'مستشفى أصدقاء المريض الخيري': 'Patient\'s Friends Benevolent Society Hospital',
  'مستشفى أصدقاء المريض': 'Patient\'s Friends Hospital'
};

const locationDict = {
  'غزة - النصر': 'Gaza - An-Naser',
  'شمال غزة - بيت لاهيا': 'North Gaza - Beit Lahia',
  'غزة - الزيتون': 'Gaza - Zaytoun',
  'رفح - الشابورة': 'Rafah - Shaboura',
  'غزة - الرمال': 'Gaza - Rimal',
  'غزة - الرمال شارع الوحدة': 'Gaza - Rimal (Wehda St)',
  'غزة - تل الهوى': 'Gaza - Tel Al-Hawa',
  'خانيونس - وسط المدينة': 'Khan Younis - City Center',
  'غزة - فلسطين': 'Gaza - Palestine',
  'رفح - الجنينة': 'Rafah - Al-Jnena'
};

const recommendationDict = {
  'متوافق مع فصيلة دمك ونطاقك الجغرافي المباشر': 'Compatible with your blood type and direct geographical area',
  'متوافق مع فصيلة دمك وفي نطاقك الجغرافي السكني': 'Compatible with your blood type and residential area',
  'نسبة تطابق عالية مع فصيلة الدم المستهدفة': 'High match rate with target blood type',
  'أولوية طوارئ قصوى - نقص حاد بالفصيلة': 'Top emergency priority - severe shortage of blood type',
  'تغطية طارئة مجهزة لقسم جراحة العظام': 'Emergency coverage prepared for orthopedics'
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const translateHospital = (name) => currentLanguage.value === 'en' ? (hospitalDict[name] || name) : name;
const translateLocation = (loc) => currentLanguage.value === 'en' ? (locationDict[loc] || loc) : loc;
const translateRecommendation = (rec) => currentLanguage.value === 'en' ? (recommendationDict[rec] || rec) : rec;

const translateUrgency = (urgency) => {
  if (currentLanguage.value === 'en') {
    if (urgency === 'حرجة جداً' || urgency === 'حرجة') return 'Critical';
    if (urgency === 'عالية') return 'High';
    if (urgency === 'متوسطة') return 'Medium';
    if (urgency === 'منخفضة') return 'Low';
  }
  return urgency;
};

const getUrgencyBadgeClass = (urgency) => {
  if (urgency === 'حرجة' || urgency === 'حرجة جداً' || urgency === 'Critical') return 'bg-danger-subtle text-danger';
  if (urgency === 'عالية' || urgency === 'متوسطة' || urgency === 'High' || urgency === 'Medium') return 'bg-warning-subtle text-warning-emphasis';
  return 'bg-secondary-subtle text-secondary';
};

const getImageUrl = (fileName) => {
  return new URL(`../../../assets/images/${fileName}`, import.meta.url).href;
};

const handleHospitalFallback = (e) => {
  e.target.src = getImageUrl('hospital.png');
};

const sortedAiRequests = computed(() => {
  return [...props.requests].sort((a, b) => b.matchScore - a.matchScore);
});
</script>

<style scoped>
.ai-recommendations-tab {
  font-family: Arial, sans-serif !important;
}

.hospital-box-img { width: 55px; height: 50px; object-fit: cover; }
@media (min-width: 768px) { .hospital-box-img { width: 65px; height: 55px; } }

.interactive-card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.interactive-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08) !important;
}

.cursor-pointer { cursor: pointer; }
.border-danger-subtle { border-color: #fca5a5 !important; }
.figma-btn-radius { border-radius: 6px !important; }

.fs-6 { font-size: 1.05rem; }
.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.fw-black { font-weight: 900; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
</style>
