<template>
  <div class="dashboard-page">
    <!-- عنوان لوحة التحكم -->
    <DashboardHeader />

    <!-- بطاقات الإحصائيات -->
    <DashboardStats />

    <!-- الرسوم البيانية -->
    <div class="charts-grid">
      <!-- الطلبات الشهرية -->
      <section class="dashboard-card monthly-card">
        <div class="card-title-row">
          <h3>الطلبات الشهرية</h3>
        </div>

        <div class="monthly-chart-wrapper">
          <MonthlyRequestsChart />
        </div>
      </section>

      <!-- توزيع فصائل الدم -->
      <section class="dashboard-card distribution-card">
        <div class="card-title-row">
          <h3>توزيع فصائل الدم</h3>

          <select v-model="selectedPeriod" class="period-select" aria-label="اختيار الفترة">
            <option value="current-month">الشهر الحالي</option>
            <option value="last-month">الشهر الماضي</option>
            <option value="current-year">السنة الحالية</option>
          </select>
        </div>

        <div class="blood-distribution">
          <div class="distribution-chart-wrapper">
            <BloodDistributionChart />
          </div>

          <div class="legend">
            <div v-for="item in bloodTypes" :key="item.type" class="legend-item">
              <span class="legend-dot" :style="{ backgroundColor: item.color }"></span>

              <strong>{{ item.type }}</strong>

              <span class="legend-percent"> ({{ item.percent }}) </span>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- القسم السفلي -->
    <div class="bottom-grid">
      <!-- توقعات الذكاء الاصطناعي -->
      <section class="dashboard-card ai-card">
        <div class="card-title-row">
          <h3>توقعات الذكاء الاصطناعي</h3>
        </div>

        <div class="ai-content">
          <!-- الرسم المصغر -->
          <div class="mini-chart">
            <div class="chart-percentage percentage-100">100%</div>

            <div class="chart-percentage percentage-75">75%</div>

            <div class="chart-percentage percentage-50">50%</div>

            <div class="chart-percentage percentage-25">25%</div>

            <div class="chart-percentage percentage-0">0%</div>

            <div class="chart-horizontal-line line-1"></div>
            <div class="chart-horizontal-line line-2"></div>
            <div class="chart-horizontal-line line-3"></div>
            <div class="chart-horizontal-line line-4"></div>

            <svg
              class="prediction-line"
              viewBox="0 0 300 120"
              preserveAspectRatio="none"
              aria-hidden="true"
            >
              <defs>
                <linearGradient id="predictionFill" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="#ef4444" stop-opacity="0.2" />

                  <stop offset="100%" stop-color="#ef4444" stop-opacity="0" />
                </linearGradient>
              </defs>

              <path
                class="prediction-area"
                d="
                  M 0 80
                  L 35 68
                  L 70 78
                  L 105 74
                  L 140 55
                  L 175 48
                  L 210 43
                  L 245 30
                  L 280 18
                  L 280 120
                  L 0 120
                  Z
                "
              />

              <polyline
                class="prediction-path"
                points="
                  0,80
                  35,68
                  70,78
                  105,74
                  140,55
                  175,48
                  210,43
                  245,30
                  280,18
                "
              />
            </svg>

            <i class="bi bi-arrow-up-right prediction-arrow" aria-hidden="true"></i>

            <div class="chart-times">
              <span>الآن</span>
              <span>24 ساعة</span>
              <span>48 ساعة</span>
              <span>72 ساعة</span>
            </div>
          </div>

          <!-- نص التوقعات -->
          <div class="ai-text">
            <p>
              تم التنبؤ بارتفاع الطلب على فصيلة
              <strong>O+</strong>
              خلال
              <strong>72 ساعة</strong>
              القادمة.
            </p>

            <small> يُنصح بتكثيف التواصل مع المتبرعين المؤهلين في المخزون. </small>
          </div>
        </div>

        <button type="button" class="soft-action-btn">عرض التقرير الكامل</button>
      </section>

      <!-- تنبيهات المخزون -->
<section class="dashboard-card alerts-card">
  <!-- عنوان البطاقة -->
  <div class="alerts-header">
    <h3>تنبيهات المخزون</h3>

    <i
      class="bi bi-bell alert-header-icon"
      aria-hidden="true"
    ></i>
  </div>

  <!-- قائمة التنبيهات -->
  <div class="alerts-list">
    <div
      v-for="item in inventoryAlerts"
      :key="item.type"
      class="alert-row"
    >
      <!-- الفصيلة: تظهر في أقصى اليسار -->
      <div class="alert-blood-type">
        <i
          class="bi bi-droplet"
          :style="{ color: item.color }"
          aria-hidden="true"
        ></i>

        <strong :style="{ color: item.color }">
          {{ item.type }}
        </strong>
      </div>

      <!-- الحالة -->
      <span class="alert-badge">
        {{ item.status }}
      </span>

      <!-- عدد الوحدات -->
      <span class="alert-units">
        {{ item.units }} وحدات فقط
      </span>

      <!-- شريط المخزون -->
      <div class="alert-progress">
        <div
          class="alert-progress-fill"
          :style="{
            width: item.width,
            backgroundColor: item.color,
          }"
        ></div>
      </div>

      <!-- الرسالة: تظهر في أقصى اليمين -->
      <span class="alert-message">
        {{ item.message }}
      </span>
    </div>
  </div>

  <button
    type="button"
    class="alerts-button"
  >
    عرض جميع التنبيهات
  </button>
</section>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

import DashboardHeader from '@/components/hospital/DashboardHeader.vue'
import DashboardStats from '@/components/hospital/DashboardStats.vue'
import MonthlyRequestsChart from '@/components/hospital/MonthlyRequestsChart.vue'
import BloodDistributionChart from '@/components/hospital/BloodDistributionChart.vue'

const selectedPeriod = ref('current-month')

const bloodTypes = [
  {
    type: 'O+',
    percent: '41%',
    color: '#dc2626',
  },
  {
    type: 'A+',
    percent: '22%',
    color: '#2563eb',
  },
  {
    type: 'B+',
    percent: '13%',
    color: '#16a34a',
  },
  {
    type: 'AB+',
    percent: '15%',
    color: '#f59e0b',
  },
  {
    type: 'O-',
    percent: '9%',
    color: '#7c3aed',
  },
]

const inventoryAlerts = [
  {
    type: 'O-',
    status: 'منخفض',
    units: 10,
    width: '72%',
    color: '#ef4444',
    message: 'أقل من الحد الأدنى',
  },
  {
    type: 'B-',
    status: 'منخفض جدًا',
    units: 15,
    width: '58%',
    color: '#f97316',
    message: 'ينخفض باستمرار',
  },
  {
    type: 'AB-',
    status: 'منخفض',
    units: 12,
    width: '56%',
    color: '#fb923c',
    message: 'ينخفض باستمرار',
  },
]
</script>


