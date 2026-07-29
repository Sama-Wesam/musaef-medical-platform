<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-3 mb-md-4 dir-rtl">
    <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
      <img :src="getImageUrl('mdi_blood-plus-outline (2).png')" alt="blood icon" width="24" height="24" class="header-icon" />
      <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">الطلب حسب فصيلة الدم</h5>
    </div>

    <!-- الحاوية الملتفة التي تسمح بالتمرير في الجوال -->
    <div class="chart-scroll-wrapper overflow-x-auto">
      <div class="position-relative pt-3 pb-1 min-chart-width" style="height: 230px; direction: ltr;">
        <!-- خلفية شبكة Y-Axis المنقطة جهة اليسار -->
        <div class="chart-y-axis position-absolute h-100 start-0 w-100 d-flex flex-column justify-content-between text-secondary fs-8 fw-semibold" style="pointer-events: none;">
          <div class="d-flex align-items-center w-100">
            <span class="pe-2 text-end" style="width: 45px;">5000</span>
            <div class="border-bottom flex-grow-1 border-secondary-subtle" style="border-style: dotted !important;"></div>
          </div>
          <div class="d-flex align-items-center w-100">
            <span class="pe-2 text-end" style="width: 45px;">4000</span>
            <div class="border-bottom flex-grow-1 border-secondary-subtle" style="border-style: dotted !important;"></div>
          </div>
          <div class="d-flex align-items-center w-100">
            <span class="pe-2 text-end" style="width: 45px;">3000</span>
            <div class="border-bottom flex-grow-1 border-secondary-subtle" style="border-style: dotted !important;"></div>
          </div>
          <div class="d-flex align-items-center w-100">
            <span class="pe-2 text-end" style="width: 45px;">2000</span>
            <div class="border-bottom flex-grow-1 border-secondary-subtle" style="border-style: dotted !important;"></div>
          </div>
          <div class="d-flex align-items-center w-100">
            <span class="pe-2 text-end" style="width: 45px;">1000</span>
            <div class="border-bottom flex-grow-1 border-secondary-subtle" style="border-style: dotted !important;"></div>
          </div>
          <div class="d-flex align-items-center w-100">
            <span class="pe-2 text-end" style="width: 45px;">0</span>
            <div class="border-bottom flex-grow-1 border-secondary-subtle" style="border-style: solid !important;"></div>
          </div>
        </div>

        <!-- أعمدة المخطط البياني الملونة -->
        <div class="d-flex align-items-end justify-content-around h-100 position-relative z-1" style="padding-left: 50px; padding-right: 20px;">
          <div v-for="b in bloodDemand" :key="b.type" class="text-center d-flex flex-column align-items-center" style="width: 40px;">
            <small class="fw-bold text-dark fs-9 mb-1.5">{{ b.count }}</small>
            <div
              class="bar-column w-100"
              :style="{ height: (b.count / 2.6) + 'px', backgroundColor: b.color, borderRadius: '6px 6px 0 0' }"
            ></div>
            <small class="fw-bold text-dark fs-8 mt-2">{{ b.type }}</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  bloodDemand: {
    type: Array,
    required: true
  }
});

const getImageUrl = (fileName) => {
  return new URL(`../../../assets/images/${fileName}`, import.meta.url).href;
};
</script>

<style scoped>
.fs-5 { font-size: 1.15rem; }
.fs-6 { font-size: 1rem; }
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }

.min-chart-width {
  min-width: 500px;
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

.header-icon {
  width: 22px;
  height: 22px;
}
@media (min-width: 768px) {
  .header-icon {
    width: 26px;
    height: 26px;
  }
}

.bar-column {
  transition: height 0.3s ease;
}
.dir-rtl { direction: rtl; }
</style>
