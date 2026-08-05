<template>
  <div class="roles-tab-content" :dir="langStore.dir">
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
            style="min-width: 170px;"
            :value="selectedFilter"
            @change="$emit('update:selectedFilter', $event.target.value)"
          >
            <option value="all">{{ t('allRoles') }}</option>
            <option value="مدير نظام عام">{{ t('superAdmin') }}</option>
            <option value="Blood Bank Admin">{{ t('bloodBankAdmin') }}</option>
            <option value="Hospital Admin">{{ t('hospitalAdmin') }}</option>
            <option value="دعم فني وتقني">{{ t('techSupport') }}</option>
          </select>
        </div>
        <div class="w-100 w-sm-auto text-end">
          <button
            class="btn btn-danger rounded-3 px-4 py-2 fs-8 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 w-100 w-sm-auto"
            @click="accountsStore.addItem(t('roleWord'))"
          >
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>{{ t('addRole') }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- جدول بيانات الصلاحيات -->
    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-4 overflow-hidden">
      <div class="table-responsive">
        <div class="min-w-table">
          <div class="row text-center fw-bold text-dark fs-8 py-2 px-3 mb-3 border-bottom border-light text-nowrap">
            <div class="col-3 text-start ps-3">{{ t('colUser') }}</div>
            <div class="col-2">{{ t('colRoleTitle') }}</div>
            <div class="col-2">{{ t('colEmail') }}</div>
            <div class="col-2">{{ t('colScope') }}</div>
            <div class="col-1">{{ t('colStatus') }}</div>
            <div class="col-2 text-center">{{ t('colActions') }}</div>
          </div>

          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="role in displayRoles"
              :key="role.id"
              class="row align-items-center text-center py-3 px-3 rounded-4 bg-light-subtle row-card border border-light-subtle transition-all text-nowrap"
            >
              <div class="col-3 text-start fw-bold text-dark fs-8 d-flex align-items-center justify-content-start gap-2 ps-3 min-w-0">
                <span class="role-icon-circle bg-primary-subtle text-primary rounded-circle p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width:28px; height:28px;">
                  🛡️
                </span>
                <span class="text-truncate">{{ getRoleUserName(role.name) }}</span>
              </div>
              <div class="col-2 fw-bold fs-8 text-primary text-truncate">{{ getRoleTitle(role.roleTitle) }}</div>
              <div class="col-2 text-muted fs-8 dir-ltr text-truncate">{{ role.email }}</div>
              <div class="col-2 text-muted fs-8 text-truncate">{{ getScopeText(role.scope) }}</div>
              <div class="col-1">
                <span class="fw-bold fs-8" :class="getStatusClass(role.status)">{{ getStatusText(role.status) }}</span>
              </div>
              <!-- عمود الإجراءات / أزرار التعديل والحذف التفاعلية -->
              <div class="col-2 d-flex align-items-center justify-content-center gap-2">
                <button
                  class="btn btn-sm btn-outline-warning text-dark border-0 bg-warning-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :title="t('edit')"
                  @click="accountsStore.editItem(role, t('roleWord'))"
                >
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                  <span>{{ t('edit') }}</span>
                </button>
                <button
                  class="btn btn-sm btn-outline-danger text-danger border-0 bg-danger-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :title="t('delete')"
                  @click="accountsStore.deleteItem(role.id, t('roleWord'))"
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
  rolesList: Array
});

defineEmits(['update:searchQuery', 'update:selectedFilter']);

const dictionary = {
  ar: {
    searchPlaceholder: 'ابحث عن مدير أو نوع الصلاحية...',
    allRoles: 'جميع الصلاحيات',
    superAdmin: 'مدير نظام (Super Admin)',
    bloodBankAdmin: 'مشرف بنك الدم',
    hospitalAdmin: 'مسؤول مستشفى',
    techSupport: 'دعم فني',
    addRole: 'إضافة دور / صلاحية',
    colUser: 'الاسم / الحساب',
    colRoleTitle: 'الرتبة / مستوى الصلاحية',
    colEmail: 'البريد الإلكتروني',
    colScope: 'نطاق الوصول',
    colStatus: 'الحالة',
    colActions: 'الإجراءات',
    edit: 'تعديل',
    delete: 'حذف',
    roleWord: 'صلاحية'
  },
  en: {
    searchPlaceholder: 'Search for admin or permission level...',
    allRoles: 'All Permissions',
    superAdmin: 'Super Admin',
    bloodBankAdmin: 'Blood Bank Admin',
    hospitalAdmin: 'Hospital Admin',
    techSupport: 'Technical Support',
    addRole: 'Add Role / Permission',
    colUser: 'Name / Account',
    colRoleTitle: 'Role / Permission Level',
    colEmail: 'Email Address',
    colScope: 'Access Scope',
    colStatus: 'Status',
    colActions: 'Actions',
    edit: 'Edit',
    delete: 'Delete',
    roleWord: 'Permission'
  }
};

const rolesUsersMap = {
  'د. سعيد عيده': { ar: 'د. سعيد عيده', en: 'Dr. Saeed Eideh' },
  'Dr. Saeed Eideh': { ar: 'د. سعيد عيده', en: 'Dr. Saeed Eideh' },
  'أحمد محمود': { ar: 'أحمد محمود', en: 'Ahmed Mahmoud' },
  'Ahmed Mahmoud': { ar: 'أحمد محمود', en: 'Ahmed Mahmoud' },
  'د. سارة خليل': { ar: 'د. سارة خليل', en: 'Dr. Sarah Khalil' },
  'Dr. Sarah Khalil': { ar: 'د. سارة خليل', en: 'Dr. Sarah Khalil' },
  'م. خالد حسن': { ar: 'م. خالد حسن', en: 'Eng. Khaled Hassan' },
  'Eng. Khaled Hassan': { ar: 'م. خالد حسن', en: 'Eng. Khaled Hassan' },
  'إيمان علي': { ar: 'إيمان علي', en: 'Eman Ali' },
  'Eman Ali': { ar: 'إيمان علي', en: 'Eman Ali' },
  'د. يوسف ناصر': { ar: 'د. يوسف ناصر', en: 'Dr. Youssef Nasser' },
  'Dr. Youssef Nasser': { ar: 'د. يوسف ناصر', en: 'Dr. Youssef Nasser' }
};

const rolesTitlesMap = {
  'مدير نظام عام': { ar: 'مدير نظام عام', en: 'Super Admin' },
  'Super Admin': { ar: 'مدير نظام عام', en: 'Super Admin' },
  'Blood Bank Admin': { ar: 'مشرف بنك الدم', en: 'Blood Bank Admin' },
  'مشرف بنك الدم': { ar: 'مشرف بنك الدم', en: 'Blood Bank Admin' },
  'Hospital Admin': { ar: 'مسؤول مستشفى', en: 'Hospital Admin' },
  'مسؤول مستشفى': { ar: 'مسؤول مستشفى', en: 'Hospital Admin' },
  'دعم فني وتقني': { ar: 'دعم فني وتقني', en: 'Tech Support' },
  'Tech Support': { ar: 'دعم فني وتقني', en: 'Tech Support' },
  'مرحل طوارئ': { ar: 'مرحل طوارئ', en: 'Emergency Dispatcher' },
  'Emergency Dispatcher': { ar: 'مرحل طوارئ', en: 'Emergency Dispatcher' }
};

const scopesMap = {
  'الوصول الكامل': { ar: 'الوصول الكامل', en: 'Full Access' },
  'Full Access': { ar: 'الوصول الكامل', en: 'Full Access' },
  'إدارة الطلبات والمتبرعين': { ar: 'إدارة الطلبات والمتبرعين', en: 'Requests & Donors Mgmt' },
  'Requests & Donors Mgmt': { ar: 'إدارة الطلبات والمتبرعين', en: 'Requests & Donors Mgmt' },
  'مستشفى الشفاء الطبي': { ar: 'مستشفى الشفاء الطبي', en: 'Al-Shifa Hospital' },
  'Al-Shifa Hospital': { ar: 'مستشفى الشفاء الطبي', en: 'Al-Shifa Hospital' },
  'السجلات والسيرفرات': { ar: 'السجلات والسيرفرات', en: 'Logs & Servers' },
  'Logs & Servers': { ar: 'السجلات والسيرفرات', en: 'Logs & Servers' },
  'رادار الطوارئ والنداءات': { ar: 'رادار الطوارئ والنداءات', en: 'Emergency Radar' },
  'Emergency Radar': { ar: 'رادار الطوارئ والنداءات', en: 'Emergency Radar' },
  'مستشفى ناصر الطبي': { ar: 'مستشفى ناصر الطبي', en: 'Nasser Hospital' },
  'Nasser Hospital': { ar: 'مستشفى ناصر الطبي', en: 'Nasser Hospital' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const getRoleUserName = (name) => (rolesUsersMap[name] ? rolesUsersMap[name][currentLanguage.value === 'en' ? 'en' : 'ar'] : name);
const getRoleTitle = (title) => (rolesTitlesMap[title] ? rolesTitlesMap[title][currentLanguage.value === 'en' ? 'en' : 'ar'] : title);
const getScopeText = (scope) => (scopesMap[scope] ? scopesMap[scope][currentLanguage.value === 'en' ? 'en' : 'ar'] : scope);

const displayRoles = computed(() => {
  return props.rolesList && props.rolesList.length ? props.rolesList : accountsStore.roles;
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
.bg-primary-subtle { background-color: #dbeafe !important; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.row-card:hover { background-color: #f3f4f6 !important; }
.action-btn { transition: all 0.2s ease; cursor: pointer; }
.action-btn:hover { transform: translateY(-1px); filter: brightness(0.95); }
.min-w-table { min-width: 750px; }
</style>
