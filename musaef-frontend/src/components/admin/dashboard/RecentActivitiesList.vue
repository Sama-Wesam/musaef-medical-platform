<template>
  <div
    class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 d-flex flex-column justify-content-between"
    :class="langStore.isRtl ? 'dir-rtl text-end' : 'dir-ltr text-start'"
  >
    <div>
      <h6 class="fw-bold text-dark mb-3 mb-md-4 fs-7">{{ t("recentTitle") }}</h6>

      <div class="d-flex flex-column gap-2 gap-md-3 mb-3">
        <div
          v-for="(activity, index) in activities"
          :key="activity.id || index"
          class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light-subtle flex-wrap gap-2"
        >
          <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
            <img
              :src="
                getIconUrl(`Frame 2147225971${index > 0 ? ' (' + index + ')' : ''}.png`)
              "
              alt="activity icon"
              width="32"
              height="32"
              class="flex-shrink-0"
            />
            <div class="min-w-0">
              <span
                class="fw-bold fs-8 d-block mb-0.5 text-truncate"
                :class="index === 0 ? 'text-danger' : 'text-dark'"
                >{{ translateTitle(activity.title) }}</span
              >
              <small class="text-muted fs-9 text-truncate d-block">{{
                translateSubtitle(activity.subtitle)
              }}</small>
            </div>
          </div>
          <small class="text-muted fs-9 text-nowrap ms-auto ms-sm-0">{{
            activity.time
          }}</small>
        </div>
      </div>
    </div>

    <a
      href="#"
      @click.prevent="handleViewAllActivities"
      class="text-danger text-decoration-none fs-8 fw-bold mt-2 d-inline-block cursor-pointer"
      :class="langStore.isRtl ? 'text-end' : 'text-start'"
    >
      {{ t("viewAllBtn") }}
    </a>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useRouter } from "vue-router";
import { useLangStore } from "@/stores/langStore";

const props = defineProps({
  activities: {
    type: Array,
    default: () => [],
  },
});

const router = useRouter();
const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: { recentTitle: "النشاطات الأخيرة", viewAllBtn: "عرض جميع النشاطات <" },
  en: { recentTitle: "Recent Activities", viewAllBtn: "View All Activities >" },
};

const t = (key) => dictionary[currentLanguage.value === "en" ? "en" : "ar"][key] || key;

const translateTitle = (title) => {
  if (!title) return "";
  if (currentLanguage.value === "en") {
    return title
      .replace(/طلب طارئ لفصيلة/g, "Emergency request for blood type")
      .replace(/طلب طارئ/g, "Emergency request");
  }
  return title;
};

const translateSubtitle = (sub) => {
  if (!sub) return "";
  if (currentLanguage.value === "en") {
    return sub
      .replace(
        /مستشفى العودة – شمال غزة \/ جباليا/g,
        "Al-Awda Hospital – North Gaza / Jabalia"
      )
      .replace(
        /مستشفى شهداء الأقصى – دير البلح/g,
        "Al-Aqsa Martyrs Hospital – Deir al-Balah"
      )
      .replace(/مستشفى الشفاء الطبي/g, "Al-Shifa Medical Hospital")
      .replace(/مستشفى ناصر الطبي/g, "Nasser Medical Hospital")
      .replace(
        /مستشفى أصدقاء المريض الخيري – مدينة غزة/g,
        "Patient’s Friends Benevolent Hospital – Gaza City"
      );
  }
  return sub;
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

const handleViewAllActivities = () => {
  router.push("/admin/accounts");
};
</script>

<style scoped>
.fs-7 {
  font-size: 0.9rem;
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
.bg-light-subtle {
  background-color: #f9fafb !important;
}
.dir-rtl {
  direction: rtl;
}
.dir-ltr {
  direction: ltr;
}
</style>
