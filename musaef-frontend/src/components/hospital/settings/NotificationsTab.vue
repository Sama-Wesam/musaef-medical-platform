<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">

    <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
      <span class="fs-5">🔔</span>
      <h6 class="fw-bold text-dark mb-0 fs-6">{{ t('systemNotifsTitle') }}</h6>
    </div>

    <div class="d-flex flex-column gap-3 mb-4">
      <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-light-subtle flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
          <div class="icon-box bg-danger-subtle rounded-3 p-2 d-flex align-items-center justify-content-center flex-shrink-0">
            <span class="text-danger fs-6">🔔</span>
          </div>
          <div :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            <span class="fw-bold text-dark d-block fs-7 mb-1">{{ t('instantNotifs') }}</span>
            <small class="text-muted fs-8 d-block">{{ t('instantNotifsDesc') }}</small>
          </div>
        </div>
        <div class="form-check form-switch m-0 p-0 ms-auto ms-sm-0">
          <input class="form-check-input custom-red-switch m-0" type="checkbox" v-model="notifSettings.instantNotifs" id="instantNotifs" />
        </div>
      </div>

      <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-light-subtle flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
          <div class="icon-box bg-danger-subtle rounded-3 p-2 d-flex align-items-center justify-content-center flex-shrink-0">
            <span class="text-danger fs-6">✉️</span>
          </div>
          <div :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            <span class="fw-bold text-dark d-block fs-7 mb-1">{{ t('emailNotifs') }}</span>
            <small class="text-muted fs-8 d-block">{{ t('emailNotifsDesc') }}</small>
          </div>
        </div>
        <div class="form-check form-switch m-0 p-0 ms-auto ms-sm-0">
          <input class="form-check-input custom-red-switch m-0" type="checkbox" v-model="notifSettings.emailNotifs" id="emailNotifs" />
        </div>
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-start gap-2 mb-3 pt-2">
      <span class="fs-5">🧠</span>
      <h6 class="fw-bold text-dark mb-0 fs-6">{{ t('aiNotifsTitle') }}</h6>
    </div>

    <div class="d-flex flex-column gap-3 mb-4">
      <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-light-subtle flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
          <div class="icon-box bg-danger-subtle rounded-3 p-2 d-flex align-items-center justify-content-center flex-shrink-0">
            <span class="text-danger fs-6">🩸</span>
          </div>
          <div :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            <span class="fw-bold text-dark d-block fs-7 mb-1">{{ t('criticalStockAlerts') }}</span>
            <small class="text-muted fs-8 d-block">{{ t('criticalStockAlertsDesc') }}</small>
          </div>
        </div>
        <div class="form-check form-switch m-0 p-0 ms-auto ms-sm-0">
          <input class="form-check-input custom-red-switch m-0" type="checkbox" v-model="notifSettings.criticalStockAlerts" id="criticalStockAlerts" />
        </div>
      </div>

      <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-light-subtle flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
          <div class="icon-box bg-danger-subtle rounded-3 p-2 d-flex align-items-center justify-content-center flex-shrink-0">
            <span class="text-danger fs-6">📈</span>
          </div>
          <div :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            <span class="fw-bold text-dark d-block fs-7 mb-1">{{ t('aiPredictions') }}</span>
            <small class="text-muted fs-8 d-block">{{ t('aiPredictionsDesc') }}</small>
          </div>
        </div>
        <div class="form-check form-switch m-0 p-0 ms-auto ms-sm-0">
          <input class="form-check-input custom-red-switch m-0" type="checkbox" v-model="notifSettings.aiPredictions" id="aiPredictions" />
        </div>
      </div>
    </div>

    <!-- أزرار التحكم -->
    <div class="d-flex align-items-center justify-content-end gap-2 gap-md-3 mt-4 pt-2 border-top">
      <button type="button" class="btn btn-outline-secondary px-3 px-md-4 py-2 rounded-3 fs-7 fw-bold" @click="resetNotifSettings">{{ t('cancel') }}</button>
      <button type="button" class="btn btn-danger px-4 px-md-5 py-2 rounded-3 fs-7 fw-bold" @click="saveNotificationSettings">{{ t('saveSettings') }}</button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    systemNotifsTitle: 'إشعارات النظام العامة',
    instantNotifs: 'الإشعارات الفورية',
    instantNotifsDesc: 'استقبال التنبيهات الفورية على المتصفح عند استجابة متبرع جديد لنداء طارئ',
    emailNotifs: 'إشعارات البريد الإلكتروني',
    emailNotifsDesc: 'تلقي تقارير أسبوعية وإشعارات الحساب الرسمية عبر البريد',
    aiNotifsTitle: 'تنبيهات المخزون الذكية والذكاء الاصطناعي (Blood Demand Forecast AI)',
    criticalStockAlerts: 'تنبيهات نقص المخزون الحرج',
    criticalStockAlertsDesc: 'إرسال إشعارات فورية عند انخفاض أية فصيلة دم عن الحد الأدنى في المستشفى',
    aiPredictions: 'توقعات الذكاء الاصطناعي للطلب المستقبلي',
    aiPredictionsDesc: 'تلقي توصيات استباقية من نموذج BloodDemandForecast لإطلاق حملات التبرع قبل النقص المتوقع خلال 72 ساعة',
    cancel: 'إلغاء',
    saveSettings: 'حفظ الإعدادات',
    saveSuccess: 'تم حفظ إعدادات الإشعارات والتنبيهات الذكية بنجاح',
    resetSuccess: 'تم استعادة الإعدادات الافتراضية بنجاح'
  },
  en: {
    systemNotifsTitle: 'General System Notifications',
    instantNotifs: 'Instant Push Notifications',
    instantNotifsDesc: 'Receive instant browser alerts when a donor responds to an emergency call',
    emailNotifs: 'Email Notifications',
    emailNotifsDesc: 'Receive weekly reports and official account notifications via email',
    aiNotifsTitle: 'Smart Inventory & AI Alerts (Blood Demand Forecast AI)',
    criticalStockAlerts: 'Critical Low Stock Alerts',
    criticalStockAlertsDesc: 'Send instant notifications when any blood type drops below the minimum limit',
    aiPredictions: 'AI Demand Forecast Predictions',
    aiPredictionsDesc: 'Receive proactive recommendations from BloodDemandForecast AI before expected 72h shortage',
    cancel: 'Cancel',
    saveSettings: 'Save Settings',
    saveSuccess: 'Notification and smart alert settings saved successfully',
    resetSuccess: 'Default settings restored successfully'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const notifSettings = ref({
  instantNotifs: true,
  emailNotifs: true,
  criticalStockAlerts: true,
  aiPredictions: true
});

const saveNotificationSettings = () => {
  alert(t('saveSuccess'));
};

const resetNotifSettings = () => {
  notifSettings.value = {
    instantNotifs: true,
    emailNotifs: true,
    criticalStockAlerts: true,
    aiPredictions: true
  };
  alert(t('resetSuccess'));
};
</script>

<style scoped>
.fs-7 { font-size: 0.85rem; }
.fs-8 { font-size: 0.75rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-light-subtle { background-color: #f8fafc; }
.icon-box { width: 38px; height: 38px; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }

.custom-red-switch {
  width: 2.8em !important;
  height: 1.5em !important;
  cursor: pointer;
  background-color: #cbd5e1;
  border-color: transparent;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
}

.custom-red-switch:checked {
  background-color: #dc3545 !important;
  border-color: #dc3545 !important;
}
</style>
