<template>
  <div class="hospitals-tab-content" :dir="langStore.dir">
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
            style="min-width: 150px;"
            :value="selectedFilter"
            @change="$emit('update:selectedFilter', $event.target.value)"
          >
            <option value="all">{{ t('allRegions') }}</option>
            <option value="غزة">{{ t('gaza') }}</option>
            <option value="شمال غزة">{{ t('northGaza') }}</option>
            <option value="دير البلح">{{ t('deirBalah') }}</option>
            <option value="خانيونس">{{ t('khanYounis') }}</option>
            <option value="رفح">{{ t('rafah') }}</option>
          </select>
        </div>
        <div class="w-100 w-sm-auto text-end">
          <button
            class="btn btn-danger rounded-3 px-4 py-2 fs-8 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 w-100 w-sm-auto"
            @click="accountsStore.addItem(t('hospitalWord'))"
          >
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>{{ t('addHospital') }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- جدول المستشفيات -->
    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-4 overflow-hidden">
      <div class="table-responsive">
        <div class="min-w-table">
          <div class="row text-center fw-bold text-dark fs-8 py-2 px-3 mb-3 border-bottom border-light text-nowrap">
            <div class="col-3 text-start ps-3">{{ t('colFacility') }}</div>
            <div class="col-2">{{ t('colSector') }}</div>
            <div class="col-2">{{ t('colContact') }}</div>
            <div class="col-2">{{ t('colLocation') }}</div>
            <div class="col-1">{{ t('colStatus') }}</div>
            <div class="col-2 text-center">{{ t('colActions') }}</div>
          </div>

          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="hospital in displayHospitals"
              :key="hospital.id"
              class="row align-items-center text-center py-3 px-3 rounded-4 bg-light-subtle row-card border border-light-subtle transition-all text-nowrap"
            >
              <div class="col-3 text-start fw-bold text-dark fs-8 d-flex align-items-center justify-content-start gap-2 ps-3 min-w-0">
                <span class="hospital-icon-circle bg-danger-subtle text-danger rounded-circle p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px; height:28px;">
                  🏥
                </span>
                <span class="text-truncate">{{ hospital.name }}</span>
              </div>
              <div class="col-2 text-muted fs-8">{{ hospital.type }}</div>
              <div class="col-2 text-dark dir-ltr fs-8">{{ hospital.phone }}</div>
              <div class="col-2 text-muted fs-8 text-truncate">{{ hospital.location }}</div>
              <div class="col-1">
                <span class="fw-bold fs-8" :class="getStatusClass(hospital.status)">{{ getStatusText(hospital.status) }}</span>
              </div>

              <!-- الإجراءات وتفعيل فحص الشبهات/المراجعة -->
              <div class="col-2 d-flex align-items-center justify-content-center gap-1.5">
                <button
                  class="btn btn-sm btn-outline-info text-dark border-0 bg-info-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :title="t('analyzeAi')"
                  @click="accountsStore.analyzeHospitalFraud(hospital.id)"
                >
                  🛡️ <span>{{ t('analyzeAi') }}</span>
                </button>
                <button
                  class="btn btn-sm btn-outline-warning text-dark border-0 bg-warning-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  @click="accountsStore.editItem(hospital, t('hospitalWord'))"
                >
                  <span>{{ t('edit') }}</span>
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
  searchQuery: String,
  selectedFilter: String,
  hospitalsList: Array
});

const dictionary = {
  ar: {
    searchPlaceholder: 'ابحث عن مستشفى أو مركز طبي...',
    allRegions: 'جميع المناطق',
    addHospital: 'إضافة مستشفى',
    colFacility: 'اسم المستشفى / الجهة',
    colSector: 'نوع القطاع',
    colContact: 'الهاتف / التواصل',
    colLocation: 'الموقع الجغرافي',
    colStatus: 'الحالة',
    colActions: 'الإجراءات',
    analyzeAi: 'تحليل AI',
    edit: 'تعديل',
    hospitalWord: 'مستشفى'
  },
  en: {
    searchPlaceholder: 'Search for hospital or medical center...',
    allRegions: 'All Regions',
    addHospital: 'Add Hospital',
    colFacility: 'Hospital / Facility Name',
    colSector: 'Sector Type',
    colContact: 'Phone / Contact',
    colLocation: 'Geographic Location',
    colStatus: 'Status',
    colActions: 'Actions',
    analyzeAi: 'AI Analyze',
    edit: 'Edit',
    hospitalWord: 'Hospital'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const displayHospitals = computed(() => {
  return props.hospitalsList && props.hospitalsList.length ? props.hospitalsList : accountsStore.hospitals;
});

const getStatusText = (status) => {
  if (status === 'active' || status === 'نشط') return currentLanguage.value === 'en' ? 'Active' : 'نشط';
  if (status === 'suspended_ai' || status === 'معلق') return currentLanguage.value === 'en' ? 'Suspended (AI)' : 'معلق (AI)';
  return status;
};

const getStatusClass = (status) => {
  switch (status) {
    case 'active': case 'نشط': return 'text-success';
    case 'suspended_ai': case 'معلق': return 'text-warning-emphasis';
    default: return 'text-muted';
  }
};
</script>

<style scoped>
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.75rem; }
.bg-light-subtle { background-color: #f9fafb !important; }
.bg-info-subtle { background-color: #e0f2fe !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.row-card:hover { background-color: #f3f4f6 !important; }
.action-btn { transition: all 0.2s ease; cursor: pointer; }
.min-w-table { min-width: 750px; }
</style>
