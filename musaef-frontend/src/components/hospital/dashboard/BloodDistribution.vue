<template>
  <div class="dashboard-card h-100 d-flex flex-column justify-content-between" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">
    <div>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0 fs-6 fs-md-5">{{ t('title') }}</h5>
        <select v-model="selectedPeriod" class="form-select form-select-sm chart-select cursor-pointer">
          <option value="current">{{ t('currentMonth') }}</option>
          <option value="previous">{{ currentLocale === 'en' ? 'Previous Month' : 'الشهر السابق' }}</option>
        </select>
      </div>

      <div class="row align-items-center g-3">
        <!-- حالة عدم وجود بيانات -->
        <div v-if="formattedDistribution.length === 0" class="col-12 text-center py-4 text-muted fs-8">
          {{ t('noData') }}
        </div>

        <template v-else>
          <!-- القائمة التفاعلية للفصائل -->
          <div class="col-12 col-sm-6 order-2 order-sm-1" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'">
            <div
              v-for="(type, index) in formattedDistribution"
              :key="index"
              class="blood-row p-1.5 rounded-3 cursor-pointer transition-all"
              :class="{ 'bg-light-subtle shadow-sm fw-bold': activeIndex === index }"
              @mouseenter="activeIndex = index"
              @mouseleave="activeIndex = null"
            >
              <span class="text-muted small">({{ type.percentage }}%)</span>
              <div class="d-flex align-items-center gap-2">
                <strong>{{ type.name }}</strong>
                <span class="blood-color" :style="{ backgroundColor: type.colorHex }"></span>
              </div>
            </div>
          </div>

          <!-- مخطط Donut SVG التفاعلي -->
          <div class="col-12 col-sm-6 text-center order-1 order-sm-2 position-relative">
            <div class="donut-chart-container position-relative d-inline-block">
              <svg viewBox="0 0 42 42" class="donut-svg">
                <circle
                  v-for="(slice, idx) in chartSlices"
                  :key="idx"
                  class="donut-segment cursor-pointer"
                  cx="21"
                  cy="21"
                  r="15.91549430918954"
                  fill="transparent"
                  :stroke="slice.colorHex"
                  :stroke-width="activeIndex === idx ? '6.5' : '5.2'"
                  :stroke-dasharray="`${slice.percentage} ${100 - slice.percentage}`"
                  :stroke-dashoffset="slice.offset"
                  @mouseenter="activeIndex = idx"
                  @mouseleave="activeIndex = null"
                >
                  <title>{{ slice.name }}: {{ slice.percentage }}% ({{ slice.units }} {{ t('totalUnits') }})</title>
                </circle>
              </svg>

              <!-- المحتوى الداخلي للمخطط الدائري -->
              <div class="donut-center-content d-flex flex-column align-items-center justify-content-center">
                <h3 class="fw-extrabold mb-0 text-dark">
                  {{ activeIndex !== null ? formattedDistribution[activeIndex].units : totalUnits }}
                </h3>
                <small class="text-secondary fs-10">
                  {{ activeIndex !== null ? formattedDistribution[activeIndex].name : t('totalUnits') }}
                </small>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  distribution: {
    type: Array,
    default: () => []
  }
});

const activeIndex = ref(null);
const selectedPeriod = ref('current');
const currentLocale = ref(localStorage.getItem('musaef_lang') || 'ar');

const updateLocale = () => {
  currentLocale.value = localStorage.getItem('musaef_lang') || 'ar';
};

onMounted(() => {
  window.addEventListener('storage', updateLocale);
  window.addEventListener('language-changed', updateLocale);
});

onUnmounted(() => {
  window.removeEventListener('storage', updateLocale);
  window.removeEventListener('language-changed', updateLocale);
});

const dictionary = {
  ar: { title: 'توزيع فصائل الدم', currentMonth: 'الشهر الحالي', totalUnits: 'إجمالي الوحدات', noData: 'لا توجد بيانات توزيع متاحة حالياً' },
  en: { title: 'Blood Group Distribution', currentMonth: 'Current Month', totalUnits: 'Total Units', noData: 'No distribution data available' }
};

const t = (key) => dictionary[currentLocale.value === 'en' ? 'en' : 'ar'][key] || key;

const colorMap = {
  '+O': '#dc2626', 'O+': '#dc2626',
  '+A': '#2563eb', 'A+': '#2563eb',
  '+B': '#16a34a', 'B+': '#16a34a',
  '+AB': '#f59e0b', 'AB+': '#f59e0b',
  '-O': '#7e22ce', 'O-': '#7e22ce',
  '-A': '#0284c7', 'A-': '#0284c7',
  '-B': '#059669', 'B-': '#059669',
  '-AB': '#d97706', 'AB-': '#d97706'
};

const formattedDistribution = computed(() => {
  if (!props.distribution || props.distribution.length === 0) return [];

  const rawSum = props.distribution.reduce((acc, curr) => acc + (curr.units || curr.count || 0), 0);

  return props.distribution.map(item => {
    const units = item.units ?? item.count ?? 0;
    const name = item.name || item.type || item.blood_type || 'Unknown';
    let pct = item.percentage;

    if (pct === undefined || pct === null) {
      pct = rawSum > 0 ? Math.round((units / rawSum) * 100) : 0;
    } else if (typeof pct === 'string') {
      pct = parseInt(pct.replace(/\D/g, '')) || 0;
    }

    return {
      name,
      percentage: pct,
      units,
      colorHex: colorMap[name] || '#dc2626'
    };
  });
});

const totalUnits = computed(() => {
  return formattedDistribution.value.reduce((acc, cur) => acc + (cur.units || 0), 0);
});

const chartSlices = computed(() => {
  let accumulated = 25;
  return formattedDistribution.value.map(item => {
    const offset = 100 - accumulated + 25;
    accumulated += item.percentage;
    return {
      ...item,
      offset: offset
    };
  });
});
</script>

<style scoped>
.dashboard-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
@media (min-width: 768px) { .dashboard-card { padding: 20px; } }

.chart-select { width: 120px; border-radius: 8px; font-size: 12px; border-color: #e2e8f0; }

.donut-chart-container { width: 150px; height: 150px; }
@media (min-width: 768px) { .donut-chart-container { width: 170px; height: 170px; } }

.donut-svg { transform: rotate(-90deg); border-radius: 50%; width: 100%; height: 100%; }
.donut-segment { transition: stroke-width 0.2s ease, opacity 0.2s; }
.donut-segment:hover { opacity: 0.9; }

.donut-center-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  pointer-events: none;
}

.donut-center-content h3 { font-size: 22px; }
.blood-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 13px; transition: all 0.2s ease; }
.blood-color { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
.cursor-pointer { cursor: pointer; }
.fs-8 { font-size: 0.8rem; }
.fs-10 { font-size: 0.65rem; }
</style>
