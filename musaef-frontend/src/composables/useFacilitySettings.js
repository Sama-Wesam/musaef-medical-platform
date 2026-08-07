import { computed, reactive, ref, watch } from 'vue'
import { storeToRefs } from 'pinia'

import { useHospitalSettingsStore } from '@/stores/hospitalSettingsStore'

export const useFacilitySettings = () => {
  const settingsStore = useHospitalSettingsStore()

  const {
    activeTab,
    facility,
    loading,
  } = storeToRefs(settingsStore)

  const tabs = computed(() => [
    {
      label: 'الملف التعريفي',
      value: 'profile',
    },
    {
      label: 'الإعدادات',
      value: 'settings',
    },
    {
      label: 'الأمان',
      value: 'security',
    },
    {
      label: 'الإشعارات',
      value: 'notifications',
    },
    {
      label: 'التحقق والاعتماد',
      value: 'verification',
    },
  ])

  const settingsForm = ref({
    name: '',
    email: '',
    phone: '',
    city: '',
    address: '',
    workingHours: '',
  })

  const errors = reactive({})

  /*
    عند تحميل بيانات facility من Store،
    تُنسخ إلى النموذج.
  */
  watch(
    facility,
    (newFacility) => {
      if (!newFacility) {
        return
      }

      settingsForm.value = {
        name: newFacility.name ?? '',
        email: newFacility.email ?? '',
        phone: newFacility.phone ?? '',
        city: newFacility.city ?? '',
        address: newFacility.address ?? '',
        workingHours:
          newFacility.workingHours ??
          newFacility.working_hours ??
          '',
      }
    },
    {
      immediate: true,
      deep: true,
    },
  )

  const changeTab = (tab) => {
    settingsStore.setActiveTab(tab)
  }

  const clearErrors = () => {
    Object.keys(errors).forEach((key) => {
      delete errors[key]
    })
  }

  const validateSettings = () => {
    clearErrors()

    if (!settingsForm.value.name?.trim()) {
      errors.name = 'اسم الجهة الطبية مطلوب'
    }

    if (!settingsForm.value.email?.trim()) {
      errors.email = 'البريد الإلكتروني مطلوب'
    } else {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

      if (!emailPattern.test(settingsForm.value.email)) {
        errors.email = 'صيغة البريد الإلكتروني غير صحيحة'
      }
    }

    if (!settingsForm.value.phone?.trim()) {
      errors.phone = 'رقم الهاتف مطلوب'
    }

    if (!settingsForm.value.city) {
      errors.city = 'يرجى اختيار المدينة'
    }

    if (!settingsForm.value.address?.trim()) {
      errors.address = 'العنوان التفصيلي مطلوب'
    }

    if (!settingsForm.value.workingHours) {
      errors.workingHours = 'يرجى اختيار ساعات العمل'
    }

    return Object.keys(errors).length === 0
  }

  const saveSettings = async () => {
    if (!validateSettings()) {
      return false
    }

    try {
      const payload = {
        name: settingsForm.value.name.trim(),
        email: settingsForm.value.email.trim(),
        phone: settingsForm.value.phone.trim(),
        city: settingsForm.value.city,
        address: settingsForm.value.address.trim(),
        workingHours: settingsForm.value.workingHours,
      }

      await settingsStore.updateFacility(payload)

      window.alert('تم تحديث بيانات الجهة الطبية بنجاح')

      return true
    } catch (error) {
      console.error('حدث خطأ أثناء حفظ الإعدادات:', error)

      window.alert('حدث خطأ أثناء حفظ البيانات')

      return false
    }
  }

  const showHistory = () => {
    window.alert('سيتم عرض سجل المراجعات هنا')
  }

  return {
    activeTab,
    facility,
    loading,
    tabs,
    settingsForm,
    errors,
    changeTab,
    saveSettings,
    showHistory,
  }
}