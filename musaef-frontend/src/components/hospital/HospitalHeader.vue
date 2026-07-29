<template>
  <header class="hospital-header" dir="rtl">

    <div class="header-container">

      <!-- ==========================================
           1. أقصى اليمين : زر القائمة (للجوال) + بيانات وصورة الطبيب
      =========================================== -->
      <div class="d-flex align-items-center gap-2 gap-sm-3">
        <!-- زر فتح القائمة الجانبية في الشاشات الصغيرة -->
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

          <div class="doctor-text d-none d-sm-flex">
            <h6 class="doctor-name">
              {{ doctor.name }}
            </h6>

            <span class="doctor-role">
              {{ doctor.role }}
            </span>
          </div>
        </div>
      </div>

      <!-- ==========================================
           2. المنتصف : شريط البحث + الإشعارات + منخفض O-
      =========================================== -->
      <div class="header-center">

        <!-- مربع البحث -->
        <div class="search-box d-none d-md-flex">
          <input
            type="text"
            v-model="search"
            placeholder="ابحث عن مريض"
            class="search-input"
            @input="handleSearch"
          />

          <img
            :src="searchIcon"
            alt="Search"
            class="search-icon"
          />
        </div>

        <!-- زر الإشعارات -->
        <button class="notification-btn" @click="openNotifications">
          <img
            :src="bellIcon"
            alt="Notifications"
            class="notification-icon"
          />

          <span class="notification-badge" v-if="notificationsCount > 0">
            {{ notificationsCount }}
          </span>
        </button>

        <!-- بطاقة انخفاض فصيلة الدم -->
        <div class="blood-alert d-none d-sm-flex">
          <span class="blood-text">
            {{ bloodStatus.status }}
          </span>

          <span class="blood-type">
            {{ bloodStatus.type }}
          </span>
        </div>

      </div>

      <!-- ==========================================
           3. أقصى اليسار : شعار منصة مسعف
      =========================================== -->
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
import bellIcon from '@/assets/images/solar_bell-outline.png';
import searchIcon from '@/assets/images/Search Icon Container.png';
import doctorAvatarImg from '@/assets/images/Ellipse 1086.png';

const authStore = useAuthStore();
const hospitalStore = useHospitalStore();

const search = ref("");

const doctor = computed(() => ({
  name: authStore.user?.name || "د. سعيد عبده",
  role: authStore.user?.role || "مدير بنك الدم"
}));

const doctorAvatar = computed(() => authStore.user?.avatar || doctorAvatarImg);
const notificationsCount = computed(() => hospitalStore.notificationsCount || 2);

const bloodStatus = computed(() => hospitalStore.criticalBloodStatus || {
  type: "O-",
  status: "منخفض"
});

const openNotifications = () => {
  if (hospitalStore.toggleNotifications) {
    hospitalStore.toggleNotifications();
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
    align-items: flex-start;
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
    gap: 12px;
}

@media (min-width: 992px) { .header-center { flex: 1; gap: 24px; } }

.search-box {
    width: 260px;
    height: 42px;
    background: #f8f8fb;
    border-radius: 14px;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

@media (min-width: 1200px) { .search-box { width: 480px; height: 46px; } }

.search-input {
    width: 100%;
    height: 100%;
    border: none;
    outline: none;
    background: transparent;
    padding: 0 45px 0 15px;
    text-align: right;
    font-size: 14px;
    color: #374151;
}

.search-input::placeholder { color: #9ca3af; }

.search-icon {
    position: absolute;
    right: 14px;
    width: 18px;
    height: 18px;
    object-fit: contain;
    opacity: .7;
}

.notification-btn {
    width: 38px;
    height: 38px;
    background: transparent;
    border: none;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.notification-btn:hover {
    background: #fafafa;
    border-radius: 50%;
}

.notification-icon {
    width: 22px;
    height: 22px;
    object-fit: contain;
}

.notification-badge {
    position: absolute;
    top: 2px;
    left: 2px;
    width: 16px;
    height: 16px;
    background: #ef4444;
    color: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 9px;
    font-weight: 700;
    border: 2px solid #ffffff;
}

.blood-alert {
    width: 110px;
    height: 38px;
    background: #dc2626;
    border-radius: 999px;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-weight: 700;
    font-size: 14px;
}

@media (min-width: 992px) { .blood-alert { width: 150px; height: 44px; font-size: 18px; } }

.blood-type { direction: ltr; }

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
</style>
