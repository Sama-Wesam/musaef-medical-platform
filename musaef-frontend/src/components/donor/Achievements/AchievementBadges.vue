<template>
  <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm">
    <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4">
      <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">
        {{ currentLanguage === 'en' ? 'Achievement Badges' : 'شارات الإنجاز' }}
      </h5>
      <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 fs-9 fw-bold">
        {{ computedBadges.length }} {{ currentLanguage === 'en' ? 'Badges' : 'شارات' }}
      </span>
    </div>

    <div class="row g-2 g-md-3">
      <div
        v-for="badge in computedBadges"
        :key="badge.id"
        class="col-6 col-sm-3 text-center"
      >
        <div class="p-3 border rounded-4 bg-light-subtle h-100 d-flex flex-column align-items-center justify-content-between shadow-2xs hover-badge-card">
          <div class="badge-icon-wrapper mb-2">
            <img
              :src="getBadgeImageUrl(badge.icon_filename || badge.image)"
              :alt="badge.title"
              class="badge-img img-fluid"
              @error="handleBadgeFallback"
            />
          </div>
          <div>
            <strong class="d-block text-dark fs-8 fw-bold mb-1">{{ badge.title }}</strong>
            <small class="text-muted fs-10 d-block">{{ badge.date }}</small>
          </div>
        </div>
      </div>

      <div v-if="!computedBadges.length" class="col-12 text-center py-4 text-muted fs-8">
        {{ currentLanguage === 'en' ? 'No badges earned yet.' : 'لا توجد شارات محققة حتى الآن.' }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

import badge1 from '@/assets/icons/badge-1.png';
import badge5 from '@/assets/icons/badge-5.png';
import badge10 from '@/assets/icons/badge-10.png';
import badgeHero from '@/assets/icons/badge-hero.png';

const props = defineProps({
  badges: {
    type: Array,
    default: () => []
  },
  donationsCount: {
    type: Number,
    default: 0
  }
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

// خريطة لربط أسماء الملفات بالصور المستوردة لضمان عمل Vite بدقة
const badgeMap = {
  'badge-1.png': badge1,
  'badge-5.png': badge5,
  'badge-10.png': badge10,
  'badge-hero.png': badgeHero
};

const getBadgeImageUrl = (fileName) => {
  if (!fileName) return badge1;
  if (fileName.startsWith('http') || fileName.startsWith('data:')) return fileName;

  // استخراج اسم الملف وإزالة أي مسارات محليّة من Windows أو Linux
  const cleanName = fileName.split(/[\\/]/).pop();

  if (badgeMap[cleanName]) {
    return badgeMap[cleanName];
  }

  try {
    return new URL(`../../../assets/icons/${cleanName}`, import.meta.url).href;
  } catch (e) {
    return badge1;
  }
};

const handleBadgeFallback = (e) => {
  if (e?.target) {
    e.target.src = badge1;
  }
};

const computedBadges = computed(() => {
  if (props.badges && props.badges.length > 0) {
    return props.badges.map((b, idx) => {
      let rawPath = b.image || b.icon_filename || b.icon || '';
      let cleanName = rawPath.split(/[\\/]/).pop();

      // التحقق من اسم الملف أولاً، أو تحليله بناءً على العنوان إذا كان الاسم مجهولاً
      if (!cleanName || !badgeMap[cleanName]) {
        const title = b.title || '';
        if (title.includes('أول') || title.toLowerCase().includes('first') || title.includes('1')) {
          cleanName = 'badge-1.png';
        } else if (title.includes('5')) {
          cleanName = 'badge-5.png';
        } else if (title.includes('10')) {
          cleanName = 'badge-10.png';
        } else if (title.includes('منقذ') || title.toLowerCase().includes('saver') || title.toLowerCase().includes('hero')) {
          cleanName = 'badge-hero.png';
        } else {
          cleanName = 'badge-1.png';
        }
      }

      return {
        id: b.id || `badge-${idx}`,
        title: b.title || (currentLanguage.value === 'en' ? 'Badge' : 'وسام إنجاز'),
        date: b.date || b.created_at || (currentLanguage.value === 'en' ? 'Achieved' : 'مُحقق'),
        icon_filename: cleanName
      };
    });
  }

  // إنشاء الشارات ديناميكياً حسب عدد التبرعات إذا لم تكن ممررة من API
  const list = [];
  const count = props.donationsCount || 0;

  if (count >= 1) {
    list.push({
      id: 'b1',
      title: currentLanguage.value === 'en' ? 'First Donation' : 'أول تبرع',
      date: currentLanguage.value === 'en' ? 'Bronze Badge' : 'الوسام البرونزي',
      icon_filename: 'badge-1.png'
    });
  }
  if (count >= 5) {
    list.push({
      id: 'b5',
      title: currentLanguage.value === 'en' ? '5 Donations' : '5 عمليات تبرع',
      date: currentLanguage.value === 'en' ? 'Silver Badge' : 'الوسام الفضي',
      icon_filename: 'badge-5.png'
    });
  }
  if (count >= 10) {
    list.push({
      id: 'b10',
      title: currentLanguage.value === 'en' ? '10 Donations' : '10 عمليات تبرع',
      date: currentLanguage.value === 'en' ? 'Gold Badge' : 'الوسام الذهبي',
      icon_filename: 'badge-10.png'
    });
  }
  if (count > 10) {
    list.push({
      id: 'bhero',
      title: currentLanguage.value === 'en' ? 'Life Saver' : 'منقذ حياة',
      date: currentLanguage.value === 'en' ? 'Hero Badge' : 'الوسام الأزرق',
      icon_filename: 'badge-hero.png'
    });
  }

  return list;
});
</script>

<style scoped>
.badge-img {
  width: 55px;
  height: 55px;
  object-fit: contain;
  transition: transform 0.2s ease;
}

.hover-badge-card:hover .badge-img {
  transform: scale(1.1);
}

.hover-badge-card {
  transition: all 0.2s ease;
}

.hover-badge-card:hover {
  background-color: #ffffff !important;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
}

.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.fs-10 { font-size: 0.65rem; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-light-subtle { background-color: #f8fafc; }
</style>
