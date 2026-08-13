<template>
  <div class="row g-3 g-md-4 mb-3 mb-md-4" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">
    <!-- 1. الحالات الحرجة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="dashboard-card position-relative h-100">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold text-danger mb-0 card-title-align">
            {{ t("criticalCases") }}
          </h6>
          <div class="card-icon danger">
            <img
              src="@/assets/icons/solar_danger-triangle-linear.png"
              alt="Critical cases"
            />
          </div>
        </div>
        <div class="mb-3" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'">
          <h2 class="card-number text-danger mb-0">
            {{ stats.critical_cases ?? stats.active_critical_cases ?? 0 }}
          </h2>
          <span class="text-muted small">{{ t("urgentCase") }}</span>
        </div>
        <div class="card-progress-bar bg-light">
          <div
            class="progress-fill bg-danger"
            :style="{
              width:
                calculatePercentage(
                  stats.critical_cases ?? stats.active_critical_cases,
                  20
                ) + '%',
            }"
          ></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mt-3">
          <span class="badge danger-badge">{{
            translateBadgeText(stats.critical_cases_text, t("activeNow"))
          }}</span>
        </div>
      </div>
    </div>

    <!-- 2. وحدات الدم المتوفرة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="dashboard-card position-relative h-100">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold text-purple mb-0 card-title-align">
            {{ t("availableUnits") }}
          </h6>
          <div class="card-icon purple">
            <img
              src="@/assets/icons/streamline-ultimate_blood-drop.png"
              alt="Blood units"
            />
          </div>
        </div>
        <div class="mb-3" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'">
          <h2 class="card-number text-purple mb-0">
            {{
              stats.available_units ??
              stats.total_available_units ??
              stats.total_units ??
              0
            }}
          </h2>
          <span class="text-muted small">{{ t("unit") }}</span>
        </div>
        <div class="card-progress-bar bg-light">
          <div
            class="progress-fill bg-purple"
            :style="{
              width:
                calculatePercentage(
                  stats.available_units ??
                    stats.total_available_units ??
                    stats.total_units,
                  500
                ) + '%',
            }"
          ></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mt-3">
          <span class="badge purple-badge">{{
            translateBadgeText(stats.available_units_text, t("totalStock"))
          }}</span>
        </div>
      </div>
    </div>

    <!-- 3. المتبرعون المسجلون اليوم -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="dashboard-card position-relative h-100">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold text-success mb-0 card-title-align">
            {{ t("todayDonors") }}
          </h6>
          <div class="card-icon success">
            <img src="@/assets/icons/tabler_users.png" alt="Donors" />
          </div>
        </div>
        <div class="mb-3" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'">
          <h2 class="card-number text-success mb-0">
            {{ stats.registered_today ?? stats.today_donors ?? stats.donors_today ?? 0 }}
          </h2>
          <span class="text-muted small">{{ t("donor") }}</span>
        </div>
        <div class="card-progress-bar bg-light">
          <div
            class="progress-fill bg-success"
            :style="{
              width:
                calculatePercentage(
                  stats.registered_today ?? stats.today_donors ?? stats.donors_today,
                  50
                ) + '%',
            }"
          ></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mt-3">
          <span class="badge success-badge">{{
            translateBadgeText(
              stats.today_donors_text ?? stats.registered_today_text,
              t("fromToday")
            )
          }}</span>
        </div>
      </div>
    </div>

    <!-- 4. الطلبات النشطة -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="dashboard-card position-relative h-100">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold text-primary mb-0 card-title-align">
            {{ t("activeRequests") }}
          </h6>
          <div class="card-icon primary">
            <img src="@/assets/icons/stash_data-date-light.png" alt="Requests" />
          </div>
        </div>
        <div class="mb-3" :class="currentLocale === 'ar' ? 'text-end' : 'text-start'">
          <h2 class="card-number text-primary mb-0">
            {{ stats.active_requests ?? stats.under_followup ?? 0 }}
          </h2>
          <span class="text-muted small">{{ t("request") }}</span>
        </div>
        <div class="card-progress-bar bg-light">
          <div
            class="progress-fill bg-primary"
            :style="{
              width:
                calculatePercentage(stats.active_requests ?? stats.under_followup, 50) +
                '%',
            }"
          ></div>
        </div>
        <div class="d-flex justify-content-start align-items-center mt-3">
          <span class="badge primary-badge">{{
            translateBadgeText(stats.active_requests_text, t("currentActive"))
          }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";

defineProps({
  stats: {
    type: Object,
    default: () => ({}),
  },
});

const currentLocale = ref(localStorage.getItem("musaef_lang") || "ar");

const updateLocale = () => {
  currentLocale.value = localStorage.getItem("musaef_lang") || "ar";
};

onMounted(() => {
  window.addEventListener("storage", updateLocale);
  window.addEventListener("language-changed", updateLocale);
});

onUnmounted(() => {
  window.removeEventListener("storage", updateLocale);
  window.removeEventListener("language-changed", updateLocale);
});

const dictionary = {
  ar: {
    criticalCases: "الحالات الحرجة",
    urgentCase: "حالة عاجلة",
    activeNow: "حالات نشطة حالياً",
    todayDonors: "المتبرعون المسجلون اليوم",
    donor: "متبرع",
    fromToday: "مسجلين اليوم",
    availableUnits: "وحدات الدم المتوفرة",
    unit: "وحدة",
    totalStock: "إجمالي المخزون المتاح",
    activeRequests: "الطلبات النشطة",
    request: "طلب",
    currentActive: "قيد المتابعة",
  },
  en: {
    criticalCases: "Critical Cases",
    urgentCase: "urgent case",
    activeNow: "Currently Active",
    todayDonors: "Today's Registered Donors",
    donor: "donor",
    fromToday: "Registered today",
    availableUnits: "Available Blood Units",
    unit: "unit",
    totalStock: "Total Available Stock",
    activeRequests: "Active Requests",
    request: "request",
    currentActive: "In progress",
  },
};

const t = (key) => dictionary[currentLocale.value === "en" ? "en" : "ar"][key] || key;

const translateBadgeText = (text, fallback) => {
  if (!text) return fallback;
  if (currentLocale.value === "en") {
    return text.replace(/من أمس/g, "from yesterday").replace(/اليوم/g, "today");
  }
  return text;
};

const calculatePercentage = (val, max = 100) => {
  if (!val) return 5;
  const pct = Math.round((val / max) * 100);
  return Math.min(Math.max(pct, 10), 100);
};
</script>

<style scoped>
.dashboard-card {
  background: #fff;
  border-radius: 16px;
  padding: 16px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
}
.card-title-align {
  font-size: 14px;
  font-weight: 700;
}
.card-number {
  font-size: 24px;
  font-weight: 800;
  margin-bottom: 2px;
}

@media (min-width: 768px) {
  .dashboard-card {
    padding: 20px;
  }
  .card-title-align {
    font-size: 15px;
  }
  .card-number {
    font-size: 28px;
  }
}

.card-title-align.text-danger,
.card-number.text-danger {
  color: #dc2626 !important;
}
.card-title-align.text-success {
  color: #16a34a !important;
}
.card-title-align.text-purple {
  color: #7e22ce !important;
}
.card-title-align.text-primary {
  color: #2563eb !important;
}

.card-progress-bar {
  width: 100%;
  height: 4px;
  border-radius: 2px;
  overflow: hidden;
  margin-top: 10px;
}
.progress-fill {
  height: 100%;
  border-radius: 2px;
  transition: width 0.4s ease;
}
.bg-purple {
  background-color: #7e22ce !important;
}

.card-icon {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
@media (min-width: 768px) {
  .card-icon {
    width: 50px;
    height: 50px;
  }
}

.card-icon.danger {
  background: #fee2e2;
}
.card-icon.success {
  background: #dcfce7;
}
.card-icon.purple {
  background: #f3e8ff;
}
.card-icon.primary {
  background: #e0f2fe;
}
.card-icon img {
  width: 20px;
  height: 20px;
  object-fit: contain;
}

.badge {
  font-size: 11px;
  padding: 5px 9px;
  border-radius: 20px;
  font-weight: 600;
}
.danger-badge {
  background: #fee2e2;
  color: #dc2626;
}
.success-badge {
  background: #dcfce7;
  color: #16a34a;
}
.purple-badge {
  background: #f3e8ff;
  color: #7e22ce;
}
.primary-badge {
  background: #e0f2fe;
  color: #0284c7;
}
</style>
