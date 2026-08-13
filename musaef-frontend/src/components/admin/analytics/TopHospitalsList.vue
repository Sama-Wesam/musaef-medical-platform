<template>
  <div
    class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-start d-flex flex-column justify-content-between position-relative"
    :dir="langStore.dir"
  >
    <transition name="fade">
      <div
        v-if="toast.show"
        class="toast-banner position-absolute top-0 start-0 end-0 p-2.5 rounded-top-4 text-center fs-9 fw-bold z-3 bg-danger text-white shadow-sm"
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
      <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
        <img
          :src="getIconUrl('Group 1000002306 (1).png')"
          alt="hospital icon"
          width="24"
          height="24"
          class="header-icon"
        />
        <h5 class="fw-bold text-dark mb-0 fs-6">{{ t("title") }}</h5>
      </div>

      <div class="d-flex flex-column gap-2.5 gap-md-3 fs-8">
        <div
          v-for="(h, i) in topHospitals"
          :key="h.id || h.name"
          class="d-flex align-items-center gap-2 hospital-row"
          @click="$emit('hospital-click', h)"
        >
          <span
            class="rank-circle text-white fw-bold fs-9 rounded-circle d-flex align-items-center justify-content-center"
            :style="{ backgroundColor: h.color || '#DC2626' }"
          >
            {{ i + 1 }}
          </span>
          <span
            class="text-dark fw-bold text-start text-truncate"
            style="min-width: 100px; max-width: 140px"
          >
            {{ translateHospitalName(h.name) }}
          </span>
          <div class="progress flex-grow-1 bg-light rounded-pill" style="height: 8px">
            <div
              class="progress-bar rounded-pill"
              :style="{
                width: (h.percent || 0) + '%',
                backgroundColor: h.color || '#DC2626',
              }"
            ></div>
          </div>
          <span class="text-muted fs-9 fw-bold w-35px text-end flex-shrink-0"
            >{{ h.percent || 0 }}%</span
          >
        </div>
        <div v-if="topHospitals.length === 0" class="text-center text-muted py-3 fs-9">
          {{ t("noData") }}
        </div>
      </div>
    </div>

    <a
      href="#"
      @click.prevent="handleViewAllHospitals"
      class="text-danger text-decoration-none fs-8 fw-bold mt-3 mt-md-4 d-inline-block text-center cursor-pointer"
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
  topHospitals: {
    type: Array,
    required: true,
  },
});

defineEmits(["hospital-click"]);

const langStore = useLangStore();
const analyticsStore = useAnalyticsStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: {
    title: "أكثر المستشفيات احتياجاً (Facility Recommendation AI)",
    viewAll: "تحديث وتحليل قائمة المستشفيات >",
    loading: "جاري تحليل كفاءة المستشفيات...",
    noData: "لا توجد بيانات احتياج حالية",
    successToast: "🏥 تم تحليل تحديثات قائمة المستشفيات بنجاح!",
  },
  en: {
    title: "Hospitals in Highest Need (Facility Recommendation AI)",
    viewAll: "Refresh & Analyze Hospitals >",
    loading: "Analyzing hospital efficiency...",
    noData: "No need data available",
    successToast: "🏥 Hospital needs data refreshed successfully!",
  },
};

const t = (key) => dictionary[currentLanguage.value === "en" ? "en" : "ar"][key] || key;

const translateHospitalName = (name) => {
  if (!name) return "";
  if (currentLanguage.value === "en") {
    return name
      .replace(/مستشفى الشفاء الطبي/g, "Al-Shifa Hospital")
      .replace(/مستشفى الشفاء/g, "Al-Shifa Hospital")
      .replace(/مستشفى ناصر الطبي/g, "Nasser Hospital")
      .replace(/مستشفى ناصر/g, "Nasser Hospital")
      .replace(/مستشفى الأوروبي/g, "European Gaza Hospital")
      .replace(/مستشفى العودة/g, "Al-Awda Hospital")
      .replace(/مستشفى شهداء الأقصى/g, "Al-Aqsa Martyrs Hospital");
  }
  return name;
};

const isLoading = ref(false);
const toast = ref({ show: false, message: "" });

const showNotification = (msg) => {
  toast.value = { show: true, message: msg };
  setTimeout(() => {
    toast.value.show = false;
  }, 4000);
};

const handleViewAllHospitals = async () => {
  isLoading.value = true;
  await analyticsStore.fetchAnalyticsData();
  showNotification(t("successToast"));
  isLoading.value = false;
};

const getIconUrl = (fileName) => {
  if (!fileName) return "";
  if (fileName.startsWith("http") || fileName.startsWith("data:")) return fileName;
  try {
    return new URL(`../../../assets/icons/${fileName}`, import.meta.url).href;
  } catch (e) {
    return "";
  }
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

.rank-circle {
  width: 22px;
  height: 22px;
  min-width: 22px;
  flex-shrink: 0;
}

.hospital-row {
  cursor: pointer;
  padding: 4px;
  border-radius: 6px;
  transition: background-color 0.2s ease;
}
.hospital-row:hover {
  background-color: #f8fafc;
}

.header-icon {
  width: 24px;
  height: 24px;
}
@media (min-width: 768px) {
  .header-icon {
    width: 28px;
    height: 28px;
  }
}

.w-35px {
  width: 35px;
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
