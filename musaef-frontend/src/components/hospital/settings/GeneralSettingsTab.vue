<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">

    <form @submit.prevent="saveSettings">
      <!-- Section 1: بيانات الحساب الأساسية -->
      <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
        <span class="text-danger fs-5">👤</span>
        <h6 class="fw-bold text-dark mb-0 fs-6">{{ t('accountDataTitle') }}</h6>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-12 col-md-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
          <label class="form-label fs-7 text-dark fw-bold mb-2">{{ t('facilityName') }}</label>
          <div class="input-icon-wrapper">
            <input
              type="text"
              class="form-control rounded-3 bg-light-input border-0 py-2 fs-7"
              :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'"
              :value="displayFacilityName"
              @input="form.name = $event.target.value"
              required
            />
            <span class="input-icon" :style="currentLanguage === 'en' ? 'right: 12px; left: auto;' : 'left: 12px; right: auto;'">🏢</span>
          </div>
        </div>

        <div class="col-12 col-md-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
          <label class="form-label fs-7 text-dark fw-bold mb-2">{{ t('officialEmail') }}</label>
          <div class="input-icon-wrapper">
            <input type="email" class="form-control rounded-3 bg-light-input border-0 py-2 fs-7 text-start" v-model="form.contact_email" dir="ltr" required />
            <span class="input-icon" :style="currentLanguage === 'en' ? 'right: 12px; left: auto;' : 'left: 12px; right: auto;'">✉️</span>
          </div>
        </div>

        <div class="col-12 col-md-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
          <label class="form-label fs-7 text-dark fw-bold mb-2">{{ t('primaryPhone') }}</label>
          <div class="input-icon-wrapper">
            <input type="text" class="form-control rounded-3 bg-light-input border-0 py-2 fs-7 text-start" v-model="form.phone_number" dir="ltr" />
            <span class="input-icon" :style="currentLanguage === 'en' ? 'right: 12px; left: auto;' : 'left: 12px; right: auto;'">📞</span>
          </div>
        </div>
      </div>

      <!-- Section 2: البيانات الجغرافية والتشغيلية -->
      <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4 pt-3 border-top">
        <span class="text-danger fs-5">📍</span>
        <h6 class="fw-bold text-dark mb-0 fs-6">{{ t('geoTitle') }}</h6>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-12 col-md-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
          <label class="form-label fs-7 text-dark fw-bold mb-2">{{ t('cityRegion') }}</label>
          <div class="input-icon-wrapper">
            <input
              type="text"
              class="form-control rounded-3 bg-light-input border-0 py-2 fs-7"
              :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'"
              :value="displayCity"
              @input="form.city = $event.target.value"
            />
            <span class="input-icon" :style="currentLanguage === 'en' ? 'right: 12px; left: auto;' : 'left: 12px; right: auto;'">📍</span>
          </div>
        </div>

        <div class="col-12 col-md-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
          <label class="form-label fs-7 text-dark fw-bold mb-2">{{ t('detailedAddress') }}</label>
          <div class="input-icon-wrapper">
            <input
              type="text"
              class="form-control rounded-3 bg-light-input border-0 py-2 fs-7"
              :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'"
              :value="displayAddress"
              @input="form.address = $event.target.value"
            />
            <span class="input-icon" :style="currentLanguage === 'en' ? 'right: 12px; left: auto;' : 'left: 12px; right: auto;'">📍</span>
          </div>
        </div>

        <div class="col-12 col-md-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
          <label class="form-label fs-7 text-dark fw-bold mb-2">{{ t('workingHours') }}</label>
          <div class="input-icon-wrapper">
            <input
              type="text"
              class="form-control rounded-3 bg-light-input border-0 py-2 fs-7"
              :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'"
              :value="displayWorkingHours"
              @input="form.working_hours = $event.target.value"
            />
            <span class="input-icon" :style="currentLanguage === 'en' ? 'right: 12px; left: auto;' : 'left: 12px; right: auto;'">⏰</span>
          </div>
        </div>
      </div>

      <!-- Control Buttons -->
      <div class="d-flex align-items-center justify-content-end gap-2 border-top pt-4">
        <button type="button" class="btn btn-outline-secondary px-3 px-md-4 py-2 rounded-3 fs-7 fw-bold" @click="resetForm">{{ t('cancel') }}</button>
        <button type="submit" class="btn btn-danger px-3 px-md-4 py-2 rounded-3 fs-7 fw-bold">{{ t('saveData') }}</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import hospitalApi from '@/api/hospital';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    accountDataTitle: 'بيانات الحساب الأساسية',
    facilityName: 'اسم الجهة الطبية',
    officialEmail: 'البريد الإلكتروني الرسمي',
    primaryPhone: 'رقم الهاتف الأساسي',
    geoTitle: 'البيانات الجغرافية والتشغيلية',
    cityRegion: 'المنطقة / المدينة',
    detailedAddress: 'العنوان التفصيلي',
    workingHours: 'ساعات العمل',
    cancel: 'إلغاء',
    saveData: 'حفظ البيانات',
    successUpdate: 'تم تحديث البيانات بنجاح',
    errorUpdate: 'حدث خطأ أثناء حفظ البيانات'
  },
  en: {
    accountDataTitle: 'Basic Account Data',
    facilityName: 'Medical Facility Name',
    officialEmail: 'Official Email Address',
    primaryPhone: 'Primary Phone Number',
    geoTitle: 'Geographical & Operational Data',
    cityRegion: 'Region / City',
    detailedAddress: 'Detailed Address',
    workingHours: 'Working Hours',
    cancel: 'Cancel',
    saveData: 'Save Data',
    successUpdate: 'Data updated successfully',
    errorUpdate: 'Error occurred while saving data'
  }
};

const valueTranslations = {
  facilityName: {
    'جمعية بنك الدم المركزي': 'Central Blood Bank Society',
    'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex'
  },
  city: {
    'رفح': 'Rafah',
    'غزة': 'Gaza',
    'خانيونس': 'Khan Younis',
    'دير البلح': 'Deir al-Balah',
    'شمال غزة': 'North Gaza'
  },
  address: {
    'غزة - الرمال شارع الوحدة': 'Gaza - Rimal, Al-Wehda St',
    'غزة - الرمال': 'Gaza - Rimal'
  },
  workingHours: {
    '24 ساعة 7 أيام في الأسبوع': '24 Hours 7 Days a Week'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

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

// القواعد الترجمية الديناميكية للقيم داخل الحقول
const displayFacilityName = computed(() => {
  const name = form.value.name || 'جمعية بنك الدم المركزي';
  if (currentLanguage.value === 'en') {
    return valueTranslations.facilityName[name] || name;
  }
  return name;
});

const displayCity = computed(() => {
  const city = form.value.city || 'رفح';
  if (currentLanguage.value === 'en') {
    return valueTranslations.city[city] || city;
  }
  return city;
});

const displayAddress = computed(() => {
  const addr = form.value.address || 'غزة - الرمال شارع الوحدة';
  if (currentLanguage.value === 'en') {
    return valueTranslations.address[addr] || addr;
  }
  return addr;
});

const displayWorkingHours = computed(() => {
  const hours = form.value.working_hours || '24 ساعة 7 أيام في الأسبوع';
  if (currentLanguage.value === 'en') {
    return valueTranslations.workingHours[hours] || hours;
  }
  return hours;
});

const saveSettings = async () => {
  try {
    await hospitalApi.updateHospitalProfile(form.value);
    emit('update:hospitalData', { ...form.value });
    alert(t('successUpdate'));
  } catch (err) {
    alert(t('errorUpdate'));
  }
};

const resetForm = () => {
  form.value = { ...props.hospitalData };
};
</script>

<style scoped>
.fs-7 { font-size: 0.85rem; }
.bg-light-input { background-color: #f8fafc !important; border: 1px solid #e2e8f0 !important; }
.input-icon-wrapper { position: relative; display: flex; align-items: center; }
.input-icon { position: absolute; color: #94a3b8; font-size: 0.9rem; pointer-events: none; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
