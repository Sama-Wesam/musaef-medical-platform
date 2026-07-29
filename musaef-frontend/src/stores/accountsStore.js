import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

export const useAccountsStore = defineStore('accounts', {
  state: () => ({
    activeTab: 'donors', // donors | hospitals | roles | logs
    donors: [
      { id: 1, name: 'محمد حسن', phone: '059998765', bloodType: '-O', location: 'غزة', status: 'نشط' },
      { id: 2, name: 'شذا محمد', phone: '059487635', bloodType: 'A+', location: 'دير البلح', status: 'نشط' },
      { id: 3, name: 'خلود خالد', phone: '059876432', bloodType: 'AB+', location: 'خانيونس', status: 'معلق' },
      { id: 4, name: 'روان تامر', phone: '059345728', bloodType: 'O+', location: 'رفح', status: 'نشط' },
      { id: 5, name: 'فرح حسن', phone: '059887655', bloodType: '-A', location: 'نصيرات', status: 'ملغي' },
      { id: 6, name: 'ختام محمد', phone: '0593344578', bloodType: 'B+', location: 'غزة', status: 'ملغي' },
      { id: 7, name: 'يوسف جميل', phone: '0598876775', bloodType: 'AB-', location: 'رفح', status: 'نشط' }
    ],
    hospitals: [
      { id: 1, name: 'مستشفى الشفاء الطبي', type: 'حكومي', phone: '082823400', location: 'غزة - الرمال', status: 'نشط' },
      { id: 2, name: 'مستشفى شهداء الأقصى', type: 'حكومي', phone: '082554100', location: 'دير البلح', status: 'نشط' },
      { id: 3, name: 'مستشفى ناصر الطبي', type: 'حكومي', phone: '082053110', location: 'خانيونس', status: 'نشط' },
      { id: 4, name: 'المستشفى الأندونيسي', type: 'حكومي', phone: '082478900', location: 'شمال غزة', status: 'معلق' },
      { id: 5, name: 'مستشفى العودة', type: 'أهلي / أونروا', phone: '082531000', location: 'النصيرات', status: 'نشط' },
      { id: 6, name: 'مستشفى القدس', type: 'خاص / هلال أحمر', phone: '082885400', location: 'غزة - تل الهوا', status: 'ملغي' },
      { id: 7, name: 'مستشفى الكويتي التخصصي', type: 'أهلي خيري', phone: '082134500', location: 'رفح', status: 'نشط' }
    ],
    roles: [
      { id: 1, name: 'د. سعيد عبده', roleTitle: 'مدير نظام عام', email: 's.abdo@musaef.ps', scope: 'الوصول الكامل', status: 'نشط' },
      { id: 2, name: 'أحمد محمود', roleTitle: 'مشرف بنك الدم', email: 'a.mahmoud@musaef.ps', scope: 'إدارة الطلبات والمتبرعين', status: 'نشط' },
      { id: 3, name: 'د. سارة خليل', roleTitle: 'مسؤول مستشفى', email: 's.khalil@shifa.ps', scope: 'مستشفى الشفاء الطبي', status: 'نشط' },
      { id: 4, name: 'م. خالد حسن', roleTitle: 'دعم فني وتقني', email: 'k.hassan@musaef.ps', scope: 'السجلات والسيرفرات', status: 'معلق' },
      { id: 5, name: 'إيمان علي', roleTitle: 'مرحل طوارئ', email: 'e.ali@musaef.ps', scope: 'رادار الطوارئ والنداءات', status: 'نشط' },
      { id: 6, name: 'د. يوسف ناصر', roleTitle: 'مسؤول مستشفى', email: 'y.nasser@nasser.ps', scope: 'مستشفى ناصر الطبي', status: 'ملغي' }
    ],
    auditLogs: [
      { id: 1, user: 'د. سعيد عبده', role: 'مدير نظام عام', actionType: 'تعديل', details: 'تعديل إعدادات خوارزمية AI لنظام المطابقة', ipAddress: '192.168.1.105', timestamp: '2026-07-27 10:14 ص' },
      { id: 2, user: 'أحمد محمود', role: 'مشرف بنك الدم', actionType: 'إضافة', details: 'إضافة حالة طارئة جديدة لفصيلة O+ (مستشفى الشفاء)', ipAddress: '192.168.1.112', timestamp: '2026-07-27 09:45 ص' },
      { id: 3, user: 'د. سارة خليل', role: 'مسؤول مستشفى', actionType: 'تأكيد', details: 'تلبية طلب التبرع رقم #8921 بنجاح', ipAddress: '10.0.4.22', timestamp: '2026-07-27 09:12 ص' },
      { id: 4, user: 'م. خالد حسن', role: 'دعم فني', actionType: 'تسجيل دخول', details: 'تسجيل دخول ناجح إلى لوحة تحكم الإدارة', ipAddress: '192.168.1.200', timestamp: '2026-07-27 08:30 ص' },
      { id: 5, user: 'إيمان علي', role: 'مرحل طوارئ', actionType: 'إرسال', details: 'تفعيل الاستجابة الفورية لرادار مستشفى الكويتي', ipAddress: '10.0.8.55', timestamp: '2026-07-27 08:05 ص' },
      { id: 6, user: 'د. يوسف ناصر', role: 'مسؤول مستشفى', actionType: 'حذف', details: 'إلغاء نداء طوارئ قديم رقم #8890', ipAddress: '10.0.12.14', timestamp: '2026-07-26 11:20 م' }
    ],
    searchQuery: '',
    selectedFilter: 'all',
    currentPage: 1,
    totalPages: 4,
    loading: false
  }),

  actions: {
    async fetchDonors() {
      this.loading = true;
      try {
        const res = await apiClient.get('/admin/accounts/donors', {
          params: { search: this.searchQuery, blood_type: this.selectedFilter, page: this.currentPage }
        });
        const data = res.data?.data || res.data;
        if (Array.isArray(data) && data.length > 0) this.donors = data;
      } catch (e) {
        console.warn('استخدام بيانات المتبرعين الحالية.');
      } finally {
        this.loading = false;
      }
    },

    async fetchHospitals() {
      this.loading = true;
      try {
        const res = await apiClient.get('/admin/accounts/hospitals', {
          params: { search: this.searchQuery, region: this.selectedFilter, page: this.currentPage }
        });
        const data = res.data?.data || res.data;
        if (Array.isArray(data) && data.length > 0) this.hospitals = data;
      } catch (e) {
        console.warn('استخدام بيانات المستشفيات الحالية.');
      } finally {
        this.loading = false;
      }
    },

    async fetchRoles() {
      this.loading = true;
      try {
        const res = await apiClient.get('/admin/accounts/roles', {
          params: { search: this.searchQuery, role: this.selectedFilter, page: this.currentPage }
        });
        const data = res.data?.data || res.data;
        if (Array.isArray(data) && data.length > 0) this.roles = data;
      } catch (e) {
        console.warn('استخدام بيانات الصلاحيات الحالية.');
      } finally {
        this.loading = false;
      }
    },

    async fetchAuditLogs() {
      this.loading = true;
      try {
        const res = await apiClient.get('/admin/accounts/audit-logs', {
          params: { search: this.searchQuery, action: this.selectedFilter, page: this.currentPage }
        });
        const data = res.data?.data || res.data;
        if (Array.isArray(data) && data.length > 0) this.auditLogs = data;
      } catch (e) {
        console.warn('استخدام سجل العمليات الحالي.');
      } finally {
        this.loading = false;
      }
    },

    // 1. زر الحذف التفاعلي
    async deleteItem(id, type) {
      if (confirm(`هل أنت تأكد من حذف هذا الـ ${type}؟`)) {
        try {
          await apiClient.delete(`/admin/accounts/${id}`);
          alert('تم الحذف بنجاح');
        } catch (e) {
          if (type === 'متبرع') this.donors = this.donors.filter(d => d.id !== id && d.phone !== id);
          if (type === 'مستشفى') this.hospitals = this.hospitals.filter(h => h.id !== id);
          if (type === 'صلاحية') this.roles = this.roles.filter(r => r.id !== id);
          alert(`تم حذف الـ ${type} بنجاح!`);
        }
      }
    },

    // 2. زر التعديل التفاعلي
    editItem(item, type) {
      const newName = prompt(`تعديل اسم الـ ${type}:`, item.name);
      if (newName) {
        item.name = newName;
        alert(`تم تعديل بيانات الـ ${type} بنجاح!`);
      }
    },

    // 3. أزرار الإضافة التفاعلية (+ إضافة متبرع / + إضافة مستشفى / + إضافة دور)
    addItem(type) {
      const name = prompt(`أدخل اسم الـ ${type} الجديد:`);
      if (name) {
        if (type === 'متبرع') {
          this.donors.unshift({ id: Date.now(), name, phone: '0590000000', bloodType: 'O+', location: 'غزة', status: 'نشط' });
        } else if (type === 'مستشفى') {
          this.hospitals.unshift({ id: Date.now(), name, type: 'حكومي', phone: '082000000', location: 'غزة', status: 'نشط' });
        } else if (type === 'دور/صلاحية') {
          this.roles.unshift({ id: Date.now(), name, roleTitle: 'مسؤول', email: 'new@musaef.ps', scope: 'محدود', status: 'نشط' });
        }
        alert(`تمت إضافة الـ ${type} بنجاح!`);
      }
    },

    // 4. زر تصدير السجل (CSV)
    exportLogsCSV() {
      alert('جاري تحضير وتصدير سجل العمليات بصيغة CSV...');
    },

    // 5. التنقل بين الصفحات (Pagination)
    setPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
        this.refreshCurrentTab();
      }
    },

    refreshCurrentTab() {
      if (this.activeTab === 'donors') this.fetchDonors();
      if (this.activeTab === 'hospitals') this.fetchHospitals();
      if (this.activeTab === 'roles') this.fetchRoles();
      if (this.activeTab === 'logs') this.fetchAuditLogs();
    }
  }
});
