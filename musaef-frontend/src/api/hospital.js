import apiClient from './axios';

export default {
  // جلب المخزون اللحظي لبنك الدم الخاص بالمستشفى وإحصائياته
  getBloodInventory(params = {}) {
    return apiClient.get('/hospital/inventory', { params });
  },

  // تحديث كمية الوحدات (إضافة أو سحب)
  updateInventory(payload) {
    return apiClient.post('/hospital/inventory/update', payload);
  },

  // جلب المتبرعين المستجيبين لنداء طارئ معين
  getDonorResponses(emergencyId) {
    return apiClient.get(`/hospital/emergencies/${emergencyId}/responses`);
  },

  // جلب بيانات الإعدادات والملف التعريفي للمستشفى
  getHospitalProfile() {
    return apiClient.get('/hospital/settings/profile');
  },

  // تحديث بيانات وإعدادات المستشفى
  updateHospitalProfile(data) {
    return apiClient.put('/hospital/settings/profile', data);
  }
};
