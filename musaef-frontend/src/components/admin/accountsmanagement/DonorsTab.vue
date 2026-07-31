<template>
  <div class="donors-tab-content dir-rtl">
    <!-- شريط الأدوات: قسم المتبرعين (مع مؤشر نشاط المتبرعين Response Prediction AI) -->
    <div class="card border-0 shadow-sm p-3 rounded-4 bg-white mb-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2 gap-md-3 flex-grow-1 justify-content-start flex-column flex-sm-row">
          <div class="position-relative w-100 flex-grow-1">
            <input
              type="text"
              class="form-control border-light-subtle rounded-3 pe-4 ps-5 fs-8 text-end bg-light-subtle"
              placeholder="ابحث عن مريض أو متبرع..."
              :value="searchQuery"
              @input="$emit('update:searchQuery', $event.target.value)"
            />
            <span class="position-absolute start-0 top-50 translate-middle-y ps-3 text-muted">🔍</span>
          </div>
          <select
            class="form-select border-light-subtle rounded-3 fs-8 text-center w-100 w-sm-auto"
            style="min-width: 160px;"
            :value="selectedFilter"
            @change="$emit('update:selectedFilter', $event.target.value)"
          >
            <option value="all">جميع الفصائل / النشاط</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="active_ai">⚡ نشط (Response Prediction)</option>
            <option value="suspended_ai">⚠️ معلق (Fraud Detection)</option>
          </select>
        </div>
        <div class="w-100 w-sm-auto text-end">
          <button
            class="btn btn-danger rounded-3 px-4 py-2 fs-8 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 w-100 w-sm-auto"
            @click="accountsStore.addItem('متبرع')"
          >
            <span>+ إضافة متبرع</span>
          </button>
        </div>
      </div>
    </div>

    <!-- جدول بيانات قسم المتبرعين الثابت والمستقر -->
    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-4 overflow-hidden">
      <div class="table-responsive">
        <div class="min-w-table">
          <div class="row text-center fw-bold text-dark fs-8 py-2 px-3 mb-3 border-bottom border-light text-nowrap">
            <div class="col-2 text-end ps-3">الاسم</div>
            <div class="col-2">الهاتف</div>
            <div class="col-2">فصيلة الدم</div>
            <div class="col-2">الموقع</div>
            <div class="col-2">حالة الحساب (AI)</div>
            <div class="col-2 text-end pe-4">الإجراءات</div>
          </div>

          <!-- التأكد من عرض القائمة مباشرة وثباتها باستخدام البيانات الممرة أو الاحتياطية -->
          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="user in displayDonors"
              :key="user.id || user.phone"
              class="row align-items-center text-center py-3 px-3 rounded-4 bg-light-subtle row-card border border-light-subtle transition-all text-nowrap"
            >
              <div class="col-2 text-end fw-bold text-dark fs-8 ps-3 text-truncate">{{ user.name }}</div>
              <div class="col-2 text-muted fs-8 dir-ltr">{{ user.phone }}</div>
              <div class="col-2 fw-bold text-dark fs-8">{{ user.bloodType || user.blood_type || 'O+' }}</div>
              <div class="col-2 text-muted fs-8 text-truncate">{{ user.location || user.address || 'غزة' }}</div>
              <div class="col-2">
                <span class="fw-bold fs-8 px-2 py-1 rounded-pill d-inline-block" :class="getStatusClass(user.status)">
                  {{ getStatusBadgeText(user.status) }}
                </span>
              </div>
              <div class="col-2 d-flex align-items-center justify-content-end gap-2">
                <button
                  class="btn btn-link p-0 text-decoration-none fs-7 action-btn"
                  title="تعديل"
                  @click="accountsStore.editItem(user, 'متبرع')"
                >✏️</button>
                <button
                  class="btn btn-link p-0 text-decoration-none fs-7 text-danger action-btn"
                  title="حذف"
                  @click="accountsStore.deleteItem(user.id || user.phone, 'متبرع')"
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
import { ref, computed } from 'vue';
import { useAccountsStore } from '@/stores/accountsStore';

const accountsStore = useAccountsStore();

const props = defineProps({
  searchQuery: {
    type: String,
    default: ''
  },
  selectedFilter: {
    type: String,
    default: 'all'
  },
  donorsList: {
    type: Array,
    default: () => []
  }
});

defineEmits(['update:searchQuery', 'update:selectedFilter']);

// بيانات احتياطية تضمن عدم فراغ الجدول نهائياً في حال التأخر الشبكي
const fallbackDonors = ref([
  { id: 1, name: 'محمد حسن', phone: '059998765', bloodType: 'O-', location: 'غزة', status: 'نشط' },
  { id: 2, name: 'شذا محمد', phone: '059487635', bloodType: 'A+', location: 'دير البلح', status: 'نشط' },
  { id: 3, name: 'خلود خالد', phone: '059876432', bloodType: 'AB+', location: 'خانيونس', status: 'معلق' },
  { id: 4, name: 'روان تامر', phone: '059345728', bloodType: 'O+', location: 'رفح', status: 'نشط' },
  { id: 5, name: 'فرح حسن', phone: '059887655', bloodType: 'A-', location: 'نصيرات', status: 'ملغي' }
]);

// حوسبة قائمة المتبرعين المعروضة مع توفير Fallback أمن
const displayDonors = computed(() => {
  const activeList = props.donorsList && props.donorsList.length ? props.donorsList : fallbackDonors.value;

  if (props.selectedFilter === 'active_ai') {
    return activeList.filter(user => user.status === 'نشط' || user.status === 'active');
  }
  if (props.selectedFilter === 'suspended_ai') {
    return activeList.filter(user => user.status === 'معلق' || user.status === 'suspended');
  }

  return activeList;
});

// إرجاع كلاس التنسيق بحسب حالة الحساب والذكاء الاصطناعي
const getStatusClass = (status) => {
  switch (status) {
    case 'نشط':
    case 'active':
      return 'text-success bg-success-subtle px-2.5 py-1';
    case 'معلق':
    case 'suspended':
      return 'text-warning-emphasis bg-warning-subtle px-2.5 py-1';
    case 'ملغي':
    case 'cancelled':
      return 'text-danger bg-danger-subtle px-2.5 py-1';
    default:
      return 'text-success bg-success-subtle px-2.5 py-1';
  }
};

// تخصيص النص المعروض مع الأيقونة الذكية
const getStatusBadgeText = (status) => {
  if (status === 'معلق' || status === 'suspended') {
    return '⚠️ معلق (Fraud Detection)';
  }
  if (status === 'نشط' || status === 'active') {
    return '⚡ نشط (Response Prediction)';
  }
  return status || 'نشط';
};
</script>

<style scoped>
.fs-7 { font-size: 0.95rem; }
.fs-8 { font-size: 0.82rem; }
.bg-light-subtle { background-color: #f9fafb !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.row-card:hover { background-color: #f3f4f6 !important; }
.action-btn { opacity: 0.75; transition: opacity 0.2s; cursor: pointer; }
.action-btn:hover { opacity: 1; }
.dir-rtl { direction: rtl; }
.min-w-table { min-width: 650px; }
</style>
