import axios from 'axios';

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'https://musaef-medical-platform.onrender.com/api',
  headers: {
    'Accept': 'application/json'
  },
  timeout: 15000
});

apiClient.interceptors.request.use(
  (config) => {
    const currentLang = localStorage.getItem('musaef_lang') || 'ar';
    config.headers['Accept-Language'] = currentLang;

    if (config.url) {
      if (config.baseURL?.endsWith('/api') && config.url.startsWith('/api/')) {
        config.url = config.url.replace(/^\/api/, '');
      } else if (config.url.startsWith('/api/api/')) {
        config.url = config.url.replace('/api/api/', '/api/');
      } else if (!config.baseURL?.endsWith('/api') && !config.url.startsWith('/api') && !config.url.startsWith('http')) {
        config.url = `/api${config.url.startsWith('/') ? '' : '/'}${config.url}`;
      }
    }

    if (config.data instanceof FormData) {
      delete config.headers['Content-Type'];
    } else {
      config.headers['Content-Type'] = 'application/json';
    }

    if (config.method === 'get') {
      config.params = {
        _t: Date.now(),
        ...(config.params || {})
      };
    }

    const isAuthPath = config.url?.includes('/login') || config.url?.includes('/register');

    if (isAuthPath) {
      delete config.headers.Authorization;
      return config;
    }

    const token = localStorage.getItem('token') || localStorage.getItem('musaef_token');

    if (token && token !== 'null' && token !== 'undefined') {
      config.headers.Authorization = `Bearer ${token}`;
    } else {
      delete config.headers.Authorization;
    }

    return config;
  },
  (error) => Promise.reject(error)
);

apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    const isAuthRequest = error.config?.url?.includes('/login') || error.config?.url?.includes('/register');

    if (error.response?.status === 401 && !isAuthRequest) {
      localStorage.removeItem('token');
      localStorage.removeItem('musaef_token');
      localStorage.removeItem('user');
      localStorage.removeItem('musaef_user');
      localStorage.removeItem('user_role');

      if (!window.location.pathname.includes('/login') && !window.location.pathname.includes('/register')) {
        window.location.href = '/login';
      }
    }

    const extractMessage = error.response?.data?.message || error.message || 'حدث خطأ في الاتصال بالشبكة';
    const normalizedError = new Error(extractMessage);
    normalizedError.response = error.response;
    normalizedError.data = error.response?.data;

    return Promise.reject(normalizedError);
  }
);

export default apiClient;
