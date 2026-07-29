<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end">
    <!-- الهيدر الداخلي للمربع[cite: 40] -->
    <div class="d-flex align-items-center justify-content-start gap-2 mb-3 flex-wrap">
      <span class="text-danger fs-6">🏷️</span>
      <span class="fw-bold text-dark fs-8">تفاصيل الطلب:</span>
      <h6 class="fw-bold text-dark mb-0 fs-8">{{ request.request_code || request.code }}</h6>
      <span class="badge bg-danger text-white rounded-pill px-3 py-1 fs-9 ms-auto">{{ request.status || 'نشط' }}</span>
    </div>

    <!-- تفاصيل الطلب السفلية جهة اليمين[cite: 40] -->
    <div class="p-3 bg-light rounded-3 fs-8 d-flex flex-column gap-2 text-end">
      <div class="d-flex align-items-center justify-content-start gap-2 text-dark">
        <span>🏥</span>
        <span class="fw-medium text-break">{{ request.hospital_name || 'مستشفى الشفاء الطبي' }}</span>
      </div>
      <div class="d-flex align-items-center justify-content-start gap-2 text-dark flex-wrap">
        <span>🩸</span>
        <span>الفصيلة:</span>
        <span class="fw-bold text-danger">{{ request.blood_type?.name || request.bloodType }}</span>
        <span class="mx-1">|</span>
        <span>عدد الوحدات:</span>
        <span class="fw-bold text-dark">{{ request.units }}</span>
      </div>
      <div class="d-flex align-items-center justify-content-start gap-2 text-dark">
        <span>🕒</span>
        <span class="fw-medium" dir="ltr">{{ request.time || '14:30 - 15-05-2026' }}</span>
      </div>
      <div class="d-flex align-items-center justify-content-start gap-2 text-danger">
        <span>⚠️</span>
        <span class="fw-medium text-break">{{ request.description || 'حالة طارئة عاجلة' }}</span>
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
