import apiClient from './axios';

export default {
  // جلب جميع الحالات الطارئة النشطة مع دعم الفلترة والـ Polling
  getActiveEmergencies(params = {}, config = {}) {
    return apiClient.get('/hospital/requests', { params, ...config });
  },

  // إنشاء نداء طارئ جديد من قبل المستشفى
  createEmergency(emergencyData) {
    return apiClient.post('/hospital/requests', emergencyData);
  },

  // جلب تفاصيل حالة طارئة محددة مع المستجيبين
  getEmergencyById(id) {
    return apiClient.get(`/hospital/requests/${id}`);
  },

  // جلب قائمة المستجيبين للنداء الطارئ
  getEmergencyResponders(requestId) {
    return apiClient.get(`/hospital/requests/${requestId}/responses`);
  },

  // قبول أو تحديث حالة الطلب
  acceptEmergency(id) {
    return apiClient.post(`/hospital/requests/${id}/accept`);
  },

  // رفض أو تحديث حالة الطلب
  rejectEmergency(id) {
    return apiClient.post(`/hospital/requests/${id}/reject`);
  }
};
