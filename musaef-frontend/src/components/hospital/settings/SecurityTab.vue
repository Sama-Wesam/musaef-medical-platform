<template>
  <div class="row g-3 g-lg-4 align-items-start dir-rtl">
    <div class="col-12 col-lg-5">
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end h-100">
        <h6 class="fw-bold text-dark mb-1 fs-6">الجلسات النشطة</h6>
        <p class="text-muted fs-7 mb-4">الأجهزة والجلسات المتصلة بحسابك حالياً</p>

        <div class="d-flex flex-column gap-3">
          <div v-for="(session, index) in activeSessions" :key="index" class="p-3 border rounded-3 bg-light-subtle">
            <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
              <span class="fw-bold fs-7 text-dark">{{ session.title }}</span>
              <span v-if="session.isCurrent" class="badge bg-success-subtle text-success fs-8 px-2 py-1 rounded-pill">● نشط الآن</span>
            </div>
            <div class="text-muted fs-8 text-break">{{ session.deviceInfo }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-7">
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end h-100">
        <h6 class="fw-bold text-dark mb-1 fs-6">تغيير كلمة المرور</h6>
        <p class="text-muted fs-7 mb-4">قم بتحديث كلمة المرور الخاصة بحسابك</p>

        <form @submit.prevent="updatePassword">
          <div class="mb-3">
            <label class="form-label fs-7 text-muted d-block mb-1">كلمة المرور الحالية</label>
            <input type="password" class="form-control rounded-3 bg-light border-0 py-2 fs-7" v-model="securityForm.current_password" placeholder="••••••••••••" required />
          </div>

          <div class="mb-3">
            <label class="form-label fs-7 text-muted d-block mb-1">كلمة المرور الجديدة</label>
            <input type="password" class="form-control rounded-3 bg-light border-0 py-2 fs-7" v-model="securityForm.new_password" placeholder="••••••••••••" required />
          </div>

          <div class="mb-4">
            <label class="form-label fs-7 text-muted d-block mb-1">تأكيد كلمة المرور الجديدة</label>
            <input type="password" class="form-control rounded-3 bg-light border-0 py-2 fs-7" v-model="securityForm.new_password_confirmation" placeholder="••••••••••••" required />
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-danger px-4 py-2 rounded-3 fs-7 fw-bold">
              تحديث كلمة المرور
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import hospitalApi from '@/api/hospital';

const securityForm = ref({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
});

const activeSessions = ref([
  { title: 'الجلسة الحالية', deviceInfo: 'غزة - Chrome . قطاع غزة', isCurrent: true },
  { title: 'آخر نشاط أمس 10:24 ص', deviceInfo: 'دير البلح - iPhone . قطاع غزة', isCurrent: false },
  { title: 'آخر نشاط 12 مايو 2026', deviceInfo: 'خانيونس - MacBook Pro . قطاع غزة', isCurrent: false }
]);

const updatePassword = async () => {
  try {
    await hospitalApi.updateHospitalProfile(securityForm.value);
    alert('تم تغيير كلمة المرور بنجاح');
    securityForm.value = { current_password: '', new_password: '', new_password_confirmation: '' };
  } catch (err) {
    alert('حدث خطأ أثناء تغيير كلمة المرور، تأكد من صحة البيانات المدخلة');
  }
};
</script>

<style scoped>
.fs-7 { font-size: 0.85rem; }
.fs-8 { font-size: 0.75rem; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-light-subtle { background-color: #f8fafc; }
.dir-rtl { direction: rtl; }
</style>
