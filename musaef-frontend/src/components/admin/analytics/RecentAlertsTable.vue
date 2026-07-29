<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-end d-flex flex-column justify-content-between dir-rtl">
    <div>
      <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
        <span class="fs-5">🔔</span>
        <h5 class="fw-bold text-dark mb-0 fs-6">آخر التنبيهات</h5>
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

    <a href="#" class="text-danger text-decoration-none fs-8 fw-bold mt-3 d-inline-block text-center">
      عرض جميع التنبيهات &gt;
    </a>
  </div>
</template>

<script setup>
defineProps({
  alertsList: {
    type: Array,
    default: () => []
  }
});
</script>

<style scoped>
.fs-6 { font-size: 1.05rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }

.min-w-table {
  min-width: 380px;
}

.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.bg-light-subtle { background-color: #f9fafb !important; }
.dir-rtl { direction: rtl; }
</style>
