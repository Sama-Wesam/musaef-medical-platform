<template>
  <div class="system-logs-section dir-rtl">
    <!-- المربعات الإحصائية العلوية -->
    <div class="row g-3 mb-3 mb-md-4">
      <!-- سجل الأخطاء -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end h-100 position-relative">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fs-8 fw-semibold">سجل الأخطاء</span>
            <div class="icon-badge bg-danger-subtle rounded-circle p-2 d-flex align-items-center justify-content-center">
              <img :src="getIconUrl('solar_danger-triangle-linear.png')" alt="error log" width="20" height="20" />
            </div>
          </div>
          <h3 class="fw-black text-dark mb-1 fs-3">23</h3>
          <div class="text-danger fs-9 fw-bold">
            <span>+18.6% عن الأمس</span>
            <small class="d-block text-muted fw-normal">أخطاء النظام اليوم</small>
          </div>
        </div>
      </div>

      <!-- سجل الأنشطة -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end h-100 position-relative">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fs-8 fw-semibold">سجل الأنشطة</span>
            <div class="icon-badge bg-purple-subtle rounded-circle p-2 d-flex align-items-center justify-content-center">
              <img :src="getIconUrl('Vector 23.png')" alt="activities log" width="20" height="20" />
            </div>
          </div>
          <h3 class="fw-black text-dark mb-1 fs-3">92.7%</h3>
          <div class="text-success fs-9 fw-bold">
            <span>+7.6%</span>
            <small class="d-block text-muted fw-normal">نشاطات النظام اليوم</small>
          </div>
        </div>
      </div>

      <!-- سجل الدخول -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end h-100 position-relative">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fs-8 fw-semibold">سجل الدخول</span>
            <div class="icon-badge bg-danger-subtle rounded-circle p-2 d-flex align-items-center justify-content-center">
              <img :src="getIconUrl('Vector (3).png')" alt="login log" width="20" height="20" />
            </div>
          </div>
          <h3 class="fw-black text-dark mb-1 fs-3">1,248</h3>
          <div class="text-danger fs-9 fw-bold">
            <span>+14.5%</span>
            <small class="d-block text-muted fw-normal">محاولات تسجيل الدخول</small>
          </div>
        </div>
      </div>

      <!-- حالة النظام -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end h-100 position-relative">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fs-8 fw-semibold">حالة النظام</span>
            <div class="icon-badge bg-success-subtle rounded-circle p-2 d-flex align-items-center justify-content-center">
              <img :src="getIconUrl('icon-park-solid_correct.png')" alt="system status" width="20" height="20" />
            </div>
          </div>
          <h3 class="fw-black text-dark mb-1 fs-3">8,765</h3>
          <div class="text-success fs-9 fw-bold">
            <span>تعمل بشكل طبيعي</span>
            <small class="d-block text-muted fw-normal">جميع الأنظمة بكفاءة 99.98%</small>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3 g-lg-4 mb-3 mb-md-4">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-start">
          <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
            <img :src="getIconUrl('Vector (3).png')" alt="login icon" width="20" height="20" />
            <h6 class="fw-bold text-dark mb-0 fs-7">سجل الدخول</h6>
          </div>
          <div class="table-responsive">
            <table class="table align-middle text-center border-0 fs-8 mb-0 min-w-table">
              <thead class="text-muted fw-normal border-bottom border-light">
                <tr>
                  <th class="py-2 text-start">اسم المريض</th>
                  <th class="py-2">الوقت</th>
                  <th class="py-2">عنوان IP</th>
                  <th class="py-2 text-end">الحالة</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="log in loginLogs" :key="log.name + log.time">
                  <td class="py-3 fw-bold text-dark text-start text-nowrap">{{ log.name }}</td>
                  <td class="py-3 text-muted fs-8 text-nowrap">{{ log.time }}</td>
                  <td class="py-3 dir-ltr text-muted fs-8 text-nowrap">{{ log.ip }}</td>
                  <td class="py-3 text-end text-nowrap">
                    <span class="badge rounded-pill px-3 py-1 fs-9 fw-bold" :class="log.status === 'مكتمل' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis'">
                      {{ log.status }}
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
        <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-start">
          <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
            <img :src="getIconUrl('mdi_gift-outline.png')" alt="gift icon" width="22" height="22" />
            <h6 class="fw-bold text-dark mb-0 fs-7">أدوات النظام</h6>
          </div>
          <div class="d-flex flex-column gap-2.5 gap-md-3">
            <div class="tool-card p-2.5 p-md-3 rounded-3 border bg-light-subtle d-flex align-items-center justify-content-between cursor-pointer" @click="runTool('إدارة النسخ الاحتياطية')">
              <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
                <div class="tool-icon-box bg-primary-subtle text-primary rounded-3 flex-shrink-0">
                  <img :src="getIconUrl('tabler_cloud-up.png')" alt="backup" width="20" height="20" />
                </div>
                <div class="text-start min-w-0">
                  <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">إدارة النسخ الاحتياطية</span>
                  <small class="text-muted fs-9 text-truncate d-block">إنشاء وإدارة النسخ الاحتياطية للبيانات</small>
                </div>
              </div>
              <span class="text-muted fs-8 flex-shrink-0">&gt;</span>
            </div>

            <div class="tool-card p-2.5 p-md-3 rounded-3 border bg-light-subtle d-flex align-items-center justify-content-between cursor-pointer" @click="runTool('مراقبة الأداء')">
              <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
                <div class="tool-icon-box bg-primary-subtle text-primary rounded-3 flex-shrink-0">
                  <img :src="getIconUrl('line-md_gauge.png')" alt="performance" width="20" height="20" />
                </div>
                <div class="text-start min-w-0">
                  <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">مراقبة الأداء</span>
                  <small class="text-muted fs-9 text-truncate d-block">مراقبة أداء النظام والخدمات</small>
                </div>
              </div>
              <span class="text-muted fs-8 flex-shrink-0">&gt;</span>
            </div>

            <div class="tool-card p-2.5 p-md-3 rounded-3 border bg-light-subtle d-flex align-items-center justify-content-between cursor-pointer" @click="runTool('إدارة الخدمات')">
              <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
                <div class="tool-icon-box bg-primary-subtle text-primary rounded-3 flex-shrink-0">
                  <img :src="getIconUrl('arcticons_toolbox.png')" alt="services" width="20" height="20" />
                </div>
                <div class="text-start min-w-0">
                  <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">إدارة الخدمات</span>
                  <small class="text-muted fs-9 text-truncate d-block">إدارة خدمات النظام وحالتها</small>
                </div>
              </div>
              <span class="text-muted fs-8 flex-shrink-0">&gt;</span>
            </div>

            <div class="tool-card p-2.5 p-md-3 rounded-3 border bg-light-subtle d-flex align-items-center justify-content-between cursor-pointer" @click="runTool('سجلات النظام الشاملة')">
              <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
                <div class="tool-icon-box bg-danger-subtle text-danger rounded-3 flex-shrink-0">
                  <img :src="getIconUrl('solar_calendar-linear.png')" alt="logs" width="20" height="20" />
                </div>
                <div class="text-start min-w-0">
                  <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">سجلات النظام</span>
                  <small class="text-muted fs-9 text-truncate d-block">عرض وتحليل سجلات النظام الشاملة</small>
                </div>
              </div>
              <span class="text-muted fs-8 flex-shrink-0">&gt;</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3 g-lg-4 mb-3 mb-md-4">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-start">
          <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
            <img :src="getIconUrl('Vector 23 (1).png')" alt="activity icon" width="22" height="22" />
            <h6 class="fw-bold text-dark mb-0 fs-7">سجل الأنشطة</h6>
          </div>
          <div class="table-responsive">
            <table class="table align-middle text-center border-0 fs-8 mb-0 min-w-table">
              <thead class="text-muted fw-normal border-bottom border-light">
                <tr>
                  <th class="py-2 text-start">الوقت</th>
                  <th class="py-2">النشاط</th>
                  <th class="py-2">المستخدم</th>
                  <th class="py-2 text-end">الوحدة</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="act in activityLogs" :key="act.activity + act.time">
                  <td class="py-2.5 text-muted fs-8 text-start text-nowrap">{{ act.time }}</td>
                  <td class="py-2.5 fw-bold text-dark fs-8 text-nowrap">{{ act.activity }}</td>
                  <td class="py-2.5 text-muted fs-8 text-nowrap">{{ act.user }}</td>
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

      <!-- الإعدادات السريعة المفعلة التغيير -->
      <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-start">
          <div class="d-flex align-items-center justify-content-start gap-2 mb-3 mb-md-4">
            <img :src="getIconUrl('material-symbols_settings-outline (1).png')" alt="settings icon" width="22" height="22" />
            <h6 class="fw-bold text-dark mb-0 fs-7">الإعدادات السريعة</h6>
          </div>
          <div class="d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between p-2 flex-wrap gap-2">
              <div class="text-start min-w-0">
                <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">وضع الصيانة</span>
                <small class="text-muted fs-9 d-block text-truncate">تفعيل وضع الصيانة للنظام</small>
              </div>
              <div class="form-check form-switch m-0 ms-auto ms-sm-0">
                <input class="form-check-input custom-switch" type="checkbox" v-model="settings.maintenance" />
              </div>
            </div>

            <div class="d-flex align-items-center justify-content-between p-2 flex-wrap gap-2">
              <div class="text-start min-w-0">
                <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">التسجيل الذاتي</span>
                <small class="text-muted fs-9 d-block text-truncate">السماح للمستخدمين بالتسجيل الذاتي</small>
              </div>
              <div class="form-check form-switch m-0 ms-auto ms-sm-0">
                <input class="form-check-input custom-switch" type="checkbox" v-model="settings.selfRegister" />
              </div>
            </div>

            <div class="d-flex align-items-center justify-content-between p-2 flex-wrap gap-2">
              <div class="text-start min-w-0">
                <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">النسخ الاحتياطي تلقائي</span>
                <small class="text-muted fs-9 d-block text-truncate">تفعيل النسخ الاحتياطي اليومي التلقائي</small>
              </div>
              <div class="form-check form-switch m-0 ms-auto ms-sm-0">
                <input class="form-check-input custom-switch" type="checkbox" v-model="settings.autoBackup" />
              </div>
            </div>

            <div class="d-flex align-items-center justify-content-between p-2 flex-wrap gap-2">
              <div class="text-start min-w-0">
                <span class="fw-bold text-dark fs-8 d-block mb-0.5 text-truncate">التحقق بخطوتين</span>
                <small class="text-muted fs-9 d-block text-truncate">تفعيل المصادقة الثنائية للمستخدمين</small>
              </div>
              <div class="form-check form-switch m-0 ms-auto ms-sm-0">
                <input class="form-check-input custom-switch" type="checkbox" v-model="settings.twoFactor" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- مؤشرات إدارة النظام -->
    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end mb-4">
      <div class="d-flex align-items-center justify-content-start gap-2 mb-4">
        <img :src="getIconUrl('Vector 24.png')" alt="performance metrics" width="22" height="22" />
        <h5 class="fw-bold text-dark mb-0 fs-6">مؤشرات أدارة النظام</h5>
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
            <h6 class="fw-bold text-dark fs-7 mb-1">استخدام المعالج</h6>
            <small class="text-success fs-8 d-block fw-semibold mb-1">طبيعي</small>
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
            <h6 class="fw-bold text-dark fs-7 mb-1">استخدام الذاكرة</h6>
            <small class="text-warning-emphasis fs-8 d-block fw-semibold mb-1">متوسط</small>
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
            <h6 class="fw-bold text-dark fs-7 mb-1">استخدام التخزين</h6>
            <small class="text-success fs-8 d-block fw-semibold mb-1">طبيعي</small>
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
            <h6 class="fw-bold text-dark fs-7 mb-1">زمن الاستجابة</h6>
            <small class="text-success fs-8 d-block fw-semibold mb-1">ممتاز</small>
            <small class="text-secondary fs-9 d-block">متوسط الاستجابة</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  loginLogs: Array,
  activityLogs: Array,
  settings: Object
});

const getIconUrl = (fileName) => {
  return new URL(`../../../assets/icons/${fileName}`, import.meta.url).href;
};

const runTool = (toolName) => {
  alert(`جاري تشغيل أداة: ${toolName}...`);
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
</style>
