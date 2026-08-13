<template>
  <div class="row g-3 mb-3 mb-md-4" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <!-- 1. عدد المتبرعين -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2-4">
      <div
        class="card border-0 shadow-sm p-3 rounded-4 bg-white position-relative h-100"
        :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'"
      >
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">{{ t("donorsCount") }}</span>
          <div
            class="icon-box-badge bg-danger-subtle text-danger rounded-3 p-1.5 d-flex align-items-center justify-content-center"
          >
            <img
              :src="getIconUrl('pepicons-pencil_people.png')"
              alt="donors"
              width="18"
              height="18"
            />
          </div>
        </div>
        <h4 class="fw-black text-dark mb-1 fs-3">{{ formatNumber(getDonorsCount()) }}</h4>
        <div class="d-flex align-items-center gap-1 text-success fs-9 fw-bold flex-wrap">
          <span>+12.5%</span>
          <span class="text-muted fw-normal">{{ t("fromLastMonth") }}</span>
        </div>
      </div>
    </div>

    <!-- 2. عدد المستشفيات -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2-4">
      <div
        class="card border-0 shadow-sm p-3 rounded-4 bg-white position-relative h-100"
        :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'"
      >
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">{{ t("hospitalsCount") }}</span>
          <div
            class="icon-box-badge bg-primary-subtle text-primary rounded-3 p-1.5 d-flex align-items-center justify-content-center"
          >
            <img
              :src="getIconUrl('pepicons-pencil_people (1).png')"
              alt="hospitals"
              width="18"
              height="18"
            />
          </div>
        </div>
        <h4 class="fw-black text-dark mb-1 fs-3">
          {{ formatNumber(getHospitalsCount()) }}
        </h4>
        <div class="d-flex align-items-center gap-1 text-success fs-9 fw-bold flex-wrap">
          <span>+12.5%</span>
          <span class="text-muted fw-normal">{{ t("fromLastMonth") }}</span>
        </div>
      </div>
    </div>

    <!-- 3. إجمالي الطلبات -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2-4">
      <div
        class="card border-0 shadow-sm p-3 rounded-4 bg-white position-relative h-100"
        :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'"
      >
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">{{ t("totalRequests") }}</span>
          <div
            class="icon-box-badge bg-purple-subtle text-purple rounded-3 p-1.5 d-flex align-items-center justify-content-center"
          >
            <img
              :src="getIconUrl('pepicons-pencil_people (2).png')"
              alt="requests"
              width="18"
              height="18"
            />
          </div>
        </div>
        <h4 class="fw-black text-dark mb-1 fs-3">
          {{ formatNumber(getRequestsCount()) }}
        </h4>
        <div class="d-flex align-items-center gap-1 text-success fs-9 fw-bold flex-wrap">
          <span>+12.5%</span>
          <span class="text-muted fw-normal">{{ t("fromLastMonth") }}</span>
        </div>
      </div>
    </div>

    <!-- 4. إجمالي التبرعات -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2-4">
      <div
        class="card border-0 shadow-sm p-3 rounded-4 bg-white position-relative h-100"
        :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'"
      >
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">{{ t("totalDonations") }}</span>
          <div
            class="icon-box-badge bg-teal-subtle text-teal rounded-3 p-1.5 d-flex align-items-center justify-content-center"
          >
            <img
              :src="getIconUrl('pepicons-pencil_people (3).png')"
              alt="donations"
              width="18"
              height="18"
            />
          </div>
        </div>
        <h4 class="fw-black text-dark mb-1 fs-3">
          {{ formatNumber(getDonationsCount()) }}
        </h4>
        <div class="d-flex align-items-center gap-1 text-success fs-9 fw-bold flex-wrap">
          <span>+12.5%</span>
          <span class="text-muted fw-normal">{{ t("fromLastMonth") }}</span>
        </div>
      </div>
    </div>

    <!-- 5. الحالات الحرجة النشطة -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2-4">
      <div
        class="card border-0 shadow-sm p-3 rounded-4 bg-white position-relative h-100"
        :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'"
      >
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted fs-8 fw-semibold">{{ t("activeCriticalCases") }}</span>
          <div
            class="icon-box-badge bg-danger-subtle text-danger rounded-3 p-1.5 d-flex align-items-center justify-content-center"
          >
            <img
              :src="getIconUrl('pepicons-pencil_people (4).png')"
              alt="critical"
              width="18"
              height="18"
            />
          </div>
        </div>
        <h4 class="fw-black text-dark mb-1 fs-3">
          {{ formatNumber(getCriticalCasesCount()) }}
        </h4>
        <div class="d-flex align-items-center gap-1 text-success fs-9 fw-bold flex-wrap">
          <span>+12.5%</span>
          <span class="text-muted fw-normal">{{ t("fromLastMonth") }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({}),
  },
});

const currentLanguage = computed(() => localStorage.getItem("musaef_lang") || "ar");

const dictionary = {
  ar: {
    donorsCount: "عدد المتبرعين",
    hospitalsCount: "عدد المستشفيات",
    totalRequests: "إجمالي الطلبات",
    totalDonations: "إجمالي التبرعات",
    activeCriticalCases: "الحالات الحرجة النشطة",
    fromLastMonth: "عن الشهر الماضي",
  },
  en: {
    donorsCount: "Donors Count",
    hospitalsCount: "Hospitals Count",
    totalRequests: "Total Requests",
    totalDonations: "Total Donations",
    activeCriticalCases: "Active Critical Cases",
    fromLastMonth: "from last month",
  },
};

const t = (key) => dictionary[currentLanguage.value === "en" ? "en" : "ar"][key] || key;

const getDonorsCount = () => {
  const s = props.stats || {};
  return s.donorsCount ?? s.donors_count ?? s.donors ?? s.totalDonors ?? 0;
};

const getHospitalsCount = () => {
  const s = props.stats || {};
  return s.hospitalsCount ?? s.hospitals_count ?? s.hospitals ?? s.totalHospitals ?? 0;
};

const getRequestsCount = () => {
  const s = props.stats || {};
  return s.requestsCount ?? s.requests_count ?? s.requests ?? s.totalRequests ?? 0;
};

const getDonationsCount = () => {
  const s = props.stats || {};
  const val =
    s.donationsCount ??
    s.totalDonations ??
    s.donations_count ??
    s.donations ??
    s.completed_donations ??
    s.total_donations;

  if (val !== undefined && val !== null && val !== "") {
    return Number(val);
  }

  // Fallback: إذا لم تُرجع الـ API قيمة صريحة لتبرعات التبرع المكتملة
  const totalReq = getRequestsCount();
  const critical = getCriticalCasesCount();
  if (totalReq > 0) {
    return Math.max(0, totalReq - critical);
  }

  return 0;
};

const getCriticalCasesCount = () => {
  const s = props.stats || {};
  return (
    s.criticalCasesCount ??
    s.critical_cases_count ??
    s.criticalCases ??
    s.critical_cases ??
    0
  );
};

const formatNumber = (val) => {
  if (val === undefined || val === null || isNaN(val)) return "0";
  return Number(val).toLocaleString();
};

const getIconUrl = (fileName) => {
  if (!fileName) return "";
  if (fileName.startsWith("http") || fileName.startsWith("data:")) return fileName;
  try {
    return new URL(`../../../assets/icons/${fileName}`, import.meta.url).href;
  } catch (e) {
    return "";
  }
};
</script>

<style scoped>
.fs-8 {
  font-size: 0.8rem;
}
.fs-9 {
  font-size: 0.72rem;
}
.fw-black {
  font-weight: 900;
}

@media (min-width: 1200px) {
  .col-xl-2-4 {
    flex: 0 0 auto;
    width: 20%;
  }
}

.bg-danger-subtle {
  background-color: #fee2e2 !important;
}
.bg-primary-subtle {
  background-color: #dbeafe !important;
}
.bg-purple-subtle {
  background-color: #f3e8ff !important;
}
.bg-teal-subtle {
  background-color: #ccfbf1 !important;
}

.text-purple {
  color: #9333ea !important;
}
.text-teal {
  color: #0d9488 !important;
}

.icon-box-badge {
  width: 32px;
  height: 32px;
}
.dir-rtl {
  direction: rtl;
}
.dir-ltr {
  direction: ltr;
}
</style>
