<template>
  <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle text-center mb-0 fs-8 border-0 min-w-table">
        <thead class="bg-light border-bottom text-muted fw-bold">
          <tr>
            <th class="py-3 text-end pe-3 pe-md-4 text-nowrap">رقم الطلب</th>
            <th class="py-3 text-nowrap">الفصيلة المطلوبة</th>
            <th class="py-3 text-nowrap">عدد الوحدات</th>
            <th class="py-3 text-nowrap">مستوى الخطورة</th>
            <th class="py-3 text-nowrap">عدد المستجيبين</th>
            <th class="py-3 ps-3 ps-md-4 text-nowrap">حالة الطلب</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="req in requests"
            :key="req.id"
            @click="$emit('selectRequest', req)"
            class="cursor-pointer text-nowrap"
            :class="selectedRequestId === req.id ? 'table-active' : ''"
          >
            <td class="fw-bold text-dark py-3 text-end pe-3 pe-md-4">{{ req.code }}</td>
            <td class="py-3">
              <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 px-md-3 py-1 fw-bold fs-8">{{ req.bloodType }}</span>
            </td>
            <td class="fw-bold text-dark py-3">{{ req.units }}</td>
            <td class="py-3">
              <span :class="['badge rounded-pill px-2.5 px-md-3 py-1 fs-8', req.urgencyBadge]">
                {{ req.urgency }}
              </span>
            </td>
            <td class="py-3">
              <div class="d-flex align-items-center justify-content-center gap-2 px-2 px-md-3">
                <span class="fw-bold text-dark fs-9">{{ req.coverage }}%</span>
                <div class="progress flex-grow-1" style="height: 4px; min-width: 45px; max-width: 80px;">
                  <div class="progress-bar bg-danger" :style="{ width: req.coverage + '%' }"></div>
                </div>
              </div>
            </td>
            <td class="py-3 ps-3 ps-md-4">
              <span :class="['badge rounded-pill px-2.5 px-md-3 py-1 fs-8', req.statusBadge]">
                {{ req.status }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
defineProps({
  requests: {
    type: Array,
    required: true
  },
  selectedRequestId: {
    type: [Number, String],
    default: null
  }
});

defineEmits(['selectRequest']);
</script>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.cursor-pointer { cursor: pointer; }
.min-w-table { min-width: 600px; }
.table-active { background-color: #fee2e2 !important; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
</style>
