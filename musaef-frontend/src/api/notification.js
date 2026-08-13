import apiClient from './axios';

export default {
  // جلب قائمة الإشعارات الخاصة بالمستخدم بشكل ديناميكي حسب دوره المحدث في localStorage مع دعم الفلترة
  getNotifications(params = {}, config = {}) {
    const userRole = localStorage.getItem('user_role') || 'donor';
    return apiClient.get(`/${userRole}/notifications`, { params, ...config });
  },

  // تحديد إشعار معين أو جميع الإشعارات كـ "مقروءة" بناءً على دور المستخدم
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
