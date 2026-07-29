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

  const validateEmail = (email) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
  };

  const validateLoginForm = (credentials) => {
    if (!credentials.email || !validateEmail(credentials.email)) {
      return 'يرجى إدخال بريد إلكتروني صحيح';
    }
    if (!credentials.password || credentials.password.length < 6) {
      return 'كلمة المرور يجب ألا تقل عن 6 أحرف';
    }
    return null;
  };

  const validateRegisterForm = (formData, accountType) => {
    if (!formData.fullName || formData.fullName.trim().length < 3) {
      return 'يرجى إدخال الاسم الكامل بشكل صحيح';
    }
    if (!formData.email || !validateEmail(formData.email)) {
      return 'يرجى إدخال بريد إلكتروني صحيح';
    }
    if (!formData.phone) {
      return 'رقم الهاتف مطلوب';
    }
    if (!formData.password || formData.password.length < 8) {
      return 'كلمة المرور يجب ألا تقل عن 8 أحرف';
    }
    if (formData.password !== formData.password_confirmation) {
      return 'كلمتا المرور غير متطابقتين';
    }
    if (!formData.terms) {
      return 'يجب الموافقة على الشروط والأحكام وسياسة الخصوصية';
    }

    if (accountType === 'donor') {
      if (!formData.bloodType) return 'يرجى اختيار فصيلة الدم';
      if (!formData.birthDate) return 'يرجى إدخال تاريخ الميلاد';
      if (!formData.gender) return 'يرجى اختيار الجنس';
    }

    if (accountType === 'hospital') {
      if (!formData.facilityName) return 'يرجى إدخال اسم الجهة الطبية';
      if (!formData.facilityType) return 'يرجى اختيار نوع الجهة';
      if (!formData.licenseNumber) return 'يرجى إدخال رقم الترخيص الطبي';
      if (!formData.managerName) return 'يرجى إدخال اسم المسؤول';
      if (!formData.licenseFile) return 'يرجى رفع نسخة من الترخيص الطبي';
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
      error.value = err.message || authStore.error || 'فشل تسجيل الدخول';
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
        payload.append('hospital_type', formData.facilityType);
        payload.append('license_number', formData.licenseNumber);

        if (formData.licenseFile) {
          payload.append('license_file', formData.licenseFile);
        }

        await authStore.registerHospital(payload);
      }

      return { success: true };
    } catch (err) {
      error.value = err.message || authStore.error || 'تعذر إنشاء الحساب حالياً';
      return { success: false, message: error.value };
    } finally {
      loading.value = false;
    }
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
    logout,
    redirectUserByRole
  };
}
