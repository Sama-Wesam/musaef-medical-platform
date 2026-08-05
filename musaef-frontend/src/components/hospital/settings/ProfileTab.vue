<template>
  <div class="row g-3 g-lg-4 align-items-stretch" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <h6 class="fw-bold text-dark mb-4 fs-6">{{ t('infoTitle') }}</h6>

        <div class="d-flex flex-column gap-3 fs-7">
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">🏢</span>
            <span class="text-muted ms-1">{{ t('hospitalName') }}:</span>
            <span class="fw-bold text-dark">{{ translateFacilityName(hospitalData.name) }}</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">📍</span>
            <span class="text-muted ms-1">{{ t('address') }}:</span>
            <span class="text-dark">{{ translateAddress(hospitalData.address) }}</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">⏰</span>
            <span class="text-muted ms-1">{{ t('workingHours') }}:</span>
            <span class="text-dark">{{ translateWorkingHours(hospitalData.working_hours) }}</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">📞</span>
            <span class="text-muted ms-1">{{ t('phone') }}:</span>
            <span class="text-dark" dir="ltr">{{ hospitalData.phone_number || 'N/A' }}</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">✉️</span>
            <span class="text-muted ms-1">{{ t('email') }}:</span>
            <span class="text-dark text-break" dir="ltr">{{ hospitalData.contact_email }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden h-100 position-relative" style="min-height: 280px;">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d108928.261234!2d34.45!3d31.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14fd7f0800000000%3A0x100!2sGaza!5e0!3m2!1sen!2s!4v1600000000000!5m2!1sen!2s"
          width="100%"
          height="100%"
          style="border:0;"
          allowfullscreen
          loading="lazy">
        </iframe>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    infoTitle: 'معلومات الجهة الطبية',
    hospitalName: 'اسم المستشفى',
    address: 'العنوان',
    workingHours: 'ساعات العمل',
    phone: 'رقم الهاتف',
    email: 'البريد الإلكتروني'
  },
  en: {
    infoTitle: 'Medical Facility Information',
    hospitalName: 'Hospital Name',
    address: 'Address',
    workingHours: 'Working Hours',
    phone: 'Phone Number',
    email: 'Email'
  }
};

const facilityNameDict = {
  'جمعية بنك الدم المركزي': 'Central Blood Bank Society',
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex'
};

const addressDict = {
  'غزة - الرمال شارع الوحدة': 'Gaza - Rimal, Al-Wehda St',
  'غزة - الرمال': 'Gaza - Rimal'
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;
const translateFacilityName = (name) => currentLanguage.value === 'en' ? (facilityNameDict[name] || name) : name;
const translateAddress = (addr) => currentLanguage.value === 'en' ? (addressDict[addr] || addr) : (addr || 'غير محدد');
const translateWorkingHours = (hours) => {
  if (currentLanguage.value === 'en') {
    if (hours === '24 ساعة 7 أيام في الأسبوع') return '24 Hours 7 Days a Week';
  }
  return hours;
};

defineProps({
  hospitalData: {
    type: Object,
    required: true
  }
});
</script>

<style scoped>
.fs-7 { font-size: 0.85rem; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
