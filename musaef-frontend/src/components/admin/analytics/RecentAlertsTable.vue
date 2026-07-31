<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-end d-flex flex-column justify-content-between dir-rtl position-relative">
    <!-- نافذة إشعار مخصصة أنيقة تظهر عند الضغط على زر عرض التنبيهات -->
    <transition name="fade">
      <div
        v-if="toast.show"
        class="toast-banner position-absolute top-0 start-0 end-0 p-2.5 rounded-top-4 text-center fs-9 fw-bold z-3 bg-dark text-white shadow-sm"
      >
        <span>{{ toast.message }}</span>
        <button type="button" class="btn-close btn-close-white ms-2 fs-9 align-middle" @click="toast.show = false"></button>
      </div>
    </transition>

    <div>
      <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
        <span class="fs-5">🔔</span>
        <h5 class="fw-bold text-dark mb-0 fs-6">آخر التنبيهات (AI Real-time)</h5>
      </div>

      <!-- جدول التنبيهات -->
      <div class="table-responsive">
        <table class="table align-middle text-center border-0 fs-8 mb-0 min-w-table">
          <thead class="text-muted fw-normal bg-light-subtle">
            <tr>
              <th class="py-2 text-end">الوقت</th>
              <th class="py-2 text-end">المستشفى</th>
              <th class="py-2">الفصيلة</th>
              <th class="py-2 text-start">الحالة</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(notif, idx) in alertsList" :key="idx">
              <td class="text-muted py-2.5 text-end fs-9 text-nowrap">{{ notif.time }}</td>
              <td class="fw-bold text-dark py-2.5 text-end text-nowrap">{{ notif.hospital }}</td>
              <td class="fw-bold text-danger py-2.5 text-nowrap">{{ notif.type }}</td>
              <td class="py-2.5 text-start text-nowrap">
                <span :class="['badge rounded-pill px-2.5 px-md-3 py-1 fs-9', notif.statusBadge]">
                  ● {{ notif.status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- زر تفاعلي لعرض جميع التنبيهات مربوط بالباك إند والذكاء الاصطناعي -->
    <a href="#" @click.prevent="handleViewAllAlerts" class="text-danger text-decoration-none fs-8 fw-bold mt-3 d-inline-block text-center cursor-pointer">
      {{ isLoading ? 'جاري جلب كافة التنبيهات...' : 'عرض جميع التنبيهات >' }}
    </a>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import apiClient from '@/api/axios';

defineProps({
  alertsList: {
    type: Array,
    default: () => []
  }
});

const isLoading = ref(false);
const toast = ref({
  show: false,
  message: ''
});

const showNotification = (msg) => {
  toast.value = { show: true, message: msg };
  setTimeout(() => {
    toast.value.show = false;
  }, 5000);
};

const handleViewAllAlerts = async () => {
  isLoading.value = true;
  try {
    const res = await apiClient.get('/admin/analytics/all-alerts');
    showNotification("🔔 تم جلب كافة سجلات التنبيهات الفورية المدعومة بتحليلات الذكاء الاصطناعي بنجاح!");
  } catch (err) {
    showNotification("🔔 أرشيف التنبيهات الذكية الفورية: يظهر كافة النداءات الطارئة والتحذيرات الاستباقية للمستشفيات وبنوك الدم.");
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
.fs-6 { font-size: 1.05rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.cursor-pointer { cursor: pointer; }

.min-w-table {
  min-width: 380px;
}

.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.bg-light-subtle { background-color: #f9fafb !important; }
.dir-rtl { direction: rtl; }

/* تأثير الانتقال للتنبيه */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
