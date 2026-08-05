<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-start d-flex flex-column justify-content-between" :dir="langStore.dir">
    <div>
      <!-- عنوان القائمة -->
      <h6 class="fw-bold text-dark mb-3 fs-7">{{ t('title') }}</h6>

      <!-- أزرار الفلترة بحسب مستوى الخطورة -->
      <EmergencyCasesFilter :filter="filter" @update:filter="$emit('update:filter', $event)" />

      <!-- بطاقات المستشفيات المباشرة -->
      <div class="d-flex flex-column gap-2 gap-md-3 mb-3">
        <CriticalHospitalCard
          v-for="hospital in hospitals"
          :key="hospital.id"
          :hospital="hospital"
        />

        <div v-if="hospitals.length === 0" class="text-center text-muted py-4 fs-8">
          {{ t('noCases') }}
        </div>
      </div>
    </div>

    <!-- زر عرض المزيد الفعّال -->
    <button
      type="button"
      class="btn btn-outline-secondary w-100 rounded-3 py-2 fs-8 text-dark bg-white border-secondary-subtle mt-2 fw-bold d-flex align-items-center justify-content-center gap-2"
      :disabled="isLoadingMore"
      @click="handleLoadMore"
    >
      <span v-if="isLoadingMore" class="spinner-border spinner-border-sm"></span>
      <span>{{ isLoadingMore ? t('loading') : t('loadMore') }}</span>
    </button>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useLangStore } from '@/stores/langStore';
import EmergencyCasesFilter from '@/components/admin/liveradar/EmergencyCasesFilter.vue';
import CriticalHospitalCard from '@/components/admin/liveradar/CriticalHospitalCard.vue';

defineProps({
  filter: {
    type: String,
    default: 'all'
  },
  hospitals: {
    type: Array,
    required: true
  }
});

const emit = defineEmits(['update:filter', 'refresh']);

const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);

const dictionary = {
  ar: {
    title: 'الحالات الحرجة المباشرة',
    noCases: 'لا توجد حالات طارئة نشطة حالياً.',
    loadMore: 'عرض المزيد / تحديث البيانات 🔄',
    loading: 'جاري جلب الحالات...'
  },
  en: {
    title: 'Live Critical Cases',
    noCases: 'No active emergency cases currently.',
    loadMore: 'View More / Refresh Data 🔄',
    loading: 'Fetching cases...'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const isLoadingMore = ref(false);

const handleLoadMore = async () => {
  isLoadingMore.value = true;
  emit('refresh');
  setTimeout(() => {
    isLoadingMore.value = false;
  }, 600);
};
</script>

<style scoped>
.fs-7 { font-size: 0.88rem; }
.fs-8 { font-size: 0.8rem; }
</style>
