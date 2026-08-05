<template>
  <div
    class="card border-0 shadow-sm p-3 p-md-4 notification-card position-relative rounded-4"
    :class="[!item.read && !item.unread ? 'bg-unread-custom' : 'bg-white', currentLanguage === 'ar' ? 'dir-rtl' : 'dir-ltr']"
  >
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">

      <!-- الأيقونة والعنوان والوصف -->
      <div class="d-flex align-items-start align-items-sm-center gap-3 flex-grow-1 w-100" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <div class="icon-box rounded-circle d-flex align-items-center justify-content-center" :class="getIconBg(item.type)">
          <i :class="['bi', getIconClass(item.type), 'fs-5']"></i>
        </div>
        <div class="flex-grow-1 min-w-0">
          <h6 class="fw-bold text-dark mb-1 fs-8 fs-md-7 line-height-base text-break">{{ item.title }}</h6>
          <p class="text-muted mb-0 fs-9 fs-md-8 text-break">{{ item.desc || item.message }}</p>
        </div>
      </div>

      <!-- الوقت والحالة وخيارات الكرت -->
      <div class="d-flex align-items-center justify-content-between justify-content-sm-end w-100 w-sm-auto gap-3 pt-2 pt-sm-0 border-top border-top-sm-0 border-light-subtle">
        <div class="d-flex align-items-center gap-2 text-muted fs-9 fs-md-8">
          <span class="text-nowrap">{{ item.time || item.created_at }}</span>

          <!-- نقطة التنبيه لغير المقروء -->
          <span v-if="!item.read && !item.unread" class="status-dot rounded-circle bg-danger" :title="t('unreadTooltip')"></span>

          <span v-else class="badge read-badge rounded-pill fw-medium px-2 py-1">{{ t('readBadge') }}</span>
        </div>
        <button class="btn btn-link text-muted p-0 text-decoration-none fs-5 dot-menu-btn me-sm-1" :aria-label="t('options')">⋮</button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: {
    unreadTooltip: 'غير مقروء',
    readBadge: 'مقروء',
    options: 'خيارات'
  },
  en: {
    unreadTooltip: 'Unread',
    readBadge: 'Read',
    options: 'Options'
  }
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

defineProps({
  item: {
    type: Object,
    required: true
  }
});

const getIconBg = (type) => {
  if (type === 'emergency' || type === 'danger') return 'bg-danger-light text-danger';
  if (type === 'success') return 'bg-success-light text-success';
  return 'bg-primary-light text-primary';
};

const getIconClass = (type) => {
  if (type === 'emergency' || type === 'danger') return 'bi-exclamation-triangle-fill';
  if (type === 'success') return 'bi-check-circle-fill';
  return 'bi-bell-fill';
};
</script>

<style scoped>
.fs-8 { font-size: 0.825rem; }
.fs-9 { font-size: 0.75rem; }

@media (min-width: 768px) {
  .fs-md-7 { font-size: 0.95rem; }
  .fs-md-8 { font-size: 0.85rem; }
}

.line-height-base { line-height: 1.5; }

.icon-box {
  width: 42px;
  height: 42px;
  flex-shrink: 0;
}
@media (min-width: 768px) {
  .icon-box {
    width: 50px;
    height: 50px;
  }
}

.bg-success-light { background-color: #e8f8f0 !important; color: #16a34a !important; }
.bg-danger-light { background-color: #fdf2f2 !important; color: #dc2626 !important; }
.bg-primary-light { background-color: #eff6ff !important; color: #2563eb !important; }

.bg-unread-custom {
  background-color: #fffbfa !important;
  border: 1px solid #fde8e8 !important;
}

.status-dot { width: 8px; height: 8px; display: inline-block; flex-shrink: 0; }

.read-badge {
  background-color: #f1f5f9;
  color: #64748b;
  font-size: 0.7rem;
}

.notification-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.notification-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important; }
.dot-menu-btn { font-size: 1.25rem; color: #94a3b8; line-height: 1; }

.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
