<template>
  <div :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="row g-3 g-lg-4">

      <!-- العمود الأيمن / الأيسر: الهوية والمعلومات الأساسية -->
      <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100">
          <h5 class="fw-bold text-dark mb-4 fs-6">{{ t('identityTitle') }}</h5>

          <div class="d-flex flex-column gap-3">

            <!-- 1. اسم المنصة -->
            <div class="d-flex align-items-stretch align-items-sm-center justify-content-between gap-2 gap-sm-3 flex-column flex-sm-row">
              <label class="fw-bold text-dark fs-8 text-nowrap general-label-width">{{ t('platformName') }}</label>
              <div class="position-relative flex-grow-1 w-100">
                <input
                  type="text"
                  class="form-control form-control-general text-muted rounded-3"
                  :class="currentLanguage === 'ar' ? 'pe-3 ps-5 text-end' : 'ps-3 pe-5 text-start'"
                  v-model="generalSettings.platformName"
                />
                <span class="position-absolute top-50 translate-middle-y text-muted" :class="currentLanguage === 'ar' ? 'start-0 ms-3' : 'end-0 me-3'">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                    <line x1="9" y1="6" x2="9" y2="6.01"></line>
                    <line x1="15" y1="6" x2="15" y2="6.01"></line>
                    <line x1="9" y1="10" x2="9" y2="10.01"></line>
                    <line x1="15" y1="10" x2="15" y2="10.01"></line>
                    <line x1="9" y1="14" x2="9" y2="14.01"></line>
                    <line x1="15" y1="14" x2="15" y2="14.01"></line>
                    <line x1="9" y1="18" x2="9" y2="18.01"></line>
                    <line x1="15" y1="18" x2="15" y2="18.01"></line>
                  </svg>
                </span>
              </div>
            </div>

            <!-- 2. رابط الموقع الرسمي (URL) -->
            <div class="d-flex align-items-stretch align-items-sm-center justify-content-between gap-2 gap-sm-3 flex-column flex-sm-row">
              <label class="fw-bold text-dark fs-8 text-nowrap general-label-width">{{ t('websiteUrl') }}</label>
              <div class="position-relative flex-grow-1 w-100">
                <input
                  type="text"
                  class="form-control form-control-general text-muted rounded-3 dir-ltr"
                  :class="currentLanguage === 'ar' ? 'pe-3 ps-5 text-end' : 'ps-3 pe-5 text-start'"
                  v-model="generalSettings.websiteUrl"
                />
                <span class="position-absolute top-50 translate-middle-y text-muted" :class="currentLanguage === 'ar' ? 'start-0 ms-3' : 'end-0 me-3'">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10z"></path>
                  </svg>
                </span>
              </div>
            </div>

            <!-- 3. شعار المنصة (Logo) -->
            <div class="d-flex align-items-stretch align-items-sm-center justify-content-between gap-2 gap-sm-3 flex-column flex-sm-row">
              <label class="fw-bold text-dark fs-8 text-nowrap general-label-width">{{ t('logoLabel') }}</label>
              <div class="upload-box flex-grow-1 d-flex align-items-center justify-content-between p-1 px-2 border rounded-3 bg-white w-100">
                <div class="d-flex align-items-center gap-1 gap-sm-2">
                  <button class="btn btn-outline-danger btn-sm rounded-2 px-2 px-sm-3 py-1 fs-9 d-flex align-items-center gap-1" @click="deleteLogo">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3 6 5 6 21 6"></polyline>
                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>{{ t('deleteBtn') }}</span>
                  </button>
                  <button class="btn btn-light border btn-sm text-muted rounded-2 px-2 px-sm-3 py-1 fs-9 d-flex align-items-center gap-1" @click="changeLogo">
                    <span>{{ t('changeLogoBtn') }}</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                      <polyline points="17 8 12 3 7 8"></polyline>
                      <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                  </button>
                </div>
                <div class="logo-preview-box px-2">
                  <img :src="getImageUrl('logo.png')" alt="Musaef Logo" height="28" />
                </div>
              </div>
            </div>

            <!-- 4. أيقونة المتصفح (Favicon) -->
            <div class="d-flex align-items-stretch align-items-sm-center justify-content-between gap-2 gap-sm-3 flex-column flex-sm-row">
              <label class="fw-bold text-dark fs-8 text-nowrap general-label-width">{{ t('faviconLabel') }}</label>
              <div class="upload-box flex-grow-1 d-flex align-items-center justify-content-between p-1 px-2 border rounded-3 bg-white w-100">
                <div class="d-flex align-items-center gap-1 gap-sm-2">
                  <button class="btn btn-outline-danger btn-sm rounded-2 px-2 px-sm-3 py-1 fs-9 d-flex align-items-center gap-1" @click="deleteFavicon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3 6 5 6 21 6"></polyline>
                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>{{ t('deleteBtn') }}</span>
                  </button>
                  <button class="btn btn-light border btn-sm text-muted rounded-2 px-2 px-sm-3 py-1 fs-9 d-flex align-items-center gap-1" @click="changeFavicon">
                    <span>{{ t('changeFaviconBtn') }}</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                      <polyline points="17 8 12 3 7 8"></polyline>
                      <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                  </button>
                </div>
                <div class="favicon-preview-box px-2">
                  <img :src="getImageUrl('auth-logo.png')" alt="Favicon" height="28" />
                </div>
              </div>
            </div>

            <!-- 5. اللغة الافتراضية للنظام -->
            <div class="d-flex align-items-stretch align-items-sm-center justify-content-between gap-2 gap-sm-3 flex-column flex-sm-row">
              <label class="fw-bold text-dark fs-8 text-nowrap general-label-width">{{ t('defaultLang') }}</label>
              <div class="position-relative flex-grow-1 w-100">
                <select
                  class="form-select form-control-general text-muted rounded-3 fs-8 fw-semibold"
                  :class="currentLanguage === 'ar' ? 'pe-3 ps-5 text-end' : 'ps-3 pe-5 text-start'"
                  v-model="generalSettings.defaultLanguage"
                >
                  <option value="ar">{{ t('langArOption') }}</option>
                  <option value="en">English</option>
                </select>
                <span class="position-absolute top-50 translate-middle-y text-muted" :class="currentLanguage === 'ar' ? 'start-0 ms-3' : 'end-0 me-3'">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10z"></path>
                  </svg>
                </span>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- العمود الأيسر: إعدادات التشغيل والصيانة + الإعدادات السريعة للنظام -->
      <div class="col-12 col-lg-5">
        <div class="d-flex flex-column gap-3 gap-md-4">

          <!-- كارت إعدادات التشغيل والصيانة -->
          <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white">
            <div class="d-flex align-items-center gap-2 mb-3">
              <div class="section-icon-box bg-purple-subtle text-purple rounded-3 p-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
              </div>
              <h6 class="fw-bold text-dark mb-0 fs-6">{{ t('opsTitle') }}</h6>
            </div>

            <div class="mb-2">
              <label class="fw-bold text-dark fs-8 d-block mb-2">{{ t('timezoneLabel') }}</label>
              <div class="position-relative">
                <input
                  type="text"
                  class="form-control form-control-general text-dark fw-bold rounded-3"
                  :class="currentLanguage === 'ar' ? 'pe-3 ps-5 text-end' : 'ps-3 pe-5 text-start'"
                  :value="translatedTimezone"
                  @input="generalSettings.timezone = $event.target.value"
                />
                <span class="position-absolute top-50 translate-middle-y text-muted" :class="currentLanguage === 'ar' ? 'start-0 ms-3' : 'end-0 me-3'">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10z"></path>
                  </svg>
                </span>
              </div>
            </div>
          </div>

          <!-- كارت الإعدادات السريعة للنظام -->
          <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white">
            <div class="d-flex align-items-center gap-2 mb-4">
              <div class="section-icon-box bg-success-subtle text-success rounded-3 p-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
              </div>
              <h6 class="fw-bold text-dark mb-0 fs-6">{{ t('quickSettingsTitle') }}</h6>
            </div>

            <div class="d-flex flex-column gap-3">

              <!-- 1. وضع الصيانة -->
              <div class="d-flex align-items-center justify-content-between p-2 border-bottom flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
                  <div class="quick-icon-box bg-success-subtle text-success rounded-3 p-2 flex-shrink-0">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('maintTitle') }}</span>
                    <small class="text-muted fs-9 d-block text-truncate">{{ t('maintDesc') }}</small>
                  </div>
                </div>
                <div class="form-check form-switch m-0" :class="currentLanguage === 'ar' ? 'ms-auto ms-sm-0' : 'me-auto me-sm-0'">
                  <input class="form-check-input custom-switch" type="checkbox" v-model="generalSettings.maintenanceMode" />
                </div>
              </div>

              <!-- 2. التسجيل الذاتي -->
              <div class="d-flex align-items-center justify-content-between p-2 border-bottom flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
                  <div class="quick-icon-box bg-purple-subtle text-purple rounded-3 p-2 flex-shrink-0">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                      <circle cx="8.5" cy="7" r="4"></circle>
                      <line x1="20" y1="8" x2="20" y2="14"></line>
                      <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('selfRegTitle') }}</span>
                    <small class="text-muted fs-9 d-block text-truncate">{{ t('selfRegDesc') }}</small>
                  </div>
                </div>
                <div class="form-check form-switch m-0" :class="currentLanguage === 'ar' ? 'ms-auto ms-sm-0' : 'me-auto me-sm-0'">
                  <input class="form-check-input custom-switch" type="checkbox" v-model="generalSettings.selfRegistration" />
                </div>
              </div>

              <!-- 3. التحقق بخطوتين (2FA) -->
              <div class="d-flex align-items-center justify-content-between p-2 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
                  <div class="quick-icon-box bg-primary-subtle text-primary rounded-3 p-2 flex-shrink-0">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                      <polyline points="9 12 11 14 15 10"></polyline>
                    </svg>
                  </div>
                  <div class="min-w-0">
                    <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('2faTitle') }}</span>
                    <small class="text-muted fs-9 d-block text-truncate">{{ t('2faDesc') }}</small>
                  </div>
                </div>
                <div class="form-check form-switch m-0" :class="currentLanguage === 'ar' ? 'ms-auto ms-sm-0' : 'me-auto me-sm-0'">
                  <input class="form-check-input custom-switch" type="checkbox" v-model="generalSettings.twoFactorAuth" />
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  generalSettings: Object
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    identityTitle: 'الهوية والمعلومات الأساسية',
    platformName: 'اسم المنصة',
    websiteUrl: 'رابط الموقع الرسمي (URL)',
    logoLabel: 'شعار المنصة (Logo)',
    deleteBtn: 'حذف',
    changeLogoBtn: 'تغيير الشعار',
    faviconLabel: 'أيقونة المتصفح (Favicon)',
    changeFaviconBtn: 'تغيير الأيقونة',
    defaultLang: 'اللغة الافتراضية للنظام',
    langArOption: 'العربية',
    opsTitle: 'إعدادات التشغيل والصيانة',
    timezoneLabel: 'المنطقة الزمنية الافتراضية',
    quickSettingsTitle: 'الإعدادات السريعة للنظام',
    maintTitle: 'وضع الصيانة',
    maintDesc: 'تفعيل وضع الصيانة وإظهار شاشة مغلقة للمستخدمين عند تحديث الكود',
    selfRegTitle: 'التسجيل الذاتي',
    selfRegDesc: 'السماح للمستشفيات ومتبرعين الجدد بإنشاء حسابات مباشرة دون مراجعة',
    '2faTitle': 'التحقق بخطوتين (2FA)',
    '2faDesc': 'فرص المصادقة الثنائية لجميع حسابات مدراء النظام'
  },
  en: {
    identityTitle: 'Identity & Basic Information',
    platformName: 'Platform Name',
    websiteUrl: 'Official Website URL',
    logoLabel: 'Platform Logo',
    deleteBtn: 'Delete',
    changeLogoBtn: 'Change Logo',
    faviconLabel: 'Favicon',
    changeFaviconBtn: 'Change Favicon',
    defaultLang: 'System Default Language',
    langArOption: 'Arabic',
    opsTitle: 'Operations & Maintenance Settings',
    timezoneLabel: 'Default Timezone',
    quickSettingsTitle: 'System Quick Settings',
    maintTitle: 'Maintenance Mode',
    maintDesc: 'Enable maintenance mode and display lock screen during system updates',
    selfRegTitle: 'Self Registration',
    selfRegDesc: 'Allow hospitals and new donors to register accounts directly without review',
    '2faTitle': 'Two-Factor Authentication (2FA)',
    '2faDesc': 'Enforce two-factor authentication for all system administrator accounts'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const translatedTimezone = computed(() => {
  const val = props.generalSettings?.timezone || 'غزة - دير البلح';
  if (currentLanguage.value === 'en') {
    if (val === 'غزة - دير البلح') return 'Gaza - Deir Al-Balah';
  } else {
    if (val === 'Gaza - Deir Al-Balah') return 'غزة - دير البلح';
  }
  return val;
});

const getImageUrl = (fileName) => {
  return new URL(`../../../assets/images/${fileName}`, import.meta.url).href;
};

const deleteLogo = () => alert(currentLanguage.value === 'ar' ? 'تم حذف الشعار بنجاح' : 'Logo deleted successfully');
const changeLogo = () => alert(currentLanguage.value === 'ar' ? 'اختر الشعار الجديد للرفع' : 'Select new logo to upload');
const deleteFavicon = () => alert(currentLanguage.value === 'ar' ? 'تم حذف الأيقونة بنجاح' : 'Favicon deleted successfully');
const changeFavicon = () => alert(currentLanguage.value === 'ar' ? 'اختر الأيقونة الجديدة للرفع' : 'Select new favicon to upload');
</script>

<style scoped>
.fs-6 { font-size: 1.05rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }

.bg-purple-subtle { background-color: #f3e8ff !important; }
.bg-primary-subtle { background-color: #dbeafe !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }

.text-purple { color: #9333ea !important; }

@media (min-width: 576px) {
  .general-label-width { min-width: 150px; }
}

.form-control-general {
  background-color: #ffffff;
  border: 1px solid #e2e8f0;
  height: 44px;
  font-size: 0.85rem;
  box-shadow: none !important;
  transition: border-color 0.2s ease;
}

.form-control-general:focus { border-color: #cbd5e1; }

.upload-box { border: 1px solid #e2e8f0 !important; min-height: 44px; }

.section-icon-box, .quick-icon-box {
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.custom-switch {
  width: 2.6em !important;
  height: 1.3em !important;
  cursor: pointer;
}

.custom-switch:checked {
  background-color: #16a34a !important;
  border-color: #16a34a !important;
}
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
