import { computed, ref, onUnmounted } from 'vue';
import { useNotificationStore } from '@/stores/notificationStore';

export function useNotifications() {
  const notificationStore = useNotificationStore();
  const pollingTimer = ref(null);

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

  // استطلاع تلقائي حقيقي للإشعارات المباشرة دون إرهاق الـ Network
  const startNotificationsPolling = (intervalMs = 10000) => {
    stopNotificationsPolling();
    fetchNotifications();
    pollingTimer.value = setInterval(() => {
      fetchNotifications();
    }, intervalMs);
  };

  const stopNotificationsPolling = () => {
    if (pollingTimer.value) {
      clearInterval(pollingTimer.value);
      pollingTimer.value = null;
    }
  };

  onUnmounted(() => {
    stopNotificationsPolling();
  });

  return {
    notifications,
    unreadCount,
    loading,
    fetchNotifications,
    markAllAsRead,
    addNotification,
    startNotificationsPolling,
    stopNotificationsPolling
  };
}
