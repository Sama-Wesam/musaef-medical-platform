/**
 * تنسيق التاريخ بالصيغة العربية الكاملة[cite: 26]
 * @param {string|Date} dateString[cite: 26]
 * @returns {string}[cite: 26]
 */
export const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('ar-EG', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date);
};

/**
 * حساب الوقت النسبي (منذ متى)
 * @param {string|Date} dateString
 * @returns {string}
 */
export const timeAgo = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  const now = new Date();
  const seconds = Math.floor((now - date) / 1000);

  if (seconds < 60) return 'منذ لحظات';
  const minutes = Math.floor(seconds / 60);
  if (minutes < 60) return `منذ ${minutes} دقيقة`;
  const hours = Math.floor(minutes / 60);
  if (hours < 24) return `منذ ${hours} ساعة`;
  const days = Math.floor(hours / 24);
  if (days < 30) return `منذ ${days} يوم`;

  return formatDate(dateString);
};

/**
 * حساب تاريخ الأهلية القادم للتبرع (بعد 90 يوماً)
 * @param {string|Date} lastDonationDate
 * @returns {string}
 */
export const getNextEligibleDate = (lastDonationDate) => {
  if (!lastDonationDate) return 'متاح الآن';
  const last = new Date(lastDonationDate);
  last.setDate(last.getDate() + 90);
  return formatDate(last);
};
