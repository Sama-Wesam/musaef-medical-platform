import axiosInstance from './axios'

/**
 * جلب إشعارات المستشفى.
 * غيّري المسار فقط إذا كان Laravel يستخدم رابطًا مختلفًا.
 */
export const getHospitalNotifications = async () => {
  const response = await axiosInstance.get('/hospital/notifications')

  return response.data
}

/**
 * تحديد جميع الإشعارات كمقروءة.
 */
export const markAllHospitalNotificationsAsRead = async () => {
  const response = await axiosInstance.patch(
    '/hospital/notifications/read-all',
  )

  return response.data
}

/**
 * تحديد إشعار واحد كمقروء.
 */
export const markHospitalNotificationAsRead = async (notificationId) => {
  if (!notificationId) {
    throw new Error('Notification ID is required')
  }

  const response = await axiosInstance.patch(
    `/hospital/notifications/${notificationId}/read`,
  )

  return response.data
}