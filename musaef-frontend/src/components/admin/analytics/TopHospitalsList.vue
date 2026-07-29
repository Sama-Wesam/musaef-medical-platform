<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-end d-flex flex-column justify-content-between dir-rtl">
    <div>
      <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
        <img :src="getImageUrl('Group 1000002306 (1).png')" alt="hospital icon" width="24" height="24" class="header-icon" />
        <h5 class="fw-bold text-dark mb-0 fs-6">أكثر المستشفيات احتياجاً</h5>
      </div>

      <div class="d-flex flex-column gap-2.5 gap-md-3 fs-8">
        <div v-for="(h, i) in topHospitals" :key="h.name" class="d-flex align-items-center gap-2">
          <span class="rank-circle text-white fw-bold fs-9 rounded-circle d-flex align-items-center justify-content-center" :style="{ backgroundColor: h.color }">
            {{ i + 1 }}
          </span>
          <span class="text-dark fw-bold text-start text-truncate" style="min-width: 90px; max-width: 120px;">{{ h.name }}</span>
          <div class="progress flex-grow-1 bg-light rounded-pill" style="height: 8px;">
            <div class="progress-bar rounded-pill" :style="{ width: h.percent + '%', backgroundColor: h.color }"></div>
          </div>
          <span class="text-muted fs-9 fw-bold w-35px text-end flex-shrink-0">{{ h.percent }}%</span>
        </div>
      </div>
    </div>

    <a href="#" class="text-danger text-decoration-none fs-8 fw-bold mt-3 mt-md-4 d-inline-block text-center">
      عرض جميع المستشفيات &gt;
    </a>
  </div>
</template>

<script setup>
defineProps({
  topHospitals: {
    type: Array,
    required: true
  }
});

const getImageUrl = (fileName) => {
  return new URL(`../../../assets/images/${fileName}`, import.meta.url).href;
};
</script>

<style scoped>
.fs-6 { font-size: 1.05rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }

.rank-circle {
  width: 22px;
  height: 22px;
  min-width: 22px;
  flex-shrink: 0;
}

.header-icon {
  width: 24px;
  height: 24px;
}
@media (min-width: 768px) {
  .header-icon {
    width: 28px;
    height: 28px;
  }
}

.w-35px {
  width: 35px;
}
.dir-rtl { direction: rtl; }
</style>
