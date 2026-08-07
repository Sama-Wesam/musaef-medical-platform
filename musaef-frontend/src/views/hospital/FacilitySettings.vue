<template>
  <div class="facility-settings-page" dir="rtl">
    <!-- عنوان الصفحة -->
    <section class="page-heading">
      <h1>إعدادات الجهة الطبية</h1>

      <p>إدارة وتحديث معلومات وإعدادات الجهة الطبية</p>
    </section>

    <!-- التبويبات -->
    <FacilitySettingsTabs :model-value="activeTab" :tabs="tabs" @update:model-value="changeTab" />

    <!-- الملف التعريفي -->
    <div v-if="activeTab === 'profile'" class="profile-layout">
      <FacilityProfileInfo :facility="facility" />

      <FacilityMapCard />
    </div>

    <!-- الإعدادات -->
    <BasicInformationForm
      v-else-if="activeTab === 'settings'"
      v-model="settingsForm"
      :loading="loading"
      :errors="errors"
      @submit="saveSettings"
      @show-history="showHistory"
    />

    <!-- الأمان -->
    <SecuritySettings v-else-if="activeTab === 'security'" />

    <!-- الإشعارات -->
    <NotificationSettings v-else-if="activeTab === 'notifications'" />

    <!-- التحقق والاعتماد -->
   <VerificationSettings
  v-else-if="activeTab === 'verification'"
/>
  </div>
</template>

<script setup>
import BasicInformationForm from '@/components/hospital/BasicInformationForm.vue'
import FacilityMapCard from '@/components/hospital/FacilityMapCard.vue'
import FacilityProfileInfo from '@/components/hospital/FacilityProfileInfo.vue'
import FacilitySettingsTabs from '@/components/hospital/FacilitySettingsTabs.vue'
import NotificationSettings from '@/components/hospital/NotificationSettings.vue'
import SecuritySettings from '@/components/hospital/SecuritySettings.vue'
import VerificationSettings from '@/components/hospital/VerificationSettings.vue'

import { useFacilitySettings } from '@/composables/useFacilitySettings'

const {
  activeTab,
  facility,
  loading,
  tabs,
  settingsForm,
  errors,
  changeTab,
  saveSettings,
  showHistory,
} = useFacilitySettings()
</script>

<style scoped>
.facility-settings-page {
  width: 100%;
  min-height: 100vh;
  padding: 25px;
  background-color: #f8f9fb;
}

.page-heading {
  margin-bottom: 22px;
}

.page-heading h1 {
  margin: 0 0 8px;
  color: #111827;
  font-size: 25px;
  font-weight: 800;
}

.page-heading p {
  margin: 0;
  color: #9ca3af;
  font-size: 14px;
}

.profile-layout {
  display: grid;
  grid-template-columns: minmax(280px, 0.7fr) minmax(0, 1.3fr);
  gap: 22px;
  align-items: start;
}

.placeholder-card {
  min-height: 350px;
  padding: 45px 25px;

  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  border: 1px solid #eceef2;
  border-radius: 12px;

  background-color: #ffffff;
  text-align: center;
}

.placeholder-card i {
  margin-bottom: 15px;
  color: #ef4444;
  font-size: 42px;
}

.placeholder-card h2 {
  margin: 0 0 10px;
  color: #111827;
  font-size: 21px;
  font-weight: 800;
}

.placeholder-card p {
  margin: 0;
  color: #9ca3af;
  font-size: 14px;
}

@media (max-width: 900px) {
  .profile-layout {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 600px) {
  .facility-settings-page {
    padding: 15px;
  }

  .page-heading h1 {
    font-size: 21px;
  }
}
</style>
