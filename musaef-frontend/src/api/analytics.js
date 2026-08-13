import apiClient from './axios';

export default {
  // جلب المؤشرات الرئيسية (KPIs) للوحة تحكم الإدارة
  getDashboardStats(params = {}) {
    return apiClient.get('/admin/dashboard', { params });
  },

  // جلب بيانات الخريطة الحرارية للحالات الحرجة والمناطق
  getHeatmapData(params = {}) {
    return apiClient.get('/admin/analytics/heatmap', { params });
  },

  // جلب تقارير الذكاء الاصطناعي لتوقع النقص المستقبلي في مخزون الدم
  getAIPredictions(params = {}) {
    return apiClient.get('/admin/analytics/ai-predictions', { params });
  }
};
