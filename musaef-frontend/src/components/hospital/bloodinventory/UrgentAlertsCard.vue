<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('title') }}</h6>
      <span class="badge bg-danger text-white rounded-pill px-2 py-1 fs-9">{{ t('aiBadge') }}</span>
    </div>
    <div class="d-flex flex-column gap-2 mb-3">
      <template v-if="combinedAlerts && combinedAlerts.length > 0">
        <div
          v-for="(alertItem, index) in combinedAlerts"
          :key="index"
          class="p-2 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8 border-start border-4"
          :class="alertItem.status === 'critical' ? 'border-danger' : 'border-warning'"
        >
          <div class="d-flex align-items-center gap-2">
            <span class="fw-bold text-dark" dir="ltr">{{ alertItem.blood_type }}</span>
            <span :class="['badge rounded-pill px-2 py-1 fs-9', alertItem.status === 'critical' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning-emphasis']">
              {{ alertItem.status === 'critical' ? t('veryLowCritical') : t('low') }}
            </span>
          </div>
          <small class="text-muted fs-9">{{ alertItem.available_text }}</small>
        </div>
      </template>

      <template v-else>
        <div class="text-center py-3 text-muted fs-8">
          {{ currentLanguage === 'en' ? 'All blood types are within safe levels.' : 'جميع الفصائل ضمن المستويات الآمنة حالياً.' }}
        </div>
      </template>
    </div>
    <button class="btn btn-light bg-light text-secondary btn-sm w-100 rounded-pill fs-8 fw-bold" @click="handleEmergencyAction">
      {{ t('actionBtn') }}
    </button>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  alerts: {
    type: Array,
    default: () => []
  }
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');
const emergencyAlerts = ref([]);

const dictionary = {
  ar: {
    title: 'تنبيهات فورية (Blood Demand Forecast AI)',
    aiBadge: 'ذكاء اصطناعي',
    veryLowCritical: 'منخفض جداً (حرج)',
    low: 'منخفض',
    actionBtn: 'إطلاق نداء طارئ أو التواصل مع بنوك مجاورة'
  },
  en: {
    title: 'Instant Alerts (Blood Demand Forecast AI)',
    aiBadge: 'AI Powered',
    veryLowCritical: 'Very Low (Critical)',
    low: 'Low',
    actionBtn: 'Dispatch Emergency Call or Contact Nearby Banks'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const loadLocalEmergencyAlerts = () => {
  try {
    const saved = localStorage.getItem('musaef_emergency_requests');
    if (saved) {
      const parsed = JSON.parse(saved);
      if (Array.isArray(parsed)) {
        emergencyAlerts.value = parsed
          .filter(req => req.status !== 'completed' && req.status !== 'مكتملة')
          .map(req => ({
            blood_type: req.bloodType || req.blood_type || 'O-',
            status: 'critical',
            available_text: currentLanguage.value === 'en'
              ? `Emergency call (${req.units || 1} units needed)`
              : `نداء طارئ نشط (مطلوب ${req.units || 1} وحدات)`
          }));
      }
    }
  } catch (e) {
    console.error('Error loading emergency alerts:', e);
  }
};

const combinedAlerts = computed(() => {
  return [...emergencyAlerts.value, ...(props.alerts || [])];
});

const handleStorageChange = (e) => {
  if (e.key === 'musaef_emergency_requests' || !e.key) {
    loadLocalEmergencyAlerts();
  }
};

onMounted(() => {
  loadLocalEmergencyAlerts();
  window.addEventListener('musaef_emergency_updated', loadLocalEmergencyAlerts);
  window.addEventListener('storage', handleStorageChange);
});

onUnmounted(() => {
  window.removeEventListener('musaef_emergency_updated', loadLocalEmergencyAlerts);
  window.removeEventListener('storage', handleStorageChange);
});

const handleEmergencyAction = () => {
  const isEn = currentLanguage.value === 'en';
  const action = confirm(isEn
    ? "Would you like to dispatch an instant emergency call to donors via Smart Matching AI, or contact nearby blood banks?\n\nClick 'OK' for Emergency Call, or 'Cancel' to contact nearby banks."
    : "هل تريد إطلاق نداء طارئ فوري للمتبرعين عبر نظام Smart Matching AI، أم التواصل مع بنوك الدم المجاورة؟\n\nاضغط 'موافق' لإطلاق النداء الطارئ، أو 'إلغاء' للتواصل مع بنوك الدم المجاورة.");
  if (action) {
    alert(isEn ? "🚨 Emergency call dispatched successfully for critical blood types!" : "🚨 تم إطلاق النداء الطارئ بنجاح للفصائل الحرجة وتم تنبيه المتبرعين القريبين!");
  } else {
    alert(isEn ? "📞 Opening communication channel with regional blood banks..." : "📞 جاري فتح نافذة الاتصال والتنسيق مع بنوك الدم الإقليمية المجاورة...");
  }
};
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.text-warning-emphasis { color: #b45309 !important; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
