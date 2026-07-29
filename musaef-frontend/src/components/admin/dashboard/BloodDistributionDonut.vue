<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-end dir-rtl">
    <h6 class="fw-bold text-dark mb-3 mb-md-4 fs-7">توزيع الطلبات حسب فصيلة الدم</h6>

    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-around gap-3">
      <!-- الدائرة المركزية (Donut View) -->
      <div class="position-relative d-flex align-items-center justify-content-center chart-container-box">
        <svg viewBox="0 0 42 42" class="donut-chart">
          <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff"></circle>
          <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#f3f4f6" stroke-width="5"></circle>

          <!-- O+ (41%) - أحمر -->
          <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#D32F2F" stroke-width="6" stroke-dasharray="41 59" stroke-dashoffset="25"></circle>
          <!-- A+ (22%) - أزرق -->
          <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#2563EB" stroke-width="5" stroke-dasharray="22 78" stroke-dashoffset="84"></circle>
          <!-- B+ (13%) - أخضر -->
          <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#16A34A" stroke-width="5" stroke-dasharray="13 87" stroke-dashoffset="62"></circle>
          <!-- AB+ (15%) - أصفر -->
          <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#FBBF24" stroke-width="5" stroke-dasharray="15 85" stroke-dashoffset="49"></circle>
          <!-- O- (6%) - بنفسجي -->
          <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#7C3AED" stroke-width="5" stroke-dasharray="6 94" stroke-dashoffset="34"></circle>
        </svg>

        <div class="position-absolute text-center">
          <strong class="d-block fs-5 fw-bold text-dark mb-0">24,892</strong>
          <small class="text-muted fs-9">إجمالي الطلبات</small>
        </div>
      </div>

      <!-- دليل الفصائل والألوان -->
      <div class="d-flex flex-column gap-2 fs-8 text-dark">
        <div class="d-flex align-items-center gap-2 justify-content-end">
          <span class="text-muted fs-9">(41%)</span>
          <span class="fw-bold me-1">O+</span>
          <span class="dot-indicator" style="background-color: #D32F2F;"></span>
        </div>
        <div class="d-flex align-items-center gap-2 justify-content-end">
          <span class="text-muted fs-9">(22%)</span>
          <span class="fw-bold me-1">A+</span>
          <span class="dot-indicator" style="background-color: #2563EB;"></span>
        </div>
        <div class="d-flex align-items-center gap-2 justify-content-end">
          <span class="text-muted fs-9">(13%)</span>
          <span class="fw-bold me-1">B+</span>
          <span class="dot-indicator" style="background-color: #16A34A;"></span>
        </div>
        <div class="d-flex align-items-center gap-2 justify-content-end">
          <span class="text-muted fs-9">(15%)</span>
          <span class="fw-bold me-1">AB+</span>
          <span class="dot-indicator" style="background-color: #FBBF24;"></span>
        </div>
        <div class="d-flex align-items-center gap-2 justify-content-end">
          <span class="text-muted fs-9">(6%)</span>
          <span class="fw-bold me-1">O-</span>
          <span class="dot-indicator" style="background-color: #7C3AED;"></span>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }

.chart-container-box {
  width: 140px;
  height: 140px;
}

@media (min-width: 768px) {
  .chart-container-box {
    width: 160px;
    height: 160px;
  }
}

.donut-chart {
  transform: rotate(-90deg);
}

.dot-indicator {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  display: inline-block;
}
.dir-rtl { direction: rtl; }
</style>
