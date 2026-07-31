<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end dir-rtl">

    <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
      <span class="fs-5">🔔</span>
      <h6 class="fw-bold text-dark mb-0 fs-6">إشعارات النظام العامة</h6>
    </div>

    <div class="d-flex flex-column gap-3 mb-4">
      <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-light-subtle flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
          <div class="icon-box bg-danger-subtle rounded-3 p-2 d-flex align-items-center justify-content-center flex-shrink-0">
            <span class="text-danger fs-6">🔔</span>
          </div>
          <div class="text-end">
            <span class="fw-bold text-dark d-block fs-7 mb-1">الإشعارات الفورية</span>
            <small class="text-muted fs-8 d-block">استقبال التنبيهات الفورية على المتصفح عند استجابة متبرع جديد لنداء طارئ</small>
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
          <div class="text-end">
            <span class="fw-bold text-dark d-block fs-7 mb-1">إشعارات البريد الإلكتروني</span>
            <small class="text-muted fs-8 d-block">تلقي تقارير أسبوعية وإشعارات الحساب الرسمية عبر البريد</small>
          </div>
        </div>
        <div class="form-check form-switch m-0 p-0 ms-auto ms-sm-0">
          <input class="form-check-input custom-red-switch m-0" type="checkbox" v-model="notifSettings.emailNotifs" id="emailNotifs" />
        </div>
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-start gap-2 mb-3 pt-2">
      <span class="fs-5">🧠</span>
      <h6 class="fw-bold text-dark mb-0 fs-6">تنبيهات المخزون الذكية والذكاء الاصطناعي (Blood Demand Forecast AI)</h6>
    </div>

    <div class="d-flex flex-column gap-3 mb-4">
      <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-light-subtle flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
          <div class="icon-box bg-danger-subtle rounded-3 p-2 d-flex align-items-center justify-content-center flex-shrink-0">
            <span class="text-danger fs-6">🩸</span>
          </div>
          <div class="text-end">
            <span class="fw-bold text-dark d-block fs-7 mb-1">تنبيهات نقص المخزون الحرج</span>
            <small class="text-muted fs-8 d-block">إرسال إشعارات فورية عند انخفاض أية فصيلة دم عن الحد الأدنى في المستشفى</small>
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
          <div class="text-end">
            <span class="fw-bold text-dark d-block fs-7 mb-1">توقعات الذكاء الاصطناعي للطلب المستقبلي</span>
            <small class="text-muted fs-8 d-block">تلقي توصيات استباقية من نموذج BloodDemandForecast لإطلاق حملات التبرع قبل النقص المتوقع خلال 72 ساعة</small>
          </div>
        </div>
        <div class="form-check form-switch m-0 p-0 ms-auto ms-sm-0">
          <input class="form-check-input custom-red-switch m-0" type="checkbox" v-model="notifSettings.aiPredictions" id="aiPredictions" />
        </div>
      </div>
    </div>

    <!-- أزرار التحكم الفعالة والتفاعلية -->
    <div class="d-flex align-items-center justify-content-end gap-2 gap-md-3 mt-4 pt-2 border-top">
      <button type="button" class="btn btn-outline-secondary px-3 px-md-4 py-2 rounded-3 fs-7 fw-bold" @click="resetNotifSettings">إلغاء</button>
      <button type="button" class="btn btn-danger px-4 px-md-5 py-2 rounded-3 fs-7 fw-bold" @click="saveNotificationSettings">حفظ الإعدادات</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const notifSettings = ref({
  instantNotifs: true,
  emailNotifs: true,
  criticalStockAlerts: true,
  aiPredictions: true
});

const saveNotificationSettings = () => {
  alert('تم حفظ إعدادات الإشعارات والتنبيهات الذكية بنجاح[cite: 67]');
};

const resetNotifSettings = () => {
  notifSettings.value = {
    instantNotifs: true,
    emailNotifs: true,
    criticalStockAlerts: true,
    aiPredictions: true
  };
  alert('تم استعادة الإعدادات الافتراضية بنجاح');
};
</script>

<style scoped>
.fs-7 { font-size: 0.85rem; }
.fs-8 { font-size: 0.75rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-light-subtle { background-color: #f8fafc; }
.icon-box { width: 38px; height: 38px; }
.dir-rtl { direction: rtl; }

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

.custom-red-switch:focus {
  border-color: transparent;
  box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}
</style>
