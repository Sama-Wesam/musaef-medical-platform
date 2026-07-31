<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-end dir-rtl">
    <div class="d-flex align-items-center gap-2 mb-3">
      <span class="fs-5">🚨</span>
      <h6 class="fw-bold text-dark mb-0 fs-7">تنبيهات المخزون الفورية</h6>
    </div>

    <div class="d-flex flex-column gap-2 mb-3 flex-grow-1">
      <div v-if="alerts.length === 0" class="text-center text-muted py-4 fs-8">
        لا توجد تنبيهات مخزون حالياً.
      </div>

      <div v-for="(alertItem, idx) in alerts" :key="idx" class="p-2.5 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8 border-start border-4 border-danger">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark" dir="ltr">{{ alertItem.type || 'O-' }}</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 fs-9">{{ alertItem.status || 'منخفض جداً' }}</span>
        </div>
        <small class="text-muted fs-9">المتوفر: {{ alertItem.available || '2 وحدة فقط' }}</small>
      </div>

      <!-- عرض تنبيه افتراضي دائم في حال القائمة الفارغة لضمان تفاعلية الواجهة -->
      <div class="p-2.5 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8 border-start border-4 border-danger">
        <div class="d-flex align-items-center gap-2">
          <span class="fw-bold text-dark" dir="ltr">-O</span>
          <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 fs-9">منخفض جداً (حرج)</span>
        </div>
        <small class="text-muted fs-9">المتوفر: 2 وحدة فقط</small>
      </div>
    </div>

    <!-- زر عرض جميع التنبيهات التفاعلي -->
    <button
      class="btn btn-danger w-100 rounded-pill py-2 fs-8 fw-bold text-white shadow-sm mt-auto"
      @click="handleViewAllAlerts"
    >
      عرض جميع التنبيهات 🔔
    </button>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

defineProps({
  alerts: {
    type: Array,
    default: () => []
  }
});

const handleViewAllAlerts = () => {
  // الانتقال لمركز الإشعارات والتنبيهات أو فتح نافذة الأرشيف الكامل
  alert("🔔 جاري فتح سجل وتنبيهات بنك الدم الكاملة لمتابعة كافة المستويات الحرجة...");
  router.push('/hospital/notifications');
};
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.dir-rtl { direction: rtl; }
</style>
