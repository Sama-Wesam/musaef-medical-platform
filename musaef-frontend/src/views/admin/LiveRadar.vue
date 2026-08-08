<template>
  <AdminLayout>
    <div class="radar-view container-fluid px-2 px-md-3" :dir="langStore.dir">
      <div class="row g-3 g-lg-4">
        <!-- 1. قائمة الحالات الحرجة المباشرة (معدلة لتكون في أقصى اليمين) -->
        <div class="col-12 col-lg-5 col-xl-4 order-1 order-lg-1">
          <CriticalCasesList
            v-model:filter="filter"
            :hospitals="filteredHospitals"
            @refresh="fetchRadarData"
          />
        </div>

        <!-- 2. الخريطة الجغرافية ودليل الخطورة (أصبحت إلى اليسار) -->
        <div class="col-12 col-lg-7 col-xl-8 order-2 order-lg-2">
          <RadarMap
            :heat-data="heatMapPoints"
            :hospitals="filteredHospitals"
          />
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
import { useLangStore } from '@/stores/langStore';

import CriticalCasesList from '@/components/admin/liveradar/CriticalCasesList.vue';
import RadarMap from '@/components/admin/liveradar/RadarMap.vue';

const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const filter = ref('all');
const timerInterval = ref(null);
const heatMapPoints = ref([]);

// قاموس الترجمة لمستشفيات الرادار ومواقعها
const hospitalNames = {
  'مستشفى الكويتي': { ar: 'مستشفى الكويتي', en: 'Kuwaiti Hospital' },
  'مستشفى العودة': { ar: 'مستشفى العودة', en: 'Al-Awda Hospital' },
  'مستشفى ناصر': { ar: 'مستشفى ناصر', en: 'Nasser Hospital' }
};

const locationNames = {
  'الجنوب - رفح': { ar: 'الجنوب - رفح', en: 'South - Rafah' },
  'وسطى - النصيرات': { ar: 'وسطى - النصيرات', en: 'Central - Nuseirat' },
  'جنوب - خانيونس': { ar: 'جنوب - خانيونس', en: 'South - Khan Younis' }
};

const getEtaUnit = () => (currentLanguage.value === 'en' ? 'mins' : 'دقائق');

// البيانات المبدئية للثلاث حالات شاملة الإحداثيات للخريطة
const defaultHospitals = [
  {
    id: 1,
    name: 'مستشفى الكويتي',
    location: 'الجنوب - رفح',
    lat: 31.296,
    lng: 34.243,
    remainingSeconds: 324,
    timeLeft: '00:05:24',
    responseTimeVal: 6,
    urgency: 'critical',
    icon: 'Group 1000002306.png'
  },
  {
    id: 2,
    name: 'مستشفى العودة',
    location: 'وسطى - النصيرات',
    lat: 31.450,
    lng: 34.380,
    remainingSeconds: 264,
    timeLeft: '00:04:24',
    responseTimeVal: 6,
    urgency: 'critical',
    icon: 'Group 1000002306 (1).png'
  },
  {
    id: 3,
    name: 'مستشفى ناصر',
    location: 'جنوب - خانيونس',
    lat: 31.345,
    lng: 34.303,
    remainingSeconds: 564,
    timeLeft: '00:09:24',
    responseTimeVal: 6,
    urgency: 'critical',
    icon: 'Group 1000002306 (2).png'
  }
];

const hospitalsList = ref([...defaultHospitals]);

// تصفية المستشفيات بحسب الفلتر مع ترجمة الأسماء والمواقع ديناميكياً
const filteredHospitals = computed(() => {
  let list = hospitalsList.value;
  if (filter.value !== 'all') {
    list = list.filter(h => h.urgency === filter.value);
  }

  return list.map(h => ({
    ...h,
    translatedName: hospitalNames[h.name] ? hospitalNames[h.name][currentLanguage.value === 'en' ? 'en' : 'ar'] : h.name,
    translatedLocation: locationNames[h.location] ? locationNames[h.location][currentLanguage.value === 'en' ? 'en' : 'ar'] : h.location,
    responseTime: `${h.responseTimeVal || 6} ${getEtaUnit()}`
  }));
});

const formatSeconds = (totalSec) => {
  if (totalSec <= 0) return '00:00:00';
  const hrs = Math.floor(totalSec / 3600);
  const mins = Math.floor((totalSec % 3600) / 60);
  const secs = totalSec % 60;
  return `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
};

const startCountdowns = () => {
  if (timerInterval.value) clearInterval(timerInterval.value);
  timerInterval.value = setInterval(() => {
    hospitalsList.value.forEach(item => {
      if (item.remainingSeconds > 0) {
        item.remainingSeconds--;
        item.timeLeft = formatSeconds(item.remainingSeconds);
      }
    });
  }, 1000);
};

// جلب تحليلات الخريطة الحرارية من الـ Backend
const fetchHeatMapData = async () => {
  try {
    const res = await apiClient.get('/admin/analytics/heat-map');
    const data = res.data?.data || res.data;
    if (Array.isArray(data)) {
      heatMapPoints.value = data;
    }
  } catch (err) {
    console.warn('Using default heatmap coordinates.');
  }
};

const fetchRadarData = async () => {
  try {
    const res = await apiClient.get('/admin/emergency-radar');
    const data = res.data?.data || res.data;
    if (Array.isArray(data) && data.length > 0) {
      hospitalsList.value = data.map(item => ({
        id: item.id,
        name: item.hospital?.facility_name || item.name || 'مستشفى الكويتي',
        location: item.hospital?.address || item.location || 'غزة',
        lat: parseFloat(item.hospital?.latitude || item.lat || 31.3),
        lng: parseFloat(item.hospital?.longitude || item.lng || 34.3),
        remainingSeconds: item.remaining_seconds || 300,
        timeLeft: formatSeconds(item.remaining_seconds || 300),
        responseTimeVal: item.expected_response_time_val || 6,
        urgency: item.urgency_level || item.urgency || 'critical',
        icon: item.icon || 'Group 1000002306.png'
      }));
    }
  } catch (err) {
    console.warn('Maintaining default critical cases list successfully.');
  }
};

onMounted(() => {
  fetchRadarData();
  fetchHeatMapData();
  startCountdowns();

  try {
    echo.channel('emergencies.live')
      .listen('.new.emergency', (e) => {
        const newCase = {
          id: e.bloodRequest?.id || Date.now(),
          name: e.bloodRequest?.facility_name || 'مستشفى الكويتي',
          location: e.bloodRequest?.address || 'غزة',
          lat: parseFloat(e.bloodRequest?.latitude || 31.35),
          lng: parseFloat(e.bloodRequest?.longitude || 34.32),
          remainingSeconds: 600,
          timeLeft: '00:10:00',
          responseTimeVal: 5,
          urgency: 'critical',
          icon: 'Group 1000002306.png'
        };

        hospitalsList.value.unshift(newCase);
      });
  } catch (err) {
    console.warn('Could not connect to live emergency radar channel.');
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
