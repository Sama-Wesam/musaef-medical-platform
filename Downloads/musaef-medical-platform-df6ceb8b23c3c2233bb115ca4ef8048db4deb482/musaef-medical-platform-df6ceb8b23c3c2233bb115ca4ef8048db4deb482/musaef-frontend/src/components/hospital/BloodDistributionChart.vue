<template>
  <div class="chart-container">
    <Doughnut :data="chartData" :options="chartOptions" />

    <div class="chart-center">
      <strong>159</strong>
      <span>إجمالي الوحدات</span>
    </div>
  </div>
</template>

<script setup>
import { Doughnut } from 'vue-chartjs'
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend,
} from 'chart.js'

ChartJS.register(ArcElement, Tooltip, Legend)

const chartData = {
  labels: ['O+', 'A+', 'B+', 'AB+', 'O-'],
  datasets: [
    {
      data: [41, 22, 13, 15, 9],
      backgroundColor: [
        '#dc2626',
        '#2563eb',
        '#16a34a',
        '#f59e0b',
        '#7c3aed',
      ],
      borderWidth: 0,
      hoverOffset: 6,
    },
  ],
}

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  cutout: '58%',
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      rtl: true,
      textDirection: 'rtl',
      callbacks: {
        label(context) {
          return `${context.label}: ${context.raw}%`
        },
      },
    },
  },
}
</script>

<style scoped>
.chart-container {
  width: 160px;
  height: 160px;
  position: relative;
  flex-shrink: 0;
}

.chart-center {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  pointer-events: none;
}

.chart-center strong {
  color: #111827;
  font-size: 22px;
  font-weight: 800;
}

.chart-center span {
  color: #6b7280;
  font-size: 9px;
  white-space: nowrap;
}
</style>