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
                <span class="text-truncate">{{ getHospitalName(hospital.name) }}</span>
              </div>
              <div class="col-2 text-muted fs-8">{{ getSectorType(hospital.type) }}</div>
              <div class="col-2 text-dark dir-ltr fs-8">{{ hospital.phone }}</div>
              <div class="col-2 text-muted fs-8 text-truncate">{{ getLocationName(hospital.location) }}</div>
              <div class="col-1">
                <span class="fw-bold fs-8" :class="getStatusClass(hospital.status)">{{ getStatusText(hospital.status) }}</span>
              </div>
              <!-- عمود الإجراءات / أزرار التعديل والحذف التفاعلية -->
              <div class="col-2 d-flex align-items-center justify-content-center gap-2">
                <button
                  class="btn btn-sm btn-outline-warning text-dark border-0 bg-warning-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :title="t('edit')"
                  @click="accountsStore.editItem(hospital, t('hospitalWord'))"
                >
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                  <span>{{ t('edit') }}</span>
                </button>
                <button
                  class="btn btn-sm btn-outline-danger text-danger border-0 bg-danger-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :title="t('delete')"
                  @click="accountsStore.deleteItem(hospital.id, t('hospitalWord'))"
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
  searchQuery: String,
  selectedFilter: String,
  hospitalsList: Array
});

defineEmits(['update:searchQuery', 'update:selectedFilter']);

const dictionary = {
  ar: {
    searchPlaceholder: 'ابحث عن مستشفى أو مركز طبي...',
    allRegions: 'جميع المناطق',
    gaza: 'غزة',
    northGaza: 'شمال غزة',
    deirBalah: 'دير البلح',
    khanYounis: 'خانيونس',
    rafah: 'رفح',
    addHospital: 'إضافة مستشفى',
    colFacility: 'اسم المستشفى / الجهة',
    colSector: 'نوع القطاع',
    colContact: 'الهاتف / التواصل',
    colLocation: 'الموقع الجغرافي',
    colStatus: 'الحالة',
    colActions: 'الإجراءات',
    edit: 'تعديل',
    delete: 'حذف',
    hospitalWord: 'مستشفى'
  },
  en: {
    searchPlaceholder: 'Search for hospital or medical center...',
    allRegions: 'All Regions',
    gaza: 'Gaza',
    northGaza: 'North Gaza',
    deirBalah: 'Deir al-Balah',
    khanYounis: 'Khan Younis',
    rafah: 'Rafah',
    addHospital: 'Add Hospital',
    colFacility: 'Hospital / Facility Name',
    colSector: 'Sector Type',
    colContact: 'Phone / Contact',
    colLocation: 'Geographic Location',
    colStatus: 'Status',
    colActions: 'Actions',
    edit: 'Edit',
    delete: 'Delete',
    hospitalWord: 'Hospital'
  }
};

const hospitalNamesMap = {
  'مستشفى الشفاء الطبي': { ar: 'مستشفى الشفاء الطبي', en: 'Al-Shifa Medical Hospital' },
  'Al-Shifa Medical Hospital': { ar: 'مستشفى الشفاء الطبي', en: 'Al-Shifa Medical Hospital' },
  'مستشفى شهداء الأقصى': { ar: 'مستشفى شهداء الأقصى', en: 'Al-Aqsa Martyrs Hospital' },
  'Al-Aqsa Martyrs Hospital': { ar: 'مستشفى شهداء الأقصى', en: 'Al-Aqsa Martyrs Hospital' },
  'مستشفى ناصر الطبي': { ar: 'مستشفى ناصر الطبي', en: 'Nasser Medical Hospital' },
  'Nasser Medical Hospital': { ar: 'مستشفى ناصر الطبي', en: 'Nasser Medical Hospital' },
  'المستشفى الإندونيسي': { ar: 'المستشفى الإندونيسي', en: 'Indonesian Hospital' },
  'مستشفى العودة': { ar: 'مستشفى العودة', en: 'Al-Awda Hospital' },
  'Al-Awda Hospital': { ar: 'مستشفى العودة', en: 'Al-Awda Hospital' },
  'Al-Quds Hospital': { ar: 'مستشفى القدس', en: 'Al-Quds Hospital' },
  'مستشفى القدس': { ar: 'مستشفى القدس', en: 'Al-Quds Hospital' },
  'مستشفى الكويتي التخصصي': { ar: 'مستشفى الكويتي التخصصي', en: 'Kuwaiti Specialty Hospital' },
  'Kuwaiti Specialty Hospital': { ar: 'مستشفى الكويتي التخصصي', en: 'Kuwaiti Specialty Hospital' }
};

const locationsMap = {
  'غزة - الرمال': { ar: 'غزة - الرمال', en: 'Gaza - Rimal' },
  'Gaza - Rimal': { ar: 'غزة - الرمال', en: 'Gaza - Rimal' },
  'Deir al-Balah': { ar: 'دير البلح', en: 'Deir al-Balah' },
  'دير البلح': { ar: 'دير البلح', en: 'Deir al-Balah' },
  'Khan Younis': { ar: 'خانيونس', en: 'Khan Younis' },
  'خانيونس': { ar: 'خانيونس', en: 'Khan Younis' },
  'North Gaza': { ar: 'شمال غزة', en: 'North Gaza' },
  'شمال غزة': { ar: 'شمال غزة', en: 'North Gaza' },
  'النصيرات': { ar: 'النصيرات', en: 'Nuseirat' },
  'Nuseirat': { ar: 'النصيرات', en: 'Nuseirat' },
  'غزة - تل الهوا': { ar: 'غزة - تل الهوا', en: 'Gaza - Tel al-Hawa' },
  'Gaza - Tel al-Hawa': { ar: 'غزة - تل الهوا', en: 'Gaza - Tel al-Hawa' },
  'Rafah': { ar: 'رفح', en: 'Rafah' },
  'رفح': { ar: 'رفح', en: 'Rafah' }
};

const sectorMap = {
  'Governmental': { ar: 'حكومي', en: 'Governmental' },
  'حكومي': { ar: 'حكومي', en: 'Governmental' },
  'NGO / UNRWA': { ar: 'أهلي / أونروا', en: 'NGO / UNRWA' },
  'أهلي / أونروا': { ar: 'أهلي / أونروا', en: 'NGO / UNRWA' },
  'Private / Red Crescent': { ar: 'خاص / هلال أحمر', en: 'Private / Red Crescent' },
  'خاص / هلال أحمر': { ar: 'خاص / هلال أحمر', en: 'Private / Red Crescent' },
  'NGO / Charitable': { ar: 'أهلي خيري', en: 'NGO / Charitable' },
  'أهلي خيري': { ar: 'أهلي خيري', en: 'NGO / Charitable' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;
const getHospitalName = (name) => (hospitalNamesMap[name] ? hospitalNamesMap[name][currentLanguage.value === 'en' ? 'en' : 'ar'] : name);
const getLocationName = (loc) => (locationsMap[loc] ? locationsMap[loc][currentLanguage.value === 'en' ? 'en' : 'ar'] : loc);
const getSectorType = (type) => (sectorMap[type] ? sectorMap[type][currentLanguage.value === 'en' ? 'en' : 'ar'] : type);

const displayHospitals = computed(() => {
  return props.hospitalsList && props.hospitalsList.length ? props.hospitalsList : accountsStore.hospitals;
});

const getStatusText = (status) => {
  if (status === 'نشط' || status === 'Active' || status === 'active') return currentLanguage.value === 'en' ? 'Active' : 'نشط';
  if (status === 'معلق' || status === 'Suspended' || status === 'suspended') return currentLanguage.value === 'en' ? 'Suspended' : 'معلق';
  if (status === 'ملغي' || status === 'Cancelled' || status === 'cancelled') return currentLanguage.value === 'en' ? 'Cancelled' : 'ملغي';
  return status;
};

const getStatusClass = (status) => {
  switch (status) {
    case 'نشط': case 'Active': case 'active': return 'text-success';
    case 'معلق': case 'Suspended': case 'suspended': return 'text-warning-emphasis';
    case 'ملغي': case 'Cancelled': case 'cancelled': return 'text-danger';
    default: return 'text-muted';
  }
};
</script>

<style scoped>
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.75rem; }
.bg-light-subtle { background-color: #f9fafb !important; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.row-card:hover { background-color: #f3f4f6 !important; }
.action-btn { transition: all 0.2s ease; cursor: pointer; }
.action-btn:hover { transform: translateY(-1px); filter: brightness(0.95); }
.min-w-table { min-width: 750px; }
</style>
