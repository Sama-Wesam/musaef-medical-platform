<template>
  <div class="all-requests-section">
    <!-- 1. شريط الفلترة والبحث -->
    <div class="card border-0 rounded-4 shadow-sm p-3 mb-3 mb-md-4 bg-white">
      <div class="row g-2 align-items-center">
        <div class="col-12 col-md-4 col-lg-3">
          <div class="position-relative">
            <input
              type="text"
              v-model="searchQuery"
              class="form-control form-control-sm pe-3 ps-5 rounded-3 bg-light border-0 py-2 fs-8"
              :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'"
              :placeholder="t('searchPlaceholder')"
            />
            <i class="bi bi-search position-absolute top-50 translate-middle-y text-muted fs-9" :class="currentLanguage === 'ar' ? 'start-0 ms-3' : 'end-0 me-3'"></i>
          </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <select v-model="sortBy" class="form-select form-select-sm rounded-3 py-2 fs-8 text-secondary" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            <option value="latest">{{ t('sortLatest') }}</option>
            <option value="closest">{{ t('sortClosest') }}</option>
          </select>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <select v-model="selectedBloodType" class="form-select form-select-sm rounded-3 py-2 fs-8 text-secondary" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            <option value="">{{ t('bloodTypeAll') }}</option>
            <option value="+A">+A</option>
            <option value="-O">-O</option>
            <option value="+O">+O</option>
            <option value="-B">-B</option>
            <option value="+AB">+AB</option>
            <option value="-A">-A</option>
          </select>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <select v-model="selectedUrgency" class="form-select form-select-sm rounded-3 py-2 fs-8 text-secondary" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            <option value="">{{ t('urgencyAll') }}</option>
            <option value="حرجة">{{ t('urgencyCritical') }}</option>
            <option value="عالية">{{ t('urgencyHigh') }}</option>
            <option value="متوسطة">{{ t('urgencyMedium') }}</option>
          </select>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <select v-model="selectedDistance" class="form-select form-select-sm rounded-3 py-2 fs-8 text-secondary" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            <option value="">{{ t('distanceAll') }}</option>
            <option value="5">{{ t('lessThan5') }}</option>
            <option value="10">{{ t('lessThan10') }}</option>
          </select>
        </div>

        <div class="col-12 col-md-4 col-lg-1">
          <button @click="resetFilters" class="btn btn-outline-secondary btn-sm w-100 rounded-3 py-2 fs-8 fw-bold d-flex align-items-center justify-content-center gap-1 text-nowrap">
            <i class="bi bi-arrow-counterclockwise"></i>
            <span>{{ t('reset') }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- 2. عداد إجمالي الطلبات -->
    <div class="d-flex flex-column mb-3 px-1" :class="currentLanguage === 'ar' ? 'align-items-start text-end' : 'align-items-start text-start'">
      <div class="d-flex align-items-center gap-2 mb-1">
        <h5 class="fw-bold text-dark mb-0 fs-6">{{ t('allRequestsTitle') }}</h5>
        <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1 fs-8 fw-bold">
          {{ filteredRequests.length }} {{ t('requestCountUnit') }}
        </span>
      </div>
      <small class="text-muted fs-9 fs-md-8">{{ t('updatedNow') }}</small>
    </div>

    <!-- حالة التحميل -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-danger mb-2" role="status"></div>
      <p class="text-muted fs-8">{{ t('loadingRequests') }}</p>
    </div>

    <!-- لا توجد طلبات -->
    <div v-else-if="filteredRequests.length === 0" class="text-center py-5 bg-white rounded-4 shadow-2xs mb-4">
      <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
      <p class="text-muted fs-8 mb-0">{{ t('noRequestsFound') }}</p>
    </div>

    <!-- 3. شبكة عرض كروت التبرع -->
    <div v-else class="row g-3 g-md-4 mb-4">
      <div v-for="item in filteredRequests" :key="item.id" class="col-12 col-md-6 col-lg-4">
        <div
          class="card border-0 rounded-4 p-3 bg-white shadow-2xs h-100 d-flex flex-column justify-content-between interactive-card cursor-pointer"
          @click="$emit('select-request', item)"
        >
          <div>
            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
              <div class="d-flex align-items-center gap-2 min-w-0" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <img :src="item.img" alt="مستشفى" class="hospital-box-img rounded-3 flex-shrink-0" @error="handleHospitalFallback" />
                <div class="min-w-0">
                  <h6 class="fw-bold text-dark mb-0 fs-7 text-truncate">{{ translateHospital(item.hospital) }}</h6>
                  <small class="text-muted fs-9 d-block text-truncate">{{ translateLocation(item.location) }}</small>
                </div>
              </div>
              <span class="badge rounded-pill px-2.5 py-1 fs-9 fw-bold flex-shrink-0" :class="getUrgencyBadgeClass(item.urgency)">
                {{ translateUrgency(item.urgency) }}
              </span>
            </div>

            <div class="row text-center align-items-center my-3 py-2 bg-light-subtle rounded-3 g-0 border border-light">
              <div class="col-4">
                <small class="text-muted fs-9 d-block mb-1">{{ t('distance') }}</small>
                <span class="fw-bold text-dark fs-8">{{ item.distance }} {{ t('km') }}</span>
              </div>
              <div class="col-4 border-start border-end">
                <small class="text-muted fs-9 d-block mb-1">{{ t('units') }}</small>
                <span class="fw-bold text-dark fs-8">{{ item.units }}</span>
              </div>
              <div class="col-4">
                <small class="text-muted fs-9 d-block mb-1">{{ t('bloodTypeLabel') }}</small>
                <span class="fw-black text-danger fs-6" dir="ltr">{{ item.bloodType }}</span>
              </div>
            </div>
          </div>

          <button class="btn btn-outline-danger w-100 py-2 fw-bold fs-8 border-danger-subtle figma-btn-radius">
            {{ t('viewDetails') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

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
    searchPlaceholder: 'ابحث عن مستشفى أو فصيلة...',
    sortLatest: 'ترتيب: الأحدث',
    sortClosest: 'الأقرب مسافة',
    bloodTypeAll: 'فصيلة الدم: الكل',
    urgencyAll: 'الخطورة: الكل',
    urgencyCritical: 'حرجة',
    urgencyHigh: 'عالية',
    urgencyMedium: 'متوسطة',
    distanceAll: 'المسافة: الكل',
    lessThan5: 'أقل من 5 كم',
    lessThan10: 'أقل من 10 كم',
    reset: 'إعادة تعيين',
    allRequestsTitle: 'جميع الطلبات',
    requestCountUnit: 'طلب',
    updatedNow: 'تم تحديث البيانات الآن',
    loadingRequests: 'جاري تحميل طلبات التبرع...',
    noRequestsFound: 'لا توجد طلبات تبرع مطابقة للفلتر حالياً.',
    distance: 'المسافة',
    km: 'كم',
    units: 'الوحدات',
    bloodTypeLabel: 'الفصيلة',
    viewDetails: 'عرض التفاصيل'
  },
  en: {
    searchPlaceholder: 'Search hospital or type...',
    sortLatest: 'Sort: Latest',
    sortClosest: 'Closest Distance',
    bloodTypeAll: 'Blood Type: All',
    urgencyAll: 'Urgency: All',
    urgencyCritical: 'Critical',
    urgencyHigh: 'High',
    urgencyMedium: 'Medium',
    distanceAll: 'Distance: All',
    lessThan5: 'Less than 5 km',
    lessThan10: 'Less than 10 km',
    reset: 'Reset',
    allRequestsTitle: 'All Requests',
    requestCountUnit: 'requests',
    updatedNow: 'Data updated just now',
    loadingRequests: 'Loading donation requests...',
    noRequestsFound: 'No donation requests matching filter currently.',
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

const searchQuery = ref('');
const sortBy = ref('latest');
const selectedBloodType = ref('');
const selectedUrgency = ref('');
const selectedDistance = ref('');

const getImageUrl = (fileName) => {
  return new URL(`../../../assets/images/${fileName}`, import.meta.url).href;
};

const handleHospitalFallback = (e) => {
  e.target.src = getImageUrl('hospital.png');
};

const filteredRequests = computed(() => {
  let list = props.requests.filter(item => {
    const translatedName = translateHospital(item.hospital);
    const matchesSearch = !searchQuery.value ||
      item.hospital.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      translatedName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.bloodType.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesBlood = !selectedBloodType.value || item.bloodType === selectedBloodType.value;
    const matchesUrgency = !selectedUrgency.value || item.urgency === selectedUrgency.value;
    const matchesDistance = !selectedDistance.value || item.distance <= parseFloat(selectedDistance.value);
    return matchesSearch && matchesBlood && matchesUrgency && matchesDistance;
  });

  if (sortBy.value === 'closest') {
    list.sort((a, b) => parseFloat(a.distance) - parseFloat(b.distance));
  }
  return list;
});

const resetFilters = () => {
  searchQuery.value = '';
  sortBy.value = 'latest';
  selectedBloodType.value = '';
  selectedUrgency.value = '';
  selectedDistance.value = '';
};

const getUrgencyBadgeClass = (urgency) => {
  if (urgency === 'حرجة' || urgency === 'حرجة جداً' || urgency === 'Critical') return 'bg-danger-subtle text-danger';
  if (urgency === 'متوسطة' || urgency === 'عالية' || urgency === 'Medium' || urgency === 'High') return 'bg-warning-subtle text-warning-emphasis';
  return 'bg-secondary-subtle text-secondary';
};
</script>

<style scoped>
.all-requests-section {
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
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
</style>
