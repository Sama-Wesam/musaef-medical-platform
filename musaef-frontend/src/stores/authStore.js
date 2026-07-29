import { defineStore } from 'pinia';
import authApi from '@/api/auth';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token') || localStorage.getItem('musaef_token') || null,
    user: JSON.parse(localStorage.getItem('user') || localStorage.getItem('musaef_user') || 'null'),
    error: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    userName: (state) => state.user?.name || 'مستخدم',
    userRole: (state) => state.user?.role || 'donor',
    isHospital: (state) => state.user?.role === 'hospital' || state.user?.role === 'blood_bank',
  },

  actions: {
    setAuthData(data) {
      const responseData = data.data || data;
      const token = responseData.token || data.token;
      const user = responseData.user || data.user;

      if (token && user) {
        this.token = token;
        this.user = user;
        localStorage.setItem('token', token);
        localStorage.setItem('musaef_token', token);
        localStorage.setItem('user', JSON.stringify(user));
        localStorage.setItem('musaef_user', JSON.stringify(user));
      }
    },

    async login(credentials) {
      try {
        this.error = null;
        const response = await authApi.login(credentials);
        this.setAuthData(response);
        return response;
      } catch (error) {
        this.error = error.message || 'بيانات الدخول غير صحيحة';
        throw error;
      }
    },

    async registerDonor(formData) {
      try {
        this.error = null;
        const response = await authApi.registerDonor(formData);
        this.setAuthData(response);
        return response;
      } catch (error) {
        this.error = error.message || 'فشل تسجيل المتبرع';
        throw error;
      }
    },

    async registerHospital(formData) {
      try {
        this.error = null;
        const response = await authApi.registerHospital(formData);
        return response;
      } catch (error) {
        this.error = error.message || 'فشل تسجيل المستشفى';
        throw error;
      }
    },

    async logout() {
      try {
        await authApi.logout();
      } catch (e) {
        console.error(e);
      } finally {
        this.token = null;
        this.user = null;
        this.error = null;
        localStorage.clear();
      }
    }
  }
});
