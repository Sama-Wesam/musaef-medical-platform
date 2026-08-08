<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <strong class="text-dark fs-8 d-block mb-2">{{ t('locationMap') }}</strong>

    <!-- خريطة تفاعلية بالدوائر الحرارية بدلاً من iframe -->
    <div class="map-wrapper rounded-3 overflow-hidden border position-relative mb-3" style="height: 140px;">
      <div id="request-location-map" ref="mapContainer" class="w-100 h-100"></div>

      <!-- مؤشر التحميل -->
      <div v-if="isLoading" class="map-loader position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75">
        <div class="spinner-border text-danger spinner-border-sm" role="status">
          <span class="visually-hidden">جاري تحميل الخريطة...</span>
        </div>
      </div>
    </div>

    <!-- أزرار الإجراءات (قبول الطلب / رفض الطلب) -->
    <div class="d-flex align-items-center gap-2">
      <button
        type="button"
        @click="$emit('accept', requestId)"
        class="btn btn-danger flex-fill rounded-3 py-2 fw-bold fs-8 text-nowrap shadow-2xs"
      >
        {{ t('acceptRequest') }}
      </button>

      <button
        type="button"
        @click="$emit('reject', requestId)"
        class="btn btn-outline-danger flex-fill rounded-3 py-2 fw-bold fs-8 text-nowrap"
      >
        {{ t('rejectRequest') }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
  latitude: {
    type: Number,
    default: 31.5
  },
  longitude: {
    type: Number,
    default: 34.45
  },
  requestId: {
    type: [String, Number],
    default: null
  }
});

defineEmits(['accept', 'reject']);

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');
const mapContainer = ref(null);
const isLoading = ref(true);

let mapInstance = null;
let markerInstance = null;
let heatLayer = null;

const dictionary = {
  ar: {
    locationMap: "الموقع على الخريطة",
    acceptRequest: "قبول الطلب",
    rejectRequest: "رفض الطلب",
    emergencyLocation: "موقع الحالة الطارئة"
  },
  en: {
    locationMap: "Location on Map",
    acceptRequest: "Accept Request",
    rejectRequest: "Reject Request",
    emergencyLocation: "Emergency Location"
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

// تحميل سكربتات وحزم Leaflet ديناميكياً
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

// بناء وتحديث الخريطة التفاعلية
const initMap = async () => {
  try {
    loadStyle('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
    await loadScript('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js');
    await loadScript('https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js').catch(() => {});

    if (!mapContainer.value || !window.L) return;

    const lat = parseFloat(props.latitude) || 31.5;
    const lng = parseFloat(props.longitude) || 34.45;

    mapInstance = window.L.map(mapContainer.value, {
      zoomControl: false,
      attributionControl: false
    }).setView([lat, lng], 14);

    window.L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
      maxZoom: 19
    }).addTo(mapInstance);

    updateMapContent(lat, lng);
    isLoading.value = false;
  } catch (err) {
    console.error('Failed to initialize Leaflet Map in LocationMapCard:', err);
    isLoading.value = false;
  }
};

const updateMapContent = (lat, lng) => {
  if (!mapInstance || !window.L) return;

  // 1. تحديث أو إضافة Marker
  if (markerInstance) {
    mapInstance.removeLayer(markerInstance);
  }

  const customIcon = window.L.divIcon({
    className: 'custom-emergency-pin',
    html: `<div class="emergency-marker-pulse">🚨</div>`,
    iconSize: [28, 28],
    iconAnchor: [14, 14]
  });

  markerInstance = window.L.marker([lat, lng], { icon: customIcon }).addTo(mapInstance);
  markerInstance.bindPopup(`<div class="fs-8 fw-bold text-danger text-center p-1">${t('emergencyLocation')}</div>`);

  // 2. تحديث رسم الدوائر الحرارية Heatmap Overlay
  if (heatLayer) {
    mapInstance.removeLayer(heatLayer);
  }

  if (window.L.heatLayer) {
    const heatData = [
      [lat, lng, 1.0],
      [lat + 0.0015, lng + 0.0015, 0.6],
      [lat - 0.0015, lng - 0.0015, 0.6],
      [lat + 0.002, lng - 0.001, 0.4],
      [lat - 0.001, lng + 0.002, 0.4]
    ];

    heatLayer = window.L.heatLayer(heatData, {
      radius: 25,
      blur: 15,
      maxZoom: 16,
      gradient: {
        0.4: '#38ef7d',
        0.7: '#f1c40f',
        1.0: '#dc3545'
      }
    }).addTo(mapInstance);
  }

  mapInstance.setView([lat, lng], 14);
};

// مراقبة تغيير الإحداثيات عند النقر على طلبات طوارئ مختلفة
watch(() => [props.latitude, props.longitude], ([newLat, newLng]) => {
  if (newLat && newLng && mapInstance) {
    updateMapContent(parseFloat(newLat), parseFloat(newLng));
  }
});

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
.fs-8 {
  font-size: 0.8rem;
}

.map-wrapper {
  border-color: #e2e8f0 !important;
  z-index: 1;
}

.map-loader {
  z-index: 10;
}

.dir-rtl {
  direction: rtl;
}

.dir-ltr {
  direction: ltr;
}

.shadow-2xs {
  box-shadow: 0 1px 2px rgba(220, 53, 69, 0.15);
}

.btn-danger {
  background-color: #dc3545;
  border-color: #dc3545;
}

.btn-outline-danger {
  color: #dc3545;
  border-color: #dc3545;
}

.btn-outline-danger:hover {
  background-color: #fee2e2;
  color: #dc3545;
}
</style>

<style>
/* تأثيرات الدبوس والتحذير المضيء على الخريطة الحرارية */
.emergency-marker-pulse {
  width: 28px;
  height: 28px;
  background: #ffffff;
  border-radius: 50%;
  border: 2px solid #dc3545;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
  animation: pulse-emergency 1.5s infinite;
}

@keyframes pulse-emergency {
  0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
  70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(220, 53, 69, 0); }
  100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}
</style>
