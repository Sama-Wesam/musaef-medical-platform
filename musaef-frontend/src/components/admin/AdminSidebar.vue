<template>
  <!-- خلفية معتمة لإغلاق القائمة في الجوال -->
  <div class="sidebar-backdrop d-lg-none" @click="closeMobileSidebar"></div>

  <aside class="hospital-sidebar d-flex flex-column justify-content-between" dir="rtl">
    <div>
      <!-- زر إغلاق القائمة في الجوال -->
      <div class="d-flex justify-content-between align-items-center mb-3 d-lg-none pb-2 border-bottom">
        <h6 class="fw-bold mb-0 text-dark">{{ t('mainDashboard') }}</h6>
        <button class="btn-close text-reset shadow-none" @click="closeMobileSidebar"></button>
      </div>

      <!-- قائمة التنقل الجانبية -->
      <nav class="sidebar-menu">
        <RouterLink to="/admin/dashboard" class="sidebar-item" active-class="sidebar-active" @click="closeMobileSidebar">
          <div class="sidebar-item-content">
            <img :src="dashboardIcon" class="sidebar-icon" alt="Dashboard" />
            <span class="sidebar-text">{{ t('dashboard') }}</span>
          </div>
        </RouterLink>

        <RouterLink to="/admin/radar" class="sidebar-item" active-class="sidebar-active" @click="closeMobileSidebar">
          <div class="sidebar-item-content">
            <img :src="radarIcon" class="sidebar-icon" alt="Emergency Radar" />
            <span class="sidebar-text">{{ t('emergencyRadar') }}</span>
          </div>
        </RouterLink>

        <RouterLink to="/admin/analytics" class="sidebar-item" active-class="sidebar-active" @click="closeMobileSidebar">
          <div class="sidebar-item-content">
            <img :src="analyticsIcon" class="sidebar-icon" alt="Smart Analytics" />
            <span class="sidebar-text">{{ t('smartAnalytics') }}</span>
          </div>
        </RouterLink>

        <RouterLink to="/admin/accounts" class="sidebar-item" active-class="sidebar-active" @click="closeMobileSidebar">
          <div class="sidebar-item-content">
            <img :src="usersIcon" class="sidebar-icon" alt="Accounts Management" />
            <span class="sidebar-text">{{ t('accountsManagement') }}</span>
          </div>
        </RouterLink>

        <RouterLink to="/admin/settings" class="sidebar-item" active-class="sidebar-active" @click="closeMobileSidebar">
          <div class="sidebar-item-content">
            <img :src="settingsIcon" class="sidebar-icon" alt="Advanced Settings" />
            <span class="sidebar-text">{{ t('advancedSettings') }}</span>
          </div>
        </RouterLink>
      </nav>

      <!-- بطاقة المساعدة السريعة -->
      <div class="support-card">
        <h5 class="support-title">{{ t('quickHelp') }}</h5>
        <p class="support-text">{{ t('quickHelpDesc') }}</p>
        <button class="support-btn" @click="contactSupport">{{ t('contactSupport') }}</button>
      </div>

      <!-- زر تسجيل الخروج مباشرة تحت مربع مساعدة سريعة -->
      <div class="logout-wrapper">
        <button class="logout-btn" @click="handleLogout">
          {{ t('logout') }}
        </button>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue';
import { RouterLink, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/authStore";

import dashboardIcon from "@/assets/icons/Frame 2147225921.png";
import radarIcon from "@/assets/icons/fluent_radar-20-regular.png";
import analyticsIcon from "@/assets/icons/carbon_analytics.png";
import usersIcon from "@/assets/icons/mdi_users-outline (2).png";
import settingsIcon from "@/assets/icons/solar_settings-linear.png";

const router = useRouter();
const authStore = useAuthStore();

const currentLocale = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    mainDashboard: 'لوحة التحكم الرئيسيّة',
    dashboard: 'لوحة التحكم',
    emergencyRadar: 'رادار الطوارئ المباشر',
    smartAnalytics: 'مركز التحليلات الذكية',
    accountsManagement: 'إدارة الحسابات',
    advancedSettings: 'الإعدادات المتقدمة',
    quickHelp: 'مساعدة سريعة',
    quickHelpDesc: 'تحتاج مساعدة؟ فريق الدعم متاح على مدار الساعة',
    contactSupport: 'تواصل مع الدعم',
    logout: 'تسجيل الخروج'
  },
  en: {
    mainDashboard: 'Main Dashboard',
    dashboard: 'Dashboard',
    emergencyRadar: 'Live Emergency Radar',
    smartAnalytics: 'Smart Analytics Center',
    accountsManagement: 'Account Management',
    advancedSettings: 'Advanced Settings',
    quickHelp: 'Quick Support',
    quickHelpDesc: 'Need help? Support team is available 24/7',
    contactSupport: 'Contact Support',
    logout: 'Logout'
  }
};

const t = (key) => dictionary[currentLocale.value === 'en' ? 'en' : 'ar'][key] || key;

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
  alert(currentLocale.value === 'en' ? "Redirecting to technical support..." : "جاري تحويلك لخياطة الدعم الفني الخاص بالمنصة...");
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

/* السايدبار مثبت تحت الهيدر وفي الجهة اليمنى بدقة في RTL */
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
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
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
  margin-top: 20px;
  border: 1px solid #F5B4B4;
  border-radius: 18px;
  background: #FFFFFF;
  padding: 16px 14px;
  text-align: center;
}

@media (min-width: 1200px) { .support-card { margin-top: 24px; padding: 20px 18px; } }

.support-title {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 8px;
}

@media (min-width: 1200px) { .support-title { font-size: 20px; margin-bottom: 10px; } }

.support-text {
  color: #8A8A8A;
  font-size: 12px;
  line-height: 1.6;
  margin-bottom: 14px;
}

@media (min-width: 1200px) { .support-text { font-size: 13px; line-height: 1.8; margin-bottom: 16px; } }

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

@media (min-width: 1200px) { .support-btn { height: 44px; font-size: 15px; } }

.support-btn:hover { background: #C71F1F; }

/* زر تسجيل الخروج مباشرة تحت بطاقة الدعم */
.logout-wrapper {
  margin-top: 14px;
  margin-bottom: 12px;
}

.logout-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 46px;
  border-radius: 999px;
  border: 1px solid #DADADA;
  background: #FFFFFF;
  color: #111827;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  transition: .25s;
}

@media (min-width: 1200px) { .logout-btn { height: 50px; font-size: 16px; } }

.logout-btn:hover { background: #F8F8F8; }
</style>
