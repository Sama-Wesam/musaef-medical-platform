<template>
  <div class="logs-tab-content" :dir="langStore.dir">
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
            <option value="all">{{ t('allLogs') }}</option>
            <option value="Edit">{{ t('actionEdit') }}</option>
            <option value="Add">{{ t('actionAdd') }}</option>
            <option value="Delete">{{ t('actionDelete') }}</option>
          </select>
        </div>
        <div class="w-100 w-sm-auto text-end">
          <button
            class="btn btn-outline-secondary rounded-3 px-4 py-2 fs-8 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 bg-white border-light-subtle text-dark w-100 w-sm-auto"
            @click="accountsStore.exportLogsCSV()"
          >
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            <span>{{ t('exportCsv') }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- جدول بيانات سجل العمليات -->
    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-4 overflow-hidden">
      <div class="table-responsive">
        <div class="min-w-table">
          <div class="row text-center fw-bold text-dark fs-8 py-2 px-3 mb-3 border-bottom border-light text-nowrap">
            <div class="col-3 text-start ps-3">{{ t('colUser') }}</div>
            <div class="col-2">{{ t('colActionType') }}</div>
            <div class="col-3">{{ t('colDetails') }}</div>
            <div class="col-2">{{ t('colIp') }}</div>
            <div class="col-2">{{ t('colTimestamp') }}</div>
          </div>

          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="log in displayLogs"
              :key="log.id"
              class="row align-items-center text-center py-3 px-3 rounded-4 bg-light-subtle row-card border border-light-subtle transition-all text-nowrap"
            >
              <div class="col-3 text-start fw-bold text-dark fs-8 d-flex align-items-center justify-content-start gap-2 ps-3 min-w-0">
                <span class="log-icon-circle bg-primary-subtle text-primary rounded-circle p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px; height:28px;">
                  📋
                </span>
                <div class="min-w-0 text-start">
                  <span class="d-block mb-0.5 text-truncate">{{ getRoleUserName(log.user) }}</span>
                  <small class="text-muted fs-9 d-block font-normal text-truncate">{{ getRoleTitle(log.role) }}</small>
                </div>
              </div>
              <div class="col-2">
                <span class="badge rounded-pill px-3 py-1 fs-9 fw-bold" :class="getActionBadgeClass(log.actionType)">
                  {{ getActionTypeText(log.actionType) }}
                </span>
              </div>
              <div class="col-3 text-dark fs-8 text-truncate" :title="getDetailsText(log.details)">{{ getDetailsText(log.details) }}</div>
              <div class="col-2 text-muted fs-8 dir-ltr">{{ log.ipAddress }}</div>
              <div class="col-2 text-muted fs-9 dir-ltr">{{ getTimestampText(log.timestamp) }}</div>
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
  logsList: Array
});

defineEmits(['update:searchQuery', 'update:selectedFilter']);

const dictionary = {
  ar: {
    searchPlaceholder: 'ابحث في تفاصيل السجل أو المستخدم...',
    allLogs: 'جميع العمليات',
    actionAdd: 'إضافة بيانات',
    actionEdit: 'تعديل بيانات',
    actionDelete: 'حذف / إلغاء',
    exportCsv: 'تصدير السجل (CSV)',
    colUser: 'المستخدم / المنفذ',
    colActionType: 'نوع العملية',
    colDetails: 'تفاصيل الإجراء',
    colIp: 'عنوان IP',
    colTimestamp: 'الوقت والتاريخ'
  },
  en: {
    searchPlaceholder: 'Search in log details or user...',
    allLogs: 'All Actions',
    actionAdd: 'Add Data',
    actionEdit: 'Edit Data',
    actionDelete: 'Delete / Cancel',
    exportCsv: 'Export Logs (CSV)',
    colUser: 'User / Executer',
    colActionType: 'Action Type',
    colDetails: 'Action Details',
    colIp: 'IP Address',
    colTimestamp: 'Date & Time'
  }
};

const usersMap = {
  'د. سعيد عيده': { ar: 'د. سعيد عيده', en: 'Dr. Saeed Eideh' },
  'Dr. Saeed Eideh': { ar: 'د. سعيد عيده', en: 'Dr. Saeed Eideh' },
  'أحمد محمود': { ar: 'أحمد محمود', en: 'Ahmed Mahmoud' },
  'Ahmed Mahmoud': { ar: 'أحمد محمود', en: 'Ahmed Mahmoud' }
};

const rolesMap = {
  'مدير نظام عام': { ar: 'مدير نظام عام', en: 'Super Admin' },
  'Super Admin': { ar: 'مدير نظام عام', en: 'Super Admin' },
  'مشرف بنك الدم': { ar: 'مشرف بنك الدم', en: 'Blood Bank Admin' },
  'Blood Bank Admin': { ar: 'مشرف بنك الدم', en: 'Blood Bank Admin' }
};

const detailsMap = {
  'نظام المطابقة AI تعديل إعدادات خوارزمية': {
    ar: 'تعديل إعدادات خوارزمية نظام المطابقة الذكي (AI)',
    en: 'Updated AI Matching Algorithm System Settings'
  },
  '(مستشفى الشفاء) O+ إضافة حالة طارئة جديدة لفصيلة': {
    ar: 'إضافة حالة طارئة جديدة لفصيلة O+ (مستشفى الشفاء)',
    en: 'Added new O+ emergency case (Al-Shifa Hospital)'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const getRoleUserName = (name) => (usersMap[name] ? usersMap[name][currentLanguage.value === 'en' ? 'en' : 'ar'] : name);
const getRoleTitle = (role) => (rolesMap[role] ? rolesMap[role][currentLanguage.value === 'en' ? 'en' : 'ar'] : role);
const getDetailsText = (det) => (detailsMap[det] ? detailsMap[det][currentLanguage.value === 'en' ? 'en' : 'ar'] : det);

const getTimestampText = (timeStr) => {
  if (!timeStr) return '';
  if (currentLanguage.value === 'en') {
    return timeStr.replace('ص', 'AM').replace('م', 'PM');
  }
  return timeStr;
};

const displayLogs = computed(() => {
  return props.logsList && props.logsList.length ? props.logsList : accountsStore.auditLogs;
});

const getActionTypeText = (actionType) => {
  if (actionType === 'إضافة' || actionType === 'Add') return currentLanguage.value === 'en' ? 'Add' : 'إضافة';
  if (actionType === 'تعديل' || actionType === 'Edit') return currentLanguage.value === 'en' ? 'Edit' : 'تعديل';
  if (actionType === 'حذف' || actionType === 'Delete') return currentLanguage.value === 'en' ? 'Delete' : 'حذف';
  return actionType;
};

const getActionBadgeClass = (actionType) => {
  switch (actionType) {
    case 'إضافة': case 'Add': return 'bg-success-subtle text-success';
    case 'تعديل': case 'Edit': return 'bg-primary-subtle text-primary';
    case 'حذف': case 'Delete': return 'bg-danger-subtle text-danger';
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
.min-w-table { min-width: 680px; }
</style>
