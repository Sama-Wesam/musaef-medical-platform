<template>
  <div
    class="profile-page bg-light-gray min-vh-100 pb-5 font-arial"
    :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'"
  >
    <!-- استدعاء مكوّن الهيدر أعلى الصفحة -->
    <DonorHeader />

    <main class="container-fluid px-2 px-md-4 pt-2">
      <!-- العنوان الرئيسي والوصف -->
      <div class="mb-3 mb-md-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <h3 class="fw-bold text-dark mb-1 fs-5 fs-md-3">{{ t('myAccountTitle') }}</h3>
        <p class="text-muted fs-8 mb-0">{{ t('myAccountSubtitle') }}</p>
      </div>

      <!-- مؤشر التحميل -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">{{ t('loading') }}</span>
        </div>
        <p class="text-muted mt-2 fs-8">{{ t('loading') }}</p>
      </div>

      <div v-else class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-start gap-3 gap-lg-4">
        <!-- القائمة الجانبية / التبويبات -->
        <div class="custom-sidebar-wrapper flex-shrink-0 w-100 w-lg-auto">
          <div class="card border-0 shadow-sm p-2 p-md-3 rounded-4 bg-white mb-2 mb-lg-3 overflow-x-auto tab-scroll-wrapper">
            <div class="nav flex-row flex-lg-column nav-pills gap-2 min-tabs-width" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <button
                class="nav-link border-0 rounded-3 py-2 py-md-2.5 px-3 fs-8 fw-bold d-flex align-items-center justify-content-start gap-2 text-nowrap flex-fill flex-lg-grow-0"
                :class="activeProfileTab === 'personal' ? 'active-tab-pink text-danger' : 'text-secondary bg-transparent'"
                @click="activeProfileTab = 'personal'"
              >
                <i class="bi bi-person fs-5"></i>
                <span>{{ t('personalDataTab') }}</span>
              </button>

              <button
                class="nav-link border-0 rounded-3 py-2 py-md-2.5 px-3 fs-8 fw-bold d-flex align-items-center justify-content-start gap-2 text-nowrap flex-fill flex-lg-grow-0"
                :class="activeProfileTab === 'health' ? 'active-tab-pink text-danger' : 'text-secondary bg-transparent'"
                @click="activeProfileTab = 'health'"
              >
                <i class="bi bi-heart-pulse text-danger fs-5"></i>
                <span>{{ t('healthDataTab') }}</span>
              </button>

              <button
                class="nav-link border-0 rounded-3 py-2 py-md-2.5 px-3 fs-8 fw-bold d-flex align-items-center justify-content-start gap-2 text-nowrap flex-fill flex-lg-grow-0"
                :class="activeProfileTab === 'settings' ? 'active-tab-pink text-danger' : 'text-secondary bg-transparent'"
                @click="activeProfileTab = 'settings'"
              >
                <i class="bi bi-gear fs-5"></i>
                <span>{{ t('settingsTab') }}</span>
              </button>
            </div>
          </div>
        </div>

        <!-- الجزء الرئيسي -->
        <div class="flex-grow-1 w-100 min-w-0">
          <PersonalInfoTab v-if="activeProfileTab === 'personal'" :profile="profileData" @update-profile="handleProfileUpdate" />
          <HealthDataTab v-else-if="activeProfileTab === 'health'" :health-info="profileData" @update-health-info="handleHealthUpdate" />
          <SettingsTab v-else-if="activeProfileTab === 'settings'" :settings="profileData.settings" />
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import donor from '@/api/donor';
import DonorHeader from '@/components/donor/DonorHeader.vue';

import PersonalInfoTab from '@/components/donor/profile/PersonalInfoTab.vue';
import HealthDataTab from '@/components/donor/profile/HealthDataTab.vue';
import SettingsTab from '@/components/donor/profile/SettingsTab.vue';
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();
const activeProfileTab = ref('personal');
const loading = ref(true);
const profileData = ref({});
const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: {
    myAccountTitle: 'حسابي',
    myAccountSubtitle: 'إدارة معلوماتك الشخصية وإعدادات الحساب الشخصية',
    loading: 'جاري تحميل ملفك الشخصي...',
    personalDataTab: 'البيانات الشخصية',
    healthDataTab: 'البيانات الصحية',
    settingsTab: 'الإعدادات'
  },
  en: {
    myAccountTitle: 'My Account',
    myAccountSubtitle: 'Manage your personal information and profile settings',
    loading: 'Loading your profile...',
    personalDataTab: 'Personal Information',
    healthDataTab: 'Health Data',
    settingsTab: 'Settings'
  }
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const loadProfile = async () => {
  loading.value = true;
  try {
    const res = await donor.getProfile();
    profileData.value = res.data?.data || res.data || {};
  } catch (error) {
    console.error('خطأ في جلب بيانات الملف الشخصي:', error);
  } finally {
    loading.value = false;
  }
};

const handleProfileUpdate = (updatedData) => {
  if (updatedData) {
    profileData.value = { ...profileData.value, ...updatedData };
  }
  loadProfile();
};

const handleHealthUpdate = (updatedData) => {
  if (updatedData) {
    profileData.value = { ...profileData.value, ...updatedData };
  }
  loadProfile();
};

onMounted(() => {
  loadProfile();
});
</script>

<style scoped>
.font-arial {
  font-family: Arial, Helvetica, sans-serif !important;
}

.bg-light-gray { background-color: #f8fafc; }

@media (min-width: 992px) {
  .custom-sidebar-wrapper {
    width: 260px !important;
  }
}

.tab-scroll-wrapper {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.tab-scroll-wrapper::-webkit-scrollbar { display: none; }

.min-tabs-width {
  min-width: 320px;
}

@media (min-width: 992px) {
  .min-tabs-width {
    min-width: 100%;
  }
}

.active-tab-pink {
  background-color: #fdecec !important;
  border: 1px solid #fca5a5 !important;
}

.fs-8 {
  font-size: 0.82rem;
}
</style>
