import apiClient from './axios';

export default {
  // جلب بيانات الحالات الحية لرادار الطوارئ
  getEmergencyRadarData(urgencyFilter = 'all') {
    return apiClient.get('/admin/emergency-radar', {
      params: { urgency: urgencyFilter }
    });
  },

  // تفعيل استجابة فورية لحالة طوارئ
  triggerInstantResponse(requestId) {
    return apiClient.post(`/admin/emergency-radar/${requestId}/trigger-response`);
  }
};
