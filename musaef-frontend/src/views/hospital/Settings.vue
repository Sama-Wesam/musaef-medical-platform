<template>
  <HospitalLayout>
    <div class="medical-settings-page container-fluid px-2 px-md-3" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">

      <!-- Header Section -->
      <div class="mb-3 mb-md-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <h4 class="fw-bold text-dark mb-1 fs-5 fs-md-4">{{ t('settingsTitle') }}</h4>
        <p class="text-muted fs-8 fs-md-7 mb-0">{{ t('settingsSubtitle') }}</p>
      </div>

      <!-- Navigation Tabs Bar -->
      <div class="tabs-container bg-white rounded-4 p-1 shadow-sm mb-4 border border-light overflow-x-auto">
        <div class="d-flex align-items-center justify-content-between text-center min-w-tabs">
          <button
            class="tab-item flex-fill py-2 px-3 fw-bold border-0 bg-transparent transition-all text-nowrap"
            :class="{ 'active-tab': activeTab === 'profile' }"
            @click="activeTab = 'profile'"
          >
            {{ t('tabProfile') }}
          </button>
          <button
            class="tab-item flex-fill py-2 px-3 fw-bold border-0 bg-transparent transition-all text-nowrap"
            :class="{ 'active-tab': activeTab === 'general' }"
            @click="activeTab = 'general'"
          >
            {{ t('tabGeneral') }}
          </button>
          <button
            class="tab-item flex-fill py-2 px-3 fw-bold border-0 bg-transparent transition-all text-nowrap"
            :class="{ 'active-tab': activeTab === 'security' }"
            @click="activeTab = 'security'"
          >
            {{ t('tabSecurity') }}
          </button>
          <button
            class="tab-item flex-fill py-2 px-3 fw-bold border-0 bg-transparent transition-all text-nowrap"
            :class="{ 'active-tab': activeTab === 'notifs' }"
            @click="activeTab = 'notifs'"
          >
            {{ t('tabNotifs') }}
          </button>
          <button
            class="tab-item flex-fill py-2 px-3 fw-bold border-0 bg-transparent transition-all text-nowrap"
            :class="{ 'active-tab': activeTab === 'verify' }"
            @click="activeTab = 'verify'"
          >
            {{ t('tabVerify') }}
          </button>
        </div>
      </div>

      <!-- Tab Content Components -->
      <ProfileTab v-if="activeTab === 'profile'" :hospitalData="hospitalData" />
      <GeneralSettingsTab v-if="activeTab === 'general'" v-model:hospitalData="hospitalData" @refresh="fetchHospitalProfile" />
      <SecurityTab v-if="activeTab === 'security'" />
      <NotificationsTab v-if="activeTab === 'notifs'" />
      <VerificationTab v-if="activeTab === 'verify'" />

    </div>
  </HospitalLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import HospitalLayout from '@/layouts/HospitalLayout.vue';
import hospitalApi from '@/api/hospital';

import ProfileTab from '@/components/hospital/settings/ProfileTab.vue';
import GeneralSettingsTab from '@/components/hospital/settings/GeneralSettingsTab.vue';
import SecurityTab from '@/components/hospital/settings/SecurityTab.vue';
import NotificationsTab from '@/components/hospital/settings/NotificationsTab.vue';
import VerificationTab from '@/components/hospital/settings/VerificationTab.vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    settingsTitle: 'إعدادات الجهة الطبية',
    settingsSubtitle: 'إدارة وتحديث معلومات وإعدادات الجهة الطبية',
    tabProfile: 'الملف التعريفي',
    tabGeneral: 'الإعدادات',
    tabSecurity: 'الأمان',
    tabNotifs: 'الإشعارات',
    tabVerify: 'التحقق والاعتماد'
  },
  en: {
    settingsTitle: 'Medical Facility Settings',
    settingsSubtitle: 'Manage and update medical facility information and settings',
    tabProfile: 'Profile',
    tabGeneral: 'Settings',
    tabSecurity: 'Security',
    tabNotifs: 'Notifications',
    tabVerify: 'Verification & Accreditation'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const activeTab = ref('profile');
const hospitalData = ref({
  name: 'مجمع الشفاء الطبي',
  contact_email: 'alawda@musaef.com',
  phone_number: '082824400',
  city: 'غزة',
  address: 'غزة - الرمال',
  latitude: 31.514,
  longitude: 34.448,
  working_hours: '24 ساعة 7 أيام في الأسبوع'
});

const fetchHospitalProfile = async () => {
  try {
    const res = await hospitalApi.getHospitalProfile();
    const data = res.data?.data || res.data;
    if (data) {
      hospitalData.value = {
        name: data.user?.name || data.name || hospitalData.value.name,
        contact_email: data.contact_email || data.user?.email || hospitalData.value.contact_email,
        phone_number: data.phone_number || data.phone || hospitalData.value.phone_number,
        city: data.city || hospitalData.value.city || 'غزة',
        address: data.address || hospitalData.value.address,
        latitude: parseFloat(data.latitude) || hospitalData.value.latitude || 31.514,
        longitude: parseFloat(data.longitude) || hospitalData.value.longitude || 34.448,
        working_hours: data.working_hours || hospitalData.value.working_hours || (currentLanguage.value === 'en' ? '24 Hours 7 Days a Week' : '24 ساعة 7 أيام في الأسبوع')
      };
    }
  } catch (err) {
    console.error('خطأ في جلب بيانات الإعدادات:', err);
  }
};

onMounted(() => {
  fetchHospitalProfile();
});
</script>

<style scoped>
.medical-settings-page { font-family: 'Cairo', sans-serif; padding-bottom: 24px; }
.tabs-container { background-color: #f8fafc; border-radius: 50px !important; }
.min-w-tabs { min-width: 500px; }
@media (min-width: 768px) { .min-w-tabs { min-width: 100%; } }
.tab-item { color: #64748b; border-radius: 50px; font-size: 0.825rem; cursor: pointer; transition: all 0.2s ease-in-out; }
.active-tab { background-color: #dc3545 !important; color: #ffffff !important; box-shadow: 0 2px 8px rgba(220, 53, 69, 0.25); }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
