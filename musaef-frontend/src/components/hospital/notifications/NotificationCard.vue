<template>
  <div
    class="card border-0 shadow-sm p-3 p-md-4 notification-card position-relative rounded-4 transition-all"
    :class="[
      isUnread ? 'bg-unread-custom' : 'bg-white',
      currentLanguage === 'ar' ? 'dir-rtl' : 'dir-ltr'
    ]"
    @click="handleCardClick"
  >
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">

      <!-- الأيقونة والعنوان والوصف -->
      <div class="d-flex align-items-start align-items-sm-center gap-3 flex-grow-1 w-100" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <div class="icon-box rounded-circle d-flex align-items-center justify-content-center" :class="getIconBg(item.type)">
          <i :class="['bi', getIconClass(item.type), 'fs-5']"></i>
        </div>
        <div class="flex-grow-1 min-w-0">
          <h6 class="fw-bold text-dark mb-1 fs-8 fs-md-7 line-height-base text-break">
            {{ displayTitle }}
          </h6>
          <p class="text-muted mb-0 fs-9 fs-md-8 text-break">
            {{ displayDescription }}
          </p>
        </div>
      </div>

      <!-- الوقت والحالة وخيارات الكرت -->
      <div class="d-flex align-items-center justify-content-between justify-content-sm-end w-100 w-sm-auto gap-3 pt-2 pt-sm-0 border-top border-top-sm-0 border-light-subtle">
        <div class="d-flex align-items-center gap-2 text-muted fs-9 fs-md-8">
          <span class="text-nowrap" :title="formattedDateTooltip">{{ displayTime }}</span>

          <!-- نقطة التنبيه لغير المقروء -->
          <span v-if="isUnread" class="status-dot rounded-circle bg-danger" :title="t('unreadTooltip')"></span>
          <span v-else class="badge read-badge rounded-pill fw-medium px-2 py-1">{{ t('readBadge') }}</span>
        </div>

        <!-- زر القائمة التفاعلي -->
        <div class="dropdown" @click.stop>
          <button
            class="btn btn-link text-muted p-0 text-decoration-none fs-5 dot-menu-btn me-sm-1"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            :aria-label="t('options')"
          >
            ⋮
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 fs-8">
            <li v-if="isUnread">
              <button class="dropdown-item d-flex align-items-center gap-2" @click="$emit('markRead', item.id)">
                <i class="bi bi-check2-all text-success"></i>
                <span>{{ t('markAsRead') }}</span>
              </button>
            </li>
            <li>
              <button class="dropdown-item d-flex align-items-center gap-2 text-danger" @click="$emit('delete', item.id)">
                <i class="bi bi-trash"></i>
                <span>{{ t('delete') }}</span>
              </button>
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  item: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['markRead', 'delete', 'click']);

const currentLanguage = ref(localStorage.getItem('musaef_lang') || 'ar');

const updateLocale = () => {
  currentLanguage.value = localStorage.getItem('musaef_lang') || 'ar';
};

onMounted(() => {
  window.addEventListener('storage', updateLocale);
  window.addEventListener('language-changed', updateLocale);
});

onUnmounted(() => {
  window.removeEventListener('storage', updateLocale);
  window.removeEventListener('language-changed', updateLocale);
});

const translations = {
  ar: {
    unreadTooltip: 'غير مقروء',
    readBadge: 'مقروء',
    options: 'خيارات',
    markAsRead: 'تحديد كمقروء',
    delete: 'حذف الإشعار'
  },
  en: {
    unreadTooltip: 'Unread',
    readBadge: 'Read',
    options: 'Options',
    markAsRead: 'Mark as read',
    delete: 'Delete notification'
  }
};

const t = (key) => translations[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const isUnread = computed(() => {
  if (props.item.is_read !== undefined) return !props.item.is_read;
  if (props.item.read !== undefined) return !props.item.read;
  if (props.item.unread !== undefined) return props.item.unread;
  return false;
});

const displayTitle = computed(() => {
  if (currentLanguage.value === 'en') {
    return props.item.title_en || props.item.title || '';
  }
  return props.item.title_ar || props.item.title || '';
});

const displayDescription = computed(() => {
  if (currentLanguage.value === 'en') {
    return props.item.description_en || props.item.desc || props.item.message || '';
  }
  return props.item.description_ar || props.item.desc || props.item.message || '';
});

const displayTime = computed(() => {
  if (props.item.time) return props.item.time;
  if (!props.item.created_at) return '';

  const date = new Date(props.item.created_at);
  if (isNaN(date.getTime())) return props.item.created_at;

  const now = new Date();
  const diffInMinutes = Math.floor((now - date) / 60000);

  if (diffInMinutes < 1) return currentLanguage.value === 'en' ? 'Just now' : 'الآن';
  if (diffInMinutes < 60) return currentLanguage.value === 'en' ? `${diffInMinutes}m ago` : `منذ ${diffInMinutes} دقيقة`;

  const diffInHours = Math.floor(diffInMinutes / 60);
  if (diffInHours < 24) return currentLanguage.value === 'en' ? `${diffInHours}h ago` : `منذ ${diffInHours} ساعة`;

  return date.toLocaleDateString(currentLanguage.value === 'en' ? 'en-US' : 'ar-SA');
});

const formattedDateTooltip = computed(() => {
  if (!props.item.created_at) return '';
  const date = new Date(props.item.created_at);
  return isNaN(date.getTime()) ? props.item.created_at : date.toLocaleString();
});

const getIconBg = (type) => {
  const t = String(type || '').toLowerCase();
  if (t === 'emergency' || t === 'danger' || t === 'critical' || t === 'fraud') return 'bg-danger-light text-danger';
  if (t === 'success' || t === 'donor_response') return 'bg-success-light text-success';
  if (t === 'warning' || t === 'alert') return 'bg-warning-light text-warning';
  return 'bg-primary-light text-primary';
};

const getIconClass = (type) => {
  const t = String(type || '').toLowerCase();
  if (t === 'emergency' || t === 'danger' || t === 'critical' || t === 'fraud') return 'bi-exclamation-triangle-fill';
  if (t === 'success' || t === 'donor_response') return 'bi-check-circle-fill';
  if (t === 'warning' || t === 'alert') return 'bi-bell-fill';
  return 'bi-info-circle-fill';
};

const handleCardClick = () => {
  if (isUnread.value) {
    emit('markRead', props.item.id);
  }
  emit('click', props.item);
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
.bg-warning-light { background-color: #fffbe6 !important; color: #d97706 !important; }
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

.notification-card { cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; }
.notification-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important; }
.dot-menu-btn { font-size: 1.25rem; color: #94a3b8; line-height: 1; }

.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
.transition-all { transition: all 0.2s ease-in-out; }
</style>
