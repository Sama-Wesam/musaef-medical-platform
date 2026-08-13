<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-2">
      <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('weeklyTitle') }}</h6>

      <div class="d-flex align-items-center gap-3 fs-9 fw-semibold text-muted">
        <div class="d-flex align-items-center gap-1">
          <span>{{ t('donors') }}</span>
          <span class="dot-indicator" style="background-color: #DC2626;"></span>
        </div>
        <div class="d-flex align-items-center gap-1">
          <span>{{ t('requests') }}</span>
          <span class="dot-indicator" style="background-color: #2563EB;"></span>
        </div>
      </div>
    </div>

    <div class="chart-scroll-wrapper overflow-x-auto flex-grow-1">
      <div class="position-relative pt-4 pb-1 min-chart-width" style="min-height: 190px;">
        <div class="chart-y-axis d-flex flex-column justify-content-between position-absolute w-100 h-100 pe-2 text-muted fs-9">
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>{{ maxVal }}</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>{{ Math.round(maxVal * 0.75) }}</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>{{ Math.round(maxVal * 0.5) }}</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>{{ Math.round(maxVal * 0.25) }}</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>0</span></div>
        </div>

        <svg class="w-100 h-100 position-absolute top-0 start-0 pe-4 ps-2" viewBox="0 0 500 130" preserveAspectRatio="none">
          <defs>
            <linearGradient id="redGradient" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#DC2626" stop-opacity="0.15"/>
              <stop offset="100%" stop-color="#DC2626" stop-opacity="0.0"/>
            </linearGradient>
            <linearGradient id="blueGradient2" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#2563EB" stop-opacity="0.15"/>
              <stop offset="100%" stop-color="#2563EB" stop-opacity="0.0"/>
            </linearGradient>
          </defs>

          <path :d="donorAreaPath" fill="url(#redGradient)" />
          <path :d="donorLinePath" fill="none" stroke="#DC2626" stroke-width="3" />

          <path :d="reqAreaPath" fill="url(#blueGradient2)" />
          <path :d="reqLinePath" fill="none" stroke="#2563EB" stroke-width="3" />

          <circle
            v-for="(day, idx) in formattedWeeklyData"
            :key="'donor-'+idx"
            :cx="day.cx" :cy="day.donorCy" :r="activeDayIndex === idx ? 7 : 5"
            fill="#DC2626"
            class="interactive-point"
            @mouseenter="setActiveDay(idx)"
          />

          <circle
            v-for="(day, idx) in formattedWeeklyData"
            :key="'req-'+idx"
            :cx="day.cx" :cy="day.reqCy" :r="activeDayIndex === idx ? 7 : 5"
            fill="#2563EB"
            class="interactive-point"
            @mouseenter="setActiveDay(idx)"
          />
        </svg>

        <div
          v-if="activeDayIndex !== null && currentDayData"
          class="position-absolute bg-dark text-white rounded-3 px-2 py-1 fs-9 fw-bold shadow-sm d-flex flex-column align-items-center active-weekly-tooltip"
          :style="tooltipStyle"
        >
          <span>{{ t('donors') }}: {{ currentDayData.donors }} | {{ t('requests') }}: {{ currentDayData.requests }}</span>
        </div>
      </div>

      <div class="d-flex justify-content-between text-muted fs-8 pt-3 px-2 min-chart-width">
        <span
          v-for="(day, idx) in formattedWeeklyData"
          :key="idx"
          class="day-label"
          :class="{ 'fw-bold text-dark': activeDayIndex === idx }"
          @mouseenter="setActiveDay(idx)"
        >
          {{ translateDay(day.dayKey) }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
  weeklyData: {
    type: Array,
    default: () => []
  }
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: { weeklyTitle: 'النشاط العام للمنصة (أسبوعي)', donors: 'المتبرعون', requests: 'الطلبات' },
  en: { weeklyTitle: 'General Platform Activity (Weekly)', donors: 'Donors', requests: 'Requests' }
};

const dayDict = {
  ar: { sat: 'السبت', sun: 'الأحد', mon: 'الإثنين', tue: 'الثلاثاء', wed: 'الأربعاء', thu: 'الخميس', fri: 'الجمعة' },
  en: { sat: 'Sat', sun: 'Sun', mon: 'Mon', tue: 'Tue', wed: 'Wed', thu: 'Thu', fri: 'Fri' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;
const translateDay = (key) => dayDict[currentLanguage.value === 'en' ? 'en' : 'ar'][key?.toLowerCase()] || key;

const activeDayIndex = ref(0);

const maxVal = computed(() => {
  if (!props.weeklyData.length) return 100;
  let max = 0;
  props.weeklyData.forEach(d => {
    if ((d.donors || 0) > max) max = d.donors;
    if ((d.requests || 0) > max) max = d.requests;
  });
  return max > 0 ? Math.ceil(max * 1.2) : 10;
});

const formattedWeeklyData = computed(() => {
  if (!props.weeklyData.length) return [];

  const total = props.weeklyData.length;
  return props.weeklyData.map((item, idx) => {
    const cx = Math.round((idx / (total - 1 || 1)) * 450 + 10);
    const donors = item.donors || 0;
    const requests = item.requests || 0;

    const donorCy = Math.round(110 - (donors / maxVal.value) * 90);
    const reqCy = Math.round(110 - (requests / maxVal.value) * 90);
    const leftPercent = Math.round((idx / (total - 1 || 1)) * 89 + 3);

    return {
      cx,
      donorCy,
      reqCy,
      donors: donors.toLocaleString(),
      requests: requests.toLocaleString(),
      leftPercent,
      dayKey: item.dayKey || 'sat'
    };
  });
});

const donorLinePath = computed(() => formattedWeeklyData.value.reduce((acc, pt, idx) => idx === 0 ? `M ${pt.cx},${pt.donorCy}` : `${acc} L ${pt.cx},${pt.donorCy}`, ''));
const donorAreaPath = computed(() => {
  if (!formattedWeeklyData.value.length) return '';
  const first = formattedWeeklyData.value[0];
  const last = formattedWeeklyData.value[formattedWeeklyData.value.length - 1];
  return `${donorLinePath.value} L ${last.cx},130 L ${first.cx},130 Z`;
});

const reqLinePath = computed(() => formattedWeeklyData.value.reduce((acc, pt, idx) => idx === 0 ? `M ${pt.cx},${pt.reqCy}` : `${acc} L ${pt.cx},${pt.reqCy}`, ''));
const reqAreaPath = computed(() => {
  if (!formattedWeeklyData.value.length) return '';
  const first = formattedWeeklyData.value[0];
  const last = formattedWeeklyData.value[formattedWeeklyData.value.length - 1];
  return `${reqLinePath.value} L ${last.cx},130 L ${first.cx},130 Z`;
});

const currentDayData = computed(() => formattedWeeklyData.value[activeDayIndex.value] || formattedWeeklyData.value[0]);

const tooltipStyle = computed(() => {
  if (!currentDayData.value) return {};
  const isTooHigh = currentDayData.value.donorCy < 30;
  const topPosition = isTooHigh ? (currentDayData.value.donorCy + 15) : (currentDayData.value.donorCy - 28);

  return {
    top: `${topPosition}px`,
    left: `${currentDayData.value.leftPercent}%`
  };
});

const setActiveDay = (idx) => {
  activeDayIndex.value = idx;
};
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.min-chart-width { min-width: 400px; }
@media (min-width: 768px) { .min-chart-width { min-width: 100%; } }
.chart-scroll-wrapper { scrollbar-width: none; -ms-overflow-style: none; }
.chart-scroll-wrapper::-webkit-scrollbar { display: none; }
.dot-indicator { width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.interactive-point { cursor: pointer; transition: all 0.2s ease; }
.day-label { cursor: pointer; transition: color 0.2s ease; }
.active-weekly-tooltip { transform: translateX(-50%); transition: left 0.25s ease, top 0.25s ease; pointer-events: none; white-space: nowrap; z-index: 50; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
