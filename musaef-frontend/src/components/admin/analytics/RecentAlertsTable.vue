<template>
  <div
    class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-start d-flex flex-column justify-content-between position-relative"
    :dir="langStore.dir"
  >
    <transition name="fade">
      <div
        v-if="toast.show"
        class="toast-banner position-absolute top-0 start-0 end-0 p-2.5 rounded-top-4 text-center fs-9 fw-bold z-3 bg-dark text-white shadow-sm"
      >
        <span>{{ toast.message }}</span>
        <button
          type="button"
          class="btn-close btn-close-white ms-2 fs-9 align-middle"
          @click="toast.show = false"
        ></button>
      </div>
    </transition>

    <div>
      <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
        <span class="fs-5">🔔</span>
        <h5 class="fw-bold text-dark mb-0 fs-6">{{ t("title") }}</h5>
      </div>

      <div class="table-responsive">
        <table class="table align-middle text-center border-0 fs-8 mb-0 min-w-table">
          <thead class="text-muted fw-normal bg-light-subtle">
            <tr>
              <th class="py-2 text-start">{{ t("thTime") }}</th>
              <th class="py-2 text-start">{{ t("thHospital") }}</th>
              <th class="py-2">{{ t("thType") }}</th>
              <th class="py-2 text-start">{{ t("thStatus") }}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(notif, idx) in alertsList"
              :key="notif.id || idx"
              class="alert-row"
              @click="$emit('alert-click', notif)"
            >
              <td class="text-muted py-2.5 text-start fs-9 text-nowrap">
                {{ formatTime(notif.time) }}
              </td>
              <td class="fw-bold text-dark py-2.5 text-start text-nowrap">
                {{ translateHospitalName(notif.hospital) }}
              </td>
              <td class="fw-bold text-danger py-2.5 text-nowrap">{{ notif.type }}</td>
              <td class="py-2.5 text-start text-nowrap">
                <span
                  :class="[
                    'badge rounded-pill px-2.5 px-md-3 py-1 fs-9',
                    notif.statusBadge || getBadgeStyle(notif.status),
                  ]"
                >
                  ● {{ getStatusText(notif.status) }}
                </span>
              </td>
            </tr>
            <tr v-if="alertsList.length === 0">
              <td colspan="4" class="text-muted py-3 fs-9">{{ t("noAlerts") }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <a
      href="#"
      @click.prevent="handleViewAllAlerts"
      class="text-danger text-decoration-none fs-8 fw-bold mt-3 d-inline-block text-center cursor-pointer"
    >
      {{ isLoading ? t("loading") : t("viewAll") }}
    </a>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useAnalyticsStore } from "@/stores/analyticsStore";
import { useLangStore } from "@/stores/langStore";

defineProps({
  alertsList: {
    type: Array,
    default: () => [],
  },
});

defineEmits(["alert-click", "refresh"]);

const langStore = useLangStore();
const analyticsStore = useAnalyticsStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: {
    title: "آخر التنبيهات (AI Real-time)",
    thTime: "الوقت",
    thHospital: "المستشفى",
    thType: "الفصيلة",
    thStatus: "الحالة",
    viewAll: "عرض جميع التنبيهات >",
    loading: "جاري جلب كافة التنبيهات...",
    noAlerts: "لا توجد تنبيهات طارئة حالياً",
    successToast: "🔔 تم تحديث قائمة التنبيهات الفورية بنجاح!",
    urgent: "عاجل",
    moderate: "متوسط",
    stable: "مستقر",
  },
  en: {
    title: "Recent Alerts (AI Real-time)",
    thTime: "Time",
    thHospital: "Hospital",
    thType: "Type",
    thStatus: "Status",
    viewAll: "View All Alerts >",
    loading: "Fetching all alerts...",
    noAlerts: "No active alerts currently",
    successToast: "🔔 Real-time alerts updated successfully!",
    urgent: "Urgent",
    moderate: "Moderate",
    stable: "Stable",
  },
};

const t = (key) => dictionary[currentLanguage.value === "en" ? "en" : "ar"][key] || key;

const translateHospitalName = (name) => {
  if (!name) return "";
  if (currentLanguage.value === "en") {
    return name
      .replace(/مستشفى الشفاء الطبي/g, "Al-Shifa Hospital")
      .replace(/مستشفى ناصر الطبي/g, "Nasser Hospital")
      .replace(/مستشفى شهداء الأقصى/g, "Al-Aqsa Martyrs Hospital")
      .replace(/مستشفى العودة/g, "Al-Awda Hospital")
      .replace(/مستشفى الأوروبي/g, "European Gaza Hospital");
  }
  return name;
};

const formatTime = (timeStr) => {
  if (!timeStr) return "";
  return currentLanguage.value === "en"
    ? timeStr.replace("ص", "AM").replace("م", "PM")
    : timeStr;
};

const getStatusText = (status) => {
  if (status === "عاجل") return t("urgent");
  if (status === "متوسط") return t("moderate");
  if (status === "مستقر") return t("stable");
  return status;
};

const getBadgeStyle = (status) => {
  if (status === "عاجل") return "bg-danger-subtle text-danger";
  if (status === "متوسط") return "bg-warning-subtle text-warning";
  return "bg-success-subtle text-success";
};

const isLoading = ref(false);
const toast = ref({ show: false, message: "" });

const showNotification = (msg) => {
  toast.value = { show: true, message: msg };
  setTimeout(() => {
    toast.value.show = false;
  }, 4000);
};

const handleViewAllAlerts = async () => {
  isLoading.value = true;
  await analyticsStore.fetchRecentAlerts();
  showNotification(t("successToast"));
  isLoading.value = false;
};
</script>

<style scoped>
.fs-6 {
  font-size: 1.05rem;
}
.fs-8 {
  font-size: 0.8rem;
}
.fs-9 {
  font-size: 0.72rem;
}
.cursor-pointer {
  cursor: pointer;
}

.min-w-table {
  min-width: 380px;
}

.alert-row {
  cursor: pointer;
  transition: background-color 0.2s ease;
}
.alert-row:hover {
  background-color: #f8fafc;
}

.bg-danger-subtle {
  background-color: #fee2e2 !important;
}
.bg-success-subtle {
  background-color: #d1fae5 !important;
}
.bg-warning-subtle {
  background-color: #fef3c7 !important;
}
.bg-light-subtle {
  background-color: #f9fafb !important;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
