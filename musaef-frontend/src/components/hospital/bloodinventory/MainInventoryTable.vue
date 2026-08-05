<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="mb-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
      <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">{{ t('inventoryTitle') }}</h5>
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
          <tr v-for="item in formattedItems" :key="item.type" class="border-bottom-subtle text-nowrap">
            <td class="fw-bold text-dark py-2.5 fs-7" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">{{ item.type }}</td>
            <td class="fw-bold text-dark py-2.5">{{ item.available }}</td>
            <td class="text-muted py-2.5">{{ item.minRequired }}</td>
            <td class="py-2.5">
              <span :class="['badge rounded-pill px-3 py-1 fs-9', item.statusClass]">
                {{ translateStatus(item.statusRaw) }}
              </span>
            </td>
            <td class="py-2.5">
              <div class="d-flex align-items-center justify-content-center gap-2" dir="ltr">
                <span class="fs-9 text-muted fw-bold">{{ item.percentage }}%</span>
                <div class="progress flex-grow-1" style="height: 4px; min-width: 50px; max-width: 80px;">
                  <div
                    class="progress-bar"
                    :class="item.progressClass"
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

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    inventoryTitle: 'مخزون بنك الدم',
    requiredType: 'الفصيلة المطلوبة',
    availableUnits: 'عدد الوحدات المتوفرة',
    minRequired: 'الحد الأدنى المطلوب',
    status: 'الحالة',
    respondersCount: 'عدد المستجيبين',
    actions: 'الإجراءات',
    viewDetails: 'عرض التفاصيل'
  },
  en: {
    inventoryTitle: 'Blood Bank Inventory',
    requiredType: 'Required Type',
    availableUnits: 'Available Units',
    minRequired: 'Min Required',
    status: 'Status',
    respondersCount: 'Responders Count',
    actions: 'Actions',
    viewDetails: 'View Details'
  }
};

const statusDict = {
  'طبيعي': { en: 'Normal', ar: 'طبيعي' },
  'حرج': { en: 'Critical', ar: 'حرج' },
  'منخفض': { en: 'Low', ar: 'منخفض' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const translateStatus = (st) => {
  if (!st) return currentLanguage.value === 'en' ? 'Normal' : 'طبيعي';
  if (statusDict[st]) {
    return statusDict[st][currentLanguage.value === 'en' ? 'en' : 'ar'];
  }
  return st;
};

const props = defineProps({
  items: {
    type: Array,
    required: true
  }
});

const defaultItems = [
  { type: 'O-', available: 256, minRequired: 100, statusRaw: 'طبيعي', status: 'طبيعي', statusClass: 'bg-success-subtle text-success', percentage: 256, progressClass: 'bg-success' },
  { type: 'A+', available: 2, minRequired: 10, statusRaw: 'حرج', status: 'حرج', statusClass: 'bg-danger-subtle text-danger', percentage: 20, progressClass: 'bg-danger' },
  { type: 'B-', available: 180, minRequired: 80, statusRaw: 'طبيعي', status: 'طبيعي', statusClass: 'bg-success-subtle text-success', percentage: 225, progressClass: 'bg-success' },
  { type: 'AB+', available: 20, minRequired: 20, statusRaw: 'طبيعي', status: 'طبيعي', statusClass: 'bg-success-subtle text-success', percentage: 100, progressClass: 'bg-success' },
  { type: 'O+', available: 60, minRequired: 60, statusRaw: 'طبيعي', status: 'طبيعي', statusClass: 'bg-success-subtle text-success', percentage: 100, progressClass: 'bg-success' },
  { type: 'A-', available: 15, minRequired: 15, statusRaw: 'منخفض', status: 'منخفض', statusClass: 'bg-warning-subtle text-warning-emphasis', percentage: 33, progressClass: 'bg-warning' },
  { type: 'B+', available: 30, minRequired: 30, statusRaw: 'طبيعي', status: 'طبيعي', statusClass: 'bg-success-subtle text-success', percentage: 100, progressClass: 'bg-success' },
  { type: 'AB-', available: 10, minRequired: 10, statusRaw: 'منخفض', status: 'منخفض', statusClass: 'bg-warning-subtle text-warning-emphasis', percentage: 60, progressClass: 'bg-warning' }
];

const formattedItems = computed(() => {
  const source = (props.items && props.items.length > 0) ? props.items : defaultItems;
  return source.map(item => ({
    ...item,
    statusRaw: item.statusRaw || item.status || 'طبيعي'
  }));
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
