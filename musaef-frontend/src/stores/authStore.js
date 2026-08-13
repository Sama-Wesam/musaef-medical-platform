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
    userRole: (state) => state.user?.role || null,
    isHospital: (state) => state.user?.role === 'hospital' || state.user?.role === 'blood_bank',

    userAvatar: (state) => {
      const rawAvatar = state.user?.avatar_url
                     || state.user?.avatar
                     || state.user?.donor?.user?.avatar_url
                     || state.user?.donor?.user?.avatar
                     || state.user?.donor?.avatar;

      if (!rawAvatar || typeof rawAvatar !== 'string') return null;

      let cleanPath = rawAvatar.trim().replace(/\\/g, '/');
      if (!cleanPath) return null;

      if (cleanPath.startsWith('http://') || cleanPath.startsWith('https://') || cleanPath.startsWith('blob:') || cleanPath.startsWith('data:')) {
        return cleanPath;
      }

      cleanPath = cleanPath.replace(/^\/?storage\//, '').replace(/^\//, '');
      return `http://localhost:8000/storage/${cleanPath}`;
    }
  },

  actions: {
    setAuthData(responsePayload) {
      const payload = responsePayload?.data || responsePayload;
      const innerData = payload?.data || payload;

      const token = innerData?.token || payload?.token || responsePayload?.token;
      const user = innerData?.user || payload?.user || responsePayload?.user;

      if (token && user) {
        this.token = token;
        this.user = user;

        localStorage.setItem('token', token);
        localStorage.setItem('musaef_token', token);
        localStorage.setItem('user', JSON.stringify(user));
        localStorage.setItem('musaef_user', JSON.stringify(user));

        if (user.role) {
          localStorage.setItem('user_role', user.role);
        }
      }
    },

    updateUserData(newUserData) {
      if (!this.user) {
        this.user = {};
      }

      // تنظيف البيانات والتأكد من عدم استبدال البيانات القديمة بـ undefined
      const cleanedData = {};
      Object.keys(newUserData || {}).forEach((key) => {
        if (newUserData[key] !== undefined && newUserData[key] !== null) {
          cleanedData[key] = newUserData[key];
        }
      });

      // مزامنة حقول الصورة avatar و avatar_url معاً
      if (cleanedData.avatar) {
        cleanedData.avatar_url = cleanedData.avatar;
      } else if (cleanedData.avatar_url) {
        cleanedData.avatar = cleanedData.avatar_url;
      }

      this.user = {
        ...this.user,
        ...cleanedData
      };

      if (this.user.donor) {
        this.user.donor = {
          ...this.user.donor,
          ...cleanedData
        };
        if (this.user.donor.user) {
          this.user.donor.user = {
            ...this.user.donor.user,
            ...cleanedData
          };
        }
      }

      localStorage.setItem('user', JSON.stringify(this.user));
      localStorage.setItem('musaef_user', JSON.stringify(this.user));
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

        localStorage.removeItem('token');
        localStorage.removeItem('musaef_token');
        localStorage.removeItem('user');
        localStorage.removeItem('musaef_user');
        localStorage.removeItem('user_role');
      }
    }
  }
});
