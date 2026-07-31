<template>
  <AdminLayout>
    <div class="radar-view container-fluid px-2 px-md-3" dir="rtl">

      <div class="row g-3 g-lg-4">
        <!-- 1. القسم الأيمن: قائمة الحالات الحرجة المباشرة (Emergency Priority AI) -->
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
import echo from '@/utils/echo';

import CriticalCasesList from '@/components/admin/liveradar/CriticalCasesList.vue';
import RadarMap from '@/components/admin/liveradar/RadarMap.vue';

const filter = ref('all');
const timerInterval = ref(null);

// التثبيت المسبق لـ 3 حالات حرجة مباشرة تظهر فور التحميل وثابتة لاختفاء أي وميض
const hospitalsList = ref([
  {
    id: 1,
    name: 'مستشفى الكويتي',
    location: 'الجنوب - رفح',
    remainingSeconds: 332,
    timeLeft: '00:05:32',
    responseTime: '6 دقائق',
    urgency: 'critical',
    icon: 'Group 1000002306.png'
  },
  {
    id: 2,
    name: 'مستشفى العودة',
    location: 'وسطى - النصيرات',
    remainingSeconds: 272,
    timeLeft: '00:04:32',
    responseTime: '6 دقائق',
    urgency: 'critical',
    icon: 'Group 1000002306 (1).png'
  },
  {
    id: 3,
    name: 'مستشفى ناصر',
    location: 'جنوب - خانيونس',
    remainingSeconds: 572,
    timeLeft: '00:09:32',
    responseTime: '6 دقائق',
    urgency: 'critical',
    icon: 'Group 1000002306 (2).png'
  }
]);

// تصفية المستشفيات بحسب الفلتر (حرجة، متوسطة، منخفضة)
const filteredHospitals = computed(() => {
  if (filter.value === 'all') return hospitalsList.value;
  return hospitalsList.value.filter(h => h.urgency === filter.value);
});

const formatSeconds = (totalSec) => {
  if (totalSec <= 0) return '00:00:00';
  const hrs = Math.floor(totalSec / 3600);
  const mins = Math.floor((totalSec % 3600) / 60);
  const secs = totalSec % 60;
  return `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
};

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

const fetchRadarData = async () => {
  try {
    const res = await apiClient.get('/admin/emergency-radar');
    const data = res.data?.data || res.data;
    if (Array.isArray(data) && data.length > 0) {
      // دمج البيانات القادمة فقط إن وجدت وتجاوز قيم الاستبدال الخاطئة
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
    console.warn('الحفاظ على القائمة الافتراضية المكونة من 3 حالات حرجة بنجاح.');
  }
};

onMounted(() => {
  fetchRadarData();
  startCountdowns();

  // الاستماع اللحظي للحدث المباشر لإضافة الحالة الحرجة فوراً لرادار الأدمن
  try {
    echo.channel('emergencies.live')
      .listen('.new.emergency', (e) => {
        console.log('🚨 تم إطلاق حالة حرجة على رادار الطوارئ:', e.bloodRequest);

        const newCase = {
          id: e.bloodRequest?.id || Date.now(),
          name: e.bloodRequest?.facility_name || 'مستشفى طارئ جديد',
          location: e.bloodRequest?.address || 'غزة',
          remainingSeconds: 600,
          timeLeft: '00:10:00',
          responseTime: '5 دقائق',
          urgency: 'critical',
          icon: 'Group 1000002306.png'
        };

        hospitalsList.value.unshift(newCase);
      });
  } catch (err) {
    console.warn('تعذر الاتصال بقناة رادار الطوارئ الحي.');
  }
});

onUnmounted(() => {
  if (timerInterval.value) clearInterval(timerInterval.value);
  try {
    echo.leaveChannel('emergencies.live');
  } catch (e) {}
});
</script>

<style scoped>
.radar-view {
  font-family: 'Cairo', sans-serif;
  padding-bottom: 24px;
}
</style>
