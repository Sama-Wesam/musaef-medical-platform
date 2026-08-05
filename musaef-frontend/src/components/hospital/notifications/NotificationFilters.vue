<template>
  <div class="notifications-filters mb-4" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <!-- أزرار الفلترة وزر تحديد الكل كمقروء -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 gap-md-3">

      <!-- أزرار الفلترة -->
      <div class="d-flex align-items-center gap-2 overflow-x-auto filter-scroll-container">
        <!-- 1. زر الكل -->
        <button
          class="btn filter-btn rounded-pill px-3 py-1.5 fs-8 fw-bold text-nowrap"
          :class="selectedFilter === 'all' ? 'btn-danger text-white' : 'btn-outline-secondary bg-white text-dark border-secondary-subtle'"
          @click="$emit('update:selectedFilter', 'all')"
        >
          {{ t('all') }}
        </button>

        <!-- 2. زر غير المقروءة -->
        <button
          class="btn filter-btn rounded-pill px-3 py-1.5 fs-8 fw-bold d-flex align-items-center gap-2 text-nowrap"
          :class="selectedFilter === 'unread' ? 'btn-danger text-white' : 'btn-outline-secondary bg-white text-dark border-secondary-subtle'"
          @click="$emit('update:selectedFilter', 'unread')"
        >
          <span class="unread-dot rounded-circle" :class="selectedFilter === 'unread' ? 'bg-white' : 'bg-danger'"></span>
          {{ t('unread') }}
        </button>

        <!-- 3. زر التنبيهات -->
        <button
          class="btn filter-btn rounded-pill px-3 py-1.5 fs-8 fw-bold text-nowrap"
          :class="selectedFilter === 'alerts' ? 'btn-danger text-white' : 'btn-outline-secondary bg-white text-dark border-secondary-subtle'"
          @click="$emit('update:selectedFilter', 'alerts')"
        >
          {{ t('alerts') }}
        </button>
      </div>

      <!-- زر تحديد الكل كمقروء -->
      <button
        @click="$emit('markAllAsRead')"
        class="btn filter-btn btn-outline-secondary bg-white border-secondary-subtle rounded-pill px-3 py-1.5 fs-8 text-dark fw-bold d-flex align-items-center gap-1 shadow-sm hover-btn text-nowrap"
      >
        <span class="text-secondary fw-bold">✓</span>
        <span>{{ t('markAllRead') }}</span>
      </button>

    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: {
    all: 'الكل',
    unread: 'غير المقروءة',
    alerts: 'التنبيهات',
    markAllRead: 'تحديد الكل كمقروء'
  },
  en: {
    all: 'All',
    unread: 'Unread',
    alerts: 'Alerts',
    markAllRead: 'Mark All as Read'
  }
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

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

.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
