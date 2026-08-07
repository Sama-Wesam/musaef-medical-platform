<template>
  <Bar :data="chartData" :options="chartOptions" />
</template>

<script setup>
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from 'chart.js'

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
)

const chartData = {
  labels: [
    'يناير',
    'فبراير',
    'مارس',
    'أبريل',
    'مايو',
    'يونيو',
    'يوليو',
    'أغسطس',
    'سبتمبر',
    'أكتوبر',
    'نوفمبر',
    'ديسمبر',
  ],
  datasets: [
    {
      label: 'الطلبات',
      data: [55, 100, 55, 90, 88, 68, 100, 55, 82, 70, 30, 100],
      backgroundColor: '#dc2626',
      borderRadius: 8,
      barThickness: 14,
    },
  ],
}

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      rtl: true,
      textDirection: 'rtl',
    },
  },
  scales: {
    x: {
      grid: {
        display: false,
      },
      ticks: {
        font: {
          size: 10,
        },
      },
    },
    y: {
      beginAtZero: true,
      max: 120,
      ticks: {
        stepSize: 30,
        font: {
          size: 10,
        },
      },
      grid: {
        color: '#eeeeee',
      },
    },
  },
}
</script>