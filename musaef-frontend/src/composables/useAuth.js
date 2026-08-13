import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';

export function useAuth() {
  const router = useRouter();
  const authStore = useAuthStore();

  const loading = ref(false);
  const error = ref(null);

  const isAuthenticated = computed(() => authStore.isAuthenticated);
  const userRole = computed(() => authStore.userRole);
  const currentUser = computed(() => authStore.user);
  const user = computed(() => authStore.user);

  const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

  const validateEmail = (email) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
  };

  const validateLoginForm = (credentials) => {
    if (!credentials.email || !validateEmail(credentials.email)) {
      return currentLanguage.value === 'en' ? 'Please enter a valid email address' : 'يرجى إدخال بريد إلكتروني صحيح';
    }
    if (!credentials.password || credentials.password.length < 6) {
      return currentLanguage.value === 'en' ? 'Password must be at least 6 characters' : 'كلمة المرور يجب ألا تقل عن 6 أحرف';
    }
    return null;
  };

  const validateRegisterForm = (formData, accountType) => {
    if (!formData.fullName || formData.fullName.trim().length < 3) {
      return currentLanguage.value === 'en' ? 'Please enter a valid full name' : 'يرجى إدخال الاسم الكامل بشكل صحيح';
    }
    if (!formData.email || !validateEmail(formData.email)) {
      return currentLanguage.value === 'en' ? 'Please enter a valid email address' : 'يرجى إدخال بريد إلكتروني صحيح';
    }
    if (!formData.phone) {
      return currentLanguage.value === 'en' ? 'Phone number is required' : 'رقم الهاتف مطلوب';
    }
    if (!formData.password || formData.password.length < 8) {
      return currentLanguage.value === 'en' ? 'Password must be at least 8 characters' : 'كلمة المرور يجب ألا تقل عن 8 أحرف';
    }
    if (formData.password !== formData.password_confirmation) {
      return currentLanguage.value === 'en' ? 'Passwords do not match' : 'كلمتا المرور غير متطابقتين';
    }
    if (!formData.terms) {
      return currentLanguage.value === 'en' ? 'You must agree to the Terms and Privacy Policy' : 'يجب الموافقة على الشروط والأحكام وسياسة الخصوصية';
    }

    if (accountType === 'donor') {
      if (!formData.bloodType) return currentLanguage.value === 'en' ? 'Please select blood type' : 'يرجى اختيار فصيلة الدم';
      if (!formData.birthDate) return currentLanguage.value === 'en' ? 'Please select birth date' : 'يرجى إدخال تاريخ الميلاد';
      if (!formData.gender) return currentLanguage.value === 'en' ? 'Please select gender' : 'يرجى اختيار الجنس';
    }

    if (accountType === 'hospital') {
      if (!formData.facilityName) return currentLanguage.value === 'en' ? 'Please enter facility name' : 'يرجى إدخال اسم الجهة الطبية';
      if (!formData.facilityType) return currentLanguage.value === 'en' ? 'Please select facility type' : 'يرجى اختيار نوع الجهة';
      if (!formData.licenseNumber) return currentLanguage.value === 'en' ? 'Please enter license number' : 'يرجى إدخال رقم الترخيص الطبي';
      if (!formData.managerName) return currentLanguage.value === 'en' ? 'Please enter manager name' : 'يرجى إدخال اسم المسؤول';
      if (!formData.licenseFile) return currentLanguage.value === 'en' ? 'Please upload license copy' : 'يرجى رفع نسخة من الترخيص الطبي';
    }

    return null;
  };

  const redirectUserByRole = (role) => {
    if (!router) return;
    switch (role) {
      case 'admin':
        router.push({ name: 'AdminDashboard' });
        break;
      case 'hospital':
      case 'blood_bank':
        router.push({ name: 'HospitalDashboard' });
        break;
      case 'donor':
      default:
        router.push({ name: 'DonorDashboard' });
        break;
    }
  };

  const login = async (credentials) => {
    loading.value = true;
    error.value = null;

    const validationError = validateLoginForm(credentials);
    if (validationError) {
      loading.value = false;
      error.value = validationError;
      return { success: false, message: validationError };
    }

    try {
      await authStore.login(credentials);
      redirectUserByRole(authStore.userRole);
      return { success: true, user: authStore.user };
    } catch (err) {
      error.value = err.message || authStore.error || (currentLanguage.value === 'en' ? 'Login failed' : 'فشل تسجيل الدخول');
      return { success: false, message: error.value };
    } finally {
      loading.value = false;
    }
  };

  const register = async (formData, accountType = 'donor') => {
    loading.value = true;
    error.value = null;

    const validationError = validateRegisterForm(formData, accountType);
    if (validationError) {
      loading.value = false;
      error.value = validationError;
      return { success: false, message: validationError };
    }

    try {
      const payload = new FormData();
      payload.append('name', accountType === 'hospital' ? formData.managerName : formData.fullName);
      payload.append('email', formData.email);
      payload.append('phone', formData.phone);
      payload.append('password', formData.password);
      payload.append('password_confirmation', formData.password_confirmation);
      payload.append('role', accountType);

      if (accountType === 'donor') {
        payload.append('blood_type_id', formData.bloodType);
        payload.append('birth_date', formData.birthDate);
        payload.append('gender', formData.gender);

        await authStore.registerDonor(payload);
        redirectUserByRole(authStore.userRole);
      } else if (accountType === 'hospital') {
        payload.append('facility_name', formData.facilityName);
        payload.append('facility_type', formData.facilityType);
        payload.append('license_number', formData.licenseNumber);
        payload.append('manager_name', formData.managerName);

        if (formData.licenseFile) {
          payload.append('license_file', formData.licenseFile);
        }

        await authStore.registerHospital(payload);
        redirectUserByRole(authStore.userRole);
      }

      return { success: true };
    } catch (err) {
      error.value = err.message || authStore.error || (currentLanguage.value === 'en' ? 'Unable to create account currently' : 'تعذر إنشاء الحساب حالياً');
      return { success: false, message: error.value };
    } finally {
      loading.value = false;
    }
  };

  const sendPasswordResetEmail = async (emailStr) => {
    loading.value = true;
    error.value = null;
    if (!emailStr || !validateEmail(emailStr)) {
      error.value = currentLanguage.value === 'en' ? 'Please enter a valid email address' : 'يرجى إدخال بريد إلكتروني صحيح';
      loading.value = false;
      return { success: false };
    }
    setTimeout(() => {
      loading.value = false;
    }, 800);
    return { success: true };
  };

  const logout = async () => {
    await authStore.logout();
    if (router) router.push({ name: 'Login' });
  };

  return {
    currentUser,
    user,
    loading,
    error,
    isAuthenticated,
    userRole,
    login,
    register,
    sendPasswordResetEmail,
    logout,
    redirectUserByRole
  };
}
