<template>
  <div class="notifications-filters mb-4">
    <!-- أزرار الفلترة وزر تحديد الكل كمقروء -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 gap-md-3">

      <!-- أزرار الفلترة في اليمين -->
      <div class="d-flex align-items-center gap-2 overflow-x-auto filter-scroll-container">
        <!-- 1. زر الكل -->
        <button
          class="btn filter-btn rounded-pill px-3 py-1.5 fs-8 fw-bold text-nowrap"
          :class="selectedFilter === 'all' ? 'btn-danger text-white' : 'btn-outline-secondary bg-white text-dark border-secondary-subtle'"
          @click="$emit('update:selectedFilter', 'all')"
        >
          الكل
        </button>

        <!-- 2. زر غير المقروءة -->
        <button
          class="btn filter-btn rounded-pill px-3 py-1.5 fs-8 fw-bold d-flex align-items-center gap-2 text-nowrap"
          :class="selectedFilter === 'unread' ? 'btn-danger text-white' : 'btn-outline-secondary bg-white text-dark border-secondary-subtle'"
          @click="$emit('update:selectedFilter', 'unread')"
        >
          <span class="unread-dot rounded-circle" :class="selectedFilter === 'unread' ? 'bg-white' : 'bg-danger'"></span>
          غير المقروءة
        </button>

        <!-- 3. زر التنبيهات (تم تفعيله وإزالة السهم) -->
        <button
          class="btn filter-btn rounded-pill px-3 py-1.5 fs-8 fw-bold text-nowrap"
          :class="selectedFilter === 'alerts' ? 'btn-danger text-white' : 'btn-outline-secondary bg-white text-dark border-secondary-subtle'"
          @click="$emit('update:selectedFilter', 'alerts')"
        >
          التنبيهات
        </button>
      </div>

      <!-- زر تحديد الكل كمقروء في اليسار -->
      <button
        @click="$emit('markAllAsRead')"
        class="btn filter-btn btn-outline-secondary bg-white border-secondary-subtle rounded-pill px-3 py-1.5 fs-8 text-dark fw-bold d-flex align-items-center gap-1 shadow-sm hover-btn text-nowrap"
      >
        <span class="text-secondary fw-bold">✓</span>
        <span>تحديد الكل كمقروء</span>
      </button>

    </div>
  </div>
</template>

<script setup>
defineProps({
  selectedFilter: {
    type: String,
    default: 'all'
  }
});

defineEmits(['update:selectedFilter', 'markAllAsRead']);
</script>

<style scoped>
.fs-8 { font-size: 0.85rem; }
.unread-dot { width: 6px; height: 6px; display: inline-block; flex-shrink: 0; }
.hover-btn:hover { background-color: #f8f9fa !important; }

/* توحيد مقاس وأبعاد جميع الأزرار بدون أي أسهم إضافية */
.filter-btn {
  height: 38px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
}

.filter-scroll-container {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.filter-scroll-container::-webkit-scrollbar {
  display: none;
}
</style>
