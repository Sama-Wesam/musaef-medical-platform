<template>
  <div class="summary-grid">
    <article
      v-for="card in cards"
      :key="card.title"
      class="summary-card"
    >
      <div
        class="summary-icon"
        :style="{
          color: card.color,
          backgroundColor: card.background,
        }"
      >
        <i :class="card.icon"></i>
      </div>

      <div class="summary-content">
        <span>{{ card.title }}</span>
        <strong>{{ card.value }}</strong>
        <small>{{ card.description }}</small>
      </div>
    </article>
  </div>
</template>

<script setup>
defineProps({
  cards: {
    type: Array,
    default: () => [],
  },
})
</script>

<style scoped>
.summary-grid {
  width: 100%;
  margin-bottom: 20px;
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 14px;
}

.summary-card {
  min-height: 84px;
  padding: 14px;
  display: flex;
  align-items: center;
  gap: 13px;
  border: 1px solid #eef0f3;
  border-radius: 7px;
  background-color: #ffffff;
}

.summary-icon {
  width: 46px;
  height: 46px;
  display: grid;
  place-items: center;
  flex-shrink: 0;
  border-radius: 4px;
  font-size: 38px;
}

.summary-content {
  min-width: 0;
  display: flex;
  flex-direction: column;
}

.summary-content span {
  margin-bottom: 5px;
  color: #374151;
  font-size: 19px;
}

.summary-content strong {
  margin-bottom: 3px;
  color: #111827;
  font-size: 18px;
}

.summary-content small {
  color: #9ca3af;
  font-size: 17px;
}

@media (max-width: 1000px) {
  .summary-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 550px) {
  .summary-grid {
    grid-template-columns: 1fr;
  }
}
</style>