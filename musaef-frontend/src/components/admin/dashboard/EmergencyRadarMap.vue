<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white h-100 overflow-hidden" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
      <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('radarTitle') }}</h6>
      <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill fs-8 px-2 py-1 d-flex align-items-center gap-1">
        <span class="pulse-dot"></span>
        {{ t('liveUpdates') }}
      </span>
    </div>

    <!-- حاوية الخريطة التفاعلية -->
    <div class="rounded-3 overflow-hidden position-relative flex-grow-1 radar-map-container">
      <div id="admin-radar-map" ref="mapContainer" class="w-100 h-100 min-height-radar"></div>

      <!-- مؤشر التحميل -->
      <div v-if="isLoading" class="map-loader position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75">
        <div class="spinner-border text-danger spinner-border-sm" role="status">
          <span class="visually-hidden">جاري تحميل رادار الطوارئ...</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useAdminDashboardStore } from '@/stores/adminStore';

const dashboardStore = useAdminDashboardStore();
const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');
const mapContainer = ref(null);
const isLoading = ref(true);

let mapInstance = null;
let heatLayer = null;
let markersGroup = null;

const dictionary = {
  ar: {
    radarTitle: 'رادار الطوارئ المباشر',
    liveUpdates: 'مباشر',
    emergencyCase: 'حالة طارئة',
    bloodType: 'فصيلة الدم'
  },
  en: {
    radarTitle: 'Live Emergency Radar',
    liveUpdates: 'Live',
    emergencyCase: 'Emergency Case',
    bloodType: 'Blood Type'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

// جلب نقاط الرادار من Store أو استخدام نقاط افتراضية لقطاع غزة في حال عدم توفرها
const radarPoints = computed(() => {
  if (dashboardStore.radarPoints && dashboardStore.radarPoints.length > 0) {
    return dashboardStore.radarPoints;
  }
  return [
    { id: 1, lat: 31.514, lng: 34.448, intensity: 1.0, bloodType: 'O-', name: 'مستشفى الشفاء' },
    { id: 2, lat: 31.502, lng: 34.439, intensity: 0.8, bloodType: 'A+', name: 'مستشفى القدس' },
    { id: 3, lat: 31.543, lng: 34.498, intensity: 0.6, bloodType: 'B-', name: 'مستشفى العودة' },
    { id: 4, lat: 31.345, lng: 34.303, intensity: 0.9, bloodType: 'AB+', name: 'مجمع ناصر الطبي' },
    { id: 5, lat: 31.520, lng: 34.455, intensity: 0.7, bloodType: 'O+', name: 'عيادة الرمال' }
  ];
});

// تحميل مكتبات Leaflet و Leaflet Heat dynamically
const loadScript = (src) => {
  return new Promise((resolve, reject) => {
    if (document.querySelector(`script[src="${src}"]`)) {
      resolve();
      return;
    }
    const script = document.createElement('script');
    script.src = src;
    script.onload = resolve;
    script.onerror = reject;
    document.head.appendChild(script);
  });
};

const loadStyle = (href) => {
  if (!document.querySelector(`link[href="${href}"]`)) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = href;
    document.head.appendChild(link);
  }
};

const initMap = async () => {
  try {
    loadStyle('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
    await loadScript('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js');
    await loadScript('https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js').catch(() => {});

    if (!mapContainer.value || !window.L) return;

    // مركز الخريطة الافتراضي
    mapInstance = window.L.map(mapContainer.value, {
      zoomControl: false,
      attributionControl: false
    }).setView([31.45, 34.40], 11);

    window.L.control.zoom({ position: 'topleft' }).addTo(mapInstance);

    window.L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
      maxZoom: 19
    }).addTo(mapInstance);

    renderRadarLayers();
    isLoading.value = false;
  } catch (err) {
    console.error('Failed to initialize Admin Radar Map:', err);
    isLoading.value = false;
  }
};

const renderRadarLayers = () => {
  if (!mapInstance || !window.L) return;

  // 1. مسح الطبقات القديمة إن وجدت
  if (heatLayer) mapInstance.removeLayer(heatLayer);
  if (markersGroup) mapInstance.removeLayer(markersGroup);

  markersGroup = window.L.layerGroup().addTo(mapInstance);

  const heatData = [];

  radarPoints.value.forEach((pt) => {
    const lat = parseFloat(pt.lat);
    const lng = parseFloat(pt.lng);
    const intensity = parseFloat(pt.intensity) || 0.8;

    heatData.push([lat, lng, intensity]);

    // إضافة Custom Marker لكل نقطة طوارئ
    const customIcon = window.L.divIcon({
      className: 'custom-radar-pin',
      html: `<div class="radar-ping-icon">🩸</div>`,
      iconSize: [26, 26],
      iconAnchor: [13, 13]
    });

    const marker = window.L.marker([lat, lng], { icon: customIcon });
    marker.bindPopup(`
      <div class="p-1 text-center ${currentLanguage.value === 'ar' ? 'dir-rtl' : 'dir-ltr'}">
        <strong class="d-block text-danger fs-8 fw-bold">${pt.name || t('emergencyCase')}</strong>
        <span class="badge bg-danger text-white mt-1 fs-9">${t('bloodType')}: ${pt.bloodType || 'N/A'}</span>
      </div>
    `);

    markersGroup.addLayer(marker);
  });

  // 2. تطبيق خريطة الحرارة Heatmap Overlay
  if (window.L.heatLayer && heatData.length > 0) {
    heatLayer = window.L.heatLayer(heatData, {
      radius: 30,
      blur: 20,
      maxZoom: 14,
      gradient: {
        0.2: '#2ecc71',
        0.5: '#f1c40f',
        0.8: '#e67e22',
        1.0: '#dc3545'
      }
    }).addTo(mapInstance);
  }
};

watch(radarPoints, () => {
  renderRadarLayers();
}, { deep: true });

onMounted(() => {
  initMap();
});

onUnmounted(() => {
  if (mapInstance) {
    mapInstance.remove();
  }
});
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.725rem; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }

.radar-map-container {
  min-height: 220px;
  z-index: 1;
}

.min-height-radar {
  min-height: 220px;
}

.map-loader {
  z-index: 10;
}

/* النقطة النابضة لتوضيح البث المباشر */
.pulse-dot {
  width: 8px;
  height: 8px;
  background-color: #dc3545;
  border-radius: 50%;
  display: inline-block;
  animation: pulse-live 1.5s infinite;
}

@keyframes pulse-live {
  0% { transform: scale(0.95); opacity: 1; }
  50% { transform: scale(1.3); opacity: 0.4; }
  100% { transform: scale(0.95); opacity: 1; }
}
</style>

<style>
/* أيقونة النبض التفاعلية الخاصة بقطرات الدم على الخريطة */
.radar-ping-icon {
  width: 26px;
  height: 26px;
  background: #ffffff;
  border-radius: 50%;
  border: 2px solid #dc3545;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  box-shadow: 0 2px 6px rgba(220, 53, 69, 0.35);
  cursor: pointer;
  transition: transform 0.2s ease;
}

.radar-ping-icon:hover {
  transform: scale(1.15);
}
</style>
