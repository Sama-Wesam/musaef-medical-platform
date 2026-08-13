import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

export const useDonorStore = defineStore('donor', {
  state: () => ({
    donors: [],
    myCard: null,
    donationsHistory: [],
    loading: false,
    healthEligibility: JSON.parse(localStorage.getItem('donor_health_eligibility')) || {
      isEligible: true,
      statusTitle: 'مؤهل للتبرع',
      statusDescription: 'حالتك الصحية مؤهلة للتبرع',
      detailedMessage: 'يمكنك التبرع الآن، صحتك تسمح بذلك.',
      messageType: 'success'
    }
  }),

  actions: {
    setEligibility(isEligible) {
      if (!isEligible) {
        this.healthEligibility = {
          isEligible: false,
          statusTitle: 'غير مؤهل',
          statusDescription: 'صحتك تهمنا',
          detailedMessage: 'بناءً على إجاباتك الحالية، يفضل أخذ قسط من الراحة أو مراجعة الطبيب قبل التبرع حرصاً على سلامتك.',
          messageType: 'warning'
        };
      } else {
        this.healthEligibility = {
          isEligible: true,
          statusTitle: 'مؤهل للتبرع',
          statusDescription: 'حالتك الصحية مؤهلة للتبرع',
          detailedMessage: 'يمكنك التبرع الآن، صحتك تسمح بذلك.',
          messageType: 'success'
        };
      }
      localStorage.setItem('donor_health_eligibility', JSON.stringify(this.healthEligibility));
    },

    async syncEligibilityWithServer() {
      try {
        const response = await apiClient.get('/donor/health-status');
        if (response?.data?.is_eligible !== undefined) {
          this.setEligibility(response.data.is_eligible);
        }
      } catch (e) {
        console.warn('استخدام حالة الأهلية المخزنة محلياً عند تعذر الاتصال بالخادم');
      }
    }
  }
});
