<template>
  <div class="email-section dir-rtl">
    <div class="d-flex flex-column gap-3 gap-md-4">
      <!-- كارت إعدادات خادم الإرسال -->
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end">
        <div class="text-end mb-3 mb-md-4">
          <h5 class="fw-bold text-dark mb-1 fs-6 fs-md-5">إعدادات خادم الإرسال(SMTP)</h5>
          <p class="text-muted fs-9 mb-0">تهيئة خادم البريد الإلكتروني لإرسال الرسائل والتعاميم</p>
        </div>

        <div class="row align-items-center g-3">
          <div class="col-12 col-lg-8">
            <div class="d-flex flex-column gap-3">
              <div class="d-flex align-items-stretch align-items-sm-center justify-content-end gap-2 gap-sm-3 flex-column flex-sm-row">
                <label class="fw-bold text-dark fs-8 text-nowrap label-width">خادم (SMTP)</label>
                <div class="position-relative flex-grow-1 w-100">
                  <input
                    type="text"
                    class="form-control form-control-custom text-end pe-3 ps-5 rounded-3"
                    v-model="smtpSettings.host"
                  />
                  <span class="position-absolute start-0 top-50 translate-middle-y ms-3 text-muted">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                      <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                      <line x1="6" y1="6" x2="6.01" y2="6"></line>
                      <line x1="6" y1="18" x2="6.01" y2="18"></line>
                    </svg>
                  </span>
                </div>
              </div>

              <div class="d-flex align-items-stretch align-items-sm-center justify-content-end gap-2 gap-sm-3 flex-column flex-sm-row">
                <label class="fw-bold text-dark fs-8 text-nowrap label-width">المنفذ (Port)</label>
                <div class="position-relative flex-grow-1 w-100">
                  <input
                    type="text"
                    class="form-control form-control-custom text-end pe-3 ps-5 rounded-3"
                    v-model="smtpSettings.port"
                  />
                  <span class="position-absolute start-0 top-50 translate-middle-y ms-3 text-muted">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                  </span>
                </div>
              </div>

              <div class="d-flex align-items-stretch align-items-sm-center justify-content-end gap-2 gap-sm-3 flex-column flex-sm-row">
                <label class="fw-bold text-dark fs-8 text-nowrap label-width">البريد الإلكتروني المرسل</label>
                <div class="position-relative flex-grow-1 w-100">
                  <input
                    type="email"
                    class="form-control form-control-custom text-end pe-3 ps-5 rounded-3"
                    v-model="smtpSettings.senderEmail"
                  />
                  <span class="position-absolute start-0 top-50 translate-middle-y ms-3 text-muted">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                      <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                  </span>
                </div>
              </div>

              <div class="d-flex align-items-stretch align-items-sm-center justify-content-end gap-2 gap-sm-3 flex-column flex-sm-row">
                <label class="fw-bold text-dark fs-8 text-nowrap label-width">كلمة مرور الخادم</label>
                <div class="position-relative flex-grow-1 w-100">
                  <input
                    type="password"
                    class="form-control form-control-custom text-end pe-3 ps-5 rounded-3"
                    v-model="smtpSettings.password"
                  />
                  <span class="position-absolute start-0 top-50 translate-middle-y ms-3 text-muted">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                  </span>
                </div>
              </div>

              <div class="d-flex align-items-stretch align-items-sm-center justify-content-end gap-2 gap-sm-3 flex-column flex-sm-row">
                <label class="fw-bold text-dark fs-8 text-nowrap label-width">نوع التشفير</label>
                <div class="position-relative flex-grow-1 w-100">
                  <input
                    type="text"
                    class="form-control form-control-custom text-end pe-3 ps-5 rounded-3"
                    v-model="smtpSettings.encryption"
                  />
                  <span class="position-absolute start-0 top-50 translate-middle-y ms-3 text-muted">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                      <polyline points="9 12 11 14 15 10"></polyline>
                    </svg>
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- زر اختبار الاتصال بالخادم المفعل -->
          <div class="col-12 col-lg-4 mt-2 mt-lg-0">
            <div class="d-flex flex-column align-items-stretch align-items-lg-start gap-2 ps-0 ps-lg-4 text-center">
              <button
                class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-2 text-dark fs-9 fw-semibold shadow-xs"
                :disabled="settingsStore.testingSmtp"
                @click="settingsStore.testSmtpConnection()"
              >
                <span v-if="settingsStore.testingSmtp" class="spinner-border spinner-border-sm me-1"></span>
                <span>اختبار الاتصال بالخادم</span>
              </button>
              <div class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fs-9 d-flex align-items-center justify-content-center gap-1">
                <span>متصل بنجاح</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- كارت التحكم بقوالب الرسائل -->
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end">
        <div class="text-end mb-3 mb-md-4">
          <h5 class="fw-bold text-dark mb-1 fs-6 fs-md-5">التحكم بقوالب الرسائل والتعاميم وآلية الإرسال</h5>
          <p class="text-muted fs-9 mb-0">إدارة قوالب البريد والتقارير وإعدادات الإرسال التلقائي</p>
        </div>

        <div class="d-flex flex-column gap-2.5 gap-md-3 mb-4">

          <!-- قالب 1 -->
          <div class="template-card p-3 rounded-4 border bg-light-subtle d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
              <div class="template-icon-box bg-white rounded-3 p-2 border flex-shrink-0">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#a0aec0" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
              </div>
              <div class="text-end min-w-0">
                <h6 class="fw-bold text-dark fs-8 mb-1 text-truncate">قالب ترحيب بالمتبرع الجديد</h6>
                <small class="text-muted fs-9 d-block text-truncate">يتم إرسال رسالة ترحيبية تلقائية للمتبرع بعد تسجيله في النظام</small>
              </div>
            </div>
            <div class="ms-auto ms-sm-0">
              <button
                class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1.5 fs-8 fw-semibold d-flex align-items-center gap-2"
                @click="settingsStore.editTemplate('ترحيب بالمتبرع الجديد')"
              >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 20h9"></path>
                  <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
                <span>تعديل النص</span>
              </button>
            </div>
          </div>

          <!-- قالب 2 -->
          <div class="template-card p-3 rounded-4 border bg-light-subtle d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
              <div class="template-icon-box bg-white rounded-3 p-2 border flex-shrink-0">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#a0aec0" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
              </div>
              <div class="text-end min-w-0">
                <h6 class="fw-bold text-dark fs-8 mb-1 text-truncate">قالب إشعار المستشفى بنقص المخزون</h6>
                <small class="text-muted fs-9 d-block text-truncate">تنبيه تلقائي للمستشفيات عند إخفاض فصيلة دم عن الحد الآمن</small>
              </div>
            </div>
            <div class="ms-auto ms-sm-0">
              <button
                class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1.5 fs-8 fw-semibold d-flex align-items-center gap-2"
                @click="settingsStore.editTemplate('إشعار المستشفى بنقص المخزون')"
              >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 20h9"></path>
                  <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
                <span>تعديل النص</span>
              </button>
            </div>
          </div>

          <!-- قالب 3 -->
          <div class="template-card p-3 rounded-4 border bg-light-subtle d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
              <div class="template-icon-box bg-white rounded-3 p-2 border flex-shrink-0">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#a0aec0" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
              </div>
              <div class="text-end min-w-0">
                <h6 class="fw-bold text-dark fs-8 mb-1 text-truncate">قالب استعادة كلمة المرور</h6>
                <small class="text-muted fs-9 d-block text-truncate">يتم إرسال رابط إعادة تعيين كلمة المرور للمستخدمين</small>
              </div>
            </div>
            <div class="ms-auto ms-sm-0">
              <button
                class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1.5 fs-8 fw-semibold d-flex align-items-center gap-2"
                @click="settingsStore.editTemplate('استعادة كلمة المرور')"
              >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 20h9"></path>
                  <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
                <span>تعديل النص</span>
              </button>
            </div>
          </div>

          <!-- قالب 4 -->
          <div class="template-card p-3 rounded-4 border bg-light-subtle d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
              <div class="template-icon-box bg-white rounded-3 p-2 border flex-shrink-0">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#a0aec0" stroke-width="2">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
              </div>
              <div class="text-end min-w-0">
                <h6 class="fw-bold text-dark fs-8 mb-1 text-truncate">إرسال التقارير الدورية الآلية للمستشفيات وبنوك الدم</h6>
                <small class="text-muted fs-9 d-block text-truncate">يتم إرسال تقارير الأداء والمخزون بشكل دوري حسب جدولة المحددة</small>
              </div>
            </div>
            <div class="ms-auto ms-sm-0">
              <div class="form-check form-switch m-0">
                <input class="form-check-input custom-switch" type="checkbox" v-model="emailSettings.periodicReports" />
              </div>
            </div>
          </div>

          <!-- قالب 5 -->
          <div class="template-card p-3 rounded-4 border bg-light-subtle d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
              <div class="template-icon-box bg-white rounded-3 p-2 border flex-shrink-0">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#a0aec0" stroke-width="2">
                  <path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path>
                </svg>
              </div>
              <div class="text-end min-w-0">
                <h6 class="fw-bold text-dark fs-8 mb-1 text-truncate">تفعيل النسخ الاحتياطي لرسائل النظام</h6>
                <small class="text-muted fs-9 d-block text-truncate">حفظ نسخة احتياطية من جميع الرسائل المرسلة في قاعدة بيانات آمنة</small>
              </div>
            </div>
            <div class="ms-auto ms-sm-0">
              <div class="form-check form-switch m-0">
                <input class="form-check-input custom-switch" type="checkbox" v-model="emailSettings.backupSystemEmails" />
              </div>
            </div>
          </div>

        </div>

        <!-- أزرار الإلغاء وحفظ إعدادات البريد الإلكتروني المفعلة -->
        <div class="d-flex align-items-center justify-content-end gap-2 gap-md-3 mt-4 pt-2 flex-wrap">
          <button class="btn btn-outline-secondary px-3 px-md-4 py-2.5 rounded-3 fw-semibold fs-8 d-flex align-items-center gap-2" @click="settingsStore.fetchSettings()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="15" y1="9" x2="9" y2="15"></line>
              <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <span>إلغاء</span>
          </button>

          <button
            class="btn bg-red-main text-white px-3 px-md-4 py-2.5 rounded-3 fw-bold fs-8 d-flex align-items-center gap-2 shadow-sm"
            :disabled="settingsStore.saving"
            @click="settingsStore.saveSettings()"
          >
            <span v-if="settingsStore.saving" class="spinner-border spinner-border-sm me-1"></span>
            <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
              <polyline points="17 21 17 13 7 13 7 21"></polyline>
              <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            <span>حفظ إعدادات البريد الإلكتروني</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useSettingsStore } from '@/stores/settingsStore';

const settingsStore = useSettingsStore();

defineProps({
  smtpSettings: Object,
  emailSettings: Object
});
</script>

<style scoped>
.fs-5 { font-size: 1.15rem; }
.fs-6 { font-size: 1.05rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }

.bg-red-main { background-color: #dc2626 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-light-subtle { background-color: #f8fafc !important; }

@media (min-width: 576px) {
  .label-width { min-width: 140px; }
}

.form-control-custom {
  background-color: #ffffff;
  border: 1px solid #e2e8f0;
  height: 44px;
  font-size: 0.85rem;
  box-shadow: none !important;
  transition: border-color 0.2s ease;
}

.form-control-custom:focus { border-color: #cbd5e1; }

.template-card {
  border-color: #edf2f7 !important;
  transition: background-color 0.2s ease;
}

.template-card:hover { background-color: #f1f5f9 !important; }

.template-icon-box {
  width: 40px;
  height: 40px;
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
