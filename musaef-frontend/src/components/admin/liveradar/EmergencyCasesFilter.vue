<template>
  <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4 overflow-x-auto pb-1 dir-rtl filter-scroll-container">
    <button
      class="btn rounded-pill px-3 py-1 fs-8 fw-bold text-nowrap"
      :class="filter === 'all' ? 'btn-danger text-white' : 'btn-light text-dark bg-white border'"
      @click="$emit('update:filter', 'all')"
    >
      الكل
    </button>

    <button
      class="btn rounded-pill px-3 py-1 fs-8 fw-bold border d-flex align-items-center gap-1.5 text-nowrap"
      :class="filter === 'critical' ? 'btn-danger text-white' : 'btn-light text-dark bg-white'"
      @click="$emit('update:filter', 'critical')"
    >
      <span class="dot-badge bg-danger"></span>
      <span>حرجة</span>
    </button>

    <button
      class="btn rounded-pill px-3 py-1 fs-8 fw-bold border d-flex align-items-center gap-1.5 text-nowrap"
      :class="filter === 'medium' ? 'btn-danger text-white' : 'btn-light text-dark bg-white'"
      @click="$emit('update:filter', 'medium')"
    >
      <span class="dot-badge bg-warning"></span>
      <span>متوسط</span>
    </button>

    <button
      class="btn rounded-pill px-3 py-1 fs-8 fw-bold border d-flex align-items-center gap-1.5 text-nowrap"
      :class="filter === 'low' ? 'btn-danger text-white' : 'btn-light text-dark bg-white'"
      @click="$emit('update:filter', 'low')"
    >
      <span class="dot-badge bg-success"></span>
      <span>منخفض</span>
    </button>
  </div>
</template>

<script setup>
defineProps({
  filter: {
    type: String,
    default: 'all'
  }
});

defineEmits(['update:filter']);
</script>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.dot-badge {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  display: inline-block;
  flex-shrink: 0;
}
.dir-rtl { direction: rtl; }

.filter-scroll-container {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.filter-scroll-container::-webkit-scrollbar {
  display: none;
}
</style>
