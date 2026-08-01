import apiClient from './axios';

export default {
  // جلب قائمة الإشعارات الخاصة بالمستخدم بشكل ديناميكي حسب دوره
  getNotifications() {
    const userRole = localStorage.getItem('user_role') || 'donor';
    return apiClient.get(`/${userRole}/notifications`);
  },

  // تحديد إشعار معين أو جميع الإشعارات كـ "مقروءة"
  markAsRead(notificationId = null) {
    const userRole = localStorage.getItem('user_role') || 'donor';

    if (notificationId) {
      return apiClient.post(`/${userRole}/notifications/${notificationId}/read`);
    }
    return apiClient.post(`/${userRole}/notifications/read-all`);
  },

  // إرسال إشعار جماعي من لوحة التحكم (خاص بالإدارة)
  sendBroadcastNotification(payload) {
    return apiClient.post('/admin/broadcast-notification', payload);
  }
};
