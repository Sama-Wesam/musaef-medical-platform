<template>
  <div class="map-tab-container position-relative rounded-4 overflow-hidden shadow-sm">
    <!-- 1. شريط التحكم التفاعلي بالخريطة الحرارية -->
    <div class="heatmap-controls position-absolute top-0 start-0 m-3 bg-white p-2 px-3 rounded-3 shadow-sm d-flex align-items-center gap-2" style="z-index: 1000;">
      <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
        <input
          class="form-check-input cursor-pointer me-0"
          type="checkbox"
          id="toggleHeatmap"
          v-model="showHeatmap"
          @change="toggleHeatmapLayer"
        />
        <label class="form-check-label fw-bold fs-8 text-dark cursor-pointer mb-0" for="toggleHeatmap">
          🔥 {{ t('showHeatmap') }}
        </label>
      </div>
    </div>

    <!-- 2. إطار الخريطة البرمجية التفاعلية -->
    <div id="musaef-leaflet-map" class="map-iframe"></div>

    <!-- 3. كارت تفاصيل المستشفى العائم -->
    <div
      v-if="selectedHospital"
      class="hospital-detail-card position-absolute bg-white rounded-4 p-3 p-md-4 shadow-lg interactive-card cursor-pointer"
      :class="currentLanguage === 'ar' ? 'dir-rtl' : 'dir-ltr'"
      @click="$emit('select-request', selectedHospital)"
    >
      <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3 position-relative">
        <button @click.stop="closeCard" class="btn-close fs-9 m-0" aria-label="إغلاق"></button>
        <span class="fw-bold text-dark fs-8 fs-md-7 mx-auto pe-2">{{ t('hospitalDetailsTitle') }}</span>
      </div>

      <div class="hospital-info-box p-2.5 bg-light-subtle rounded-3 border mb-3">
        <div class="d-flex align-items-center gap-2">
          <div class="hospital-text-container min-w-0" :class="currentLanguage === 'ar' ? 'text-end ms-auto' : 'text-start me-auto'">
            <h6 class="fw-bold text-dark mb-1 fs-8 fs-md-7 text-truncate" :title="translateHospital(selectedHospital.hospital)">
              {{ translateHospital(selectedHospital.hospital) }}
            </h6>
            <small class="text-muted fs-9 d-block text-truncate">
              <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ translateLocation(selectedHospital.location) }}
            </small>
          </div>

          <div class="hospital-img-fixed-box flex-shrink-0">
            <img :src="selectedHospital.img" alt="مستشفى" class="hospital-fixed-img rounded-2 shadow-2xs" @error="handleHospitalFallback" />
          </div>
        </div>
      </div>

      <div class="row text-center g-2 mb-3">
        <div class="col-6">
          <div class="bg-light-subtle rounded-3 p-2 border h-100 d-flex flex-column align-items-center justify-content-center">
            <small class="text-muted fs-9 d-block mb-0.5">{{ t('distance') }}</small>
            <span class="fw-bold text-dark fs-8 fs-md-7">{{ selectedHospital.distance }} {{ t('km') }}</span>
            <small class="text-muted d-block fs-9">{{ t('fromYourLocation') }}</small>
          </div>
        </div>
        <div class="col-6">
          <div class="bg-light-subtle rounded-3 p-2 border h-100 d-flex flex-column align-items-center justify-content-center">
            <small class="text-muted fs-9 d-block mb-0.5">{{ t('estimatedTime') }}</small>
            <div class="d-flex align-items-center justify-content-center gap-1 text-danger my-0.5">
              <i class="bi bi-car-front-fill fs-9"></i>
              <span class="fw-bold fs-8 fs-md-7">10 {{ t('minutes') }}</span>
            </div>
            <small class="text-muted fs-9">{{ t('byCar') }}</small>
          </div>
        </div>
      </div>

      <div class="mb-3 text-center">
        <small class="text-dark fs-8 d-block mb-1.5 fw-bold">{{ t('requiredBloodType') }}</small>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
          <span class="badge bg-pink-light text-danger border border-danger-subtle rounded-3 px-3 py-1.5 fs-8 fw-bold" dir="ltr">
            {{ selectedHospital.bloodType }}
          </span>
        </div>
      </div>

      <div class="bg-light-subtle rounded-3 p-2.5 p-md-3 border mb-3">
        <div class="row align-items-center text-center g-0">
          <div class="col-6 border-end" :class="currentLanguage === 'en' ? 'border-end' : 'border-start border-end-0'">
            <small class="text-muted fs-9 d-block mb-1">{{ t('urgency') }}</small>
            <span class="badge bg-danger text-white rounded-2 px-2.5 py-1 fs-9 fw-bold">
              {{ translateUrgency(selectedHospital.urgency) }}
            </span>
          </div>
          <div class="col-6">
            <small class="text-muted fs-9 d-block mb-1">{{ t('requiredUnits') }}</small>
            <span class="fw-bold text-dark fs-8 fs-md-7">{{ selectedHospital.units }} {{ t('units') }}</span>
          </div>
        </div>
      </div>

      <button class="btn btn-danger w-100 py-2 fw-bold fs-8 rounded-3 shadow-2xs">
        {{ t('startNavigation') }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted, computed } from 'vue';

const props = defineProps({
  requests: {
    type: Array,
    default: () => []
  }
});

defineEmits(['select-request']);

const showHeatmap = ref(true);
let map = null;
let heatmapLayer = null;
let markersGroup = null;

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: {
    hospitalDetailsTitle: 'تفاصيل المستشفى',
    distance: 'المسافة',
    km: 'كم',
    fromYourLocation: 'من موقعك',
    estimatedTime: 'الوقت المتوقع',
    minutes: 'دقائق',
    byCar: 'بالسيارة',
    requiredBloodType: 'الفصيلة المطلوبة',
    urgency: 'الخطورة',
    requiredUnits: 'الوحدات المطلوبة',
    units: 'وحدات',
    startNavigation: 'أبدأ التوجيه الآن',
    showHeatmap: 'تحليل الكثافة والخرائط الحرارية'
  },
  en: {
    hospitalDetailsTitle: 'Hospital Details',
    distance: 'Distance',
    km: 'km',
    fromYourLocation: 'From your location',
    estimatedTime: 'Estimated Time',
    minutes: 'mins',
    byCar: 'By car',
    requiredBloodType: 'Required Blood Type',
    urgency: 'Urgency',
    requiredUnits: 'Required Units',
    units: 'units',
    startNavigation: 'Start Navigation Now',
    showHeatmap: 'Heatmap Density Analysis'
  }
};

const hospitalDict = {
  'بنك الدم المركزي - وزارة الصحة': 'Central Blood Bank - Ministry of Health',
  'جمعية بنك الدم المركزي': 'Central Blood Bank Society',
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex',
  'المستشفى الإندونيسي': 'Indonesian Hospital',
  'مستشفى الأهلي العربي (المعمداني)': 'Al-Ahli Arab Hospital (Baptist)',
  'مستشفى أصلان/أبو يوسف النجار': 'Abu Yousef Al-Najjar Hospital',
  'مستشفى أبو يوسف النجار': 'Abu Yousef Al-Najjar Hospital',
  'مستشفى كمال عدوان': 'Kamal Adwan Hospital',
  'مستشفى القدس - الهلال الأحمر': 'Al-Quds Hospital - Red Crescent',
  'مجمع ناصر الطبي': 'Nasser Medical Complex',
  'مستشفى أصدقاء المريض الخيري': 'Patient\'s Friends Benevolent Society Hospital',
  'مستشفى أصدقاء المريض': 'Patient\'s Friends Hospital'
};

const locationDict = {
  'غزة - النصر': 'Gaza - An-Naser',
  'شمال غزة - بيت لاهيا': 'North Gaza - Beit Lahia',
  'غزة - الزيتون': 'Gaza - Zaytoun',
  'رفح - الشابورة': 'Rafah - Shaboura',
  'غزة - الرمال': 'Gaza - Rimal',
  'غزة - الرمال شارع الوحدة': 'Gaza - Rimal (Wehda St)',
  'غزة - تل الهوى': 'Gaza - Tel Al-Hawa',
  'خانيونس - وسط المدينة': 'Khan Younis - City Center',
  'غزة - فلسطين': 'Gaza - Palestine',
  'رفح - الجنينة': 'Rafah - Al-Jnena'
};

const hospitalCoordinates = {
  'مستشفى أصلان/أبو يوسف النجار': [31.2968, 34.2435],
  'مستشفى كمال عدوان': [31.5364, 34.4962],
  'المستشفى الإندونيسي': [31.5388, 34.5050],
  'مجمع الشفاء الطبي': [31.5247, 34.4447],
  'مجمع ناصر الطبي': [31.3462, 34.3031],
  'جمعية بنك الدم المركزي': [31.5210, 34.4530],
  'مستشفى الأهلي العربي (المعمداني)': [31.5082, 34.4632],
  'بنك الدم المركزي - وزارة الصحة': [31.5312, 34.4501],
  'مستشفى أصدقاء المريض الخيري': [31.5175, 34.4412]
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const translateHospital = (name) => currentLanguage.value === 'en' ? (hospitalDict[name] || name) : name;
const translateLocation = (loc) => currentLanguage.value === 'en' ? (locationDict[loc] || loc) : loc;

const translateUrgency = (urgency) => {
  if (currentLanguage.value === 'en') {
    if (urgency === 'حرجة جداً' || urgency === 'حرجة') return 'Critical';
    if (urgency === 'عالية') return 'High';
    if (urgency === 'متوسطة') return 'Medium';
    if (urgency === 'منخفضة') return 'Low';
  }
  return urgency;
};

const getImageUrl = (fileName) => {
  return new URL(`../../../assets/images/${fileName}`, import.meta.url).href;
};

const handleHospitalFallback = (e) => {
  e.target.src = getImageUrl('hospital.png');
};

const selectedHospital = ref(null);

const loadLeafletScripts = () => {
  return new Promise((resolve) => {
    if (window.L && window.L.heatLayer) {
      resolve();
      return;
    }

    if (!document.getElementById('leaflet-css')) {
      const css = document.createElement('link');
      css.id = 'leaflet-css';
      css.rel = 'stylesheet';
      css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
      document.head.appendChild(css);
    }

    const scriptLeaflet = document.createElement('script');
    scriptLeaflet.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    scriptLeaflet.onload = () => {
      const scriptHeat = document.createElement('script');
      scriptHeat.src = 'https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js';
      scriptHeat.onload = () => resolve();
      document.head.appendChild(scriptHeat);
    };
    document.head.appendChild(scriptLeaflet);
  });
};

const initMap = async () => {
  await loadLeafletScripts();

  if (!document.getElementById('musaef-leaflet-map')) return;

  map = window.L.map('musaef-leaflet-map').setView([31.42, 34.38], 11);

  window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors | Musaef Platform'
  }).addTo(map);

  markersGroup = window.L.layerGroup().addTo(map);
  updateMapElements();
};

const updateMapElements = () => {
  if (!map || !window.L) return;

  markersGroup.clearLayers();
  if (heatmapLayer) map.removeLayer(heatmapLayer);

  const heatPoints = [];

  props.requests.forEach((req) => {
    // جلب الإحداثيات المباشرة من قاعدة البيانات مع fallback إذا لم تتوفر
    const coords = (req.lat && req.lng)
      ? [parseFloat(req.lat), parseFloat(req.lng)]
      : (hospitalCoordinates[req.hospital] || [31.5247, 34.4447]);

    let intensity = 0.6;
    if (req.urgency === 'حرجة جداً' || req.urgency === 'critical') intensity = 1.0;
    else if (req.urgency === 'عالية' || req.urgency === 'high') intensity = 0.8;

    heatPoints.push([...coords, intensity]);

    const customIcon = window.L.divIcon({
      className: 'custom-map-pin',
      html: `<div class="pin-marker ${intensity === 1.0 ? 'pulse-red' : ''}"><i class="bi bi-geo-alt-fill"></i></div>`,
      iconSize: [30, 30],
      iconAnchor: [15, 30]
    });

    const marker = window.L.marker(coords, { icon: customIcon });
    marker.on('click', () => {
      selectedHospital.value = req;
    });

    markersGroup.addLayer(marker);
  });

  if (heatPoints.length > 0 && showHeatmap.value && window.L.heatLayer) {
    heatmapLayer = window.L.heatLayer(heatPoints, {
      radius: 35,
      blur: 20,
      maxZoom: 13,
      gradient: { 0.4: 'blue', 0.65: 'lime', 0.8: 'yellow', 1.0: 'red' }
    }).addTo(map);
  }
};

const toggleHeatmapLayer = () => {
  if (!map) return;
  if (showHeatmap.value) {
    updateMapElements();
  } else if (heatmapLayer) {
    map.removeLayer(heatmapLayer);
  }
};

watch(() => props.requests, (newRequests) => {
  if (newRequests && newRequests.length > 0) {
    if (!selectedHospital.value) {
      selectedHospital.value = newRequests[0];
    }
    updateMapElements();
  }
}, { deep: true });

onMounted(() => {
  initMap();
  if (props.requests && props.requests.length > 0) {
    selectedHospital.value = props.requests[0];
  }
});

onUnmounted(() => {
  if (map) {
    map.remove();
  }
});

const closeCard = () => {
  selectedHospital.value = null;
};
</script>

<style scoped>
.map-tab-container {
  font-family: Arial, sans-serif !important;
  min-height: 450px;
}
.map-iframe { height: 450px; width: 100%; }

@media (min-width: 768px) {
  .map-tab-container { min-height: 600px; }
  .map-iframe { height: 600px; }
}

.hospital-detail-card {
  bottom: 15px;
  right: 15px;
  left: 15px;
  width: auto;
  z-index: 1000;
  border: 1px solid #e2e8f0;
}

@media (min-width: 768px) {
  .hospital-detail-card {
    top: 25px;
    right: 25px;
    left: auto;
    bottom: auto;
    width: 360px;
  }
  .dir-ltr.hospital-detail-card {
    right: 25px !important;
    left: auto !important;
  }
}

.hospital-info-box { width: 100%; }
.hospital-text-container { max-width: calc(100% - 70px); }
.hospital-img-fixed-box { width: 60px; height: 48px; overflow: hidden; border-radius: 6px; }
.hospital-fixed-img { width: 100% !important; height: 100% !important; object-fit: cover !important; display: block; }

.interactive-card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.interactive-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
}

.bg-pink-light { background-color: #fdecec; }
.bg-light-subtle { background-color: #f8fafc; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.border-danger-subtle { border-color: #fca5a5 !important; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
.cursor-pointer { cursor: pointer; }

:deep(.custom-map-pin) {
  display: flex;
  align-items: center;
  justify-content: center;
}

:deep(.pin-marker) {
  font-size: 24px;
  color: #dc2626;
  filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.3));
  transition: transform 0.2s ease;
}

:deep(.pin-marker:hover) {
  transform: scale(1.25);
}

:deep(.pulse-red) {
  animation: pulse-animation 1.5s infinite;
}

@keyframes pulse-animation {
  0% { transform: scale(1); }
  50% { transform: scale(1.3); }
  100% { transform: scale(1); }
}
</style>
