<template>
  <AdminLayout>
    <div class="accounts-management-view container-fluid px-2 px-md-3" dir="rtl">

      <!-- 1. التبويبات العلوية الرئيسية -->
      <div class="main-tabs-header border-bottom mb-4 overflow-x-auto tabs-scroll-container">
        <div class="d-flex align-items-center justify-content-start gap-3 gap-md-5 ps-3 min-tabs-width">

          <!-- 1. المتبرعون -->
          <button
            class="tab-item btn border-0 py-3 text-muted fs-8 fw-semibold d-flex align-items-center gap-2 text-nowrap"
            :class="{ 'active-tab': accountsStore.activeTab === 'donors' }"
            @click="switchTab('donors')"
          >
            <img :src="getIconUrl('Vector (2).png')" alt="donors icon" width="18" height="18" />
            <span>المتبرعون</span>
          </button>

          <!-- 2. المستشفيات -->
          <button
            class="tab-item btn border-0 py-3 text-muted fs-8 fw-semibold d-flex align-items-center gap-2 text-nowrap"
            :class="{ 'active-tab': accountsStore.activeTab === 'hospitals' }"
            @click="switchTab('hospitals')"
          >
            <img :src="getIconUrl('solar_hospital-linear (2).png')" alt="hospitals icon" width="18" height="18" />
            <span>المستشفيات</span>
          </button>

          <!-- 3. الصلاحيات -->
          <button
            class="tab-item btn border-0 py-3 text-muted fs-8 fw-semibold d-flex align-items-center gap-2 text-nowrap"
            :class="{ 'active-tab': accountsStore.activeTab === 'roles' }"
            @click="switchTab('roles')"
          >
            <img :src="getIconUrl('Vector (1).png')" alt="roles icon" width="18" height="18" />
            <span>الصلاحيات</span>
          </button>

          <!-- 4. سجل العمليات -->
          <button
            class="tab-item btn border-0 py-3 text-muted fs-8 fw-semibold d-flex align-items-center gap-2 text-nowrap"
            :class="{ 'active-tab': accountsStore.activeTab === 'logs' }"
            @click="switchTab('logs')"
          >
            <img :src="getIconUrl('el_list-alt (2).png')" alt="logs icon" width="18" height="18" />
            <span>سجل العمليات</span>
          </button>

        </div>
      </div>

      <!-- 2 & 3. التبويبات والمحتويات المصحوبة بالبيانات من الـ Store -->
      <DonorsTab
        v-if="accountsStore.activeTab === 'donors'"
        v-model:searchQuery="accountsStore.searchQuery"
        v-model:selectedFilter="accountsStore.selectedFilter"
        :donorsList="filteredDonors"
      />

      <HospitalsTab
        v-else-if="accountsStore.activeTab === 'hospitals'"
        v-model:searchQuery="accountsStore.searchQuery"
        v-model:selectedFilter="accountsStore.selectedFilter"
        :hospitalsList="filteredHospitals"
      />

      <RolesTab
        v-else-if="accountsStore.activeTab === 'roles'"
        v-model:searchQuery="accountsStore.searchQuery"
        v-model:selectedFilter="accountsStore.selectedFilter"
        :rolesList="filteredRoles"
      />

      <LogsTab
        v-else-if="accountsStore.activeTab === 'logs'"
        v-model:searchQuery="accountsStore.searchQuery"
        v-model:selectedFilter="accountsStore.selectedFilter"
        :logsList="filteredLogs"
      />

      <!-- 4. أرقام التنقل والصفحات (Pagination) -->
      <AccountsPagination />

    </div>
  </AdminLayout>
</template>

<script setup>
import { onMounted, computed, watch } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { useAccountsStore } from '@/stores/accountsStore';

import DonorsTab from '@/components/admin/accountsmanagement/DonorsTab.vue';
import HospitalsTab from '@/components/admin/accountsmanagement/HospitalsTab.vue';
import RolesTab from '@/components/admin/accountsmanagement/RolesTab.vue';
import LogsTab from '@/components/admin/accountsmanagement/LogsTab.vue';
import AccountsPagination from '@/components/admin/accountsmanagement/AccountsPagination.vue';

const accountsStore = useAccountsStore();

const switchTab = (tab) => {
  accountsStore.activeTab = tab;
  accountsStore.searchQuery = '';
  accountsStore.selectedFilter = 'all';
  accountsStore.refreshCurrentTab();
};

// تصفية حية لحظية محلياً وفي الباك إند
const filteredDonors = computed(() => {
  if (!accountsStore.searchQuery) return accountsStore.donors;
  return accountsStore.donors.filter(d => d.name.includes(accountsStore.searchQuery) || d.phone.includes(accountsStore.searchQuery));
});

const filteredHospitals = computed(() => {
  if (!accountsStore.searchQuery) return accountsStore.hospitals;
  return accountsStore.hospitals.filter(h => h.name.includes(accountsStore.searchQuery) || h.location.includes(accountsStore.searchQuery));
});

const filteredRoles = computed(() => {
  if (!accountsStore.searchQuery) return accountsStore.roles;
  return accountsStore.roles.filter(r => r.name.includes(accountsStore.searchQuery) || r.roleTitle.includes(accountsStore.searchQuery));
});

const filteredLogs = computed(() => {
  if (!accountsStore.searchQuery) return accountsStore.auditLogs;
  return accountsStore.auditLogs.filter(l => l.user.includes(accountsStore.searchQuery) || l.details.includes(accountsStore.searchQuery));
});

// دالة محدثة لجلب الأيقونات من المسار الصحيح للمجلد المطلوب
const getIconUrl = (fileName) => {
  if (!fileName) return '';
  if (fileName.startsWith('http') || fileName.startsWith('data:')) return fileName;
  try {
    return new URL(`../../assets/icons/${fileName}`, import.meta.url).href;
  } catch (e) {
    return '';
  }
};

watch([() => accountsStore.searchQuery, () => accountsStore.selectedFilter], () => {
  accountsStore.refreshCurrentTab();
});

onMounted(() => {
  accountsStore.fetchDonors();
});
</script>

<style scoped>
.accounts-management-view {
  font-family: 'Cairo', sans-serif;
  padding-bottom: 24px;
}

.fs-8 { font-size: 0.82rem; }

.tabs-scroll-container {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.tabs-scroll-container::-webkit-scrollbar {
  display: none;
}

.min-tabs-width {
  min-width: 480px;
}

@media (min-width: 768px) {
  .min-tabs-width {
    min-width: 100%;
  }
}

.tab-item {
  position: relative;
  transition: all 0.2s ease;
}

.tab-item.active-tab {
  color: #dc2626 !important;
  font-weight: 700 !important;
}

.tab-item.active-tab::after {
  content: '';
  position: absolute;
  bottom: -1px;
  right: 0;
  width: 100%;
  height: 3px;
  background-color: #dc2626;
  border-radius: 3px 3px 0 0;
}
</style>
