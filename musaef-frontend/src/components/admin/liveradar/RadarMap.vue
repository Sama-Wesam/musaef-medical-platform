<template>
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative h-100 radar-map-card">
    <!-- حاوية خريطة Leaflet -->
    <div id="leaflet-map" ref="mapContainer" class="map-container"></div>

    <!-- مؤشر التحميل لحين تجهيز الخريطة -->
    <div v-if="isLoading" class="map-loader position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75">
      <div class="spinner-border text-danger" role="status">
        <span class="visually-hidden">جاري تحميل الخريطة التفاعلية...</span>
      </div>
    </div>

    <!-- دليل مستوى الخطورة -->
    <SeverityLegend />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import SeverityLegend from '@/components/admin/liveradar/SeverityLegend.vue';

const props = defineProps({
  heatData: {
    type: Array,
    default: () => []
  },
  hospitals: {
    type: Array,
    default: () => []
  }
});

const mapContainer = ref(null);
const isLoading = ref(true);

let mapInstance = null;
let heatLayer = null;
let markersGroup = null;

// تحميل مكتبات Leaflet ديناميكياً
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

// تهيئة الخريطة
const initMap = async () => {
  try {
    loadStyle('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
    await loadScript('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js');
    await loadScript('https://cdn.jsdelivr.net/npm/leaflet-heatmap@1.0.0/leaflet-heatmap.js').catch(() => {
      return loadScript('https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js');
    });

    if (!mapContainer.value || !window.L) return;

    // مركز غزة الافتراضي
    const centerLat = 31.4;
    const centerLng = 34.38;

    mapInstance = window.L.map(mapContainer.value, {
      zoomControl: false
    }).setView([centerLat, centerLng], 11);

    // إضافة التحكم بالزوم في الزاوية اليسرى
    window.L.control.zoom({ position: 'topleft' }).addTo(mapInstance);

    // استخدام خريطة OpenStreetMap أو CartoDB
    window.L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
    }).addTo(mapInstance);

    markersGroup = window.L.layerGroup().addTo(mapInstance);

    renderHeatMap();
    renderMarkers();
    isLoading.value = false;
  } catch (err) {
    console.error('Failed to initialize Leaflet Map:', err);
    isLoading.value = false;
  }
};

// رسم الخريطة الحرارية (Heatmap Overlay)
const renderHeatMap = () => {
  if (!mapInstance || !window.L) return;

  if (heatLayer) {
    mapInstance.removeLayer(heatLayer);
  }

  // استخدام البيانات الممررة أو استخدام نقاط افتراضية لقطاع غزة في حال التجهيز
  const points = (props.heatData && props.heatData.length > 0)
    ? props.heatData.map(p => [p.lat, p.lng, p.intensity || 0.8])
    : [
        [31.501, 34.466, 0.9], // شمال غزة
        [31.520, 34.450, 0.7],
        [31.416, 34.333, 0.85], // دير البلح / الوسطى
        [31.345, 34.303, 0.95], // خانيونس
        [31.296, 34.243, 1.0]   // رفح
      ];

  if (window.L.heatLayer) {
    heatLayer = window.L.heatLayer(points, {
      radius: 28,
      blur: 20,
      maxZoom: 14,
      gradient: {
        0.2: '#38ef7d',
        0.5: '#f1c40f',
        0.8: '#e67e22',
        1.0: '#e74c3c'
      }
    }).addTo(mapInstance);
  }
};

// إضافة المستشفيات كـ Markers تفاعلية
const renderMarkers = () => {
  if (!mapInstance || !markersGroup || !window.L) return;

  markersGroup.clearLayers();

  const hospitalsData = (props.hospitals && props.hospitals.length > 0)
    ? props.hospitals
    : [
        { name: 'مستشفى العودة', lat: 31.52, lng: 34.45, urgency: 'critical' },
        { name: 'مستشفى ناصر', lat: 31.345, lng: 34.303, urgency: 'critical' },
        { name: 'مستشفى الكويتي', lat: 31.296, lng: 34.243, urgency: 'critical' }
      ];

  hospitalsData.forEach(h => {
    if (h.lat && h.lng) {
      const customIcon = window.L.divIcon({
        className: 'custom-map-pin',
        html: `<div class="pulse-pin ${h.urgency || 'critical'}"></div>`,
        iconSize: [24, 24],
        iconAnchor: [12, 12]
      });

      const marker = window.L.marker([h.lat, h.lng], { icon: customIcon });

      const popupContent = `
        <div class="p-2 text-center dir-rtl">
          <strong class="d-block text-danger fw-bold fs-6">${h.translatedName || h.name}</strong>
          <small class="text-muted d-block mt-1">${h.translatedLocation || h.location || ''}</small>
        </div>
      `;

      marker.bindPopup(popupContent);
      markersGroup.addLayer(marker);
    }
  });
};

watch(() => props.heatData, () => {
  renderHeatMap();
}, { deep: true });

watch(() => props.hospitals, () => {
  renderMarkers();
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
.radar-map-card {
  min-height: 350px;
  z-index: 1;
}

.map-container {
  width: 100%;
  height: 100%;
  min-height: 350px;
}

.map-loader {
  z-index: 10;
}

@media (min-width: 768px) {
  .radar-map-card, .map-container {
    min-height: 480px;
  }
}

@media (min-width: 992px) {
  .radar-map-card, .map-container {
    min-height: 560px;
  }
}
</style>

<style>
/* أنماط DPIN التفاعلية للـ Markers على الخريطة */
.pulse-pin {
  width: 18px;
  height: 18px;
  background-color: #dc3545;
  border-radius: 50%;
  border: 2px solid #ffffff;
  box-shadow: 0 0 8px rgba(220, 53, 69, 0.8);
  animation: pulse-ring 1.8s infinite;
}

.pulse-pin.warning {
  background-color: #ffc107;
  box-shadow: 0 0 8px rgba(255, 193, 7, 0.8);
}

@keyframes pulse-ring {
  0% {
    transform: scale(0.95);
    box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
  }
  70% {
    transform: scale(1.15);
    box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
  }
  100% {
    transform: scale(0.95);
    box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
  }
}
</style>
