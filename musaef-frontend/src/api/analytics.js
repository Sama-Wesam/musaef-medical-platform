import apiClient from './axios';

export default {
  // جلب المؤشرات الرئيسية (KPIs) للوحة تحكم الإدارة
  getDashboardStats() {
    return apiClient.get('/analytics/dashboard');
  },

  // جلب بيانات الخريطة الحرارية للحالات الحرجة والمناطق
  getHeatmapData() {
    return apiClient.get('/analytics/heatmap');
  },

  // جلب تقارير الذكاء الاصطناعي لتوقع النقص المستقبلي في مخزون الدم
  getAIPredictions() {
    return apiClient.get('/analytics/ai-predictions');
  }
};
