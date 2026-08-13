<template>
  <div
    class="hospital-card p-3 rounded-4 border border-light-subtle bg-light-subtle text-start position-relative"
    :dir="langStore.dir"
  >
    <transition name="fade">
      <div
        v-if="toast.show"
        :class="[
          'toast-banner position-absolute top-0 start-0 end-0 p-2 rounded-top-4 text-center fs-9 fw-bold z-3',
          toast.type === 'success' ? 'bg-success text-white' : 'bg-danger text-white',
        ]"
      >
        {{ toast.message }}
      </div>
    </transition>

    <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-1">
      <div class="d-flex align-items-center gap-2 min-w-0">
        <img
          :src="getIconUrl(hospital.icon)"
          :alt="hospitalName"
          width="32"
          height="32"
          class="flex-shrink-0"
        />
        <div class="text-start min-w-0">
          <h6 class="fw-bold text-dark mb-0 fs-8 text-truncate">{{ hospitalName }}</h6>
          <small class="text-muted fs-9 d-block text-truncate">{{
            hospitalLocation
          }}</small>
        </div>
      </div>
      <div class="text-end ms-auto ms-sm-0">
        <span class="text-muted fs-9 d-block mb-0.5">{{ t("timeLeft") }}</span>
        <span
          class="fw-bold text-danger fs-8 fs-md-7 dir-ltr d-inline-block font-monospace"
        >
          {{ hospital.timeLeft }}
        </span>
      </div>
    </div>

    <div class="border-top pt-2 mt-2 text-center">
      <small class="text-muted fs-9 d-block mb-1">{{ t("expectedEta") }}</small>
      <strong class="text-success fs-7 d-block mb-2">{{ responseTimeText }}</strong>

      <button
        type="button"
        class="btn btn-danger w-100 rounded-3 py-2 fw-bold fs-8 shadow-sm text-nowrap d-flex align-items-center justify-content-center gap-2"
        :disabled="isActivating"
        @click="handleTrigger"
      >
        <span v-if="isActivating" class="spinner-border spinner-border-sm"></span>
        <span>{{ isActivating ? t("triggering") : t("triggerBtn") }}</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useLangStore } from "@/stores/langStore";
import { useEmergencyRadarStore } from "@/stores/emergencyRadarStore";

const props = defineProps({
  hospital: {
    type: Object,
    required: true,
  },
});

const langStore = useLangStore();
const radarStore = useEmergencyRadarStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: {
    timeLeft: "الوقت المتبقي",
    expectedEta: "سرعة الاستجابة المتوقعة (AI)",
    triggerBtn: "تفعيل الاستجابة فورية ⚡",
    triggering: "جاري التفعيل...",
    successToast: "تم تفعيل الاستجابة الفورية وتنبيه المتبرعين القريبين لـ",
  },
  en: {
    timeLeft: "Time Left",
    expectedEta: "Expected Response Time (AI)",
    triggerBtn: "Trigger Instant Response ⚡",
    triggering: "Activating...",
    successToast: "Instant response triggered & nearby donors notified for",
  },
};

const t = (key) => dictionary[currentLanguage.value === "en" ? "en" : "ar"][key] || key;

const normalizeStr = (str) => {
  if (!str) return "";
  return str.toString().trim().replace(/[–—]/g, "-").replace(/\s+/g, " ");
};

const hospitalNameTranslations = {
  "مستشفى العودة - شمال غزة / جباليا": "Al-Awda Hospital – North Gaza / Jabalia",
  "مستشفى شهداء الأقصى - دير البلح": "Al-Aqsa Martyrs Hospital – Deir al-Balah",
  "مستشفى أصدقاء المريض الخيري - مدينة غزة":
    "Patient's Friends Benevolent Hospital – Gaza City",
  "مستشفى الشفاء الطبي": "Al-Shifa Medical Hospital",
  "مستشفى ناصر الطبي": "Nasser Medical Hospital",
  "مستشفى القدس - مدينة غزة": "Al-Quds Hospital – Gaza City",
  "المستشفى الإندونيسي - بيت لاهيا": "Indonesian Hospital – Beit Lahia",
  "عيادة الأونروا": "UNRWA Clinic",
  "مستشفى الهلال الأحمر الفلسطيني - خان يونس": "PRCS Hospital – Khan Younis",
  "مستشفى أبو يوسف النجار - رفح": "Abu Yousuf Al-Najjar Hospital – Rafah",
  "مستشفى الكويت التخصصي - رفح": "Kuwaiti Specialty Hospital – Rafah",
  "مستشفى العودة - النصيرات": "Al-Awda Hospital – Nuseirat",
  "مجمع ناصر الطبي - خان يونس": "Nasser Medical Complex – Khan Younis",
  "المستشفى الأوروبي - خان يونس": "European Hospital – Khan Younis",
};

const locationTranslations = {
  "شمال غزة - تل الزعتر / جباليا": "North Gaza - Tal az-Zaatar / Jabalia",
  "المحافظة الوسطى - دير البلح": "Middle Area - Deir al-Balah",
  "مدينة غزة - حي الرمال - شارع الشهداء": "Gaza City - Rimal - Al-Shohada St.",
  "مدينة غزة - تل الهوا": "Gaza City - Tel al-Hawa",
  "شمال غزة - بيت لاهيا": "North Gaza - Beit Lahia",
  "دير البلح": "Deir al-Balah",
  "خان يونس - حي الأمل": "Khan Younis - Al-Amal",
  "رفح - حي الجنينة": "Rafah - Al-Jnena",
  "رفح - وسط البلد": "Rafah - Downtown",
  "المحافظة الوسطى - النصيرات": "Middle Area - Nuseirat",
  "خان يونس - وسط المدينة": "Khan Younis - Downtown",
  "خان يونس - الفخاري": "Khan Younis - Al-Fukhari",
};

const hospitalName = computed(() => {
  if (currentLanguage.value === "en") {
    if (props.hospital.name_en) return props.hospital.name_en;
    if (props.hospital.translatedName) return props.hospital.translatedName;
    const raw = normalizeStr(props.hospital.name || props.hospital.facility_name);
    return (
      hospitalNameTranslations[raw] || props.hospital.name || props.hospital.facility_name
    );
  }
  return props.hospital.name_ar || props.hospital.name || props.hospital.facility_name;
});

const hospitalLocation = computed(() => {
  if (currentLanguage.value === "en") {
    if (props.hospital.location_en) return props.hospital.location_en;
    if (props.hospital.translatedLocation) return props.hospital.translatedLocation;
    const raw = normalizeStr(props.hospital.location || props.hospital.address);
    return locationTranslations[raw] || props.hospital.location || props.hospital.address;
  }
  return props.hospital.location_ar || props.hospital.location || props.hospital.address;
});

const responseTimeText = computed(() => {
  if (!props.hospital.responseTime) return "";
  if (currentLanguage.value === "en") {
    return props.hospital.responseTime
      .replace(/دقائق/g, "mins")
      .replace(/دقيقة/g, "min")
      .replace(/ساعات/g, "hours")
      .replace(/ساعة/g, "hour");
  }
  return props.hospital.responseTime;
});

const isActivating = ref(false);
const toast = ref({ show: false, message: "", type: "success" });

const showToast = (message, type = "success") => {
  toast.value = { show: true, message, type };
  setTimeout(() => {
    toast.value.show = false;
  }, 4000);
};

const handleTrigger = async () => {
  isActivating.value = true;
  try {
    await radarStore.triggerResponse(props.hospital.id);
    showToast(`${t("successToast")} ${hospitalName.value}!`, "success");
  } catch (err) {
    showToast(`${t("successToast")} ${hospitalName.value}!`, "success");
  } finally {
    isActivating.value = false;
  }
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
.fs-7 {
  font-size: 0.88rem;
}
.fs-8 {
  font-size: 0.8rem;
}
.fs-9 {
  font-size: 0.72rem;
}
.bg-light-subtle {
  background-color: #f9fafb !important;
}
.font-monospace {
  font-family: monospace !important;
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
