import { createRouter, createWebHistory } from 'vue-router';
// استدعاء مخزن المصادقة للتحقق من الصلاحيات (Pinia Store)
import { useAuthStore } from '@/stores/authStore';

const routes = [
  // ==========================================
  // 1. صفحات الواجهة العامة (Public Pages)
  // ==========================================
  {
    path: '/',
    name: 'Home',
    component: () => import('@/views/public/HomeView.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/help',
    name: 'HelpCenter',
    component: () => import('@/views/public/HelpCenterView.vue'),
    meta: { requiresAuth: false }
  },
  
  // ==========================================
  // 2. صفحة المصادقة (Auth Page)
  // ==========================================
  {
    path: '/auth',
    component: () => import('@/layouts/AuthLayout.vue'),
    children: [
      {
        path: '',
        name: 'Auth',
        component: () => import('@/views/public/AuthPage.vue'),
        meta: { requiresAuth: false, guestOnly: true }
      }
    ]
  },

  // ==========================================
  // 3. صفحات المتبرع (Donor Pages)
  // ==========================================
  {
    path: '/donor',
    component: () => import('@/layouts/DonorLayout.vue'),
    meta: { requiresAuth: true, role: 'donor' },
    children: [
      { path: '', redirect: '/donor/dashboard' },
      {
        path: 'dashboard',
        name: 'DonorDashboard',
        component: () => import('@/views/donor/Dashboard.vue'),
      },
      {
        path: 'center',
        name: 'DonationCenter',
        component: () => import('@/views/donor/DonationCenter.vue'),
      },
      {
        path: 'achievements',
        name: 'DonorAchievements',
        component: () => import('@/views/donor/Achievements.vue'),
      },
      {
        path: 'settings',
        name: 'DonorSettings',
        component: () => import('@/views/donor/AccountSettings.vue'),
      }
    ]
  },

  // ==========================================
  // 4. صفحات المستشفى (Hospital Pages)
  // ==========================================
  {
    path: '/hospital',
    component: () => import('@/layouts/HospitalLayout.vue'),
    meta: { requiresAuth: true, role: 'hospital' },
    children: [
      { path: '', redirect: '/hospital/dashboard' },
      {
        path: 'dashboard',
        name: 'HospitalDashboard',
        component: () => import('@/views/hospital/Dashboard.vue'),
      },
      {
        path: 'requests',
        name: 'EmergencyRequests',
        component: () => import('@/views/hospital/EmergencyRequestsManagement.vue'),
      },
      {
        path: 'inventory',
        name: 'BloodInventory',
        component: () => import('@/views/hospital/BloodInventory.vue'),
      },
      {
        path: 'settings',
        name: 'FacilitySettings',
        component: () => import('@/views/hospital/FacilitySettings.vue'),
      }
    ]
  },

  // ==========================================
  // 5. صفحات الإدارة (Admin Pages)
  // ==========================================
  {
    path: '/admin',
    component: () => import('@/layouts/AdminLayout.vue'),
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      { path: '', redirect: '/admin/dashboard' },
      {
        path: 'dashboard',
        name: 'AdminDashboard',
        component: () => import('@/views/admin/Dashboard.vue'),
      },
      {
        path: 'radar',
        name: 'LiveRadar',
        component: () => import('@/views/admin/LiveRadar.vue'),
      },
      {
        path: 'analytics',
        name: 'AnalyticsCenter',
        component: () => import('@/views/admin/AnalyticsCenter.vue'),
      },
      {
        path: 'accounts',
        name: 'AccountsManagement',
        component: () => import('@/views/admin/AccountsManagement.vue'),
      },
      {
        path: 'operations',
        name: 'Operations',
        component: () => import('@/views/admin/Operations.vue'),
      },
      {
        path: 'settings',
        name: 'AdvancedSettings',
        component: () => import('@/views/admin/AdvancedSettings.vue'),
      }
    ]
  },

  // ==========================================
  // 6. صفحة الخطأ 404 (Not Found)
  // ==========================================
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('@/views/common/NotFound.vue')
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  // التمرير لأعلى الصفحة عند التنقل
  scrollBehavior() {
    return { top: 0 };
  }
});

// ==========================================
// حراس المسارات (Route Guards) للحماية
// ==========================================
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const isAuthenticated = authStore.isAuthenticated;
  const userRole = authStore.userRole; // 'donor' | 'hospital' | 'admin'

  // إذا كان المسار يخص الزوار فقط (مثل صفحة تسجيل الدخول) والمستخدم مسجل دخول بالفعل
  if (to.meta.guestOnly && isAuthenticated) {
    return next({ path: `/${userRole}/dashboard` }); // توجيهه للوحة التحكم الخاصة به
  }

  // إذا كان المسار يتطلب تسجيل دخول والمستخدم غير مسجل
  if (to.meta.requiresAuth && !isAuthenticated) {
    return next({ name: 'Auth' }); // توجيهه لصفحة الدخول
  }

  // إذا كان المسار محمي بصلاحية معينة (Role-Based Access)
  if (to.meta.requiresAuth && to.meta.role && to.meta.role !== userRole) {
    return next({ name: 'NotFound' }); // أو صفحة "لا تملك صلاحية"
  }

  next(); // السماح بالمرور
});

export default router;