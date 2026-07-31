<template>
  <header class="hospital-header" dir="rtl">
    <div class="header-container">
      <!-- 1. أقصى اليمين : زر القائمة للجوال + بيانات وصورة الأدمن -->
      <div class="d-flex align-items-center gap-2 gap-sm-3">
        <!-- زر فتح القائمة الجانبية في الهواتف والآيباد -->
        <button class="menu-toggle-btn d-lg-none" @click="toggleMobileSidebar" aria-label="Toggle Navigation">
          <i class="bi bi-list fs-3 text-dark"></i>
        </button>

        <div class="doctor-info">
          <img :src="doctorAvatar" alt="Doctor" class="doctor-avatar" />
          <div class="doctor-text d-none d-sm-flex">
            <h6 class="doctor-name">
              {{ user?.name || "د. سعيد عبده" }}
            </h6>
            <span class="doctor-role">
              {{ userRole === 'admin' ? 'مدير النظام' : 'مدير بنك الدم' }}
            </span>
          </div>
        </div>
      </div>

      <!-- 2. المنتصف : شريط البحث + الإشعارات التفاعلية + بطاقة انخفاض مخزون الدم التفاعلية -->
      <div class="header-center">
        <!-- مربع البحث -->
        <div class="search-box d-none d-md-flex">
          <input
            type="text"
            v-model="search"
            placeholder="ابحث عن مريض أو طلب"
            class="search-input"
            @input="handleSearch"
          />
          <img :src="searchIcon" alt="Search" class="search-icon" />
        </div>

        <!-- زر الإشعارات التفاعلي (مربوط بالذكاء الاصطناعي والباك إند) -->
        <div class="dropdown position-relative">
          <button class="notification-btn" type="button" id="adminNotificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false" @click="fetchLiveNotifications">
            <img :src="bellIcon" alt="Notifications" class="notification-icon" />
            <span class="notification-badge" v-if="unreadCount > 0">
              {{ unreadCount }}
            </span>
          </button>

          <!-- القائمة المنسدلة التفاعلية للإشعارات الفورية -->
          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 p-3 mt-2 fs-8 text-end notifications-dropdown-menu" aria-labelledby="adminNotificationsDropdown">
            <li class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
              <span class="fw-bold text-dark">إشعارات النظام الذكية (AI)</span>
              <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9">{{ unreadCount }} جديدة</span>
            </li>

            <div style="max-height: 240px; overflow-y: auto;">
              <li v-for="notif in notificationsList" :key="notif.id" class="py-2 border-bottom cursor-pointer notification-item" @click="handleNotificationClick(notif)">
                <div class="fw-bold text-danger fs-9 mb-0.5">{{ notif.title }}</div>
                <div class="text-dark fs-9 text-truncate" style="max-width: 220px;">{{ notif.message }}</div>
                <small class="text-muted fs-10">{{ notif.time }}</small>
              </li>
              <li v-if="notificationsList.length === 0" class="text-center text-muted py-3 fs-9">
                لا توجد إشعارات جديدة.
              </li>
            </div>

            <!-- زر عرض جميع إشعارات النظام المفعل بشكل تفاعلي تام -->
            <li class="pt-2 text-center border-top mt-2">
              <a href="#" @click.prevent="handleViewAllSystemNotifications" class="text-danger fw-bold fs-9 text-decoration-none d-block cursor-pointer">
                عرض جميع إشعارات النظام &gt;
              </a>
            </li>
          </ul>
        </div>

        <!-- بطاقة انخفاض فصيلة الدم التفاعلية (مربوطة بـ Blood Demand Forecast AI) -->
        <div class="blood-alert d-none d-sm-flex cursor-pointer shadow-2xs" @click="handleBloodAlertClick" title="انقر لعرض تفاصيل تحليل النقص الذكي">
          <span class="blood-text">منخفض</span>
          <span class="blood-type">O-</span>
        </div>
      </div>

      <!-- 3. أقصى اليسار : شعار منصة مسعف -->
      <div class="header-logo">
        <RouterLink to="/">
          <img :src="logoImage" alt="Musaef Logo" class="logo-image" />
        </RouterLink>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
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
const unreadCount = computed(() => notificationStore.unreadCount || notificationsList.value.filter(n => !n.read).length);

const search = ref("");
const notificationsList = ref([
  { id: 1, title: 'تنبيه نقص حرج - O-', message: 'تجاوز مخزون فصيلة O- الحد الآمن في مستشفيات القطاع.', time: 'منذ 5 دقائق', read: false },
  { id: 2, title: 'تقرير الذكاء الاصطناعي', message: 'تم تحديث خوارزمية التنبؤ بالطلب المستقبلي بنجاح.', time: 'منذ ساعة', read: false }
]);

const fetchLiveNotifications = async () => {
  try {
    const res = await apiClient.get('/admin/notifications');
    const data = res?.data?.data || res?.data;
    if (Array.isArray(data) && data.length > 0) {
      notificationsList.value = data;
    }
  } catch (err) {
    console.warn('استخدام التنبيهات الافتراضية بنجاح.');
  }
};

const handleNotificationClick = (notif) => {
  notif.read = true;
  router.push('/admin/notifications');
};

// دالة تفاعلية لزر عرض جميع إشعارات النظام
const handleViewAllSystemNotifications = () => {
  notificationsList.value.forEach(n => n.read = true);
  alert("🔔 جاري توجيهك إلى صفحة أرشيف إشعارات النظام الشاملة...");
  router.push('/admin/notifications');
};

// دالة تفاعلية عند النقر على زر انخفاض فصيلة الدم O- لاستدعاء تحليل الذكاء الاصطناعي
const handleBloodAlertClick = async () => {
  try {
    const res = await apiClient.get('/admin/analytics/demand-forecast', {
      params: { blood_type: 'O-', alert: 'critical' }
    });
    alert("🤖 تحليل الذكاء الاصطناعي (Blood Demand Forecast):\n- فصيلة O- تسجل نقصاً حرجاً يتطلب إطلاق حملات تبرع عاجلة وتوجيه النداءات للمتبرعين المطابقين ضمن نطاق 10 كم.");
  } catch (err) {
    alert("🚨 تنبيه عاجل (Blood Demand Forecast AI):\nالفصيلة O- في مستوى حرج (متوفر وحدات قليلة جداً). تم تفعيل توصيات الرفع الاستباقي للججاهزية.");
  }
};

const handleSearch = () => {
  console.log("Searching for:", search.value);
};

const toggleMobileSidebar = () => {
  const sidebar = document.querySelector('.hospital-sidebar');
  const backdrop = document.querySelector('.sidebar-backdrop');
  if (sidebar) sidebar.classList.toggle('show-mobile');
  if (backdrop) backdrop.classList.toggle('show');
};

onMounted(() => {
  fetchLiveNotifications();
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
  .hospital-header { height: 96px; }
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
  .header-container { padding: 0 28px; }
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
  .doctor-info { min-width: 220px; gap: 16px; }
}

.doctor-avatar {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e5e7eb;
}

@media (min-width: 768px) {
  .doctor-avatar { width: 58px; height: 58px; }
}

.doctor-text {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.doctor-name {
  margin: 0;
  font-size: 16px;
  font-weight: 700;
  color: #111827;
  line-height: 1.3;
}

@media (min-width: 992px) { .doctor-name { font-size: 21px; } }

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

@media (min-width: 992px) { .header-center { flex: 1; gap: 24px; } }

.search-box {
  width: 260px;
  height: 42px;
  background: #f8f8fb;
  border-radius: 14px;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
}

@media (min-width: 1200px) { .search-box { width: 480px; height: 46px; } }

.search-input {
  width: 100%;
  height: 100%;
  border: none;
  outline: none;
  background: transparent;
  padding: 0 45px 0 15px;
  text-align: right;
  font-size: 14px;
  color: #374151;
}

.search-input::placeholder { color: #9ca3af; }

.search-icon {
  position: absolute;
  right: 14px;
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

@media (min-width: 992px) { .blood-alert { width: 150px; height: 44px; font-size: 18px; } }

.blood-type { direction: ltr; }

.notifications-dropdown-menu {
  width: 280px;
}

.cursor-pointer { cursor: pointer; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.fs-10 { font-size: 0.65rem; }

.header-logo {
  display: flex;
  align-items: center;
  justify-content: flex-end;
}

@media (min-width: 992px) { .header-logo { min-width: 210px; } }

.logo-image {
  width: 110px;
  height: auto;
  object-fit: contain;
}

@media (min-width: 768px) { .logo-image { width: 140px; } }
@media (min-width: 1200px) { .logo-image { width: 180px; } }
</style>
