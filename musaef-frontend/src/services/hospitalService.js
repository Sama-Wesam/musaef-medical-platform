import apiClient from './axios';

const hospitalService = {
  // جلب المخزون والبيانات الإحصائية
  getInventory: () => apiClient.get('/hospital/inventory'),
  updateInventory: (data) => apiClient.post('/hospital/inventory/update', data),
  getDashboardStats: () => apiClient.get('/hospital/dashboard/stats'),

  // جلب استجابات المتبرعين بناءً على معرف الطلب الموحد
  getRequestResponses: (requestId) => apiClient.get(`/hospital/requests/${requestId}/responses`),
};

export default hospitalService;
