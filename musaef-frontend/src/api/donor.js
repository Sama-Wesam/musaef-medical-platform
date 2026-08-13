import apiClient from './axios';

export default {
  // جلب الملف الشخصي
  getProfile() {
    return apiClient.get('/donor/profile');
  },

  // جلب بيانات لوحة تحكم المتبرع الديناميكية
  getDashboardData() {
    return apiClient.get('/donor/dashboard');
  },

  // تحديث البيانات الشخصية
  updateProfile(formData) {
    return apiClient.post('/donor/profile/update', formData);
  },

  // تحديث الاستبيان الصحي
  updateHealthInfo(data) {
    return apiClient.post('/donor/health-questionnaire', data);
  },

  // جلب الحالات العاجلة المباشرة مع دعم معامل الاستعلام والـ Polling
  getUrgentRequests(params = {}, config = {}) {
    return apiClient.get('/donor/urgent-requests', { params, ...config });
  },

  getDonationRequests(params = {}, config = {}) {
    return this.getUrgentRequests(params, config);
  },

  // قبول طلب التبرع
  acceptDonationRequest(requestId) {
    return apiClient.post(`/donor/requests/${requestId}/accept`);
  },

  // بطاقة المتبرع والنقاط
  getRewardsAndCard() {
    return apiClient.get('/donor/rewards');
  },

  // سجل التبرعات
  getDonationHistory() {
    return apiClient.get('/donor/history');
  },

  // جلب الإشعارات
  getNotifications(params = {}) {
    return apiClient.get('/donor/notifications', { params });
  },

  // قراءة كافة الإشعارات
  markAllNotificationsAsRead() {
    return apiClient.post('/donor/notifications/mark-as-read');
  }
};
