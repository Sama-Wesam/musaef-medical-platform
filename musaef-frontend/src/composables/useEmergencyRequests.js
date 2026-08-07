import { computed } from 'vue'
import { storeToRefs } from 'pinia'

import { useEmergencyStore } from '@/stores/emergencyStore'

export const useEmergencyRequests = () => {
  const emergencyStore = useEmergencyStore()

  const {
    selectedFilter,
    selectedRequestId,
    selectedRequest,
    responders,
  } = storeToRefs(emergencyStore)

  const requests = computed(
    () => emergencyStore.filteredRequests,
  )

  const filters = computed(() => [
    {
      label: 'الكل',
      value: 'all',
      count: emergencyStore.allCount,
    },
    {
      label: 'قيد المعالجة',
      value: 'processing',
      count: emergencyStore.processingCount,
    },
    {
      label: 'مكتملة',
      value: 'completed',
      count: emergencyStore.completedCount,
    },
  ])

  const changeFilter = (filter) => {
    emergencyStore.setFilter(filter)
  }

  const selectRequest = (request) => {
    emergencyStore.selectRequest(request)
  }

  return {
    selectedFilter,
    selectedRequestId,
    selectedRequest,
    responders,
    requests,
    filters,
    changeFilter,
    selectRequest,
  }
}