<template>
  <AdminLayout>
    <div
      class="advanced-settings-view container-fluid px-2 px-md-3"
      :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'"
    >

      <!-- 1. التبويبات العلوية الرئيسية -->
      <div class="main-tabs-header border-bottom mb-4 overflow-x-auto tabs-scroll-container">
        <div class="d-flex align-items-center justify-content-start justify-content-md-center gap-3 gap-md-5 min-tabs-width">

          <!-- 1. سجلات النظام -->
          <button
            class="tab-item btn border-0 py-3 text-muted fs-8 fw-semibold text-nowrap"
            :class="{ 'active-tab': settingsStore.activeTab === 'logs' }"
            @click="settingsStore.activeTab = 'logs'"
          >
            {{ t('systemLogs') }}
          </button>

          <!-- 2. الذكاء الاصطناعي -->
          <button
            class="tab-item btn border-0 py-3 text-muted fs-8 fw-semibold text-nowrap"
            :class="{ 'active-tab': settingsStore.activeTab === 'ai' }"
            @click="settingsStore.activeTab = 'ai'"
          >
            {{ t('ai') }}
          </button>

          <!-- 3. البريد الإلكتروني -->
          <button
            class="tab-item btn border-0 py-3 text-muted fs-8 fw-semibold text-nowrap"
            :class="{ 'active-tab': settingsStore.activeTab === 'email' }"
            @click="settingsStore.activeTab = 'email'"
          >
            {{ t('email') }}
          </button>

          <!-- 4. الإعدادات العامة -->
          <button
            class="tab-item btn border-0 py-3 text-muted fs-8 fw-semibold text-nowrap"
            :class="{ 'active-tab': settingsStore.activeTab === 'general' }"
            @click="settingsStore.activeTab = 'general'"
          >
            {{ t('generalSettings') }}
          </button>

        </div>
      </div>

      <!-- 2. مكونات أقسام الإعدادات المتقدمة -->
      <GeneralSettingsTab
        v-if="settingsStore.activeTab === 'general'"
        :generalSettings="settingsStore.generalSettings"
      />

      <SystemLogsTab
        v-else-if="settingsStore.activeTab === 'logs'"
        :loginLogs="settingsStore.loginLogs"
        :activityLogs="settingsStore.activityLogs"
        :settings="settingsStore.quickSettings"
      />

      <AiSettingsTab
        v-else-if="settingsStore.activeTab === 'ai'"
        :aiSettings="settingsStore.aiSettings"
        :aiMetrics="settingsStore.aiMetrics"
      />

      <EmailSettingsTab
        v-else-if="settingsStore.activeTab === 'email'"
        :smtpSettings="settingsStore.smtpSettings"
        :emailSettings="settingsStore.emailSettings"
      />

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { useSettingsStore } from '@/stores/settingsStore';

import GeneralSettingsTab from '@/components/admin/advancedsettings/GeneralSettingsTab.vue';
import SystemLogsTab from '@/components/admin/advancedsettings/SystemLogsTab.vue';
import AiSettingsTab from '@/components/admin/advancedsettings/AiSettingsTab.vue';
import EmailSettingsTab from '@/components/admin/advancedsettings/EmailSettingsTab.vue';

const settingsStore = useSettingsStore();

const activeLanguage = ref(localStorage.getItem('musaef_lang') || 'ar');

const syncLanguage = () => {
  activeLanguage.value = localStorage.getItem('musaef_lang') || 'ar';
};

const currentLanguage = computed(() => activeLanguage.value);

const dictionary = {
  ar: {
    systemLogs: 'سجلات النظام',
    ai: 'الذكاء الاصطناعي',
    email: 'البريد الإلكتروني',
    generalSettings: 'الإعدادات العامة'
  },
  en: {
    systemLogs: 'System Logs',
    ai: 'AI Settings',
    email: 'Email Settings',
    generalSettings: 'General Settings'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

onMounted(() => {
  settingsStore.activeTab = 'logs';
  settingsStore.startPolling(5000);

  window.addEventListener('storage', syncLanguage);
  window.addEventListener('musaef_lang_changed', syncLanguage);
});

onUnmounted(() => {
  settingsStore.stopPolling();
  window.removeEventListener('storage', syncLanguage);
  window.removeEventListener('musaef_lang_changed', syncLanguage);
});
</script>

<style scoped>
.advanced-settings-view {
  font-family: 'Cairo', sans-serif;
  padding-bottom: 24px;
}

.fs-8 { font-size: 0.82rem; }

.tabs-scroll-container {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.tabs-scroll-container::-webkit-scrollbar {
  display: none;
}

.min-tabs-width {
  min-width: 420px;
}

@media (min-width: 768px) {
  .min-tabs-width {
    min-width: 100%;
  }
}

.tab-item {
  position: relative;
  transition: all 0.2s ease;
}

.tab-item.active-tab {
  color: #dc2626 !important;
  font-weight: 700 !important;
}

.tab-item.active-tab::after {
  content: '';
  position: absolute;
  bottom: -1px;
  width: 100%;
  height: 3px;
  background-color: #dc2626;
  border-radius: 3px 3px 0 0;
}

.dir-rtl .tab-item.active-tab::after { right: 0; }
.dir-ltr .tab-item.active-tab::after { left: 0; }

.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
