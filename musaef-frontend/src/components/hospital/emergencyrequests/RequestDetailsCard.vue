<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 fs-9 fw-bold">أولوية قصوى (Emergency Priority AI)</span>
      <span class="text-muted fs-9">{{ request.created_at || 'منذ قليل' }}</span>
    </div>
    <h5 class="fw-bold text-dark mb-1 fs-6">{{ request.hospital_name || 'مجمع الشفاء الطبي' }}</h5>
    <p class="text-muted fs-8 mb-3">📍 {{ request.location || 'غزة - الرمال' }}</p>

    <div class="row g-2 text-center bg-light p-2.5 rounded-3 fs-8 mb-2">
      <div class="col-6 border-end">
        <small class="text-muted d-block fs-9">الفصيلة المطلوبة</small>
        <strong class="text-danger fs-6" dir="ltr">{{ request.bloodType || request.blood_type }}</strong>
      </div>
      <div class="col-6">
        <small class="text-muted d-block fs-9">الوحدات المطلوبة</small>
        <strong class="text-dark fs-6">{{ request.units || request.units_required }} وحدات</strong>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  request: {
    type: Object,
    required: true
  }
});
</script>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
</style>
