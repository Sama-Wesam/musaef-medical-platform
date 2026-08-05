<template>
  <div class="dashboard-card h-100 d-flex flex-column justify-content-between" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">
    <div>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0 fs-6 fs-md-5">{{ t('title') }}</h5>
        <select class="form-select form-select-sm chart-select">
          <option>{{ t('currentMonth') }}</option>
        </select>
      </div>
      <div class="row align-items-center g-3">
        <div class="col-12 col-sm-6 order-2 order-sm-1" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'">
          <div v-for="(type, index) in bloodTypes" :key="index" class="blood-row">
            <span class="text-muted small">{{ type.percentage }}</span>
            <div class="d-flex align-items-center gap-2">
              <strong>{{ type.name }}</strong>
              <span :class="['blood-color', type.color]"></span>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-6 text-center order-1 order-sm-2">
          <div class="donut-chart-mock">
            <div class="donut-content">
              <h3>159</h3>
              <small>{{ t('totalUnits') }}</small>
            </div>
          </div>
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
    title: 'توزيع فصائل الدم',
    currentMonth: 'الشهر الحالي',
    totalUnits: 'اجمالي الوحدات'
  },
  en: {
    title: 'Blood Group Distribution',
    currentMonth: 'Current Month',
    totalUnits: 'Total Units'
  }
};

const t = (key) => dictionary[currentLocale.value === 'en' ? 'en' : 'ar'][key] || key;

const bloodTypes = ref([
  { name: '+O', percentage: '(41%)', color: 'bg-danger' },
  { name: '+A', percentage: '(22%)', color: 'bg-primary' },
  { name: '+B', percentage: '(13%)', color: 'bg-success' },
  { name: '+AB', percentage: '(15%)', color: 'bg-warning' },
  { name: '-O', percentage: '(6%)', color: 'bg-purple' }
]);
</script>

<style scoped>
.dashboard-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
@media (min-width: 768px) { .dashboard-card { padding: 20px; } }

.chart-select { width: 120px; border-radius: 8px; font-size: 12px; border-color: #e2e8f0; }

.donut-chart-mock { width: 140px; height: 140px; margin: auto; border-radius: 50%; position: relative; display: flex; align-items: center; justify-content: center; background: conic-gradient(#dc2626 0% 41%, #f59e0b 41% 56%, #16a34a 56% 69%, #2563eb 69% 91%, #7e22ce 91% 100%); }
@media (min-width: 768px) { .donut-chart-mock { width: 160px; height: 160px; } }

.donut-chart-mock::after { content: ""; position: absolute; width: 90px; height: 90px; background: #fff; border-radius: 50%; }
@media (min-width: 768px) { .donut-chart-mock::after { width: 105px; height: 105px; } }

.donut-content { position: relative; z-index: 2; text-align: center; }
.donut-content h3 { font-weight: 800; font-size: 20px; margin-bottom: 0; color: #1e293b; }
.donut-content small { color: #64748b; font-size: 11px; }

.blood-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 13px; }
.blood-color { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
.bg-danger { background: #dc2626; }
.bg-primary { background: #2563eb; }
.bg-success { background: #16a34a; }
.bg-warning { background: #f59e0b; }
.bg-purple { background: #7e22ce; }
</style>
