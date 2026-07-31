<template>
  <div class="emergency-filters-header mb-4" dir="rtl">
    <!-- الهيدر العلوي وعنوان الصفحة مع زر إنشاء طلب طارئ بارز -->
    <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-3">
      <div class="text-end">
        <h5 class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-start gap-2 fs-6 fs-md-5">
          <img src="@/assets/icons/Frame 2147225776.png" alt="emergency icon" width="22" height="22" class="header-icon" />
          <span>إدارة النداءات الطارئة</span>
        </h5>
        <p class="text-muted fs-8 mb-0 text-end">عرض وإدارة جميع النداءات الطارئة بشكل لحظي</p>
      </div>

      <!-- أزرار الإجراءات في اليسار: إنشاء طلب + تصدير التقرير -->
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <button
          @click="$emit('createEmergency')"
          class="btn btn-danger rounded-pill px-3 py-1.5 fs-8 fw-bold text-white d-flex align-items-center gap-2 shadow-sm text-nowrap transition-btn"
        >
          <i class="bi bi-plus-lg"></i>
          <span>إنشاء طلب طارئ جديد</span>
        </button>

        <button
          @click="$emit('export')"
          class="btn btn-outline-secondary bg-white border-secondary-subtle rounded-pill px-3 py-1.5 fs-8 fw-bold text-dark d-flex align-items-center gap-2 shadow-sm text-nowrap transition-btn"
        >
          <span>تصدير التقرير</span>
          <span>📥</span>
        </button>
      </div>
    </div>

    <!-- أزرار الفلترة الثلاثة التفاعلية -->
    <div class="d-flex align-items-center justify-content-start gap-2 overflow-x-auto pb-2 filter-scroll-container">

      <!-- زر الكل -->
      <button
        class="btn filter-pill-btn rounded-4 px-3.5 py-1.5 fs-8 fw-bold d-flex align-items-center gap-2 text-nowrap"
        :class="filter === 'all' ? 'active-pill-red' : 'default-pill'"
        @click="$emit('update:filter', 'all')"
      >
        <span>الكل</span>
        <span class="badge-count-red">{{ counts.all }}</span>
        <i class="bi bi-chevron-left fs-9 ms-1"></i>
      </button>

      <!-- زر قيد التغطية -->
      <button
        class="btn filter-pill-btn rounded-4 px-3.5 py-1.5 fs-8 fw-bold d-flex align-items-center gap-2 text-nowrap"
        :class="filter === 'covering' ? 'active-pill-orange' : 'default-pill'"
        @click="$emit('update:filter', 'covering')"
      >
        <span>قيد التغطية</span>
        <span class="badge-count-orange">{{ counts.covering }}</span>
      </button>

      <!-- زر مكتملة -->
      <button
        class="btn filter-pill-btn rounded-4 px-3.5 py-1.5 fs-8 fw-bold d-flex align-items-center gap-2 text-nowrap"
        :class="filter === 'completed' ? 'active-pill-green' : 'default-pill'"
        @click="$emit('update:filter', 'completed')"
      >
        <span>مكتملة</span>
        <span class="badge-count-green">{{ counts.completed }}</span>
      </button>

    </div>
  </div>
</template>

<script setup>
defineProps({
  filter: {
    type: String,
    default: 'all'
  },
  counts: {
    type: Object,
    default: () => ({ all: 12, covering: 4, completed: 8 })
  }
});

defineEmits(['update:filter', 'export', 'createEmergency']);
</script>

<style scoped>
.fs-8 { font-size: 0.85rem; }
.fs-9 { font-size: 0.72rem; }

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

.transition-btn {
  transition: all 0.2s ease-in-out;
}
.transition-btn:hover {
  transform: translateY(-1px);
}

.filter-pill-btn {
  height: 42px;
  background-color: #ffffff;
  border: 1px solid #e2e8f0;
  color: #334155;
  transition: all 0.2s ease-in-out;
  box-shadow: 0 1px 2px rgba(0,0,0,0.03);
  cursor: pointer;
}

.default-pill:hover {
  border-color: #cbd5e1;
  background-color: #f8fafc;
}

.active-pill-red {
  border-color: #ef4444 !important;
  color: #dc2626 !important;
  background-color: #ffffff !important;
}

.active-pill-orange {
  border-color: #f97316 !important;
  color: #ea580c !important;
  background-color: #ffffff !important;
}

.active-pill-green {
  border-color: #22c55e !important;
  color: #16a34a !important;
  background-color: #ffffff !important;
}

.badge-count-red {
  background-color: #ef4444;
  color: #ffffff;
  font-size: 0.75rem;
  font-weight: 700;
  min-width: 22px;
  height: 22px;
  padding: 0 4px;
  border-radius: 11px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.badge-count-orange {
  background-color: #f97316;
  color: #ffffff;
  font-size: 0.75rem;
  font-weight: 700;
  min-width: 22px;
  height: 22px;
  padding: 0 4px;
  border-radius: 11px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.badge-count-green {
  background-color: #22c55e;
  color: #ffffff;
  font-size: 0.75rem;
  font-weight: 700;
  min-width: 22px;
  height: 22px;
  padding: 0 4px;
  border-radius: 11px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.filter-scroll-container {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.filter-scroll-container::-webkit-scrollbar {
  display: none;
}
</style>
