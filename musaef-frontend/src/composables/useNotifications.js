import { computed } from 'vue';
import { useNotificationStore } from '@/stores/notificationStore';

export function useNotifications() {
  const notificationStore = useNotificationStore();

  const notifications = computed(() => notificationStore.notifications);
  const unreadCount = computed(() => notificationStore.unreadCount);
  const loading = computed(() => notificationStore.loading);

  const fetchNotifications = async () => {
    await notificationStore.fetchNotifications();
  };

  const markAllAsRead = async () => {
    await notificationStore.markAllAsRead();
  };

  const addNotification = (item) => {
    notificationStore.addNotification(item);
  };

  return {
    notifications,
    unreadCount,
    loading,
    fetchNotifications,
    markAllAsRead,
    addNotification
  };
}
