<template>
  <div class="empty-state text-center p-5 rounded-4 bg-white shadow-sm my-3 dir-rtl">
    <!-- الصورة التوضيحية مع معالجة الصورة في حال عدم وجودها -->
    <img
      :src="imageUrl"
      :alt="title"
      class="mb-3 img-fluid empty-state-img"
      @error="handleImgFallback"
    />

    <!-- العنوان الرئيسي -->
    <h5 class="text-dark fw-bold mb-2">{{ title }}</h5>

    <!-- الوصف الفرعي -->
    <p class="text-muted fs-8 mb-3 max-w-400 mx-auto">{{ description }}</p>

    <!-- زر إجراء تفاعلي اختياري (مثل: تصفح الحالات / إعادة المحاولة) -->
    <div v-if="actionText" class="mt-2">
      <button @click="$emit('action-clicked')" class="btn btn-outline-danger btn-sm rounded-pill px-4 shadow-sm fw-bold">
        {{ actionText }}
      </button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
    default: 'لا توجد حالات طارئة قريبة حالياً'
  },
  description: {
    type: String,
    default: 'شكراً لكونك مستعداً دائماً لإنقاذ الأرواح ومساعدة المحتاجين ❤️'
  },
  imageUrl: {
    type: String,
    default: 'https://cdn-icons-png.flaticon.com/512/7486/7486744.png'
  },
  actionText: {
    type: String,
    default: ''
  }
});

defineEmits(['action-clicked']);

const handleImgFallback = (e) => {
  e.target.src = 'https://cdn-icons-png.flaticon.com/512/7486/7486744.png';
};
</script>

<style scoped>
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 280px;
}

.empty-state-img {
  max-width: 180px;
  height: auto;
  filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.05));
}

.fs-8 {
  font-size: 0.85rem;
}

.max-w-400 {
  max-width: 400px;
}

.dir-rtl {
  direction: rtl;
}
</style>
