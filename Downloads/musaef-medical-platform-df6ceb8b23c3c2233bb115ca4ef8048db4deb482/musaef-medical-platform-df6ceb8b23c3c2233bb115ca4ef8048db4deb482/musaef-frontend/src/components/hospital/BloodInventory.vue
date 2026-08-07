<template>
  <div class="blood-inventory-page" dir="rtl">
    <!-- بطاقات الملخص -->
    <InventorySummaryCards :cards="summaryCards" />

    <div class="inventory-layout">
      <!-- المحتوى الرئيسي -->
      <main class="inventory-main">
        <BloodInventoryTable
          :inventory="filteredInventory"
        />

        <div class="forms-grid">
          <InventoryAdjustmentForm
            variant="remove"
            @submit="removeUnits"
          />

          <InventoryAdjustmentForm
            variant="add"
            @submit="addUnits"
          />
        </div>
      </main>

      <!-- العمود الجانبي -->
      <aside class="inventory-sidebar">
        <InventoryAlertsPanel :alerts="alerts" />

        <UpcomingBloodRequests
          :requests="upcomingRequests"
        />
      </aside>
    </div>
  </div>
</template>

<script setup>
import BloodInventoryTable from '@/components/hospital/BloodInventoryTable.vue'
import InventoryAdjustmentForm from '@/components/hospital/InventoryAdjustmentForm.vue'
import InventoryAlertsPanel from '@/components/hospital/InventoryAlertsPanel.vue'
import InventorySummaryCards from '@/components/hospital/InventorySummaryCards.vue'
import UpcomingBloodRequests from '@/components/hospital/UpcomingBloodRequests.vue'

import { useBloodInventory } from '@/composables/useBloodInventory'

const {
  filteredInventory,
  alerts,
  upcomingRequests,
  summaryCards,
  addUnits,
  removeUnits,
} = useBloodInventory()
</script>

<style scoped>
* {
  box-sizing: border-box;
}

.blood-inventory-page {
  width: 100%;
  color: #111827;
}

.inventory-layout {
  width: 100%;
  display: grid;
  grid-template-columns:
    190px
    minmax(0, 1fr);
  grid-template-areas: 'side main';
  gap: 16px;
  direction: ltr;
}

.inventory-main {
  grid-area: main;
  min-width: 0;
  direction: rtl;
}

.inventory-sidebar {
  grid-area: side;
  display: flex;
  flex-direction: column;
  gap: 14px;
  direction: rtl;
}

.forms-grid {
  margin-top: 17px;
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 16px;
}

@media (max-width: 950px) {
  .inventory-layout {
    grid-template-columns: 1fr;
    grid-template-areas:
      'main'
      'side';
  }

  .inventory-sidebar {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 650px) {
  .forms-grid,
  .inventory-sidebar {
    grid-template-columns: 1fr;
  }
}
</style>