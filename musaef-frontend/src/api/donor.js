import apiClient from './axios';

export default {
  getDashboardData() {
    return apiClient.get('/donor/home-stats');
  },
  getUrgentRequests() {
    return apiClient.get('/donor/urgent-requests');
  },
  getDonationRequests() {
    return apiClient.get('/emergencies/active');
  },
  acceptDonationRequest(requestId) {
    return apiClient.post(`/emergencies/${requestId}/accept`);
  },
  getRewardsAndCard() {
    return apiClient.get('/donor/rewards-and-card');
  },
  getDonationHistory() {
    return apiClient.get('/donor/donation-history');
  },
  // مسارات الإشعارات
  getNotifications() {
    return apiClient.get('/donor/notifications');
  },
  markAllNotificationsAsRead() {
    return apiClient.post('/donor/notifications/mark-as-read');
  }
};
