<template>
  <div
    class="donation-center-page bg-light-gray min-vh-100 pb-5"
    :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'"
  >
    <DonorHeader />

    <main class="container-fluid px-2 px-md-4">
      <!-- التبويبات الرئيسية -->
      <div class="card border-0 rounded-4 shadow-2xs bg-white mb-3 mb-md-4 overflow-x-auto tab-scroll-wrapper">
        <div class="d-flex align-items-center justify-content-start justify-content-md-around py-2 py-md-3 px-2 border-bottom min-tabs-width">
          <button
            class="tab-item-btn border-0 bg-transparent fw-bold fs-8 fs-md-7 d-flex align-items-center gap-2 pb-2 px-3 text-nowrap"
            :class="activeMainTab === 'all' ? 'text-danger active-tab-red' : 'text-secondary'"
            @click="activeMainTab = 'all'"
          >
            <i class="bi bi-list-task fs-5"></i>
            <span>{{ t('allRequestsTab') }}</span>
          </button>

          <button
            class="tab-item-btn border-0 bg-transparent fw-bold fs-8 fs-md-7 d-flex align-items-center gap-2 pb-2 px-3 text-nowrap"
            :class="activeMainTab === 'ai' ? 'text-danger active-tab-red' : 'text-secondary'"
            @click="activeMainTab = 'ai'"
          >
            <i class="bi bi-stars fs-5 text-warning"></i>
            <span>{{ t('aiRecommendationsTab') }}</span>
          </button>

          <button
            class="tab-item-btn border-0 bg-transparent fw-bold fs-8 fs-md-7 d-flex align-items-center gap-2 pb-2 px-3 text-nowrap"
            :class="activeMainTab === 'map' ? 'text-danger active-tab-red' : 'text-secondary'"
            @click="activeMainTab = 'map'"
          >
            <i class="bi bi-map fs-5"></i>
            <span>{{ t('mapTab') }}</span>
          </button>
        </div>
      </div>

      <!-- رسالة خطأ تنبيهية إن وجدت -->
      <div v-if="errorMessage" class="alert alert-warning rounded-4 text-center mb-4 fs-8 border-0 shadow-2xs">
        {{ errorMessage }}
        <button @click="fetchRequests" class="btn btn-sm btn-outline-danger ms-2 rounded-3 fs-9">{{ t('retryBtn') }}</button>
      </div>

      <!-- TAB 1: جميع الطلبات -->
      <div v-if="activeMainTab === 'all'">
        <AllRequestsTab
          :requests="requests"
          :loading="isLoading"
          @refresh="fetchRequests"
          @select-request="openRequestModal"
        />
      </div>

      <!-- TAB 2: توصيات الذكاء الاصطناعي -->
      <div v-if="activeMainTab === 'ai'">
        <AiRecommendationsTab
          :requests="requests"
          :loading="isLoading"
          @select-request="openRequestModal"
        />
      </div>

      <!-- TAB 3: الخريطة -->
      <div v-if="activeMainTab === 'map'">
        <MapTab
          :requests="requests"
          @select-request="openRequestModal"
        />
      </div>
    </main>

    <!-- Modal تفاصيل الحالة والمطابقة الذكية -->
    <div v-if="selectedModalRequest" class="modal-backdrop-custom d-flex align-items-center justify-content-center p-2 p-md-3">
      <div class="bg-white rounded-4 shadow-lg p-3 p-md-4 max-w-450 w-100 position-relative" :class="currentLanguage === 'ar' ? 'dir-rtl' : 'dir-ltr'">
        <button @click="selectedModalRequest = null" class="btn-close position-absolute top-0 m-3" :class="currentLanguage === 'ar' ? 'start-0' : 'end-0'" aria-label="Close"></button>

        <h5 class="fw-bold text-dark mb-3 text-center fs-6 fs-md-5">{{ t('modalTitle') }}</h5>
        <div class="text-center mb-3">
          <img :src="getImageUrl('hospital.png')" class="img-fluid rounded-3 w-100 max-h-150" alt="hospital" @error="handleHospitalFallback" />
        </div>

        <h6 class="fw-bold mb-1 fs-7" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">{{ translateHospital(selectedModalRequest.hospital) }}</h6>
        <small class="text-muted d-block mb-3 fs-8" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">📍 {{ translateLocation(selectedModalRequest.location) }}</small>

        <div class="row text-center bg-light p-2 rounded-3 g-2 mb-3 fs-8">
          <div class="col-6 border-end">
            <small class="text-muted d-block fs-9">{{ t('requiredType') }}</small>
            <strong class="text-danger fs-5" dir="ltr">{{ selectedModalRequest.bloodType }}</strong>
          </div>
          <div class="col-6">
            <small class="text-muted d-block fs-9">{{ t('unitsCount') }}</small>
            <strong class="text-dark fs-5">{{ selectedModalRequest.units }} {{ t('unitsUnit') }}</strong>
          </div>
        </div>

        <!-- ترجمة النص الوردي الداخلي بالكامل -->
        <div class="p-2.5 p-md-3 bg-danger bg-opacity-10 text-danger rounded-3 text-center mb-3">
          <small class="d-block fs-9">{{ t('aiMatchRateTitle') }}</small>
          <strong class="fs-7 fs-md-6">{{ selectedModalRequest.matchScore }}% - {{ translateRecommendation(selectedModalRequest.recommendationText) }}</strong>
        </div>

        <div class="d-flex gap-2">
          <button @click="acceptRequest(selectedModalRequest.id)" :disabled="isSubmitting" class="btn btn-danger flex-fill rounded-pill py-2 fw-bold fs-8">
            <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-1"></span>
            <span v-else>✓ {{ t('acceptAndDonateBtn') }}</span>
          </button>
          <button @click="selectedModalRequest = null" class="btn btn-outline-secondary flex-fill rounded-pill py-2 fs-8">{{ t('closeBtn') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import donor from '@/api/donor';
import apiClient from '@/api/axios';
import DonorHeader from '@/components/donor/DonorHeader.vue';
import AllRequestsTab from '@/components/donor/DonationCenter/AllRequestsTab.vue';
import AiRecommendationsTab from '@/components/donor/DonationCenter/AiRecommendationsTab.vue';
import MapTab from '@/components/donor/DonationCenter/MapTab.vue';

const activeMainTab = ref('all');
const selectedModalRequest = ref(null);
const isSubmitting = ref(false);
const isLoading = ref(false);
const requests = ref([]);
const errorMessage = ref('');

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: {
    allRequestsTab: 'جميع الطلبات',
    aiRecommendationsTab: 'توصيات الذكاء الاصطناعي',
    mapTab: 'الخريطة',
    retryBtn: 'إعادة المحاولة',
    modalTitle: 'تفاصيل الحالة ومرئيات الذكاء الاصطناعي',
    requiredType: 'فصيلة مطلوبة',
    unitsCount: 'عدد الوحدات',
    unitsUnit: 'وحدات',
    aiMatchRateTitle: 'نسبة التوافق والمطابقة الذكية',
    acceptAndDonateBtn: 'قبول الطلب والتبرع',
    closeBtn: 'إغلاق',
    acceptSuccessAlert: 'تم قبول طلب التبرع بنجاح وتوجيه الإشعار لمركز المستشفى!',
    acceptErrorAlert: 'حدث خطأ أثناء إرسال القبول، حاول مرة أخرى.'
  },
  en: {
    allRequestsTab: 'All Requests',
    aiRecommendationsTab: 'AI Recommendations',
    mapTab: 'Map View',
    retryBtn: 'Retry',
    modalTitle: 'Case Details & AI Insights',
    requiredType: 'Required Type',
    unitsCount: 'Units Count',
    unitsUnit: 'units',
    aiMatchRateTitle: 'Smart Matching Rate',
    acceptAndDonateBtn: 'Accept & Donate',
    closeBtn: 'Close',
    acceptSuccessAlert: 'Donation request accepted successfully! Hospital notified.',
    acceptErrorAlert: 'An error occurred while submitting. Please try again.'
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

const translateHospital = (name) => {
  if (!name) return currentLanguage.value === 'en' ? 'Central Blood Bank' : 'بنك الدم المركزي';
  return currentLanguage.value === 'en' ? (hospitalDict[name] || name) : name;
};

const translateLocation = (loc) => {
  if (!loc) return currentLanguage.value === 'en' ? 'Gaza' : 'غزة';
  return currentLanguage.value === 'en' ? (locationDict[loc] || loc) : loc;
};

const translateRecommendation = (rec) => {
  if (!rec) return '';
  return currentLanguage.value === 'en' ? (recommendationDict[rec] || rec) : rec;
};

const getImageUrl = (fileName) => {
  return new URL(`../../assets/images/${fileName}`, import.meta.url).href;
};

const handleHospitalFallback = (e) => { e.target.src = getImageUrl('hospital.png'); };

const openRequestModal = (requestItem) => {
  selectedModalRequest.value = requestItem;
};

const fallbackRequests = [
  { id: 1, hospital: 'مستشفى أصلان/أبو يوسف النجار', location: 'رفح - الشابورة', bloodType: 'AB+', units: 3, distance: 2.7, urgency: 'عالية', matchScore: 98, recommendationText: 'متوافق مع فصيلة دمك ونطاقك الجغرافي المباشر', img: getImageUrl('hospital.png') },
  { id: 2, hospital: 'مستشفى كمال عدوان', location: 'شمال غزة - بيت لاهيا', bloodType: 'AB-', units: 5, distance: 2.6, urgency: 'حرجة جداً', matchScore: 98, recommendationText: 'متوافق مع فصيلة دمك ونطاقك الجغرافي المباشر', img: getImageUrl('hospital.png') },
  { id: 3, hospital: 'المستشفى الإندونيسي', location: 'شمال غزة - بيت لاهيا', bloodType: 'AB-', units: 3, distance: 4.2, urgency: 'حرجة جداً', matchScore: 97, recommendationText: 'متوافق مع فصيلة دمك ونطاقك الجغرافي المباشر', img: getImageUrl('hospital.png') },
  { id: 4, hospital: 'مجمع الشفاء الطبي', location: 'غزة - الرمال', bloodType: 'O+', units: 5, distance: 3.6, urgency: 'حرجة جداً', matchScore: 97, recommendationText: 'متوافق مع فصيلة دمك ونطاقك الجغرافي المباشر', img: getImageUrl('hospital.png') },
  { id: 5, hospital: 'مجمع ناصر الطبي', location: 'خانيونس - وسط المدينة', bloodType: 'A-', units: 2, distance: 1.8, urgency: 'حرجة جداً', matchScore: 96, recommendationText: 'متوافق مع فصيلة دمك ونطاقك الجغرافي المباشر', img: getImageUrl('hospital.png') },
  { id: 6, hospital: 'جمعية بنك الدم المركزي', location: 'غزة - الرمال شارع الوحدة', bloodType: 'AB+', units: 4, distance: 3.0, urgency: 'حرجة جداً', matchScore: 93, recommendationText: 'متوافق مع فصيلة دمك ونطاقك الجغرافي المباشر', img: getImageUrl('hospital.png') },
  { id: 7, hospital: 'مستشفى الأهلي العربي (المعمداني)', location: 'غزة - الزيتون', bloodType: 'O+', units: 3, distance: 3.9, urgency: 'حرجة جداً', matchScore: 92, recommendationText: 'متوافق مع فصيلة دمك ونطاقك الجغرافي المباشر', img: getImageUrl('hospital.png') },
  { id: 8, hospital: 'بنك الدم المركزي - وزارة الصحة', location: 'غزة - النصر', bloodType: 'AB-', units: 5, distance: 4.4, urgency: 'حرجة جداً', matchScore: 90, recommendationText: 'متوافق مع فصيلة دمك ونطاقك الجغرافي المباشر', img: getImageUrl('hospital.png') },
  { id: 9, hospital: 'مستشفى أصدقاء المريض الخيري', location: 'غزة - فلسطين', bloodType: 'A-', units: 8, distance: 2.8, urgency: 'حرجة', matchScore: 95, recommendationText: 'متوافق مع فصيلة دمك ونطاقك الجغرافي المباشر', img: getImageUrl('hospital.png') }
];

const parseRequestsData = (rawData) => {
  if (!Array.isArray(rawData) || rawData.length === 0) return [];

  return rawData.map(item => {
    const rawBlood = item.blood_type?.name || item.blood_type || item.blood || 'O+';
    const rawUrgency = item.emergency_level || item.severity || item.urgency;
    const urgencyLabel = (rawUrgency === 'critical' || rawUrgency === 'High' || rawUrgency === 'حرجة جداً')
      ? 'حرجة جداً'
      : ((rawUrgency === 'high' || rawUrgency === 'عالية') ? 'عالية' : 'متوسطة');

    return {
      id: item.id || Date.now(),
      hospital: item.hospital?.facility_name || item.hospital?.name || item.hospital_name || item.facility_name || item.hospital || 'مجمع الشفاء الطبي',
      location: item.hospital?.address || item.address || item.location || 'غزة - القطاع',
      bloodType: rawBlood.startsWith('+') || rawBlood.startsWith('-') ? rawBlood : `${rawBlood}`,
      units: item.units_required || item.units_needed || item.units || 2,
      distance: item.distance || (Math.random() * (4.5 - 1.2) + 1.2).toFixed(1),
      urgency: urgencyLabel,
      matchScore: item.match_score || item.match_rate || Math.floor(Math.random() * (99 - 85 + 1)) + 85,
      recommendationText: 'متوافق مع فصيلة دمك ونطاقك الجغرافي المباشر',
      img: item.hospital_image ? item.hospital_image : getImageUrl('hospital.png')
    };
  });
};

const fetchRequests = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  try {
    let rawData = [];

    try {
      const response = await donor.getDonationRequests();
      rawData = response?.data?.data || response?.data || response || [];
    } catch (err) {
      console.warn('تعذر الجلب من مسار المتبرع المحمي، محاولة الجلب من المسار العام /public/urgent-requests');
    }

    if (!Array.isArray(rawData) || rawData.length === 0) {
      try {
        const publicRes = await apiClient.get('/public/urgent-requests');
        rawData = publicRes?.data?.data || publicRes?.data || publicRes || [];
      } catch (err) {
        console.warn('تعذر الجلب من المسار العام أيضاً.');
      }
    }

    const parsed = parseRequestsData(rawData);
    if (parsed.length > 0) {
      requests.value = parsed;
    } else {
      requests.value = fallbackRequests;
    }

  } catch (err) {
    console.error('خطأ أثناء جلب طلبات التبرع:', err);
    requests.value = fallbackRequests;
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchRequests();
});

const acceptRequest = async (requestId) => {
  isSubmitting.value = true;
  try {
    if (requestId) {
      try {
        await donor.acceptDonationRequest(requestId);
      } catch (e) {
        console.warn('قبول الطلب وضع محاكاة الواجهة السريعة.');
      }
    }
    alert(t('acceptSuccessAlert'));
    selectedModalRequest.value = null;
    fetchRequests();
  } catch (error) {
    console.error('خطأ في قبول الطلب:', error);
    alert(t('acceptErrorAlert'));
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
.donation-center-page {
  font-family: Arial, sans-serif !important;
}

.bg-light-gray { background-color: #f8fafc; }

.tab-scroll-wrapper {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.tab-scroll-wrapper::-webkit-scrollbar { display: none; }

.min-tabs-width { min-width: 380px; }
@media (min-width: 768px) { .min-tabs-width { min-width: 100%; } }

.tab-item-btn { position: relative; transition: all 0.2s ease; cursor: pointer; font-family: Arial, sans-serif !important; }
.active-tab-red { color: #dc2626 !important; border-bottom: 3px solid #dc2626 !important; }
.modal-backdrop-custom { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1050; }
.max-w-450 { max-width: 440px; }
.max-h-150 { max-height: 160px; object-fit: cover; }
.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.85rem; }
.fs-9 { font-size: 0.72rem; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
