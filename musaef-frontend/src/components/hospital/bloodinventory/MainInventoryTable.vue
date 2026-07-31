<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-end dir-rtl">
    <div class="text-end mb-3">
      <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">مخزون بنك الدم</h5>
    </div>
    <div class="table-responsive">
      <table class="table align-middle text-center mb-0 fs-8 border-0 min-w-table">
        <thead class="bg-light border-bottom">
          <tr class="text-muted text-nowrap">
            <th class="py-2 text-end">الفصيلة المطلوبة</th>
            <th class="py-2">عدد الوحدات المتوفرة</th>
            <th class="py-2">الحد الأدنى المطلوب</th>
            <th class="py-2">الحالة</th>
            <th class="py-2">عدد المستجيبين</th>
            <th class="py-2">الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.type" class="border-bottom-subtle text-nowrap">
            <td class="fw-bold text-dark py-2.5 fs-7 text-end">{{ item.type }}</td>
            <td class="fw-bold text-dark py-2.5">{{ item.available }}</td>
            <td class="text-muted py-2.5">{{ item.minRequired }}</td>
            <td class="py-2.5">
              <span :class="['badge rounded-pill px-3 py-1 fs-9', item.statusClass]">
                {{ item.status }}
              </span>
            </td>
            <td class="py-2.5">
              <div class="d-flex align-items-center justify-content-center gap-2">
                <span class="fs-9 text-muted fw-bold">{{ item.percentage }}%</span>
                <div class="progress flex-grow-1" style="height: 4px; min-width: 50px; max-width: 80px;">
                  <div
                    class="progress-bar"
                    :class="item.progressClass"
                    :style="{ width: Math.min(item.percentage, 100) + '%' }"
                  ></div>
                </div>
              </div>
            </td>
            <td class="py-2.5">
              <button class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 px-md-3 fs-9 text-nowrap fw-bold" @click="handleViewDetails(item)">
                عرض التفاصيل
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
defineProps({
  items: {
    type: Array,
    required: true
  }
});

const handleViewDetails = (item) => {
  alert(`📊 تفاصيل مخزون فصيلة الدم (${item.type}):\n- الوحدات المتوفرة: ${item.available}\n- الحد الأدنى المطلوب: ${item.minRequired}\n- الحالة: ${item.status}\n- نسبة التغطية: ${item.percentage}%`);
};
</script>

<style scoped>
.fs-7 { font-size: 0.88rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.dir-rtl { direction: rtl; }
.min-w-table { min-width: 580px; }
</style>
