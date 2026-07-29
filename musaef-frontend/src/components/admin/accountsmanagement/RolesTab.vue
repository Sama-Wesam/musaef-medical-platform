<template>
  <div class="roles-tab-content dir-rtl">
    <!-- شريط الأدوات: قسم الصلاحيات -->
    <div class="card border-0 shadow-sm p-3 rounded-4 bg-white mb-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2 gap-md-3 flex-grow-1 justify-content-start flex-column flex-sm-row">
          <div class="position-relative w-100 flex-grow-1">
            <input
              type="text"
              class="form-control border-light-subtle rounded-3 pe-4 ps-5 fs-8 text-end bg-light-subtle"
              placeholder="ابحث عن مدير أو نوع الصلاحية..."
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
            <option value="all">جميع الصلاحيات</option>
            <option value="مدير نظام">مدير نظام (Super Admin)</option>
            <option value="مشرف بنك الدم">مشرف بنك الدم</option>
            <option value="مسؤول مستشفى">مسؤول مستشفى</option>
            <option value="دعم فني">دعم فني</option>
          </select>
        </div>
        <div class="w-100 w-sm-auto text-end">
          <button
            class="btn btn-danger rounded-3 px-4 py-2 fs-8 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 w-100 w-sm-auto"
            @click="accountsStore.addItem('دور/صلاحية')"
          >
            <span>+ إضافة دور / صلاحية</span>
          </button>
        </div>
      </div>
    </div>

    <!-- جدول بيانات قسم الصلاحيات -->
    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-4 overflow-hidden">
      <div class="table-responsive">
        <div class="min-w-table">
          <div class="row text-center fw-bold text-dark fs-8 py-2 px-3 mb-3 border-bottom border-light text-nowrap">
            <div class="col-3 text-end ps-3">الاسم / الحساب</div>
            <div class="col-2">الرتبة / مستوى الصلاحية</div>
            <div class="col-2">البريد الإلكتروني</div>
            <div class="col-2">نطاق الوصول</div>
            <div class="col-2">الحالة</div>
            <div class="col-1 text-end pe-3">الإجراءات</div>
          </div>

          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="role in rolesList"
              :key="role.id"
              class="row align-items-center text-center py-3 px-3 rounded-4 bg-light-subtle row-card border border-light-subtle transition-all text-nowrap"
            >
              <div class="col-3 text-end fw-bold text-dark fs-8 d-flex align-items-center justify-content-start gap-2 ps-3 min-w-0">
                <span class="role-icon-circle bg-purple-subtle text-purple rounded-circle p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px; height:28px;">🛡️</span>
                <span class="text-truncate">{{ role.name }}</span>
              </div>
              <div class="col-2 fw-bold fs-8 text-primary text-truncate">{{ role.roleTitle }}</div>
              <div class="col-2 text-muted fs-8 dir-ltr text-truncate">{{ role.email }}</div>
              <div class="col-2 text-muted fs-8 text-truncate">{{ role.scope }}</div>
              <div class="col-2">
                <span class="fw-bold fs-8" :class="getStatusClass(role.status)">{{ role.status }}</span>
              </div>
              <div class="col-1 d-flex align-items-center justify-content-end gap-2">
                <button
                  class="btn btn-link p-0 text-decoration-none fs-7 action-btn"
                  title="تعديل"
                  @click="accountsStore.editItem(role, 'صلاحية')"
                >✏️</button>
                <button
                  class="btn btn-link p-0 text-decoration-none fs-7 text-danger action-btn"
                  title="حذف"
                  @click="accountsStore.deleteItem(role.id, 'صلاحية')"
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
  rolesList: Array
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
.bg-purple-subtle { background-color: #f3e8ff !important; }
.text-purple { color: #9333ea !important; }
.row-card:hover { background-color: #f3f4f6 !important; }
.action-btn { opacity: 0.75; transition: opacity 0.2s; cursor: pointer; }
.action-btn:hover { opacity: 1; }
.dir-rtl { direction: rtl; }
.min-w-table { min-width: 680px; }
</style>
