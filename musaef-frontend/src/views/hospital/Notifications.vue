<template>
  <HospitalLayout>
    <div class="notifications-page container-fluid px-2 px-md-3" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">

      <!-- الهيدر الخاص بالصفحة -->
      <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-2">
        <div :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
          <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2 fs-6 fs-md-5">
            <img src="@/assets/icons/solar_bell-outline.png" alt="bell" width="24" height="24" class="header-icon" />
            <span>{{ t('pageTitle') }}</span>
          </h5>
          <p class="text-muted fs-8 mb-0">
            {{ t('pageSubtitle') }}
          </p>
        </div>
        <button class="btn btn-sm btn-light border rounded-pill px-3" @click="fetchNotifications(false)">
          🔄 {{ currentLanguage === 'en' ? 'Refresh' : 'تحديث' }}
        </button>
      </div>

      <!-- مكون أزرار الفلترة -->
      <NotificationFilters
        v-model:selectedFilter="selectedFilter"
        @markAllAsRead="handleMarkAllAsRead"
      />

      <!-- مؤشر التحميل -->
      <div v-if="loading && notificationsList.length === 0" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">{{ t('loading') }}</span>
        </div>
      </div>

      <!-- مكون قائمة الإشعارات -->
      <NotificationList
        v-else
        :items="filteredNotifications"
        :emptyText="emptyMessageText"
        @markRead="handleMarkSingleAsRead"
        @delete="handleDeleteNotification"
        @itemClick="handleNotificationClick"
      />

    </div>
  </HospitalLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import HospitalLayout from '@/layouts/HospitalLayout.vue';
import NotificationFilters from '@/components/hospital/notifications/NotificationFilters.vue';
import NotificationList from '@/components/hospital/notifications/NotificationList.vue';
import apiClient from '@/api/axios';

const router = useRouter();
const loading = ref(false);
const selectedFilter = ref('all');
const notificationsList = ref([]);
const currentLanguage = ref(localStorage.getItem('musaef_lang') || 'ar');
let notificationsPolling = null;

const updateLocale = () => {
  currentLanguage.value = localStorage.getItem('musaef_lang') || 'ar';
};

const translations = {
  ar: {
    pageTitle: 'مركز الإشعارات والتنبيهات والذكاء الاصطناعي',
    pageSubtitle: 'متابعة نداءات الاستجابة الفورية، رصد الأنشطة المشبوهة (Fraud Detection AI)، وتحديثات مخزون الدم',
    loading: 'جاري تحميل الإشعارات...',
    emptyAlertsText: 'لا توجد تنبيهات عاجلة أو أمنية لعرضها حالياً',
    emptyUnreadText: 'لا توجد إشعارات غير مقروءة حالياً',
    emptyAllText: 'لا توجد إشعارات لعرضها حالياً'
  },
  en: {
    pageTitle: 'Notifications, Alerts & AI Center',
    pageSubtitle: 'Track real-time responses, Fraud Detection AI alerts, and blood inventory updates',
    loading: 'Loading notifications...',
    emptyAlertsText: 'No urgent or security alerts to display currently',
    emptyUnreadText: 'No unread notifications currently',
    emptyAllText: 'No notifications to display currently'
  }
};

const t = (key) => translations[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const filteredNotifications = computed(() => {
  return notificationsList.value.filter(item => {
    const isUnread = item.is_read === false || item.read === false;
    const isAlert = ['emergency', 'danger', 'alert', 'warning', 'fraud', 'critical', 'fraud_alert', 'response_prediction'].includes(String(item.type).toLowerCase());

    if (selectedFilter.value === 'unread') {
      return isUnread;
    }
    if (selectedFilter.value === 'alerts') {
      return isAlert;
    }
    return true;
  });
});

const emptyMessageText = computed(() => {
  if (selectedFilter.value === 'alerts') return t('emptyAlertsText');
  if (selectedFilter.value === 'unread') return t('emptyUnreadText');
  return t('emptyAllText');
});

const fetchNotifications = async (showLoading = true) => {
  if (showLoading) loading.value = true;
  try {
    const res = await apiClient.get('/hospital/notifications');
    let data = res?.data?.data || res?.data || [];

    if (Array.isArray(data)) {
      notificationsList.value = data.map(item => ({
        id: item.id,
        type: item.type || 'info',
        title: item.title || item.title_ar || item.title_en || '',
        title_ar: item.title_ar || item.title,
        title_en: item.title_en || item.title,
        desc: item.description || item.desc || item.body || item.message || '',
        description_ar: item.description_ar || item.desc || item.body || item.message,
        description_en: item.description_en || item.desc || item.body || item.message,
        is_read: item.is_read !== undefined ? Boolean(item.is_read) : (item.read !== undefined ? Boolean(item.read) : false),
        created_at: item.created_at || new Date().toISOString(),
        action_url: item.action_url || item.url || null
      }));
    }
  } catch (err) {
    console.error('خطأ أثناء جلب الإشعارات:', err);
  } finally {
    loading.value = false;
  }
};

const handleMarkSingleAsRead = async (id) => {
  const target = notificationsList.value.find(n => n.id === id);
  if (target) {
    target.is_read = true;
    target.read = true;
  }
  try {
    await apiClient.patch(`/hospital/notifications/${id}/read`);
  } catch (err) {
    console.warn(`تعذر المزامنة مع السيرفر للإشعار ${id}`);
  }
};

const handleMarkAllAsRead = async () => {
  notificationsList.value.forEach(n => {
    n.is_read = true;
    n.read = true;
  });
  try {
    await apiClient.patch('/hospital/notifications/read-all');
  } catch (err) {
    console.warn('تعذر المزامنة الكاملة مع السيرفر');
  }
};

const handleDeleteNotification = async (id) => {
  notificationsList.value = notificationsList.value.filter(n => n.id !== id);
  try {
    await apiClient.delete(`/hospital/notifications/${id}`);
  } catch (err) {
    console.warn(`تعذر حذف الإشعار ${id} من السيرفر`);
  }
};

const handleNotificationClick = (item) => {
  if (item.action_url) {
    router.push(item.action_url);
  }
};

onMounted(() => {
  window.addEventListener('storage', updateLocale);
  window.addEventListener('language-changed', updateLocale);
  fetchNotifications(true);

  notificationsPolling = setInterval(() => {
    fetchNotifications(false);
  }, 5000);
});

onUnmounted(() => {
  window.removeEventListener('storage', updateLocale);
  window.removeEventListener('language-changed', updateLocale);
  if (notificationsPolling) clearInterval(notificationsPolling);
});
</script>

<style scoped>
.notifications-page {
  padding-bottom: 24px;
}
.fs-8 { font-size: 0.85rem; }

.header-icon {
  width: 20px;
  height: 20px;
}

@media (min-width: 768px) {
  .header-icon {
    width: 24px;
    height: 24px;
  }
}
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
