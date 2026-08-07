import { defineStore } from 'pinia'

import {
  getHospitalNotifications,
  markAllHospitalNotificationsAsRead,
  markHospitalNotificationAsRead,
} from '@/api/notification'

const fallbackNotifications = [
  {
    id: 1,
    category: 'emergency',
    title:
      'قام المتبرع أحمد خالد بالاستجابة للنداء الطارئ رقم ER-2026-1847',
    description: 'وهو في طريقه إلى المستشفى الآن',
    time: 'منذ دقيقتين',
    icon: 'bi bi-person-fill-check',
    color: '#16a34a',
    background: '#edf9f0',
    isUrgent: true,
    isRead: false,
  },
  {
    id: 2,
    category: 'inventory',
    title: 'تنبيه حرج: انخفاض مخزون فصيلة الدم O- عن الحد الآمن',
    description: 'يرجى اتخاذ إجراء استجابة فورية',
    time: 'منذ 10 دقائق',
    icon: 'bi bi-droplet-fill',
    color: '#dc2626',
    background: '#fdebec',
    isUrgent: true,
    isRead: false,
  },
  {
    id: 3,
    category: 'general',
    title: 'تم اعتماد وتوثيق إجراء المناوبة الطبية بنجاح',
    description: 'من قبل فريق الدعم الإداري',
    time: 'منذ 3 ساعات',
    icon: 'bi bi-file-earmark-check-fill',
    color: '#2563eb',
    background: '#eef3ff',
    isUrgent: false,
    isRead: true,
  },
  {
    id: 4,
    category: 'inventory',
    title: 'تم استلام 15 وحدة دم من المتبرعين لهذا الأسبوع',
    description: 'أضيفت الوحدات إلى مخزون بنك الدم بنجاح',
    time: 'منذ 5 ساعات',
    icon: 'bi bi-capsule-pill',
    color: '#16a34a',
    background: '#edf9f0',
    isUrgent: false,
    isRead: true,
  },
  {
    id: 5,
    category: 'general',
    title: 'تذكير: موعد الصيانة الدورية لأجهزة بنك الدم',
    description: 'يوم الأحد من الساعة 02:00 حتى الساعة 04:00 صباحًا',
    time: 'منذ ساعة',
    icon: 'bi bi-gear',
    color: '#475569',
    background: '#f1f5f9',
    isUrgent: false,
    isRead: true,
  },
]

export const useNotificationStore = defineStore('notification', {
  state: () => ({
    notifications: [],
    selectedFilter: 'all',
    isLoading: false,
    error: null,
  }),

  getters: {
    unreadCount: (state) =>
      state.notifications.filter(
        (notification) => !notification.isRead,
      ).length,

    filteredNotifications: (state) => {
      if (state.selectedFilter === 'unread') {
        return state.notifications.filter(
          (notification) => !notification.isRead,
        )
      }

      if (state.selectedFilter === 'alerts') {
        return state.notifications.filter(
          (notification) => notification.isUrgent,
        )
      }

      return state.notifications
    },
  },

  actions: {
    setFilter(filter) {
      this.selectedFilter = filter
    },

    normalizeNotification(notification) {
      return {
        id: notification.id,
        category: notification.category || notification.type || 'general',
        title: notification.title || '',
        description: notification.description || notification.message || '',
        time: notification.time || notification.created_at || '',
        icon: notification.icon || 'bi bi-bell',
        color: notification.color || '#dc2626',
        background: notification.background || '#fdebec',
        isUrgent:
          notification.isUrgent ??
          notification.is_urgent ??
          false,
        isRead:
          notification.isRead ??
          notification.is_read ??
          false,
      }
    },

    async fetchNotifications() {
      this.isLoading = true
      this.error = null

      try {
        const data = await getHospitalNotifications()

        const list = Array.isArray(data)
          ? data
          : data?.data || data?.notifications || []

        this.notifications = list.map(this.normalizeNotification)
      } catch (error) {
        console.error('Failed to load notifications:', error)

        this.error =
          error?.response?.data?.message ||
          'تعذر تحميل الإشعارات من الخادم.'

        /*
         * بيانات مؤقتة لتظهر الصفحة أثناء مرحلة تصميم الواجهة.
         * عند اكتمال Laravel يمكن حذف هذا السطر.
         */
        this.notifications = fallbackNotifications
      } finally {
        this.isLoading = false
      }
    },

    async markAllAsRead() {
      const previousNotifications = this.notifications.map(
        (notification) => ({ ...notification }),
      )

      this.notifications = this.notifications.map(
        (notification) => ({
          ...notification,
          isRead: true,
        }),
      )

      try {
        await markAllHospitalNotificationsAsRead()
      } catch (error) {
        console.error('Failed to mark all notifications:', error)

        this.notifications = previousNotifications

        throw error
      }
    },

    async markAsRead(notificationId) {
      const notification = this.notifications.find(
        (item) => item.id === notificationId,
      )

      if (!notification || notification.isRead) {
        return
      }

      const previousState = notification.isRead
      notification.isRead = true

      try {
        await markHospitalNotificationAsRead(notificationId)
      } catch (error) {
        notification.isRead = previousState
        throw error
      }
    },
  },
})