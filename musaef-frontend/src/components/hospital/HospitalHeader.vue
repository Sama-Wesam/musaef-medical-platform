<template>
  <header class="hospital-header" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">

    <div class="header-container">

      <!-- 1. صورة واسم المستشفى والصفة -->
      <div class="d-flex align-items-center gap-2 gap-sm-3">
        <button class="menu-toggle-btn d-lg-none" @click="toggleMobileSidebar" aria-label="Toggle Navigation">
          <i class="bi bi-list fs-3 text-dark"></i>
        </button>

        <div class="doctor-info">
          <img
            :src="doctorAvatar"
            alt="Doctor"
            class="doctor-avatar"
            @error="handleImageError($event, doctorAvatarImg)"
          />

          <div class="doctor-text d-none d-sm-flex" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'">
            <h6 class="doctor-name">
              {{ translateHospitalName(hospitalDisplayName) }}
            </h6>

            <span class="doctor-role">
              {{ currentLocale === 'en' ? 'Blood Bank Director' : 'مدير بنك الدم' }}
            </span>
          </div>
        </div>
      </div>

      <!-- 2. البحث والتوجيه ومحول اللغة -->
      <div class="header-center">

        <div class="search-box d-none d-md-flex">
          <input
            type="text"
            v-model="search"
            :placeholder="currentLocale === 'en' ? 'Search for patient...' : 'ابحث عن مريض'"
            class="search-input"
            :class="currentLocale === 'ar' ? 'text-end' : 'text-start'"
            @input="handleSearch"
          />

          <img
            :src="searchIcon"
            alt="Search"
            class="search-icon"
            :style="currentLocale === 'en' ? 'left: 14px; right: auto;' : 'right: 14px; left: auto;'"
          />
        </div>

        <div class="d-flex align-items-center gap-2">

          <!-- محول اللغة -->
          <div class="dropdown">
            <button
              class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 fw-semibold d-flex align-items-center gap-1.5 lang-switch-btn"
              type="button"
              id="languageMenuButton"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <i class="bi bi-globe fs-7"></i>
              <span>{{ currentLocale === 'ar' ? 'العربية' : 'English' }}</span>
              <i class="bi bi-chevron-down fs-9"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3 border-0 mt-1" aria-labelledby="languageMenuButton">
              <li>
                <button class="dropdown-item d-flex align-items-center justify-content-between fs-8 py-2" :class="{ 'active fw-bold text-danger bg-light': currentLocale === 'ar' }" @click="switchLanguage('ar')">
                  <span>العربية</span>
                  <span v-if="currentLocale === 'ar'" class="text-danger">✓</span>
                </button>
              </li>
              <li>
                <button class="dropdown-item d-flex align-items-center justify-content-between fs-8 py-2" :class="{ 'active fw-bold text-danger bg-light': currentLocale === 'en' }" @click="switchLanguage('en')">
                  <span>English</span>
                  <span v-if="currentLocale === 'en'" class="text-danger">✓</span>
                </button>
              </li>
            </ul>
          </div>

          <!-- زر إنشاء طلب طارئ -->
          <button class="btn btn-danger fw-bold rounded-pill px-3 py-2 fs-8 d-flex align-items-center gap-1.5 shadow-sm text-nowrap create-emergency-btn" @click="openCreateEmergencyModal">
            <i class="bi bi-plus-circle-fill"></i>
            <span>{{ currentLocale === 'en' ? '+ Create Emergency Request' : '+ إنشاء طلب طارئ' }}</span>
          </button>

        </div>

      </div>

      <!-- 3. الشعار -->
      <div class="header-logo">
        <RouterLink to="/hospital/dashboard">
          <img
            :src="logoImage"
            alt="Musaef Logo"
            class="logo-image"
            @error="handleImageError($event, logoImage)"
          />
        </RouterLink>
      </div>

    </div>

  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { RouterLink, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/authStore";
import { useHospitalStore } from "@/stores/hospitalStore";
import { useNotificationStore } from "@/stores/notificationStore";
import apiClient from "@/api/axios";

import logoImage from '@/assets/images/logo.png';
import searchIcon from '@/assets/icons/Search Icon Container.png';
import doctorAvatarImg from '@/assets/icons/Ellipse 1086.png';

const router = useRouter();
const authStore = useAuthStore();
const hospitalStore = useHospitalStore();
const notificationStore = useNotificationStore();

const search = ref("");
const currentLocale = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const user = computed(() => authStore.user);
const userRole = computed(() => authStore.userRole);

const showNotificationsModal = ref(false);
const isLoadingModal = ref(false);
let pollingInterval = null;

// 1. تفريغ مصفوفة الإشعارات للتعامل مع المزامنة المباشرة فقط من قاعدة البيانات
const rawNotificationsList = ref([]);

const unreadCount = computed(() => notificationStore.unreadCount || rawNotificationsList.value.filter(n => !n.read).length);

const translatedNotifications = computed(() => {
  return rawNotificationsList.value.map(n => ({
    id: n.id,
    title: currentLocale.value === 'en' ? (n.title_en || n.title) : (n.title_ar || n.title),
    message: currentLocale.value === 'en' ? (n.description_en || n.message || n.desc) : (n.description_ar || n.message || n.desc),
    time: currentLocale.value === 'en' ? (n.time_en || n.time || n.created_at) : (n.time_ar || n.time || n.created_at),
    read: n.is_read || n.read || false
  }));
});

const dynamicHospitalName = ref('');

const getStoredHospitalName = () => {
  if (authStore.user?.facility_name) return authStore.user.facility_name;
  if (authStore.user?.name) return authStore.user.name;

  const savedSettings = localStorage.getItem('musaef_hospital_settings');
  if (savedSettings) {
    try {
      const parsed = JSON.parse(savedSettings);
      if (parsed.name) return parsed.name;
    } catch (e) {
      console.error(e);
    }
  }
  return "الجهة الطبية";
};

const updateHospitalName = (event) => {
  if (event?.detail?.name) {
    dynamicHospitalName.value = event.detail.name;
  } else {
    dynamicHospitalName.value = getStoredHospitalName();
  }
};

watch(() => authStore.user, () => {
  dynamicHospitalName.value = getStoredHospitalName();
}, { deep: true });

const fetchLiveNotifications = async (isBackground = false) => {
  try {
    // تم تصحيح المسار إلى المسار الخاص بالمستشفيات لتجنب خطأ 403 Forbidden
    const res = await apiClient.get('/hospital/notifications');
    const data = res?.data?.data || res?.data;
    if (Array.isArray(data)) {
      rawNotificationsList.value = data;
    }
  } catch (err) {
    console.warn('حدث خطأ أثناء جلب إشعارات النظام من الخادم.');
    if (!isBackground) rawNotificationsList.value = [];
  }
};

const handleNotificationClick = (notif) => {
  notif.read = true;
  showNotificationsModal.value = true;
};

const handleViewAllSystemNotifications = () => {
  rawNotificationsList.value.forEach(n => n.read = true);
  showNotificationsModal.value = true;
  isLoadingModal.value = true;

  setTimeout(() => {
    isLoadingModal.value = false;
  }, 600);
};

const handleBloodAlertClick = async () => {
  try {
    await apiClient.get('/hospital/analytics/demand-forecast', {
      params: { blood_type: 'O-', alert: 'critical' }
    });
    alert(currentLocale.value === 'en'
      ? "🤖 AI Report: O- blood group shows critical shortage requiring immediate donation calls."
      : "🤖 تحليل الذكاء الاصطناعي (Blood Demand Forecast):\n- فصيلة O- تسجل نقصاً حرجاً يتطلب إطلاق حملات تبرع عاجلة.");
  } catch (err) {
    alert(currentLocale.value === 'en'
      ? "🚨 Emergency Alert (AI): O- Blood level is critical!"
      : "🚨 تنبيه عاجل (Blood Demand Forecast AI):\nالفصيلة O- في مستوى حرج (متوفر وحدات قليلة جداً).");
  }
};

onMounted(() => {
  dynamicHospitalName.value = getStoredHospitalName();
  window.addEventListener('hospital-name-updated', updateHospitalName);

  // جلب أول للبيانات وتفعيل الـ Polling الدوري
  fetchLiveNotifications();
  pollingInterval = setInterval(() => {
    fetchLiveNotifications(true);
  }, 10000);
});

onUnmounted(() => {
  window.removeEventListener('hospital-name-updated', updateHospitalName);
  if (pollingInterval) clearInterval(pollingInterval);
});

const hospitalDisplayName = computed(() => dynamicHospitalName.value || getStoredHospitalName());

const hospitalDict = {
  'المستشفى الإندونيسي': 'Indonesian Hospital',
  'مستشفى الإندونيسي': 'Indonesian Hospital',
  'المستشفى الإندونيسي – بيت لاهيا': 'Indonesian Hospital – Beit Lahia',
  'مستشفى كمال عدوان': 'Kamal Adwan Hospital',
  'مستشفى كمال عدوان – بيت لاهيا': 'Kamal Adwan Hospital – Beit Lahia',
  'مستشفى العودة - جباليا': 'Al-Awda Hospital - Jabalia',
  'مستشفى العودة – شمال غزة / جباليا': 'Al-Awda Hospital – North Gaza / Jabalia',
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex',
  'مجمع الشفاء الطبي – مدينة غزة': 'Al-Shifa Medical Complex – Gaza City',
  'المستشفى الأهلي العربي (المعمداني)': 'Ahli Arab Hospital (Al-Mamdani)',
  'المستشفى الأهلي العربي (المعمداني) – مدينة غزة': 'Ahli Arab Hospital (Al-Mamdani) – Gaza City',
  'مستشفى الأهلي العربي': 'Ahli Arab Hospital',
  'مستشفى القدس': 'Al-Quds Hospital',
  'مستشفى القدس – مدينة غزة': 'Al-Quds Hospital – Gaza City',
  'مستشفى أصدقاء المريض الخيري': 'Patient Friends Charitable Hospital',
  'مستشفى أصدقاء المريض الخيري – مدينة غزة': 'Patient Friends Charitable Hospital – Gaza City',
  'مستشفى شهداء الأقصى': 'Al-Aqsa Martyrs Hospital',
  'مستشفى شهداء الأقصى – دير البلح': 'Al-Aqsa Martyrs Hospital – Deir al-Balah',
  'مستشفى العودة - النصيرات': 'Al-Awda Hospital - Nuseirat',
  'مستشفى العودة – النصيرات': 'Al-Awda Hospital – Nuseirat',
  'مجمع ناصر الطبي': 'Nasser Medical Complex',
  'مجمع ناصر الطبي – خان يونس': 'Nasser Medical Complex – Khan Younis',
  'المستشفى الأوروبي': 'Gaza European Hospital',
  'المستشفى الأوروبي – خان يونس': 'Gaza European Hospital – Khan Younis',
  'مستشفى غزة الأوروبي': 'Gaza European Hospital',
  'مستشفى الهلال الأحمر الفلسطيني': 'Palestine Red Crescent Hospital',
  'مستشفى الهلال الأحمر الفلسطيني – خان يونس': 'Palestine Red Crescent Hospital – Khan Younis',
  'مستشفى أبو يوسف النجار': 'Abu Yousef Al-Najjar Hospital',
  'مستشفى أبو يوسف النجار – رفح': 'Abu Yousef Al-Najjar Hospital – Rafah',
  'مستشفى الكويت التخصصي': 'Kuwait Specialized Hospital',
  'مستشفى الكويت التخصصي – رفح': 'Kuwait Specialized Hospital – Rafah',
  'جمعية بنك الدم المركزي': 'Central Blood Bank Society',
  'بنك الدم المركزي - وزارة الصحة': 'Central Blood Bank - Ministry of Health',
  'الجهة الطبية': 'Medical Facility'
};

const translateHospitalName = (name) => {
  if (!name) return currentLocale.value === 'en' ? 'Medical Facility' : 'الجهة الطبية';
  if (currentLocale.value !== 'en') return name;

  const trimmedName = name.trim();
  if (hospitalDict[trimmedName]) {
    return hospitalDict[trimmedName];
  }

  return trimmedName
    .replace(/المستشفى الإندونيسي/g, 'Indonesian Hospital')
    .replace(/مستشفى الإندونيسي/g, 'Indonesian Hospital')
    .replace(/مستشفى كمال عدوان/g, 'Kamal Adwan Hospital')
    .replace(/مستشفى العودة/g, 'Al-Awda Hospital')
    .replace(/مجمع الشفاء الطبي/g, 'Al-Shifa Medical Complex')
    .replace(/المستشفى الأهلي العربي/g, 'Ahli Arab Hospital')
    .replace(/مستشفى القدس/g, 'Al-Quds Hospital')
    .replace(/مستشفى أصدقاء المريض الخيري/g, 'Patient Friends Charitable Hospital')
    .replace(/مستشفى شهداء الأقصى/g, 'Al-Aqsa Martyrs Hospital')
    .replace(/مجمع ناصر الطبي/g, 'Nasser Medical Complex')
    .replace(/المستشفى الأوروبي/g, 'European Hospital')
    .replace(/مستشفى غزة الأوروبي/g, 'Gaza European Hospital')
    .replace(/مستشفى الهلال الأحمر الفلسطيني/g, 'Palestine Red Crescent Hospital')
    .replace(/مستشفى أبو يوسف النجار/g, 'Abu Yousef Al-Najjar Hospital')
    .replace(/مستشفى الكويت التخصصي/g, 'Kuwait Specialized Hospital')
    .replace(/بيت لاهيا/g, 'Beit Lahia')
    .replace(/جباليا/g, 'Jabalia')
    .replace(/شمال غزة/g, 'North Gaza')
    .replace(/مدينة غزة/g, 'Gaza City')
    .replace(/دير البلح/g, 'Deir al-Balah')
    .replace(/النصيرات/g, 'Nuseirat')
    .replace(/خان يونس/g, 'Khan Younis')
    .replace(/رفح/g, 'Rafah')
    .replace(/المعمداني/g, 'Al-Mamdani')
    .replace(/المحافظة الوسطى/g, 'Middle Area')
    .replace(/مستشفى/g, 'Hospital')
    .replace(/مجمع/g, 'Complex')
    .replace(/جمعية/g, 'Society')
    .replace(/بنك الدم/g, 'Blood Bank')
    .replace(/المركزي/g, 'Central')
    .replace(/الطبي/g, 'Medical');
};

const switchLanguage = (lang) => {
  localStorage.setItem('musaef_lang', lang);
  document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
  document.documentElement.setAttribute('lang', lang);
  window.location.reload();
};

const doctorAvatar = computed(() => authStore.user?.avatar || doctorAvatarImg);

const openCreateEmergencyModal = () => {
  const bloodType = prompt(currentLocale.value === 'en' ? "Enter required blood type (e.g. O+, O-, A+):" : "أدخل فصيلة الدم المطلوبة (مثال: O+, O-, A+):", "O+");
  if (!bloodType) return;

  const formattedType = bloodType.trim().toUpperCase();
  const unitsInput = prompt(currentLocale.value === 'en' ? "Enter required units count:" : "أدخل عدد الوحدات المطلوبة:", "3");
  const units = parseInt(unitsInput) || 3;

  window.dispatchEvent(new CustomEvent('trigger-create-emergency', {
    detail: { bloodType: formattedType, units: units }
  }));
};

const handleSearch = () => {
  if (hospitalStore.searchPatients) {
    hospitalStore.searchPatients(search.value);
  }
};

const toggleMobileSidebar = () => {
  const sidebar = document.querySelector('.hospital-sidebar');
  const backdrop = document.querySelector('.sidebar-backdrop');
  if (sidebar) sidebar.classList.toggle('show-mobile');
  if (backdrop) backdrop.classList.toggle('show');
};

const handleImageError = (e, fallback) => {
  e.target.src = fallback;
};
</script>

<style scoped>
.hospital-header {
    width: 100%;
    height: 80px;
    background: #ffffff;
    border-bottom: 1px solid #ececec;
    display: flex;
    align-items: center;
    justify-content: center;
    position: sticky;
    top: 0;
    z-index: 999;
}

@media (min-width: 992px) {
    .hospital-header { height: 96px; }
}

.header-container {
    width: 100%;
    max-width: 1600px;
    height: 100%;
    padding: 0 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

@media (min-width: 768px) {
    .header-container { padding: 0 28px; }
}

.menu-toggle-btn {
    background: transparent;
    border: none;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.doctor-info {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 12px;
}

@media (min-width: 992px) {
    .doctor-info { min-width: 220px; gap: 16px; }
}

.doctor-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e7eb;
}

@media (min-width: 768px) {
    .doctor-avatar { width: 58px; height: 58px; }
}

.doctor-text {
    display: flex;
    flex-direction: column;
}

.doctor-name {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    line-height: 1.3;
}

@media (min-width: 992px) { .doctor-name { font-size: 21px; } }

.doctor-role {
    margin-top: 2px;
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
}

.header-center {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
}

@media (min-width: 992px) { .header-center { flex: 1; gap: 24px; } }

.search-box {
    width: 220px;
    height: 42px;
    background: #f8f8fb;
    border-radius: 14px;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

@media (min-width: 1200px) { .search-box { width: 380px; height: 46px; } }

.search-input {
    width: 100%;
    height: 100%;
    border: none;
    outline: none;
    background: transparent;
    padding: 0 45px 0 15px;
    font-size: 14px;
    color: #374151;
}

.search-input::placeholder { color: #9ca3af; }

.search-icon {
    position: absolute;
    width: 18px;
    height: 18px;
    object-fit: contain;
    opacity: .7;
}

.lang-switch-btn {
    border-color: #e5e7eb;
    color: #374151;
    background-color: #f8fafc;
    height: 38px;
}

.create-emergency-btn {
    background-color: #dc2626;
    border: none;
    color: #ffffff;
    height: 38px;
    transition: background-color 0.2s ease;
}
.create-emergency-btn:hover {
    background-color: #b91c1c;
}

.header-logo {
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

@media (min-width: 992px) { .header-logo { min-width: 210px; } }

.logo-image {
    width: 110px;
    height: auto;
    object-fit: contain;
    user-select: none;
}

@media (min-width: 768px) { .logo-image { width: 140px; } }
@media (min-width: 1200px) { .logo-image { width: 180px; } }

.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
</style>
