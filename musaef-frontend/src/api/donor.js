import apiClient from './axios';

export default {
  getDashboardData() {
    return apiClient.get('/donor/profile');
  },
  getUrgentRequests() {
    return apiClient.get('/emergencies/active');
  },
  getDonationRequests() {
    return apiClient.get('/emergencies/active');
  },
  acceptDonationRequest(requestId) {
    return apiClient.post(`/donor/requests/${requestId}/accept`);
  },
  getRewardsAndCard() {
    return apiClient.get('/donor/rewards');
  },
  getDonationHistory() {
    return apiClient.get('/donor/qr-card');
  },
  // مسارات الإشعارات
  getNotifications() {
    return apiClient.get('/donor/notifications');
  },
  markAllNotificationsAsRead() {
    return apiClient.post('/donor/notifications/mark-as-read');
  }
};
