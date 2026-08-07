<template>
  <div class="requests-table-wrapper">
    <div class="requests-table-header">
      <span>رقم الطلب</span>
      <span>الفصيلة المطلوبة</span>
      <span>عدد الوحدات</span>
      <span>مستوى الخطورة</span>
      <span>عدد المستجيبين</span>
      <span>حالة الطلب</span>
    </div>

    <button
      v-for="request in requests"
      :key="request.id"
      type="button"
      class="request-row"
      :class="{ selected: selectedId === request.id }"
      @click="$emit('select', request)"
    >
      <strong>{{ request.id }}</strong>

      <span>{{ request.bloodType }}</span>

      <span>{{ request.units }}</span>

      <span
        class="urgency-badge"
        :class="urgencyClass(request.urgency)"
      >
        {{ request.urgency }}
      </span>

      <div class="responders-progress">
        <span>{{ request.responders }}%</span>

        <div class="progress-track">
          <div
            class="progress-fill"
            :class="progressClass(request.responders)"
            :style="{ width: `${request.responders}%` }"
          ></div>
        </div>
      </div>

      <span
        class="status-badge"
        :class="request.statusType"
      >
        {{ request.status }}
      </span>
    </button>
  </div>
</template>

<script setup>
defineProps({
  requests: {
    type: Array,
    default: () => [],
  },

  selectedId: {
    type: String,
    default: '',
  },
})

defineEmits(['select'])

const urgencyClass = (urgency) => {
  if (urgency === 'حرج') {
    return 'critical'
  }

  if (urgency === 'متوسط') {
    return 'medium'
  }

  return 'low'
}

const progressClass = (value) => {
  if (value >= 90) {
    return 'success'
  }

  if (value >= 60) {
    return 'warning'
  }

  return 'danger'
}
</script>

<style scoped>
.requests-table-wrapper {
  width: 100%;
  overflow: hidden;
}

.requests-table-header,
.request-row {
  width: 100%;
  display: grid;
  grid-template-columns:
    minmax(105px, 1.2fr)
    minmax(80px, 0.9fr)
    minmax(65px, 0.7fr)
    minmax(90px, 0.9fr)
    minmax(135px, 1.25fr)
    minmax(90px, 0.9fr);
  align-items: center;
  gap: 12px;
}

.requests-table-header {
  min-height: 42px;
  padding: 0 12px;
  color: #374151;
  font-size: 19px;
  font-weight: 700;
  border-bottom: 1px solid #eceef2;
}

.request-row {
  min-height: 54px;
  padding: 0 12px;
  border: 0;
  border-bottom: 1px solid #f0f1f3;
  background-color: #ffffff;
  color: #111827;
  text-align: right;
  font-size: 13px;
  cursor: pointer;
}

.request-row:hover,
.request-row.selected {
  background-color: #fffafa;
}

.request-row strong {
  font-size: 9px;
}

.urgency-badge,
.status-badge {
  min-width: 52px;
  padding: 4px 8px;
  display: inline-flex;
  justify-content: center;
  border-radius: 12px;
  white-space: nowrap;
  font-size: 13px;
  font-weight: 700;
}

.urgency-badge.critical,
.status-badge.active {
  color: #dc2626;
  background-color: #fdebec;
}

.urgency-badge.medium,
.status-badge.processing {
  color: #f97316;
  background-color: #fff1e7;
}

.urgency-badge.low,
.status-badge.completed {
  color: #16a34a;
  background-color: #edf9f0;
}

.responders-progress {
  display: flex;
  align-items: center;
  gap: 7px;
  direction: ltr;
}

.responders-progress > span {
  width: 29px;
  color: #111827;
  text-align: left;
  font-size: 12px;
}

.progress-track {
  flex: 1;
  height: 3px;
  overflow: hidden;
  border-radius: 10px;
  background-color: #e5e7eb;
}

.progress-fill {
  height: 100%;
}

.progress-fill.success {
  background-color: #16a34a;
}

.progress-fill.warning {
  background-color: #f97316;
}

.progress-fill.danger {
  background-color: #ef4444;
}

@media (max-width: 760px) {
  .requests-table-wrapper {
    overflow-x: auto;
  }

  .requests-table-header,
  .request-row {
    min-width: 730px;
  }
}
</style>