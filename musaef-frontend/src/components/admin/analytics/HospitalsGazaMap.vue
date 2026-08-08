<template>
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative h-100 map-card" :dir="langStore.dir">
    <!-- حاوية خريطة Leaflet التفاعلية -->
    <div id="hospitals-gaza-map" ref="mapContainer" class="w-100 h-100 min-map-height"></div>

    <!-- مؤشر التحميل -->
    <div v-if="isLoading" class="map-loader position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75">
      <div class="spinner-border text-danger spinner-border-sm" role="status">
        <span class="visually-hidden">جاري تحميل خريطة التحليلات...</span>
      </div>
    </div>

    <!-- شارة عنوان الخريطة -->
    <div
      class="position-absolute bg-white px-2.5 px-md-3 py-1.5 rounded-3 shadow-sm text-dark fw-bold fs-9 fs-md-8 title-badge"
      :class="langStore.isRtl ? 'badge-rtl' : 'badge-ltr'"
    >
      {{ t('mapTitle') }}
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useLangStore } from '@/stores/langStore';
import { useAnalyticsStore } from '@/stores/analyticsStore';

const langStore = useLangStore();
const analyticsStore = useAnalyticsStore();
const currentLanguage = computed(() => langStore.currentLang);

const mapContainer = ref(null);
const isLoading = ref(true);

let mapInstance = null;
let heatLayer = null;
let markersGroup = null;

const dictionary = {
  ar: { mapTitle: 'خريطة التوزيع الجغرافي واحتياجات المستشفيات (AI Heatmap)', needLevel: 'مستوى الاحتياج' },
  en: { mapTitle: 'Hospitals Geographic Distribution Map (AI Heatmap)', needLevel: 'Need Level' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

// التحميل الديناميكي لسكربتات Leaflet
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

    // تهيئة الخريطة بمركز قطاع غزة
    mapInstance = window.L.map(mapContainer.value, {
      zoomControl: false,
      attributionControl: false
    }).setView([31.42, 34.38], 11);

    window.L.control.zoom({ position: 'bottomleft' }).addTo(mapInstance);

    window.L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
      maxZoom: 18
    }).addTo(mapInstance);

    renderHospitalsData();
    isLoading.value = false;
  } catch (err) {
    console.error('Failed to initialize Gaza Hospitals Analytics Map:', err);
    isLoading.value = false;
  }
};

const renderHospitalsData = () => {
  if (!mapInstance || !window.L) return;

  if (heatLayer) mapInstance.removeLayer(heatLayer);
  if (markersGroup) mapInstance.removeLayer(markersGroup);

  markersGroup = window.L.layerGroup().addTo(mapInstance);

  // مواضع المستشفيات الرئيسية في القطاع
  const defaultHospitals = [
    { name: 'مستشفى الشفاء', nameEn: 'Al-Shifa Hospital', lat: 31.514, lng: 34.448, need: 95 },
    { name: 'مستشفى القدس', nameEn: 'Al-Quds Hospital', lat: 31.502, lng: 34.439, need: 88 },
    { name: 'مستشفى ناصر', nameEn: 'Nasser Hospital', lat: 31.345, lng: 34.303, need: 82 },
    { name: 'مستشفى الأوروبي', nameEn: 'European Hospital', lat: 31.301, lng: 34.332, need: 75 },
    { name: 'مستشفى الأندونيسي', nameEn: 'Indonesian Hospital', lat: 31.543, lng: 34.498, need: 68 }
  ];

  const heatData = [];

  defaultHospitals.forEach((h) => {
    const intensity = h.need / 100;
    heatData.push([h.lat, h.lng, intensity]);

    const customIcon = window.L.divIcon({
      className: 'hospital-ai-marker',
      html: `<div class="hospital-pin-badge">${h.need}%</div>`,
      iconSize: [32, 32],
      iconAnchor: [16, 16]
    });

    const displayName = currentLanguage.value === 'en' ? h.nameEn : h.name;
    const marker = window.L.marker([h.lat, h.lng], { icon: customIcon });
    marker.bindPopup(`
      <div class="p-1 text-center">
        <strong class="d-block text-dark fs-8 fw-bold">${displayName}</strong>
        <span class="badge bg-danger text-white mt-1 fs-9">${t('needLevel')}: ${h.need}%</span>
      </div>
    `);

    markersGroup.addLayer(marker);
  });

  if (window.L.heatLayer && heatData.length > 0) {
    heatLayer = window.L.heatLayer(heatData, {
      radius: 35,
      blur: 22,
      maxZoom: 13,
      gradient: {
        0.3: '#2ecc71',
        0.6: '#f1c40f',
        0.85: '#e67e22',
        1.0: '#dc3545'
      }
    }).addTo(mapInstance);
  }
};

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
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }

.min-map-height {
  min-height: 280px;
}

.map-card {
  min-height: 280px;
  z-index: 1;
}

@media (min-width: 768px) {
  .map-card, .min-map-height {
    min-height: 340px;
  }
}

.title-badge {
  top: 12px;
  z-index: 10;
}

.badge-rtl { right: 12px; }
.badge-ltr { left: 12px; }

.map-loader {
  z-index: 20;
}
</style>

<style>
.hospital-ai-marker {
  background: transparent;
  border: none;
}

.hospital-pin-badge {
  width: 32px;
  height: 32px;
  background: #dc3545;
  color: #ffffff;
  border-radius: 50%;
  border: 2px solid #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 10px;
  font-weight: 800;
  box-shadow: 0 3px 8px rgba(220, 53, 69, 0.4);
  cursor: pointer;
  transition: transform 0.2s ease;
}

.hospital-pin-badge:hover {
  transform: scale(1.15);
}
</style>
