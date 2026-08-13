<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white flex-grow-1" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <h6 class="fw-bold text-dark mb-3 fs-7">{{ t('title') }}</h6>
    <div class="d-flex flex-column gap-2 mb-3 fs-8">
      <template v-if="combinedDonations && combinedDonations.length > 0">
        <div
          v-for="(item, index) in combinedDonations"
          :key="item.id || index"
          class="d-flex align-items-center justify-content-between p-1"
          :class="index < combinedDonations.length - 1 ? 'border-bottom-dashed' : ''"
        >
          <div class="d-flex align-items-center gap-2 min-w-0">
            <span class="text-truncate">{{ item.donor_name || item.name }}</span>
            <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9 flex-shrink-0" dir="ltr">
              {{ item.blood_type }}
            </span>
          </div>
          <small class="text-muted fs-9 flex-shrink-0">{{ item.formatted_time || item.created_at }}</small>
        </div>
      </template>

      <template v-else>
        <div class="text-center py-3 text-muted fs-8">
          {{ currentLanguage === 'en' ? 'No recent donation activity' : 'لا توجد عمليات تبرع مسجلة مؤخراً' }}
        </div>
      </template>
    </div>
    <button class="btn btn-light bg-light text-secondary btn-sm w-100 rounded-pill fs-8 mt-auto fw-bold" @click="handleViewAllOperations">
      {{ t('viewAllBtn') }}
    </button>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  donations: {
    type: Array,
    default: () => []
  }
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');
const localDonations = ref([]);

const dictionary = {
  ar: {
    title: 'عمليات التبرع الأخيرة',
    viewAllBtn: 'عرض جميع العمليات'
  },
  en: {
    title: 'Recent Donation Operations',
    viewAllBtn: 'View All Operations'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const loadLocalDonations = () => {
  try {
    const saved = localStorage.getItem('musaef_recent_donations');
    if (saved) {
      localDonations.value = JSON.parse(saved);
    }
  } catch (e) {
    console.error('Error loading local donations:', e);
  }
};

const combinedDonations = computed(() => {
  const merged = [...localDonations.value, ...(props.donations || [])];
  const uniqueMap = new Map();

  merged.forEach(item => {
    const name = item.donor_name || item.name;
    const key = `${name}_${item.blood_type}`;
    if (!uniqueMap.has(key)) {
      uniqueMap.set(key, item);
    }
  });

  return Array.from(uniqueMap.values());
});

const handleStorageChange = (e) => {
  if (e.key === 'musaef_recent_donations' || !e.key) {
    loadLocalDonations();
  }
};

onMounted(() => {
  loadLocalDonations();
  window.addEventListener('musaef_responders_updated', loadLocalDonations);
  window.addEventListener('storage', handleStorageChange);
});

onUnmounted(() => {
  window.removeEventListener('musaef_responders_updated', loadLocalDonations);
  window.removeEventListener('storage', handleStorageChange);
});

const handleViewAllOperations = () => {
  alert(currentLanguage.value === 'en'
    ? "📋 Opening complete donation operations log archive..."
    : "📋 جاري فتح سجل عمليات التبرع بالكامل للاطلاع على الأرشيف التفصيلي...");
};
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.border-bottom-dashed { border-bottom: 1px dashed #e5e7eb; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
