<template>
  <section class="inventory-table-card">
    <div class="table-heading">
      <div>
        <h2>مخزون بنك الدم</h2>
        <p>التفاصيل الحالية لفصائل الدم المتوفرة</p>
      </div>
    </div>

    <div class="table-wrapper">
      <div class="table-header table-grid">
        <span>الفصيلة المطلوبة</span>
        <span>عدد الوحدات المتوفرة</span>
        <span>الحد الأدنى المطلوب</span>
        <span>الحالة</span>
        <span>عدد المستجيبين</span>
        <span>الإجراءات</span>
      </div>

      <div
        v-for="item in inventory"
        :key="item.type"
        class="table-row table-grid"
      >
        <strong>{{ item.type }}</strong>

        <span>{{ item.available }}</span>

        <span>{{ item.minimum }}</span>

        <span
          class="status-badge"
          :class="item.statusType"
        >
          {{ item.status }}
        </span>

        <div class="responders-progress">
          <span>{{ item.responders }}%</span>

          <div class="progress-track">
            <div
              class="progress-fill"
              :class="item.statusType"
              :style="{
                width: `${Math.min(item.responders, 100)}%`,
              }"
            ></div>
          </div>
        </div>

        <button type="button" class="details-button">
          عرض التفاصيل
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
defineProps({
  inventory: {
    type: Array,
    default: () => [],
  },
})
</script>

<style scoped>
.inventory-table-card {
  width: 100%;
  padding: 17px;
  overflow: hidden;
  border: 1px solid #eceef2;
  border-radius: 10px;
  background-color: #ffffff;
}

.table-heading {
  margin-bottom: 16px;
}

.table-heading h2 {
  margin: 0 0 5px;
  font-size: 44;
  font-weight: 800;
}

.table-heading p {
  margin: 0;
  color: #9ca3af;
  font-size: 14px;
}

.table-wrapper {
  width: 100%;
}

.table-grid {
  display: grid;
  grid-template-columns:
    75px
    105px
    105px
    75px
    minmax(120px, 1fr)
    88px;
  align-items: center;
  gap: 10px;
}

.table-header {
  min-height: 38px;
  padding: 0 8px;
  color: #374151;
  font-size: 13px;
  font-weight: 700;
  border-bottom: 1px solid #eceef2;
}

.table-row {
  min-height: 44px;
  padding: 0 8px;
  border-bottom: 1px solid #f1f2f4;
  font-size: 13px;
}

.table-row:last-child {
  border-bottom: 0;
}

.table-row strong {
  font-size: 13px;
}

.status-badge {
  min-width: 48px;
  padding: 4px 7px;
  border-radius: 12px;
  text-align: center;
  font-size: 13px;
}

.status-badge.normal {
  color: #16a34a;
  background-color: #edf9f0;
}

.status-badge.low {
  color: #f97316;
  background-color: #fff1e7;
}

.status-badge.critical {
  color: #dc2626;
  background-color: #fdebec;
}

.responders-progress {
  display: flex;
  align-items: center;
  gap: 8px;
  direction: ltr;
}

.responders-progress > span {
  width: 30px;
  color: #111827;
  font-size: 12px;
}

.progress-track {
  flex: 1;
  height: 3px;
  overflow: hidden;
  border-radius: 20px;
  background-color: #e5e7eb;
}

.progress-fill {
  height: 100%;
}

.progress-fill.normal {
  background-color: #16a34a;
}

.progress-fill.low {
  background-color: #f97316;
}

.progress-fill.critical {
  background-color: #ef4444;
}

.details-button {
  min-height: 27px;
  padding: 0 8px;
  border: 1px solid #e5e7eb;
  border-radius: 5px;
  background-color: #ffffff;
  color: #111827;
  font-size: 12px;
  cursor: pointer;
}

.details-button:hover {
  color: #dc2626;
  border-color: #fecaca;
}

@media (max-width: 760px) {
  .table-wrapper {
    overflow-x: auto;
  }

  .table-grid {
    min-width: 680px;
  }
}
</style>