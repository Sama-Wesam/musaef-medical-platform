<template>
  <div class="row g-3 g-lg-4 align-items-stretch" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">

    <!-- قسم معلومات الجهة الطبية -->
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <h6 class="fw-bold text-dark mb-4 fs-6">{{ t('infoTitle') }}</h6>

        <div class="d-flex flex-column gap-3 fs-7">
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">🏢</span>
            <span class="text-muted ms-1">{{ t('hospitalName') }}:</span>
            <span class="fw-bold text-dark">{{ translateFacilityName(hospitalData.name) }}</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">📍</span>
            <span class="text-muted ms-1">{{ t('address') }}:</span>
            <span class="text-dark">{{ translateAddress(hospitalData.address) }}</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">⏰</span>
            <span class="text-muted ms-1">{{ t('workingHours') }}:</span>
            <span class="text-dark">{{ translateWorkingHours(hospitalData.working_hours) }}</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">📞</span>
            <span class="text-muted ms-1">{{ t('phone') }}:</span>
            <span class="text-dark" dir="ltr">{{ hospitalData.phone_number || 'N/A' }}</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">✉️</span>
            <span class="text-muted ms-1">{{ t('email') }}:</span>
            <span class="text-dark text-break" dir="ltr">{{ hospitalData.contact_email }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- قسم الخريطة التفاعلية والدوائر الحرارية -->
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden h-100 position-relative map-card-container">
        <!-- حاوية خريطة Leaflet -->
        <div id="hospital-profile-map" ref="mapContainer" class="profile-leaflet-map"></div>

        <!-- مؤشر تحميل الخريطة -->
        <div v-if="isLoading" class="map-loader position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75">
          <div class="spinner-border text-danger spinner-border-sm" role="status">
            <span class="visually-hidden">جاري تحميل الخريطة...</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
  hospitalData: {
    type: Object,
    required: true
  }
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');
const mapContainer = ref(null);
const isLoading = ref(true);

let mapInstance = null;
let markerInstance = null;
let heatLayer = null;

const dictionary = {
  ar: {
    infoTitle: 'معلومات الجهة الطبية',
    hospitalName: 'اسم المستشفى',
    address: 'العنوان',
    workingHours: 'ساعات العمل',
    phone: 'رقم الهاتف',
    email: 'البريد الإلكتروني'
  },
  en: {
    infoTitle: 'Medical Facility Information',
    hospitalName: 'Hospital Name',
    address: 'Address',
    workingHours: 'Working Hours',
    phone: 'Phone Number',
    email: 'Email'
  }
};

const facilityNameDict = {
  'جمعية بنك الدم المركزي': 'Central Blood Bank Society',
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex'
};

const addressDict = {
  'غزة - الرمال شارع الوحدة': 'Gaza - Rimal, Al-Wehda St',
  'غزة - الرمال': 'Gaza - Rimal'
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;
const translateFacilityName = (name) => currentLanguage.value === 'en' ? (facilityNameDict[name] || name) : name;
const translateAddress = (addr) => currentLanguage.value === 'en' ? (addressDict[addr] || addr) : (addr || 'غير محدد');
const translateWorkingHours = (hours) => {
  if (currentLanguage.value === 'en') {
    if (hours === '24 ساعة 7 أيام في الأسبوع') return '24 Hours 7 Days a Week';
  }
  return hours;
};

// تحميل سكربتات Leaflet ديناميكياً
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

// تهيئة الخريطة التفاعلية
const initMap = async () => {
  try {
    loadStyle('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
    await loadScript('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js');
    await loadScript('https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js').catch(() => {});

    if (!mapContainer.value || !window.L) return;

    const lat = parseFloat(props.hospitalData?.latitude) || 31.514;
    const lng = parseFloat(props.hospitalData?.longitude) || 34.448;

    mapInstance = window.L.map(mapContainer.value, {
      zoomControl: false
    }).setView([lat, lng], 13);

    window.L.control.zoom({ position: 'topleft' }).addTo(mapInstance);

    window.L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapInstance);

    updateMapElements(lat, lng);
    isLoading.value = false;
  } catch (err) {
    console.error('Failed to initialize Hospital Profile Map:', err);
    isLoading.value = false;
  }
};

// تحديث العلامات الجغرافية والطبقة الحرارية
const updateMapElements = (lat, lng) => {
  if (!mapInstance || !window.L) return;

  // 1. تحديث العلامة الجغرافية
  if (markerInstance) {
    mapInstance.removeLayer(markerInstance);
  }

  const customIcon = window.L.divIcon({
    className: 'custom-hospital-marker',
    html: `<div class="hospital-pin">🏢</div>`,
    iconSize: [32, 32],
    iconAnchor: [16, 16]
  });

  markerInstance = window.L.marker([lat, lng], { icon: customIcon }).addTo(mapInstance);

  const hospitalTitle = translateFacilityName(props.hospitalData.name);
  const hospitalAddr = translateAddress(props.hospitalData.address);

  markerInstance.bindPopup(`
    <div class="p-1 text-center ${currentLanguage.value === 'ar' ? 'dir-rtl' : 'dir-ltr'}">
      <strong class="d-block text-danger fw-bold fs-7">${hospitalTitle}</strong>
      <small class="text-muted d-block mt-1">${hospitalAddr}</small>
    </div>
  `).openPopup();

  // 2. تحديث الخريطة الحرارية Overlay حول موقع المستشفى
  if (heatLayer) {
    mapInstance.removeLayer(heatLayer);
  }

  if (window.L.heatLayer) {
    // توليد نقاط حول المستشفى لإعطاء تأثير الدائرة الحرارية الجذابة
    const heatPoints = [
      [lat, lng, 1.0],
      [lat + 0.002, lng + 0.002, 0.7],
      [lat - 0.002, lng - 0.002, 0.7],
      [lat + 0.003, lng - 0.001, 0.5],
      [lat - 0.001, lng + 0.003, 0.5]
    ];

    heatLayer = window.L.heatLayer(heatPoints, {
      radius: 35,
      blur: 25,
      maxZoom: 15,
      gradient: {
        0.3: '#38ef7d',
        0.6: '#f1c40f',
        1.0: '#e74c3c'
      }
    }).addTo(mapInstance);
  }

  mapInstance.setView([lat, lng], 13);
};

watch(() => [props.hospitalData.latitude, props.hospitalData.longitude], ([newLat, newLng]) => {
  if (newLat && newLng && mapInstance) {
    updateMapElements(parseFloat(newLat), parseFloat(newLng));
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
.fs-7 { font-size: 0.85rem; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }

.map-card-container {
  min-height: 280px;
  border: 1px solid #e2e8f0;
  z-index: 1;
}

.profile-leaflet-map {
  width: 100%;
  height: 100%;
  min-height: 280px;
}

.map-loader {
  z-index: 10;
}
</style>

<style>
/* تخصيص شكل الدبوس التفاعلي للمستشفى */
.hospital-pin {
  width: 32px;
  height: 32px;
  background: #ffffff;
  border-radius: 50%;
  border: 2px solid #dc3545;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  box-shadow: 0 4px 10px rgba(220, 53, 69, 0.35);
  animation: pulse-hospital 2s infinite;
}

@keyframes pulse-hospital {
  0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.5); }
  70% { transform: scale(1.08); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
  100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}
</style>
