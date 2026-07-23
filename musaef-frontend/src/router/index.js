import { createRouter, createWebHistory } from 'vue-router';

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
        path: 'donation-center',
        name: 'DonationCenter',
        component: () => import('@/views/donor/DonationCenter.vue'),
      },
      // 2. صفحة توصيات الذكاء الاصطناعي
      {
        path: 'donation-center/ai',
        name: 'DonationCenterAI',
        component: () => import('@/views/donor/DonationCenterAI.vue'),
      },
      // 3. صفحة الخريطة (انتبهي لاسم الملف لديكِ إذا كان DonationCenteMap.vue)
      {
        path: 'donation-center/map',
        name: 'DonationCenterMap',
        component: () => import('@/views/donor/DonationCenterMap.vue'),
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
      },
      {
        path: 'main-dashboard',
        name: 'MainDashboard',
        component: () => import('@/views/donor/MainDashboard.vue'),
      },
    ],
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
  scrollBehavior() {
    return { top: 0 };
  }
});

// 🚀 السماح المباشر بالمرور بدون مشاكل الـ Store
router.beforeEach((to, from, next) => {
  return next();
});

export default router;
