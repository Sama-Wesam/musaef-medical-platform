<template>
  <div class="notification-filters">
    <button
      v-for="filter in filters"
      :key="filter.value"
      type="button"
      class="filter-button"
      :class="{ active: modelValue === filter.value }"
      @click="$emit('update:modelValue', filter.value)"
    >
      <i :class="filter.icon"></i>

      <span>{{ filter.label }}</span>

      <span
        v-if="filter.count > 0"
        class="filter-count"
      >
        {{ filter.count }}
      </span>
    </button>
  </div>
</template>

<script setup>
defineProps({
  filters: {
    type: Array,
    default: () => [],
  },

  modelValue: {
    type: String,
    default: 'all',
  },
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
.notification-filters {
  display: flex;
  align-items: center;
  gap: 12px;
}

.filter-button {
  min-width: 112px;
  height: 39px;
  padding: 0 14px;

  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 7px;

  border: 1px solid #e5e7eb;
  border-radius: 8px;

  background-color: #ffffff;
  color: #374151;

  font-size: 15px;
  cursor: pointer;
}

.filter-button:hover {
  color: #dc2626;
  border-color: #fecaca;
  background-color: #fff7f7;
}

.filter-button.active {
  color: #dc2626;
  border-color: #ef4444;
  background-color: #fff7f7;
  font-weight: 900;
}

.filter-count {
  min-width: 18px;
  height: 18px;
  padding: 0 5px;

  display: inline-flex;
  align-items: center;
  justify-content: center;

  border-radius: 10px;
  background-color: #f3f4f6;

  font-size: 8px;
}

.filter-button.active .filter-count {
  background-color: #fee2e2;
}

@media (max-width: 600px) {
  .notification-filters {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 7px;
  }

  .filter-button {
    min-width: 0;
    padding: 0 7px;
    font-size: 8px;
  }

  .filter-count {
    display: none;
  }
}
</style>