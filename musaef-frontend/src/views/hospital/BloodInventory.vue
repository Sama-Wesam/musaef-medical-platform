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

