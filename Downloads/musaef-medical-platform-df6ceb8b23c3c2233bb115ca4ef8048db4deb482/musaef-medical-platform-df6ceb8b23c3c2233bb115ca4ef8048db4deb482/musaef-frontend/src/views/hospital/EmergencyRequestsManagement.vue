<template>
  <div class="emergency-page" dir="rtl">
    <!-- رأس محتوى الصفحة فقط -->
    <section class="page-heading">
      <div class="heading-text">
        <div class="heading-icon">
          <i class="bi bi-bell"></i>
        </div>

        <div>
          <h1>إدارة النداءات الطارئة</h1>

          <p>عرض وإدارة جميع النداءات الطارئة بشكل لحظي</p>
        </div>
      </div>
    </section>

    <!-- شريط الفلاتر -->
    <section class="toolbar">
      <div class="filter-tabs">
        <button
          v-for="filter in filters"
          :key="filter.value"
          type="button"
          class="filter-button"
          :class="{ active: selectedFilter === filter.value }"
          @click="changeFilter(filter.value)"
        >
          <span>{{ filter.label }}</span>

          <strong>
            {{ filter.count }}
          </strong>
        </button>
      </div>

      <button type="button" class="export-button">
        <i class="bi bi-download"></i>
        تصدير التقرير
      </button>
    </section>

    <div class="requests-layout">
      <!-- العمود الرئيسي -->
      <section class="main-requests-card">
        <EmergencyRequestsTable
          :requests="requests"
          :selected-id="selectedRequestId"
          @select="selectRequest"
        />
      </section>

      <!-- العمود الجانبي -->
      <aside class="details-column">
        <EmergencyRequestDetails :request="selectedRequest" />

        <EmergencyResponders :responders="responders" />

        <EmergencyLocationCard />
      </aside>
    </div>
  </div>
</template>

<script setup>
import EmergencyLocationCard from '@/components/hospital/EmergencyLocationCard.vue'
import EmergencyRequestDetails from '@/components/hospital/EmergencyRequestDetails.vue'
import EmergencyRequestsTable from '@/components/hospital/EmergencyRequestsTable.vue'
import EmergencyResponders from '@/components/hospital/EmergencyResponders.vue'

import { useEmergencyRequests } from '@/composables/useEmergencyRequests'

const {
  selectedFilter,
  selectedRequestId,
  selectedRequest,
  responders,
  requests,
  filters,
  changeFilter,
  selectRequest,
} = useEmergencyRequests()
</script>

