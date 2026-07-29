<template>
  <div class="notifications-list d-flex flex-column gap-2 gap-md-3">
    <NotificationCard
      v-for="item in items"
      :key="item.id"
      :item="item"
    />

    <div v-if="items.length === 0" class="text-center text-muted py-5 px-3 fs-8 bg-white rounded-4 shadow-sm">
      {{ emptyText }}
    </div>
  </div>
</template>

<script setup>
import NotificationCard from '@/components/hospital/notifications/NotificationCard.vue';

defineProps({
  items: {
    type: Array,
    default: () => []
  },
  emptyText: {
    type: String,
    default: 'لا توجد إشعارات لعرضها حالياً'
  }
});
</script>

<style scoped>
.fs-8 { font-size: 0.85rem; }
</style>
