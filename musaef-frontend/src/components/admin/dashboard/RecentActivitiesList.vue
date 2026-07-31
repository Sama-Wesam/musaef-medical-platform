<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-end d-flex flex-column justify-content-between dir-rtl" dir="rtl">
    <div>
      <h6 class="fw-bold text-dark mb-3 mb-md-4 fs-7">النشاطات الأخيرة</h6>

      <div class="d-flex flex-column gap-2 gap-md-3 mb-3">
        <!-- طلب طارئ جديد -->
        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light-subtle flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
            <img :src="getIconUrl('Frame 2147225971.png')" alt="activity 1" width="32" height="32" class="flex-shrink-0" />
            <div class="min-w-0">
              <span class="fw-bold text-danger fs-8 d-block mb-0.5 text-truncate">طلب طارئ جديد لفصيلة O+</span>
              <small class="text-muted fs-9 text-truncate d-block">مستشفى شهداء الأقصى - دير البلح</small>
            </div>
          </div>
          <small class="text-muted fs-9 text-nowrap ms-auto ms-sm-0">منذ دقيقة</small>
        </div>

        <!-- تم اعتماد مستشفى جديد -->
        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light-subtle flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
            <img :src="getIconUrl('Frame 2147225971 (1).png')" alt="activity 2" width="32" height="32" class="flex-shrink-0" />
            <div class="min-w-0">
              <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">تم اعتماد مستشفى جديد</span>
              <small class="text-muted fs-9 text-truncate d-block">مستشفى الأمريكي - غزة</small>
            </div>
          </div>
          <small class="text-muted fs-9 text-nowrap ms-auto ms-sm-0">منذ 15 دقيقة</small>
        </div>

        <!-- تبرع جديد لفصيلة A+ -->
        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light-subtle flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
            <img :src="getIconUrl('Frame 2147225971 (2).png')" alt="activity 3" width="32" height="32" class="flex-shrink-0" />
            <div class="min-w-0">
              <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">تبرع جديد لفصيلة A+</span>
              <small class="text-muted fs-9 text-truncate d-block">المتبرع : أحمد محمد</small>
            </div>
          </div>
          <small class="text-muted fs-9 text-nowrap ms-auto ms-sm-0">منذ 32 دقيقة</small>
        </div>

        <!-- تم تلبية طلب طارئ -->
        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light-subtle flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
            <img :src="getIconUrl('Frame 2147225971 (3).png')" alt="activity 4" width="32" height="32" class="flex-shrink-0" />
            <div class="min-w-0">
              <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">تم تلبية طلب طارئ</span>
              <small class="text-muted fs-9 text-truncate d-block">رقم الطلب #8921</small>
            </div>
          </div>
          <small class="text-muted fs-9 text-nowrap ms-auto ms-sm-0">منذ 45 دقيقة</small>
        </div>
      </div>
    </div>

    <!-- زر تفاعلي لعرض جميع النشاطات -->
    <a href="#" @click.prevent="handleViewAllActivities" class="text-danger text-decoration-none fs-8 fw-bold mt-2 d-inline-block text-end cursor-pointer">
      عرض جميع النشاطات &lt;
    </a>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

defineProps({
  activities: {
    type: Array,
    default: () => []
  }
});

const router = useRouter();

const getIconUrl = (fileName) => {
  if (!fileName) return '';
  if (fileName.startsWith('http') || fileName.startsWith('data:')) return fileName;
  try {
    return new URL(`../../../assets/icons/${fileName}`, import.meta.url).href;
  } catch (e) {
    return '';
  }
};

const handleViewAllActivities = () => {
  router.push('/admin/accounts');
};
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.cursor-pointer { cursor: pointer; }

.bg-light-subtle {
  background-color: #f9fafb !important;
}
.dir-rtl { direction: rtl; }
</style>
