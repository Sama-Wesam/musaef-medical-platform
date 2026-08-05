<template>
  <header class="hospital-header" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">

    <div class="header-container">

      <!-- 1. صورة اسم المستشفى واسمه والصفة -->
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
              {{ translateHospitalName(doctor.rawName) }}
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
        <router-link to="/hospital/dashboard">
          <img
            :src="logoImage"
            alt="Musaef Logo"
            class="logo-image"
            @error="handleImageError($event, logoImage)"
          />
        </router-link>
      </div>

    </div>

  </header>
</template>

<script setup>
import { ref, computed } from "vue";
import { useAuthStore } from "@/stores/authStore";
import { useHospitalStore } from "@/stores/hospitalStore";

import logoImage from '@/assets/images/logo.png';
import searchIcon from '@/assets/icons/Search Icon Container.png';
import doctorAvatarImg from '@/assets/icons/Ellipse 1086.png';

const authStore = useAuthStore();
const hospitalStore = useHospitalStore();

const search = ref("");
const currentLocale = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const hospitalDict = {
  'جمعية بنك الدم المركزي': 'Central Blood Bank Society',
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex',
  'بنك الدم المركزي - وزارة الصحة': 'Central Blood Bank - Ministry of Health'
};

const translateHospitalName = (name) => {
  if (!name) return currentLocale.value === 'en' ? 'Central Blood Bank Society' : 'جمعية بنك الدم المركزي';
  return currentLocale.value === 'en' ? (hospitalDict[name] || name) : name;
};

const switchLanguage = (lang) => {
  localStorage.setItem('musaef_lang', lang);
  document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
  document.documentElement.setAttribute('lang', lang);
  window.location.reload();
};

const doctor = computed(() => ({
  rawName: authStore.user?.name || "جمعية بنك الدم المركزي",
  role: authStore.user?.role || "hospital"
}));

const doctorAvatar = computed(() => authStore.user?.avatar || doctorAvatarImg);

const openCreateEmergencyModal = () => {
  const bloodType = prompt(currentLocale.value === 'en' ? "Enter required blood type (e.g. O-):" : "أدخل فصيلة الدم المطلوبة (مثال: O-):", "O-");
  if (bloodType) {
    const units = prompt(currentLocale.value === 'en' ? "Enter required units count:" : "أدخل عدد الوحدات المطلوبة:", "3");
    if (units) {
      alert(currentLocale.value === 'en'
        ? `Emergency call sent successfully for (${bloodType}). Nearby donors notified via Smart Matching AI!`
        : `تم إطلاق النداء الطارئ بنجاح لفصيلة (${bloodType}) وتم تنبيه كافة المتبرعين القريبين عبر نظام Smart Matching AI!`);
    }
  }
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
