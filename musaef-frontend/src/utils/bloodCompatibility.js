// مصفوفات التوافق الطبي لفصائل الدم
const GIVE_MATRIX = {
  'O-': ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'], // متبرع عام
  'O+': ['O+', 'A+', 'B+', 'AB+'],
  'A-': ['A-', 'A+', 'AB-', 'AB+'],
  'A+': ['A+', 'AB+'],
  'B-': ['B-', 'B+', 'AB-', 'AB+'],
  'B+': ['B+', 'AB+'],
  'AB-': ['AB-', 'AB+'],
  'AB+': ['AB+']
};

const RECEIVE_MATRIX = {
  'AB+': ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'], // مستقبل عام
  'AB-': ['O-', 'A-', 'B-', 'AB-'],
  'A+': ['O-', 'O+', 'A-', 'A+'],
  'A-': ['O-', 'A-'],
  'B+': ['O-', 'O+', 'B-', 'B+'],
  'B-': ['O-', 'B-'],
  'O+': ['O-', 'O+'],
  'O-': ['O-']
};

/**
 * فحص إمكانية التبرع بين فصيلتين
 * @param {string} donorType - فصيلة المتبرع
 * @param {string} recipientType - فصيلة المستقبل
 * @returns {boolean}
 */
export const canDonate = (donorType, recipientType) => {
  if (!donorType || !recipientType) return false;
  const allowed = GIVE_MATRIX[donorType] || [];
  return allowed.includes(recipientType);
};

/**
 * جلب قائمة الفصائل التي يمكن التبرع لها
 * @param {string} bloodType
 * @returns {Array<string>}
 */
export const getCanDonateTo = (bloodType) => {
  return GIVE_MATRIX[bloodType] || [];
};

/**
 * جلب قائمة الفصائل التي يمكن الاستقبال منها
 * @param {string} bloodType
 * @returns {Array<string>}
 */
export const getCanReceiveFrom = (bloodType) => {
  return RECEIVE_MATRIX[bloodType] || [];
};
