<template>
  <header
    class="top-navbar bg-white py-2 py-md-3 px-3 px-md-4 shadow-sm border-bottom mb-3 mb-md-4"
    :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'"
  >
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2 gap-md-3">

      <!-- جهة اليمين/اليسار حسب الاتجاه: صورة المتبرع واسمه والقائمة المنسدلة -->
      <div class="dropdown order-1">
        <div
          class="d-flex align-items-center gap-2 gap-sm-3 cursor-pointer dropdown-toggle no-chevron"
          id="donorUserDropdown"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <img
            :src="userAvatar"
            :alt="userName"
            class="rounded-circle donor-avatar-img border shadow-2xs"
            @error="handleAvatarFallback"
          />
          <div class="text-start d-none d-sm-block">
            <h6 class="fw-bold text-dark mb-0 fs-7 text-truncate" style="max-width: 140px;">{{ userName }}</h6>
            <small class="text-success fs-9 d-flex align-items-center gap-1">
              <span class="active-dot"></span>
              <span>{{ currentLanguage === 'ar' ? 'متبرع نشط' : 'Active Donor' }}</span>
              <i class="bi bi-chevron-down text-muted"></i>
            </small>
          </div>
        </div>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 py-2 mt-2 fs-8 text-end" aria-labelledby="donorUserDropdown">
          <li>
            <router-link to="/donor/dashboard" class="dropdown-item py-2 px-3 d-flex align-items-center justify-content-start gap-2">
              <i class="bi bi-speedometer2 text-danger fs-6"></i>
              <span class="fw-bold text-dark">{{ currentLanguage === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</span>
            </router-link>
          </li>
          <li>
            <router-link to="/donor/donation-center" class="dropdown-item py-2 px-3 d-flex align-items-center justify-content-start gap-2">
              <i class="bi bi-geo-alt text-danger fs-6"></i>
              <span class="fw-bold text-dark">{{ currentLanguage === 'ar' ? 'مركز التبرع' : 'Donation Center' }}</span>
            </router-link>
          </li>
          <li>
            <router-link to="/donor/profile" class="dropdown-item py-2 px-3 d-flex align-items-center justify-content-start gap-2">
              <i class="bi bi-person text-danger fs-6"></i>
              <span class="fw-bold text-dark">{{ currentLanguage === 'ar' ? 'حسابي' : 'My Account' }}</span>
            </router-link>
          </li>
          <li>
            <router-link to="/donor/achievements" class="dropdown-item py-2 px-3 d-flex align-items-center justify-content-start gap-2">
              <i class="bi bi-trophy text-danger fs-6"></i>
              <span class="fw-bold text-dark">{{ currentLanguage === 'ar' ? 'إنجازاتي' : 'Achievements' }}</span>
            </router-link>
          </li>
          <li><hr class="dropdown-divider opacity-25"></li>
          <li>
            <button @click="handleLogout" class="dropdown-item py-2 px-3 text-danger fw-bold d-flex align-items-center justify-content-start gap-2 border-0 bg-transparent w-100">
              <i class="bi bi-box-arrow-right fs-6"></i>
              <span>{{ currentLanguage === 'ar' ? 'تسجيل الخروج' : 'Logout' }}</span>
            </button>
          </li>
        </ul>
      </div>

      <!-- الجزء الأوسط: حقل البحث، الإشعارات المباشرة، ومحول اللغة -->
      <div class="d-flex align-items-center gap-2 gap-md-3 flex-grow-1 justify-content-center max-w-600 order-3 order-md-2 w-100 w-md-auto mt-2 mt-md-0">
        <div class="search-input-wrapper position-relative flex-grow-1">
          <input
            type="text"
            class="form-control form-control-sm pe-4 ps-5 rounded-3 bg-light border-0 py-2 fs-8 text-end"
            :placeholder="currentLanguage === 'ar' ? 'ابحث عن مريض أو مستشفى...' : 'Search for patient or hospital...'"
            v-model="searchQuery"
          />
          <i class="bi bi-search position-absolute top-50 translate-middle-y start-0 ms-3 text-muted fs-8"></i>
        </div>

        <div class="dropdown position-relative flex-shrink-0">
          <button
            class="btn btn-light rounded-circle p-2 border-0 bg-transparent text-muted position-relative pulse-animation"
            type="button"
            id="notificationsDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            @click="markAsRead"
          >
            <i class="bi bi-bell fs-5 text-danger"></i>
            <span v-if="unreadCount > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger border border-light notification-badge">
              {{ unreadCount }}
            </span>
          </button>

          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 p-3 mt-2 fs-8 text-end notifications-dropdown-menu" aria-labelledby="notificationsDropdown">
            <li class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
              <span class="fw-bold text-dark">{{ currentLanguage === 'ar' ? 'الإشعارات الفورية (AI)' : 'Live Notifications (AI)' }}</span>
              <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9">
                {{ unreadCount }} {{ currentLanguage === 'ar' ? 'جديدة' : 'New' }}
              </span>
            </li>

            <!-- قائمة الإشعارات في القائمة المنسدلة -->
            <div class="notifications-list-scroll" style="max-height: 260px; overflow-y: auto;">
              <li v-for="notif in notificationsList" :key="notif.id" class="py-2.5 border-bottom cursor-pointer notification-item" @click="handleNotificationClick(notif)">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-bold text-danger fs-9">{{ translateNotifTitle(notif.title) }}</span>
                  <small class="text-muted fs-10">{{ translateNotifTime(notif.time) }}</small>
                </div>
                <div class="text-dark fs-9 text-truncate" style="max-width: 230px;">
                  {{ translateNotifMessage(notif.message || notif.desc) }}
                </div>
              </li>

              <li v-if="notificationsList.length === 0" class="text-center text-muted py-3 fs-9">
                {{ currentLanguage === 'ar' ? 'لا توجد إشعارات جديدة حالياً.' : 'No new notifications currently.' }}
              </li>
            </div>

            <li class="pt-2 text-center border-top mt-2">
              <button
                type="button"
                @click.prevent="openInteractiveNotificationsModal"
                class="btn btn-link text-danger fw-bold fs-9 text-decoration-none p-0 w-100 border-0 bg-transparent cursor-pointer"
              >
                {{ currentLanguage === 'ar' ? 'عرض جميع الإشعارات >' : 'Show All Notifications >' }}
              </button>
            </li>
          </ul>
        </div>

        <!-- زر تحويل اللغة -->
        <div class="dropdown flex-shrink-0">
          <button class="btn btn-light btn-sm rounded-3 border d-flex align-items-center gap-1 fs-8 px-2 px-sm-3 py-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-translate text-muted"></i>
            <span class="fw-bold ms-1 d-none d-sm-inline">{{ currentLanguage === 'ar' ? 'العربية' : 'English' }}</span>
            <span class="fw-bold ms-1 d-inline d-sm-none">{{ currentLanguage === 'ar' ? 'AR' : 'EN' }}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 fs-8">
            <li>
              <button class="dropdown-item d-flex align-items-center justify-content-between py-2" @click="changeLanguage('ar')">
                <span>العربية</span>
                <i v-if="currentLanguage === 'ar'" class="bi bi-check2 text-danger fw-bold"></i>
              </button>
            </li>
            <li>
              <button class="dropdown-item d-flex align-items-center justify-content-between py-2" @click="changeLanguage('en')">
                <span>English</span>
                <i v-if="currentLanguage === 'en'" class="bi bi-check2 text-danger fw-bold"></i>
              </button>
            </li>
          </ul>
        </div>
      </div>

      <!-- جهة اليسار/اليمين: شعار "مسعف" -->
      <div class="d-flex align-items-center order-2 order-md-3">
        <router-link to="/donor/dashboard" class="d-flex align-items-center text-decoration-none">
          <img :src="logoImg" alt="مسعف" class="navbar-logo-img-large" @error="handleLogoFallback" />
        </router-link>
      </div>

    </div>

    <!-- النافذة المنبثقة التفاعلية لعرض جميع إشعارات النظام -->
    <Teleport to="body">
      <div v-if="showNotificationsModal" class="notifications-modal-overlay d-flex align-items-center justify-content-center" @click.self="showNotificationsModal = false">
        <div class="modal-card bg-white rounded-4 shadow-lg p-4 position-relative text-center border-0" style="width: 90%; max-width: 480px; z-index: 2050;">
          <!-- زر الإغلاق علامة X -->
          <button type="button" class="btn-close position-absolute top-0 start-0 m-3" @click="showNotificationsModal = false" aria-label="Close"></button>

          <!-- عنوان النافذة -->
          <div class="d-flex align-items-center justify-content-center gap-2 text-danger mb-2">
            <i class="bi bi-bell-fill fs-4"></i>
            <h5 class="fw-bold mb-0">
              {{ currentLanguage === 'ar' ? 'إشعارات النظام الذكية التفاعلية' : 'Interactive System Notifications' }}
            </h5>
          </div>

          <!-- النص التفاعلي الفرعي -->
          <div class="text-danger-emphasis fs-9 fw-semibold my-2">
            🔔 {{ currentLanguage === 'ar' ? 'جاري فتح جميع إشعارات النظام بشكل تفاعلي' : 'Opening all system notifications interactively...' }}
          </div>

          <!-- قائمة الإشعارات داخل النافذة -->
          <div class="mt-3 text-start pe-1" style="max-height: 280px; overflow-y: auto;" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
            <div v-for="notif in notificationsList" :key="notif.id" class="p-3 mb-2 rounded-3 bg-light border border-light-subtle">
              <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fw-bold text-danger fs-8">{{ translateNotifTitle(notif.title) }}</span>
                <small class="text-muted fs-10">{{ translateNotifTime(notif.time) }}</small>
              </div>
              <p class="text-dark fs-8 mb-0">{{ translateNotifMessage(notif.message || notif.desc) }}</p>
            </div>

            <div v-if="notificationsList.length === 0" class="text-center text-muted py-3 fs-8">
              {{ currentLanguage === 'ar' ? 'لا توجد إشعارات جديدة حالياً.' : 'No new notifications currently.' }}
            </div>
          </div>

          <!-- زر الإغلاق السفلي -->
          <div class="mt-3 pt-2 text-center">
            <button class="btn btn-secondary rounded-pill px-4 py-1.5 fs-8 fw-bold" @click="showNotificationsModal = false">
              {{ currentLanguage === 'ar' ? 'إغلاق' : 'Close' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';
import { useNotificationStore } from '@/stores/notificationStore';
import apiClient from '@/api/axios';
import echo from '@/utils/echo';

import logoImg from '@/assets/images/logo.png';
import defaultAvatarImg from '@/assets/images/pngtree-whatsapp-default-profile-photo-vector-png-image_17034397.webp';

const router = useRouter();
const authStore = useAuthStore();
const notificationStore = useNotificationStore();

const currentLanguage = ref(localStorage.getItem('musaef_lang') || 'ar');
const searchQuery = ref('');

// التحكم في ظهور نافذة الإشعارات التفاعلية
const showNotificationsModal = ref(false);

const changeLanguage = (lang) => {
  currentLanguage.value = lang;
  localStorage.setItem('musaef_lang', lang);

  const dir = lang === 'ar' ? 'rtl' : 'ltr';
  document.documentElement.setAttribute('dir', dir);
  document.documentElement.setAttribute('lang', lang);

  window.location.reload();
};

const notificationsList = ref([
  {
    id: 1,
    title: 'تنبيه نقص حرج - O-',
    message: 'تجاوز مخزون فصيلة O- الحد الآمن في مستشفيات القطاع.',
    time: 'منذ 5 دقائق',
    read: false
  },
  {
    id: 2,
    title: 'تقرير الذكاء الاصطناعي',
    message: 'تم تحديث خوارزمية التنبؤ بالطلب المستقبلي بنجاح.',
    time: 'منذ ساعة',
    read: false
  }
]);

const notifTitleDict = {
  'تنبيه نقص حرج - O-': 'Critical Shortage Alert - O-',
  'تقرير الذكاء الاصطناعي': 'AI Report',
  'مستشفى الشفاء - O+': 'Al-Shifa Hospital - O+',
  'تذكير التبرع': 'Donation Reminder'
};

const notifMessageDict = {
  'تجاوز مخزون فصيلة O- الحد الآمن في مستشفيات القطاع.': 'O- blood stock exceeded safe limits in sector hospitals.',
  'تم تحديث خوارزمية التنبؤ بالطلب المستقبلي بنجاح.': 'Future demand forecast algorithm updated successfully.',
  'مطلوب تبرع بالدم بشكل عاجل عبر Smart Matching AI.': 'Urgent blood donation required via Smart Matching AI.'
};

const notifTimeDict = {
  'منذ 5 دقائق': '5 mins ago',
  'منذ ساعة': '1 hour ago',
  'منذ 10 دقائق': '10 mins ago',
  'الآن': 'Just now'
};

const translateNotifTitle = (title) => title ? (currentLanguage.value === 'en' ? (notifTitleDict[title] || title) : title) : '';
const translateNotifMessage = (msg) => msg ? (currentLanguage.value === 'en' ? (notifMessageDict[msg] || msg) : msg) : '';
const translateNotifTime = (time) => time ? (currentLanguage.value === 'en' ? (notifTimeDict[time] || time) : time) : '';

const unreadCount = computed(() => {
  return notificationStore.unreadCount || notificationsList.value.filter(n => !n.read).length;
});

const openInteractiveNotificationsModal = () => {
  markAsRead();
  showNotificationsModal.value = true;
};

const markAsRead = () => {
  notificationStore.markAllAsRead();
  notificationsList.value.forEach(n => n.read = true);
};

const handleNotificationClick = (notif) => {
  notif.read = true;
  showNotificationsModal.value = true;
};

const fetchLiveNotifications = async () => {
  try {
    localStorage.setItem('user_role', 'donor');
    await notificationStore.fetchNotifications();
    const res = await apiClient.get('/donor/notifications');
    const data = res?.data?.data || res?.data;
    if (Array.isArray(data) && data.length > 0) {
      notificationsList.value = data;
    }
  } catch (err) {
    console.warn('استخدام الإشعارات الافتراضية.');
  }
};

onMounted(() => {
  fetchLiveNotifications();
  const savedLang = localStorage.getItem('musaef_lang') || 'ar';
  document.documentElement.setAttribute('dir', savedLang === 'ar' ? 'rtl' : 'ltr');
  document.documentElement.setAttribute('lang', savedLang);
});

const userName = computed(() => authStore.userName || authStore.user?.name || (currentLanguage.value === 'ar' ? 'متبرع' : 'Donor'));

const userAvatar = computed(() => {
  const avatar = authStore.userAvatar || authStore.user?.avatar;
  if (!avatar) return defaultAvatarImg;
  if (avatar.startsWith('http') || avatar.startsWith('blob:')) return avatar;
  return `http://localhost:8000/storage/${avatar}`;
});

const handleLogout = async () => {
  if (authStore.logout) await authStore.logout();
  else authStore.setUser(null, null);
  router.push('/login');
};

const handleLogoFallback = (e) => { e.target.src = logoImg; };
const handleAvatarFallback = (e) => { e.target.src = defaultAvatarImg; };
</script>

<style scoped>
.navbar-logo-img-large {
  height: 42px;
  width: auto;
  object-fit: contain;
}

@media (min-width: 768px) {
  .navbar-logo-img-large { height: 58px; }
}

.donor-avatar-img {
  width: 38px;
  height: 38px;
  object-fit: cover;
}

@media (min-width: 768px) {
  .donor-avatar-img { width: 46px; height: 46px; }
}

.active-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #22c55e;
  display: inline-block;
}

.max-w-600 { max-width: 600px; }

.notification-badge {
  font-size: 0.65rem;
  padding: 3px 6px;
}

.notifications-dropdown-menu { width: 290px; }

.notification-item {
  transition: background-color 0.2s ease;
  padding: 8px 10px;
  border-radius: 8px;
}

.notification-item:hover { background-color: #f8fafc; }

.bg-danger-subtle { background-color: #fee2e2 !important; }

.no-chevron::after { display: none !important; }

.dropdown-item { transition: all 0.2s ease; }
.dropdown-item:hover { background-color: #fdecec; }
.dropdown-item:hover span { color: #dc2626 !important; }

.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.fs-10 { font-size: 0.65rem; }
.cursor-pointer { cursor: pointer; }
.shadow-2xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }

/* خلفية التراكب للنافذة المنبثقة التفاعلية */
.notifications-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.45);
  backdrop-filter: blur(3px);
  z-index: 2000;
}
</style>
