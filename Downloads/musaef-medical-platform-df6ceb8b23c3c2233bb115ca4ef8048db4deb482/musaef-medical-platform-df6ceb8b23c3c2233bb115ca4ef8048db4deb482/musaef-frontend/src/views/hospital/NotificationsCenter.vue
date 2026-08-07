<template>
  <div class="notifications-page" dir="rtl">
    <section class="page-heading">
      <div>
        <h1>مركز الإشعارات والتنبيهات</h1>

        <p>
          متابعة نداءات الاستجابة الطارئة وتحديثات مخزون الدم
          أولًا بأول
        </p>
      </div>

      <button
        type="button"
        class="read-all-button"
        :disabled="isLoading || unreadCount === 0"
        @click="handleMarkAllAsRead"
      >
        <i class="bi bi-check-lg"></i>
        <span>تحديد الكل كمقروء</span>
      </button>
    </section>

    <section class="filters-section">
      <NotificationFilters
        :model-value="selectedFilter"
        :filters="filters"
        @update:model-value="changeFilter"
      />
    </section>

    <div
      v-if="error"
      class="error-message"
    >
      <i class="bi bi-exclamation-circle"></i>
      <span>{{ error }}</span>
    </div>

    <section
      v-if="isLoading"
      class="loading-state"
    >
      <span class="spinner"></span>
      <p>جارٍ تحميل الإشعارات...</p>
    </section>

    <section
      v-else-if="filteredNotifications.length"
      class="notifications-list"
    >
      <NotificationItem
        v-for="notification in filteredNotifications"
        :key="notification.id"
        :notification="notification"
        @read="handleMarkAsRead"
      />
    </section>

    <section
      v-else
      class="empty-state"
    >
      <i class="bi bi-bell-slash"></i>
      <h3>لا توجد إشعارات</h3>
      <p>لا توجد إشعارات ضمن هذا التصنيف حاليًا.</p>
    </section>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

import NotificationFilters from '@/components/hospital/NotificationFilters.vue'
import NotificationItem from '@/components/hospital/NotificationItem.vue'
import { useNotifications } from '@/composables/useNotifications'

const {
  selectedFilter,
  filteredNotifications,
  unreadCount,
  filters,
  isLoading,
  error,
  changeFilter,
  loadNotifications,
  markAllAsRead,
  markAsRead,
} = useNotifications()

const handleMarkAllAsRead = async () => {
  try {
    await markAllAsRead()
  } catch (requestError) {
    console.error(requestError)
  }
}

const handleMarkAsRead = async (notificationId) => {
  try {
    await markAsRead(notificationId)
  } catch (requestError) {
    console.error(requestError)
  }
}

onMounted(() => {
  loadNotifications()
})
</script>

