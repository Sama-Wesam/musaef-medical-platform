<template>
  <div
    class="donor-dashboard-page bg-light-gray min-vh-100 pb-5"
    :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'"
  >
    <!-- الهيدر -->
    <DonorHeader />

    <main class="container-fluid px-2 px-md-4 pt-3">
      <!-- مؤشر التحميل -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">{{ t('loadingDashboard') }}</span>
        </div>
        <p class="text-muted mt-2 fs-8">{{ t('loadingDashboard') }}</p>
      </div>

      <template v-else>
        <!-- 1. البانر العلوي الذكي (حالة الأهلية والعد التنازلي) -->
        <DonorHeroBanner :stats="stats" />

        <!-- 2. الكروت الإحصائية الأربعة -->
        <DonorStatsCards :stats="stats" />

        <!-- 3. الأقسام السفلية (الإشعارات العاجلة، الطلبات المقترحة، والحالة الفارغة) -->
        <div class="row g-3 g-lg-4">

          <!-- العمود الأول: إشعارات عاجلة (Urgent Alerts) -->
          <div class="col-12 col-lg-6 col-xl-4">
            <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column justify-content-between text-start">
              <div>
                <h5 class="fw-bold text-dark mb-3 mb-md-4 text-start fs-6 fs-md-5">{{ t('urgentRequestsTitle') }}</h5>
                <div class="d-flex flex-column gap-2.5 gap-md-3">
                  <div v-for="item in notifications" :key="item.id" class="p-2.5 p-md-3 bg-pink-light rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-2 text-start">
                    <div class="d-flex align-items-center gap-2 text-start min-w-0">
                      <div class="drop-circle-icon bg-white shadow-2xs flex-shrink-0">
                        <img :src="bloodIconImg" alt="دم" class="notice-blood-icon" />
                      </div>
                      <div class="min-w-0">
                        <!-- اسم المستشفى مترجم بالكامل -->
                        <h6 class="fw-bold text-danger mb-0.5 fs-7 text-truncate text-start">
                          {{ translateHospitalName(item.hospital || item.hospital_name || item.title) }}
                        </h6>
                        <p class="text-dark fs-8 mb-0.5 fw-medium text-truncate text-start">
                          {{ t('urgentCondition') }} {{ item.blood || item.blood_type || item.blood_type_name }}
                        </p>
                        <small class="text-muted fs-9 d-block text-start">{{ item.created_at || 'Just now' }}</small>
                      </div>
                    </div>
                    <div class="ms-auto ms-sm-0">
                      <span class="badge bg-pink-dark text-danger rounded-pill px-2.5 py-1 fs-9 fw-bold">{{ t('urgentPriority') }}</span>
                    </div>
                  </div>

                  <div v-if="!notifications.length" class="text-center text-muted py-4 fs-8">
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

          <!-- العمود الثاني: الطلبات المقترحة بالذكاء الاصطناعي (AI Suggested Requests) -->
          <div class="col-12 col-lg-6 col-xl-5">
            <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column justify-content-between text-start">
              <div>
                <h5 class="fw-bold text-dark mb-3 mb-md-4 text-start fs-6 fs-md-5">{{ t('aiRequestsTitle') }}</h5>
                <div class="d-flex flex-column gap-2.5 gap-md-3">
                  <div v-for="request in suggestedRequests" :key="request.id" class="p-3 border rounded-4 bg-white d-flex align-items-start justify-content-between gap-2 gap-sm-3 shadow-2xs flex-wrap flex-sm-nowrap text-start">
                    <div class="d-flex align-items-start gap-2 gap-sm-3 flex-grow-1 text-start min-w-0">
                      <img :src="request.hospital_image || shifaHospitalImg" alt="المستشفى" class="hospital-card-img rounded-3 flex-shrink-0" @error="handleHospitalFallback" />
                      <div class="min-w-0 text-start">
                        <!-- اسم المستشفى والموقع مترجمان بالكامل -->
                        <h6 class="fw-bold text-dark mb-1 fs-7 text-truncate text-start">
                          {{ translateHospitalName(request.hospital || request.hospital_name) }}
                        </h6>
                        <small class="text-muted d-block fs-9 mb-1 text-truncate text-start">
                          {{ translateLocation(request.location) }}
                        </small>
                        <p class="text-secondary fs-9 mb-1 text-start">
                          {{ t('requiredBlood') }} : {{ request.blood || 'A-' }}
                        </p>
                        <p class="text-secondary fs-9 mb-2 text-start">
                          {{ t('requiredUnits') }} : {{ request.units_needed || 8 }} {{ t('unitsUnit') }}
                        </p>
                        <router-link to="/donor/donation-center" class="btn btn-outline-danger btn-xs rounded-pill px-3 py-1 fs-9 fw-bold text-nowrap text-decoration-none">
                          {{ t('viewDetails') }}
                        </router-link>
                      </div>
                    </div>

                    <div class="d-flex flex-column align-items-center text-center ms-auto ms-sm-0 flex-shrink-0">
                      <span class="badge bg-pink-light text-danger rounded-pill px-2.5 py-1 fs-9 fw-bold mb-2">{{ t('urgentPriority') }}</span>
                      <div class="figma-green-progress-ring position-relative d-flex align-items-center justify-content-center mb-1" :style="getRingStyle(request.match_rate)">
                        <div class="inner-match-circle bg-white rounded-circle d-flex align-items-center justify-content-center">
                          <span class="text-dark fw-bold fs-9">{{ request.match_rate || '94' }}%</span>
                        </div>
                      </div>
                      <small class="text-muted fs-9 text-nowrap">{{ t('matchRate') }}</small>
                    </div>
                  </div>

                  <div v-if="!suggestedRequests.length" class="text-center text-muted py-4 fs-8">
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

          <!-- العمود الثالث: الحالة الفارغة التفاعلية -->
          <div class="col-12 col-xl-3">
            <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column align-items-center justify-content-center text-center text-start">
              <div class="empty-state-icon-wrapper mb-3">
                <img :src="emptyStateImg" alt="لا توجد حالات" class="empty-state-img" @error="handleEmptyStateFallback" />
              </div>
              <h5 class="fw-bold text-dark mb-2 fs-6 text-center">{{ t('noEmergency') }}</h5>
              <p class="text-muted fs-8 mb-4 d-flex align-items-center justify-content-center gap-1 flex-wrap text-center">
                <span>{{ t('thanksHero') }}</span>
                <i class="bi bi-heart text-danger"></i>
              </p>
              <router-link to="/donor/donation-center" class="btn btn-outline-danger rounded-3 px-4 py-2 fw-bold fs-8 shadow-2xs text-decoration-none">
                {{ t('browseAllRequests') }}
              </router-link>
            </div>
          </div>

        </div>
      </template>

      <!-- النافذة المنبثقة لإظهار الإشعارات العاجلة عند الضغط على الرابط -->
      <div v-if="showNotificationsModal" class="modal-backdrop-custom d-flex align-items-center justify-content-center p-3">
        <div class="card border-0 rounded-4 bg-white shadow-lg modal-card p-3 p-md-4">
          <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
            <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">{{ t('urgentRequestsTitle') }}</h5>
            <button type="button" class="btn-close" @click="showNotificationsModal = false"></button>
          </div>
          <div class="modal-body-content overflow-auto pe-1" style="max-height: 60vh;">
            <div v-for="item in notifications" :key="item.id" class="p-3 bg-pink-light rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2 text-start">
              <div class="d-flex align-items-center gap-2 text-start min-w-0">
                <div class="drop-circle-icon bg-white shadow-2xs flex-shrink-0">
                  <img :src="bloodIconImg" alt="دم" class="notice-blood-icon" />
                </div>
                <div class="min-w-0 text-start">
                  <h6 class="fw-bold text-danger mb-0.5 fs-7 text-truncate">
                    {{ translateHospitalName(item.hospital || item.hospital_name || item.title) }}
                  </h6>
                  <p class="text-dark fs-8 mb-0.5 fw-medium text-truncate">
                    {{ t('urgentCondition') }} {{ item.blood || item.blood_type || item.blood_type_name }}
                  </p>
                  <small class="text-muted fs-9 d-block">{{ item.created_at || 'Just now' }}</small>
                </div>
              </div>
              <div class="ms-auto ms-sm-0">
                <span class="badge bg-pink-dark text-danger rounded-pill px-2.5 py-1 fs-9 fw-bold">{{ t('urgentPriority') }}</span>
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
import { ref, computed, onMounted, onUnmounted } from 'vue';
import apiClient from '@/api/axios';
import DonorHeader from '@/components/donor/DonorHeader.vue';
import DonorHeroBanner from '@/components/donor/dashboard/DonorHeroBanner.vue';
import DonorStatsCards from '@/components/donor/dashboard/DonorStatsCards.vue';

import bloodIconImg from '@/assets/icons/blood-icon.png';
import emptyStateImg from '@/assets/icons/Rectangle 22873.png';
import shifaHospitalImg from '@/assets/images/shifa-hospital (2).png';

const loading = ref(true);
const currentLanguage = ref(localStorage.getItem('musaef_lang') || 'ar');
const showNotificationsModal = ref(false);

// متابعة التغيير في اللغة فورياً عند النقر على الهيدر
const updateLanguage = () => {
  currentLanguage.value = localStorage.getItem('musaef_lang') || 'ar';
};

// قاموس الترجمات الكامل للنصوص الثابتة
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

// قاموس الأسماء الديناميكية (المستشفيات)
const hospitalDictionary = {
  'مستشفى أصدقاء المريض الخيري': "Patients' Friends Society Hospital",
  'بنك الدم المركزي - وزارة الصحة': 'Central Blood Bank - Ministry of Health',
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex',
  'المستشفى الإندونيسي': 'Indonesian Hospital',
  'مستشفى الأهلي العربي (المعمداني)': 'Al-Ahli Arab Hospital (Baptist)',
  'مستشفى القدس الطبي': 'Al-Quds Medical Hospital'
};

// قاموس العناوين والمواقع الديناميكية
const locationDictionary = {
  'غزة - فلسطين': 'Gaza - Palestine',
  'غزة - النصر': 'Gaza - An-Naser',
  'غزة - الرمال': 'Gaza - Rimal',
  'شمال غزة - بيت لاهيا': 'North Gaza - Beit Lahia',
  'غزة - الزيتون': 'Gaza - Zaytoun',
  'غزة - تل الهوى': 'Gaza - Tel Al-Hawa'
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const translateHospitalName = (name) => {
  if (!name) return currentLanguage.value === 'en' ? "Patients' Friends Society Hospital" : 'مستشفى أصدقاء المريض الخيري';
  if (currentLanguage.value === 'en') {
    return hospitalDictionary[name] || name;
  }
  return name;
};

const translateLocation = (loc) => {
  if (!loc) return currentLanguage.value === 'en' ? 'Gaza - Palestine' : 'غزة - فلسطين';
  if (currentLanguage.value === 'en') {
    return locationDictionary[loc] || loc;
  }
  return loc;
};

const stats = ref({
  donationsCount: 8,
  points: 230,
  badgesCount: 3,
  daysUntilNextDonation: 12,
  isEligible: true,
  lastDonationText: 'آخر تبرع منذ 45 يوم',
  level: 'متقدم',
  nearbyRequestsCount: 2
});

const notifications = ref([]);
const suggestedRequests = ref([]);

// بيانات افتراضية مجهزة مطابقة للصورة
const defaultAIRequests = [
  {
    id: 101,
    hospital: 'مستشفى أصدقاء المريض الخيري',
    location: 'غزة - فلسطين',
    blood: 'A-',
    units_needed: 8,
    match_rate: 94,
    severity: 'Critical',
    created_at: '2026-08-05 15:28'
  },
  {
    id: 102,
    hospital: 'مستشفى أصدقاء المريض الخيري',
    location: 'غزة - فلسطين',
    blood: 'O+',
    units_needed: 8,
    match_rate: 94,
    severity: 'Critical',
    created_at: '2026-08-05 15:28'
  },
  {
    id: 103,
    hospital: 'مستشفى أصدقاء المريض الخيري',
    location: 'غزة - فلسطين',
    blood: 'O+',
    units_needed: 8,
    match_rate: 94,
    severity: 'Critical',
    created_at: '2026-08-05 02:41'
  }
];

const fetchData = async () => {
  loading.value = true;
  try {
    try {
      const statsRes = await apiClient.get('/public/home-stats');
      const payload = statsRes?.data || statsRes;
      if (payload && payload.success) {
        stats.value.donationsCount = payload.supported_cases ?? stats.value.donationsCount;
        stats.value.nearbyRequestsCount = payload.total_requests ?? stats.value.nearbyRequestsCount;
      }
    } catch (e) {
      console.warn('استخدام الإحصائيات الافتراضية عند تعذر الوصول لـ public/home-stats');
    }

    try {
      const urgentRes = await apiClient.get('/public/urgent-requests');
      const casesData = Array.isArray(urgentRes) ? urgentRes : (urgentRes?.data || []);

      if (Array.isArray(casesData) && casesData.length > 0) {
        notifications.value = casesData.slice(0, 3);
        suggestedRequests.value = casesData.slice(0, 3);
      } else {
        notifications.value = defaultAIRequests;
        suggestedRequests.value = defaultAIRequests;
      }
    } catch (e) {
      console.warn('استخدام الحالات الافتراضية عند تعذر الوصول لـ public/urgent-requests');
      notifications.value = defaultAIRequests;
      suggestedRequests.value = defaultAIRequests;
    }

  } catch (error) {
    console.error('خطأ في جلب بيانات لوحة التحكم:', error);
    notifications.value = defaultAIRequests;
    suggestedRequests.value = defaultAIRequests;
  } finally {
    loading.value = false;
  }
};

const getRingStyle = (rate) => {
  const percentage = rate || 94;
  const degrees = (percentage / 100) * 360;
  return {
    background: `conic-gradient(#22c55e 0deg ${degrees}deg, #e2e8f0 ${degrees}deg 360deg)`
  };
};

onMounted(() => {
  fetchData();
  window.addEventListener('storage', updateLanguage);
  window.addEventListener('language-changed', updateLanguage);
});

onUnmounted(() => {
  window.removeEventListener('storage', updateLanguage);
  window.removeEventListener('language-changed', updateLanguage);
});

const handleHospitalFallback = (e) => { e.target.src = shifaHospitalImg; };
const handleEmptyStateFallback = (e) => { e.target.src = emptyStateImg; };
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
  transition: all 0.3s ease;
}
.inner-match-circle { width: 100%; height: 100%; }

.bg-pink-light { background-color: #fdecec; }
.bg-pink-dark { background-color: #fca5a5; }

.drop-circle-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.notice-blood-icon { width: 18px; height: 18px; object-fit: contain; }
.hospital-card-img { width: 70px; height: 65px; object-fit: cover; flex-shrink: 0; }

.btn-xs { font-size: 0.75rem; padding: 4px 12px; }
.empty-state-img { max-height: 130px; width: auto; object-fit: contain; }

.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }

/* تنسيق النافذة المنبثقة للاشعارات */
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
