import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

export const useNotificationStore = defineStore('notification', {
  state: () => ({
    notifications: [], // تم جعلها تبدأ بمصفوفة فارغة لضمان عدم وجود إشعارات وهمية
    loading: false
  }),

  getters: {
    unreadCount: (state) => state.notifications.filter(n => !n.read && !n.read_at && !n.is_read).length
  },

  actions: {
    async fetchNotifications() {
      this.loading = true;
      try {
        const userRole = localStorage.getItem('user_role') || 'donor';

        // جلب الإشعارات عبر apiClient المعتمد مباشرة لتفادي مشاكل استيراد الملفات الفرعية
        const response = await apiClient.get(`/${userRole}/notifications`);

        const list = Array.isArray(response) ? response : (response.data?.data || response.data || response.notifications || []);

        if (Array.isArray(list)) {
          this.notifications = list.map(item => ({
            id: item.id || Date.now(),
            title: item.data?.title || item.title || 'تنبيه جديد',
            desc: item.data?.body || item.desc || item.body || '',
            time: item.created_at ? new Date(item.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' }) : (item.time || 'الآن'),
            read: Boolean(item.read_at || item.is_read || item.read),
            type: item.data?.type || item.type || 'emergency'
          }));
        } else {
          this.notifications = [];
        }
      } catch (err) {
        console.warn('تعذر جلب الإشعارات حالياً، تم إرجاع قائمة فارغة.');
        this.notifications = [];
      } finally {
        this.loading = false;
      }
    },

    async markAllAsRead() {
      this.notifications.forEach(n => n.read = true);
      try {
        const userRole = localStorage.getItem('user_role') || 'donor';
        await apiClient.post(`/${userRole}/notifications/read-all`);
      } catch (err) {
        console.warn('تم تغيير حالة الإشعارات محلياً.');
      }
    },

    async markAsRead(notificationId) {
      const notif = this.notifications.find(n => n.id === notificationId);
      if (notif) notif.read = true;

      try {
        const userRole = localStorage.getItem('user_role') || 'donor';
        await apiClient.post(`/${userRole}/notifications/${notificationId}/read`);
      } catch (err) {
        console.warn('تم تحديث حالة الإشعار محلياً.');
      }
    },

    addNotification(item) {
      this.notifications.unshift({
        id: item.id || Date.now(),
        title: item.title || 'نداء طارئ جديد',
        desc: item.desc || item.body || '',
        time: 'الآن',
        read: false,
        type: item.type || 'emergency'
      });
    }
  }
});
