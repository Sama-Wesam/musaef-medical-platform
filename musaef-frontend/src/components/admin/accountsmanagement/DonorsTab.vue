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
            <div class="col-2">{{ t('colBlood') }}</div>
            <div class="col-2">{{ t('colLocation') }}</div>
            <div class="col-2">{{ t('colStatus') }}</div>
            <div class="col-2 text-center">{{ t('colActions') }}</div>
          </div>

          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="user in displayDonors"
              :key="user.id || user.phone"
              class="row align-items-center text-center py-3 px-3 rounded-4 bg-light-subtle row-card border border-light-subtle transition-all text-nowrap"
            >
              <div class="col-2 text-start fw-bold text-dark fs-8 ps-3 text-truncate">{{ getDonorName(user.name) }}</div>
              <div class="col-2 text-muted fs-8 dir-ltr">{{ user.phone || '—' }}</div>
              <div class="col-2 fw-bold text-dark fs-8">{{ user.bloodType || user.blood_type || 'O+' }}</div>
              <div class="col-2 text-muted fs-8 text-truncate">{{ getLocationName(user.location || user.address) }}</div>
              <div class="col-2">
                <span class="fw-bold fs-8 px-2.5 py-1 rounded-pill d-inline-block" :class="getStatusClass(user.status)">
                  {{ getStatusBadgeText(user.status) }}
                </span>
              </div>
              <!-- عمود الإجراءات / أزرار التعديل والحذف التفاعلية -->
              <div class="col-2 d-flex align-items-center justify-content-center gap-2">
                <button
                  class="btn btn-sm btn-outline-warning text-dark border-0 bg-warning-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :title="t('edit')"
                  @click="accountsStore.editItem(user, t('donorWord'))"
                >
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                  <span>{{ t('edit') }}</span>
                </button>
                <button
                  class="btn btn-sm btn-outline-danger text-danger border-0 bg-danger-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :title="t('delete')"
                  @click="accountsStore.deleteItem(user.id || user.phone, t('donorWord'))"
                >
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
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
    activeAi: 'نشط',
    suspendedAi: 'معلق',
    addDonor: 'إضافة متبرع',
    colName: 'الاسم',
    colPhone: 'الهاتف',
    colBlood: 'فصيلة الدم',
    colLocation: 'الموقع',
    colStatus: 'حالة الحساب (AI)',
    colActions: 'الإجراءات',
    edit: 'تعديل',
    delete: 'حذف',
    donorWord: 'متبرع'
  },
  en: {
    searchPlaceholder: 'Search for patient or donor...',
    filterAll: 'All Types / Activity',
    activeAi: 'Active',
    suspendedAi: 'Suspended',
    addDonor: 'Add Donor',
    colName: 'Name',
    colPhone: 'Phone',
    colBlood: 'Blood Type',
    colLocation: 'Location',
    colStatus: 'Account Status (AI)',
    colActions: 'Actions',
    edit: 'Edit',
    delete: 'Delete',
    donorWord: 'Donor'
  }
};

const namesMap = {
  'shimaa': { ar: 'شيماء', en: 'shimaa' },
  'YASSER ALQRINAWI': { ar: 'ياسر القرناوي', en: 'YASSER ALQRINAWI' },
  'أحمد المتبرع': { ar: 'أحمد المتبرع', en: 'Ahmed Donor' },
  'Ahmed Donor': { ar: 'أحمد المتبرع', en: 'Ahmed Donor' },
  'Hamza Nabeel': { ar: 'حمزة نبيل', en: 'Hamza Nabeel' },
  'Sama Wesam': { ar: 'سما وسام', en: 'Sama Wesam' },
  'مياساء المطرفي': { ar: 'ميساء المطرفي', en: 'Maysaa Al-Matrafi' }
};

const locationsMap = {
  'Gaza': { ar: 'غزة', en: 'Gaza' },
  'غزة': { ar: 'غزة', en: 'Gaza' },
  'Deir al-Balah': { ar: 'دير البلح', en: 'Deir al-Balah' },
  'Khan Younis': { ar: 'خانيونس', en: 'Khan Younis' },
  'Rafah': { ar: 'رفح', en: 'Rafah' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;
const getDonorName = (name) => (namesMap[name] ? namesMap[name][currentLanguage.value === 'en' ? 'en' : 'ar'] : name);
const getLocationName = (loc) => (locationsMap[loc] ? locationsMap[loc][currentLanguage.value === 'en' ? 'en' : 'ar'] : loc || (currentLanguage.value === 'en' ? 'Gaza' : 'غزة'));

const displayDonors = computed(() => {
  const activeList = props.donorsList && props.donorsList.length ? props.donorsList : accountsStore.donors;
  if (props.selectedFilter === 'active_ai') {
    return activeList.filter(user => user.status === 'نشط' || user.status === 'active');
  }
  if (props.selectedFilter === 'suspended_ai') {
    return activeList.filter(user => user.status === 'معلق' || user.status === 'suspended');
  }
  return activeList;
});

const getStatusClass = (status) => {
  switch (status) {
    case 'نشط': case 'active': case 'Active': return 'text-success bg-success-subtle';
    case 'معلق': case 'suspended': case 'Suspended': return 'text-warning-emphasis bg-warning-subtle';
    case 'ملغي': case 'cancelled': case 'Cancelled': return 'text-danger bg-danger-subtle';
    default: return 'text-success bg-success-subtle';
  }
};

const getStatusBadgeText = (status) => {
  if (status === 'معلق' || status === 'suspended' || status === 'Suspended') {
    return currentLanguage.value === 'en' ? 'Suspended' : 'معلق';
  }
  return currentLanguage.value === 'en' ? 'Active' : 'نشط';
};
</script>

<style scoped>
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.75rem; }
.bg-light-subtle { background-color: #f9fafb !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.row-card:hover { background-color: #f3f4f6 !important; }
.action-btn { transition: all 0.2s ease; cursor: pointer; }
.action-btn:hover { transform: translateY(-1px); filter: brightness(0.95); }
.min-w-table { min-width: 750px; }
</style>
