<template>
  <header class="hospital-header" dir="rtl">

    <div class="header-container">

      <!-- ==========================================
           1. أقصى اليمين : (صورة المستشفى واسمه) + زر القائمة للجوال
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
           2. المنتصف : مستطيل البحث + زر إنشاء طلب طارئ
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

        <!-- زر بارز لإنشاء طلب طارئ فوري -->
        <button class="btn btn-danger fw-bold rounded-pill px-3 py-2 fs-8 d-flex align-items-center gap-1.5 shadow-sm text-nowrap create-emergency-btn" @click="openCreateEmergencyModal">
          <i class="bi bi-plus-circle-fill"></i>
          <span>+ إنشاء طلب طارئ</span>
        </button>

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
import searchIcon from '@/assets/icons/Search Icon Container.png';
import doctorAvatarImg from '@/assets/icons/Ellipse 1086.png';

const authStore = useAuthStore();
const hospitalStore = useHospitalStore();

const search = ref("");

const doctor = computed(() => ({
  name: authStore.user?.name || "د. سعيد عبده",
  role: authStore.user?.role || "مدير بنك الدم"
}));

const doctorAvatar = computed(() => authStore.user?.avatar || doctorAvatarImg);

const openCreateEmergencyModal = () => {
  const bloodType = prompt("أدخل فصيلة الدم المطلوبة (مثال: O-):", "O-");
  if (bloodType) {
    const units = prompt("أدخل عدد الوحدات المطلوبة:", "3");
    if (units) {
      alert(`تم إطلاق النداء الطارئ بنجاح لفصيلة (${bloodType}) وتم تنبيه كافة المتبرعين القريبين عبر نظام Smart Matching AI!`);
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

.create-emergency-btn {
    background-color: #dc2626;
    border: none;
    color: #ffffff;
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
</style>
