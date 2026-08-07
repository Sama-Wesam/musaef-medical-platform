<template>
  <section class="side-card">
    <h3>تنبيهات فورية</h3>

    <div class="alerts-list">
      <article
        v-for="alert in alerts"
        :key="alert.type"
        class="alert-item"
      >
        <div>
          <strong :style="{ color: alert.color }">
            {{ alert.type }}
          </strong>

          <p>{{ alert.message }}</p>
        </div>

        <span
          class="alert-status"
          :style="{
            color: alert.color,
            backgroundColor: `${alert.color}12`,
          }"
        >
          {{ alert.status }}
        </span>
      </article>
    </div>

    <button type="button" class="all-alerts-button">
      عرض جميع التنبيهات
    </button>
  </section>
</template>

<script setup>
defineProps({
  alerts: {
    type: Array,
    default: () => [],
  },
})
</script>

<style scoped>
.side-card {
  padding: 15px;
  border: 1px solid #eceef2;
  border-radius: 9px;
  background-color: #ffffff;
}

.side-card h3 {
  margin: 0 0 13px;
  font-size: 11px;
  font-weight: 800;
}

.alerts-list {
  display: flex;
  flex-direction: column;
}

.alert-item {
  min-height: 48px;
  padding: 8px 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 9px;
  border-bottom: 1px solid #f1f2f4;
}

.alert-item:last-child {
  border-bottom: 0;
}

.alert-item strong {
  font-size: 10px;
}

.alert-item p {
  margin: 5px 0 0;
  color: #9ca3af;
  font-size: 7px;
}

.alert-status {
  min-width: 48px;
  padding: 4px 6px;
  border-radius: 12px;
  text-align: center;
  white-space: nowrap;
  font-size: 6px;
}

.all-alerts-button {
  width: 100%;
  min-height: 29px;
  margin-top: 12px;
  border: 0;
  border-radius: 15px;
  background-color: #f7f8fb;
  color: #111827;
  font-size: 8px;
  font-weight: 700;
  cursor: pointer;
}
</style>