<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 d-flex flex-column justify-content-between" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div>
      <h6 class="fw-bold text-dark mb-3 mb-md-4 fs-7">{{ t('recentTitle') }}</h6>

      <div class="d-flex flex-column gap-2 gap-md-3 mb-3">
        <!-- 1. طلب طارئ جديد -->
        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light-subtle flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
            <img :src="getIconUrl('Frame 2147225971.png')" alt="activity 1" width="32" height="32" class="flex-shrink-0" />
            <div class="min-w-0">
              <span class="fw-bold text-danger fs-8 d-block mb-0.5 text-truncate">{{ t('act1Title') }}</span>
              <small class="text-muted fs-9 text-truncate d-block">{{ t('act1Desc') }}</small>
            </div>
          </div>
          <small class="text-muted fs-9 text-nowrap ms-auto ms-sm-0">{{ t('time1Min') }}</small>
        </div>

        <!-- 2. تم اعتماد مستشفى جديد -->
        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light-subtle flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
            <img :src="getIconUrl('Frame 2147225971 (1).png')" alt="activity 2" width="32" height="32" class="flex-shrink-0" />
            <div class="min-w-0">
              <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('act2Title') }}</span>
              <small class="text-muted fs-9 text-truncate d-block">{{ t('act2Desc') }}</small>
            </div>
          </div>
          <small class="text-muted fs-9 text-nowrap ms-auto ms-sm-0">{{ t('time15Mins') }}</small>
        </div>

        <!-- 3. تبرع جديد -->
        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light-subtle flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
            <img :src="getIconUrl('Frame 2147225971 (2).png')" alt="activity 3" width="32" height="32" class="flex-shrink-0" />
            <div class="min-w-0">
              <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('act3Title') }}</span>
              <small class="text-muted fs-9 text-truncate d-block">{{ t('act3Desc') }}</small>
            </div>
          </div>
          <small class="text-muted fs-9 text-nowrap ms-auto ms-sm-0">{{ t('time32Mins') }}</small>
        </div>

        <!-- 4. تم تلبية طلب طارئ -->
        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light-subtle flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
            <img :src="getIconUrl('Frame 2147225971 (3).png')" alt="activity 4" width="32" height="32" class="flex-shrink-0" />
            <div class="min-w-0">
              <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('act4Title') }}</span>
              <small class="text-muted fs-9 text-truncate d-block">{{ t('act4Desc') }}</small>
            </div>
          </div>
          <small class="text-muted fs-9 text-nowrap ms-auto ms-sm-0">{{ t('time45Mins') }}</small>
        </div>
      </div>
    </div>

    <a href="#" @click.prevent="handleViewAllActivities" class="text-danger text-decoration-none fs-8 fw-bold mt-2 d-inline-block cursor-pointer" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
      {{ t('viewAllBtn') }}
    </a>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    recentTitle: 'النشاطات الأخيرة',
    act1Title: 'طلب طارئ جديد لفصيلة O+', act1Desc: 'مستشفى شهداء الأقصى - دير البلح',
    act2Title: 'تم اعتماد مستشفى جديد', act2Desc: 'مستشفى الأمريكي - غزة',
    act3Title: 'تبرع جديد لفصيلة A+', act3Desc: 'المتبرع : أحمد محمد',
    act4Title: 'تم تلبية طلب طارئ', act4Desc: 'رقم الطلب #8921',
    time1Min: 'منذ دقيقة', time15Mins: 'منذ 15 دقيقة', time32Mins: 'منذ 32 دقيقة', time45Mins: 'منذ 45 دقيقة',
    viewAllBtn: 'عرض جميع النشاطات <'
  },
  en: {
    recentTitle: 'Recent Activities',
    act1Title: 'New Emergency Request for O+', act1Desc: 'Al-Aqsa Martyrs Hospital - Deir Al-Balah',
    act2Title: 'New Hospital Approved', act2Desc: 'American Hospital - Gaza',
    act3Title: 'New Donation for A+', act3Desc: 'Donor: Ahmed Mohamed',
    act4Title: 'Emergency Request Fulfilled', act4Desc: 'Request Code #8921',
    time1Min: '1 min ago', time15Mins: '15 mins ago', time32Mins: '32 mins ago', time45Mins: '45 mins ago',
    viewAllBtn: 'View All Activities >'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

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
.dir-ltr { direction: ltr; }
</style>
