<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end dir-rtl">
    <h6 class="fw-bold text-dark mb-3">تنبيهات فورية</h6>
    <div class="d-flex flex-column gap-2 mb-3">
      <div class="p-2 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark">-O</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 fs-9">منخفض جداً</span>
        </div>
        <small class="text-muted fs-9">المتوفر: 2 وحدة فقط</small>
      </div>

      <div class="p-2 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark">-B</span>
          <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1 fs-9">منخفض</span>
        </div>
        <small class="text-muted fs-9">المتوفر: 5 وحدة</small>
      </div>

      <div class="p-2 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark">+A</span>
          <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1 fs-9">منخفض</span>
        </div>
        <small class="text-muted fs-9">المتوفر: 3 وحدة فقط</small>
      </div>
    </div>
    <button class="btn btn-light bg-light text-secondary btn-sm w-100 rounded-pill fs-8">
      عرض جميع التنبيهات
    </button>
  </div>
</template>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.dir-rtl { direction: rtl; }
</style>
