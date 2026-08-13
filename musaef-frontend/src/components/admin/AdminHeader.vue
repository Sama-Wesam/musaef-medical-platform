<template>
  <header class="hospital-header" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">
    <div class="header-container">
      <!-- 1. أقصى اليمين في RTL / اليسار في LTR : زر القائمة للجوال + بيانات وصورة الأدمن -->
      <div class="d-flex align-items-center gap-2 gap-sm-3">
        <!-- زر فتح القائمة الجانبية في الهواتف والآيباد -->
        <button
          class="menu-toggle-btn d-lg-none"
          @click="toggleMobileSidebar"
          type="button"
          aria-label="Toggle Navigation"
        >
          <i class="bi bi-list fs-3 text-dark"></i>
        </button>

        <div class="doctor-info">
          <img :src="doctorAvatar" alt="Doctor" class="doctor-avatar" />
          <div
            class="doctor-text d-none d-sm-flex"
            :class="currentLocale === 'ar' ? 'text-end' : 'text-start'"
          >
            <h6 class="doctor-name">
              {{
                user?.name ||
                (currentLocale === "en" ? "Super Admin" : "مدير النظام - Super Admin")
              }}
            </h6>
            <span class="doctor-role">
              {{
                userRole === "admin"
                  ? currentLocale === "en"
                    ? "System Admin"
                    : "مدير النظام"
                  : currentLocale === "en"
                  ? "Blood Bank Director"
                  : "مدير بنك الدم"
              }}
            </span>
          </div>
        </div>
      </div>

      <!-- 2. المنتصف : شريط البحث + الإشعارات + زر تبديل اللغة + بطاقة انخفاض مخزون الدم -->
      <div class="header-center">
        <!-- مربع البحث -->
        <div class="search-box d-none d-md-flex">
          <input
            type="text"
            v-model="search"
            :placeholder="
              currentLocale === 'en'
                ? 'Search for patient or request...'
                : 'ابحث عن مريض أو طلب'
            "
            class="search-input"
            :class="currentLocale === 'ar' ? 'text-end' : 'text-start'"
            @input="handleSearch"
          />
          <img
            :src="searchIcon"
            alt="Search"
            class="search-icon"
            :style="
              currentLocale === 'en'
                ? 'left: 14px; right: auto;'
                : 'right: 14px; left: auto;'
            "
          />
        </div>

        <!-- زر الإشعارات التفاعلي -->
        <div class="dropdown position-relative">
          <button
            class="notification-btn"
            type="button"
            id="adminNotificationsDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            @click="fetchLiveNotifications(false)"
          >
            <img :src="bellIcon" alt="Notifications" class="notification-icon" />
            <span class="notification-badge" v-if="unreadCount > 0">
              {{ unreadCount }}
            </span>
          </button>

          <!-- القائمة المنسدلة للإشعارات -->
          <ul
            class="dropdown-menu shadow-lg border-0 rounded-4 p-3 mt-2 fs-8 notifications-dropdown-menu"
            :class="
              currentLocale === 'ar'
                ? 'dropdown-menu-end text-end'
                : 'dropdown-menu-start text-start'
            "
            aria-labelledby="adminNotificationsDropdown"
          >
            <li
              class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom"
            >
              <span class="fw-bold text-dark fs-8">{{
                currentLocale === "en"
                  ? "AI System Notifications"
                  : "إشعارات النظام الذكية (AI)"
              }}</span>
              <span
                class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 fs-9 fw-bold"
                >{{ unreadCount }} {{ currentLocale === "en" ? "new" : "جديدة" }}</span
              >
            </li>

            <div class="notifications-scroll-area">
              <li
                v-for="notif in translatedNotifications"
                :key="notif.id"
                class="py-2.5 px-2 border-bottom cursor-pointer notification-item rounded-3 mb-1"
                @click="handleNotificationClick(notif)"
              >
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <span
                    class="fw-bold text-danger fs-8 text-truncate"
                    style="max-width: 170px"
                    >{{ notif.title }}</span
                  >
                  <small class="text-muted fs-10 dir-ltr">{{ notif.time }}</small>
                </div>
                <div class="text-secondary fs-9 text-truncate" style="max-width: 250px">
                  {{ notif.message }}
                </div>
              </li>
              <li
                v-if="translatedNotifications.length === 0"
                class="text-center text-muted py-4 fs-9"
              >
                {{
                  currentLocale === "en"
                    ? "No new notifications."
                    : "لا توجد إشعارات جديدة."
                }}
              </li>
            </div>

            <li class="pt-2 text-center border-top mt-2">
              <button
                @click.prevent="handleViewAllSystemNotifications"
                class="btn btn-link text-danger fw-bold fs-9 text-decoration-none p-0 border-0 w-100 text-center cursor-pointer"
              >
                {{
                  currentLocale === "en"
                    ? "View All System Notifications >"
                    : "عرض جميع إشعارات النظام >"
                }}
              </button>
            </li>
          </ul>
        </div>

        <!-- زر تبديل اللغة -->
        <div class="dropdown">
          <button
            class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 fw-semibold d-flex align-items-center gap-1.5 lang-switch-btn"
            type="button"
            id="adminLanguageDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            <i class="bi bi-globe fs-7"></i>
            <span>{{ currentLocale === "ar" ? "English" : "العربية" }}</span>
            <i class="bi bi-chevron-down fs-9"></i>
          </button>
          <ul
            class="dropdown-menu shadow-sm rounded-3 border-0 mt-1"
            :class="currentLocale === 'ar' ? 'dropdown-menu-end' : 'dropdown-menu-start'"
            aria-labelledby="adminLanguageDropdown"
          >
            <li>
              <button
                class="dropdown-item d-flex align-items-center justify-content-between fs-8 py-2"
                :class="{ 'active fw-bold text-danger bg-light': currentLocale === 'ar' }"
                @click="switchLanguage('ar')"
              >
                <span>العربية</span>
                <span v-if="currentLocale === 'ar'" class="text-danger">✓</span>
              </button>
            </li>
            <li>
              <button
                class="dropdown-item d-flex align-items-center justify-content-between fs-8 py-2"
                :class="{ 'active fw-bold text-danger bg-light': currentLocale === 'en' }"
                @click="switchLanguage('en')"
              >
                <span>English</span>
                <span v-if="currentLocale === 'en'" class="text-danger">✓</span>
              </button>
            </li>
          </ul>
        </div>

        <!-- بطاقة انخفاض فصيلة الدم التفاعلية -->
        <div
          class="blood-alert d-none d-sm-flex cursor-pointer shadow-2xs"
          @click="handleBloodAlertClick"
          :title="
            currentLocale === 'en'
              ? 'Click for AI Forecast'
              : 'انقر لعرض تفاصيل تحليل النقص الذكي'
          "
        >
          <span class="blood-text">{{ currentLocale === "en" ? "Low" : "منخفض" }}</span>
          <span class="blood-type">O-</span>
        </div>
      </div>

      <!-- 3. الجهة اليسرى/اليمنى المقابلة : شعار منصة مسعف -->
      <div class="header-logo">
        <RouterLink to="/">
          <img :src="logoImage" alt="Musaef Logo" class="logo-image" />
        </RouterLink>
      </div>
    </div>

    <!-- نافذة المودال التفاعلية لعرض جميع إشعارات النظام -->
    <div
      v-if="showNotificationsModal"
      class="modal-backdrop-custom d-flex align-items-center justify-content-center"
    >
      <div
        class="modal-card bg-white rounded-4 shadow-lg p-4 position-relative"
        :class="currentLocale === 'ar' ? 'text-end' : 'text-start'"
        style="width: 90%; max-width: 500px; z-index: 1050"
      >
        <button
          type="button"
          class="btn-close position-absolute top-0 m-3"
          :class="currentLocale === 'ar' ? 'start-0' : 'end-0'"
          @click="showNotificationsModal = false"
          aria-label="Close"
        ></button>

        <div class="d-flex align-items-center gap-2 text-danger mb-3 border-bottom pb-2">
          <i class="bi bi-bell-fill fs-4"></i>
          <h5 class="fw-bold mb-0">
            {{
              currentLocale === "en"
                ? "Interactive AI System Notifications"
                : "إشعارات النظام الذكية التفاعلية"
            }}
          </h5>
        </div>

        <div v-if="isLoadingModal" class="text-center py-4 text-muted">
          <div
            class="spinner-border text-danger spinner-border-sm mb-2"
            role="status"
          ></div>
          <p class="mb-0 fw-semibold">
            {{
              currentLocale === "en"
                ? "Opening all system notifications interactively..."
                : "جاري فتح جميع إشعارات النظام بشكل تفاعلي..."
            }}
          </p>
        </div>

        <div v-else>
          <div
            class="alert alert-danger-subtle text-danger border-0 rounded-3 py-2 px-3 mb-3 fs-8 fw-semibold text-center"
          >
            🔔
            {{
              currentLocale === "en"
                ? "Opening all system notifications interactively"
                : "جاري فتح جميع إشعارات النظام بشكل تفاعلي"
            }}
          </div>

          <div style="max-height: 320px; overflow-y: auto" class="pe-1">
            <div
              v-for="notif in translatedNotifications"
              :key="notif.id"
              class="p-3 mb-2 rounded-3 bg-light border border-light-subtle"
            >
              <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fw-bold text-danger fs-8">{{ notif.title }}</span>
                <span class="text-muted fs-10 dir-ltr">{{ notif.time }}</span>
              </div>
              <p class="text-dark fs-8 mb-0">{{ notif.message }}</p>
            </div>
            <div
              v-if="translatedNotifications.length === 0"
              class="text-center text-muted py-3 fs-8"
            >
              {{
                currentLocale === "en"
                  ? "No notifications recorded."
                  : "لا توجد إشعارات مسجلة."
              }}
            </div>
          </div>

          <div class="text-center mt-3 pt-2 border-top">
            <button
              class="btn btn-secondary btn-sm rounded-pill px-4"
              @click="showNotificationsModal = false"
            >
              {{ currentLocale === "en" ? "Close" : "إغلاق" }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { RouterLink, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/authStore";
import { useNotificationStore } from "@/stores/notificationStore";
import apiClient from "@/api/axios";

import logoImage from "@/assets/images/logo.png";
import bellIcon from "@/assets/icons/solar_bell-outline.png";
import searchIcon from "@/assets/icons/Search Icon Container.png";
import doctorAvatar from "@/assets/icons/Ellipse 1086.png";

const router = useRouter();
const authStore = useAuthStore();
const notificationStore = useNotificationStore();

const user = computed(() => authStore.user);
const userRole = computed(() => authStore.userRole);
const currentLocale = computed(() => localStorage.getItem("musaef_lang") || "ar");

const showNotificationsModal = ref(false);
const isLoadingModal = ref(false);
let pollingInterval = null;

const unreadCount = computed(
  () =>
    notificationStore.unreadCount ||
    rawNotificationsList.value.filter((n) => !n.read && !n.is_read).length
);

const search = ref("");
const rawNotificationsList = ref([]);

// دالة تحويل الوقت بتنسيق جميل وسلس
const formatNotificationTime = (timeStr) => {
  if (!timeStr) return currentLocale.value === "en" ? "Just now" : "الآن";
  if (!timeStr.includes("T") && !timeStr.includes("-")) return timeStr;

  try {
    const date = new Date(timeStr);
    if (isNaN(date.getTime())) return timeStr;

    const now = new Date();
    const diffInMinutes = Math.floor((now - date) / (1000 * 60));

    if (diffInMinutes < 1) return currentLocale.value === "en" ? "Just now" : "الآن";
    if (diffInMinutes < 60)
      return currentLocale.value === "en"
        ? `${diffInMinutes} mins ago`
        : `منذ ${diffInMinutes} دقيقة`;

    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24)
      return currentLocale.value === "en"
        ? `${diffInHours} hours ago`
        : `منذ ${diffInHours} ساعة`;

    return date.toLocaleDateString(currentLocale.value === "en" ? "en-US" : "ar-EG", {
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  } catch (e) {
    return timeStr;
  }
};

const translatedNotifications = computed(() => {
  return rawNotificationsList.value.map((n) => ({
    id: n.id,
    title:
      currentLocale.value === "en"
        ? n.titleEn || n.title_en || n.title || "System Alert"
        : n.titleAr || n.title_ar || n.title || "تنبيه النظام",
    message:
      currentLocale.value === "en"
        ? n.messageEn ||
          n.message_en ||
          n.message ||
          n.desc ||
          "New update available in system."
        : n.messageAr ||
          n.message_ar ||
          n.message ||
          n.desc ||
          "يوجد تحديث جديد في النظام.",
    time: formatNotificationTime(n.timeEn || n.timeAr || n.created_at || n.time),
    read: n.read || n.is_read,
  }));
});

const switchLanguage = (lang) => {
  localStorage.setItem("musaef_lang", lang);
  window.location.reload();
};

// جلب الإشعارات المباشرة مع دعم التحديث الدوري التلقائي (Polling)
const fetchLiveNotifications = async (isBackground = false) => {
  try {
    const res = await apiClient.get("/admin/notifications");
    const data = res?.data?.data || res?.data;
    if (Array.isArray(data) && data.length > 0) {
      rawNotificationsList.value = data;
    } else if (!isBackground) {
      setFallbackNotifications();
    }
  } catch (err) {
    console.warn("تعذر جلب إشعارات المستشفى/النظام من السيرفر.");
    if (!isBackground && rawNotificationsList.value.length === 0) {
      setFallbackNotifications();
    }
  }
};

const setFallbackNotifications = () => {
  rawNotificationsList.value = [
    {
      id: 1,
      titleAr: "تنبيه نقص حرج - O-",
      titleEn: "Critical Shortage Alert - O-",
      messageAr: "تجاوز مخزون فصيلة O- الحد الآمن في مستشفيات القطاع.",
      messageEn:
        "O- blood group stock has dropped below safe limits in sector hospitals.",
      timeAr: "منذ 5 دقائق",
      timeEn: "5 mins ago",
      read: false,
    },
    {
      id: 2,
      titleAr: "تقرير الذكاء الاصطناعي",
      titleEn: "AI Intelligence Report",
      messageAr: "تم تحديث خوارزمية التنبؤ بالطلب المستقبلي بنجاح.",
      messageEn: "Future demand forecast algorithm updated successfully.",
      timeAr: "منذ ساعة",
      timeEn: "1 hour ago",
      read: false,
    },
    {
      id: 3,
      titleAr: "تنشيط طلب طوارئ #890",
      titleEn: "Emergency Request Activated #890",
      messageAr: "تمت إضافة طلب تبرع عاجل لمستشفى الشفاء.",
      messageEn: "Urgent donation request added for Al-Shifa Hospital.",
      timeAr: "منذ ساعتين",
      timeEn: "2 hours ago",
      read: true,
    },
  ];
};

const handleNotificationClick = (notif) => {
  notif.read = true;
  notif.is_read = true;
  showNotificationsModal.value = true;
};

const handleViewAllSystemNotifications = () => {
  rawNotificationsList.value.forEach((n) => {
    n.read = true;
    n.is_read = true;
  });
  showNotificationsModal.value = true;
  isLoadingModal.value = true;

  setTimeout(() => {
    isLoadingModal.value = false;
  }, 600);
};

const handleBloodAlertClick = async () => {
  try {
    await apiClient.get("/admin/analytics/demand-forecast", {
      params: { blood_type: "O-", alert: "critical" },
    });
    alert(
      currentLocale.value === "en"
        ? "🤖 AI Report: O- blood group shows critical shortage requiring immediate donation calls."
        : "🤖 تحليل الذكاء الاصطناعي (Blood Demand Forecast):\n- فصيلة O- تسجل نقصاً حرجاً يتطلب إطلاق حملات تبرع عاجلة."
    );
  } catch (err) {
    alert(
      currentLocale.value === "en"
        ? "🚨 Emergency Alert (AI): O- Blood level is critical!"
        : "🚨 تنبيه عاجل (Blood Demand Forecast AI):\nالفصيلة O- في مستوى حرج (متوفر وحدات قليلة جداً)."
    );
  }
};

const handleSearch = () => {
  console.log("Searching for:", search.value);
};

// إرسال الحدث برمجياً للسايدبار فقط بدون التعديل المباشر على كلاسات DOM
const toggleMobileSidebar = () => {
  window.dispatchEvent(new CustomEvent("toggle-admin-sidebar"));
};

onMounted(() => {
  fetchLiveNotifications();

  // تفعيل الاستطلاع المباشر (Polling) كل 10 ثوانٍ لتحديث تنبيهات النظام والمستشفى فوراً
  pollingInterval = setInterval(() => {
    fetchLiveNotifications(true);
  }, 10000);
});

onUnmounted(() => {
  if (pollingInterval) clearInterval(pollingInterval);
});
</script>

<style scoped>
.hospital-header {
  width: 100%;
  height: 80px;
  background: #ffffff;
  border-bottom: 1px solid #ececec;
  display: flex;
  align-items: center;
  justify-content: center;
  position: sticky;
  top: 0;
  z-index: 999;
}

@media (min-width: 992px) {
  .hospital-header {
    height: 96px;
  }
}

.header-container {
  width: 100%;
  max-width: 1600px;
  height: 100%;
  padding: 0 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

@media (min-width: 768px) {
  .header-container {
    padding: 0 28px;
  }
}

.menu-toggle-btn {
  background: transparent;
  border: none;
  padding: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.doctor-info {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 12px;
}

@media (min-width: 992px) {
  .doctor-info {
    min-width: 220px;
    gap: 16px;
  }
}

.doctor-avatar {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e5e7eb;
}

@media (min-width: 768px) {
  .doctor-avatar {
    width: 58px;
    height: 58px;
  }
}

.doctor-text {
  display: flex;
  flex-direction: column;
}

.doctor-name {
  margin: 0;
  font-size: 16px;
  font-weight: 700;
  color: #111827;
  line-height: 1.3;
}

@media (min-width: 992px) {
  .doctor-name {
    font-size: 21px;
  }
}

.doctor-role {
  margin-top: 2px;
  font-size: 12px;
  color: #6b7280;
  font-weight: 500;
}

.header-center {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
}

@media (min-width: 992px) {
  .header-center {
    flex: 1;
    gap: 24px;
  }
}

.search-box {
  width: 220px;
  height: 42px;
  background: #f8f8fb;
  border-radius: 14px;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
}

@media (min-width: 1200px) {
  .search-box {
    width: 360px;
    height: 46px;
  }
}

.search-input {
  width: 100%;
  height: 100%;
  border: none;
  outline: none;
  background: transparent;
  padding: 0 45px 0 15px;
  font-size: 14px;
  color: #374151;
}

.search-input::placeholder {
  color: #9ca3af;
}

.search-icon {
  position: absolute;
  width: 18px;
  height: 18px;
  object-fit: contain;
  opacity: 0.7;
}

.notification-btn {
  width: 38px;
  height: 38px;
  background: transparent;
  border: none;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.notification-btn:hover {
  background: #fafafa;
  border-radius: 50%;
}

.notification-icon {
  width: 22px;
  height: 22px;
  object-fit: contain;
}

.notification-badge {
  position: absolute;
  top: 2px;
  left: 2px;
  width: 16px;
  height: 16px;
  background: #ef4444;
  color: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 9px;
  font-weight: 700;
  border: 2px solid #ffffff;
}

.lang-switch-btn {
  border-color: #e5e7eb;
  color: #374151;
  background-color: #f8fafc;
  height: 38px;
}

.lang-switch-btn:hover,
.lang-switch-btn:focus {
  background-color: #f1f5f9;
  border-color: #cbd5e1;
  color: #0f172a;
}

.blood-alert {
  width: 110px;
  height: 38px;
  background: #dc2626;
  border-radius: 999px;
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.blood-alert:hover {
  transform: scale(1.03);
}

@media (min-width: 992px) {
  .blood-alert {
    width: 140px;
    height: 42px;
    font-size: 16px;
  }
}

.blood-type {
  direction: ltr;
}

.notifications-dropdown-menu {
  width: 310px;
}

.notifications-scroll-area {
  max-height: 260px;
  overflow-y: auto;
  padding-right: 2px;
}

.notification-item {
  transition: background-color 0.15s ease;
}

.notification-item:hover {
  background-color: #f9fafb;
}

.cursor-pointer {
  cursor: pointer;
}
.dir-ltr {
  direction: ltr;
}
.fs-7 {
  font-size: 0.9rem;
}
.fs-8 {
  font-size: 0.82rem;
}
.fs-9 {
  font-size: 0.72rem;
}
.fs-10 {
  font-size: 0.65rem;
}

.header-logo {
  display: flex;
  align-items: center;
  justify-content: flex-end;
}

@media (min-width: 992px) {
  .header-logo {
    min-width: 210px;
  }
}

.logo-image {
  width: 110px;
  height: auto;
  object-fit: contain;
}

@media (min-width: 768px) {
  .logo-image {
    width: 140px;
  }
}
@media (min-width: 1200px) {
  .logo-image {
    width: 180px;
  }
}

.modal-backdrop-custom {
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
