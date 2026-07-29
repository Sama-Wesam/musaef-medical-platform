<template>
  <div class="logs-tab-content dir-rtl">
    <!-- شريط الأدوات: قسم سجل العمليات -->
    <div class="card border-0 shadow-sm p-3 rounded-4 bg-white mb-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2 gap-md-3 flex-grow-1 justify-content-start flex-column flex-sm-row">
          <div class="position-relative w-100 flex-grow-1">
            <input
              type="text"
              class="form-control border-light-subtle rounded-3 pe-4 ps-5 fs-8 text-end bg-light-subtle"
              placeholder="ابحث في تفاصيل السجل أو المستخدم..."
              :value="searchQuery"
              @input="$emit('update:searchQuery', $event.target.value)"
            />
            <span class="position-absolute start-0 top-50 translate-middle-y ps-3 text-muted">🔍</span>
          </div>
          <select
            class="form-select border-light-subtle rounded-3 fs-8 text-center w-100 w-sm-auto"
            style="min-width: 150px;"
            :value="selectedFilter"
            @change="$emit('update:selectedFilter', $event.target.value)"
          >
            <option value="all">جميع العمليات</option>
            <option value="إضافة">إضافة بيانات</option>
            <option value="تعديل">تعديل بيانات</option>
            <option value="حذف">حذف / إلغاء</option>
            <option value="تسجيل دخول">تسجيل دخول</option>
          </select>
        </div>
        <div class="w-100 w-sm-auto text-end">
          <button
            class="btn btn-outline-secondary rounded-3 px-4 py-2 fs-8 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 bg-white border-light-subtle text-dark w-100 w-sm-auto"
            @click="accountsStore.exportLogsCSV()"
          >
            <span>📥 تصدير السجل (CSV)</span>
          </button>
        </div>
      </div>
    </div>

    <!-- جدول بيانات قسم سجل العمليات -->
    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-4 overflow-hidden">
      <div class="table-responsive">
        <div class="min-w-table">
          <div class="row text-center fw-bold text-dark fs-8 py-2 px-3 mb-3 border-bottom border-light text-nowrap">
            <div class="col-3 text-end ps-3">المستخدم / المنفذ</div>
            <div class="col-2">نوع العملية</div>
            <div class="col-3">تفاصيل الإجراء</div>
            <div class="col-2">عنوان IP</div>
            <div class="col-2">الوقت والتاريخ</div>
          </div>

          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="log in logsList"
              :key="log.id"
              class="row align-items-center text-center py-3 px-3 rounded-4 bg-light-subtle row-card border border-light-subtle transition-all text-nowrap"
            >
              <div class="col-3 text-end fw-bold text-dark fs-8 d-flex align-items-center justify-content-start gap-2 ps-3 min-w-0">
                <span class="log-icon-circle bg-primary-subtle text-primary rounded-circle p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px; height:28px;">📋</span>
                <div class="min-w-0">
                  <span class="d-block mb-0.5 text-truncate">{{ log.user }}</span>
                  <small class="text-muted fs-9 d-block font-normal text-truncate">{{ log.role }}</small>
                </div>
              </div>
              <div class="col-2">
                <span class="badge rounded-pill px-3 py-1 fs-9 fw-bold" :class="getActionBadgeClass(log.actionType)">
                  {{ log.actionType }}
                </span>
              </div>
              <div class="col-3 text-dark fs-8 text-truncate" :title="log.details">{{ log.details }}</div>
              <div class="col-2 text-muted fs-8 dir-ltr">{{ log.ipAddress }}</div>
              <div class="col-2 text-muted fs-9">{{ log.timestamp }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAccountsStore } from '@/stores/accountsStore';

const accountsStore = useAccountsStore();

defineProps({
  searchQuery: String,
  selectedFilter: String,
  logsList: Array
});

defineEmits(['update:searchQuery', 'update:selectedFilter']);

const getActionBadgeClass = (actionType) => {
  switch (actionType) {
    case 'إضافة': return 'bg-success-subtle text-success';
    case 'تعديل':
    case 'تأكيد': return 'bg-primary-subtle text-primary';
    case 'حذف': return 'bg-danger-subtle text-danger';
    case 'تسجيل دخول': return 'bg-info-subtle text-info-emphasis';
    default: return 'bg-secondary-subtle text-secondary';
  }
};
</script>

<style scoped>
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.bg-light-subtle { background-color: #f9fafb !important; }
.bg-primary-subtle { background-color: #dbeafe !important; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.row-card:hover { background-color: #f3f4f6 !important; }
.dir-rtl { direction: rtl; }
.min-w-table { min-width: 680px; }
</style>
