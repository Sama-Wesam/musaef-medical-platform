<template>
  <div class="severity-legend-box position-absolute bg-white p-2.5 p-md-3 rounded-4 shadow text-end dir-rtl">
    <span class="fw-bold text-dark fs-8 d-block mb-1.5 mb-md-2.5">مستوى الخطورة</span>

    <div class="d-flex flex-column gap-1.5 gap-md-2 fs-9">
      <div class="d-flex align-items-center justify-content-start gap-2">
        <span class="dot-indicator bg-danger"></span>
        <span class="text-dark fw-bold">حرجة</span>
      </div>
      <div class="d-flex align-items-center justify-content-start gap-2">
        <span class="dot-indicator bg-warning"></span>
        <span class="text-dark fw-bold">متوسطة</span>
      </div>
      <div class="d-flex align-items-center justify-content-start gap-2">
        <span class="dot-indicator bg-success"></span>
        <span class="text-dark fw-bold">منخفضة</span>
      </div>
    </div>
  </div>
</template>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }

.dot-indicator {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  display: inline-block;
  flex-shrink: 0;
}

@media (min-width: 768px) {
  .dot-indicator {
    width: 12px;
    height: 12px;
  }
}

.severity-legend-box {
  border: 1px solid #e5e7eb;
  bottom: 12px;
  right: 12px;
  width: 120px;
  z-index: 10;
}

@media (min-width: 768px) {
  .severity-legend-box {
    bottom: 24px;
    right: 24px;
    width: 140px;
  }
}

.dir-rtl { direction: rtl; }
</style>
