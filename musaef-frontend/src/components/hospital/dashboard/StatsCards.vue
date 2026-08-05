<template>
  <div class="row g-3 g-md-4 mb-3 mb-md-4" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">
    <!-- 1. الحالات الحرجة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="dashboard-card position-relative h-100">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold text-danger mb-0 card-title-align">{{ t('criticalCases') }}</h6>
          <div class="card-icon danger">
            <img src="@/assets/icons/solar_danger-triangle-linear.png" alt="Critical cases">
          </div>
        </div>
        <div class="mb-3" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'">
          <h2 class="card-number text-danger mb-0">{{ stats.critical_cases ?? 7 }}</h2>
          <span class="text-muted small">{{ t('urgentCase') }}</span>
        </div>
        <div class="card-progress-bar bg-light">
          <div class="progress-fill bg-danger" style="width: 60%;"></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mt-3">
          <span class="badge danger-badge">{{ stats.critical_cases_text || t('fromYesterday') }}</span>
        </div>
      </div>
    </div>

    <!-- 2. المتبرعون المسجلون اليوم -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="dashboard-card position-relative h-100">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold text-success mb-0 card-title-align">{{ t('todayDonors') }}</h6>
          <div class="card-icon success">
            <img src="@/assets/icons/tabler_users.png" alt="Donors">
          </div>
        </div>
        <div class="mb-3" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'">
          <h2 class="card-number text-success mb-0">{{ stats.today_donors ?? 42 }}</h2>
          <span class="text-muted small">{{ t('donor') }}</span>
        </div>
        <div class="card-progress-bar bg-light">
          <div class="progress-fill bg-success" style="width: 75%;"></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mt-3">
          <span class="badge success-badge">{{ stats.today_donors_text || t('fromToday') }}</span>
        </div>
      </div>
    </div>

    <!-- 3. وحدات الدم المتوفرة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="dashboard-card position-relative h-100">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold text-purple mb-0 card-title-align">{{ t('availableUnits') }}</h6>
          <div class="card-icon purple">
            <img src="@/assets/icons/streamline-ultimate_blood-drop.png" alt="Blood units">
          </div>
        </div>
        <div class="mb-3" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'">
          <h2 class="card-number text-purple mb-0">{{ stats.available_units ?? 156 }}</h2>
          <span class="text-muted small">{{ t('unit') }}</span>
        </div>
        <div class="card-progress-bar bg-light">
          <div class="progress-fill bg-purple" style="width: 85%;"></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mt-3">
          <span class="badge purple-badge">{{ stats.available_units_text || t('fromYesterday') }}</span>
        </div>
      </div>
    </div>

    <!-- 4. الطلبات النشطة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="dashboard-card position-relative h-100">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold text-primary mb-0 card-title-align">{{ t('activeRequests') }}</h6>
          <div class="card-icon primary">
            <img src="@/assets/icons/stash_data-date-light.png" alt="Requests">
          </div>
        </div>
        <div class="mb-3" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'">
          <h2 class="card-number text-primary mb-0">{{ stats.active_requests ?? 23 }}</h2>
          <span class="text-muted small">{{ t('request') }}</span>
        </div>
        <div class="card-progress-bar bg-light">
          <div class="progress-fill bg-primary" style="width: 50%;"></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mt-3">
          <span class="badge primary-badge">{{ stats.active_requests_text || t('fromYesterday') }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const currentLocale = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    criticalCases: 'الحالات الحرجة',
    urgentCase: 'حالة عاجلة',
    fromYesterday: '+3 من أمس',
    todayDonors: 'المتبرعون المسجلون اليوم',
    donor: 'متبرع',
    fromToday: '+15 اليوم',
    availableUnits: 'وحدات الدم المتوفرة',
    unit: 'وحدة',
    activeRequests: 'الطلبات النشطة',
    request: 'طلب'
  },
  en: {
    criticalCases: 'Critical Cases',
    urgentCase: 'urgent case',
    fromYesterday: '+3 from yesterday',
    todayDonors: "Today's Registered Donors",
    donor: 'donor',
    fromToday: '+15 today',
    availableUnits: 'Available Blood Units',
    unit: 'unit',
    activeRequests: 'Active Requests',
    request: 'request'
  }
};

const t = (key) => dictionary[currentLocale.value === 'en' ? 'en' : 'ar'][key] || key;

defineProps({
  stats: {
    type: Object,
    default: () => ({})
  }
});
</script>

<style scoped>
.dashboard-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
.card-title-align { font-size: 14px; font-weight: 700; }
.card-number { font-size: 24px; font-weight: 800; margin-bottom: 2px; }

@media (min-width: 768px) {
  .dashboard-card { padding: 20px; }
  .card-title-align { font-size: 15px; }
  .card-number { font-size: 28px; }
}

.card-title-align.text-danger, .card-number.text-danger { color: #dc2626 !important; }
.card-title-align.text-success { color: #16a34a !important; }
.card-title-align.text-purple { color: #7e22ce !important; }
.card-title-align.text-primary { color: #2563eb !important; }

.card-progress-bar { width: 100%; height: 4px; border-radius: 2px; overflow: hidden; margin-top: 10px; }
.progress-fill { height: 100%; border-radius: 2px; }

.card-icon { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
@media (min-width: 768px) { .card-icon { width: 50px; height: 50px; } }

.card-icon.danger { background: #fee2e2; }
.card-icon.success { background: #dcfce7; }
.card-icon.purple { background: #f3e8ff; }
.card-icon.primary { background: #e0f2fe; }
.card-icon img { width: 20px; height: 20px; object-fit: contain; }

.badge { font-size: 11px; padding: 5px 9px; border-radius: 20px; font-weight: 600; }
.danger-badge { background: #fee2e2; color: #dc2626; }
.success-badge { background: #dcfce7; color: #16a34a; }
.purple-badge { background: #f3e8ff; color: #7e22ce; }
.primary-badge { background: #e0f2fe; color: #0284c7; }
</style>
