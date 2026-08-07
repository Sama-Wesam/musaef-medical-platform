<template>
  <div class="empty-state text-center p-5">
    <img :src="imageUrl" alt="Empty State" class="mb-3" style="max-width: 200px;" />
    
    <h4 class="text-muted fw-bold">{{ title }}</h4>
    
    <p class="text-secondary">{{ description }}</p>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

// تعريف الخصائص التي سيستقبلها المكون من الصفحات الأخرى
const props = defineProps({
  title: {
    type: String,
    default: 'لا توجد بيانات حالياً'
  },
  description: {
    type: String,
    default: 'شكرًا لكونك مستعدًا دائمًا للمساعدة ❤️'
  },
  imageUrl: {
    type: String,
    default: '/assets/images/default-empty.svg' // مسار صورة افتراضية
  }
});
</script>

<style scoped>
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 300px;
}
</style>