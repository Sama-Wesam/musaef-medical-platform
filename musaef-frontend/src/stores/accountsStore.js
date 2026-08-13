import { defineStore } from 'pinia';
import apiClient from '@/api/axios';
import { useLangStore } from '@/stores/langStore';

// دمج وتخزين التعديلات والإضافات محلياً لتستمر مع التحديث والـ Polling
const LOCAL_DONORS_KEY = 'musaef_local_donors';
const LOCAL_HOSPITALS_KEY = 'musaef_local_hospitals';
const LOCAL_ROLES_KEY = 'musaef_local_roles';

const getLocalData = (key) => {
  try {
    return JSON.parse(localStorage.getItem(key) || '[]');
  } catch (e) {
    return [];
  }
};

const saveLocalData = (key, data) => {
  try {
    localStorage.setItem(key, JSON.stringify(data));
  } catch (e) {
    console.error(`Error saving ${key} to LocalStorage:`, e);
  }
};

// بيانات افتراضية لأدوار وصلاحيات النظام
const defaultRoles = [
  {
    id: 1,
    name: 'مدير النظام - Super Admin',
    role: 'مشرف بنك الدم',
    email: 'admin@musaef.com',
    accessLevel: 'الوصول الكامل',
    status: 'نشط'
  }
];

// سجل عمليات محاكي افتراضي لملء جدول سجل العمليات بالكامل
const defaultAuditLogs = [
  {
    id: 1,
    user: 'د. سعيد عبده',
    role: 'مدير نظام عام',
    actionType: 'تعديل',
    details: 'تعديل إعدادات خوارزمية AI لنظام المطابقة',
    ipAddress: '192.168.1.105',
    timestamp: '2026-08-13 14:32 ص'
  },
  {
    id: 2,
    user: 'أحمد محمود',
    role: 'مشرف بنك الدم',
    actionType: 'إضافة',
    details: 'إضافة حالة طارئة جديدة لفصيلة O+ (مستشفى الشفاء)',
    ipAddress: '192.168.1.112',
    timestamp: '2026-08-13 13:15 ص'
  },
  {
    id: 3,
    user: 'د. سارة خليل',
    role: 'مسؤول مستشفى',
    actionType: 'تأكيد',
    details: 'تلبية طلب التبرع رقم #8921 بنجاح',
    ipAddress: '192.168.2.45',
    timestamp: '2026-08-13 11:50 ص'
  },
  {
    id: 4,
    user: 'م. خالد حسن',
    role: 'دعم فني وتقني',
    actionType: 'تسجيل دخول',
    details: 'تسجيل دخول ناجح إلى لوحة تحكم الإدارة',
    ipAddress: '10.0.0.12',
    timestamp: '2026-08-13 09:10 ص'
  },
  {
    id: 5,
    user: 'إيمان علي',
    role: 'مرحل طوارئ',
    actionType: 'تعديل',
    details: 'تفعيل الاستجابة الفورية لرادار مستشفى الكويتي',
    ipAddress: '192.168.1.88',
    timestamp: '2026-08-12 18:22 م'
  },
  {
    id: 6,
    user: 'د. يوسف ناصر',
    role: 'مسؤول مستشفى',
    actionType: 'حذف',
    details: 'إلغاء نداء طوارئ قديم رقم #8890',
    ipAddress: '192.168.3.14',
    timestamp: '2026-08-12 15:04 م'
  }
];

export const useAccountsStore = defineStore('accounts', {
  state: () => {
    const storedRoles = getLocalData(LOCAL_ROLES_KEY);
    return {
      activeTab: 'logs', // donors | hospitals | roles | logs

      donors: getLocalData(LOCAL_DONORS_KEY),
      deletedDonorIds: [], 

      hospitals: getLocalData(LOCAL_HOSPITALS_KEY),
      deletedHospitalIds: [],

      roles: storedRoles.length > 0 ? storedRoles : defaultRoles,
      deletedRoleIds: [],

      auditLogs: defaultAuditLogs,

      searchQuery: '',
      selectedFilter: 'all',
      currentPage: 1,
      totalPages: 1,
      loading: false,
      pollingTimer: null
    };
  },

  actions: {
    // ------------------------------------------------------------------------
    // 1. جلب البيانات المباشرة من الـ API ودمجها مع البيانات المحلية
    // ------------------------------------------------------------------------
    async fetchDonors() {
      this.loading = true;
      try {
        const res = await apiClient.get('/admin/accounts/donors', {
          params: { search: this.searchQuery, blood_type: this.selectedFilter, page: this.currentPage }
        });
        const data = res.data?.data || res.data;
        let fetchedDonors = [];

        if (Array.isArray(data)) {
          fetchedDonors = data;
        } else if (data?.donors) {
          fetchedDonors = data.donors;
          this.totalPages = data.last_page || 1;
        }

        const localDonors = getLocalData(LOCAL_DONORS_KEY);
        const mergedDonors = [...localDonors];

        fetchedDonors.forEach(apiDonor => {
          const apiId = apiDonor.id || apiDonor.phone;
          const existsLocally = mergedDonors.some(d => (d.id || d.phone) === apiId);
          if (!existsLocally) {
            mergedDonors.push(apiDonor);
          }
        });

        this.donors = mergedDonors.filter(d => !this.deletedDonorIds.includes(d.id || d.phone));
      } catch (e) {
        console.error('خطأ في جلب بيانات المتبرعين المباشرة:', e);
        const localDonors = getLocalData(LOCAL_DONORS_KEY);
        if (localDonors.length > 0) {
          this.donors = localDonors.filter(d => !this.deletedDonorIds.includes(d.id || d.phone));
        }
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
        let fetchedHospitals = [];

        if (Array.isArray(data)) {
          fetchedHospitals = data;
        } else if (data?.hospitals) {
          fetchedHospitals = data.hospitals;
          this.totalPages = data.last_page || 1;
        }

        const localHospitals = getLocalData(LOCAL_HOSPITALS_KEY);
        const mergedHospitals = [...localHospitals];

        fetchedHospitals.forEach(apiHosp => {
          const index = mergedHospitals.findIndex(h => h.id === apiHosp.id);
          if (index === -1) {
            mergedHospitals.push(apiHosp);
          } else {
            mergedHospitals[index] = { ...apiHosp, ...mergedHospitals[index] };
          }
        });

        this.hospitals = mergedHospitals.filter(h => !this.deletedHospitalIds.includes(h.id));
      } catch (e) {
        console.error('خطأ في جلب بيانات المستشفيات المباشرة:', e);
        const localHospitals = getLocalData(LOCAL_HOSPITALS_KEY);
        if (localHospitals.length > 0) {
          this.hospitals = localHospitals.filter(h => !this.deletedHospitalIds.includes(h.id));
        }
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
        let fetchedRoles = [];

        if (Array.isArray(data)) {
          fetchedRoles = data;
        } else if (data?.roles) {
          fetchedRoles = data.roles;
          this.totalPages = data.last_page || 1;
        }

        const localRoles = getLocalData(LOCAL_ROLES_KEY);
        const mergedRoles = localRoles.length > 0 ? [...localRoles] : [...this.roles];

        fetchedRoles.forEach(apiRole => {
          const index = mergedRoles.findIndex(r => r.id === apiRole.id);
          if (index === -1) {
            mergedRoles.push(apiRole);
          } else {
            mergedRoles[index] = { ...apiRole, ...mergedRoles[index] };
          }
        });

        this.roles = mergedRoles.filter(r => !this.deletedRoleIds.includes(r.id));
        saveLocalData(LOCAL_ROLES_KEY, this.roles);
      } catch (e) {
        console.error('خطأ في جلب بيانات الصلاحيات المباشرة:', e);
        const localRoles = getLocalData(LOCAL_ROLES_KEY);
        if (localRoles.length > 0) {
          this.roles = localRoles.filter(r => !this.deletedRoleIds.includes(r.id));
        }
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
        if (Array.isArray(data) && data.length > 0) {
          this.auditLogs = data;
        } else if (data?.logs && data.logs.length > 0) {
          this.auditLogs = data.logs;
          this.totalPages = data.last_page || 1;
        } else if (!this.auditLogs || this.auditLogs.length === 0) {
          this.auditLogs = defaultAuditLogs;
        }
      } catch (e) {
        console.error('خطأ في جلب بيانات سجل العمليات المباشر، جاري استخدام سجل البيانات الافتراضي:', e);
        if (!this.auditLogs || this.auditLogs.length === 0) {
          this.auditLogs = defaultAuditLogs;
        }
      } finally {
        this.loading = false;
      }
    },

    // ------------------------------------------------------------------------
    // 2. آلية الاستطلاع المباشر (Polling)
    // ------------------------------------------------------------------------
    startPolling(intervalMs = 5000) {
      this.refreshCurrentTab();

      if (this.pollingTimer) clearInterval(this.pollingTimer);

      this.pollingTimer = setInterval(() => {
        this.refreshCurrentTab();
      }, intervalMs);
    },

    stopPolling() {
      if (this.pollingTimer) {
        clearInterval(this.pollingTimer);
        this.pollingTimer = null;
      }
    },

    refreshCurrentTab() {
      if (this.activeTab === 'donors') this.fetchDonors();
      if (this.activeTab === 'hospitals') this.fetchHospitals();
      if (this.activeTab === 'roles') this.fetchRoles();
      if (this.activeTab === 'logs') this.fetchAuditLogs();
    },

    // ------------------------------------------------------------------------
    // 3. عمليات الذكاء الاصطناعي (AI Fraud Detection & Review)
    // ------------------------------------------------------------------------
    async analyzeHospitalFraud(hospitalId) {
      try {
        const response = await apiClient.post('/admin/fraud/analyze-hospital', {
          hospital_id: hospitalId,
          simulated_units: 50
        });
        return response.data;
      } catch (error) {
        console.error('Error analyzing hospital fraud:', error);
        return null;
      }
    },

    async reviewDonorWithAi(donor) {
      const langStore = useLangStore();
      const isEn = langStore.currentLang === 'en';
      const targetDonor = this.donors.find(d => (d.id || d.phone) === (donor.id || donor.phone)) || donor;

      try {
        await apiClient.post('/admin/fraud/review-account', {
          account_id: targetDonor.id,
          account_type: 'donor',
          action: 're_evaluate'
        });

        await this.fetchDonors();

        const alertMsg = isEn
          ? `AI Re-evaluation Completed for ${targetDonor.name}!`
          : `تمت مراجعة الحساب بواسطة الذكاء الاصطناعي بنجاح للمتبرع: ${targetDonor.name}!`;

        alert(alertMsg);
      } catch (error) {
        console.error('Error reviewing donor with AI:', error);
      }
    },

    // ------------------------------------------------------------------------
    // 4. العمليات الإدارية للمستشفيات، المتبرعين، والأدوار والصلاحيات
    // ------------------------------------------------------------------------
    async addRole(roleData) {
      const newRole = {
        id: Date.now(),
        name: roleData.name || 'مستخدم جديد',
        role: roleData.role || 'مشرف بنك الدم',
        email: roleData.email || 'user@musaef.com',
        accessLevel: roleData.accessLevel || 'الوصول الكامل',
        status: roleData.status || 'نشط'
      };

      this.roles.unshift(newRole);
      saveLocalData(LOCAL_ROLES_KEY, this.roles);

      try {
        await apiClient.post('/admin/accounts/roles', newRole);
      } catch (e) {
        console.error('API role creation failed, saved locally.', e);
      }
    },

    async updateRole(roleData) {
      const targetId = roleData.id;
      const index = this.roles.findIndex(r => r.id === targetId);

      if (index !== -1) {
        this.roles[index] = {
          ...this.roles[index],
          ...roleData
        };
      }

      saveLocalData(LOCAL_ROLES_KEY, this.roles);

      try {
        await apiClient.put(`/admin/accounts/roles/${targetId}`, roleData);
      } catch (e) {
        console.error('API role update failed, updated locally.', e);
      }
    },

    async deleteRole(id) {
      const langStore = useLangStore();
      const isEn = langStore.currentLang === 'en';
      const confirmMsg = isEn ? 'Are you sure you want to delete this role/permission?' : 'هل أنت تأكد من حذف هذا الدور / الصلاحية؟';

      if (confirm(confirmMsg)) {
        this.deletedRoleIds.push(id);
        this.roles = this.roles.filter(r => r.id !== id);
        saveLocalData(LOCAL_ROLES_KEY, this.roles);

        try {
          await apiClient.delete(`/admin/accounts/roles/${id}`);
        } catch (e) {
          console.error('Error deleting role from API, deleted locally.', e);
        }
      }
    },

    async updateHospital(hospitalData) {
      const targetId = hospitalData.id;
      const index = this.hospitals.findIndex(h => h.id === targetId);

      if (index !== -1) {
        this.hospitals[index] = {
          ...this.hospitals[index],
          ...hospitalData
        };
      }

      saveLocalData(LOCAL_HOSPITALS_KEY, this.hospitals);

      try {
        await apiClient.put(`/admin/accounts/hospitals/${targetId}`, hospitalData);
      } catch (e) {
        console.error('API hospital update failed, updated locally.', e);
      }
    },

    async addHospital(hospitalData) {
      const newHospital = {
        id: Date.now(),
        name: hospitalData.name || 'مستشفى جديد',
        type: hospitalData.type || 'حكومي',
        phone: hospitalData.phone || '0590000000',
        location: hospitalData.location || 'غزة - الرمال',
        status: 'active'
      };

      this.hospitals.unshift(newHospital);
      saveLocalData(LOCAL_HOSPITALS_KEY, this.hospitals);

      try {
        await apiClient.post(`/admin/accounts/hospitals`, newHospital);
      } catch (e) {
        console.error('API hospital creation failed, saved locally.', e);
      }
    },

    async updateDonor(donorData) {
      const targetId = donorData.id;
      const index = this.donors.findIndex(d => (d.id || d.phone) === targetId);

      if (index !== -1) {
        this.donors[index] = {
          ...this.donors[index],
          name: donorData.name,
          phone: donorData.phone,
          bloodType: donorData.bloodType,
          blood_type: donorData.bloodType
        };
      }

      saveLocalData(LOCAL_DONORS_KEY, this.donors);

      try {
        await apiClient.put(`/admin/accounts/${targetId}`, {
          name: donorData.name,
          phone: donorData.phone,
          blood_type: donorData.bloodType
        });
      } catch (e) {
        console.error('API update failed, updated locally.', e);
      }
    },

    async addDonor(donorData) {
      const newDonor = {
        id: donorData.id || Date.now(),
        name: donorData.name || 'متبرع جديد',
        phone: donorData.phone || '—',
        bloodType: donorData.bloodType || 'O+',
        blood_type: donorData.bloodType || 'O+',
        activity_score: 85,
        status: 'active_ai'
      };

      this.donors.unshift(newDonor);
      saveLocalData(LOCAL_DONORS_KEY, this.donors);

      try {
        await apiClient.post(`/admin/accounts`, newDonor);
      } catch (e) {
        console.error('API creation failed, saved locally.', e);
      }
    },

    async deleteDonor(id) {
      const langStore = useLangStore();
      const isEn = langStore.currentLang === 'en';
      const confirmMsg = isEn ? 'Are you sure you want to delete this donor?' : 'هل أنت تأكد من حذف هذا المتبرع؟';

      if (confirm(confirmMsg)) {
        this.deletedDonorIds.push(id);
        this.donors = this.donors.filter(d => (d.id || d.phone) !== id);
        saveLocalData(LOCAL_DONORS_KEY, this.donors);

        try {
          await apiClient.delete(`/admin/accounts/${id}`);
        } catch (e) {
          console.error('Error deleting item from API, deleted locally.', e);
        }
      }
    },

    async deleteItem(id, type) {
      if (type === 'donor') {
        return this.deleteDonor(id);
      }
      if (type === 'role') {
        return this.deleteRole(id);
      }
      const langStore = useLangStore();
      const isEn = langStore.currentLang === 'en';
      const confirmMsg = isEn ? `Are you sure you want to delete this ${type}?` : `هل أنت تأكد من حذف هذا الـ ${type}؟`;

      if (confirm(confirmMsg)) {
        if (type === 'hospitals' || type === 'مستشفى' || type === 'Hospital') {
          this.deletedHospitalIds.push(id);
          this.hospitals = this.hospitals.filter(h => h.id !== id);
          saveLocalData(LOCAL_HOSPITALS_KEY, this.hospitals);
        }
        try {
          await apiClient.delete(`/admin/accounts/${id}`);
        } catch (e) {
          console.error('Error deleting item:', e);
        }
      }
    },

    // ------------------------------------------------------------------------
    // 5. أدوات التصدير والتصفح (CSV & Pagination)
    // ------------------------------------------------------------------------
    exportLogsCSV() {
      const langStore = useLangStore();
      const isEn = langStore.currentLang === 'en';

      const logsToExport = this.auditLogs && this.auditLogs.length ? this.auditLogs : defaultAuditLogs;

      if (!logsToExport.length) {
        alert(isEn ? 'No data available for export.' : 'لا توجد بيانات متاحة للتصدير.');
        return;
      }

      const headers = isEn
        ? ['ID', 'User', 'Role', 'Action Type', 'Details', 'IP Address', 'Timestamp']
        : ['م', 'المستخدم', 'الدور / الصلاحية', 'نوع العملية', 'تفاصيل الإجراء', 'عنوان IP', 'الوقت والتاريخ'];

      const rows = logsToExport.map(log => [
        log.id,
        `"${log.user || ''}"`,
        `"${log.role || ''}"`,
        `"${log.actionType || ''}"`,
        `"${log.details || ''}"`,
        `"${log.ipAddress || ''}"`,
        `"${log.timestamp || ''}"`
      ]);

      const csvContent = '\uFEFF' + [headers.join(','), ...rows.map(e => e.join(','))].join('\r\n');
      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const link = document.createElement('a');
      const url = URL.createObjectURL(blob);

      link.setAttribute('href', url);
      link.setAttribute('download', `musaef_audit_logs_${new Date().toISOString().slice(0, 10)}.csv`);
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },

    setPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
        this.refreshCurrentTab();
      }
    }
  }
});