<template>
  <AdminLayout>
    <div
      class="accounts-management-view container-fluid px-2 px-md-3"
      :dir="langStore.dir"
    >
      <!-- 1. التبويبات العلوية الرئيسية -->
      <div
        class="main-tabs-header border-bottom mb-4 overflow-x-auto tabs-scroll-container"
      >
        <div
          class="d-flex align-items-center justify-content-start gap-3 gap-md-5 ps-3 min-tabs-width"
        >
          <button
            class="tab-item btn border-0 py-3 text-muted fs-8 fw-semibold d-flex align-items-center gap-2 text-nowrap"
            :class="{ 'active-tab': accountsStore.activeTab === 'donors' }"
            @click="switchTab('donors')"
          >
            <img
              :src="getIconUrl('Vector (2).png')"
              alt="donors icon"
              width="18"
              height="18"
            />
            <span>{{ t("donorsTab") }}</span>
          </button>

          <button
            class="tab-item btn border-0 py-3 text-muted fs-8 fw-semibold d-flex align-items-center gap-2 text-nowrap"
            :class="{ 'active-tab': accountsStore.activeTab === 'hospitals' }"
            @click="switchTab('hospitals')"
          >
            <img
              :src="getIconUrl('solar_hospital-linear (2).png')"
              alt="hospitals icon"
              width="18"
              height="18"
            />
            <span>{{ t("hospitalsTab") }}</span>
          </button>

          <button
            class="tab-item btn border-0 py-3 text-muted fs-8 fw-semibold d-flex align-items-center gap-2 text-nowrap"
            :class="{ 'active-tab': accountsStore.activeTab === 'roles' }"
            @click="switchTab('roles')"
          >
            <img
              :src="getIconUrl('Vector (1).png')"
              alt="roles icon"
              width="18"
              height="18"
            />
            <span>{{ t("rolesTab") }}</span>
          </button>

          <button
            class="tab-item btn border-0 py-3 text-muted fs-8 fw-semibold d-flex align-items-center gap-2 text-nowrap"
            :class="{ 'active-tab': accountsStore.activeTab === 'logs' }"
            @click="switchTab('logs')"
          >
            <img
              :src="getIconUrl('el_list-alt (2).png')"
              alt="logs icon"
              width="18"
              height="18"
            />
            <span>{{ t("logsTab") }}</span>
          </button>
        </div>
      </div>

      <!-- 2. محتوى التبويبات -->
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

      <!-- 3. التنقل والصفحات -->
      <AccountsPagination />
    </div>
  </AdminLayout>
</template>

<script setup>
import { onMounted, onUnmounted, computed, watch } from "vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import { useAccountsStore } from "@/stores/accountsStore";
import { useLangStore } from "@/stores/langStore";

import DonorsTab from "@/components/admin/accountsmanagement/DonorsTab.vue";
import HospitalsTab from "@/components/admin/accountsmanagement/HospitalsTab.vue";
import RolesTab from "@/components/admin/accountsmanagement/RolesTab.vue";
import LogsTab from "@/components/admin/accountsmanagement/LogsTab.vue";
import AccountsPagination from "@/components/admin/accountsmanagement/AccountsPagination.vue";

const accountsStore = useAccountsStore();
const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: {
    donorsTab: "المتبرعون",
    hospitalsTab: "المستشفيات",
    rolesTab: "الصلاحيات والأدوار",
    logsTab: "سجل العمليات",
  },
  en: {
    donorsTab: "Donors",
    hospitalsTab: "Hospitals",
    rolesTab: "Roles & Permissions",
    logsTab: "Audit Logs",
  },
};

const t = (key) => dictionary[currentLanguage.value === "en" ? "en" : "ar"][key] || key;

const switchTab = (tab) => {
  accountsStore.activeTab = tab;
  accountsStore.searchQuery = "";
  accountsStore.selectedFilter = "all";
  accountsStore.currentPage = 1;
  accountsStore.refreshCurrentTab();
};

const filteredDonors = computed(() => accountsStore.donors);
const filteredHospitals = computed(() => accountsStore.hospitals);
const filteredRoles = computed(() => accountsStore.roles);
const filteredLogs = computed(() => accountsStore.auditLogs);

const getIconUrl = (fileName) => {
  if (!fileName) return "";
  if (fileName.startsWith("http") || fileName.startsWith("data:")) return fileName;
  try {
    return new URL(`../../assets/icons/${fileName}`, import.meta.url).href;
  } catch (e) {
    return "";
  }
};

watch([() => accountsStore.searchQuery, () => accountsStore.selectedFilter], () => {
  accountsStore.refreshCurrentTab();
});

onMounted(() => {
  accountsStore.startPolling(5000);
});

onUnmounted(() => {
  accountsStore.stopPolling();
});
</script>

<style scoped>
.accounts-management-view {
  font-family: "Cairo", sans-serif;
  padding-bottom: 24px;
}

.fs-8 {
  font-size: 0.82rem;
}

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
  cursor: pointer;
}

.tab-item.active-tab {
  color: #dc2626 !important;
  font-weight: 700 !important;
}

.tab-item.active-tab::after {
  content: "";
  position: absolute;
  bottom: -1px;
  left: 0;
  right: 0;
  height: 3px;
  background-color: #dc2626;
  border-radius: 3px 3px 0 0;
}
</style>
