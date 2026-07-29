<template>
  <HospitalLayout>
    <div class="notifications-page container-fluid px-2 px-md-3 dir-rtl text-end">

      <!-- الهيدر الخاص بالصفحة -->
      <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-2">
        <div>
          <h5 class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-start gap-2 fs-6 fs-md-5">
            <img src="@/assets/images/solar_bell-outline.png" alt="bell" width="24" height="24" class="header-icon" />
            <span>مركز الإشعارات والتنبيهات</span>
          </h5>
          <p class="text-muted fs-8 mb-0">
            متابعة نداءات الاستجابة الطارئة وتحديثات مخزون الدم أولاً بأول
          </p>
        </div>
      </div>

      <!-- مكون أزرار الفلترة -->
      <NotificationFilters
        v-model:selectedFilter="selectedFilter"
        @markAllAsRead="handleMarkAllAsRead"
      />

      <!-- مؤشر التحميل -->
      <div v-if="notificationStore.loading" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">جاري التحميل...</span>
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
import { useNotificationStore } from '@/stores/notificationStore';

const notificationStore = useNotificationStore();
const selectedFilter = ref('all');

const filteredNotifications = computed(() => {
  if (selectedFilter.value === 'unread') {
    return notificationStore.notifications.filter(n => !n.read && !n.unread);
  }
  if (selectedFilter.value === 'alerts') {
    return notificationStore.notifications.filter(n => n.type === 'emergency');
  }
  return notificationStore.notifications;
});

const emptyMessageText = computed(() => {
  if (selectedFilter.value === 'alerts') {
    return 'لا توجد تنبيهات عاجلة لعرضها حالياً';
  }
  return 'لا توجد إشعارات لعرضها حالياً';
});

const handleMarkAllAsRead = async () => {
  await notificationStore.markAllAsRead();
};

onMounted(() => {
  notificationStore.fetchNotifications();
});
</script>

<style scoped>
.notifications-page {
  font-family: 'Cairo', sans-serif;
  direction: rtl;
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
</style>
