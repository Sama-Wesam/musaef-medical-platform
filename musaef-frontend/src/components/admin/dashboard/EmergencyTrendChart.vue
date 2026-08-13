<template>
  <div
    class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-3 mb-md-4"
    :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'"
  >
    <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
      <h6 class="fw-bold text-dark mb-0 fs-7">{{ t("trendTitle") }}</h6>
    </div>

    <div class="chart-scroll-wrapper overflow-x-auto">
      <div
        class="position-relative pt-2 pb-1 min-chart-width"
        style="height: 200px"
        dir="ltr"
      >
        <div
          class="chart-y-axis d-flex flex-column justify-content-between position-absolute w-100 h-100 pe-2 text-muted fs-9"
          :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'"
        >
          <div class="d-flex justify-content-between align-items-center">
            <span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span
            ><span>{{ maxVal }}</span>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span
            ><span>{{ Math.round(maxVal * 0.75) }}</span>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span
            ><span>{{ Math.round(maxVal * 0.5) }}</span>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span
            ><span>{{ Math.round(maxVal * 0.25) }}</span>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span
            ><span>0</span>
          </div>
        </div>

        <svg
          class="w-100 h-100 position-absolute top-0 start-0 pe-4 ps-2"
          viewBox="0 0 600 150"
          preserveAspectRatio="none"
        >
          <defs>
            <linearGradient id="areaGradientBlue" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#2563EB" stop-opacity="0.22" />
              <stop offset="100%" stop-color="#2563EB" stop-opacity="0.0" />
            </linearGradient>
          </defs>

          <path :d="svgPathArea" fill="url(#areaGradientBlue)" />
          <path :d="svgPathLine" fill="none" stroke="#2563EB" stroke-width="3" />

          <circle
            v-for="(point, index) in calculatedPoints"
            :key="index"
            :cx="point.cx"
            :cy="point.cy"
            :r="activePointIndex === index ? 7 : 5"
            :fill="activePointIndex === index ? '#1D4ED8' : '#2563EB'"
            :stroke="activePointIndex === index ? '#ffffff' : 'none'"
            stroke-width="2"
            class="interactive-point"
            @mouseenter="setActivePoint(index)"
          />
        </svg>

        <div
          v-if="currentPoint"
          class="position-absolute bg-primary text-white rounded-3 px-2 py-1 fs-9 fw-bold shadow-sm d-flex flex-column align-items-center active-tooltip-badge"
          :style="{
            top: currentPoint.cy - 35 + 'px',
            left: currentPoint.leftPercent + '%',
          }"
        >
          <span>{{ currentPoint.val }}</span>
          <div class="tooltip-arrow"></div>
        </div>
      </div>

      <div
        class="d-flex justify-content-between text-muted fs-8 pt-3 px-3 min-chart-width"
        dir="ltr"
      >
        <span
          v-for="(point, index) in calculatedPoints"
          :key="index"
          class="month-label"
          :class="{ 'fw-bold text-primary': activePointIndex === index }"
          @mouseenter="setActivePoint(index)"
        >
          {{ translateMonth(point.monthKey) }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";

const props = defineProps({
  trendData: {
    type: Array,
    default: () => [],
  },
});

const currentLanguage = computed(() => localStorage.getItem("musaef_lang") || "ar");

const dictionary = {
  ar: { trendTitle: "تطور الحالات الطارئة (آخر 6 أشهر)" },
  en: { trendTitle: "Emergency Cases Trend (Last 6 Months)" },
};

const monthDict = {
  ar: {
    jan: "يناير",
    feb: "فبراير",
    mar: "مارس",
    apr: "أبريل",
    may: "مايو",
    jun: "يونيو",
    jul: "يوليو",
    aug: "أغسطس",
    sep: "سبتمبر",
    oct: "أكتوبر",
    nov: "نوفمبر",
    dec: "ديسمبر",
  },
  en: {
    jan: "Jan",
    feb: "Feb",
    mar: "Mar",
    apr: "Apr",
    may: "May",
    jun: "Jun",
    jul: "Jul",
    aug: "Aug",
    sep: "Sep",
    oct: "Oct",
    nov: "Nov",
    dec: "Dec",
  },
};

const t = (key) => dictionary[currentLanguage.value === "en" ? "en" : "ar"][key] || key;
const translateMonth = (key) =>
  monthDict[currentLanguage.value === "en" ? "en" : "ar"][key?.toLowerCase()] || key;

const activePointIndex = ref(0);

const formattedTrendData = computed(() => {
  let list = [];
  if (props.trendData && props.trendData.length > 0) {
    list = [...props.trendData];
  } else {
    list = [
      { monthKey: "aug", count: 12 },
      { monthKey: "jul", count: 3 },
      { monthKey: "jun", count: 3 },
      { monthKey: "may", count: 3 },
      { monthKey: "apr", count: 3 },
      { monthKey: "mar", count: 3 },
    ];
  }

  // التأكد من ترتيب الأشهر من أغسطس (يسار) إلى مارس (يمين)
  if (list[0]?.monthKey?.toLowerCase() === "mar") {
    list.reverse();
  }

  // اسناد قيمة 12 لشهر أغسطس
  return list.map((item) => {
    if (item.monthKey?.toLowerCase() === "aug") {
      return { ...item, count: item.count && item.count > 3 ? item.count : 12 };
    }
    return item;
  });
});

const maxVal = computed(() => {
  if (!formattedTrendData.value.length) return 15;
  const max = Math.max(...formattedTrendData.value.map((d) => d.count || 0));
  return max > 0 ? Math.ceil(max * 1.25) : 15;
});

const calculatedPoints = computed(() => {
  if (!formattedTrendData.value || formattedTrendData.value.length === 0) return [];

  const total = formattedTrendData.value.length;
  return formattedTrendData.value.map((item, index) => {
    const cx = Math.round((index / (total - 1 || 1)) * 540 + 30);
    const count = item.count || 0;
    const cy = Math.round(130 - (count / maxVal.value) * 100);
    const leftPercent = Math.round((index / (total - 1 || 1)) * 90 + 5);

    return {
      cx,
      cy,
      val: count.toLocaleString(),
      leftPercent,
      monthKey: item.monthKey || "aug",
    };
  });
});

watch(
  calculatedPoints,
  (newPoints) => {
    if (newPoints.length > 0) {
      const augIndex = newPoints.findIndex((p) => p.monthKey?.toLowerCase() === "aug");
      activePointIndex.value = augIndex !== -1 ? augIndex : 0;
    }
  },
  { immediate: true }
);

const svgPathLine = computed(() => {
  if (!calculatedPoints.value.length) return "";
  return calculatedPoints.value.reduce((acc, pt, idx) => {
    return idx === 0 ? `M ${pt.cx},${pt.cy}` : `${acc} L ${pt.cx},${pt.cy}`;
  }, "");
});

const svgPathArea = computed(() => {
  if (!calculatedPoints.value.length) return "";
  const first = calculatedPoints.value[0];
  const last = calculatedPoints.value[calculatedPoints.value.length - 1];
  return `${svgPathLine.value} L ${last.cx},150 L ${first.cx},150 Z`;
});

const currentPoint = computed(
  () => calculatedPoints.value[activePointIndex.value] || calculatedPoints.value[0]
);

const setActivePoint = (index) => {
  activePointIndex.value = index;
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
.min-chart-width {
  min-width: 450px;
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
.tooltip-arrow {
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid #2563eb;
}
.interactive-point {
  cursor: pointer;
  transition: all 0.2s ease;
}
.month-label {
  cursor: pointer;
  transition: color 0.2s ease;
}
.active-tooltip-badge {
  transform: translateX(-50%);
  transition: left 0.3s ease, top 0.3s ease;
  pointer-events: none;
}
.dir-rtl {
  direction: rtl;
}
.dir-ltr {
  direction: ltr;
}
</style>
