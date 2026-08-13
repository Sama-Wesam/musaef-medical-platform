<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <h6 class="fw-bold text-dark mb-3 mb-md-4 fs-7" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
      {{ t('title') }}
    </h6>

    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-around gap-3 position-relative">
      <div class="position-relative d-flex align-items-center justify-content-center chart-container-box">
        <svg viewBox="0 0 42 42" class="donut-chart">
          <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff"></circle>
          <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#f3f4f6" stroke-width="5"></circle>

          <circle
            v-for="(item, index) in computedSegments"
            :key="index"
            class="donut-segment interactive-segment"
            cx="21" cy="21" r="15.91549430918954"
            fill="transparent"
            :stroke="item.color"
            :stroke-width="6"
            :stroke-dasharray="`${item.dashPercent} ${100 - item.dashPercent}`"
            :stroke-dashoffset="item.dashOffset"
            @mouseenter="showTooltip($event, item.blood_type, item.percentage + '%', item.count)"
            @mouseleave="hideTooltip"
          ></circle>
        </svg>

        <div class="position-absolute text-center pointer-events-none">
          <strong class="d-block fs-5 fw-bold text-dark mb-0">{{ totalCount.toLocaleString() }}</strong>
          <small class="text-muted fs-9">{{ t('totalRequests') }}</small>
        </div>
      </div>

      <div class="d-flex flex-column gap-2 fs-8 text-dark">
        <div
          v-for="(item, index) in computedSegments"
          :key="'legend-'+index"
          class="d-flex align-items-center gap-2"
          :class="currentLanguage === 'ar' ? 'justify-content-end' : 'justify-content-start'"
        >
          <span class="text-muted fs-9">({{ item.percentage }}%)</span>
          <span class="fw-bold me-1">{{ item.blood_type }}</span>
          <span class="dot-indicator" :style="{ backgroundColor: item.color }"></span>
        </div>
      </div>

      <div
        v-if="tooltip.visible"
        class="chart-tooltip bg-dark text-white rounded-3 px-2 py-1 fs-9 fw-bold shadow-sm position-absolute"
        :style="{ top: tooltip.y + 'px', left: tooltip.x + 'px' }"
      >
        <span>{{ tooltip.type }}: {{ tooltip.percentage }} ({{ tooltip.count }})</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, reactive } from 'vue';

const props = defineProps({
  distribution: {
    type: Array,
    default: () => []
  }
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: { title: 'توزيع الطلبات حسب فصيلة الدم', totalRequests: 'إجمالي الطلبات' },
  en: { title: 'Requests Distribution by Blood Type', totalRequests: 'Total Requests' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const colorPalette = ['#D32F2F', '#2563EB', '#16A34A', '#FBBF24', '#7C3AED', '#EC4899', '#06B6D4', '#F97316'];

const totalCount = computed(() => {
  return props.distribution.reduce((acc, curr) => acc + (curr.count || curr.total || 0), 0);
});

const computedSegments = computed(() => {
  let accumulatedDash = 25;
  return props.distribution.map((item, idx) => {
    const count = item.count || item.total || 0;
    const percentage = item.percentage ?? (totalCount.value ? Math.round((count / totalCount.value) * 100) : 0);
    const dashPercent = percentage;
    const dashOffset = accumulatedDash;
    accumulatedDash -= dashPercent;

    return {
      blood_type: item.blood_type || item.type,
      count: count.toLocaleString(),
      percentage: percentage,
      dashPercent: dashPercent,
      dashOffset: dashOffset,
      color: colorPalette[idx % colorPalette.length]
    };
  });
});

const tooltip = reactive({ visible: false, x: 0, y: 0, type: '', percentage: '', count: '' });

const showTooltip = (event, type, percentage, count) => {
  const rect = event.currentTarget.getBoundingClientRect();
  const parentRect = event.currentTarget.closest('.card').getBoundingClientRect();
  tooltip.type = type;
  tooltip.percentage = percentage;
  tooltip.count = count;
  tooltip.x = rect.left - parentRect.left + 20;
  tooltip.y = rect.top - parentRect.top - 30;
  tooltip.visible = true;
};

const hideTooltip = () => { tooltip.visible = false; };
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.chart-container-box { width: 140px; height: 140px; }
@media (min-width: 768px) { .chart-container-box { width: 160px; height: 160px; } }
.donut-chart { transform: rotate(-90deg); }
.dot-indicator { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
.interactive-segment { cursor: pointer; transition: stroke-width 0.25s ease, opacity 0.25s ease; }
.interactive-segment:hover { stroke-width: 7.5; opacity: 0.85; }
.pointer-events-none { pointer-events: none; }
.chart-tooltip { pointer-events: none; z-index: 100; transform: translateX(-50%); transition: all 0.15s ease-out; white-space: nowrap; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
