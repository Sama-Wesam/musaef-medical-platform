<template>
  <div class="sidebar-backdrop d-lg-none" @click="closeMobileSidebar"></div>

  <aside class="hospital-sidebar d-flex flex-column justify-content-between" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">

    <div>
      <div class="d-flex justify-content-between align-items-center mb-3 d-lg-none pb-2 border-bottom">
        <h6 class="fw-bold mb-0 text-dark">{{ t('mainMenu') }}</h6>
        <button class="btn-close text-reset shadow-none" @click="closeMobileSidebar"></button>
      </div>

      <nav class="sidebar-menu">

        <RouterLink
          to="/hospital/dashboard"
          class="sidebar-item"
          active-class="sidebar-active"
          @click="closeMobileSidebar"
        >
          <div class="sidebar-item-content">
            <img :src="dashboardIcon" class="sidebar-icon" alt="dashboard" />
            <span class="sidebar-text">{{ t('dashboard') }}</span>
          </div>
        </RouterLink>

        <RouterLink
          to="/hospital/notifications"
          class="sidebar-item"
          active-class="sidebar-active"
          @click="closeMobileSidebar"
        >
          <div class="sidebar-item-content">
            <img :src="bellIcon" class="sidebar-icon" alt="notifications" />
            <span class="sidebar-text">{{ t('notifications') }}</span>
          </div>
        </RouterLink>

        <RouterLink
          to="/hospital/requests"
          class="sidebar-item"
          active-class="sidebar-active"
          @click="closeMobileSidebar"
        >
          <div class="sidebar-item-content">
            <img :src="alertIcon" class="sidebar-icon" alt="requests" />
            <span class="sidebar-text">{{ t('requests') }}</span>
          </div>
        </RouterLink>

        <RouterLink
          to="/hospital/inventory"
          class="sidebar-item"
          active-class="sidebar-active"
          @click="closeMobileSidebar"
        >
          <div class="sidebar-item-content">
            <img :src="bloodPlusIcon" class="sidebar-icon" alt="inventory" />
            <span class="sidebar-text">{{ t('inventory') }}</span>
          </div>
        </RouterLink>

        <RouterLink
          to="/hospital/settings"
          class="sidebar-item"
          active-class="sidebar-active"
          @click="closeMobileSidebar"
        >
          <div class="sidebar-item-content">
            <img :src="settingsIcon" class="sidebar-icon" alt="settings" />
            <span class="sidebar-text">{{ t('settings') }}</span>
          </div>
        </RouterLink>

      </nav>

      <div class="support-card">
        <h5 class="support-title">{{ t('quickHelpTitle') }}</h5>
        <p class="support-text">{{ t('quickHelpText') }}</p>
        <button @click="contactSupport" class="support-btn">{{ t('contactSupport') }}</button>
      </div>

    </div>

    <div class="logout-wrapper">
      <button class="logout-btn" @click="handleLogout">{{ t('logout') }}</button>
    </div>

  </aside>
</template>

<script setup>
import { computed } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';

import dashboardIcon from '@/assets/icons/Frame 2147225925.png';
import bellIcon from '@/assets/icons/solar_bell-outline.png';
import alertIcon from '@/assets/icons/ant-design_alert-twotone.png';
import bloodPlusIcon from '@/assets/icons/mdi_blood-plus-outline.png';
import settingsIcon from '@/assets/icons/material-symbols_settings-outline.png';

const router = useRouter();
const authStore = useAuthStore();
const currentLocale = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    mainMenu: 'القائمة الرئيسية',
    dashboard: 'لوحة التحكم',
    notifications: 'مركز الإشعارات والتنبيهات',
    requests: 'إدارة النداءات الطارئة',
    inventory: 'إدارة بنك الدم',
    settings: 'إعدادات الجهة الطبية',
    quickHelpTitle: 'مساعدة سريعة',
    quickHelpText: 'تحتاج مساعدة؟ فريق الدعم متاح على مدار الساعة',
    contactSupport: 'تواصل مع الدعم',
    logout: 'تسجيل الخروج'
  },
  en: {
    mainMenu: 'Main Menu',
    dashboard: 'Dashboard',
    notifications: 'Notifications & Alerts Center',
    requests: 'Emergency Calls Management',
    inventory: 'Blood Bank Management',
    settings: 'Medical Facility Settings',
    quickHelpTitle: 'Quick Support',
    quickHelpText: 'Need help? Support team is available 24/7',
    contactSupport: 'Contact Support',
    logout: 'Logout'
  }
};

const t = (key) => dictionary[currentLocale.value === 'en' ? 'en' : 'ar'][key] || key;

const contactSupport = () => {
  alert(currentLocale.value === 'en' ? 'Connecting to Musaef technical support...' : 'جاري نقلك للتواصل مع الدعم الفني لمسعف...');
};

const closeMobileSidebar = () => {
  const sidebar = document.querySelector('.hospital-sidebar');
  const backdrop = document.querySelector('.sidebar-backdrop');
  if (sidebar) sidebar.classList.remove('show-mobile');
  if (backdrop) backdrop.classList.remove('show');
};

const handleLogout = async () => {
  closeMobileSidebar();
  if (authStore.logout) {
    await authStore.logout();
  } else {
    authStore.user = null;
    authStore.token = null;
  }
  router.push('/login');
};
</script>

<style scoped>
.sidebar-backdrop {
    position: fixed;
    top: 0; left: 0; width: 100vw; height: 100vh;
    background: rgba(0, 0, 0, 0.4); z-index: 1040;
    opacity: 0; visibility: hidden; transition: all 0.3s ease;
}
.sidebar-backdrop.show { opacity: 1; visibility: visible; }

.hospital-sidebar {
    width: 280px; height: 100%; background: #FFFFFF;
    border-inline-end: 1px solid #ECECEC; padding: 24px 18px;
    transition: all 0.3s ease;
}

@media (max-width: 991.98px) {
    .hospital-sidebar {
        position: fixed; top: 0; height: 100vh; z-index: 1050;
        box-shadow: 0 0 20px rgba(0,0,0,0.1); overflow-y: auto;
    }
}

.sidebar-menu { display: flex; flex-direction: column; gap: 12px; }

.sidebar-item {
    height: 50px; border: 1px solid #E6E6E6; border-radius: 999px;
    display: flex; align-items: center; padding: 0 16px;
    text-decoration: none; color: #1F2937; background: #FFFFFF; transition: .25s;
}

@media (min-width: 1200px) { .sidebar-item { height: 54px; padding: 0 20px; } }

.sidebar-item-content { display: flex; align-items: center; gap: 12px; width: 100%; }
.sidebar-item:hover { background: #FAFAFA; border-color: #DDDDDD; }

.sidebar-active { background: #FCECED; border-color: #FCECED; color: #DC2626; }
.sidebar-active .sidebar-text { color: #DC2626; font-weight: 700; }

.sidebar-active .sidebar-icon {
    filter: brightness(0) saturate(100%) invert(20%) sepia(94%) saturate(3500%) hue-rotate(346deg) brightness(94%) contrast(100%);
}

.sidebar-text { font-size: 14px; font-weight: 600; color: #1F2937; transition: .25s; }
@media (min-width: 1200px) { .sidebar-text { font-size: 15px; } }

.sidebar-icon { width: 20px; height: 20px; object-fit: contain; transition: .25s; }
@media (min-width: 1200px) { .sidebar-icon { width: 23px; height: 23px; } }

.support-card {
    margin-top: 24px; border: 1px solid #F5B4B4; border-radius: 18px;
    background: #FFFFFF; padding: 18px 14px; text-align: center;
}

.support-title { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 10px; }
.support-text { color: #8A8A8A; font-size: 12px; line-height: 1.8; margin-bottom: 16px; }

.support-btn {
    width: 100%; height: 42px; border: none; border-radius: 8px;
    background: #DC2626; color: #FFFFFF; font-size: 14px; font-weight: 700;
    transition: .25s; cursor: pointer;
}
.support-btn:hover { background: #C71F1F; }

.logout-wrapper { padding-top: 20px; }

.logout-btn {
  display: flex; align-items: center; justify-content: center;
  width: 100%; height: 48px; border-radius: 999px; border: 1px solid #DADADA;
  background: #FFFFFF; color: #111827; font-size: 16px; font-weight: 700;
  cursor: pointer; transition: .25s;
}
.logout-btn:hover { background: #F8F8F8; }
</style>
