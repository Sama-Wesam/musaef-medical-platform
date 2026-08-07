import { computed, ref } from 'vue'
import { storeToRefs } from 'pinia'

import { useHospitalStore } from '@/stores/hospitalStore'

export const useBloodInventory = () => {
  const hospitalStore = useHospitalStore()

  const {
    inventory,
    alerts,
    upcomingRequests,
  } = storeToRefs(hospitalStore)

  const searchText = ref('')

  const filteredInventory = computed(() => {
    const query = searchText.value.trim().toLowerCase()

    if (!query) {
      return inventory.value
    }

    return inventory.value.filter((item) =>
      item.type.toLowerCase().includes(query),
    )
  })

  const summaryCards = computed(() => [
    {
      title: 'إجمالي الوحدات',
      value: hospitalStore.totalUnits.toLocaleString(),
      description: 'وحدة دم',
      icon: 'bi bi-droplet',
      color: '#2563eb',
      background: '#eef4ff',
    },
    {
      title: 'الوحدات المستهلكة',
      value: hospitalStore.consumedUnits,
      description: 'وحدة هذا الشهر',
      icon: 'bi bi-exclamation-triangle',
      color: '#f59e0b',
      background: '#fff7e8',
    },
    {
      title: 'فصائل ذات انخفاض',
      value: hospitalStore.lowStockCount,
      description: 'تحتاج متابعة',
      icon: 'bi bi-check-lg',
      color: '#16a34a',
      background: '#edf9f0',
    },
    {
      title: 'الحالات الحرجة',
      value: hospitalStore.urgentCasesCount,
      description: 'طلب مستعجل',
      icon: 'bi bi-alarm',
      color: '#ef4444',
      background: '#fdebec',
    },
  ])

  const addUnits = (payload) => {
    hospitalStore.addUnits(payload)
  }

  const removeUnits = (payload) => {
    hospitalStore.removeUnits(payload)
  }

  return {
    searchText,
    filteredInventory,
    alerts,
    upcomingRequests,
    summaryCards,
    addUnits,
    removeUnits,
  }
}