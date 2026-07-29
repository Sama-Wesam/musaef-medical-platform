<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white flex-grow-1 text-end dir-rtl">
    <h6 class="fw-bold text-dark mb-3">عمليات التبرع الأخيرة</h6>
    <div class="d-flex flex-column gap-2 mb-3 fs-8">
      <div class="d-flex align-items-center justify-content-between p-1 border-bottom-dashed">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <span class="text-truncate">أحمد خالد</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9 flex-shrink-0">+O</span>
        </div>
        <small class="text-muted fs-9 flex-shrink-0">10:24 ص</small>
      </div>

      <div class="d-flex align-items-center justify-content-between p-1 border-bottom-dashed">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <span class="text-truncate">سارة القحطاني</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9 flex-shrink-0">+A</span>
        </div>
        <small class="text-muted fs-9 flex-shrink-0">09:58 ص</small>
      </div>

      <div class="d-flex align-items-center justify-content-between p-1 border-bottom-dashed">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <span class="text-truncate">فهد المطيري</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9 flex-shrink-0">+B</span>
        </div>
        <small class="text-muted fs-9 flex-shrink-0">09:15 ص</small>
      </div>

      <div class="d-flex align-items-center justify-content-between p-1 border-bottom-dashed">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <span class="text-truncate">ريم الشمري</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9 flex-shrink-0">+AB</span>
        </div>
        <small class="text-muted fs-9 flex-shrink-0">08:47 ص</small>
      </div>

      <div class="d-flex align-items-center justify-content-between p-1">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <span class="text-truncate">عبدالله القحطاني</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9 flex-shrink-0">-O</span>
        </div>
        <small class="text-muted fs-9 flex-shrink-0">08:30 ص</small>
      </div>
    </div>
    <button class="btn btn-light bg-light text-secondary btn-sm w-100 rounded-pill fs-8 mt-auto">
      عرض جميع العمليات
    </button>
  </div>
</template>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.border-bottom-dashed { border-bottom: 1px dashed #e5e7eb; }
.dir-rtl { direction: rtl; }
</style>
