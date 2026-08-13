<template>
  <div
    class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-3 mb-md-4"
    :dir="langStore.dir"
  >
    <div
      class="d-flex align-items-center justify-content-between mb-3 mb-md-4 flex-wrap gap-2"
    >
      <div class="d-flex align-items-center gap-2">
        <img
          :src="getIconUrl('mdi_blood-plus-outline (2).png')"
          alt="blood icon"
          width="24"
          height="24"
          class="header-icon"
        />
        <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">{{ t("chartTitle") }}</h5>
      </div>
      <span
        class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill fs-9 px-2.5 py-1"
      >
        {{ t("aiAnalyticBadge") }}
      </span>
    </div>

    <!-- الحاوية التفاعلية المباشرة -->
    <div class="chart-scroll-wrapper overflow-x-auto">
      <div
        class="position-relative pt-4 pb-2 min-chart-width"
        style="height: 320px; direction: ltr"
      >
        <div
          class="chart-y-axis position-absolute h-100 start-0 w-100 d-flex flex-column justify-content-between text-secondary fs-8 fw-semibold"
          style="pointer-events: none; padding-bottom: 25px"
        >
          <div
            v-for="scale in yAxisScales"
            :key="scale"
            class="d-flex align-items-center w-100"
          >
            <span class="pe-2 text-end" style="width: 45px">{{ scale }}</span>
            <div
              class="border-bottom flex-grow-1 border-secondary-subtle"
              :style="{
                borderStyle: scale === 0 ? 'solid !important' : 'dotted !important',
              }"
            ></div>
          </div>
        </div>

        <div
          class="d-flex align-items-end justify-content-around h-100 position-relative z-1"
          style="padding-left: 55px; padding-right: 20px; padding-bottom: 25px"
        >
          <div
            v-for="b in normalizedDemand"
            :key="b.type"
            class="text-center d-flex flex-column align-items-center bar-wrapper"
            style="width: 42px"
            @click="emitBarClick(b)"
          >
            <small class="fw-bold text-dark fs-9 mb-1 count-label">{{ b.count }}</small>

            <div
              class="bar-column w-100 position-relative shadow-sm"
              :style="{
                height: b.calculatedHeight + 'px',
                backgroundColor: b.color || '#DC2626',
                borderRadius: '6px 6px 0 0',
              }"
              :title="`${b.type}: ${b.count}`"
            >
              <div class="bar-hover-overlay"></div>
            </div>

            <small class="fw-bold text-dark fs-8 mt-2">{{ b.type }}</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useLangStore } from "@/stores/langStore";

const props = defineProps({
  bloodDemand: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["bar-click"]);

const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: {
    chartTitle: "الطلب حسب فصيلة الدم (Donation Analytics AI)",
    aiAnalyticBadge: "تحليل تنبؤي حقيقي",
  },
  en: {
    chartTitle: "Demand by Blood Type (Donation Analytics AI)",
    aiAnalyticBadge: "Real-time Predictive AI",
  },
};

const t = (key) => dictionary[currentLanguage.value === "en" ? "en" : "ar"][key] || key;

const maxVal = computed(() => {
  const maxInArray = Math.max(
    ...(props.bloodDemand || []).map((i) => Number(i.count) || 0),
    0
  );
  if (maxInArray <= 50) return 50;
  if (maxInArray <= 200) return 200;
  if (maxInArray <= 500) return 500;
  return Math.ceil(maxInArray * 1.2);
});

const yAxisScales = computed(() => {
  const step = maxVal.value / 5;
  return [
    maxVal.value,
    Math.round(step * 4),
    Math.round(step * 3),
    Math.round(step * 2),
    Math.round(step * 1),
    0,
  ];
});

const normalizedDemand = computed(() => {
  const availableHeightPx = 230;

  return (props.bloodDemand || []).map((item) => {
    const val = Number(item.count) || 0;
    const height = Math.min(
      Math.max((val / maxVal.value) * availableHeightPx, 10),
      availableHeightPx
    );
    return {
      ...item,
      calculatedHeight: height,
    };
  });
});

const emitBarClick = (item) => {
  emit("bar-click", item);
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
.fs-5 {
  font-size: 1.15rem;
}
.fs-6 {
  font-size: 1rem;
}
.fs-8 {
  font-size: 0.8rem;
}
.fs-9 {
  font-size: 0.72rem;
}

.min-chart-width {
  min-width: 520px;
}

@media (min-width: 768px) {
  .min-chart-width {
    min-width: 100%;
  }
}

.chart-scroll-wrapper {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.chart-scroll-wrapper::-webkit-scrollbar {
  display: none;
}

.header-icon {
  width: 22px;
  height: 22px;
}
@media (min-width: 768px) {
  .header-icon {
    width: 26px;
    height: 26px;
  }
}

.bar-column {
  transition: height 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.2s ease,
    filter 0.2s ease;
  cursor: pointer;
}

.bar-wrapper:hover .bar-column {
  transform: scaleY(1.03);
  filter: brightness(1.1);
}

.bar-wrapper:hover .count-label {
  color: #dc3545 !important;
  font-weight: 900 !important;
}

.bar-hover-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.12);
  border-radius: 6px 6px 0 0;
  opacity: 0;
  transition: opacity 0.2s;
}

.bar-wrapper:hover .bar-hover-overlay {
  opacity: 1;
}

.bg-danger-subtle {
  background-color: #fee2e2 !important;
}
</style>
