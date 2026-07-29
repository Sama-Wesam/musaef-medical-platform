<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end dir-rtl">

    <form @submit.prevent="saveSettings">
      <!-- Section 1: بيانات الحساب الأساسية -->
      <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
        <span class="text-danger fs-5">👤</span>
        <h6 class="fw-bold text-dark mb-0 fs-6">بيانات الحساب الأساسية</h6>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-12 col-md-4 text-end">
          <label class="form-label fs-7 text-dark fw-bold mb-2">اسم الجهة الطبية</label>
          <div class="input-icon-wrapper">
            <input type="text" class="form-control rounded-3 bg-light-input border-0 py-2 fs-7 text-end" v-model="form.name" required />
            <span class="input-icon">🏢</span>
          </div>
        </div>

        <div class="col-12 col-md-4 text-end">
          <label class="form-label fs-7 text-dark fw-bold mb-2">البريد الإلكتروني الرسمي</label>
          <div class="input-icon-wrapper">
            <input type="email" class="form-control rounded-3 bg-light-input border-0 py-2 fs-7 text-start" v-model="form.contact_email" dir="ltr" required />
            <span class="input-icon">✉️</span>
          </div>
        </div>

        <div class="col-12 col-md-4 text-end">
          <label class="form-label fs-7 text-dark fw-bold mb-2">رقم الهاتف الأساسي</label>
          <div class="input-icon-wrapper">
            <input type="text" class="form-control rounded-3 bg-light-input border-0 py-2 fs-7 text-start" v-model="form.phone_number" dir="ltr" required />
            <span class="input-icon">📞</span>
          </div>
        </div>
      </div>

      <!-- Section 2: البيانات الجغرافية والتشغيلية -->
      <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4 pt-3 border-top">
        <span class="text-danger fs-5">📍</span>
        <h6 class="fw-bold text-dark mb-0 fs-6">البيانات الجغرافية والتشغيلية</h6>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-12 col-md-4 text-end">
          <label class="form-label fs-7 text-dark fw-bold mb-2">المنطقة / المدينة</label>
          <div class="input-icon-wrapper">
            <input type="text" class="form-control rounded-3 bg-light-input border-0 py-2 fs-7 text-end" v-model="form.city" />
            <span class="input-icon">📍</span>
          </div>
        </div>

        <div class="col-12 col-md-4 text-end">
          <label class="form-label fs-7 text-dark fw-bold mb-2">العنوان التفصيلي</label>
          <div class="input-icon-wrapper">
            <input type="text" class="form-control rounded-3 bg-light-input border-0 py-2 fs-7 text-end" v-model="form.address" />
            <span class="input-icon">📍</span>
          </div>
        </div>

        <div class="col-12 col-md-4 text-end">
          <label class="form-label fs-7 text-dark fw-bold mb-2">ساعات العمل</label>
          <div class="input-icon-wrapper">
            <input type="text" class="form-control rounded-3 bg-light-input border-0 py-2 fs-7 text-end" v-model="form.working_hours" />
            <span class="input-icon">⏰</span>
          </div>
        </div>
      </div>

      <!-- Control Buttons -->
      <div class="d-flex align-items-center justify-content-end gap-2 border-top pt-4">
        <button type="button" class="btn btn-outline-secondary px-3 px-md-4 py-2 rounded-3 fs-7 fw-bold" @click="resetForm">إلغاء</button>
        <button type="submit" class="btn btn-danger px-3 px-md-4 py-2 rounded-3 fs-7 fw-bold">حفظ البيانات</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import hospitalApi from '@/api/hospital';

const props = defineProps({
  hospitalData: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['update:hospitalData']);
const form = ref({ ...props.hospitalData });

watch(() => props.hospitalData, (newData) => {
  if (newData) {
    form.value = { ...newData };
  }
}, { deep: true });

const saveSettings = async () => {
  try {
    await hospitalApi.updateHospitalProfile(form.value);
    // تحديث البيانات محلياً فوراً لضمان عدم اختفائها عند ظهور تنبيه النجاح
    emit('update:hospitalData', { ...form.value });
    alert('تم تحديث البيانات بنجاح');
  } catch (err) {
    alert('حدث خطأ أثناء حفظ البيانات');
  }
};

const resetForm = () => {
  form.value = { ...props.hospitalData };
};
</script>

<style scoped>
.fs-7 { font-size: 0.85rem; }
.bg-light-input { background-color: #f8fafc !important; border: 1px solid #e2e8f0 !important; padding-left: 2.5rem !important; }
.input-icon-wrapper { position: relative; display: flex; align-items: center; }
.input-icon { position: absolute; left: 12px; color: #94a3b8; font-size: 0.9rem; pointer-events: none; }
.dir-rtl { direction: rtl; }
</style>
