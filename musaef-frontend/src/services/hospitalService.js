import apiClient from './axios';

const hospitalService = {
  // --- جلب المخزون والبيانات الإحصائية ---
  getInventory: () => apiClient.get('/hospital/inventory'),
  getBloodInventory: () => apiClient.get('/hospital/inventory'),

  updateInventory: (data) => apiClient.post('/hospital/inventory/update', data),
  updateStock: (data) => apiClient.post('/hospital/inventory/update', data),

  getDashboardStats: () => apiClient.get('/hospital/dashboard'),

  // --- إدارة طلبات الطوارئ ---
  getRequests: () => apiClient.get('/hospital/requests'),
  createEmergencyRequest: (requestData) => apiClient.post('/hospital/requests', requestData),
  getRequestResponses: (requestId) => apiClient.get(`/hospital/requests/${requestId}/responses`),

  // --- التفاعل مع المتبرعين والاستجابات ---
  updateDonorResponseStatus: (responseId, status) =>
    apiClient.patch(`/hospital/responses/${responseId}`, { status }),

  // --- الإشعارات والإعدادات ---
  getNotifications: () => apiClient.get('/hospital/notifications'),
  updateHospitalProfile: (settingsData) => apiClient.put('/hospital/settings/profile', settingsData)
};

export default hospitalService;
