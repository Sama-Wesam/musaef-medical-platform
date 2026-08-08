<template>
  <div class="donors-tab-content" :dir="langStore.dir">
    <!-- شريط الأدوات والبحث -->
    <div class="card border-0 shadow-sm p-3 rounded-4 bg-white mb-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2 gap-md-3 flex-grow-1 justify-content-start flex-column flex-sm-row">
          <div class="position-relative w-100 flex-grow-1">
            <input
              type="text"
              class="form-control border-light-subtle rounded-3 pe-4 ps-5 fs-8 bg-light-subtle"
              :placeholder="t('searchPlaceholder')"
              :value="searchQuery"
              @input="$emit('update:searchQuery', $event.target.value)"
            />
            <span class="position-absolute top-50 translate-middle-y text-muted" :class="currentLanguage === 'en' ? 'end-0 pe-3' : 'start-0 ps-3'">
              <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
          </div>
          <select
            class="form-select border-light-subtle rounded-3 fs-8 text-center w-100 w-sm-auto"
            style="min-width: 180px;"
            :value="selectedFilter"
            @change="$emit('update:selectedFilter', $event.target.value)"
          >
            <option value="all">{{ t('filterAll') }}</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="active_ai">{{ t('activeAi') }}</option>
            <option value="suspended_ai">{{ t('suspendedAi') }}</option>
          </select>
        </div>
        <div class="w-100 w-sm-auto text-end">
          <button
            class="btn btn-danger rounded-3 px-4 py-2 fs-8 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 w-100 w-sm-auto"
            @click="accountsStore.addItem(t('donorWord'))"
          >
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>{{ t('addDonor') }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- جدول بيانات المتبرعين -->
    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-4 overflow-hidden">
      <div class="table-responsive">
        <div class="min-w-table">
          <div class="row text-center fw-bold text-dark fs-8 py-2 px-3 mb-3 border-bottom border-light text-nowrap">
            <div class="col-2 text-start ps-3">{{ t('colName') }}</div>
            <div class="col-2">{{ t('colPhone') }}</div>
            <div class="col-1">{{ t('colBlood') }}</div>
            <div class="col-2">{{ t('colActivityScore') }}</div>
            <div class="col-2">{{ t('colStatus') }}</div>
            <div class="col-3 text-center">{{ t('colActions') }}</div>
          </div>

          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="user in displayDonors"
              :key="user.id || user.phone"
              class="row align-items-center text-center py-3 px-3 rounded-4 bg-light-subtle row-card border border-light-subtle transition-all text-nowrap"
            >
              <div class="col-2 text-start fw-bold text-dark fs-8 ps-3 text-truncate">{{ getDonorName(user) }}</div>
              <div class="col-2 text-muted fs-8 dir-ltr">{{ user.phone || '—' }}</div>
              <div class="col-1 fw-bold text-dark fs-8">{{ user.bloodType || user.blood_type || 'O+' }}</div>

              <!-- مؤشر النشاط (Activity Score) للذكاء الاصطناعي -->
              <div class="col-2">
                <div class="d-flex align-items-center justify-content-center gap-1">
                  <div class="progress w-100" style="height: 6px; max-width: 70px;">
                    <div
                      class="progress-bar rounded-pill"
                      :class="(user.activity_score || 75) >= 60 ? 'bg-success' : 'bg-warning'"
                      :style="{ width: (user.activity_score || 75) + '%' }"
                    ></div>
                  </div>
                  <span class="fs-9 fw-bold text-muted">{{ user.activity_score || 75 }}%</span>
                </div>
              </div>

              <div class="col-2">
                <span class="fw-bold fs-8 px-2.5 py-1 rounded-pill d-inline-block" :class="getStatusClass(user.status)">
                  {{ getStatusBadgeText(user.status) }}
                </span>
              </div>

              <!-- عمود الإجراءات والمراجعة الإدارية -->
              <div class="col-3 d-flex align-items-center justify-content-center gap-1.5">
                <!-- زر المراجعة الإدارية / تقييم الذكاء الاصطناعي -->
                <button
                  class="btn btn-sm btn-outline-info text-dark border-0 bg-info-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :title="t('aiReview')"
                  @click="triggerAiReview(user)"
                >
                  🤖 <span>{{ t('aiReview') }}</span>
                </button>
                <button
                  class="btn btn-sm btn-outline-warning text-dark border-0 bg-warning-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :title="t('edit')"
                  @click="accountsStore.editItem(user, t('donorWord'))"
                >
                  <span>{{ t('edit') }}</span>
                </button>
                <button
                  class="btn btn-sm btn-outline-danger text-danger border-0 bg-danger-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :title="t('delete')"
                  @click="accountsStore.deleteItem(user.id || user.phone, t('donorWord'))"
                >
                  <span>{{ t('delete') }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useAccountsStore } from '@/stores/accountsStore';
import { useLangStore } from '@/stores/langStore';

const accountsStore = useAccountsStore();
const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const props = defineProps({
  searchQuery: { type: String, default: '' },
  selectedFilter: { type: String, default: 'all' },
  donorsList: { type: Array, default: () => [] }
});

defineEmits(['update:searchQuery', 'update:selectedFilter']);

const dictionary = {
  ar: {
    searchPlaceholder: 'ابحث عن مريض أو متبرع...',
    filterAll: 'جميع الفصائل / النشاط',
    activeAi: 'نشط (AI)',
    suspendedAi: 'معلق (AI)',
    addDonor: 'إضافة متبرع',
    colName: 'الاسم',
    colPhone: 'الهاتف',
    colBlood: 'فصيلة الدم',
    colActivityScore: 'مؤشر النشاط',
    colStatus: 'حالة الحساب (AI)',
    colActions: 'الإجراءات والمراجعة',
    aiReview: 'مراجعة AI',
    edit: 'تعديل',
    delete: 'حذف',
    donorWord: 'متبرع'
  },
  en: {
    searchPlaceholder: 'Search for patient or donor...',
    filterAll: 'All Types / Activity',
    activeAi: 'Active (AI)',
    suspendedAi: 'Suspended (AI)',
    addDonor: 'Add Donor',
    colName: 'Name',
    colPhone: 'Phone',
    colBlood: 'Blood Type',
    colActivityScore: 'Activity Score',
    colStatus: 'Account Status (AI)',
    colActions: 'Actions & Review',
    aiReview: 'AI Review',
    edit: 'Edit',
    delete: 'Delete',
    donorWord: 'Donor'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

// دالة مساعدة لاستخراج اسم المتبرع بأمان وتجنب خطأ TypeError
const getDonorName = (donor) => {
  if (!donor) return 'غير محدد';
  if (typeof donor === 'string') return donor;
  return donor.name || donor.full_name || donor.user?.name || 'متبرع';
};

const displayDonors = computed(() => {
  const activeList = props.donorsList && props.donorsList.length ? props.donorsList : accountsStore.donors;
  if (props.selectedFilter === 'active_ai') {
    return activeList.filter(user => user.status === 'نشط' || user.status === 'active' || user.status === 'active_ai');
  }
  if (props.selectedFilter === 'suspended_ai') {
    return activeList.filter(user => user.status === 'معلق' || user.status === 'suspended' || user.status === 'suspended_ai');
  }
  return activeList;
});

const getStatusClass = (status) => {
  switch (status) {
    case 'نشط': case 'active': case 'active_ai': return 'text-success bg-success-subtle';
    case 'معلق': case 'suspended': case 'suspended_ai': return 'text-warning-emphasis bg-warning-subtle';
    case 'ملغي': case 'cancelled': return 'text-danger bg-danger-subtle';
    default: return 'text-success bg-success-subtle';
  }
};

const getStatusBadgeText = (status) => {
  if (status === 'معلق' || status === 'suspended' || status === 'suspended_ai') {
    return currentLanguage.value === 'en' ? 'Suspended (AI)' : 'معلق (AI)';
  }
  return currentLanguage.value === 'en' ? 'Active (AI)' : 'نشط (AI)';
};

// تشغيل المراجعة الإدارية عبر الذكاء الاصطناعي
const triggerAiReview = (user) => {
  accountsStore.reviewDonorWithAi(user);
};
</script>

<style scoped>
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.75rem; }
.bg-light-subtle { background-color: #f9fafb !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.bg-info-subtle { background-color: #e0f2fe !important; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.row-card:hover { background-color: #f3f4f6 !important; }
.action-btn { transition: all 0.2s ease; cursor: pointer; }
.action-btn:hover { transform: translateY(-1px); filter: brightness(0.95); }
.min-w-table { min-width: 800px; }
</style>
