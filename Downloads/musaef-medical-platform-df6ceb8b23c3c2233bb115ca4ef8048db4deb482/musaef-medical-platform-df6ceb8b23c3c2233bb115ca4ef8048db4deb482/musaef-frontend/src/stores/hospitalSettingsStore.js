import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useHospitalSettingsStore = defineStore(
  'hospitalSettings',
  () => {
    const activeTab = ref('profile')
    const loading = ref(false)

    const facility = ref({
      name: 'مجمع الشفاء الطبي',
      email: 'info@hospital.ps',
      phone: '+970 562145896',
      emergencyPhone: '101',
      city: 'غزة',
      address: 'شارع المستشفى، غزة',
      workingHours: '24/7',
    })

    const setActiveTab = (tab) => {
      activeTab.value = tab
    }

    const updateFacility = async (payload) => {
      loading.value = true

      try {
        /*
          لاحقًا ضعي هنا طلب API:

          const response = await hospitalApi.updateSettings(payload)
          facility.value = response.data
        */

        await new Promise((resolve) => {
          setTimeout(resolve, 700)
        })

        facility.value = {
          ...facility.value,
          ...payload,
        }

        return facility.value
      } finally {
        loading.value = false
      }
    }

    return {
      activeTab,
      facility,
      loading,
      setActiveTab,
      updateFacility,
    }
  },
)