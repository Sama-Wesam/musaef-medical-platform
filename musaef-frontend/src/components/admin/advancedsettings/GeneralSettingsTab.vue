<template>
  <div class="general-section dir-rtl">
    <div class="row g-3 g-lg-4">

      <!-- العمود الأيمن: الهوية والمعلومات الأساسية -->
      <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end h-100">
          <h5 class="fw-bold text-dark mb-4 fs-6">الهوية والمعلومات الأساسية</h5>

          <div class="d-flex flex-column gap-3">

            <!-- 1. اسم المنصة -->
            <div class="d-flex align-items-stretch align-items-sm-center justify-content-between gap-2 gap-sm-3 flex-column flex-sm-row">
              <label class="fw-bold text-dark fs-8 text-nowrap general-label-width">اسم المنصة</label>
              <div class="position-relative flex-grow-1 w-100">
                <input
                  type="text"
                  class="form-control form-control-general text-muted text-end pe-3 ps-5 rounded-3"
                  v-model="generalSettings.platformName"
                />
                <span class="position-absolute start-0 top-50 translate-middle-y ms-3 text-muted">
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
              <label class="fw-bold text-dark fs-8 text-nowrap general-label-width">رابط الموقع الرسمي (URL)</label>
              <div class="position-relative flex-grow-1 w-100">
                <input
                  type="text"
                  class="form-control form-control-general text-muted text-end pe-3 ps-5 rounded-3 dir-ltr"
                  v-model="generalSettings.websiteUrl"
                />
                <span class="position-absolute start-0 top-50 translate-middle-y ms-3 text-muted">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                  </svg>
                </span>
              </div>
            </div>

            <!-- 3. شعار المنصة (Logo) -->
            <div class="d-flex align-items-stretch align-items-sm-center justify-content-between gap-2 gap-sm-3 flex-column flex-sm-row">
              <label class="fw-bold text-dark fs-8 text-nowrap general-label-width">شعار المنصة (Logo)</label>
              <div class="upload-box flex-grow-1 d-flex align-items-center justify-content-between p-1 px-2 border rounded-3 bg-white w-100">
                <div class="d-flex align-items-center gap-1 gap-sm-2">
                  <button class="btn btn-outline-danger btn-sm rounded-2 px-2 px-sm-3 py-1 fs-9 d-flex align-items-center gap-1" @click="deleteLogo">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3 6 5 6 21 6"></polyline>
                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>حذف</span>
                  </button>
                  <button class="btn btn-light border btn-sm text-muted rounded-2 px-2 px-sm-3 py-1 fs-9 d-flex align-items-center gap-1" @click="changeLogo">
                    <span>تغيير الشعار</span>
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
              <label class="fw-bold text-dark fs-8 text-nowrap general-label-width">أيقونة المتصفح (Favicon)</label>
              <div class="upload-box flex-grow-1 d-flex align-items-center justify-content-between p-1 px-2 border rounded-3 bg-white w-100">
                <div class="d-flex align-items-center gap-1 gap-sm-2">
                  <button class="btn btn-outline-danger btn-sm rounded-2 px-2 px-sm-3 py-1 fs-9 d-flex align-items-center gap-1" @click="deleteFavicon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3 6 5 6 21 6"></polyline>
                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>حذف</span>
                  </button>
                  <button class="btn btn-light border btn-sm text-muted rounded-2 px-2 px-sm-3 py-1 fs-9 d-flex align-items-center gap-1" @click="changeFavicon">
                    <span>تغيير الأيقونة</span>
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
              <label class="fw-bold text-dark fs-8 text-nowrap general-label-width">اللغة الافتراضية للنظام</label>
              <div class="position-relative flex-grow-1 w-100">
                <select class="form-select form-control-general text-end text-muted pe-3 ps-5 rounded-3 fs-8 fw-semibold" v-model="generalSettings.defaultLanguage">
                  <option value="ar">العربية</option>
                  <option value="en">English</option>
                </select>
                <span class="position-absolute start-0 top-50 translate-middle-y ms-3 text-muted">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
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
          <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end">
            <div class="d-flex align-items-center gap-2 mb-3">
              <div class="section-icon-box bg-purple-subtle text-purple rounded-3 p-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
              </div>
              <h6 class="fw-bold text-dark mb-0 fs-6">إعدادات التشغيل والصيانة</h6>
            </div>

            <div class="mb-2 text-start">
              <label class="fw-bold text-dark fs-8 d-block mb-2 text-end">المنطقة الزمنية الافتراضية</label>
              <div class="position-relative">
                <input
                  type="text"
                  class="form-control form-control-general text-end text-dark fw-bold pe-3 ps-5 rounded-3"
                  v-model="generalSettings.timezone"
                />
                <span class="position-absolute start-0 top-50 translate-middle-y ms-3 text-muted">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                  </svg>
                </span>
              </div>
            </div>
          </div>

          <!-- كارت الإعدادات السريعة للنظام -->
          <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end">
            <div class="d-flex align-items-center gap-2 mb-4">
              <div class="section-icon-box bg-success-subtle text-success rounded-3 p-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
              </div>
              <h6 class="fw-bold text-dark mb-0 fs-6">الإعدادات السريعة للنظام</h6>
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
                  <div class="text-end min-w-0">
                    <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">وضع الصيانة</span>
                    <small class="text-muted fs-9 d-block text-truncate">تفعيل وضع الصيانة وإظهار شاشة مغلقة للمستخدمين عند تحديث الكود</small>
                  </div>
                </div>
                <div class="form-check form-switch m-0 ms-auto ms-sm-0">
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
                  <div class="text-end min-w-0">
                    <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">التسجيل الذاتي</span>
                    <small class="text-muted fs-9 d-block text-truncate">السماح للمستشفيات والمتبرعين الجدد بإنشاء حسابات مباشرة دون مراجعة</small>
                  </div>
                </div>
                <div class="form-check form-switch m-0 ms-auto ms-sm-0">
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
                  <div class="text-end min-w-0">
                    <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">التحقق بخطوتين (2FA)</span>
                    <small class="text-muted fs-9 d-block text-truncate">فرص المصادقة الثنائية لجميع حسابات مدراء النظام</small>
                  </div>
                </div>
                <div class="form-check form-switch m-0 ms-auto ms-sm-0">
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
defineProps({
  generalSettings: Object
});

const getImageUrl = (fileName) => {
  return new URL(`../../../assets/images/${fileName}`, import.meta.url).href;
};

const deleteLogo = () => {
  alert('تم حذف الشعار بنجاح');
};

const changeLogo = () => {
  alert('اختر الشعار الجديد للرفع');
};

const deleteFavicon = () => {
  alert('تم حذف الأيقونة بنجاح');
};

const changeFavicon = () => {
  alert('اختر الأيقونة الجديدة للرفع');
};
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
</style>
