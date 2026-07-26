import { computed } from 'vue'
import { storeToRefs } from 'pinia'

import { useNotificationStore } from '@/stores/notificationStore'

export const useNotifications = () => {
  const notificationStore = useNotificationStore()

  const {
    notifications,
    selectedFilter,
    isLoading,
    error,
  } = storeToRefs(notificationStore)

  const filteredNotifications = computed(
    () => notificationStore.filteredNotifications,
  )

  const unreadCount = computed(
    () => notificationStore.unreadCount,
  )

  const filters = computed(() => [
    {
      label: 'الكل',
      value: 'all',
      icon: 'bi bi-list-ul',
      count: notifications.value.length,
    },
    {
      label: 'غير المقروءة',
      value: 'unread',
      icon: 'bi bi-circle-fill',
      count: unreadCount.value,
    },
    {
      label: 'التنبيهات',
      value: 'alerts',
      icon: 'bi bi-exclamation-triangle',
      count: notifications.value.filter(
        (notification) => notification.isUrgent,
      ).length,
    },
  ])

  const changeFilter = (filter) => {
    notificationStore.setFilter(filter)
  }

  const loadNotifications = async () => {
    await notificationStore.fetchNotifications()
  }

  const markAllAsRead = async () => {
    await notificationStore.markAllAsRead()
  }

  const markAsRead = async (notificationId) => {
    await notificationStore.markAsRead(notificationId)
  }

  return {
    notifications,
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
  }
}