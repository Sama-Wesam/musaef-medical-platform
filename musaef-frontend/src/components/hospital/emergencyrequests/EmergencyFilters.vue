<template>
  <div class="emergency-filters-header mb-4">
    <!-- الهيدر العلوي وعنوان الصفحة -->
    <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-3">
      <div class="text-end">
        <h5 class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-start gap-2 fs-6 fs-md-5">
          <img src="@/assets/images/Frame 2147225776.png" alt="emergency icon" width="22" height="22" class="header-icon" />
          <span>إدارة النداءات الطارئة</span>
        </h5>
        <p class="text-muted fs-8 mb-0 text-end">عرض وإدارة جميع النداءات الطارئة بشكل لحظي مباشرة</p>
      </div>

      <!-- زر تصدير التقرير في اليسار -->
      <button
        @click="$emit('export')"
        class="btn btn-outline-secondary bg-white border-secondary-subtle rounded-pill px-3 py-1.5 fs-8 fw-bold text-dark d-flex align-items-center gap-2 shadow-sm text-nowrap"
      >
        <span>تصدير التقرير</span>
        <span>📥</span>
      </button>
    </div>

    <!-- أزرار الفلترة أسفل الوصف -->
    <div class="d-flex align-items-center justify-content-start gap-2 overflow-x-auto pb-2 filter-scroll-container">
      <button
        class="btn filter-btn rounded-pill px-3 px-md-4 py-1.5 fs-8 fw-bold d-flex align-items-center gap-1.5 text-nowrap"
        :class="filter === 'all' ? 'btn-danger text-white' : 'btn-outline-secondary bg-white text-dark border-secondary-subtle'"
        @click="$emit('update:filter', 'all')"
      >
        <span>الكل</span>
        <span class="badge rounded-circle px-1.5 py-0.5 fs-9" :class="filter === 'all' ? 'bg-white text-danger' : 'bg-secondary text-white'">18</span>
      </button>

      <button
        class="btn filter-btn rounded-pill px-3 px-md-4 py-1.5 fs-8 fw-bold d-flex align-items-center gap-1.5 text-nowrap"
        :class="filter === 'covering' ? 'btn-danger text-white' : 'btn-outline-secondary bg-white text-dark border-secondary-subtle'"
        @click="$emit('update:filter', 'covering')"
      >
        <span>قيد التغطية</span>
        <span class="badge bg-warning text-dark rounded-circle px-1.5 py-0.5 fs-9">4</span>
      </button>

      <button
        class="btn filter-btn rounded-pill px-3 px-md-4 py-1.5 fs-8 fw-bold d-flex align-items-center gap-1.5 text-nowrap"
        :class="filter === 'completed' ? 'btn-danger text-white' : 'btn-outline-secondary bg-white text-dark border-secondary-subtle'"
        @click="$emit('update:filter', 'completed')"
      >
        <span>مكتملة</span>
        <span class="badge bg-success text-white rounded-circle px-1.5 py-0.5 fs-9">2</span>
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  filter: {
    type: String,
    default: 'all'
  }
});

defineEmits(['update:filter', 'export']);
</script>

<style scoped>
.fs-8 { font-size: 0.85rem; }
.fs-9 { font-size: 0.72rem; }

.filter-btn {
  height: 38px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.header-icon {
  width: 20px;
  height: 20px;
}
@media (min-width: 768px) {
  .header-icon {
    width: 24px;
    height: 24px;
  }
}

.filter-scroll-container {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.filter-scroll-container::-webkit-scrollbar {
  display: none;
}
</style>
