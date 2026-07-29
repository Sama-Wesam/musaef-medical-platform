// فصائل الدم المعتمدة
export const BLOOD_TYPES = ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'];

// مستويات خطورة النداءات الطارئة
export const URGENCY_LEVELS = [
  { value: 'critical', label: 'حرج جداً (خلال ساعتين)', color: 'danger' },
  { value: 'high', label: 'عالي (خلال 6 ساعات)', color: 'warning' },
  { value: 'medium', label: 'متوسط (خلال 24 ساعة)', color: 'info' },
  { value: 'low', label: 'عادي (مخطط له)', color: 'secondary' }
];

// حالات طلبات التبرع
export const REQUEST_STATUSES = {
  ACTIVE: 'نشط',
  COMPLETED: 'مكتمل',
  CANCELLED: 'ملغى',
  EXPIRED: 'منتهي'
};

// أدوار المستخدمين في المنصة
export const USER_ROLES = {
  DONOR: 'donor',
  HOSPITAL: 'hospital',
  ADMIN: 'admin'
};
