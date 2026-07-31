import apiClient from './axios';

export default {
  // جلب المؤشرات الرئيسية (KPIs) للوحة تحكم الإدارة
  getDashboardStats() {
    return apiClient.get('/admin/dashboard');
  },

  // جلب بيانات الخريطة الحرارية للحالات الحرجة والمناطق
  getHeatmapData() {
    return apiClient.get('/admin/analytics/heatmap');
  },

  // جلب تقارير الذكاء الاصطناعي لتوقع النقص المستقبلي في مخزون الدم
  getAIPredictions() {
    return apiClient.get('/admin/settings');
  }
};
