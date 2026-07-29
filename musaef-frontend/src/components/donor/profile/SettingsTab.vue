<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-end">
    <h5 class="fw-bold text-dark mb-3 border-bottom pb-3 fs-6 fs-md-5">
      <i class="bi bi-gear text-danger me-2"></i> إعدادات الحساب
    </h5>
    <div class="p-3 bg-light rounded-3 mb-3">
      <h6 class="fw-bold text-dark fs-8 mb-2">الإشعارات والتنبيهات والتوفر</h6>
      <div class="form-check form-switch d-flex align-items-center justify-content-between p-0 flex-wrap gap-2">
        <label class="form-check-label fs-8 text-secondary ms-2 flex-grow-1 min-w-0" for="notifSwitch">استقبال إشعارات وتنبيهات الحالات العاجلة (متاح للتبرع)</label>
        <input
          class="form-check-input ms-0 custom-switch flex-shrink-0"
          type="checkbox"
          id="notifSwitch"
          v-model="isAvailable"
          @change="updateAvailability"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import apiClient from '@/api/axios';

const props = defineProps({
  settings: {
    type: Object,
    default: () => ({ is_available: true })
  }
});

const isAvailable = ref(props.settings?.is_available ?? true);

watch(() => props.settings, (newVal) => {
  if (newVal) {
    isAvailable.value = newVal.is_available ?? true;
  }
}, { deep: true });

const updateAvailability = async () => {
  try {
    await apiClient.post('/donor/profile/update', { is_available: isAvailable.value });
    alert('تم تحديث حالة التوفر والإشعارات بنجاح!');
  } catch (error) {
    console.error('خطأ في تحديث الإعدادات:', error);
    alert('حدث خطأ أثناء حفظ الإعدادات.');
  }
};
</script>

<style scoped>
.custom-switch { accent-color: #dc2626; width: 40px; height: 20px; cursor: pointer; }
.fs-8 { font-size: 0.82rem; }
</style>
