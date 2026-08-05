<template>
  <div class="dashboard-card h-100" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h5 class="fw-bold mb-0 fs-6 fs-md-5">{{ t('title') }}</h5>
      <select class="form-select form-select-sm chart-select">
        <option>{{ t('currentMonth') }}</option>
        <option>{{ t('last12Months') }}</option>
      </select>
    </div>
    <div class="chart-scroll-wrapper">
      <div class="chart-container">
        <div v-for="(month, index) in monthlyRequests" :key="index" class="chart-item">
          <div class="chart-bar-wrapper">
            <div class="chart-bar" :style="{ height: month.height + '%' }"></div>
          </div>
          <small>{{ getMonthName(month.key) }}</small>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const currentLocale = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    title: 'الطلبات الشهرية',
    currentMonth: 'الشهر الحالي',
    last12Months: 'آخر 12 شهر',
    jan: 'يناير', feb: 'فبراير', mar: 'مارس', apr: 'أبريل',
    may: 'مايو', jun: 'يونيو', jul: 'يوليو', aug: 'أغسطس',
    sep: 'سبتمبر', oct: 'أكتوبر', nov: 'نوفمبر', dec: 'ديسمبر'
  },
  en: {
    title: 'Monthly Requests',
    currentMonth: 'Current Month',
    last12Months: 'Last 12 Months',
    jan: 'Jan', feb: 'Feb', mar: 'Mar', apr: 'Apr',
    may: 'May', jun: 'Jun', jul: 'Jul', aug: 'Aug',
    sep: 'Sep', oct: 'Oct', nov: 'Nov', dec: 'Dec'
  }
};

const t = (key) => dictionary[currentLocale.value === 'en' ? 'en' : 'ar'][key] || key;

const monthlyRequests = ref([
  { key: 'jan', height: 55 }, { key: 'feb', height: 100 }, { key: 'mar', height: 53 },
  { key: 'apr', height: 90 }, { key: 'may', height: 90 }, { key: 'jun', height: 68 },
  { key: 'jul', height: 100 }, { key: 'aug', height: 55 }, { key: 'sep', height: 82 },
  { key: 'oct', height: 45 }, { key: 'nov', height: 30 }, { key: 'dec', height: 100 }
]);

const getMonthName = (key) => t(key);
</script>

<style scoped>
.dashboard-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
@media (min-width: 768px) { .dashboard-card { padding: 20px; } }

.chart-select { width: 120px; border-radius: 8px; font-size: 12px; border-color: #e2e8f0; }

.chart-scroll-wrapper { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
.chart-container { height: 210px; display: flex; align-items: flex-end; gap: 6px; min-width: 480px; padding-top: 15px; }
@media (min-width: 768px) { .chart-container { height: 230px; gap: 8px; min-width: auto; } }

.chart-item { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; }
.chart-bar-wrapper { width: 100%; height: 100%; display: flex; align-items: flex-end; justify-content: center; }
.chart-bar { width: 100%; max-width: 20px; background: linear-gradient(180deg, #dc2626 0%, #7f1d1d 100%); border-radius: 6px 6px 0 0; transition: 0.3s; }
@media (min-width: 768px) { .chart-bar { max-width: 24px; border-radius: 8px 8px 0 0; } }

.chart-bar:hover { opacity: 0.85; }
.chart-item small { margin-top: 8px; font-size: 9px; color: #64748b; white-space: nowrap; }
@media (min-width: 768px) { .chart-item small { font-size: 10px; } }
</style>
