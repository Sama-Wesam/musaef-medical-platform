<template>
  <AdminLayout>
    <div class="radar-view container-fluid px-2 px-md-3" dir="rtl">

      <div class="row g-3 g-lg-4">
        <!-- 1. القسم الأيمن: قائمة الحالات الحرجة المباشرة -->
        <div class="col-12 col-lg-5 col-xl-4 order-2 order-lg-1">
          <CriticalCasesList
            v-model:filter="filter"
            :hospitals="filteredHospitals"
            @refresh="fetchRadarData"
          />
        </div>

        <!-- 2. القسم الأيسر: الخريطة الجغرافية المباشرة ودليل الخطورة -->
        <div class="col-12 col-lg-7 col-xl-8 order-1 order-lg-2">
          <RadarMap />
        </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import apiClient from '@/api/axios';

import CriticalCasesList from '@/components/admin/liveradar/CriticalCasesList.vue';
import RadarMap from '@/components/admin/liveradar/RadarMap.vue';

const filter = ref('all');
const timerInterval = ref(null);

const hospitalsList = ref([
  {
    id: 1,
    name: 'مستشفى الكويتي',
    location: 'الجنوب - رفح',
    remainingSeconds: 332, // 00:05:32 بالثواني
    timeLeft: '00:05:32',
    responseTime: '6 دقائق',
    urgency: 'critical',
    icon: 'Group 1000002306.png'
  },
  {
    id: 2,
    name: 'مستشفى العودة',
    location: 'وسطى - النصيرات',
    remainingSeconds: 272, // 00:04:32 بالثواني
    timeLeft: '00:04:32',
    responseTime: '6 دقائق',
    urgency: 'critical',
    icon: 'Group 1000002306 (1).png'
  },
  {
    id: 3,
    name: 'مستشفى ناصر',
    location: 'جنوب - خانيونس',
    remainingSeconds: 572, // 00:09:32 بالثواني
    timeLeft: '00:09:32',
    responseTime: '6 دقائق',
    urgency: 'medium',
    icon: 'Group 1000002306 (2).png'
  }
]);

// تصفية المستشفيات بحسب الفلتر
const filteredHospitals = computed(() => {
  if (filter.value === 'all') return hospitalsList.value;
  return hospitalsList.value.filter(h => h.urgency === filter.value);
});

// تحويل عدد الثواني إلى صيغة HH:MM:SS
const formatSeconds = (totalSec) => {
  if (totalSec <= 0) return '00:00:00';
  const hrs = Math.floor(totalSec / 3600);
  const mins = Math.floor((totalSec % 3600) / 60);
  const secs = totalSec % 60;
  return `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
};

// بدء مؤقت الخصم التلقائي بالثواني
const startCountdowns = () => {
  timerInterval.value = setInterval(() => {
    hospitalsList.value.forEach(item => {
      if (item.remainingSeconds > 0) {
        item.remainingSeconds--;
        item.timeLeft = formatSeconds(item.remainingSeconds);
      }
    });
  }, 1000);
};

// جلب البيانات الفعلية من الباك إند
const fetchRadarData = async () => {
  try {
    const res = await apiClient.get('/admin/emergency-radar');
    const data = res.data?.data || res.data;
    if (Array.isArray(data) && data.length > 0) {
      hospitalsList.value = data.map(item => ({
        id: item.id,
        name: item.hospital?.facility_name || item.name || 'مستشفى معتمد',
        location: item.hospital?.address || item.location || 'قطاع غزة',
        remainingSeconds: item.remaining_seconds || 300,
        timeLeft: formatSeconds(item.remaining_seconds || 300),
        responseTime: item.expected_response_time || '6 دقائق',
        urgency: item.urgency_level || item.urgency || 'critical',
        icon: item.icon || 'Group 1000002306.png'
      }));
    }
  } catch (err) {
    console.warn('استخدام البيانات الافتراضية مع تفعيل المؤقت الحقيقي.');
  }
};

onMounted(() => {
  fetchRadarData();
  startCountdowns();
});

onUnmounted(() => {
  if (timerInterval.value) clearInterval(timerInterval.value);
});
</script>

<style scoped>
.radar-view {
  font-family: 'Cairo', sans-serif;
  padding-bottom: 24px;
}
</style>
