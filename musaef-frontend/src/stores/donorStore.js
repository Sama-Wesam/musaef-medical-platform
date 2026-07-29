// src/stores/donorStore.js
import { defineStore } from 'pinia';

export const useDonorStore = defineStore('donor', {
  state: () => ({
    // استرجاع حالة الأهلية المحفوظة مسبقاً أو تعيين القيمة الافتراضية
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
      // حفظ الحالة في التخزين المحلي لضمان المزامنة بين اللوحة والملف الشخصي
      localStorage.setItem('donor_health_eligibility', JSON.stringify(this.healthEligibility));
    }
  }
});
