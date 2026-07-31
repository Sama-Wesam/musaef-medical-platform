<template>
  <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden p-2 p-md-3" dir="rtl">
    <div class="table-responsive">
      <table class="table align-middle text-center mb-0 fs-8 border-0 min-w-table custom-design-table">
        <thead>
          <tr class="text-secondary border-0">
            <th class="py-3 text-end pe-3 pe-md-4 text-nowrap fw-bold">رقم الطلب</th>
            <th class="py-3 text-nowrap fw-bold">الفصيلة المطلوبة</th>
            <th class="py-3 text-nowrap fw-bold">عدد الوحدات</th>
            <th class="py-3 text-nowrap fw-bold">مستوى الخطورة</th>
            <th class="py-3 text-nowrap fw-bold">عدد المستجيبين</th>
            <th class="py-3 ps-3 ps-md-4 text-nowrap fw-bold text-center">حالة الطلب</th>
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
            <td class="fw-bold text-dark py-3.5 text-end pe-3 pe-md-4 fs-8">
              {{ req.code }}
            </td>

            <!-- الفصيلة المطلوبة -->
            <td class="py-3.5 fw-bold text-dark fs-8">
              {{ req.bloodType }}
            </td>

            <!-- عدد الوحدات -->
            <td class="fw-bold text-dark py-3.5 fs-8">
              {{ req.units }}
            </td>

            <!-- مستوى الخطورة -->
            <td class="py-3.5">
              <span :class="['pill-badge px-3 py-1 fs-9 fw-bold', req.urgencyCustomClass]">
                {{ req.urgencyText }}
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
            <td class="py-3.5 ps-3 ps-md-4 text-center">
              <span :class="['pill-badge px-3 py-1 fs-9 fw-bold', req.statusCustomClass]">
                {{ req.statusText }}
              </span>
            </td>
          </tr>

          <tr v-if="formattedRequests.length === 0">
            <td colspan="6" class="text-center text-muted py-4 fs-8">
              لا توجد طلبات متطابقة مع التصفية الحالية.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

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

const formattedRequests = computed(() => {
  return props.requests.map((item, idx) => {
    let code = item.code || `ER-2024-${1840 + idx}`;

    let urgencyText = item.urgency || 'حرج';
    let urgencyCustomClass = 'urgency-critical';

    if (urgencyText === 'حرجة جداً' || urgencyText === 'حرجة' || urgencyText === 'حرج' || urgencyText === 'critical') {
      urgencyText = 'حرج';
      urgencyCustomClass = 'urgency-critical';
    } else if (urgencyText === 'عالية' || urgencyText === 'خطر' || urgencyText === 'high') {
      urgencyText = 'خطر';
      urgencyCustomClass = 'urgency-high';
    } else if (urgencyText === 'متوسطة' || urgencyText === 'متوسط' || urgencyText === 'medium') {
      urgencyText = 'متوسط';
      urgencyCustomClass = 'urgency-medium';
    } else {
      urgencyText = 'منخفض';
      urgencyCustomClass = 'urgency-low';
    }

    let progressColorClass = 'bg-danger';
    if (item.coverage >= 100) {
      progressColorClass = 'bg-success';
    } else if (item.coverage >= 50) {
      progressColorClass = 'bg-warning';
    }

    let statusText = item.status || 'نشط';
    let statusCustomClass = 'status-active';

    if (statusText === 'نشط' || statusText === 'قيد التغطية' || statusText === 'نشط/قيد التغطية') {
      if (item.coverage >= 100) {
        statusText = 'مكتملة';
        statusCustomClass = 'status-completed';
      } else if (item.coverage > 40) {
        statusText = 'قيد المعالجة';
        statusCustomClass = 'status-processing';
      } else {
        statusText = 'نشط';
        statusCustomClass = 'status-active';
      }
    } else if (statusText === 'مكتملة' || item.coverage >= 100) {
      statusText = 'مكتملة';
      statusCustomClass = 'status-completed';
    } else if (statusText === 'مرفوضة') {
      statusText = 'مرفوضة';
      statusCustomClass = 'urgency-high';
    }

    return {
      ...item,
      code,
      urgencyText,
      urgencyCustomClass,
      progressColorClass,
      statusText,
      statusCustomClass
    };
  });
});
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

.custom-row td:first-child {
  border-top-right-radius: 12px;
  border-bottom-right-radius: 12px;
  border-right: 1px solid #f1f5f9;
}

.custom-row td:last-child {
  border-top-left-radius: 12px;
  border-bottom-left-radius: 12px;
  border-left: 1px solid #f1f5f9;
}

.custom-row:hover {
  background-color: #f8fafc;
}

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

.urgency-critical {
  background-color: #fff1f2;
  color: #e11d48;
}

.urgency-high {
  background-color: #fef2f2;
  color: #dc2626;
}

.urgency-medium {
  background-color: #fffbe6;
  color: #d97706;
}

.urgency-low {
  background-color: #f0fdf4;
  color: #16a34a;
}

.status-active {
  background-color: #fff1f2;
  color: #e11d48;
}

.status-processing {
  background-color: #fff7ed;
  color: #ea580c;
}

.status-completed {
  background-color: #f0fdf4;
  color: #16a34a;
}

.progress-bar-flat {
  background-color: #e2e8f0;
  border-radius: 4px;
  overflow: hidden;
}
</style>
