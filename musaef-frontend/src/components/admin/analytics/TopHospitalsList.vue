<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-start d-flex flex-column justify-content-between position-relative" :dir="langStore.dir">
    <!-- نافذة إشعار مخصصة -->
    <transition name="fade">
      <div
        v-if="toast.show"
        class="toast-banner position-absolute top-0 start-0 end-0 p-2.5 rounded-top-4 text-center fs-9 fw-bold z-3 bg-danger text-white shadow-sm"
      >
        <span>{{ toast.message }}</span>
        <button type="button" class="btn-close btn-close-white ms-2 fs-9 align-middle" @click="toast.show = false"></button>
      </div>
    </transition>

    <div>
      <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
        <img :src="getIconUrl('Group 1000002306 (1).png')" alt="hospital icon" width="24" height="24" class="header-icon" />
        <h5 class="fw-bold text-dark mb-0 fs-6">{{ t('title') }}</h5>
      </div>

      <div class="d-flex flex-column gap-2.5 gap-md-3 fs-8">
        <div v-for="(h, i) in topHospitals" :key="h.name" class="d-flex align-items-center gap-2">
          <span class="rank-circle text-white fw-bold fs-9 rounded-circle d-flex align-items-center justify-content-center" :style="{ backgroundColor: h.color }">
            {{ i + 1 }}
          </span>
          <span class="text-dark fw-bold text-start text-truncate" style="min-width: 100px; max-width: 140px;">
            {{ getHospitalName(h.name) }}
          </span>
          <div class="progress flex-grow-1 bg-light rounded-pill" style="height: 8px;">
            <div class="progress-bar rounded-pill" :style="{ width: h.percent + '%', backgroundColor: h.color }"></div>
          </div>
          <span class="text-muted fs-9 fw-bold w-35px text-end flex-shrink-0">{{ h.percent }}%</span>
        </div>
      </div>
    </div>

    <!-- زر تفاعلي لعرض جميع المستشفيات -->
    <a href="#" @click.prevent="handleViewAllHospitals" class="text-danger text-decoration-none fs-8 fw-bold mt-3 mt-md-4 d-inline-block text-center cursor-pointer">
      {{ isLoading ? t('loading') : t('viewAll') }}
    </a>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import apiClient from '@/api/axios';
import { useLangStore } from '@/stores/langStore';

defineProps({
  topHospitals: {
    type: Array,
    required: true
  }
});

const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: {
    title: 'أكثر المستشفيات احتياجاً (Facility Recommendation AI)',
    viewAll: 'عرض جميع المستشفيات >',
    loading: 'جاري تحليل كفاءة المستشفيات...',
    successToast: '🏥 تم استدعاء تقرير كفاءة التوزيع الجغرافي واحتياجات كافة المستشفيات بنجاح!',
    fallbackToast: '🏥 قائمة المستشفيات الشاملة وتحليل النقص.'
  },
  en: {
    title: 'Hospitals in Highest Need (Facility Recommendation AI)',
    viewAll: 'View All Hospitals >',
    loading: 'Analyzing hospital efficiency...',
    successToast: '🏥 Hospital needs report retrieved successfully!',
    fallbackToast: '🏥 Comprehensive hospital list & gap analysis.'
  }
};

// قاموس لترجمة أسماء المستشفيات
const hospitalNames = {
  'مستشفى ناصر': { ar: 'مستشفى ناصر', en: 'Nasser Hospital' },
  'مستشفى القدس': { ar: 'مستشفى القدس', en: 'Al-Quds Hospital' },
  'مستشفى الأوروبي': { ar: 'مستشفى الأوروبي', en: 'European Hospital' },
  'مستشفى الشفاء': { ar: 'مستشفى الشفاء', en: 'Al-Shifa Hospital' },
  'مستشفى الأندونيسي': { ar: 'مستشفى الأندونيسي', en: 'Indonesian Hospital' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const getHospitalName = (name) => {
  if (hospitalNames[name]) {
    return hospitalNames[name][currentLanguage.value === 'en' ? 'en' : 'ar'];
  }
  return name;
};

const isLoading = ref(false);
const toast = ref({ show: false, message: '' });

const showNotification = (msg) => {
  toast.value = { show: true, message: msg };
  setTimeout(() => {
    toast.value.show = false;
  }, 5000);
};

const handleViewAllHospitals = async () => {
  isLoading.value = true;
  try {
    await apiClient.get('/admin/analytics/all-hospitals-performance');
    showNotification(t('successToast'));
  } catch (err) {
    showNotification(t('fallbackToast'));
  } finally {
    isLoading.value = false;
  }
};

const getIconUrl = (fileName) => {
  if (!fileName) return '';
  if (fileName.startsWith('http') || fileName.startsWith('data:')) return fileName;
  try {
    return new URL(`../../../assets/icons/${fileName}`, import.meta.url).href;
  } catch (e) {
    return '';
  }
};
</script>

<style scoped>
.fs-6 { font-size: 1.05rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.cursor-pointer { cursor: pointer; }

.rank-circle {
  width: 22px;
  height: 22px;
  min-width: 22px;
  flex-shrink: 0;
}

.header-icon {
  width: 24px;
  height: 24px;
}
@media (min-width: 768px) {
  .header-icon {
    width: 28px;
    height: 28px;
  }
}

.w-35px {
  width: 35px;
}

/* تأثير الانتقال للتنبيه */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
