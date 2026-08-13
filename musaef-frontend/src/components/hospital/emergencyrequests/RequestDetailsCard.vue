<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 fs-9 fw-bold">
        {{ t('emergency.cards.priority') }}
      </span>
      <span class="text-muted fs-9">{{ request.created_at || t('emergency.cards.justNow') }}</span>
    </div>
    <h5 class="fw-bold text-dark mb-1 fs-6">{{ translateHospital(activeHospitalName) }}</h5>
    <p class="text-muted fs-8 mb-3">📍 {{ translateLocation(activeLocation) }}</p>

    <div class="row g-2 text-center bg-light p-2.5 rounded-3 fs-8 mb-2">
      <div class="col-6 border-end">
        <small class="text-muted d-block fs-9">{{ t('emergency.cards.requiredBloodType') }}</small>
        <strong class="text-danger fs-6" dir="ltr">{{ request.bloodType || request.blood_type }}</strong>
      </div>
      <div class="col-6">
        <small class="text-muted d-block fs-9">{{ t('emergency.cards.requiredUnits') }}</small>
        <strong class="text-dark fs-6">{{ request.units || request.units_required }} {{ t('emergency.cards.units') }}</strong>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useAuthStore } from "@/stores/authStore";

const props = defineProps({
  request: {
    type: Object,
    required: true
  }
});

const authStore = useAuthStore();
const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const hospitalAddressMap = {
  'المستشفى الإندونيسي': 'شمال غزة - بيت لاهيا',
  'المستشفى الإندونيسي – بيت لاهيا': 'شمال غزة - بيت لاهيا',
  'مستشفى كمال عدوان': 'شمال غزة - بيت لاهيا',
  'مستشفى كمال عدوان – بيت لاهيا': 'شمال غزة - بيت لاهيا',
  'مستشفى العودة - جباليا': 'شمال غزة - تل الزعتر / جباليا',
  'مستشفى العودة – شمال غزة / جباليا': 'شمال غزة - تل الزعتر / جباليا',
  'مجمع الشفاء الطبي': 'مدينة غزة - الرمال',
  'مجمع الشفاء الطبي – مدينة غزة': 'مدينة غزة - الرمال',
  'المستشفى الأهلي العربي (المعمداني)': 'مدينة غزة - الزيتون / الشجاعية',
  'المستشفى الأهلي العربي (المعمداني) – مدينة غزة': 'مدينة غزة - الزيتون / الشجاعية',
  'مستشفى القدس': 'مدينة غزة - تل الهوى',
  'مستشفى القدس – مدينة غزة': 'مدينة غزة - تل الهوى',
  'مستشفى أصدقاء المريض الخيري': 'مدينة غزة - حي الرمال - شارع الشهداء',
  'مستشفى أصدقاء المريض الخيري – مدينة غزة': 'مدينة غزة - حي الرمال - شارع الشهداء',
  'مستشفى شهداء الأقصى': 'المحافظة الوسطى - دير البلح',
  'مستشفى شهداء الأقصى – دير البلح': 'المحافظة الوسطى - دير البلح',
  'مستشفى العودة - النصيرات': 'المحافظة الوسطى - النصيرات',
  'مستشفى العودة – النصيرات': 'المحافظة الوسطى - النصيرات',
  'مجمع ناصر الطبي': 'خان يونس - وسط المدينة',
  'مجمع ناصر الطبي – خان يونس': 'خان يونس - وسط المدينة',
  'المستشفى الأوروبي': 'خان يونس - الفخاري',
  'المستشفى الأوروبي – خان يونس': 'خان يونس - الفخاري',
  'مستشفى الهلال الأحمر الفلسطيني': 'خان يونس - حي الأمل',
  'مستشفى الهلال الأحمر الفلسطيني – خان يونس': 'خان يونس - حي الأمل',
  'مستشفى أبو يوسف النجار': 'رفح - حي الجنينة',
  'مستشفى أبو يوسف النجار – رفح': 'رفح - حي الجنينة',
  'مستشفى الكويت التخصصي': 'رفح - وسط البلد',
  'مستشفى الكويت التخصصي – رفح': 'رفح - وسط البلد'
};

const activeHospitalName = computed(() => {
  if (authStore.user?.facility_name) return authStore.user.facility_name;
  if (authStore.user?.name) return authStore.user.name;

  const savedSettings = localStorage.getItem('musaef_hospital_settings');
  if (savedSettings) {
    try {
      const parsed = JSON.parse(savedSettings);
      if (parsed.name) return parsed.name;
    } catch (e) {
      console.error(e);
    }
  }
  return props.request?.hospital_name || "الجهة الطبية";
});

const activeLocation = computed(() => {
  const hospitalName = activeHospitalName.value;
  if (hospitalAddressMap[hospitalName]) {
    return hospitalAddressMap[hospitalName];
  }

  if (authStore.user?.address) return authStore.user.address;

  const savedSettings = localStorage.getItem('musaef_hospital_settings');
  if (savedSettings) {
    try {
      const parsed = JSON.parse(savedSettings);
      if (parsed.address) return parsed.address;
    } catch (e) {
      console.error(e);
    }
  }

  return props.request?.location || props.request?.hospital?.address || "مدينة غزة - الرمال";
});

const dictionary = {
  ar: {
    "emergency.cards.priority": "أولوية قصوى (Emergency Priority AI)",
    "emergency.cards.justNow": "منذ قليل",
    "emergency.cards.requiredBloodType": "الفصيلة المطلوبة",
    "emergency.cards.requiredUnits": "الوحدات المطلوبة",
    "emergency.cards.units": "وحدات"
  },
  en: {
    "emergency.cards.priority": "Emergency Priority AI",
    "emergency.cards.justNow": "Just now",
    "emergency.cards.requiredBloodType": "Required Blood Type",
    "emergency.cards.requiredUnits": "Required Units",
    "emergency.cards.units": "Units"
  }
};

const hospitalDict = {
  'المستشفى الإندونيسي': 'Indonesian Hospital',
  'مستشفى الإندونيسي': 'Indonesian Hospital',
  'المستشفى الإندونيسي – بيت لاهيا': 'Indonesian Hospital – Beit Lahia',
  'مستشفى كمال عدوان': 'Kamal Adwan Hospital',
  'مستشفى كمال عدوان – بيت لاهيا': 'Kamal Adwan Hospital – Beit Lahia',
  'مستشفى العودة - جباليا': 'Al-Awda Hospital - Jabalia',
  'مستشفى العودة – شمال غزة / جباليا': 'Al-Awda Hospital – Jabalia',
  'مستشفى العودة': 'Al-Awda Hospital',
  'مستشفى الشفاء الطبي': 'Al-Shifa Medical Complex',
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex',
  'مجمع الشفاء الطبي – مدينة غزة': 'Al-Shifa Medical Complex – Gaza City',
  'مستشفى القدس الطبي': 'Al-Quds Medical Hospital',
  'مستشفى القدس': 'Al-Quds Hospital',
  'مستشفى القدس – مدينة غزة': 'Al-Quds Hospital – Gaza City',
  'المستشفى الأهلي العربي (المعمداني)': 'Ahli Arab Hospital (Al-Mamdani)',
  'المستشفى الأهلي العربي (المعمداني) – مدينة غزة': 'Ahli Arab Hospital (Al-Mamdani) – Gaza City',
  'مستشفى الأهلي العربي': 'Ahli Arab Hospital',
  'مستشفى أصدقاء المريض الخيري': 'Patient Friends Charitable Hospital',
  'مستشفى أصدقاء المريض الخيري – مدينة غزة': 'Patient Friends Charitable Hospital – Gaza City',
  'مستشفى شهداء الأقصى': 'Al-Aqsa Martyrs Hospital',
  'مستشفى شهداء الأقصى – دير البلح': 'Al-Aqsa Martyrs Hospital – Deir Al-Balah',
  'مستشفى العودة - النصيرات': 'Al-Awda Hospital - Nuseirat',
  'مستشفى العودة – النصيرات': 'Al-Awda Hospital – Nuseirat',
  'مجمع ناصر الطبي': 'Nasser Medical Complex',
  'مجمع ناصر الطبي – خان يونس': 'Nasser Medical Complex – Khan Younis',
  'المستشفى الأوروبي': 'Gaza European Hospital',
  'المستشفى الأوروبي – خان يونس': 'Gaza European Hospital – Khan Younis',
  'مستشفى الهلال الأحمر الفلسطيني': 'Palestine Red Crescent Hospital',
  'مستشفى الهلال الأحمر الفلسطيني – خان يونس': 'Palestine Red Crescent Hospital – Khan Younis',
  'مستشفى أبو يوسف النجار': 'Abu Yousuf Al-Najjar Hospital',
  'مستشفى أبو يوسف النجار – رفح': 'Abu Yousuf Al-Najjar Hospital – Rafah',
  'مستشفى الكويت التخصصي': 'Kuwaiti Specialty Hospital',
  'مستشفى الكويت التخصصي – رفح': 'Kuwaiti Specialty Hospital – Rafah',
  'جمعية بنك الدم المركزي': 'Central Blood Bank Society',
  'الجهة الطبية': 'Medical Facility'
};

const locationDict = {
  'شمال غزة - بيت لاهيا': 'North Gaza - Beit Lahia',
  'شمال غزة - تل الزعتر / جباليا': 'North Gaza - Jabalia / Tal Al-Zaatar',
  'مدينة غزة - الرمال': 'Gaza City - Rimal',
  'مدينة غزة - الزيتون / الشجاعية': 'Gaza City - Al-Zaytoun / Shuja\'iyya',
  'مدينة غزة - تل الهوى': 'Gaza City - Tel Al-Hawa',
  'مدينة غزة - حي الرمال - شارع الشهداء': 'Gaza City - Rimal - Al-Shuhada St',
  'المحافظة الوسطى - دير البلح': 'Central Governorate - Deir Al-Balah',
  'المحافظة الوسطى - النصيرات': 'Central Governorate - Nuseirat',
  'خان يونس - وسط المدينة': 'Khan Younis - City Center',
  'خان يونس - الفخاري': 'Khan Younis - Al-Fukhari',
  'خان يونس - حي الأمل': 'Khan Younis - Al-Amal',
  'رفح - حي الجنينة': 'Rafah - Al-Jenena',
  'رفح - وسط البلد': 'Rafah - Downtown',
  'غزة - الرمال': 'Gaza - Rimal',
  'غزة - الزيتون': 'Gaza - Al-Zaytoun'
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;
const translateHospital = (h) => currentLanguage.value === 'en' ? (hospitalDict[h] || h) : h;
const translateLocation = (l) => currentLanguage.value === 'en' ? (locationDict[l] || l) : l;
</script>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
