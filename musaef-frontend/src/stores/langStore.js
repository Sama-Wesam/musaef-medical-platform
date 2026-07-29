import { defineStore } from 'pinia';

export const useLangStore = defineStore('lang', {
  state: () => ({
    currentLang: localStorage.getItem('musaef_lang') || 'ar'
  }),

  getters: {
    isRtl: (state) => state.currentLang === 'ar',
    dir: (state) => (state.currentLang === 'ar' ? 'rtl' : 'ltr')
  },

  actions: {
    setLanguage(lang) {
      this.currentLang = lang;
      localStorage.setItem('musaef_lang', lang);
      document.documentElement.setAttribute('dir', this.dir);
      document.documentElement.setAttribute('lang', lang);
    },

    toggleLanguage() {
      const targetLang = this.currentLang === 'ar' ? 'en' : 'ar';
      this.setLanguage(targetLang);
    }
  }
});
