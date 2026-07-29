import apiClient from './axios';

export default {
  // جلب قائمة الإشعارات الخاصة بالمستخدم أو المستشفى
  getNotifications() {
    return apiClient.get('/hospital/notifications');
  },

  // تحديد إشعار معين أو جميع الإشعارات كـ "مقروءة"
  markAsRead(notificationId = null) {
    if (notificationId) {
      return apiClient.patch(`/hospital/notifications/${notificationId}/read`);
    }
    return apiClient.patch('/hospital/notifications/read-all');
  },

  // إرسال إشعار جماعي من لوحة التحكم (خاص بالإدارة)
  sendBroadcastNotification(payload) {
    return apiClient.post('/admin/broadcast-notification', payload);
  }
};
