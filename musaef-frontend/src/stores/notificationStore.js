import { defineStore } from 'pinia';
import apiClient from '@/api/axios';
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
    unreadCount: (state) => state.notifications.filter(n => !n.read && !n.read_at && !n.is_read).length
  },

  actions: {
    // 1. جلب قائمة الإشعارات من الباك إند ديناميكياً
    async fetchNotifications() {
      this.loading = true;
      try {
        const userRole = localStorage.getItem('user_role') || 'donor';
        let response;

        try {
          response = await notificationApi.getNotifications();
        } catch (e) {
          response = await apiClient.get(`/${userRole}/notifications`);
        }

        const list = Array.isArray(response) ? response : (response.data?.data || response.data || response.notifications || []);
        if (Array.isArray(list) && list.length > 0) {
          this.notifications = list.map(item => ({
            id: item.id || Date.now(),
            title: item.data?.title || item.title || 'تنبيه جديد',
            desc: item.data?.body || item.desc || item.body || '',
            time: item.created_at ? new Date(item.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' }) : (item.time || 'الآن'),
            read: Boolean(item.read_at || item.is_read || item.read),
            type: item.data?.type || item.type || 'emergency'
          }));
        }
      } catch (err) {
        console.warn('استخدام قائمة الإشعارات الافتراضية.');
      } finally {
        this.loading = false;
      }
    },

    // 2. التأشير على جميع الإشعارات كمقروءة
    async markAllAsRead() {
      this.notifications.forEach(n => n.read = true);
      try {
        const userRole = localStorage.getItem('user_role') || 'donor';
        try {
          await notificationApi.markAsRead();
        } catch (e) {
          await apiClient.post(`/${userRole}/notifications/read-all`);
        }
      } catch (err) {
        console.warn('تم تغيير حالة الإشعارات محلياً.');
      }
    },

    // 3. التأشير على إشعار منفرد كمقروء
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

    // 4. إدراج إشعار حي جديد مباشر
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
