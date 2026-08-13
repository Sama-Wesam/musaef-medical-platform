<template>
  <div class="dashboard-card h-100" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h5 class="fw-bold mb-0 fs-6 fs-md-5">{{ t('title') }}</h5>
      <select v-model="timeRange" class="form-select form-select-sm chart-select cursor-pointer">
        <option value="current">{{ t('currentMonth') }}</option>
        <option value="12months">{{ t('last12Months') }}</option>
      </select>
    </div>

    <!-- حالة عدم وجود بيانات -->
    <div v-if="displayMonthlyRequests.length === 0" class="text-center text-muted py-5 fs-8">
      {{ t('noData') }}
    </div>

    <!-- المخطط البياني للأعمدة التفاعلية -->
    <div v-else class="chart-scroll-wrapper overflow-visible">
      <div class="chart-container position-relative">

        <!-- أعمدة المخطط -->
        <div
          v-for="(month, index) in displayMonthlyRequests"
          :key="index"
          class="chart-item position-relative"
          @mouseenter="hoveredIndex = index"
          @mouseleave="hoveredIndex = null"
        >
          <!-- Tooltip يظهر فوق العمود -->
          <div
            v-if="hoveredIndex === index"
            class="chart-tooltip shadow bg-dark text-white rounded-2 px-2 py-1 fs-9 position-absolute"
          >
            {{ getMonthName(month.key || month.month) }}: <strong>{{ month.count }} {{ t('requests') }}</strong>
          </div>

          <div class="chart-bar-wrapper">
            <div
              class="chart-bar transition-all"
              :style="{
                height: month.height + '%',
                background: hoveredIndex === index
                  ? 'linear-gradient(180deg, #ef4444 0%, #dc2626 100%)'
                  : 'linear-gradient(180deg, #dc2626 0%, #7f1d1d 100%)',
                transform: hoveredIndex === index ? 'scaleY(1.03)' : 'scaleY(1)'
              }"
            ></div>
          </div>
          <small :class="{ 'fw-bold text-danger': hoveredIndex === index }">{{ getMonthName(month.key || month.month) }}</small>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  chartData: {
    type: Array,
    default: () => []
  }
});

const hoveredIndex = ref(null);
const timeRange = ref('12months');
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
  ar: {
    title: 'الطلبات الشهرية', currentMonth: 'الشهر الحالي', last12Months: 'آخر 12 شهر', requests: 'طلب', noData: 'لا توجد بيانات طلبات شهرية',
    jan: 'يناير', feb: 'فبراير', mar: 'مارس', apr: 'أبريل', may: 'مايو', jun: 'يونيو',
    jul: 'يوليو', aug: 'أغسطس', sep: 'سبتمبر', oct: 'أكتوبر', nov: 'نوفمبر', dec: 'ديسمبر'
  },
  en: {
    title: 'Monthly Requests', currentMonth: 'Current Month', last12Months: 'Last 12 Months', requests: 'requests', noData: 'No monthly request data',
    jan: 'Jan', feb: 'Feb', mar: 'Mar', apr: 'Apr', may: 'May', jun: 'Jun',
    jul: 'Jul', aug: 'Aug', sep: 'Sep', oct: 'Oct', nov: 'Nov', dec: 'Dec'
  }
};

const t = (key) => dictionary[currentLocale.value === 'en' ? 'en' : 'ar'][key] || key;

const displayMonthlyRequests = computed(() => {
  if (!props.chartData || props.chartData.length === 0) return [];

  const maxCount = Math.max(...props.chartData.map(item => item.count || item.requests || 0), 1);

  const formattedData = props.chartData.map(item => {
    const cnt = item.count ?? item.requests ?? 0;
    const rawHeight = Math.round((cnt / maxCount) * 100);
    return {
      key: (item.key || item.month || '').toLowerCase(),
      count: cnt,
      height: Math.max(rawHeight, 8)
    };
  });

  if (timeRange.value === 'current') {
    return formattedData.slice(-1);
  }
  return formattedData;
});

const getMonthName = (key) => {
  const cleanKey = (key || '').toLowerCase().substring(0, 3);
  return t(cleanKey) !== cleanKey ? t(cleanKey) : key;
};
</script>

<style scoped>
.dashboard-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); overflow: visible; }
@media (min-width: 768px) { .dashboard-card { padding: 20px; } }

.chart-select { width: 120px; border-radius: 8px; font-size: 12px; border-color: #e2e8f0; }

.chart-scroll-wrapper { width: 100%; overflow-x: auto; overflow-y: visible; -webkit-overflow-scrolling: touch; }
.chart-container { height: 220px; display: flex; align-items: flex-end; gap: 6px; min-width: 480px; padding-top: 35px; }
@media (min-width: 768px) { .chart-container { height: 240px; gap: 8px; min-width: auto; padding-top: 40px; } }

.chart-item { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; cursor: pointer; }
.chart-bar-wrapper { width: 100%; height: 100%; display: flex; align-items: flex-end; justify-content: center; }
.chart-bar { width: 100%; max-width: 20px; border-radius: 6px 6px 0 0; transform-origin: bottom; }
@media (min-width: 768px) { .chart-bar { max-width: 24px; border-radius: 8px 8px 0 0; } }

.chart-item small { margin-top: 8px; font-size: 9px; color: #64748b; white-space: nowrap; transition: color 0.2s; }
@media (min-width: 768px) { .chart-item small { font-size: 10px; } }

.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }

.chart-tooltip {
  top: -30px;
  z-index: 20;
  white-space: nowrap;
  pointer-events: none;
}
</style>
