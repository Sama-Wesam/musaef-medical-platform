<<<<<<< HEAD
import { createRouter, createWebHistory } from 'vue-router'
=======
import { createRouter, createWebHistory } from 'vue-router';
>>>>>>> c612875508df5400cd64da2b71a92d9c9198e51e

const routes = [
  {
    path: '/',
<<<<<<< HEAD
    redirect: '/hospital/dashboard',
  },

=======
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
>>>>>>> c612875508df5400cd64da2b71a92d9c9198e51e
  {
    path: '/hospital',
    component: () => import('@/layouts/HospitalLayout.vue'),

    children: [
      {
        path: '',
        redirect: '/hospital/dashboard',
      },

      {
        path: 'dashboard',
        name: 'HospitalDashboard',
        component: () => import('@/views/hospital/Dashboard.vue'),
      },

      {
        path: 'notifications',
        name: 'HospitalNotifications',
        component: () =>
          import('@/views/hospital/NotificationsCenter.vue'),
      },

      {
        path: 'requests',
        name: 'EmergencyRequests',
        component: () =>
          import('@/views/hospital/EmergencyRequestsManagement.vue'),
      },

      {
        path: 'inventory',
        name: 'BloodInventory',
        component: () =>
          import('@/views/hospital/BloodInventory.vue'),
      },

      {
        path: 'settings',
        name: 'FacilitySettings',
        component: () =>
          import('@/views/hospital/FacilitySettings.vue'),
      },
    ],
  },

  {
    path: '/:pathMatch(.*)*',
    redirect: '/hospital/dashboard',
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
<<<<<<< HEAD
})

export default router
=======
  scrollBehavior() {
    return { top: 0 };
  }
});

// 🚀 السماح المباشر بالمرور بدون مشاكل الـ Store
router.beforeEach((to, from, next) => {
  return next();
});

export default router;
>>>>>>> c612875508df5400cd64da2b71a92d9c9198e51e
