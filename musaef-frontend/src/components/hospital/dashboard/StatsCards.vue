<template>
  <div class="row g-3 g-md-4 mb-3 mb-md-4">
    <!-- 1. الحالات الحرجة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="dashboard-card position-relative h-100">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold text-danger mb-0 card-title-right">الحالات الحرجة</h6>
          <div class="card-icon danger">
            <img src="@/assets/icons/solar_danger-triangle-linear.png" alt="حالات حرجة">
          </div>
        </div>
        <div class="text-end mb-3">
          <h2 class="card-number text-danger mb-0">{{ stats.critical_cases ?? 7 }}</h2>
          <span class="text-muted small">حالة عاجلة</span>
        </div>
        <div class="card-progress-bar bg-light">
          <div class="progress-fill bg-danger" style="width: 60%;"></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mt-3">
          <span class="badge danger-badge">{{ stats.critical_cases_text || '+3 من أمس' }}</span>
        </div>
      </div>
    </div>

    <!-- 2. المتبرعون المسجلون اليوم -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="dashboard-card position-relative h-100">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold text-success mb-0 card-title-right">المتبرعون المسجلون اليوم</h6>
          <div class="card-icon success">
            <img src="@/assets/icons/tabler_users.png" alt="المتبرعون">
          </div>
        </div>
        <div class="text-end mb-3">
          <h2 class="card-number text-success mb-0">{{ stats.today_donors ?? 42 }}</h2>
          <span class="text-muted small">متبرع</span>
        </div>
        <div class="card-progress-bar bg-light">
          <div class="progress-fill bg-success" style="width: 75%;"></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mt-3">
          <span class="badge success-badge">{{ stats.today_donors_text || '+15 اليوم' }}</span>
        </div>
      </div>
    </div>

    <!-- 3. وحدات الدم المتوفرة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="dashboard-card position-relative h-100">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold text-purple mb-0 card-title-right">وحدات الدم المتوفرة</h6>
          <div class="card-icon purple">
            <img src="@/assets/icons/streamline-ultimate_blood-drop.png" alt="وحدات الدم">
          </div>
        </div>
        <div class="text-end mb-3">
          <h2 class="card-number text-purple mb-0">{{ stats.available_units ?? 156 }}</h2>
          <span class="text-muted small">وحدة</span>
        </div>
        <div class="card-progress-bar bg-light">
          <div class="progress-fill bg-purple" style="width: 85%;"></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mt-3">
          <span class="badge purple-badge">{{ stats.available_units_text || '+3 من أمس' }}</span>
        </div>
      </div>
    </div>

    <!-- 4. الطلبات النشطة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="dashboard-card position-relative h-100">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold text-primary mb-0 card-title-right">الطلبات النشطة</h6>
          <div class="card-icon primary">
            <img src="@/assets/icons/stash_data-date-light.png" alt="الطلبات">
          </div>
        </div>
        <div class="text-end mb-3">
          <h2 class="card-number text-primary mb-0">{{ stats.active_requests ?? 23 }}</h2>
          <span class="text-muted small">طلب</span>
        </div>
        <div class="card-progress-bar bg-light">
          <div class="progress-fill bg-primary" style="width: 50%;"></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mt-3">
          <span class="badge primary-badge">{{ stats.active_requests_text || '+3 من أمس' }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  stats: {
    type: Object,
    default: () => ({})
  }
});
</script>

<style scoped>
.dashboard-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
.card-title-right { font-size: 14px; font-weight: 700; }
.card-number { font-size: 24px; font-weight: 800; margin-bottom: 2px; }

@media (min-width: 768px) {
  .dashboard-card { padding: 20px; }
  .card-title-right { font-size: 15px; }
  .card-number { font-size: 28px; }
}

.card-title-right.text-danger, .card-number.text-danger { color: #dc2626 !important; }
.card-title-right.text-success { color: #16a34a !important; }
.card-title-right.text-purple { color: #7e22ce !important; }
.card-title-right.text-primary { color: #2563eb !important; }

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
