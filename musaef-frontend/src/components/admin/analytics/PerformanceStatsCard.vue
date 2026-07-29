<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-end dir-rtl">
    <h5 class="fw-bold text-dark mb-3 mb-md-4 fs-6 text-center">إحصائيات الأداء</h5>

    <div class="d-flex flex-column gap-2.5 gap-md-3">
      <!-- متوسط وقت الاستجابة -->
      <div class="p-2.5 p-md-3 bg-light-subtle rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
          <img :src="getImageUrl('mingcute_time-line.png')" alt="time icon" width="32" height="32" class="flex-shrink-0" />
          <div class="text-start min-w-0">
            <span class="fw-bold text-danger fs-8 d-block mb-0.5 text-truncate">متوسط وقت الاستجابة</span>
            <small class="text-muted fs-9 text-truncate d-block">مستشفى شهداء الأقصى - دير البلح</small>
          </div>
        </div>
        <small class="text-muted fs-9 fw-bold text-nowrap ms-auto ms-sm-0">{{ performance?.avg_response_time || '18:24 دقيقة' }}</small>
      </div>

      <!-- نسبة تلبية الطلبات -->
      <div class="p-2.5 p-md-3 bg-light-subtle rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
          <img :src="getImageUrl('ei_check.png')" alt="check icon" width="32" height="32" class="flex-shrink-0" />
          <div class="text-start min-w-0">
            <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">نسبة تلبية الطلبات</span>
            <small class="text-muted fs-9 text-truncate d-block">المستشفى الأمريكي - غزة</small>
          </div>
        </div>
        <small class="text-muted fs-9 fw-bold text-nowrap ms-auto ms-sm-0">{{ performance?.fulfillment_rate || '92.6%' }}</small>
      </div>

      <!-- معدل التبرع اليومي -->
      <div class="p-2.5 p-md-3 bg-light-subtle rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
          <img :src="getImageUrl('mdi_drop.png')" alt="drop icon" width="32" height="32" class="flex-shrink-0" />
          <div class="text-start min-w-0">
            <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">معدل التبرع اليومي</span>
            <small class="text-muted fs-9 text-truncate d-block">المتبرع : أحمد محمد</small>
          </div>
        </div>
        <small class="text-muted fs-9 fw-bold text-nowrap ms-auto ms-sm-0">{{ performance?.daily_donation_rate || '1,234 وحدة' }}</small>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  performance: {
    type: Object,
    default: () => ({})
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

.bg-light-subtle { background-color: #f9fafb !important; }
.dir-rtl { direction: rtl; }
</style>
