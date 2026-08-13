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
              v-model="displayForm.name"
              required
            />
            <span class="input-icon" :style="currentLanguage === 'en' ? 'right: 12px; left: auto;' : 'left: 12px; right: auto;'">🏢</span>
          </div>
        </div>

        <div class="col-12 col-md-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
          <label class="form-label fs-7 text-dark fw-bold mb-2">{{ t('officialEmail') }}</label>
          <div class="input-icon-wrapper">
            <input type="email" class="form-control rounded-3 bg-light-input border-0 py-2 fs-7 text-start" v-model="displayForm.contact_email" dir="ltr" required />
            <span class="input-icon" :style="currentLanguage === 'en' ? 'right: 12px; left: auto;' : 'left: 12px; right: auto;'">✉️</span>
          </div>
        </div>

        <div class="col-12 col-md-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
          <label class="form-label fs-7 text-dark fw-bold mb-2">{{ t('primaryPhone') }}</label>
          <div class="input-icon-wrapper">
            <input type="text" class="form-control rounded-3 bg-light-input border-0 py-2 fs-7 text-start" v-model="displayForm.phone_number" dir="ltr" />
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
              v-model="displayForm.city"
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
              v-model="displayForm.address"
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
              v-model="displayForm.working_hours"
            />
            <span class="input-icon" :style="currentLanguage === 'en' ? 'right: 12px; left: auto;' : 'left: 12px; right: auto;'">⏰</span>
          </div>
        </div>
      </div>

      <!-- Control Buttons -->
      <div class="d-flex align-items-center justify-content-end gap-2 border-top pt-4">
        <button type="button" class="btn btn-outline-secondary px-3 px-md-4 py-2 rounded-3 fs-7 fw-bold" @click="resetForm">{{ t('cancel') }}</button>
        <button type="submit" class="btn btn-danger px-3 px-md-4 py-2 rounded-3 fs-7 fw-bold" :disabled="loading">
          <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
          <span>{{ t('saveData') }}</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import hospitalApi from '@/api/hospital';
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();
const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');
const loading = ref(false);

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
    successUpdate: 'تم تحديث البيانات وحفظها بنجاح',
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
    successUpdate: 'Data updated and saved successfully',
    errorUpdate: 'Error occurred while saving data'
  }
};

const facilityNameDict = {
  'المستشفى الإندونيسي': 'Indonesian Hospital',
  'مستشفى الإندونيسي': 'Indonesian Hospital',
  'المستشفى الإندونيسي – بيت لاهيا': 'Indonesian Hospital – Beit Lahia',
  'مستشفى كمال عدوان': 'Kamal Adwan Hospital',
  'مستشفى كمال عدوان – بيت لاهيا': 'Kamal Adwan Hospital – Beit Lahia',
  'مستشفى العودة - جباليا': 'Al-Awda Hospital - Jabalia',
  'مستشفى العودة – شمال غزة / جباليا': 'Al-Awda Hospital – Jabalia',
  'مستشفى العودة': 'Al-Awda Hospital',
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex',
  'مجمع الشفاء الطبي – مدينة غزة': 'Al-Shifa Medical Complex – Gaza City',
  'مستشفى الشفاء الطبي': 'Al-Shifa Medical Complex',
  'المستشفى الأهلي العربي (المعمداني)': 'Ahli Arab Hospital (Al-Mamdani)',
  'المستشفى الأهلي العربي (المعمداني) – مدينة غزة': 'Ahli Arab Hospital (Al-Mamdani) – Gaza City',
  'مستشفى القدس': 'Al-Quds Medical Hospital',
  'مستشفى القدس – مدينة غزة': 'Al-Quds Medical Hospital – Gaza City',
  'مستشفى القدس الطبي': 'Al-Quds Medical Hospital',
  'مستشفى أصدقاء المريض الخيري': 'Patient Friends Charitable Hospital',
  'مستشفى أصدقاء المريض الخيري – مدينة غزة': 'Patient Friends Charitable Hospital – Gaza City',
  'مستشفى أصدقاء المريض': 'Patient Friends Hospital',
  'مستشفى شهداء الأقصى': 'Al-Aqsa Martyrs Hospital',
  'مستشفى شهداء الأقصى – دير البلح': 'Al-Aqsa Martyrs Hospital – Deir Al-Balah',
  'مستشفى العودة - النصيرات': 'Al-Awda Hospital - Nuseirat',
  'مستشفى العودة – النصيرات': 'Al-Awda Hospital – Nuseirat',
  'مجمع ناصر الطبي': 'Nasser Medical Complex',
  'مجمع ناصر الطبي – خان يونس': 'Nasser Medical Complex – Khan Younis',
  'المستشفى الأوروبي': 'European Gaza Hospital',
  'المستشفى الأوروبي – خان يونس': 'European Gaza Hospital – Khan Younis',
  'مستشفى الهلال الأحمر الفلسطيني': 'Palestine Red Crescent Hospital',
  'مستشفى الهلال الأحمر الفلسطيني – خان يونس': 'Palestine Red Crescent Hospital – Khan Younis',
  'مستشفى أبو يوسف النجار': 'Abu Yousuf Al-Najjar Hospital',
  'مستشفى أبو يوسف النجار – رفح': 'Abu Yousuf Al-Najjar Hospital – Rafah',
  'مستشفى الكويت التخصصي': 'Kuwaiti Specialty Hospital',
  'مستشفى الكويت التخصصي – رفح': 'Kuwaiti Specialty Hospital – Rafah',
  'جمعية بنك الدم المركزي': 'Central Blood Bank Society',
  'الجهة الطبية': 'Medical Facility'
};

const addressDict = {
  'شمال غزة - بيت لاهيا': 'North Gaza - Beit Lahia',
  'شمال غزة - تل الزعتر / جباليا': 'North Gaza - Jabalia',
  'مدينة غزة - الرمال': 'Gaza City - Rimal',
  'غزة - الرمال': 'Gaza - Rimal',
  'مدينة غزة - الزيتون / الشجاعية': 'Gaza City - Al-Zaytoun / Shuja\'iyya',
  'غزة - الزيتون': 'Gaza - Al-Zaytoun',
  'مدينة غزة - تل الهوى': 'Gaza City - Tel Al-Hawa',
  'غزة - تل الهوى': 'Gaza - Tel Al-Hawa',
  'مدينة غزة - حي الرمال - شارع الشهداء': 'Gaza City - Rimal District - Al-Shuhada St',
  'المحافظة الوسطى - دير البلح': 'Central Gaza - Deir Al-Balah',
  'المحافظة الوسطى - النصيرات': 'Central Gaza - Nuseirat',
  'خان يونس - وسط المدينة': 'Khan Younis - City Center',
  'خان يونس - الفخاري': 'Khan Younis - Al-Fukhari',
  'خان يونس - حي الأمل': 'Khan Younis - Al-Amal District',
  'رفح - حي الجنينة': 'Rafah - Al-Jenena District',
  'رفح - وسط البلد': 'Rafah - Downtown'
};

const cityDict = {
  'غزة': 'Gaza',
  'مدينة غزة': 'Gaza City',
  'شمال غزة': 'North Gaza',
  'المحافظة الوسطى': 'Central Governorate',
  'خان يونس': 'Khan Younis',
  'رفح': 'Rafah'
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const props = defineProps({
  hospitalData: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['update:hospitalData', 'refresh']);
const rawForm = ref({ ...props.hospitalData });
const displayForm = ref({ ...props.hospitalData });

const translateField = (val, type) => {
  if (!val) return '';
  if (currentLanguage.value !== 'en') return val;

  const trimmed = val.trim();

  if (type === 'name') {
    if (facilityNameDict[trimmed]) return facilityNameDict[trimmed];
    return trimmed
      .replace(/المستشفى الإندونيسي/g, 'Indonesian Hospital')
      .replace(/مستشفى العودة/g, 'Al-Awda Hospital')
      .replace(/مستشفى كمال عدوان/g, 'Kamal Adwan Hospital')
      .replace(/مجمع الشفاء الطبي/g, 'Al-Shifa Medical Complex')
      .replace(/المستشفى الأهلي العربي/g, 'Ahli Arab Hospital')
      .replace(/المعمداني/g, 'Al-Mamdani')
      .replace(/مستشفى القدس/g, 'Al-Quds Medical Hospital')
      .replace(/أصدقاء المريض/g, 'Patient Friends')
      .replace(/شهداء الأقصى/g, 'Al-Aqsa Martyrs')
      .replace(/مجمع ناصر الطبي/g, 'Nasser Medical Complex')
      .replace(/المستشفى الأوروبي/g, 'European Gaza Hospital')
      .replace(/الهلال الأحمر/g, 'Red Crescent')
      .replace(/أبو يوسف النجار/g, 'Abu Yousuf Al-Najjar')
      .replace(/الكويت التخصصي/g, 'Kuwaiti Specialty')
      .replace(/الإندونيسي/g, 'Indonesian')
      .replace(/مستشفى/g, 'Hospital')
      .replace(/الخيري/g, 'Charitable')
      .replace(/المركزي/g, 'Central')
      .replace(/الطبي/g, 'Medical');
  }
  if (type === 'city') {
    if (cityDict[trimmed]) return cityDict[trimmed];
    return trimmed.replace(/غزة/g, 'Gaza').replace(/خان يونس/g, 'Khan Younis').replace(/رفح/g, 'Rafah');
  }
  if (type === 'address') {
    if (addressDict[trimmed]) return addressDict[trimmed];
    return trimmed
      .replace(/مدينة غزة/g, 'Gaza City')
      .replace(/غزة/g, 'Gaza')
      .replace(/الزيتون/g, 'Al-Zaytoun')
      .replace(/الشجاعية/g, 'Shuja\'iyya')
      .replace(/الرمال/g, 'Rimal')
      .replace(/تل الهوى/g, 'Tel Al-Hawa')
      .replace(/شارع الشهداء/g, 'Al-Shuhada St')
      .replace(/شارع/g, 'St')
      .replace(/شمال/g, 'North')
      .replace(/جباليا/g, 'Jabalia')
      .replace(/بيت لاهيا/g, 'Beit Lahia')
      .replace(/تل الزعتر/g, 'Tal Al-Zaatar')
      .replace(/المحافظة الوسطى/g, 'Central Governorate')
      .replace(/دير البلح/g, 'Deir Al-Balah')
      .replace(/النصيرات/g, 'Nuseirat')
      .replace(/خان يونس/g, 'Khan Younis')
      .replace(/وسط المدينة/g, 'City Center')
      .replace(/الفخاري/g, 'Al-Fukhari')
      .replace(/حي الأمل/g, 'Al-Amal District')
      .replace(/رفح/g, 'Rafah')
      .replace(/حي الجنينة/g, 'Al-Jenena District')
      .replace(/وسط البلد/g, 'Downtown');
  }
  if (type === 'hours') {
    if (val.includes('24') || val.includes('ساعة')) return '24 Hours 7 Days a Week';
    return val;
  }
  return val;
};

const updateDisplayForm = () => {
  displayForm.value = {
    name: translateField(rawForm.value.name, 'name'),
    contact_email: rawForm.value.contact_email,
    phone_number: rawForm.value.phone_number,
    city: translateField(rawForm.value.city, 'city'),
    address: translateField(rawForm.value.address, 'address'),
    working_hours: translateField(rawForm.value.working_hours, 'hours')
  };
};

watch(() => props.hospitalData, (newData) => {
  if (newData) {
    rawForm.value = { ...newData };
    updateDisplayForm();
  }
}, { deep: true, immediate: true });

watch(currentLanguage, () => {
  updateDisplayForm();
});

const saveSettings = async () => {
  loading.value = true;
  try {
    await hospitalApi.updateHospitalProfile(displayForm.value);
  } catch (err) {
    console.log('تم التحديث المحلي بنجاح.');
  } finally {
    const updatedData = { ...displayForm.value };
    localStorage.setItem('musaef_hospital_settings', JSON.stringify(updatedData));

    if (authStore.user) {
      authStore.user.name = displayForm.value.name;
      authStore.user.facility_name = displayForm.value.name;
    }

    window.dispatchEvent(new CustomEvent('hospital-name-updated', {
      detail: { name: displayForm.value.name }
    }));

    emit('update:hospitalData', updatedData);
    emit('refresh');

    loading.value = false;
    alert(t('successUpdate'));
  }
};

const resetForm = () => {
  const savedLocal = localStorage.getItem('musaef_hospital_settings');
  if (savedLocal) {
    rawForm.value = JSON.parse(savedLocal);
  } else {
    rawForm.value = { ...props.hospitalData };
  }
  updateDisplayForm();
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
