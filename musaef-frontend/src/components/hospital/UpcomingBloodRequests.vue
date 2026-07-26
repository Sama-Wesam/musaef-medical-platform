<template>
  <section class="side-card">
    <h3>طلبات التبرع الأخيرة</h3>

    <div class="requests-list">
      <div
        v-for="request in requests"
        :key="`${request.hospital}-${request.time}`"
        class="request-item"
      >
        <div>
          <strong>{{ request.bloodType }}</strong>
          <span>{{ request.hospital }}</span>
        </div>

        <time>{{ request.time }}</time>
      </div>
    </div>

    <button type="button" class="all-requests-button">
      عرض جميع الطلبات
    </button>
  </section>
</template>

<script setup>
defineProps({
  requests: {
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

.requests-list {
  display: flex;
  flex-direction: column;
}

.request-item {
  min-height: 39px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid #f1f2f4;
}

.request-item:last-child {
  border-bottom: 0;
}

.request-item div {
  display: flex;
  align-items: center;
  gap: 7px;
}

.request-item strong {
  color: #dc2626;
  font-size: 8px;
}

.request-item span,
.request-item time {
  color: #6b7280;
  font-size: 7px;
}

.all-requests-button {
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