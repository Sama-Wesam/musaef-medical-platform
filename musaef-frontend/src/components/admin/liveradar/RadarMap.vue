<template>
  <div
    class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative h-100 radar-map-card"
  >
    <div id="leaflet-map" ref="mapContainer" class="map-container"></div>

    <div
      v-if="isLoading"
      class="map-loader position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75"
    >
      <div class="spinner-border text-danger" role="status">
        <span class="visually-hidden">Loading map...</span>
      </div>
    </div>

    <SeverityLegend />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from "vue";
import { useLangStore } from "@/stores/langStore";
import SeverityLegend from "@/components/admin/liveradar/SeverityLegend.vue";

const props = defineProps({
  heatData: {
    type: Array,
    default: () => [],
  },
  hospitals: {
    type: Array,
    default: () => [],
  },
});

const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const mapContainer = ref(null);
const isLoading = ref(true);

let mapInstance = null;
let heatLayer = null;
let markersGroup = null;

const hospitalNameTranslations = {
  "مستشفى العودة – شمال غزة / جباليا": "Al-Awda Hospital – North Gaza / Jabalia",
  "مستشفى شهداء الأقصى – دير البلح": "Al-Aqsa Martyrs Hospital – Deir al-Balah",
  "مستشفى أصدقاء المريض الخيري – مدينة غزة":
    "Patient's Friends Benevolent Hospital – Gaza City",
  "مستشفى الشفاء الطبي": "Al-Shifa Medical Hospital",
  "مستشفى ناصر الطبي": "Nasser Medical Hospital",
  "مستشفى القدس – مدينة غزة": "Al-Quds Hospital – Gaza City",
  "المستشفى الإندونيسي – بيت لاهيا": "Indonesian Hospital – Beit Lahia",
};

const locationTranslations = {
  "شمال غزة - تل الزعتر / جباليا": "North Gaza - Tal az-Zaatar / Jabalia",
  "المحافظة الوسطى - دير البلح": "Middle Area - Deir al-Balah",
  "مدينة غزة - حي الرمال - شارع الشهداء": "Gaza City - Rimal - Al-Shohada St.",
  "مدينة غزة - تل الهوا": "Gaza City - Tel al-Hawa",
  "شمال غزة - بيت لاهيا": "North Gaza - Beit Lahia",
};

const getTranslatedHospitalName = (h) => {
  if (currentLanguage.value === "en") {
    if (h.name_en) return h.name_en;
    if (h.translatedName) return h.translatedName;
    const raw = (h.name || "").trim();
    return hospitalNameTranslations[raw] || raw;
  }
  return h.name;
};

const getTranslatedLocation = (h) => {
  if (currentLanguage.value === "en") {
    if (h.location_en) return h.location_en;
    if (h.translatedLocation) return h.translatedLocation;
    const raw = (h.location || "").trim();
    return locationTranslations[raw] || raw;
  }
  return h.location || "";
};

const loadScript = (src) => {
  return new Promise((resolve, reject) => {
    if (document.querySelector(`script[src="${src}"]`)) {
      resolve();
      return;
    }
    const script = document.createElement("script");
    script.src = src;
    script.onload = resolve;
    script.onerror = reject;
    document.head.appendChild(script);
  });
};

const loadStyle = (href) => {
  if (!document.querySelector(`link[href="${href}"]`)) {
    const link = document.createElement("link");
    link.rel = "stylesheet";
    link.href = href;
    document.head.appendChild(link);
  }
};

const initMap = async () => {
  try {
    loadStyle("https://unpkg.com/leaflet@1.9.4/dist/leaflet.css");
    await loadScript("https://unpkg.com/leaflet@1.9.4/dist/leaflet.js");
    await loadScript(
      "https://cdn.jsdelivr.net/npm/heatmap.js@2.0.5/build/heatmap.min.js"
    ).catch(() => {});

    try {
      await loadScript(
        "https://cdn.jsdelivr.net/npm/heatmap.js@2.0.5/plugins/leaflet-heatmap/leaflet-heatmap.js"
      );
    } catch {
      await loadScript("https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js");
    }

    if (!mapContainer.value || !window.L) return;

    mapInstance = window.L.map(mapContainer.value, {
      zoomControl: false,
    }).setView([31.4, 34.38], 11);

    window.L.control.zoom({ position: "topleft" }).addTo(mapInstance);

    window.L.tileLayer(
      "https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png",
      {
        maxZoom: 19,
      }
    ).addTo(mapInstance);

    markersGroup = window.L.layerGroup().addTo(mapInstance);

    renderHeatMap();
    renderMarkers();
    isLoading.value = false;
  } catch (err) {
    console.error("Failed to initialize Leaflet Map:", err);
    isLoading.value = false;
  }
};

const renderHeatMap = () => {
  if (!mapInstance || !window.L) return;

  if (heatLayer) {
    mapInstance.removeLayer(heatLayer);
  }

  const points =
    props.heatData && props.heatData.length > 0
      ? props.heatData.map((p) => [p.lat, p.lng, p.intensity || 0.8])
      : [];

  if (window.HeatmapOverlay && points.length > 0) {
    const heatDataFormatted = {
      max: 1,
      data: points.map((p) => ({ lat: p[0], lng: p[1], value: p[2] })),
    };

    heatLayer = new window.HeatmapOverlay({
      radius: 0.035,
      maxOpacity: 0.8,
      scaleRadius: true,
      useLocalExtrema: true,
      latField: "lat",
      lngField: "lng",
      valueField: "value",
    });

    mapInstance.addLayer(heatLayer);
    heatLayer.setData(heatDataFormatted);
  } else if (window.L.heatLayer && points.length > 0) {
    heatLayer = window.L.heatLayer(points, {
      radius: 28,
      blur: 20,
      maxZoom: 14,
      gradient: { 0.2: "#38ef7d", 0.5: "#f1c40f", 0.8: "#e67e22", 1.0: "#e74c3c" },
    }).addTo(mapInstance);
  }
};

const renderMarkers = () => {
  if (!mapInstance || !markersGroup || !window.L) return;

  markersGroup.clearLayers();

  props.hospitals.forEach((h) => {
    if (h.lat && h.lng) {
      const customIcon = window.L.divIcon({
        className: "custom-map-pin",
        html: `<div class="pulse-pin ${h.urgency || "critical"}"></div>`,
        iconSize: [24, 24],
        iconAnchor: [12, 12],
      });

      const marker = window.L.marker([h.lat, h.lng], { icon: customIcon });

      const name = getTranslatedHospitalName(h);
      const loc = getTranslatedLocation(h);

      const popupContent = `
        <div class="p-2 text-center" dir="${langStore.dir}">
          <strong class="d-block text-danger fw-bold fs-6">${name}</strong>
          <small class="text-muted d-block mt-1">${loc}</small>
        </div>
      `;

      marker.bindPopup(popupContent);
      markersGroup.addLayer(marker);
    }
  });
};

watch(
  () => props.heatData,
  () => {
    renderHeatMap();
  },
  { deep: true }
);
watch(
  () => props.hospitals,
  () => {
    renderMarkers();
  },
  { deep: true }
);
watch(
  () => currentLanguage.value,
  () => {
    renderMarkers();
  }
);

onMounted(() => {
  initMap();
});
onUnmounted(() => {
  if (mapInstance) mapInstance.remove();
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
  .radar-map-card,
  .map-container {
    min-height: 480px;
  }
}
@media (min-width: 992px) {
  .radar-map-card,
  .map-container {
    min-height: 560px;
  }
}
</style>

<style>
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
