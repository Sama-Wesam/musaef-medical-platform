import { defineStore } from 'pinia';
import notificationApi from '@/api/notification';

export const useNotificationStore = defineStore('notification', {
  state: () => ({
    notifications: [
      { id: 1, title: 'قام المتبرع احمد خالد بالاستجابة للنداء الطارئ رقم ER-2026-1847', desc: 'وهو في طريقة للمستشفى الان', time: 'منذ دقيقتين', read: false, type: 'emergency' },
      { id: 2, title: 'تنبيه حرج: انخفض مخزون فصيلة الدم O- عن الحد الآمن', desc: 'يرجى إنشاء نداء استجابة فورية', time: 'منذ 10 دقائق', read: false, type: 'emergency' },
      { id: 3, title: 'تم اعتماد وتوثيق أوراق المنشأة الطبية بنجاح', desc: 'من قبل بنك الدم المركزي', time: 'منذ 3 ساعات', read: true, type: 'general' }
    ],
    loading: false
  }),

  getters: {
    unreadCount: (state) => state.notifications.filter(n => !n.read).length
  },

  actions: {
    async fetchNotifications() {
      this.loading = true;
      try {
        const response = await notificationApi.getNotifications();
        const list = response.data?.data || response.data || response;
        if (Array.isArray(list) && list.length > 0) {
          this.notifications = list;
        }
      } catch (err) {
        console.error('خطأ في جلب الإشعارات:', err);
      } finally {
        this.loading = false;
      }
    },

    async markAllAsRead() {
      this.notifications.forEach(n => n.read = true);
      try {
        await notificationApi.markAsRead();
      } catch (err) {
        console.error('خطأ في تحديث الإشعارات:', err);
      }
    },

    addNotification(item) {
      this.notifications.unshift({
        id: Date.now(),
        time: 'الآن',
        read: false,
        ...item
      });
    }
  }
});
