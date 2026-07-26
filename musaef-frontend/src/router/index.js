import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    redirect: '/hospital/dashboard',
  },

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
})

export default router