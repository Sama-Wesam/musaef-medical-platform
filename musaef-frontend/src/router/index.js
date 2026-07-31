import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';

// 1. استيراد الصفحات العامة
import Home from '@/views/public/HomeView.vue';
import About from '@/views/public/AboutView.vue';
import BloodGuide from '@/views/public/BloodGuide.vue';

// 2. استيراد صفحات المصادقة
import LoginRegister from '@/views/auth/LoginRegister.vue';
import ForgotPassword from '@/views/auth/ForgotPassword.vue';
import ResetPassword from '@/views/auth/ResetPassword.vue';

// 3. استيراد صفحات بوابة المتبرع
import DonorDashboard from '@/views/donor/Dashboard.vue';
import DonationCenter from '@/views/donor/DonationCenter.vue';
import DonorProfile from '@/views/donor/Profile.vue';
import DonorAchievements from '@/views/donor/AchievementsView.vue';

// 4. استيراد صفحات بوابة المستشفى وبنك الدم
import HospitalDashboard from '@/views/hospital/Dashboard.vue';
import HospitalRequests from '@/views/hospital/EmergencyRequests.vue';
import HospitalInventory from '@/views/hospital/BloodInventory.vue';
import HospitalNotifications from '@/views/hospital/Notifications.vue';
import HospitalSettings from '@/views/hospital/Settings.vue';

// 5. استيراد صفحات لوحة تحكم الإدارة العليا
import AdminDashboard from '@/views/admin/Dashboard.vue';
import AdminLiveRadar from '@/views/admin/LiveRadar.vue';
import AdminAnalytics from '@/views/admin/Analytics.vue';
import AdminAccounts from '@/views/admin/AccountsManagement.vue';
import AdminSettings from '@/views/admin/AdvancedSettings.vue';

const routes = [
  // --- المسارات العامة ---
  { path: '/', name: 'Home', component: Home },
  { path: '/about', name: 'About', component: About },
  { path: '/blood-guide', name: 'BloodGuide', component: BloodGuide },

  // --- مسارات المصادقة والتسجيل ---
  { path: '/login', name: 'Login', component: LoginRegister, meta: { guestOnly: true } },
  { path: '/register', name: 'Register', component: LoginRegister, meta: { guestOnly: true } },
  { path: '/forgot-password', name: 'ForgotPassword', component: ForgotPassword, meta: { guestOnly: true } },
  { path: '/reset-password', name: 'ResetPassword', component: ResetPassword, meta: { guestOnly: true } },

  // --- مسارات بوابة المتبرع ---
  { path: '/donor/dashboard', name: 'DonorDashboard', component: DonorDashboard, meta: { requiresAuth: true, role: 'donor' } },
  { path: '/donor/donation-center', name: 'DonationCenter', component: DonationCenter, meta: { requiresAuth: true, role: 'donor' } },
  { path: '/donor/profile', name: 'DonorProfile', component: DonorProfile, meta: { requiresAuth: true, role: 'donor' } },
  { path: '/donor/achievements', name: 'DonorAchievements', component: DonorAchievements, meta: { requiresAuth: true, role: 'donor' } },

  // --- مسارات بوابة المستشفى وبنك الدم ---
  { path: '/hospital/dashboard', name: 'HospitalDashboard', component: HospitalDashboard, meta: { requiresAuth: true, role: 'hospital' } },
  { path: '/hospital/requests', name: 'HospitalRequests', component: HospitalRequests, meta: { requiresAuth: true, role: 'hospital' } },
  { path: '/hospital/inventory', name: 'HospitalInventory', component: HospitalInventory, meta: { requiresAuth: true, role: 'hospital' } },
  { path: '/hospital/notifications', name: 'HospitalNotifications', component: HospitalNotifications, meta: { requiresAuth: true, role: 'hospital' } },
  { path: '/hospital/settings', name: 'HospitalSettings', component: HospitalSettings, meta: { requiresAuth: true, role: 'hospital' } },

  // --- مسارات بوابة الإدارة العليا ---
  { path: '/admin/dashboard', name: 'AdminDashboard', component: AdminDashboard, meta: { requiresAuth: true, role: 'admin' } },
  { path: '/admin/radar', name: 'AdminLiveRadar', component: AdminLiveRadar, meta: { requiresAuth: true, role: 'admin' } },
  { path: '/admin/analytics', name: 'AdminAnalytics', component: AdminAnalytics, meta: { requiresAuth: true, role: 'admin' } },
  { path: '/admin/accounts', name: 'AdminAccounts', component: AdminAccounts, meta: { requiresAuth: true, role: 'admin' } },
  { path: '/admin/settings', name: 'AdminSettings', component: AdminSettings, meta: { requiresAuth: true, role: 'admin' } },

  // --- إعادة التوجيه للحالات غير الموجودة 404 ---
  { path: '/:pathMatch(.*)*', redirect: '/' }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior() {
    return { top: 0 };
  }
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token') || localStorage.getItem('musaef_token');

  let userRole = localStorage.getItem('user_role');
  if (!userRole) {
    try {
      const authStore = useAuthStore();
      userRole = authStore.userRole;
    } catch (e) {
      userRole = null;
    }
  }

  const getTargetDashboardName = (role) => {
    if (role === 'admin') return 'AdminDashboard';
    if (role === 'hospital' || role === 'blood_bank') return 'HospitalDashboard';
    return 'DonorDashboard';
  };

  // 1. إعادة توجيه المستخدمين المسجلين مسبقاً من صفحات الزوار/المصادقة إلى لوحتهم المناسبة
  if (to.meta.guestOnly && token) {
    const targetName = getTargetDashboardName(userRole);
    if (to.name === targetName) return next();
    return next({ name: targetName });
  }

  // 2. التحقق من مسارات الصلاحيات والـ Authentication للمسارات المحمية
  if (to.meta.requiresAuth) {
    if (!token) {
      if (to.name === 'Login') return next();
      return next({ name: 'Login' });
    }

    // 3. التحقق من مطابقة الصلاحية (Role-Based Access Control)
    if (to.meta.role && userRole && to.meta.role !== userRole) {
      const targetName = getTargetDashboardName(userRole);
      if (to.name === targetName) return next();
      return next({ name: targetName });
    }
  }

  next();
});

export default router;
