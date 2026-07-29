<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-3 mb-md-4 text-end dir-rtl">
    <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
      <h6 class="fw-bold text-dark mb-0 fs-7">تطور الحالات الطارئة (آخر 6 أشهر)</h6>
    </div>

    <!-- المخطط البياني الملتف مع خاصية التمرير للحفاظ على الرسم البياني -->
    <div class="chart-scroll-wrapper overflow-x-auto">
      <div class="position-relative pt-2 pb-1 min-chart-width" style="height: 200px;">
        <div class="chart-y-axis d-flex flex-column justify-content-between position-absolute w-100 h-100 pe-2 text-muted fs-9">
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>5K</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>4K</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>3K</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>2K</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>1K</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>0</span></div>
        </div>

        <svg class="w-100 h-100 position-absolute top-0 start-0 pe-4 ps-2" viewBox="0 0 600 150" preserveAspectRatio="none">
          <defs>
            <linearGradient id="areaGradientBlue" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#2563EB" stop-opacity="0.22"/>
              <stop offset="100%" stop-color="#2563EB" stop-opacity="0.0"/>
            </linearGradient>
          </defs>

          <path d="M 20,110 L 100,75 L 200,40 L 300,70 L 400,50 L 500,35 L 560,50 L 560,150 L 20,150 Z" fill="url(#areaGradientBlue)" />
          <path d="M 20,110 L 100,75 L 200,40 L 300,70 L 400,50 L 500,35 L 560,50" fill="none" stroke="#2563EB" stroke-width="3" />

          <circle cx="20" cy="110" r="5" fill="#2563EB" />
          <circle cx="100" cy="75" r="5" fill="#2563EB" />
          <circle cx="200" cy="40" r="5" fill="#2563EB" />
          <circle cx="300" cy="70" r="5" fill="#2563EB" />
          <circle cx="400" cy="50" r="5" fill="#2563EB" />
          <circle cx="500" cy="35" r="5" fill="#2563EB" />
          <circle cx="560" cy="50" r="6" fill="#1D4ED8" stroke="#ffffff" stroke-width="2" />
        </svg>

        <!-- الشارة الزرقاء فوق القمة 4,287 -->
        <div class="position-absolute bg-primary text-white rounded-3 px-2 py-1 fs-9 fw-bold shadow-sm d-flex flex-column align-items-center" style="top: 10px; left: 88%; transform: translateX(-50%);">
          <span>4,287</span>
          <div class="tooltip-arrow"></div>
        </div>
      </div>

      <!-- ترتيب الأشهر المعدل من اليمين لليسار: يوليو، أغسطس، سبتمبر، أكتوبر، نوفمبر، ديسمبر -->
      <div class="d-flex justify-content-between text-muted fs-8 pt-3 px-3 min-chart-width">
        <span>يوليو</span>
        <span>أغسطس</span>
        <span>سبتمبر</span>
        <span>أكتوبر</span>
        <span>نوفمبر</span>
        <span>ديسمبر</span>
      </div>
    </div>
  </div>
</template>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }

.min-chart-width {
  min-width: 450px;
}

@media (min-width: 768px) {
  .min-chart-width {
    min-width: 100%;
  }
}

.chart-scroll-wrapper {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.chart-scroll-wrapper::-webkit-scrollbar {
  display: none;
}

.tooltip-arrow {
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid #2563EB;
}
.dir-rtl { direction: rtl; }
</style>
