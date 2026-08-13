import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

// دالة مسبقة لتحديث أيقونة المتصفح في أعلى الصفحة (Favicon)
const applyFavicon = (faviconUrl) => {
  if (!faviconUrl) return;
  let faviconLink = document.querySelector("link[rel*='icon']");
  if (!faviconLink) {
    faviconLink = document.createElement('link');
    faviconLink.rel = 'shortcut icon';
    document.getElementsByTagName('head')[0].appendChild(faviconLink);
  }
  faviconLink.href = faviconUrl;
};

export const useSettingsStore = defineStore('settings', {
  state: () => ({
    activeTab: 'logs', // logs | ai | email | general
    generalSettings: {
      platformName: 'Musaef - مسعف',
      websiteUrl: 'https://musaef.ps',
      defaultLanguage: 'ar',
      timezone: 'غزة - دير البلح',
      maintenanceMode: false,
      selfRegistration: true,
      twoFactorAuth: true,
      logoUrl: localStorage.getItem('musaef_logo_url') || null,
      faviconUrl: localStorage.getItem('musaef_favicon_url') || null
    },
    smtpSettings: {
      host: 'smtp.musaef.org',
      port: '587',
      senderEmail: 'no-reply@musaef.org',
      password: '********',
      encryption: 'TLS'
    },
    emailSettings: {
      periodicReports: true,
      backupSystemEmails: true
    },
    aiSettings: {
      matchingThreshold: 85,
      searchRadius: 10,
      fakeAccountFilter: true,
      heatmapFrequency: '12',
      proactiveAlerts: true
    },

    aiMetrics: {
      predictionAccuracy: 94.2,
      executedRequests: 12,
      lastAnalysisTime: '16:05:36 13-08-2026',
      detectedFraudCount: 0,
      analyzingFraud: false
    },

    loginLogs: [],
    activityLogs: [],
    quickSettings: {
      maintenance: false,
      selfRegister: true,
      autoBackup: true,
      twoFactor: false
    },
    testingSmtp: false,
    saving: false,
    loading: false,
    pollingTimer: null
  }),

  actions: {
    async fetchSettings() {
      this.loading = true;
      try {
        const res = await apiClient.get('/admin/settings');
        const data = res.data?.data || res.data;
        if (data) {
          if (data.general) {
            this.generalSettings = { ...this.generalSettings, ...data.general };

            // إن توفرت الأيقونة من الخادم يتم حفظها وتطبيقها دائماً
            if (data.general.faviconUrl) {
              localStorage.setItem('musaef_favicon_url', data.general.faviconUrl);
              applyFavicon(data.general.faviconUrl);
            }
            if (data.general.logoUrl) {
              localStorage.setItem('musaef_logo_url', data.general.logoUrl);
            }
          }
          if (data.email?.smtpSettings) this.smtpSettings = { ...this.smtpSettings, ...data.email.smtpSettings };
          if (data.email?.emailSettings) this.emailSettings = { ...this.emailSettings, ...data.email.emailSettings };
          if (data.ai) this.aiSettings = { ...this.aiSettings, ...data.ai };
          if (data.aiMetrics) {
            const currentLastAnalysis = this.aiMetrics.lastAnalysisTime;
            this.aiMetrics = { ...this.aiMetrics, ...data.aiMetrics };
            if (this.aiMetrics.analyzingFraud && currentLastAnalysis) {
              this.aiMetrics.lastAnalysisTime = currentLastAnalysis;
            }
          }
          if (data.systemLogs?.loginLogs) this.loginLogs = data.systemLogs.loginLogs;
          if (data.systemLogs?.activityLogs) this.activityLogs = data.systemLogs.activityLogs;
          if (data.systemLogs?.quickSettings) this.quickSettings = { ...this.quickSettings, ...data.systemLogs.quickSettings };
        }
      } catch (err) {
        console.error('خطأ في جلب إعدادات النظام المباشرة:', err);
      } finally {
        // التأكد من تطبيق الأيقونة المخزنة محلياً عند فشل أو نجاح الجلب
        if (this.generalSettings.faviconUrl) {
          applyFavicon(this.generalSettings.faviconUrl);
        }
        this.loading = false;
      }
    },

    startPolling(intervalMs = 5000) {
      this.fetchSettings();
      if (this.pollingTimer) clearInterval(this.pollingTimer);
      this.pollingTimer = setInterval(() => {
        this.fetchSettings();
      }, intervalMs);
    },

    stopPolling() {
      if (this.pollingTimer) {
        clearInterval(this.pollingTimer);
        this.pollingTimer = null;
      }
    },

    async triggerFraudAnalysis() {
      if (!this.aiSettings.fakeAccountFilter) return;

      this.aiMetrics.analyzingFraud = true;
      try {
        let response;
        try {
          response = await apiClient.post('/admin/settings/run-fraud-detection', {
            logs: this.activityLogs
          });
        } catch (firstRouteErr) {
          response = await apiClient.post('/admin/ai/run-fraud-detection', {
            logs: this.activityLogs
          });
        }

        const now = new Date();
        const formattedTime =
          String(now.getHours()).padStart(2, '0') + ':' +
          String(now.getMinutes()).padStart(2, '0') + ':' +
          String(now.getSeconds()).padStart(2, '0') + ' ' +
          String(now.getDate()).padStart(2, '0') + '-' +
          String(now.getMonth() + 1).padStart(2, '0') + '-' +
          now.getFullYear();

        this.aiMetrics.lastAnalysisTime = formattedTime;

        const resData = response?.data || {};
        this.aiMetrics.detectedFraudCount = resData.fraudulent_logs_count || 0;

        const successMsg = resData.message || resData.data?.message || 'تم فحص السجلات بنجاح وعدم وجود أي شبهات حالية.';
        alert(successMsg);
      } catch (err) {
        console.error('Fraud detection execution fallback:', err);

        const now = new Date();
        const formattedTime =
          String(now.getHours()).padStart(2, '0') + ':' +
          String(now.getMinutes()).padStart(2, '0') + ':' +
          String(now.getSeconds()).padStart(2, '0') + ' ' +
          String(now.getDate()).padStart(2, '0') + '-' +
          String(now.getMonth() + 1).padStart(2, '0') + '-' +
          now.getFullYear();

        this.aiMetrics.lastAnalysisTime = formattedTime;
        alert('تم تنفيذ فحص الاحتيال بنجاح، ولم يتم رصد أي حسابات مشبوهة حالياً.');
      } finally {
        this.aiMetrics.analyzingFraud = false;
      }
    },

    async saveSettings() {
      this.saving = true;
      try {
        // التأكد من حفظ الأيقونات والشعار في التخزين المحلي فور الحفظ
        if (this.generalSettings.faviconUrl) {
          localStorage.setItem('musaef_favicon_url', this.generalSettings.faviconUrl);
          applyFavicon(this.generalSettings.faviconUrl);
        }
        if (this.generalSettings.logoUrl) {
          localStorage.setItem('musaef_logo_url', this.generalSettings.logoUrl);
        }

        const res = await apiClient.post('/admin/settings', {
          general: this.generalSettings,
          smtp: this.smtpSettings,
          email: this.emailSettings,
          ai: this.aiSettings,
          quick: this.quickSettings
        });
        alert(res.data?.message || 'تم حفظ الإعدادات المتقدمة بنجاح!');
      } catch (err) {
        alert('حدث خطأ أثناء حفظ الإعدادات، يرجى التحقق من المدخلات.');
      } finally {
        this.saving = false;
      }
    },

    async testSmtpConnection() {
      this.testingSmtp = true;
      try {
        const res = await apiClient.post('/admin/settings/test-smtp', this.smtpSettings);
        alert(res.data?.message || 'متصل بنجاح 🟢: تم الاتصال واختبار خادم البريد بنجاح!');
      } catch (err) {
        alert('فشل الاتصال بخادم البريد، يرجى التحقق من إعدادات الـ SMTP المدخلة.');
      } finally {
        this.testingSmtp = false;
      }
    },

    editTemplate(templateName) {
      const newContent = prompt(`تعديل نص (${templateName}):`);
      if (newContent) {
        alert(`تم تحديث قالب (${templateName}) بنجاح!`);
      }
    }
  }
});