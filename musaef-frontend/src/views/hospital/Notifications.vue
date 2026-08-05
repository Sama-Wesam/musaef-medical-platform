<template>
  <HospitalLayout>
    <div class="notifications-page container-fluid px-2 px-md-3" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">

      <!-- الهيدر الخاص بالصفحة -->
      <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-2">
        <div :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
          <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2 fs-6 fs-md-5" :class="currentLanguage === 'ar' ? 'justify-content-start' : 'justify-content-start'">
            <img src="@/assets/icons/solar_bell-outline.png" alt="bell" width="24" height="24" class="header-icon" />
            <span>{{ t('pageTitle') }}</span>
          </h5>
          <p class="text-muted fs-8 mb-0">
            {{ t('pageSubtitle') }}
          </p>
        </div>
      </div>

      <!-- مكون أزرار الفلترة -->
      <NotificationFilters
        v-model:selectedFilter="selectedFilter"
        @markAllAsRead="handleMarkAllAsRead"
      />

      <!-- مؤشر التحميل -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">{{ t('loading') }}</span>
        </div>
      </div>

      <!-- مكون قائمة الإشعارات -->
      <NotificationList
        v-else
        :items="filteredNotifications"
        :emptyText="emptyMessageText"
      />

    </div>
  </HospitalLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import HospitalLayout from '@/layouts/HospitalLayout.vue';
import NotificationFilters from '@/components/hospital/notifications/NotificationFilters.vue';
import NotificationList from '@/components/hospital/notifications/NotificationList.vue';
import apiClient from '@/api/axios';

const loading = ref(false);
const selectedFilter = ref('all');
const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: {
    pageTitle: 'مركز الإشعارات والتنبيهات والذكاء الاصطناعي',
    pageSubtitle: 'متابعة نداءات الاستجابة الفورية، رصد الأنشطة المشبوهة (Fraud Detection AI)، وتحديثات مخزون الدم',
    loading: 'جاري التحميل...',
    emptyAlertsText: 'لا توجد تنبيهات عاجلة أو أمنية لعرضها حالياً',
    emptyAllText: 'لا توجد إشعارات لعرضها حالياً'
  },
  en: {
    pageTitle: 'Notifications, Alerts & AI Center',
    pageSubtitle: 'Track real-time responses, Fraud Detection AI alerts, and blood inventory updates',
    loading: 'Loading...',
    emptyAlertsText: 'No urgent or security alerts to display currently',
    emptyAllText: 'No notifications to display currently'
  }
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const rawNotificationsList = ref([
  {
    id: 1,
    type: 'success',
    titleKey: 'notif1Title',
    descKey: 'notif1Desc',
    timeKey: 'time2Mins',
    read: false
  },
  {
    id: 2,
    type: 'emergency',
    titleKey: 'notif2Title',
    descKey: 'notif2Desc',
    timeKey: 'time15Mins',
    read: false
  },
  {
    id: 3,
    type: 'alert',
    titleKey: 'notif3Title',
    descKey: 'notif3Desc',
    timeKey: 'time1Hour',
    read: true
  }
]);

const notifDict = {
  ar: {
    notif1Title: 'استجابة متبرع (Response Prediction AI)',
    notif1Desc: 'قام المتبرع أحمد خالد بالاستجابة لنداء الطوارئ لفصيلة O- وهو في طريقه للمستشفى (وصول مقدر خلال 4 دقائق).',
    notif2Title: 'تحذير أمني ونشاط مشبوه (Fraud Detection AI)',
    notif2Desc: 'تم رصد محاولة متكررة لإرسال طلبات دم مكثفة من قبل جهة غير مألوفة، قام النظام بحظر الطلب مؤقتاً للمراجعة.',
    notif3Title: 'تنبيه انخفاض مخزون الدم',
    notif3Desc: 'وصل مخزون فصيلة الدم -O إلى حد حرج (وحدتان متوفرتان فقط)، يرجى اتخاذ الإجراء اللازم.',
    time2Mins: 'منذ دقيقتين',
    time15Mins: 'منذ 15 دقيقة',
    time1Hour: 'منذ ساعة'
  },
  en: {
    notif1Title: 'Donor Response (Response Prediction AI)',
    notif1Desc: 'Donor Ahmed Khaled responded to O- emergency call and is on his way to the hospital (Estimated arrival in 4 mins).',
    notif2Title: 'Security Warning & Suspicious Activity (Fraud Detection AI)',
    notif2Desc: 'Repeated attempt to send intensive blood requests from an unfamiliar source detected; request temporarily blocked.',
    notif3Title: 'Low Blood Inventory Alert',
    notif3Desc: 'Blood type O- stock reached a critical limit (2 units only available), please take necessary action.',
    time2Mins: '2 mins ago',
    time15Mins: '15 mins ago',
    time1Hour: '1 hour ago'
  }
};

const localizedNotifications = computed(() => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return rawNotificationsList.value.map(n => ({
    ...n,
    title: notifDict[lang][n.titleKey] || n.titleKey,
    desc: notifDict[lang][n.descKey] || n.descKey,
    time: notifDict[lang][n.timeKey] || n.timeKey
  }));
});

const filteredNotifications = computed(() => {
  if (selectedFilter.value === 'unread') {
    return localizedNotifications.value.filter(n => !n.read);
  }
  if (selectedFilter.value === 'alerts') {
    return localizedNotifications.value.filter(n => n.type === 'emergency' || n.type === 'alert');
  }
  return localizedNotifications.value;
});

const emptyMessageText = computed(() => {
  if (selectedFilter.value === 'alerts') {
    return t('emptyAlertsText');
  }
  return t('emptyAllText');
});

const handleMarkAllAsRead = async () => {
  rawNotificationsList.value.forEach(n => n.read = true);
  try {
    await apiClient.patch('/hospital/notifications/read-all');
  } catch (err) {
    console.warn('تم التحديث محلياً');
  }
};

const fetchNotifications = async () => {
  loading.value = true;
  try {
    const res = await apiClient.get('/hospital/notifications');
    const data = res?.data?.data || res?.data || [];
    if (Array.isArray(data) && data.length > 0) {
      rawNotificationsList.value = data;
    }
  } catch (err) {
    console.warn('استخدام إشعارات الذكاء الاصطناعي الافتراضية');
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchNotifications();
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
