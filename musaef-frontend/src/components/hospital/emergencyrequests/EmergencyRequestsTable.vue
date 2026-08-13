<template>
  <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden p-2 p-md-3" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <div class="table-responsive">
      <table class="table align-middle text-center mb-0 fs-8 border-0 min-w-table custom-design-table">
        <thead>
          <tr class="text-secondary border-0">
            <th class="py-3 text-nowrap fw-bold" :class="currentLanguage === 'ar' ? 'text-end pe-3 pe-md-4' : 'text-start ps-3 ps-md-4'">
              {{ t('emergency.table.requestCode') }}
            </th>
            <th class="py-3 text-nowrap fw-bold">{{ t('emergency.table.requiredBloodType') }}</th>
            <th class="py-3 text-nowrap fw-bold">{{ t('emergency.table.unitsCount') }}</th>
            <th class="py-3 text-nowrap fw-bold">{{ t('emergency.table.urgencyLevel') }}</th>
            <th class="py-3 text-nowrap fw-bold">{{ t('emergency.table.respondersCount') }}</th>
            <th class="py-3 text-nowrap fw-bold text-center" :class="currentLanguage === 'ar' ? 'ps-3 ps-md-4' : 'pe-3 pe-md-4'">
              {{ t('emergency.table.status') }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="req in formattedRequests"
            :key="req.id"
            @click="$emit('selectRequest', req)"
            class="cursor-pointer text-nowrap custom-row"
            :class="selectedRequestId === req.id ? 'active-selected-row' : ''"
          >
            <!-- رقم الطلب -->
            <td class="fw-bold text-dark py-3.5 fs-8" :class="currentLanguage === 'ar' ? 'text-end pe-3 pe-md-4' : 'text-start ps-3 ps-md-4'">
              {{ req.code }}
            </td>

            <!-- الفصيلة المطلوبة -->
            <td class="py-3.5 fw-bold text-danger fs-8">
              {{ req.bloodType }}
            </td>

            <!-- عدد الوحدات -->
            <td class="fw-bold text-dark py-3.5 fs-8">
              {{ req.units }}
            </td>

            <!-- مستوى الخطورة -->
            <td class="py-3.5">
              <span :class="['pill-badge px-3 py-1 fs-9 fw-bold', req.urgencyCustomClass]">
                {{ translateUrgency(req.urgencyRaw) }}
              </span>
            </td>

            <!-- عدد المستجيبين وشريط التقدم -->
            <td class="py-3.5">
              <div class="d-flex align-items-center justify-content-center gap-2 px-2" dir="ltr">
                <span class="fw-bold text-dark fs-8 min-w-35">{{ req.coverage }}%</span>
                <div class="progress progress-bar-flat flex-grow-1" style="height: 4px; max-width: 75px;">
                  <div class="progress-bar" :class="req.progressColorClass" :style="{ width: req.coverage + '%' }"></div>
                </div>
              </div>
            </td>

            <!-- حالة الطلب -->
            <td class="py-3.5 text-center" :class="currentLanguage === 'ar' ? 'ps-3 ps-md-4' : 'pe-3 pe-md-4'">
              <span :class="['pill-badge px-3 py-1 fs-9 fw-bold', req.statusCustomClass]">
                {{ translateStatus(req.statusRaw, req.coverage) }}
              </span>
            </td>
          </tr>

          <tr v-if="formattedRequests.length === 0">
            <td colspan="6" class="text-center text-muted py-4 fs-8">
              {{ t('emergency.table.noRequests') }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { computed, watch } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const props = defineProps({
  requests: {
    type: Array,
    required: true
  },
  selectedRequestId: {
    type: [Number, String],
    default: null
  }
});

defineEmits(['selectRequest']);

const dictionary = {
  ar: {
    "emergency.table.requestCode": "رقم الطلب",
    "emergency.table.requiredBloodType": "الفصيلة المطلوبة",
    "emergency.table.unitsCount": "عدد الوحدات",
    "emergency.table.urgencyLevel": "مستوى الخطورة",
    "emergency.table.respondersCount": "عدد المستجيبين",
    "emergency.table.status": "حالة الطلب",
    "emergency.table.noRequests": "لا توجد طلبات متطابقة مع التصفية الحالية."
  },
  en: {
    "emergency.table.requestCode": "Request Code",
    "emergency.table.requiredBloodType": "Required Type",
    "emergency.table.unitsCount": "Units Needed",
    "emergency.table.urgencyLevel": "Urgency Level",
    "emergency.table.respondersCount": "Responders Count",
    "emergency.table.status": "Request Status",
    "emergency.table.noRequests": "No requests match the current filter."
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const translateUrgency = (raw) => {
  if (currentLanguage.value === 'en') {
    if (['critical', 'حرجة', 'حرجة جداً'].includes(raw)) return 'Critical';
    if (['high', 'عالية'].includes(raw)) return 'High';
    if (['medium', 'متوسطة'].includes(raw)) return 'Medium';
    return 'Low';
  }
  if (['critical', 'حرجة', 'حرجة جداً'].includes(raw)) return 'حرج';
  if (['high', 'عالية'].includes(raw)) return 'خطر';
  if (['medium', 'متوسطة'].includes(raw)) return 'متوسط';
  return 'منخفض';
};

const translateStatus = (raw, coverage) => {
  if (currentLanguage.value === 'en') {
    if (coverage >= 100 || raw === 'completed') return 'Completed';
    if (raw === 'rejected') return 'Rejected';
    if (raw === 'searching' || raw === 'active') return 'Active';
    return 'Processing';
  }
  if (coverage >= 100 || raw === 'completed') return 'مكتملة';
  if (raw === 'rejected') return 'مرفوضة';
  if (raw === 'searching' || raw === 'active') return 'نشط';
  return 'قيد المعالجة';
};

const formattedRequests = computed(() => {
  return props.requests.map((item, idx) => {
    let code = item.code || `ER-2026-${1840 + idx}`;
    let urgencyRaw = item.urgency || 'critical';
    let urgencyCustomClass = 'urgency-critical';

    if (['critical', 'حرجة', 'حرجة جداً'].includes(urgencyRaw)) {
      urgencyCustomClass = 'urgency-critical';
    } else if (['high', 'عالية'].includes(urgencyRaw)) {
      urgencyCustomClass = 'urgency-high';
    } else {
      urgencyCustomClass = 'urgency-medium';
    }

    let progressColorClass = 'bg-danger';
    if (item.coverage >= 100) {
      progressColorClass = 'bg-success';
    } else if (item.coverage >= 50) {
      progressColorClass = 'bg-warning';
    }

    let statusRaw = item.status || 'searching';
    let statusCustomClass = 'status-active';

    if (item.coverage >= 100 || statusRaw === 'completed') {
      statusCustomClass = 'status-completed';
    } else if (statusRaw === 'rejected') {
      statusCustomClass = 'urgency-high';
    } else {
      statusCustomClass = 'status-active';
    }

    return {
      ...item,
      code,
      urgencyRaw,
      urgencyCustomClass,
      progressColorClass,
      statusRaw,
      statusCustomClass
    };
  });
});

// مزامنة النداءات الطارئة فورياً للوصول إليها في بنك الدم
watch(() => props.requests, (newRequests) => {
  if (newRequests && Array.isArray(newRequests)) {
    try {
      localStorage.setItem('musaef_emergency_requests', JSON.stringify(newRequests));
      window.dispatchEvent(new Event('musaef_emergency_updated'));
    } catch (e) {
      console.error('Error syncing emergency requests:', e);
    }
  }
}, { immediate: true, deep: true });
</script>

<style scoped>
.fs-8 { font-size: 0.83rem; }
.fs-9 { font-size: 0.74rem; }
.cursor-pointer { cursor: pointer; }
.min-w-table { min-width: 620px; }
.min-w-35 { min-width: 35px; text-align: right; }

.custom-design-table {
  border-collapse: separate;
  border-spacing: 0 8px;
}

.custom-design-table thead th {
  border: none;
  font-weight: 700;
  color: #475569;
  background: transparent;
}

.custom-row {
  background-color: #ffffff;
  transition: all 0.2s ease;
  border-radius: 12px;
}

.custom-row td {
  border-top: 1px solid #f1f5f9;
  border-bottom: 1px solid #f1f5f9;
}

.custom-row:hover { background-color: #f8fafc; }

.active-selected-row td {
  background-color: #fff1f2 !important;
  border-color: #fecdd3 !important;
}

.pill-badge {
  border-radius: 20px;
  display: inline-block;
  min-width: 75px;
  text-align: center;
}

.urgency-critical { background-color: #fff1f2; color: #e11d48; }
.urgency-high { background-color: #fef2f2; color: #dc2626; }
.urgency-medium { background-color: #fffbe6; color: #d97706; }

.status-active { background-color: #fff1f2; color: #e11d48; }
.status-completed { background-color: #f0fdf4; color: #16a34a; }

.progress-bar-flat { background-color: #e2e8f0; border-radius: 4px; overflow: hidden; }
</style>
