<template>
  <div
    class="donor-dashboard-page bg-light-gray min-vh-100 pb-5"
    :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'"
  >
    <!-- الهيدر -->
    <DonorHeader />

    <main class="container-fluid px-2 px-md-4 pt-3">
      <!-- مؤشر التحميل الأولاني -->
      <div v-if="loading && !stats.isLoaded" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">{{ t('loadingDashboard') }}</span>
        </div>
        <p class="text-muted mt-2 fs-8">{{ t('loadingDashboard') }}</p>
      </div>

      <template v-else>
        <!-- 1. البانر العلوي الذكي (حالة الأهلية والعد التنازلي) -->
        <DonorHeroBanner :stats="stats" :current-language="currentLanguage" />

        <!-- 2. الكروت الإحصائية الأربعة -->
        <DonorStatsCards :stats="stats" :current-language="currentLanguage" />

        <!-- 3. الأقسام السفلية (الإشعارات العاجلة، الطلبات المقترحة، والحالة الفارغة) -->
        <div class="row g-3 g-lg-4">

          <!-- العمود الأول: إشعارات عاجلة (Urgent Alerts) -->
          <div class="col-12 col-lg-6 col-xl-4">
            <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column justify-content-between text-start overflow-hidden">
              <div>
                <h5 class="fw-bold text-dark mb-3 mb-md-4 text-start fs-6 fs-md-5">{{ currentLanguage === 'ar' ? 'إشعارات عاجلة' : 'Urgent Alerts' }}</h5>
                <div class="d-flex flex-column gap-2.5 gap-md-3">
                  <div
                    v-for="(item, index) in safeNotifications.slice(0, 3)"
                    :key="item?.id || item?._id || index"
                    class="p-2.5 p-md-3 bg-pink-light rounded-4 d-flex align-items-start justify-content-between gap-2 text-start cursor-pointer w-100 overflow-hidden"
                    @click="navigateTo('/donor/donation-center')"
                  >
                    <div class="d-flex align-items-start gap-2.5 text-start w-100 min-w-0">
                      <div class="drop-circle-icon bg-white shadow-2xs flex-shrink-0 mt-1">
                        <img :src="bloodIconImg" :alt="currentLanguage === 'ar' ? 'دم' : 'Blood'" class="notice-blood-icon" />
                      </div>
                      <div class="flex-grow-1 min-w-0 overflow-hidden">
                        <h6 class="fw-bold text-danger mb-1 fs-7 text-truncate text-start">
                          {{ getNotifTitle(item) }}
                        </h6>
                        <p class="text-dark fs-8 mb-1 fw-medium text-truncate text-start">
                          {{ getNotifMessage(item) }}
                        </p>
                        <small class="text-muted fs-9 d-block text-start text-truncate">{{ formatTime(item?.created_at || item?.time) }}</small>
                      </div>
                    </div>
                  </div>

                  <div v-if="!safeNotifications.length" class="text-center text-muted py-4 fs-8">
                    {{ t('noUrgentNotifs') }}
                  </div>
                </div>
              </div>

              <div class="text-center mt-4">
                <button type="button" class="btn btn-link text-danger fw-bold fs-8 text-decoration-none p-0 border-0" @click="showNotificationsModal = true">
                  {{ t('viewAllNotifications') }}
                </button>
              </div>
            </div>
          </div>

          <!-- العمود الثاني: الطلبات المقترحة بالذكاء الاصطناعي - يقتصر على 3 كروت -->
          <div class="col-12 col-lg-6 col-xl-5">
            <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column justify-content-between text-start overflow-hidden">
              <div>
                <h5 class="fw-bold text-dark mb-3 mb-md-4 text-start fs-6 fs-md-5">{{ t('aiRequestsTitle') }}</h5>
                <div class="d-flex flex-column gap-2.5 gap-md-3">
                  <div
                    v-for="(request, index) in safeSuggestedRequests.slice(0, 3)"
                    :key="request?.id || request?._id || index"
                    class="p-3 border rounded-4 bg-white d-flex align-items-start justify-content-between gap-2 gap-sm-3 shadow-2xs flex-wrap flex-sm-nowrap text-start cursor-pointer w-100 overflow-hidden"
                    @click="navigateTo('/donor/donation-center')"
                  >
                    <div class="d-flex align-items-start gap-2 gap-sm-3 flex-grow-1 text-start min-w-0 overflow-hidden">
                      <img :src="request?.hospital_image || shifaHospitalImg" :alt="currentLanguage === 'ar' ? 'المستشفى' : 'Hospital'" class="hospital-card-img rounded-3 flex-shrink-0" @error="handleHospitalFallback" />
                      <div class="min-w-0 text-start flex-grow-1 overflow-hidden">
                        <h6 class="fw-bold text-dark mb-1 fs-7 text-truncate text-start">
                          {{ translateHospitalName(request?.hospital || request?.hospital_name || request?.facility_name) }}
                        </h6>
                        <small class="text-muted d-block fs-9 mb-1 text-truncate text-start">
                          {{ translateLocation(request?.location || request?.address) }}
                        </small>
                        <p class="text-secondary fs-9 mb-1 text-start text-truncate">
                          {{ t('requiredBlood') }} : {{ formatBloodType(request?.blood || request?.blood_type || request?.blood_type_name) }}
                        </p>
                        <p class="text-secondary fs-9 mb-2 text-start text-truncate">
                          {{ t('requiredUnits') }} : {{ request?.units_needed || request?.units_required || 1 }} {{ t('unitsUnit') }}
                        </p>
                        <router-link to="/donor/donation-center" class="btn btn-outline-danger btn-xs rounded-pill px-3 py-1 fs-9 fw-bold text-nowrap text-decoration-none" @click.stop>
                          {{ t('viewDetails') }}
                        </router-link>
                      </div>
                    </div>

                    <div class="d-flex flex-column align-items-center text-center ms-auto ms-sm-0 flex-shrink-0">
                      <span class="badge bg-pink-light text-danger rounded-pill px-2.5 py-1 fs-9 fw-bold mb-2">{{ t('urgentPriority') }}</span>
                      <div class="figma-green-progress-ring position-relative d-flex align-items-center justify-content-center mb-1" :style="getRingStyle(request?.match_rate)">
                        <div class="inner-match-circle bg-white rounded-circle d-flex align-items-center justify-content-center">
                          <span class="text-dark fw-bold fs-9">{{ request?.match_rate || '90' }}%</span>
                        </div>
                      </div>
                      <small class="text-muted fs-9 text-nowrap">{{ t('matchRate') }}</small>
                    </div>
                  </div>

                  <div v-if="!safeSuggestedRequests.length" class="text-center text-muted py-4 fs-8">
                    {{ t('noProposals') }}
                  </div>
                </div>
              </div>

              <div class="text-center mt-4">
                <router-link to="/donor/donation-center" class="text-danger fw-bold fs-8 text-decoration-none">
                  {{ t('viewAllRequests') }}
                </router-link>
              </div>
            </div>
          </div>

          <!-- العمود الثالث: حالة التوجيه السريعة للمركز -->
          <div class="col-12 col-xl-3">
            <div
              class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column align-items-center justify-content-center text-center cursor-pointer overflow-hidden"
              @click="navigateTo('/donor/donation-center')"
            >
              <div class="empty-state-icon-wrapper mb-3">
                <img :src="emptyStateImg" :alt="currentLanguage === 'ar' ? 'لا توجد حالات' : 'No cases'" class="empty-state-img" @error="handleEmptyStateFallback" />
              </div>
              <h5 class="fw-bold text-dark mb-2 fs-6 text-center">{{ t('noEmergency') }}</h5>
              <p class="text-muted fs-8 mb-4 d-flex align-items-center justify-content-center gap-1 flex-wrap text-center">
                <span>{{ t('thanksHero') }}</span>
                <i class="bi bi-heart text-danger"></i>
              </p>
              <router-link to="/donor/donation-center" class="btn btn-outline-danger rounded-3 px-4 py-2 fw-bold fs-8 shadow-2xs text-decoration-none" @click.stop>
                {{ t('browseAllRequests') }}
              </router-link>
            </div>
          </div>

        </div>
      </template>

      <!-- النافذة المنبثقة لإظهار جميع الإشعارات العاجلة -->
      <div v-if="showNotificationsModal" class="modal-backdrop-custom d-flex align-items-center justify-content-center p-3">
        <div class="card border-0 rounded-4 bg-white shadow-lg modal-card p-3 p-md-4 overflow-hidden">
          <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
            <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">{{ currentLanguage === 'ar' ? 'إشعارات عاجلة' : 'Urgent Alerts' }}</h5>
            <button type="button" class="btn-close" @click="showNotificationsModal = false"></button>
          </div>
          <div class="modal-body-content overflow-auto pe-1" style="max-height: 60vh;">
            <div
              v-for="(item, index) in safeNotifications"
              :key="item?.id || item?._id || index"
              class="p-3 bg-pink-light rounded-4 d-flex align-items-start justify-content-between gap-2 mb-2 text-start cursor-pointer w-100 overflow-hidden"
              @click="navigateTo('/donor/donation-center'); showNotificationsModal = false;"
            >
              <div class="d-flex align-items-start gap-2.5 text-start w-100 min-w-0 overflow-hidden">
                <div class="drop-circle-icon bg-white shadow-2xs flex-shrink-0 mt-1">
                  <img :src="bloodIconImg" :alt="currentLanguage === 'ar' ? 'دم' : 'Blood'" class="notice-blood-icon" />
                </div>
                <div class="min-w-0 text-start flex-grow-1 overflow-hidden">
                  <h6 class="fw-bold text-danger mb-1 fs-7 text-truncate">
                    {{ getNotifTitle(item) }}
                  </h6>
                  <p class="text-dark fs-8 mb-1 fw-medium text-truncate">
                    {{ getNotifMessage(item) }}
                  </p>
                  <small class="text-muted fs-9 d-block text-truncate">{{ formatTime(item?.created_at || item?.time) }}</small>
                </div>
              </div>
            </div>
          </div>
          <div class="text-end pt-3 border-top mt-2">
            <button class="btn btn-secondary btn-sm rounded-pill px-4" @click="showNotificationsModal = false">{{ t('close') }}</button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, onErrorCaptured } from 'vue';
import { useRouter } from 'vue-router';
import apiClient from '@/api/axios';
import { useDonorStore } from '@/stores/donorStore';
import DonorHeader from '@/components/donor/DonorHeader.vue';
import DonorHeroBanner from '@/components/donor/dashboard/DonorHeroBanner.vue';
import DonorStatsCards from '@/components/donor/dashboard/DonorStatsCards.vue';

import bloodIconImg from '@/assets/icons/blood-icon.png';
import emptyStateImg from '@/assets/icons/Rectangle 22873.png';
import shifaHospitalImg from '@/assets/images/shifa-hospital (2).png';

const router = useRouter();
const donorStore = useDonorStore();

const loading = ref(true);
const currentLanguage = ref(localStorage.getItem('musaef_lang') || 'ar');
const showNotificationsModal = ref(false);
let pollingTimer = null;

onErrorCaptured((err, instance, info) => {
  loading.value = false;
  return false;
});

const stats = ref({
  donationsCount: 0,
  points: 0,
  badgesCount: 0,
  daysUntilNextDonation: 0,
  isEligible: true,
  lastDonationText: currentLanguage.value === 'en' ? 'No previous donations recorded' : 'لا توجد تبرعات مسجلة',
  level: currentLanguage.value === 'en' ? 'Beginner' : 'مبتدئ',
  nearbyRequestsCount: 0,
  isLoaded: false
});

const notifications = ref([]);
const suggestedRequests = ref([]);

const safeNotifications = computed(() => {
  if (!Array.isArray(notifications.value)) return [];
  return notifications.value.filter(item => item && typeof item === 'object');
});

const safeSuggestedRequests = computed(() => {
  if (!Array.isArray(suggestedRequests.value)) return [];
  return suggestedRequests.value.filter(req => req && typeof req === 'object');
});

const parseValue = (val) => {
  if (!val) return '';
  if (typeof val === 'string') return val;
  if (typeof val === 'object') {
    return val.name || val.title || val.hospital_name || val.blood_type || val.type || '';
  }
  return String(val);
};

const hospitalDictionary = {
  'مستشفى شهداء الأقصى': 'Al-Aqsa Martyrs Hospital',
  'شهداء الأقصى': 'Al-Aqsa Martyrs Hospital',
  'مستشفى أصدقاء المريض الخيري': "Patients' Friends Society Hospital",
  'مستشفى أصدقاء المريض': "Patients' Friends Hospital",
  'جمعية بنك الدم المركزي': 'Central Blood Bank Society',
  'بنك الدم المركزي': 'Central Blood Bank',
  'بنك الدم المركزي - وزارة الصحة': 'Central Blood Bank - Ministry of Health',
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex',
  'المستشفى الإندونيسي': 'Indonesian Hospital',
  'مستشفى الأهلي العربي (المعمداني)': 'Al-Ahli Arab Hospital (Baptist)',
  'مستشفى القدس الطبي': 'Al-Quds Medical Hospital',
  'مستشفى العودة': 'Al-Awda Hospital'
};

const locationDictionary = {
  'دير البلح': 'Deir al-Balah',
  'مدينة غزة': 'Gaza City',
  'غزة': 'Gaza',
  'شمال غزة / جباليا': 'North Gaza / Jabalia',
  'شمال غزة': 'North Gaza',
  'جباليا': 'Jabalia',
  'خانيونس': 'Khan Younis',
  'رفح': 'Rafah',
  'غزة - الرمال شارع الوحدة': 'Gaza - Rimal, Wehda St.',
  'غزة - الرمال': 'Gaza - Rimal',
  'غزة - فلسطين': 'Gaza - Palestine',
  'غزة - النصر': 'Gaza - An-Naser',
  'شمال غزة - بيت لاهيا': 'North Gaza - Beit Lahia',
  'غزة - الزيتون': 'Gaza - Zaytoun',
  'غزة - تل الهوى': 'Gaza - Tel Al-Hawa'
};

const translateHospitalName = (name) => {
  if (!name || typeof name !== 'string') return currentLanguage.value === 'en' ? 'Central Blood Bank Society' : 'جمعية بنك الدم المركزي';
  if (currentLanguage.value === 'en') {
    if (hospitalDictionary[name]) return hospitalDictionary[name];
    for (const [arKey, enValue] of Object.entries(hospitalDictionary)) {
      if (name.includes(arKey)) return name.replace(arKey, enValue);
    }
    return name;
  }
  return name;
};

const translateLocation = (loc) => {
  if (!loc || typeof loc !== 'string') return currentLanguage.value === 'en' ? 'Gaza - Rimal, Wehda St.' : 'غزة - الرمال شارع الوحدة';
  if (currentLanguage.value === 'en') {
    if (locationDictionary[loc]) return locationDictionary[loc];
    for (const [arKey, enValue] of Object.entries(locationDictionary)) {
      if (loc.includes(arKey)) return loc.replace(arKey, enValue);
    }
    return loc;
  }
  return loc;
};

const getNotifTitle = (notif) => {
  if (!notif) return '';

  const rawHospital = notif.hospital || notif.hospital_name || notif.facility_name;
  const hospital = translateHospitalName(parseValue(rawHospital));

  const rawLocation = notif.location || notif.address || notif.city;
  const location = translateLocation(parseValue(rawLocation));

  if (notif.title && typeof notif.title === 'string' && currentLanguage.value !== 'en') {
    return notif.title;
  }

  if (currentLanguage.value === 'en') {
    let titleStr = hospital ? `Urgent: ${hospital}` : 'Urgent Call!';
    if (location) {
      titleStr += ` – ${location}`;
    }
    return titleStr;
  }

  let titleStr = hospital ? `نداء طوارئ: ${hospital}` : 'نداء طوارئ جديد!';
  if (location) {
    titleStr += ` – ${location}`;
  }
  return titleStr;
};

const getNotifMessage = (notif) => {
  if (!notif) return '';

  const rawBlood = notif.blood || notif.blood_type || notif.blood_type_name;
  const rawHospital = notif.hospital || notif.hospital_name || notif.facility_name;

  const blood = formatBloodType(rawBlood);
  const hospital = translateHospitalName(parseValue(rawHospital));

  if (currentLanguage.value === 'en') {
    return hospital
      ? `Emergency case needs urgent donation for blood type ${blood} at ${hospital}`
      : `Emergency case needs urgent donation for blood type ${blood}`;
  }

  if (notif.message && typeof notif.message === 'string') return notif.message;
  if (notif.desc && typeof notif.desc === 'string') return notif.desc;

  return hospital
    ? `حالة طارئة عاجلة بحاجة للتبرع بفصيلة ${blood} في ${hospital}`
    : `حالة طارئة عاجلة بحاجة للتبرع بفصيلة ${blood}`;
};

const formatBloodType = (val) => {
  if (!val) return 'O+';
  if (typeof val === 'object') {
    return val.name || val.blood_type || 'O+';
  }
  if (typeof val === 'string') {
    const trimmed = val.trim();
    if (trimmed.startsWith('{') && trimmed.endsWith('}')) {
      try {
        const parsed = JSON.parse(trimmed);
        return parsed.name || parsed.blood_type || 'O+';
      } catch (e) {
        return 'O+';
      }
    }
    return trimmed;
  }
  return String(val);
};

const formatTime = (val) => {
  if (!val) return currentLanguage.value === 'en' ? 'Just now' : 'منذ قليل';

  try {
    const date = new Date(val);
    if (isNaN(date.getTime())) return String(val);

    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);

    if (diffInSeconds < 60) return currentLanguage.value === 'en' ? 'Just now' : 'منذ قليل';

    const minutes = Math.floor(diffInSeconds / 60);
    if (minutes < 60) return currentLanguage.value === 'en' ? `${minutes}m ago` : `منذ ${minutes} دقيقة`;

    const hours = Math.floor(minutes / 60);
    if (hours < 24) return currentLanguage.value === 'en' ? `${hours}h ago` : `منذ ${hours} ساعة`;

    const days = Math.floor(hours / 24);
    if (days < 30) return currentLanguage.value === 'en' ? `${days}d ago` : `منذ ${days} يوم`;

    return date.toLocaleDateString(currentLanguage.value === 'en' ? 'en-US' : 'ar-EG');
  } catch (e) {
    return currentLanguage.value === 'en' ? 'Just now' : 'منذ قليل';
  }
};

const navigateTo = (path) => {
  if (path) router.push(path);
};

const updateLanguage = () => {
  currentLanguage.value = localStorage.getItem('musaef_lang') || 'ar';
};

const translations = {
  ar: {
    loadingDashboard: 'جاري تحميل بيانات اللوحة...',
    urgentRequestsTitle: 'إشعارات عاجلة',
    urgentCondition: 'حالة طارئة بحاجة لتبرع عاجل فصيلة',
    urgentPriority: 'أولوية قصوى',
    noUrgentNotifs: 'لا توجد إشعارات عاجلة حالياً.',
    viewAllNotifications: 'عرض جميع الإشعارات',
    aiRequestsTitle: 'الطلبات المقترحة بالذكاء الاصطناعي',
    requiredBlood: 'الفصيلة المطلوبة',
    requiredUnits: 'عدد الوحدات',
    unitsUnit: 'وحدات',
    viewDetails: 'عرض التفاصيل',
    matchRate: 'تطابق مع ملفك',
    noProposals: 'لا توجد مقترحات حالياً.',
    viewAllRequests: 'عرض كل الطلبات',
    noEmergency: 'لا توجد حالات طارئة قريبة حالياً',
    thanksHero: 'شكراً لكونك مستعداً دائماً لإنقاذ حياة',
    browseAllRequests: 'تصفح جميع الطلبات',
    close: 'إغلاق'
  },
  en: {
    loadingDashboard: 'Loading dashboard data...',
    urgentRequestsTitle: 'Urgent Alerts',
    urgentCondition: 'Emergency case needs urgent donation for blood type',
    urgentPriority: 'Top Priority',
    noUrgentNotifs: 'No urgent notifications currently.',
    viewAllNotifications: 'View All Notifications',
    aiRequestsTitle: 'AI Suggested Requests',
    requiredBlood: 'Required Blood Type',
    requiredUnits: 'Required Units',
    unitsUnit: 'units',
    viewDetails: 'View Details',
    matchRate: 'Match with your profile',
    noProposals: 'No suggestions currently.',
    viewAllRequests: 'View All Requests',
    noEmergency: 'No urgent cases nearby currently',
    thanksHero: 'Thank you for always being ready to save a life',
    browseAllRequests: 'Browse All Requests',
    close: 'Close'
  }
};

const t = (key) => {
  if (!key) return '';
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang]?.[key] || key;
};

const fetchData = async (isBackground = false) => {
  if (!isBackground) loading.value = true;
  try {
    try {
      const statsRes = await apiClient.get('/donor/dashboard');
      const payload = statsRes?.data?.data || statsRes?.data || statsRes;
      if (payload && typeof payload === 'object') {
        stats.value = {
          donationsCount: Number(payload.donations_count ?? stats.value.donationsCount ?? 0),
          points: Number(payload.points ?? stats.value.points ?? 0),
          badgesCount: Number(payload.badges_count ?? stats.value.badgesCount ?? 0),
          daysUntilNextDonation: Number(payload.days_until_next_donation ?? stats.value.daysUntilNextDonation ?? 0),
          isEligible: Boolean(payload.is_eligible ?? donorStore?.healthEligibility?.isEligible ?? true),
          lastDonationText: String(payload.last_donation_text || stats.value.lastDonationText || (currentLanguage.value === 'en' ? 'No previous donations recorded' : 'لا توجد تبرعات مسجلة')),
          level: String(payload.level || stats.value.level || (currentLanguage.value === 'en' ? 'Beginner' : 'مبتدئ')),
          nearbyRequestsCount: Number(payload.nearby_requests_count ?? stats.value.nearbyRequestsCount ?? 0),
          isLoaded: true
        };
      }
    } catch (e) {}

    try {
      const urgentRes = await apiClient.get('/donor/urgent-requests');
      const casesData = urgentRes?.data?.data || urgentRes?.data || urgentRes || [];

      if (Array.isArray(casesData)) {
        notifications.value = casesData;
        suggestedRequests.value = casesData;
      } else {
        notifications.value = [];
        suggestedRequests.value = [];
      }
    } catch (e) {
      notifications.value = [];
      suggestedRequests.value = [];
    }

  } catch (error) {
  } finally {
    stats.value.isLoaded = true;
    if (!isBackground) loading.value = false;
  }
};

const getRingStyle = (rate) => {
  const numRate = parseFloat(rate) || 90;
  const degrees = (numRate / 100) * 360;
  return {
    background: `conic-gradient(#22c55e 0deg ${degrees}deg, #e2e8f0 ${degrees}deg 360deg)`
  };
};

onMounted(() => {
  fetchData();

  pollingTimer = setInterval(() => {
    fetchData(true);
  }, 3000);

  window.addEventListener('storage', updateLanguage);
  window.addEventListener('language-changed', updateLanguage);
});

onUnmounted(() => {
  if (pollingTimer) clearInterval(pollingTimer);
  window.removeEventListener('storage', updateLanguage);
  window.removeEventListener('language-changed', updateLanguage);
});

const handleHospitalFallback = (e) => {
  if (e?.target) e.target.src = shifaHospitalImg;
};
const handleEmptyStateFallback = (e) => {
  if (e?.target) e.target.src = emptyStateImg;
};
</script>

<style scoped>
.donor-dashboard-page {
  font-family: Arial, sans-serif !important;
}

.bg-light-gray { background-color: #f8fafc; }

.figma-green-progress-ring {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  padding: 4px;
}
.inner-match-circle { width: 100%; height: 100%; }

.bg-pink-light { background-color: #fdecec; }
.bg-pink-dark { background-color: #fca5a5; }

.drop-circle-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.notice-blood-icon { width: 18px; height: 18px; object-fit: contain; }
.hospital-card-img { width: 70px; height: 65px; object-fit: cover; flex-shrink: 0; }

.btn-xs { font-size: 0.75rem; padding: 4px 12px; }
.empty-state-img { max-height: 130px; width: auto; object-fit: contain; }

.cursor-pointer { cursor: pointer; }

.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }

.text-truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.modal-backdrop-custom {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1050;
}
.modal-card {
  width: 100%;
  max-width: 500px;
}
</style>
