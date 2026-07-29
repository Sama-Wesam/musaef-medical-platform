<template>
  <div class="hospitals-tab-content dir-rtl">
    <!-- شريط البحث الفعال وزر الإضافة -->
    <div class="card border-0 shadow-sm p-3 rounded-4 bg-white mb-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2 gap-md-3 flex-grow-1 justify-content-start flex-column flex-sm-row">
          <div class="position-relative w-100 flex-grow-1">
            <input
              type="text"
              class="form-control border-light-subtle rounded-3 pe-4 ps-5 fs-8 text-end bg-light-subtle"
              placeholder="ابحث عن مستشفى أو مركز طبي..."
              :value="searchQuery"
              @input="$emit('update:searchQuery', $event.target.value)"
            />
            <span class="position-absolute start-0 top-50 translate-middle-y ps-3 text-muted">🔍</span>
          </div>
          <select
            class="form-select border-light-subtle rounded-3 fs-8 text-center w-100 w-sm-auto"
            style="min-width: 140px;"
            :value="selectedFilter"
            @change="$emit('update:selectedFilter', $event.target.value)"
          >
            <option value="all">جميع المناطق</option>
            <option value="غزة">غزة</option>
            <option value="شمال غزة">شمال غزة</option>
            <option value="دير البلح">دير البلح</option>
            <option value="خانيونس">خانيونس</option>
            <option value="رفح">رفح</option>
          </select>
        </div>
        <div class="w-100 w-sm-auto text-end">
          <button
            class="btn btn-danger rounded-3 px-4 py-2 fs-8 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 w-100 w-sm-auto"
            @click="accountsStore.addItem('مستشفى')"
          >
            <span>+ إضافة مستشفى</span>
          </button>
        </div>
      </div>
    </div>

    <!-- جدول بيانات المستشفيات -->
    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-4 overflow-hidden">
      <div class="table-responsive">
        <div class="min-w-table">
          <div class="row text-center fw-bold text-dark fs-8 py-2 px-3 mb-3 border-bottom border-light text-nowrap">
            <div class="col-3 text-end ps-3">اسم المستشفى / الجهة</div>
            <div class="col-2">نوع القطاع</div>
            <div class="col-2">الهاتف / التواصل</div>
            <div class="col-2">الموقع الجغرافي</div>
            <div class="col-2">الحالة</div>
            <div class="col-1 text-end pe-3">الإجراءات</div>
          </div>

          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="hospital in hospitalsList"
              :key="hospital.id"
              class="row align-items-center text-center py-3 px-3 rounded-4 bg-light-subtle row-card border border-light-subtle transition-all text-nowrap"
            >
              <div class="col-3 text-end fw-bold text-dark fs-8 d-flex align-items-center justify-content-start gap-2 ps-3 min-w-0">
                <span class="hospital-icon-circle bg-danger-subtle text-danger rounded-circle p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px; height:28px;">🏥</span>
                <span class="text-truncate">{{ hospital.name }}</span>
              </div>
              <div class="col-2 text-muted fs-8">{{ hospital.type }}</div>
              <div class="col-2 text-dark dir-ltr fs-8">{{ hospital.phone }}</div>
              <div class="col-2 text-muted fs-8 text-truncate">{{ hospital.location }}</div>
              <div class="col-2">
                <span class="fw-bold fs-8" :class="getStatusClass(hospital.status)">{{ hospital.status }}</span>
              </div>
              <div class="col-1 d-flex align-items-center justify-content-end gap-2">
                <button
                  class="btn btn-link p-0 text-decoration-none fs-7 action-btn"
                  title="تعديل"
                  @click="accountsStore.editItem(hospital, 'مستشفى')"
                >✏️</button>
                <button
                  class="btn btn-link p-0 text-decoration-none fs-7 text-danger action-btn"
                  title="حذف"
                  @click="accountsStore.deleteItem(hospital.id, 'مستشفى')"
                >🗑️</button>
              </div>
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
  hospitalsList: Array
});

defineEmits(['update:searchQuery', 'update:selectedFilter']);

const getStatusClass = (status) => {
  switch (status) {
    case 'نشط': return 'text-success';
    case 'معلق': return 'text-warning-emphasis';
    case 'ملغي': return 'text-danger';
    default: return 'text-muted';
  }
};
</script>

<style scoped>
.fs-7 { font-size: 0.95rem; }
.fs-8 { font-size: 0.82rem; }
.bg-light-subtle { background-color: #f9fafb !important; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.row-card:hover { background-color: #f3f4f6 !important; }
.action-btn { opacity: 0.75; transition: opacity 0.2s; cursor: pointer; }
.action-btn:hover { opacity: 1; }
.dir-rtl { direction: rtl; }
.min-w-table { min-width: 680px; }
</style>
