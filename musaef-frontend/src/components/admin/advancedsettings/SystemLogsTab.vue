<template>
  <div :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <!-- المربعات الإحصائية العلوية -->
    <div class="row g-3 mb-3 mb-md-4">
      <!-- سجل الأخطاء -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3 rounded-4 bg-white position-relative h-100">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fs-8 fw-semibold">{{ t('errorLogs') }}</span>
            <div class="icon-badge bg-danger-subtle rounded-circle p-2 d-flex align-items-center justify-content-center">
              <img :src="getIconUrl('solar_danger-triangle-linear.png')" alt="error log" width="20" height="20" />
            </div>
          </div>
          <h3 class="fw-black text-dark mb-1 fs-3">23</h3>
          <div class="text-danger fs-9 fw-bold">
            <span>+18.6% {{ t('fromYesterday') }}</span>
            <small class="d-block text-muted fw-normal">{{ t('todayErrors') }}</small>
          </div>
        </div>
      </div>

      <!-- سجل الأنشطة -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3 rounded-4 bg-white position-relative h-100">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fs-8 fw-semibold">{{ t('activityLogs') }}</span>
            <div class="icon-badge bg-purple-subtle rounded-circle p-2 d-flex align-items-center justify-content-center">
              <img :src="getIconUrl('Vector 23.png')" alt="activities log" width="20" height="20" />
            </div>
          </div>
          <h3 class="fw-black text-dark mb-1 fs-3">92.7%</h3>
          <div class="text-success fs-9 fw-bold">
            <span>+7.6%</span>
            <small class="d-block text-muted fw-normal">{{ t('todayActivities') }}</small>
          </div>
        </div>
      </div>

      <!-- سجل الدخول -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3 rounded-4 bg-white position-relative h-100">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fs-8 fw-semibold">{{ t('loginLogs') }}</span>
            <div class="icon-badge bg-danger-subtle rounded-circle p-2 d-flex align-items-center justify-content-center">
              <img :src="getIconUrl('Vector (3).png')" alt="login log" width="20" height="20" />
            </div>
          </div>
          <h3 class="fw-black text-dark mb-1 fs-3">1,248</h3>
          <div class="text-danger fs-9 fw-bold">
            <span>+14.5%</span>
            <small class="d-block text-muted fw-normal">{{ t('loginAttempts') }}</small>
          </div>
        </div>
      </div>

      <!-- حالة النظام -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3 rounded-4 bg-white position-relative h-100">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fs-8 fw-semibold">{{ t('systemStatus') }}</span>
            <div class="icon-badge bg-success-subtle rounded-circle p-2 d-flex align-items-center justify-content-center">
              <img :src="getIconUrl('icon-park-solid_correct.png')" alt="system status" width="20" height="20" />
            </div>
          </div>
          <h3 class="fw-black text-dark mb-1 fs-3">8,765</h3>
          <div class="text-success fs-9 fw-bold">
            <span>{{ t('normalStatus') }}</span>
            <small class="d-block text-muted fw-normal">{{ t('efficiencyNote') }}</small>
          </div>
        </div>
      </div>
    </div>

    <!-- جدول سجل الدخول + أدوات النظام -->
    <div class="row g-3 g-lg-4 mb-3 mb-md-4">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100">
          <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
            <img :src="getIconUrl('Vector (3).png')" alt="login icon" width="20" height="20" />
            <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('loginLogs') }}</h6>
          </div>
          <div class="table-responsive">
            <table class="table align-middle text-center border-0 fs-8 mb-0 min-w-table">
              <thead class="text-muted fw-normal border-bottom border-light">
                <tr>
                  <th class="py-2 text-start">{{ t('patientName') }}</th>
                  <th class="py-2">{{ t('timeLabel') }}</th>
                  <th class="py-2">{{ t('ipLabel') }}</th>
                  <th class="py-2 text-end">{{ t('statusLabel') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="log in loginLogs" :key="log.name + log.time">
                  <td class="py-3 fw-bold text-dark text-start text-nowrap">{{ translateText(log.name) }}</td>
                  <td class="py-3 text-muted fs-8 text-nowrap">{{ translateTime(log.time) }}</td>
                  <td class="py-3 dir-ltr text-muted fs-8 text-nowrap">{{ log.ip }}</td>
                  <td class="py-3 text-end text-nowrap">
                    <span class="badge rounded-pill px-3 py-1 fs-9 fw-bold" :class="isCompletedStatus(log.status) ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis'">
                      {{ translateStatus(log.status) }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- أدوات النظام التفاعلية -->
      <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100">
          <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
            <img :src="getIconUrl('mdi_gift-outline.png')" alt="gift icon" width="22" height="22" />
            <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('systemTools') }}</h6>
          </div>
          <div class="d-flex flex-column gap-2.5 gap-md-3">

            <div class="tool-card p-2.5 p-md-3 rounded-3 border bg-light-subtle d-flex align-items-center justify-content-between cursor-pointer" @click="runTool(t('backupToolTitle'))">
              <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
                <div class="tool-icon-box bg-primary-subtle text-primary rounded-3 flex-shrink-0">
                  <img :src="getIconUrl('tabler_cloud-up.png')" alt="backup" width="20" height="20" />
                </div>
                <div class="min-w-0">
                  <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('backupToolTitle') }}</span>
                  <small class="text-muted fs-9 text-truncate d-block">{{ t('backupToolDesc') }}</small>
                </div>
              </div>
              <span class="text-muted fs-8 flex-shrink-0">{{ currentLanguage === 'ar' ? '>' : '<' }}</span>
            </div>

            <div class="tool-card p-2.5 p-md-3 rounded-3 border bg-light-subtle d-flex align-items-center justify-content-between cursor-pointer" @click="runTool(t('perfToolTitle'))">
              <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
                <div class="tool-icon-box bg-primary-subtle text-primary rounded-3 flex-shrink-0">
                  <img :src="getIconUrl('line-md_gauge.png')" alt="performance" width="20" height="20" />
                </div>
                <div class="min-w-0">
                  <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('perfToolTitle') }}</span>
                  <small class="text-muted fs-9 text-truncate d-block">{{ t('perfToolDesc') }}</small>
                </div>
              </div>
              <span class="text-muted fs-8 flex-shrink-0">{{ currentLanguage === 'ar' ? '>' : '<' }}</span>
            </div>

            <div class="tool-card p-2.5 p-md-3 rounded-3 border bg-light-subtle d-flex align-items-center justify-content-between cursor-pointer" @click="runTool(t('servicesToolTitle'))">
              <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
                <div class="tool-icon-box bg-primary-subtle text-primary rounded-3 flex-shrink-0">
                  <img :src="getIconUrl('arcticons_toolbox.png')" alt="services" width="20" height="20" />
                </div>
                <div class="min-w-0">
                  <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('servicesToolTitle') }}</span>
                  <small class="text-muted fs-9 text-truncate d-block">{{ t('servicesToolDesc') }}</small>
                </div>
              </div>
              <span class="text-muted fs-8 flex-shrink-0">{{ currentLanguage === 'ar' ? '>' : '<' }}</span>
            </div>

            <div class="tool-card p-2.5 p-md-3 rounded-3 border bg-light-subtle d-flex align-items-center justify-content-between cursor-pointer" @click="runTool(t('logsToolTitle'))">
              <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
                <div class="tool-icon-box bg-danger-subtle text-danger rounded-3 flex-shrink-0">
                  <img :src="getIconUrl('solar_calendar-linear.png')" alt="logs" width="20" height="20" />
                </div>
                <div class="min-w-0">
                  <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('logsToolTitle') }}</span>
                  <small class="text-muted fs-9 text-truncate d-block">{{ t('logsToolDesc') }}</small>
                </div>
              </div>
              <span class="text-muted fs-8 flex-shrink-0">{{ currentLanguage === 'ar' ? '>' : '<' }}</span>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- سجل الأنشطة والإعدادات السريعة -->
    <div class="row g-3 g-lg-4 mb-3 mb-md-4">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100">
          <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
            <img :src="getIconUrl('Vector 23 (1).png')" alt="activity icon" width="22" height="22" />
            <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('activityLogs') }}</h6>
          </div>
          <div class="table-responsive">
            <table class="table align-middle text-center border-0 fs-8 mb-0 min-w-table">
              <thead class="text-muted fw-normal border-bottom border-light">
                <tr>
                  <th class="py-2 text-start">{{ t('timeLabel') }}</th>
                  <th class="py-2">{{ t('activityLabel') }}</th>
                  <th class="py-2">{{ t('userLabel') }}</th>
                  <th class="py-2 text-end">{{ t('moduleLabel') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="act in activityLogs" :key="act.activity + act.time">
                  <td class="py-2.5 text-muted fs-8 text-start text-nowrap">{{ translateTime(act.time) }}</td>
                  <td class="py-2.5 fw-bold text-dark fs-8 text-nowrap">{{ translateText(act.activity) }}</td>
                  <td class="py-2.5 text-muted fs-8 text-nowrap">{{ translateText(act.user) }}</td>
                  <td class="py-2.5 text-end text-nowrap">
                    <span class="badge bg-light text-dark border px-3 py-1.5 rounded-3 fs-9 font-normal">
                      {{ act.module }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- الإعدادات السريعة -->
      <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100">
          <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
            <img :src="getIconUrl('material-symbols_settings-outline (1).png')" alt="settings icon" width="22" height="22" />
            <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('quickSettings') }}</h6>
          </div>
          <div class="d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between p-2 flex-wrap gap-2">
              <div class="min-w-0">
                <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('maintMode') }}</span>
                <small class="text-muted fs-9 d-block text-truncate">{{ t('maintModeSub') }}</small>
              </div>
              <div class="form-check form-switch m-0" :class="currentLanguage === 'ar' ? 'ms-auto ms-sm-0' : 'me-auto me-sm-0'">
                <input class="form-check-input custom-switch" type="checkbox" v-model="settings.maintenance" />
              </div>
            </div>

            <div class="d-flex align-items-center justify-content-between p-2 flex-wrap gap-2">
              <div class="min-w-0">
                <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('selfReg') }}</span>
                <small class="text-muted fs-9 d-block text-truncate">{{ t('selfRegSub') }}</small>
              </div>
              <div class="form-check form-switch m-0" :class="currentLanguage === 'ar' ? 'ms-auto ms-sm-0' : 'me-auto me-sm-0'">
                <input class="form-check-input custom-switch" type="checkbox" v-model="settings.selfRegister" />
              </div>
            </div>

            <div class="d-flex align-items-center justify-content-between p-2 flex-wrap gap-2">
              <div class="min-w-0">
                <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('autoBackup') }}</span>
                <small class="text-muted fs-9 d-block text-truncate">{{ t('autoBackupSub') }}</small>
              </div>
              <div class="form-check form-switch m-0" :class="currentLanguage === 'ar' ? 'ms-auto ms-sm-0' : 'me-auto me-sm-0'">
                <input class="form-check-input custom-switch" type="checkbox" v-model="settings.autoBackup" />
              </div>
            </div>

            <div class="d-flex align-items-center justify-content-between p-2 flex-wrap gap-2">
              <div class="min-w-0">
                <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">{{ t('2faTitle') }}</span>
                <small class="text-muted fs-9 d-block text-truncate">{{ t('2faSub') }}</small>
              </div>
              <div class="form-check form-switch m-0" :class="currentLanguage === 'ar' ? 'ms-auto ms-sm-0' : 'me-auto me-sm-0'">
                <input class="form-check-input custom-switch" type="checkbox" v-model="settings.twoFactor" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- مؤشرات إدارة النظام -->
    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-4">
      <div class="d-flex align-items-center justify-content-start gap-2 mb-4">
        <img :src="getIconUrl('Vector 24.png')" alt="performance metrics" width="22" height="22" />
        <h5 class="fw-bold text-dark mb-0 fs-6">{{ t('metricsTitle') }}</h5>
      </div>

      <div class="row g-3 g-md-4 text-center align-items-center">
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="p-2">
            <div class="gauge-box mx-auto mb-3 position-relative" style="width: 140px; height: 110px;">
              <svg viewBox="0 0 100 80" class="w-100 h-100">
                <path d="M 15 65 A 38 38 0 1 1 85 65" fill="none" stroke="#e5e7eb" stroke-width="10" stroke-linecap="round"/>
                <path d="M 15 65 A 38 38 0 1 1 85 65" fill="none" stroke="#38A169" stroke-width="10" stroke-linecap="round" stroke-dasharray="160" stroke-dashoffset="105"/>
              </svg>
              <div class="position-absolute start-50 top-50 translate-middle fw-bold fs-4 text-dark mt-2">
                34%
              </div>
            </div>
            <h6 class="fw-bold text-dark fs-7 mb-1">{{ t('cpuUsage') }}</h6>
            <small class="text-success fs-8 d-block fw-semibold mb-1">{{ t('normalStat') }}</small>
            <small class="text-secondary fs-9 dir-ltr d-block">2.7 GHz \ 8 Core</small>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
          <div class="p-2">
            <div class="gauge-box mx-auto mb-3 position-relative" style="width: 140px; height: 110px;">
              <svg viewBox="0 0 100 80" class="w-100 h-100">
                <path d="M 15 65 A 38 38 0 1 1 85 65" fill="none" stroke="#e5e7eb" stroke-width="11" stroke-linecap="round"/>
                <path d="M 15 65 A 38 38 0 0 1 50 12" fill="none" stroke="#F97316" stroke-width="11" stroke-linecap="round"/>
              </svg>
              <div class="position-absolute start-50 top-50 translate-middle fw-bold fs-4 text-dark mt-2">
                62%
              </div>
            </div>
            <h6 class="fw-bold text-dark fs-7 mb-1">{{ t('ramUsage') }}</h6>
            <small class="text-warning-emphasis fs-8 d-block fw-semibold mb-1">{{ t('mediumStat') }}</small>
            <small class="text-secondary fs-9 dir-ltr d-block">9.8 GB \ 16GB</small>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
          <div class="p-2">
            <div class="gauge-box mx-auto mb-3 position-relative" style="width: 140px; height: 110px;">
              <svg viewBox="0 0 100 80" class="w-100 h-100">
                <path d="M 15 65 A 38 38 0 1 1 85 65" fill="none" stroke="#e5e7eb" stroke-width="11" stroke-linecap="round"/>
                <path d="M 15 65 A 38 38 0 0 1 58 13" fill="none" stroke="#38A169" stroke-width="11" stroke-linecap="round"/>
              </svg>
              <div class="position-absolute start-50 top-50 translate-middle fw-bold fs-4 text-dark mt-2">
                45%
              </div>
            </div>
            <h6 class="fw-bold text-dark fs-7 mb-1">{{ t('storageUsage') }}</h6>
            <small class="text-success fs-8 d-block fw-semibold mb-1">{{ t('normalStat') }}</small>
            <small class="text-secondary fs-9 dir-ltr d-block">225GB \ 500 GB</small>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
          <div class="p-2">
            <div class="gauge-box mx-auto mb-3 position-relative" style="width: 140px; height: 110px;">
              <svg viewBox="0 0 100 80" class="w-100 h-100">
                <path d="M 15 65 A 38 38 0 1 1 85 65" fill="none" stroke="#e5e7eb" stroke-width="11" stroke-linecap="round"/>
                <path d="M 15 65 A 38 38 0 1 1 85 65" fill="none" stroke="#38A169" stroke-width="11" stroke-linecap="round"/>
              </svg>
              <div class="position-absolute start-50 top-50 translate-middle fw-bold fs-5 text-dark mt-2">
                120ms
              </div>
            </div>
            <h6 class="fw-bold text-dark fs-7 mb-1">{{ t('latencyLabel') }}</h6>
            <small class="text-success fs-8 d-block fw-semibold mb-1">{{ t('excellentStat') }}</small>
            <small class="text-secondary fs-9 d-block">{{ t('avgLatency') }}</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

defineProps({
  loginLogs: Array,
  activityLogs: Array,
  settings: Object
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    errorLogs: 'سجل الأخطاء',
    fromYesterday: 'عن الأمس',
    todayErrors: 'أخطاء النظام اليوم',
    activityLogs: 'سجل الأنشطة',
    todayActivities: 'نشاطات النظام اليوم',
    loginLogs: 'سجل الدخول',
    loginAttempts: 'محاولات تسجيل الدخول',
    systemStatus: 'حالة النظام',
    normalStatus: 'تعمل بشكل طبيعي',
    efficiencyNote: 'جميع الأنظمة بكفاءة 99.98%',
    patientName: 'اسم المريض / المستخدم',
    timeLabel: 'الوقت',
    ipLabel: 'عنوان IP',
    statusLabel: 'الحالة',
    systemTools: 'أدوات النظام',
    backupToolTitle: 'إدارة النسخ الاحتياطية',
    backupToolDesc: 'إنشاء وإدارة النسخ الاحتياطية للبيانات',
    perfToolTitle: 'مراقبة الأداء',
    perfToolDesc: 'مراقبة أداء النظام والخدمات',
    servicesToolTitle: 'إدارة الخدمات',
    servicesToolDesc: 'إدارة خدمات النظام وحالتها',
    logsToolTitle: 'سجلات النظام',
    logsToolDesc: 'عرض وتحليل سجلات النظام الشاملة',
    activityLabel: 'النشاط',
    userLabel: 'المستخدم',
    moduleLabel: 'الوحدة',
    quickSettings: 'الإعدادات السريعة',
    maintMode: 'وضع الصيانة',
    maintModeSub: 'تفعيل وضع الصيانة للنظام',
    selfReg: 'التسجيل الذاتي',
    selfRegSub: 'السماح للمستخدمين بالتسجيل الذاتي',
    autoBackup: 'النسخ الاحتياطي تلقائي',
    autoBackupSub: 'تفعيل النسخ الاحتياطي اليومي التلقائي',
    '2faTitle': 'التحقق بخطوتين',
    '2faSub': 'تفعيل المصادقة الثنائية للمستخدمين',
    metricsTitle: 'مؤشرات أدارة النظام',
    cpuUsage: 'استخدام المعالج',
    ramUsage: 'استخدام الذاكرة',
    storageUsage: 'استخدام التخزين',
    latencyLabel: 'زمن الاستجابة',
    normalStat: 'طبيعي',
    mediumStat: 'متوسط',
    excellentStat: 'ممتاز',
    avgLatency: 'متوسط الاستجابة'
  },
  en: {
    errorLogs: 'Error Logs',
    fromYesterday: 'from yesterday',
    todayErrors: 'Today\'s system errors',
    activityLogs: 'Activity Logs',
    todayActivities: 'Today\'s system activities',
    loginLogs: 'Login Logs',
    loginAttempts: 'Login attempts',
    systemStatus: 'System Status',
    normalStatus: 'Operating Normally',
    efficiencyNote: 'All systems operating at 99.98%',
    patientName: 'Patient / User Name',
    timeLabel: 'Time',
    ipLabel: 'IP Address',
    statusLabel: 'Status',
    systemTools: 'System Tools',
    backupToolTitle: 'Backup Management',
    backupToolDesc: 'Create and manage system data backups',
    perfToolTitle: 'Performance Monitoring',
    perfToolDesc: 'Monitor system and service performance',
    servicesToolTitle: 'Service Management',
    servicesToolDesc: 'Manage system services and status',
    logsToolTitle: 'System Logs',
    logsToolDesc: 'View and analyze comprehensive system logs',
    activityLabel: 'Activity',
    userLabel: 'User',
    moduleLabel: 'Module',
    quickSettings: 'Quick Settings',
    maintMode: 'Maintenance Mode',
    maintModeSub: 'Enable maintenance mode for the system',
    selfReg: 'Self Registration',
    selfRegSub: 'Allow users to self-register accounts',
    autoBackup: 'Automatic Backup',
    autoBackupSub: 'Enable daily automatic backups',
    '2faTitle': 'Two-Factor Auth',
    '2faSub': 'Enable Two-Factor Authentication for users',
    metricsTitle: 'System Management Metrics',
    cpuUsage: 'CPU Usage',
    ramUsage: 'RAM Usage',
    storageUsage: 'Storage Usage',
    latencyLabel: 'Response Time',
    normalStat: 'Normal',
    mediumStat: 'Medium',
    excellentStat: 'Excellent',
    avgLatency: 'Average latency'
  }
};

const textTranslationMap = {
  // أسماء المستخدمين
  'ليلى المنصور': { ar: 'ليلى المنصور', en: 'Laila Al-Mansour' },
  'احمد حسن': { ar: 'احمد حسن', en: 'Ahmed Hassan' },
  'سلمى محمد': { ar: 'سلمى محمد', en: 'Salma Mohamed' },
  'محمود علي': { ar: 'محمود علي', en: 'Mahmoud Ali' },
  'مدير النظام': { ar: 'مدير النظام', en: 'System Admin' },
  'أحمد السوسي': { ar: 'أحمد السوسي', en: 'Ahmed Al-Susi' },
  'سارة الشهري': { ar: 'سارة الشهري', en: 'Sara Al-Shehri' },

  // الأنشطة
  'تحديث إعدادات النظام': { ar: 'تحديث إعدادات النظام', en: 'System Settings Update' },
  'إضافة مستخدم جديد': { ar: 'إضافة مستخدم جديد', en: 'New User Addition' },
  'تعديل بيانات مستشفى': { ar: 'تعديل بيانات مستشفى', en: 'Hospital Data Edit' },
  'إنشاء حملة تبرع جديدة': { ar: 'إنشاء حملة تبرع جديدة', en: 'New Campaign Created' },
  'تصدير تقرير تحليلات': { ar: 'تصدير تقرير تحليلات', en: 'Analytics Report Export' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const translateText = (text) => {
  if (!text) return '';
  const item = textTranslationMap[text];
  if (item) {
    return item[currentLanguage.value === 'en' ? 'en' : 'ar'];
  }
  return text;
};

const translateTime = (timeStr) => {
  if (!timeStr) return '';
  if (currentLanguage.value === 'en') {
    return timeStr.replace('ص', 'AM').replace('م', 'PM');
  } else {
    return timeStr.replace('AM', 'ص').replace('PM', 'م');
  }
};

const isCompletedStatus = (status) => {
  return status === 'مكتمل' || status === 'Completed';
};

const translateStatus = (status) => {
  if (currentLanguage.value === 'en') {
    if (status === 'مكتمل') return 'Completed';
    if (status === 'غير مكتمل') return 'Pending';
  } else {
    if (status === 'Completed') return 'مكتمل';
    if (status === 'Pending') return 'غير مكتمل';
  }
  return status;
};

const getIconUrl = (fileName) => {
  return new URL(`../../../assets/icons/${fileName}`, import.meta.url).href;
};

const runTool = (toolName) => {
  alert(currentLanguage.value === 'ar' ? `جاري تشغيل أداة: ${toolName}...` : `Running tool: ${toolName}...`);
};
</script>

<style scoped>
.fs-6 { font-size: 1.05rem; }
.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.fw-black { font-weight: 900; }

.min-w-table { min-width: 480px; }

.bg-purple-subtle { background-color: #f3e8ff !important; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-primary-subtle { background-color: #dbeafe !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.bg-light-subtle { background-color: #f8fafc !important; }

.tool-icon-box {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.custom-switch {
  width: 2.6em !important;
  height: 1.3em !important;
  cursor: pointer;
}

.custom-switch:checked {
  background-color: #16a34a !important;
  border-color: #16a34a !important;
}

.cursor-pointer { cursor: pointer; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
