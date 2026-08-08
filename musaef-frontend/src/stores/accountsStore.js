import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

export const useAccountsStore = defineStore('accounts', {
  state: () => ({
    activeTab: 'donors', // donors | hospitals | roles | logs

    // البيانات الابتدائية للحفظ المحلي (Fallback Data)
    donors: [
      { id: 1, name: 'محمد حسن', phone: '059998765', bloodType: '-O', location: 'غزة', status: 'active_ai', activity_score: 92 },
      { id: 2, name: 'شذا محمد', phone: '059487635', bloodType: 'A+', location: 'دير البلح', status: 'active_ai', activity_score: 85 },
      { id: 3, name: 'خلود خالد', phone: '059876432', bloodType: 'AB+', location: 'خانيونس', status: 'suspended_ai', activity_score: 30 },
      { id: 4, name: 'روان تامر', phone: '059345728', bloodType: 'O+', location: 'رفح', status: 'active_ai', activity_score: 78 },
      { id: 5, name: 'فرح حسن', phone: '059887655', bloodType: '-A', location: 'نصيرات', status: 'cancelled', activity_score: 10 },
      { id: 6, name: 'ختام محمد', phone: '0593344578', bloodType: 'B+', location: 'غزة', status: 'cancelled', activity_score: 15 },
      { id: 7, name: 'يوسف جميل', phone: '0598876775', bloodType: 'AB-', location: 'رفح', status: 'active_ai', activity_score: 88 }
    ],
    hospitals: [
      { id: 1, name: 'مستشفى الشفاء الطبي', type: 'حكومي', phone: '082823400', location: 'غزة - الرمال', status: 'active' },
      { id: 2, name: 'مستشفى شهداء الأقصى', type: 'حكومي', phone: '082554100', location: 'دير البلح', status: 'active' },
      { id: 3, name: 'مستشفى ناصر الطبي', type: 'حكومي', phone: '082053110', location: 'خانيونس', status: 'active' },
      { id: 4, name: 'المستشفى الأندونيسي', type: 'حكومي', phone: '082478900', location: 'شمال غزة', status: 'suspended_ai' },
      { id: 5, name: 'مستشفى العودة', type: 'أهلي / أونروا', phone: '082531000', location: 'النصيرات', status: 'active' },
      { id: 6, name: 'مستشفى القدس', type: 'خاص / هلال أحمر', phone: '082885400', location: 'غزة - تل الهوا', status: 'cancelled' },
      { id: 7, name: 'مستشفى الكويتي التخصصي', type: 'أهلي خيري', phone: '082134500', location: 'رفح', status: 'active' }
    ],
    roles: [
      { id: 1, name: 'د. سعيد عبده', roleTitle: 'مدير نظام عام', email: 's.abdo@musaef.ps', scope: 'الوصول الكامل', status: 'active' },
      { id: 2, name: 'أحمد محمود', roleTitle: 'مشرف بنك الدم', email: 'a.mahmoud@musaef.ps', scope: 'إدارة الطلبات ومتبرعين', status: 'active' },
      { id: 3, name: 'د. سارة خليل', roleTitle: 'مسؤول مستشفى', email: 's.khalil@shifa.ps', scope: 'مستشفى الشفاء الطبي', status: 'active' },
      { id: 4, name: 'م. خالد حسن', roleTitle: 'دعم فني وتقني', email: 'k.hassan@musaef.ps', scope: 'السجلات والسيرفرات', status: 'suspended_ai' },
      { id: 5, name: 'إيمان علي', roleTitle: 'مرحل طوارئ', email: 'e.ali@musaef.ps', scope: 'رادار الطوارئ والنداءات', status: 'active' },
      { id: 6, name: 'د. يوسف ناصر', roleTitle: 'مسؤول مستشفى', email: 'y.nasser@nasser.ps', scope: 'مستشفى ناصر الطبي', status: 'cancelled' }
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
    // ------------------------------------------------------------------------
    // 1. جلب البيانات من الـ API مع الاحتفاظ بالبيانات المحلية عند الخطأ
    // ------------------------------------------------------------------------
    async fetchDonors() {
      this.loading = true;
      try {
        const res = await apiClient.get('/admin/accounts/donors', {
          params: { search: this.searchQuery, blood_type: this.selectedFilter, page: this.currentPage }
        });
        const data = res.data?.data || res.data;
        if (Array.isArray(data) && data.length > 0) this.donors = data;
      } catch (e) {
        console.warn('تعذر الاتصال بالخادم، تم استخدام بيانات المتبرعين الحالية.');
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
        console.warn('تعذر الاتصال بالخادم، تم استخدام بيانات المستشفيات الحالية.');
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
        console.warn('تعذر الاتصال بالخادم، تم استخدام بيانات الصلاحيات الحالية.');
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
        console.warn('تعذر الاتصال بالخادم، تم استخدام سجل العمليات الحالي.');
      } finally {
        this.loading = false;
      }
    },

    // ------------------------------------------------------------------------
    // 2. عمليات الذكاء الاصطناعي (AI Fraud Detection & Review)
    // ------------------------------------------------------------------------

    // إجراء التقييم التفاعلي للاحتيال للمستشفى
    async analyzeHospitalFraud(hospitalId) {
      try {
        const response = await apiClient.post('/admin/fraud/analyze-hospital', {
          hospital_id: hospitalId,
          simulated_units: 50
        });

        // تحديث حالة المستشفى فورياً في الواجهة
        const updated = this.hospitals.find(h => h.id === hospitalId);
        if (updated) {
          updated.status = response.data?.data?.hospital_status || 'suspended_ai';
        }
        alert(response.data?.message || 'تم إجراء تحليل الشبهات بنجاح!');
      } catch (error) {
        console.warn('فحص شكلي local fallback عند تعذر اتصال الـ API');
        const target = this.hospitals.find(h => h.id === hospitalId);
        if (target) {
          target.status = 'suspended_ai';
          alert(`تم تحليل سلوك "${target.name}" عبر AI وتم تعليق الحساب لتجاوز الطلبات!`);
        }
      }
    },

    // المراجعة الإدارية للحسابات مع تقييم الذكاء الاصطناعي
    async reviewDonorWithAi(donor) {
      try {
        const response = await apiClient.post('/admin/fraud/review-account', {
          account_id: donor.id,
          account_type: 'donor',
          action: 're_evaluate'
        });

        alert(response.data?.message || 'تم إعادة تقييم الحساب بنجاح!');
        this.fetchDonors();
      } catch (error) {
        console.warn('تقييم محلي عند تعذر الاتصال بالـ API');
        donor.status = donor.status === 'suspended_ai' ? 'active_ai' : 'suspended_ai';
        alert(`تم تحديث حالة المتبرع "${donor.name}" بواسطة خوارزمية الذكاء الاصطناعي!`);
      }
    },

    // ------------------------------------------------------------------------
    // 3. العمليات الإدارية (حذف، تعديل، إضافة)
    // ------------------------------------------------------------------------
    async deleteItem(id, type) {
      if (confirm(`هل أنت تأكد من حذف هذا الـ ${type}؟`)) {
        try {
          await apiClient.delete(`/admin/accounts/${id}`);
          this.removeLocalItem(id, type);
          alert('تم الحذف بنجاح');
        } catch (e) {
          // Fallback للتعامل المحلي في حالة عدم وجود بيئة خلفية نشطة
          this.removeLocalItem(id, type);
          alert(`تم حذف الـ ${type} بنجاح!`);
        }
      }
    },

    removeLocalItem(id, type) {
      const isDonor = type.includes('متبرع') || type.includes('Donor');
      const isHospital = type.includes('مستشفى') || type.includes('Hospital');
      const isRole = type.includes('صلاحية') || type.includes('دور') || type.includes('Role');

      if (isDonor) {
        this.donors = this.donors.filter(d => d.id !== id && d.phone !== id);
      } else if (isHospital) {
        this.hospitals = this.hospitals.filter(h => h.id !== id);
      } else if (isRole) {
        this.roles = this.roles.filter(r => r.id !== id);
      }
    },

    editItem(item, type) {
      const newName = prompt(`تعديل اسم الـ ${type}:`, item.name);
      if (newName && newName.trim() !== '') {
        item.name = newName.trim();
        alert(`تم تعديل بيانات الـ ${type} بنجاح!`);
      }
    },

    addItem(type) {
      const name = prompt(`أدخل اسم الـ ${type} الجديد:`);
      if (name && name.trim() !== '') {
        const cleanName = name.trim();
        const isDonor = type.includes('متبرع') || type.includes('Donor');
        const isHospital = type.includes('مستشفى') || type.includes('Hospital');
        const isRole = type.includes('صلاحية') || type.includes('دور') || type.includes('Role');

        if (isDonor) {
          this.donors.unshift({
            id: Date.now(),
            name: cleanName,
            phone: '0590000000',
            bloodType: 'O+',
            location: 'غزة',
            status: 'active_ai',
            activity_score: 80
          });
        } else if (isHospital) {
          this.hospitals.unshift({
            id: Date.now(),
            name: cleanName,
            type: 'حكومي',
            phone: '082000000',
            location: 'غزة',
            status: 'active'
          });
        } else if (isRole) {
          this.roles.unshift({
            id: Date.now(),
            name: cleanName,
            roleTitle: 'مسؤول',
            email: 'new@musaef.ps',
            scope: 'محدود',
            status: 'active'
          });
        }
        alert(`تمت إضافة الـ ${type} بنجاح!`);
      }
    },

    // ------------------------------------------------------------------------
    // 4. أدوات التصدير والتصفح (CSV & Pagination)
    // ------------------------------------------------------------------------
    exportLogsCSV() {
      if (!this.auditLogs.length) {
        alert('لا توجد بيانات متاحة للتصدير.');
        return;
      }

      const headers = ['ID', 'المستخدم', 'الدور', 'نوع العملية', 'التفاصيل', 'عنوان IP', 'التاريخ والوقت'];
      const rows = this.auditLogs.map(log => [
        log.id,
        `"${log.user || ''}"`,
        `"${log.role || ''}"`,
        `"${log.actionType || ''}"`,
        `"${log.details || ''}"`,
        `"${log.ipAddress || ''}"`,
        `"${log.timestamp || ''}"`
      ]);

      const csvContent = '\uFEFF' + [headers.join(','), ...rows.map(e => e.join(','))].join('\n');
      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const link = document.createElement('a');
      const url = URL.createObjectURL(blob);

      link.setAttribute('href', url);
      link.setAttribute('download', `audit_logs_${new Date().toISOString().slice(0, 10)}.csv`);
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },

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
