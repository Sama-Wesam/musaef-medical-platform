<template>
  <div class="row g-3 mb-4 dir-rtl">
    <!-- المربع الأول: إجمالي الوحدات -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center h-100 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span class="text-muted fs-8">إجمالي الوحدات</span>
          <img src="@/assets/icons/Frame 2147225613.png" alt="إجمالي الوحدات" class="stat-img" />
        </div>
        <div class="text-center">
          <h2 class="fw-bold text-dark mb-0 fs-3">1,248</h2>
          <small class="text-muted fs-9">وحدة دم</small>
        </div>
      </div>
    </div>

    <!-- المربع الثاني: الوحدات الصالحة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center h-100 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span class="text-muted fs-8">الوحدات الصالحة</span>
          <img src="@/assets/icons/Frame 2147225866.png" alt="الوحدات الصالحة" class="stat-img" />
        </div>
        <div class="text-center">
          <h2 class="fw-bold text-dark mb-0 fs-3">856</h2>
          <small class="text-muted fs-9">وحدة دم</small>
        </div>
      </div>
    </div>

    <!-- المربع الثالث: مخزون منخفض -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center h-100 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span class="text-muted fs-8">مخزون منخفض</span>
          <img src="@/assets/icons/Frame 2147225868.png" alt="مخزون منخفض" class="stat-img" />
        </div>
        <div class="text-center">
          <h2 class="fw-bold text-dark mb-0 fs-3">254</h2>
          <small class="text-muted fs-9">وحدة دم</small>
        </div>
      </div>
    </div>

    <!-- المربع الرابع: الحالات الحرجة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-center h-100 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span class="text-muted fs-8">الحالات الحرجة</span>
          <img src="@/assets/icons/Frame 2147225871.png" alt="الحالات الحرجة" class="stat-img" />
        </div>
        <div class="text-center">
          <h2 class="fw-bold text-danger mb-0 fs-3">3</h2>
          <small class="text-muted fs-9">فصائل</small>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.fs-8 { font-size: 0.85rem; }
.fs-9 { font-size: 0.75rem; }
.dir-rtl { direction: rtl; }

.stat-img {
  width: 40px;
  height: 40px;
}

@media (min-width: 768px) {
  .stat-img {
    width: 48px;
    height: 48px;
  }
}
</style>
