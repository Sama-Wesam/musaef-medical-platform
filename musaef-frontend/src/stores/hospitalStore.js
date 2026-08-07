import { defineStore } from 'pinia'

export const useHospitalStore = defineStore('hospital', {
  state: () => ({
    inventory: [
      {
        type: 'O-',
        available: 256,
        minimum: 100,
        status: 'طبيعي',
        statusType: 'normal',
        responders: 256,
      },
      {
        type: 'A+',
        available: 2,
        minimum: 10,
        status: 'حرج',
        statusType: 'critical',
        responders: 20,
      },
      {
        type: 'B-',
        available: 180,
        minimum: 80,
        status: 'طبيعي',
        statusType: 'normal',
        responders: 225,
      },
      {
        type: 'AB+',
        available: 20,
        minimum: 20,
        status: 'طبيعي',
        statusType: 'normal',
        responders: 175,
      },
      {
        type: 'O+',
        available: 60,
        minimum: 60,
        status: 'طبيعي',
        statusType: 'normal',
        responders: 236,
      },
      {
        type: 'A-',
        available: 15,
        minimum: 15,
        status: 'منخفض',
        statusType: 'low',
        responders: 33,
      },
      {
        type: 'B+',
        available: 30,
        minimum: 30,
        status: 'طبيعي',
        statusType: 'normal',
        responders: 227,
      },
      {
        type: 'AB-',
        available: 10,
        minimum: 10,
        status: 'منخفض',
        statusType: 'low',
        responders: 60,
      },
    ],

    alerts: [
      {
        type: 'O-',
        status: 'منخفض جدًا',
        message: 'المخزون أقل من الحد الآمن',
        color: '#ef4444',
      },
      {
        type: 'B-',
        status: 'منخفض',
        message: 'المخزون يكفي لمدة 5 أيام',
        color: '#f97316',
      },
      {
        type: 'A+',
        status: 'منخفض',
        message: 'المخزون يكفي لمدة 3 أيام',
        color: '#f59e0b',
      },
    ],

    upcomingRequests: [
      {
        hospital: 'مستشفى الأمل',
        bloodType: 'O+',
        time: '10:24 ص',
      },
      {
        hospital: 'مركز الشفاء',
        bloodType: 'A+',
        time: '09:50 ص',
      },
      {
        hospital: 'بنك الدم المركزي',
        bloodType: 'B-',
        time: '09:15 ص',
      },
      {
        hospital: 'مستشفى القدس',
        bloodType: 'AB-',
        time: '08:47 ص',
      },
      {
        hospital: 'مركز الحياة',
        bloodType: 'O-',
        time: '08:30 ص',
      },
    ],
  }),

  getters: {
    totalUnits: (state) =>
      state.inventory.reduce(
        (total, item) => total + item.available,
        0,
      ),

    consumedUnits: () => 856,

    lowStockCount: (state) =>
      state.inventory.filter(
        (item) =>
          item.statusType === 'low' ||
          item.statusType === 'critical',
      ).length,

    urgentCasesCount: () => 6,
  },

  actions: {
    addUnits({ bloodType, units }) {
      const item = this.inventory.find(
        (inventoryItem) => inventoryItem.type === bloodType,
      )

      if (!item) {
        return
      }

      item.available += Number(units)
      this.updateStatus(item)
    },

    removeUnits({ bloodType, units }) {
      const item = this.inventory.find(
        (inventoryItem) => inventoryItem.type === bloodType,
      )

      if (!item) {
        return
      }

      item.available = Math.max(
        0,
        item.available - Number(units),
      )

      this.updateStatus(item)
    },

    updateStatus(item) {
      if (item.available < item.minimum * 0.5) {
        item.status = 'حرج'
        item.statusType = 'critical'
        return
      }

      if (item.available < item.minimum) {
        item.status = 'منخفض'
        item.statusType = 'low'
        return
      }

      item.status = 'طبيعي'
      item.statusType = 'normal'
    },
  },
})