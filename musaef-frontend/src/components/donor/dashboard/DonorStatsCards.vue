<template>
  <div class="row g-3 mb-3 mb-md-4">
    <!-- عدد التبرعات -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 text-center">
        <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3 w-100">
          <span class="text-dark fw-bold fs-8 fs-md-6">عدد التبرعات</span>
          <div class="stat-icon-wrapper bg-pink-light">
            <img :src="getImageUrl('blood-icon.png')" alt="تبرعات" class="stat-icon-img" />
          </div>
        </div>
        <h3 class="fw-black text-danger mb-1 mb-md-2 fs-4 fs-md-3">{{ stats.donationsCount }}</h3>
        <small class="text-muted fs-8 text-center d-block text-truncate">
          {{ stats.lastDonationText || 'لم تقم بالتبرع سابقاً' }}
        </small>
      </div>
    </div>

    <!-- النقاط -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 text-center">
        <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3 w-100">
          <span class="text-dark fw-bold fs-8 fs-md-6">النقاط</span>
          <div class="stat-icon-wrapper bg-yellow-light">
            <img :src="getImageUrl('star.png')" alt="نقاط" class="stat-icon-img" />
          </div>
        </div>
        <h3 class="fw-black text-warning mb-1 mb-md-2 fs-4 fs-md-3">{{ stats.points }}</h3>
        <small class="text-muted fs-8 text-center d-block text-truncate">المستوى : {{ stats.level || 'مبتدئ' }}</small>
      </div>
    </div>

    <!-- الطلبات القريبة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 text-center">
        <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3 w-100">
          <span class="text-dark fw-bold fs-8 fs-md-6">الطلبات القريبة</span>
          <div class="stat-icon-wrapper bg-blue-light">
            <img :src="getImageUrl('Frame 2147225248.png')" alt="موقع" class="stat-icon-img" />
          </div>
        </div>
        <h3 class="fw-black text-primary mb-1 mb-md-2 fs-4 fs-md-3">{{ stats.nearbyRequestsCount }}</h3>
        <small class="text-muted fs-8 text-center d-block lh-sm text-truncate">
          يوجد {{ stats.nearbyRequestsCount }} طلبات قريبة منك
        </small>
      </div>
    </div>

    <!-- حالة الأهلية للتبرع -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 text-center">
        <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3 w-100">
          <span class="text-dark fw-bold fs-8 fs-md-6">حالة الأهلية للتبرع</span>
          <div class="stat-icon-wrapper bg-green-light">
            <img :src="getImageUrl('Frame 2147225275.png')" alt="أهلية" class="stat-icon-img" />
          </div>
        </div>
        <h3 class="fw-bold mb-1 mb-md-2 fs-5 fs-md-4" :class="stats.isEligible ? 'text-success' : 'text-danger'">
          {{ stats.isEligible ? 'مؤهل' : 'غير مؤهل' }}
        </h3>
        <small class="text-muted fs-8 text-center d-block text-truncate">
          {{ stats.isEligible ? 'يمكنك التبرع الآن' : 'بانتظار اكتمال فترة التعافي' }}
        </small>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  stats: {
    type: Object,
    required: true
  }
});

const getImageUrl = (fileName) => {
  return new URL(`../../../assets/images/${fileName}`, import.meta.url).href;
};
</script>

<style scoped>
.stat-icon-wrapper { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
@media (min-width: 768px) { .stat-icon-wrapper { width: 48px; height: 48px; } }

.stat-icon-img { width: 22px; height: 22px; object-fit: contain; }
@media (min-width: 768px) { .stat-icon-img { width: 26px; height: 26px; } }

.bg-pink-light { background-color: #fdecec; }
.bg-yellow-light { background-color: #fef9c3; }
.bg-blue-light { background-color: #e0f2fe; }
.bg-green-light { background-color: #dcfce7; }
.fs-8 { font-size: 0.82rem; }
.fw-black { font-weight: 900; }
</style>
