import apiClient from './axios';

export default {
  login(credentials) {
    return apiClient.post('/login', credentials);
  },
  registerDonor(userData) {
    return apiClient.post('/register/donor', userData);
  },
  registerHospital(userData) {
    return apiClient.post('/register/hospital', userData);
  },
  logout() {
    return apiClient.post('/logout');
  }
};
