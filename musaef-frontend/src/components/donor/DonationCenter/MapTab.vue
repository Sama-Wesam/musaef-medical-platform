<template>
  <div class="map-tab-container position-relative rounded-4 overflow-hidden shadow-sm">
    <!-- 1. إطار الخريطة الرئيسي -->
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d108928.261234!2d34.45!3d31.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14fd7f0800000000%3A0x100!2sGaza!5e0!3m2!1sen!2s!4v1600000000000!5m2!1sen!2s"
      width="100%"
      class="map-iframe"
      style="border:0;"
      allowfullscreen=""
      loading="lazy"
    ></iframe>

    <!-- 2. كارت تفاصيل المستشفى العائم فوق الخريطة -->
    <div v-if="selectedHospital" class="hospital-detail-card position-absolute bg-white rounded-4 p-3 p-md-4 shadow-lg" :class="currentLanguage === 'ar' ? 'dir-rtl' : 'dir-ltr'">
      <!-- هيدر الكارت: زر الإغلاق والعنوان -->
      <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3 position-relative">
        <button @click="closeCard" class="btn-close fs-9 m-0" aria-label="إغلاق"></button>
        <span class="fw-bold text-dark fs-8 fs-md-7 mx-auto pe-2">{{ t('hospitalDetailsTitle') }}</span>
      </div>

      <!-- صورة المستشفى والاسم (تنسيق منظم ومحمي بـ Flex و Grid) -->
      <div class="hospital-info-box p-2.5 bg-light-subtle rounded-3 border mb-3">
        <div class="d-flex align-items-center gap-2">
          <!-- النص المكتوب مع تحديد max-width لمنع التداخل -->
          <div class="hospital-text-container min-w-0" :class="currentLanguage === 'ar' ? 'text-end ms-auto' : 'text-start me-auto'">
            <h6 class="fw-bold text-dark mb-1 fs-8 fs-md-7 text-truncate" :title="translateHospital(selectedHospital.hospital)">
              {{ translateHospital(selectedHospital.hospital) }}
            </h6>
            <small class="text-muted fs-9 d-block text-truncate">
              <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ translateLocation(selectedHospital.location) }}
            </small>
          </div>

          <!-- الصورة بأبعاد قياسية مثبتة لمنع التشوه طولياً أو عرضياً -->
          <div class="hospital-img-fixed-box flex-shrink-0">
            <img :src="selectedHospital.img" alt="مستشفى" class="hospital-fixed-img rounded-2 shadow-2xs" @error="handleHospitalFallback" />
          </div>
        </div>
      </div>

      <!-- المسافة والوقت المتوقع -->
      <div class="row text-center g-2 mb-3">
        <div class="col-6">
          <div class="bg-light-subtle rounded-3 p-2 border h-100 d-flex flex-column align-items-center justify-content-center">
            <small class="text-muted fs-9 d-block mb-0.5">{{ t('distance') }}</small>
            <span class="fw-bold text-dark fs-8 fs-md-7">{{ selectedHospital.distance }} {{ t('km') }}</span>
            <small class="text-muted d-block fs-9">{{ t('fromYourLocation') }}</small>
          </div>
        </div>
        <div class="col-6">
          <div class="bg-light-subtle rounded-3 p-2 border h-100 d-flex flex-column align-items-center justify-content-center">
            <small class="text-muted fs-9 d-block mb-0.5">{{ t('estimatedTime') }}</small>
            <div class="d-flex align-items-center justify-content-center gap-1 text-danger my-0.5">
              <i class="bi bi-car-front-fill fs-9"></i>
              <span class="fw-bold fs-8 fs-md-7">10 {{ t('minutes') }}</span>
            </div>
            <small class="text-muted fs-9">{{ t('byCar') }}</small>
          </div>
        </div>
      </div>

      <!-- الفصائل المطلوبة -->
      <div class="mb-3 text-center">
        <small class="text-dark fs-8 d-block mb-1.5 fw-bold">{{ t('requiredBloodType') }}</small>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
          <span class="badge bg-pink-light text-danger border border-danger-subtle rounded-3 px-3 py-1.5 fs-8 fw-bold" dir="ltr">
            {{ selectedHospital.bloodType }}
          </span>
        </div>
      </div>

      <!-- عدد الوحدات ومستوى الخطورة -->
      <div class="bg-light-subtle rounded-3 p-2.5 p-md-3 border mb-3">
        <div class="row align-items-center text-center g-0">
          <div class="col-6 border-end" :class="currentLanguage === 'en' ? 'border-end' : 'border-start border-end-0'">
            <small class="text-muted fs-9 d-block mb-1">{{ t('urgency') }}</small>
            <span class="badge bg-danger text-white rounded-2 px-2.5 py-1 fs-9 fw-bold">
              {{ translateUrgency(selectedHospital.urgency) }}
            </span>
          </div>
          <div class="col-6">
            <small class="text-muted fs-9 d-block mb-1">{{ t('requiredUnits') }}</small>
            <span class="fw-bold text-dark fs-8 fs-md-7">{{ selectedHospital.units }} {{ t('units') }}</span>
          </div>
        </div>
      </div>

      <!-- أزرار التفاعل -->
      <button @click="$emit('select-request', selectedHospital)" class="btn btn-danger w-100 py-2 fw-bold fs-8 rounded-3 shadow-2xs">
        {{ t('startNavigation') }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, watchEffect, computed } from 'vue';

const props = defineProps({
  requests: {
    type: Array,
    default: () => []
  }
});

defineEmits(['select-request']);

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: {
    hospitalDetailsTitle: 'تفاصيل المستشفى',
    distance: 'المسافة',
    km: 'كم',
    fromYourLocation: 'من موقعك',
    estimatedTime: 'الوقت المتوقع',
    minutes: 'دقائق',
    byCar: 'بالسيارة',
    requiredBloodType: 'الفصيلة المطلوبة',
    urgency: 'الخطورة',
    requiredUnits: 'الوحدات المطلوبة',
    units: 'وحدات',
    startNavigation: 'أبدأ التوجيه الآن'
  },
  en: {
    hospitalDetailsTitle: 'Hospital Details',
    distance: 'Distance',
    km: 'km',
    fromYourLocation: 'From your location',
    estimatedTime: 'Estimated Time',
    minutes: 'mins',
    byCar: 'By car',
    requiredBloodType: 'Required Blood Type',
    urgency: 'Urgency',
    requiredUnits: 'Required Units',
    units: 'units',
    startNavigation: 'Start Navigation Now'
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

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const translateHospital = (name) => currentLanguage.value === 'en' ? (hospitalDict[name] || name) : name;
const translateLocation = (loc) => currentLanguage.value === 'en' ? (locationDict[loc] || loc) : loc;

const translateUrgency = (urgency) => {
  if (currentLanguage.value === 'en') {
    if (urgency === 'حرجة جداً' || urgency === 'حرجة') return 'Critical';
    if (urgency === 'عالية') return 'High';
    if (urgency === 'متوسطة') return 'Medium';
    if (urgency === 'منخفضة') return 'Low';
  }
  return urgency;
};

const getImageUrl = (fileName) => {
  return new URL(`../../../assets/images/${fileName}`, import.meta.url).href;
};

const handleHospitalFallback = (e) => {
  e.target.src = getImageUrl('hospital.png');
};

const selectedHospital = ref(null);

watchEffect(() => {
  if (props.requests && props.requests.length > 0) {
    selectedHospital.value = props.requests[0];
  }
});

const closeCard = () => {
  selectedHospital.value = null;
};
</script>

<style scoped>
.map-tab-container {
  font-family: Arial, sans-serif !important;
  min-height: 450px;
}
.map-iframe { height: 450px; }

@media (min-width: 768px) {
  .map-tab-container { min-height: 600px; }
  .map-iframe { height: 600px; }
}

.hospital-detail-card {
  bottom: 15px;
  right: 15px;
  left: 15px;
  width: auto;
  z-index: 10;
  border: 1px solid #e2e8f0;
}

@media (min-width: 768px) {
  .hospital-detail-card {
    top: 25px;
    right: 25px;
    left: auto;
    bottom: auto;
    width: 360px;
  }
  .dir-ltr.hospital-detail-card {
    right: 25px !important;
    left: auto !important;
  }
}

.hospital-info-box {
  width: 100%;
}

.hospital-text-container {
  max-width: calc(100% - 70px);
}

.hospital-img-fixed-box {
  width: 60px;
  height: 48px;
  overflow: hidden;
  border-radius: 6px;
}

.hospital-fixed-img {
  width: 100% !important;
  height: 100% !important;
  object-fit: cover !important;
  display: block;
}

.bg-pink-light { background-color: #fdecec; }
.bg-light-subtle { background-color: #f8fafc; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.border-danger-subtle { border-color: #fca5a5 !important; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
</style>
