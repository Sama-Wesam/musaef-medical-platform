<template>
  <div class="row g-3 mb-3 mb-md-4 dir-rtl" dir="rtl">
    <!-- 1. عدد المتبرعين -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2-4">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end position-relative h-100">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">عدد المتبرعين</span>
          <div class="icon-box-badge bg-danger-subtle text-danger rounded-3 p-1.5 d-flex align-items-center justify-content-center">
            <img :src="getIconUrl('pepicons-pencil_people.png')" alt="donors" width="18" height="18" />
          </div>
        </div>
        <h4 class="fw-black text-dark mb-1 fs-3">{{ stats?.donorsCount || '128,547' }}</h4>
        <div class="d-flex align-items-center gap-1 text-success fs-9 fw-bold flex-wrap">
          <span>+12.5%</span>
          <span class="text-muted fw-normal">عن الشهر الماضي</span>
        </div>
      </div>
    </div>

    <!-- 2. عدد المستشفيات -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2-4">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end position-relative h-100">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">عدد المستشفيات</span>
          <div class="icon-box-badge bg-primary-subtle text-primary rounded-3 p-1.5 d-flex align-items-center justify-content-center">
            <img :src="getIconUrl('pepicons-pencil_people (1).png')" alt="hospitals" width="18" height="18" />
          </div>
        </div>
        <h4 class="fw-black text-dark mb-1 fs-3">{{ stats?.hospitalsCount || '347' }}</h4>
        <div class="d-flex align-items-center gap-1 text-success fs-9 fw-bold flex-wrap">
          <span>+12.5%</span>
          <span class="text-muted fw-normal">عن الشهر الماضي</span>
        </div>
      </div>
    </div>

    <!-- 3. إجمالي الطلبات -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2-4">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end position-relative h-100">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">إجمالي الطلبات</span>
          <div class="icon-box-badge bg-purple-subtle text-purple rounded-3 p-1.5 d-flex align-items-center justify-content-center">
            <img :src="getIconUrl('pepicons-pencil_people (2).png')" alt="requests" width="18" height="18" />
          </div>
        </div>
        <h4 class="fw-black text-dark mb-1 fs-3">{{ stats?.requestsCount || '24,892' }}</h4>
        <div class="d-flex align-items-center gap-1 text-success fs-9 fw-bold flex-wrap">
          <span>+12.5%</span>
          <span class="text-muted fw-normal">عن الشهر الماضي</span>
        </div>
      </div>
    </div>

    <!-- 4. إجمالي التبرعات -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2-4">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end position-relative h-100">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">إجمالي التبرعات</span>
          <div class="icon-box-badge bg-teal-subtle text-teal rounded-3 p-1.5 d-flex align-items-center justify-content-center">
            <img :src="getIconUrl('pepicons-pencil_people (3).png')" alt="donations" width="18" height="18" />
          </div>
        </div>
        <h4 class="fw-black text-dark mb-1 fs-3">{{ stats?.donationsCount || '18,765' }}</h4>
        <div class="d-flex align-items-center gap-1 text-success fs-9 fw-bold flex-wrap">
          <span>+12.5%</span>
          <span class="text-muted fw-normal">عن الشهر الماضي</span>
        </div>
      </div>
    </div>

    <!-- 5. الحالات الحرجة النشطة -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2-4">
      <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end position-relative h-100">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">الحالات الحرجة النشطة</span>
          <div class="icon-box-badge bg-danger-subtle text-danger rounded-3 p-1.5 d-flex align-items-center justify-content-center">
            <img :src="getIconUrl('pepicons-pencil_people (4).png')" alt="critical" width="18" height="18" />
          </div>
        </div>
        <h4 class="fw-black text-dark mb-1 fs-3">{{ stats?.criticalCasesCount || '312' }}</h4>
        <div class="d-flex align-items-center gap-1 text-success fs-9 fw-bold flex-wrap">
          <span>+12.5%</span>
          <span class="text-muted fw-normal">عن الشهر الماضي</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  stats: {
    type: Object,
    default: () => ({})
  }
});

const getIconUrl = (fileName) => {
  if (!fileName) return '';
  if (fileName.startsWith('http') || fileName.startsWith('data:')) return fileName;
  try {
    return new URL(`../../../assets/icons/${fileName}`, import.meta.url).href;
  } catch (e) {
    return '';
  }
};
</script>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.fw-black { font-weight: 900; }

@media (min-width: 1200px) {
  .col-xl-2-4 {
    flex: 0 0 auto;
    width: 20%;
  }
}

.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-primary-subtle { background-color: #dbeafe !important; }
.bg-purple-subtle { background-color: #f3e8ff !important; }
.bg-teal-subtle { background-color: #ccfbf1 !important; }

.text-purple { color: #9333ea !important; }
.text-teal { color: #0d9488 !important; }

.icon-box-badge {
  width: 32px;
  height: 32px;
}
.dir-rtl { direction: rtl; }
</style>
