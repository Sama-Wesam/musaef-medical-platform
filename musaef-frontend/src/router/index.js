import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';

// 1. استيراد مباشر لصفحة الهبوط الرئيسية للتحميل السريع الأولي (Static Import)
import Home from '@/views/public/HomeView.vue';

// التحميل المتأخر لباقي الصفحات لتقسيم الكود (Lazy Loading & Code Splitting)

// الصفحات العامة الفرعية
const About = () => import('@/views/public/AboutView.vue');
const BloodGuide = () => import('@/views/public/BloodGuide.vue');

// 2. صفحات المصادقة
const LoginRegister = () => import('@/views/auth/LoginRegister.vue');
const ForgotPassword = () => import('@/views/auth/ForgotPassword.vue');
const ResetPassword = () => import('@/views/auth/ResetPassword.vue');

// 3. صفحات بوابة المتبرع
const DonorDashboard = () => import('@/views/donor/Dashboard.vue');
const DonationCenter = () => import('@/views/donor/DonationCenter.vue');
const DonorProfile = () => import('@/views/donor/Profile.vue');
const DonorAchievements = () => import('@/views/donor/AchievementsView.vue');

// 4. صفحات بوابة المستشفى وبنك الدم
const HospitalDashboard = () => import('@/views/hospital/Dashboard.vue');
const HospitalRequests = () => import('@/views/hospital/EmergencyRequests.vue');
const HospitalInventory = () => import('@/views/hospital/BloodInventory.vue');
const HospitalNotifications = () => import('@/views/hospital/Notifications.vue');
const HospitalSettings = () => import('@/views/hospital/Settings.vue');

// 5. صفحات لوحة تحكم الإدارة العليا
const AdminDashboard = () => import('@/views/admin/Dashboard.vue');
const AdminLiveRadar = () => import('@/views/admin/LiveRadar.vue');
const AdminAnalytics = () => import('@/views/admin/Analytics.vue');
const AdminAccounts = () => import('@/views/admin/AccountsManagement.vue');
const AdminSettings = () => import('@/views/admin/AdvancedSettings.vue');

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

// Navigation Guard متوافق 100% مع Vue Router 4 ومعتمد على إرجاع القيم بدون next() المطرودة
router.beforeEach((to) => {
  const token = localStorage.getItem('token') || localStorage.getItem('musaef_token');

  // استخراج دور المستخدم بأمان تام من جميع المفاتيح المحتملة
  let userRole = localStorage.getItem('user_role');

  if (!userRole) {
    const rawUser = localStorage.getItem('user') || localStorage.getItem('musaef_user');
    if (rawUser) {
      try {
        const parsed = JSON.parse(rawUser);
        userRole = parsed.role || parsed.type || null;
      } catch (e) {
        userRole = null;
      }
    }
  }

  if (!userRole) {
    try {
      const authStore = useAuthStore();
      userRole = authStore.userRole || authStore.user?.role;
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
    if (to.name !== targetName) {
      return { name: targetName };
    }
  }

  // 2. التحقق من مسارات الصلاحيات والـ Authentication للمسارات المحمية
  if (to.meta.requiresAuth) {
    if (!token) {
      if (to.name !== 'Login') {
        return { name: 'Login' };
      }
    }

    // 3. التحقق من مطابقة الصلاحية (Role-Based Access Control) مع دعم مرن لبنك الدم والمستشفى
    if (to.meta.role && userRole && userRole !== 'admin') {
      const isHospitalRole = (to.meta.role === 'hospital' && (userRole === 'hospital' || userRole === 'blood_bank'));
      const isExactMatch = to.meta.role === userRole;

      if (!isHospitalRole && !isExactMatch) {
        const targetName = getTargetDashboardName(userRole);
        if (to.name !== targetName) {
          return { name: targetName };
        }
      }
    }
  }

  return true;
});

export default router;
