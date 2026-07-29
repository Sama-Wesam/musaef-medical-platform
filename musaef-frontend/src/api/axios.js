import axios from 'axios';

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api',
  headers: {
    'Accept': 'application/json'
  },
  timeout: 15000
});

apiClient.interceptors.request.use(
  (config) => {
    if (config.data instanceof FormData) {
      delete config.headers['Content-Type'];
    } else {
      config.headers['Content-Type'] = 'application/json';
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
  (response) => response.data,
  (error) => {
    const isAuthRequest = error.config?.url?.includes('/login') || error.config?.url?.includes('/register');

    if (error.response?.status === 401 && !isAuthRequest) {
      localStorage.removeItem('token');
      localStorage.removeItem('musaef_token');
      localStorage.removeItem('user');
      localStorage.removeItem('musaef_user');

      if (!window.location.pathname.includes('/login') && !window.location.pathname.includes('/register')) {
        window.location.href = '/login';
      }
    }

    // إرجاع كائن الخطأ بشكل مضمون كي يتوفر رسالة واضحة للفرونت إند
    const extractMessage = error.response?.data?.message || error.message || 'حدث خطأ في الاتصال بالشبكة';
    const normalizedError = new Error(extractMessage);
    normalizedError.response = error.response;
    normalizedError.data = error.response?.data;

    return Promise.reject(normalizedError);
  }
);

export default apiClient;
