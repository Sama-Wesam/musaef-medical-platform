<template>
  <nav
    class="settings-tabs"
    aria-label="أقسام إعدادات الجهة الطبية"
  >
    <button
      v-for="tab in tabs"
      :key="tab.value"
      type="button"
      class="tab-button"
      :class="{ active: modelValue === tab.value }"
      @click="$emit('update:modelValue', tab.value)"
    >
      {{ tab.label }}
    </button>
  </nav>
</template>

<script setup>
defineProps({
  tabs: {
    type: Array,
    default: () => [],
  },

  modelValue: {
    type: String,
    default: 'profile',
  },
})

defineEmits(['update:modelValue'])
</script>

<style scoped>
body {
  font-family: var(--font-family-main);
}
.settings-tabs {
  
  width: 100%;
  min-height: 48px;
  margin-bottom: 22px;
  padding: 0 12px;

  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));

  border-radius: 5px;
  background-color: #ffffff;
}

.tab-button {
  min-width: 0;
  height: 48px;
  position: relative;

  border: 0;
  background-color: transparent;
  color: #111827;

  font-size: 17px;
  font-weight: 700;
  cursor: pointer;
}

.tab-button::after {
  content: '';
  height: 2px;

  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;

  transform: scaleX(0);
  background-color: #ef4444;

  transition: transform 0.2s ease;
}

.tab-button:hover {
  color: #dc2626;
}

.tab-button.active {
  color: #ef4444;
}

.tab-button.active::after {
  transform: scaleX(1);
}

@media (max-width: 700px) {
  .settings-tabs {
    padding: 0;
    display: flex;
    overflow-x: auto;
  }

  .tab-button {
    min-width: 125px;
    padding: 0 15px;
  }
}

</style>