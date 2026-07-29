<template>
  <!-- خلفية معتمة لإغلاق القائمة للجوال -->
  <div class="sidebar-backdrop d-lg-none" @click="closeMobileSidebar"></div>

  <aside class="hospital-sidebar d-flex flex-column justify-content-between" dir="rtl">
    <div>
      <!-- زر إغلاق القائمة في الجوال -->
      <div class="d-flex justify-content-between align-items-center mb-3 d-lg-none pb-2 border-bottom">
        <h6 class="fw-bold mb-0 text-dark">لوحة التحكم الرئيسيّة</h6>
        <button class="btn-close text-reset shadow-none" @click="closeMobileSidebar"></button>
      </div>

      <nav class="sidebar-menu">
        <RouterLink to="/admin/dashboard" class="sidebar-item" active-class="sidebar-active" @click="closeMobileSidebar">
          <div class="sidebar-item-content">
            <img :src="dashboardIcon" class="sidebar-icon" alt="لوحة التحكم" />
            <span class="sidebar-text">لوحة التحكم</span>
          </div>
        </RouterLink>

        <RouterLink to="/admin/radar" class="sidebar-item" active-class="sidebar-active" @click="closeMobileSidebar">
          <div class="sidebar-item-content">
            <img :src="radarIcon" class="sidebar-icon" alt="رادار الطوارئ المباشر" />
            <span class="sidebar-text">رادار الطوارئ المباشر</span>
          </div>
        </RouterLink>

        <RouterLink to="/admin/analytics" class="sidebar-item" active-class="sidebar-active" @click="closeMobileSidebar">
          <div class="sidebar-item-content">
            <img :src="analyticsIcon" class="sidebar-icon" alt="مركز التحليلات الذكية" />
            <span class="sidebar-text">مركز التحليلات الذكية</span>
          </div>
        </RouterLink>

        <RouterLink to="/admin/accounts" class="sidebar-item" active-class="sidebar-active" @click="closeMobileSidebar">
          <div class="sidebar-item-content">
            <img :src="usersIcon" class="sidebar-icon" alt="إدارة الحسابات" />
            <span class="sidebar-text">إدارة الحسابات</span>
          </div>
        </RouterLink>

        <RouterLink to="/admin/settings" class="sidebar-item" active-class="sidebar-active" @click="closeMobileSidebar">
          <div class="sidebar-item-content">
            <img :src="settingsIcon" class="sidebar-icon" alt="الإعدادات المتقدمة" />
            <span class="sidebar-text">الإعدادات المتقدمة</span>
          </div>
        </RouterLink>
      </nav>

      <div class="support-card">
        <h5 class="support-title">مساعدة سريعة</h5>
        <p class="support-text">
          تحتاج مساعدة؟ فريق الدعم متاح على<br />مدار الساعة
        </p>
        <button class="support-btn" @click="contactSupport">تواصل مع الدعم</button>
      </div>
    </div>

    <div class="logout-wrapper">
      <button class="logout-btn" @click="handleLogout">
        تسجيل الخروج
      </button>
    </div>
  </aside>
</template>

<script setup>
import { RouterLink, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/authStore";

import dashboardIcon from "@/assets/images/Frame 2147225921.png";
import radarIcon from "@/assets/images/fluent_radar-20-regular.png";
import analyticsIcon from "@/assets/images/carbon_analytics.png";
import usersIcon from "@/assets/images/mdi_users-outline (2).png";
import settingsIcon from "@/assets/images/solar_settings-linear.png";

const router = useRouter();
const authStore = useAuthStore();

const closeMobileSidebar = () => {
  const sidebar = document.querySelector('.hospital-sidebar');
  const backdrop = document.querySelector('.sidebar-backdrop');
  if (sidebar) sidebar.classList.remove('show-mobile');
  if (backdrop) backdrop.classList.remove('show');
};

const handleLogout = async () => {
  closeMobileSidebar();
  await authStore.logout();
  router.push("/login");
};

const contactSupport = () => {
  alert("جاري تحويلك لخياطة الدعم الفني الخاص بالمنصة...");
};
</script>

<style scoped>
.sidebar-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.4);
  z-index: 1040;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.sidebar-backdrop.show {
  opacity: 1;
  visibility: visible;
}

.hospital-sidebar {
  width: 280px;
  height: 100%;
  background: #FFFFFF;
  border-left: 1px solid #ECECEC;
  padding: 24px 18px;
  transition: all 0.3s ease;
}

@media (max-width: 991.98px) {
  .hospital-sidebar {
    position: fixed;
    top: 0;
    right: -290px;
    height: 100vh;
    z-index: 1050;
    box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
  }

  .hospital-sidebar.show-mobile {
    right: 0;
  }
}

.sidebar-menu {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.sidebar-item {
  height: 50px;
  border: 1px solid #E6E6E6;
  border-radius: 999px;
  display: flex;
  align-items: center;
  padding: 0 16px;
  text-decoration: none;
  color: #1F2937;
  background: #FFFFFF;
  transition: .25s;
}

@media (min-width: 1200px) {
  .sidebar-item { height: 54px; padding: 0 20px; }
}

.sidebar-item-content {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
}

.sidebar-item:hover {
  background: #FAFAFA;
  border-color: #DDDDDD;
}

.sidebar-active {
  background: #FCECED;
  border-color: #FCECED;
  color: #DC2626;
}

.sidebar-active .sidebar-text {
  color: #DC2626;
  font-weight: 700;
}

.sidebar-active .sidebar-icon {
  filter: brightness(0) saturate(100%) invert(20%) sepia(94%) saturate(3500%) hue-rotate(346deg) brightness(94%) contrast(100%);
}

.sidebar-text {
  font-size: 14px;
  font-weight: 600;
  color: #1F2937;
}

@media (min-width: 1200px) { .sidebar-text { font-size: 15px; } }

.sidebar-icon {
  width: 20px;
  height: 20px;
  object-fit: contain;
}

@media (min-width: 1200px) { .sidebar-icon { width: 23px; height: 23px; } }

.support-card {
  margin-top: 24px;
  border: 1px solid #F5B4B4;
  border-radius: 18px;
  background: #FFFFFF;
  padding: 18px 14px;
  text-align: center;
}

@media (min-width: 1200px) { .support-card { margin-top: 32px; padding: 24px 18px; } }

.support-title {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 10px;
}

@media (min-width: 1200px) { .support-title { font-size: 22px; margin-bottom: 14px; } }

.support-text {
  color: #8A8A8A;
  font-size: 12px;
  line-height: 1.8;
  margin-bottom: 16px;
}

@media (min-width: 1200px) { .support-text { font-size: 13px; line-height: 2; margin-bottom: 20px; } }

.support-btn {
  width: 100%;
  height: 42px;
  border: none;
  border-radius: 8px;
  background: #DC2626;
  color: #FFFFFF;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: .25s;
}

@media (min-width: 1200px) { .support-btn { height: 46px; font-size: 15px; } }

.support-btn:hover { background: #C71F1F; }

.logout-wrapper { padding-top: 20px; }

.logout-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 48px;
  border-radius: 999px;
  border: 1px solid #DADADA;
  background: #FFFFFF;
  color: #111827;
  font-size: 16px;
  font-weight: 700;
  cursor: pointer;
  transition: .25s;
}

@media (min-width: 1200px) { .logout-btn { height: 56px; font-size: 18px; } }

.logout-btn:hover { background: #F8F8F8; }
</style>
