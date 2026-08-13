<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="mb-3 d-flex justify-content-between align-items-center" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
      <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">{{ t('inventoryTitle') }}</h5>
      <span v-if="loading" class="spinner-border spinner-border-sm text-danger" role="status"></span>
    </div>

    <div class="table-responsive">
      <table class="table align-middle text-center mb-0 fs-8 border-0 min-w-table">
        <thead class="bg-light border-bottom">
          <tr class="text-muted text-nowrap">
            <th class="py-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">{{ t('requiredType') }}</th>
            <th class="py-2">{{ t('availableUnits') }}</th>
            <th class="py-2">{{ t('minRequired') }}</th>
            <th class="py-2">{{ t('status') }}</th>
            <th class="py-2">{{ t('respondersCount') }}</th>
            <th class="py-2">{{ t('actions') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="formattedItems.length === 0 && !loading">
            <td colspan="6" class="py-4 text-muted text-center">
              {{ currentLanguage === 'en' ? 'No inventory records found.' : 'لا توجد بيانات مخزون حالية.' }}
            </td>
          </tr>

          <tr v-for="item in formattedItems" :key="item.type || item.id" class="border-bottom-subtle text-nowrap">
            <td class="fw-bold text-dark py-2.5 fs-7" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">{{ item.type }}</td>
            <td class="fw-bold text-dark py-2.5">{{ item.available }}</td>
            <td class="text-muted py-2.5">{{ item.minRequired }}</td>
            <td class="py-2.5">
              <span :class="['badge rounded-pill px-3 py-1 fs-9', getStatusBadgeClass(item.statusRaw)]">
                {{ translateStatus(item.statusRaw) }}
              </span>
            </td>
            <td class="py-2.5">
              <div class="d-flex align-items-center justify-content-center gap-2" dir="ltr">
                <span class="fs-9 text-muted fw-bold">{{ item.percentage }}%</span>
                <div class="progress flex-grow-1" style="height: 4px; min-width: 50px; max-width: 80px;">
                  <div
                    class="progress-bar"
                    :class="getProgressBarClass(item.statusRaw)"
                    :style="{ width: Math.min(item.percentage, 100) + '%' }"
                  ></div>
                </div>
              </div>
            </td>
            <td class="py-2.5">
              <button class="btn btn-outline-secondary btn-sm rounded-pill px-2.5 px-md-3 fs-9 text-nowrap fw-bold" @click="handleViewDetails(item)">
                {{ t('viewDetails') }}
              </button>
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
  items: {
    type: Array,
    required: true,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    inventoryTitle: 'مخزون بنك الدم',
    requiredType: 'الفصيلة المطلوبة',
    availableUnits: 'عدد الوحدات المتوفرة',
    minRequired: 'الحد الأدنى المطلوب',
    status: 'الحالة',
    respondersCount: 'نسبة الكفاية',
    actions: 'الإجراءات',
    viewDetails: 'عرض التفاصيل'
  },
  en: {
    inventoryTitle: 'Blood Bank Inventory',
    requiredType: 'Required Type',
    availableUnits: 'Available Units',
    minRequired: 'Min Required',
    status: 'Status',
    respondersCount: 'Coverage Rate',
    actions: 'Actions',
    viewDetails: 'View Details'
  }
};

const statusDict = {
  'طبيعي': { en: 'Normal', ar: 'طبيعي' },
  'Normal': { en: 'Normal', ar: 'طبيعي' },
  'حرج': { en: 'Critical', ar: 'حرج' },
  'Critical': { en: 'Critical', ar: 'حرج' },
  'منخفض': { en: 'Low', ar: 'منخفض' },
  'Low': { en: 'Low', ar: 'منخفض' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const translateStatus = (st) => {
  if (!st) return currentLanguage.value === 'en' ? 'Normal' : 'طبيعي';
  if (statusDict[st]) {
    return statusDict[st][currentLanguage.value === 'en' ? 'en' : 'ar'];
  }
  return st;
};

const getStatusBadgeClass = (status) => {
  if (status === 'حرج' || status === 'Critical') return 'bg-danger-subtle text-danger';
  if (status === 'منخفض' || status === 'Low') return 'bg-warning-subtle text-warning-emphasis';
  return 'bg-success-subtle text-success';
};

const getProgressBarClass = (status) => {
  if (status === 'حرج' || status === 'Critical') return 'bg-danger';
  if (status === 'منخفض' || status === 'Low') return 'bg-warning';
  return 'bg-success';
};

const formattedItems = computed(() => {
  return props.items.map(item => {
    const min = item.minRequired || item.min_required || 10;
    const avail = item.available ?? item.units ?? 0;
    const percentage = item.percentage ?? Math.round((avail / min) * 100);

    return {
      ...item,
      type: item.type || item.blood_type || 'O-',
      available: avail,
      minRequired: min,
      percentage: percentage,
      statusRaw: item.statusRaw || item.status || 'طبيعي'
    };
  });
});

const handleViewDetails = (item) => {
  alert(currentLanguage.value === 'en'
    ? `📊 Blood Group (${item.type}) Inventory Details:\n- Available Units: ${item.available}\n- Minimum Required: ${item.minRequired}\n- Status: ${translateStatus(item.statusRaw)}\n- Coverage Rate: ${item.percentage}%`
    : `📊 تفاصيل مخزون فصيلة الدم (${item.type}):\n- الوحدات المتوفرة: ${item.available}\n- الحد الأدنى المطلوب: ${item.minRequired}\n- الحالة: ${translateStatus(item.statusRaw)}\n- نسبة التغطية: ${item.percentage}%`);
};
</script>

<style scoped>
.fs-7 { font-size: 0.88rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.text-warning-emphasis { color: #b45309 !important; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
.min-w-table { min-width: 580px; }
</style>
