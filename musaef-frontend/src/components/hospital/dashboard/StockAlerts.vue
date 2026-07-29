<template>
  <div class="dashboard-card h-100 d-flex flex-column justify-content-between">
    <div>
      <div class="d-flex justify-content-start align-items-center gap-2 mb-3">
        <img src="@/assets/images/Group.png" width="22" alt="تنبيهات">
        <h5 class="fw-bold mb-0 fs-6 fs-md-5">تنبيهات المخزون</h5>
      </div>
      <div class="alerts-list">
        <div v-for="(alert, index) in alerts" :key="index" class="alert-row">
          <div class="d-flex align-items-center gap-2 gap-sm-3">
            <img src="@/assets/images/healthicons_blood-drop-outline.png" width="20" alt="دم">
            <strong class="text-danger">{{ alert.blood_type }}</strong>
            <span class="badge px-2 px-sm-3 py-1 rounded-pill" :class="alert.level === 'critical' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning'">
              {{ alert.status || 'منخفض' }}
            </span>
          </div>
          <small class="text-muted">{{ alert.units }} وحدات فقط</small>
        </div>

        <div v-if="!alerts.length" class="text-center text-muted py-4 small">
          لا توجد تنبيهات مخزون حالياً.
        </div>
      </div>
    </div>
    <button class="btn btn-danger-subtle text-danger fw-bold w-100 py-2 py-md-2.5 rounded-3 border-0 mt-4 fs-8 fs-md-7">
      عرض جميع التنبيهات
    </button>
  </div>
</template>

<script setup>
defineProps({
  alerts: {
    type: Array,
    default: () => []
  }
});
</script>

<style scoped>
.dashboard-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
@media (min-width: 768px) { .dashboard-card { padding: 20px; } }

.alerts-list { display: flex; flex-direction: column; gap: 8px; }
.alert-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; background: #f8fafc; border-radius: 12px; }
.alert-row strong { font-size: 13px; }
.alert-row small { font-size: 11px; }

.badge { font-size: 10px; }
@media (min-width: 576px) { .badge { font-size: 11px; } }

.btn-danger-subtle { background: #FDECEC; transition: 0.2s; }
.btn-danger-subtle:hover { background: #fcd3d3; }
.fs-8 { font-size: 0.85rem; }
.fs-md-7 { font-size: 0.95rem; }
</style>
