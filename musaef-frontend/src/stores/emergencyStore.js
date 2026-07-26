import { defineStore } from 'pinia'

export const useEmergencyStore = defineStore('emergency', {
  state: () => ({
    selectedFilter: 'all',
    selectedRequestId: 'ER-2026-1840',

    requests: [
      {
        id: 'ER-2026-1840',
        bloodType: 'O-',
        units: 6,
        urgency: 'حرج',
        responders: 92,
        status: 'نشط',
        statusType: 'active',
      },
      {
        id: 'ER-2026-1841',
        bloodType: 'A+',
        units: 4,
        urgency: 'حرج',
        responders: 75,
        status: 'نشط',
        statusType: 'active',
      },
      {
        id: 'ER-2026-1842',
        bloodType: 'B-',
        units: 3,
        urgency: 'متوسط',
        responders: 60,
        status: 'قيد المعالجة',
        statusType: 'processing',
      },
      {
        id: 'ER-2026-1843',
        bloodType: 'AB+',
        units: 2,
        urgency: 'متوسط',
        responders: 40,
        status: 'قيد المعالجة',
        statusType: 'processing',
      },
      {
        id: 'ER-2026-1844',
        bloodType: 'O+',
        units: 2,
        urgency: 'منخفض',
        responders: 100,
        status: 'مكتملة',
        statusType: 'completed',
      },
      {
        id: 'ER-2026-1845',
        bloodType: 'A-',
        units: 1,
        urgency: 'منخفض',
        responders: 100,
        status: 'مكتملة',
        statusType: 'completed',
      },
      {
        id: 'ER-2026-1846',
        bloodType: 'B+',
        units: 1,
        urgency: 'منخفض',
        responders: 100,
        status: 'مكتملة',
        statusType: 'completed',
      },
      {
        id: 'ER-2026-1847',
        bloodType: 'AB-',
        units: 1,
        urgency: 'منخفض',
        responders: 100,
        status: 'مكتملة',
        statusType: 'completed',
      },
    ],

    responders: [
      {
        id: 1,
        name: 'أحمد محمد',
        bloodType: 'O-',
        compatibility: 95,
        image: '/images/responders/donor-1.jpg',
      },
      {
        id: 2,
        name: 'عمر حسن',
        bloodType: 'A-',
        compatibility: 89,
        image: '/images/responders/donor-2.jpg',
      },
      {
        id: 3,
        name: 'خالد محمود',
        bloodType: 'B-',
        compatibility: 85,
        image: '/images/responders/donor-3.jpg',
      },
    ],

    selectedRequest: {
      id: 'ER-2026-1840',
      status: 'نشط',
      location: 'مستشفى الشفاء الأهلي',
      bloodType: 'O-',
      requiredUnits: 4,
      date: '15-05-2026 14:30',
      description: 'حالة حرجة تحتاج إلى استجابة عاجلة.',
    },
  }),

  getters: {
    allCount: (state) => state.requests.length,

    processingCount: (state) =>
      state.requests.filter(
        (request) => request.statusType === 'processing',
      ).length,

    completedCount: (state) =>
      state.requests.filter(
        (request) => request.statusType === 'completed',
      ).length,

    filteredRequests: (state) => {
      if (state.selectedFilter === 'all') {
        return state.requests
      }

      return state.requests.filter(
        (request) =>
          request.statusType === state.selectedFilter,
      )
    },
  },

  actions: {
    setFilter(filter) {
      this.selectedFilter = filter
    },

    selectRequest(request) {
      this.selectedRequestId = request.id

      this.selectedRequest = {
        id: request.id,
        status: request.status,
        location: 'مستشفى الشفاء الأهلي',
        bloodType: request.bloodType,
        requiredUnits: request.units,
        date: '15-05-2026 14:30',
        description: 'حالة حرجة تحتاج إلى استجابة عاجلة.',
      }
    },
  },
})