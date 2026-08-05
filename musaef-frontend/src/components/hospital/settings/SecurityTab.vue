<template>
  <div class="row g-3 g-lg-4 align-items-start" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <!-- الجلسات النشطة -->
    <div class="col-12 col-lg-5">
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <h6 class="fw-bold text-dark mb-1 fs-6">{{ t('activeSessionsTitle') }}</h6>
        <p class="text-muted fs-7 mb-4">{{ t('activeSessionsDesc') }}</p>

        <div class="d-flex flex-column gap-3">
          <div v-for="(session, index) in activeSessions" :key="index" class="p-3 border rounded-3 bg-light-subtle">
            <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
              <span class="fw-bold fs-7 text-dark">{{ translateSessionTitle(session.titleKey, session.isCurrent) }}</span>
              <span v-if="session.isCurrent" class="badge bg-success-subtle text-success fs-8 px-2 py-1 rounded-pill">● {{ t('activeNow') }}</span>
            </div>
            <div class="text-muted fs-8 text-break">{{ translateDeviceInfo(session.deviceInfo) }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- تغيير كلمة المرور -->
    <div class="col-12 col-lg-7">
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <h6 class="fw-bold text-dark mb-1 fs-6">{{ t('changePasswordTitle') }}</h6>
        <p class="text-muted fs-7 mb-4">{{ t('changePasswordDesc') }}</p>

        <form @submit.prevent="updatePassword">
          <div class="mb-3">
            <label class="form-label fs-7 text-muted d-block mb-1">{{ t('currentPassword') }}</label>
            <input type="password" class="form-control rounded-3 bg-light border-0 py-2 fs-7" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'" v-model="securityForm.current_password" placeholder="••••••••••••" required />
          </div>

          <div class="mb-3">
            <label class="form-label fs-7 text-muted d-block mb-1">{{ t('newPassword') }}</label>
            <input type="password" class="form-control rounded-3 bg-light border-0 py-2 fs-7" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'" v-model="securityForm.new_password" placeholder="••••••••••••" required />
          </div>

          <div class="mb-4">
            <label class="form-label fs-7 text-muted d-block mb-1">{{ t('confirmNewPassword') }}</label>
            <input type="password" class="form-control rounded-3 bg-light border-0 py-2 fs-7" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'" v-model="securityForm.new_password_confirmation" placeholder="••••••••••••" required />
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-danger px-4 py-2 rounded-3 fs-7 fw-bold">
              {{ t('updatePasswordBtn') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import hospitalApi from '@/api/hospital';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    activeSessionsTitle: 'الجلسات النشطة',
    activeSessionsDesc: 'الأجهزة والجلسات المتصلة بحسابك حالياً',
    activeNow: 'نشط الآن',
    changePasswordTitle: 'تغيير كلمة المرور',
    changePasswordDesc: 'قم بتحديث كلمة المرور الخاصة بحسابك',
    currentPassword: 'كلمة المرور الحالية',
    newPassword: 'كلمة المرور الجديدة',
    confirmNewPassword: 'تأكيد كلمة المرور الجديدة',
    updatePasswordBtn: 'تحديث كلمة المرور',
    successMsg: 'تم تغيير كلمة المرور بنجاح',
    errorMsg: 'حدث خطأ أثناء تغيير كلمة المرور، تأكد من صحة البيانات المدخلة'
  },
  en: {
    activeSessionsTitle: 'Active Sessions',
    activeSessionsDesc: 'Devices and sessions currently connected to your account',
    activeNow: 'Active Now',
    changePasswordTitle: 'Change Password',
    changePasswordDesc: 'Update your account password',
    currentPassword: 'Current Password',
    newPassword: 'New Password',
    confirmNewPassword: 'Confirm New Password',
    updatePasswordBtn: 'Update Password',
    successMsg: 'Password changed successfully',
    errorMsg: 'An error occurred while changing password, check entered data'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const securityForm = ref({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
});

const activeSessions = ref([
  { titleKey: 'currentSession', deviceInfo: 'غزة - Chrome . قطاع غزة', isCurrent: true },
  { titleKey: 'lastActiveYesterday', deviceInfo: 'دير البلح - iPhone . قطاع غزة', isCurrent: false },
  { titleKey: 'lastActiveDate', deviceInfo: 'خانيونس - MacBook Pro . قطاع غزة', isCurrent: false }
]);

const translateSessionTitle = (key, isCurrent) => {
  if (currentLanguage.value === 'en') {
    if (isCurrent) return 'Current Session';
    if (key === 'lastActiveYesterday') return 'Last active yesterday 10:24 AM';
    if (key === 'lastActiveDate') return 'Last active 12 May 2026';
  }
  if (isCurrent) return 'الجلسة الحالية';
  if (key === 'lastActiveYesterday') return 'آخر نشاط أمس 10:24 ص';
  return 'آخر نشاط 12 مايو 2026';
};

const translateDeviceInfo = (info) => {
  if (currentLanguage.value === 'en') {
    return info.replace('قطاع غزة', 'Gaza Strip').replace('غزة', 'Gaza').replace('دير البلح', 'Deir al-Balah').replace('خانيونس', 'Khan Younis');
  }
  return info;
};

const updatePassword = async () => {
  try {
    await hospitalApi.updateHospitalProfile(securityForm.value);
    alert(t('successMsg'));
    securityForm.value = { current_password: '', new_password: '', new_password_confirmation: '' };
  } catch (err) {
    alert(t('errorMsg'));
  }
};
</script>

<style scoped>
.fs-7 { font-size: 0.85rem; }
.fs-8 { font-size: 0.75rem; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-light-subtle { background-color: #f8fafc; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
