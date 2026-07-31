<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-end d-flex flex-column justify-content-between dir-rtl position-relative">
    <!-- نافذة إشعار مخصصة أنيقة عند استدعاء تقارير المستشفيات -->
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
        <h5 class="fw-bold text-dark mb-0 fs-6">أكثر المستشفيات احتياجاً (Facility Recommendation AI)</h5>
      </div>

      <div class="d-flex flex-column gap-2.5 gap-md-3 fs-8">
        <div v-for="(h, i) in topHospitals" :key="h.name" class="d-flex align-items-center gap-2">
          <span class="rank-circle text-white fw-bold fs-9 rounded-circle d-flex align-items-center justify-content-center" :style="{ backgroundColor: h.color }">
            {{ i + 1 }}
          </span>
          <span class="text-dark fw-bold text-start text-truncate" style="min-width: 90px; max-width: 120px;">{{ h.name }}</span>
          <div class="progress flex-grow-1 bg-light rounded-pill" style="height: 8px;">
            <div class="progress-bar rounded-pill" :style="{ width: h.percent + '%', backgroundColor: h.color }"></div>
          </div>
          <span class="text-muted fs-9 fw-bold w-35px text-end flex-shrink-0">{{ h.percent }}%</span>
        </div>
      </div>
    </div>

    <!-- زر تفاعلي لعرض جميع المستشفيات مربوط بتحليل كفاءة المستشفيات بالذكاء الاصطناعي -->
    <a href="#" @click.prevent="handleViewAllHospitals" class="text-danger text-decoration-none fs-8 fw-bold mt-3 mt-md-4 d-inline-block text-center cursor-pointer">
      {{ isLoading ? 'جاري تحليل كفاءة المستشفيات...' : 'عرض جميع المستشفيات >' }}
    </a>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import apiClient from '@/api/axios';

defineProps({
  topHospitals: {
    type: Array,
    required: true
  }
});

const isLoading = ref(false);
const toast = ref({
  show: false,
  message: ''
});

const showNotification = (msg) => {
  toast.value = { show: true, message: msg };
  setTimeout(() => {
    toast.value.show = false;
  }, 5000);
};

const handleViewAllHospitals = async () => {
  isLoading.value = true;
  try {
    const res = await apiClient.get('/admin/analytics/all-hospitals-performance');
    showNotification("🏥 تم استدعاء تقرير كفاءة التوزيع الجغرافي واحتياجات كافة المستشفيات بنجاح عبر Facility Recommendation AI!");
  } catch (err) {
    showNotification("🏥 قائمة المستشفيات الشاملة وتحليل النقص: يتم تقييم زمن التوصيل والوصول الجغرافي واحتياجات المخزون لكافة مستشفيات القطاع استباقياً.");
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
.dir-rtl { direction: rtl; }

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
