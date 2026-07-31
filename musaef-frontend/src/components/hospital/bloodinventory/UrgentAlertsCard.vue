<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end dir-rtl">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="fw-bold text-dark mb-0">تنبيهات فورية (Blood Demand Forecast AI)</h6>
      <span class="badge bg-danger text-white rounded-pill px-2 py-1 fs-9">ذكاء اصطناعي</span>
    </div>
    <div class="d-flex flex-column gap-2 mb-3">
      <div class="p-2 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8 border-start border-4 border-danger">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark" dir="ltr">-O</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 fs-9">منخفض جداً (حرج)</span>
        </div>
        <small class="text-muted fs-9">المتوفر: 2 وحدة فقط</small>
      </div>

      <div class="p-2 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8 border-start border-4 border-warning">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark" dir="ltr">-B</span>
          <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1 fs-9">منخفض</span>
        </div>
        <small class="text-muted fs-9">المتوفر: 5 وحدة</small>
      </div>

      <div class="p-2 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8 border-start border-4 border-warning">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark" dir="ltr">+A</span>
          <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1 fs-9">منخفض</span>
        </div>
        <small class="text-muted fs-9">المتوفر: 3 وحدة فقط</small>
      </div>
    </div>
    <button class="btn btn-light bg-light text-secondary btn-sm w-100 rounded-pill fs-8 fw-bold" @click="handleEmergencyAction">
      إطلاق نداء طارئ أو التواصل مع بنوك مجاورة
    </button>
  </div>
</template>

<script setup>
const handleEmergencyAction = () => {
  const action = confirm("هل تريد إطلاق نداء طارئ فوري للمتبرعين عبر نظام Smart Matching AI، أم التواصل مع بنوك الدم المجاورة؟\n\nاضغط 'موافق' لإطلاق النداء الطارئ، أو 'إلغاء' للتواصل مع بنوك الدم المجاورة.");
  if (action) {
    alert("🚨 تم إطلاق النداء الطارئ بنجاح لفصيلة (-O) وتم تنبيه المتبرعين القريبين!");
  } else {
    alert("📞 جاري فتح نافذة الاتصال والتنسيق مع بنوك الدم الإقليمية المجاورة...");
  }
};
</script>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.dir-rtl { direction: rtl; }
</style>
