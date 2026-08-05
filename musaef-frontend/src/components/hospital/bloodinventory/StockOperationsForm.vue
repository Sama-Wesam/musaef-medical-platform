<template>
  <div class="row g-3 g-lg-4" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <!-- نموذج إضافة وحدات -->
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-center" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <h6 class="fw-bold text-success mb-3">{{ t('addUnitsTitle') }}</h6>
        <form @submit.prevent="submitAdd">
          <div class="row g-2 g-md-3 mb-3">
            <div class="col-12 col-sm-6" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <label class="form-label fs-8 text-muted d-block">{{ t('hospitalLabel') }}</label>
              <input type="text" class="form-control form-control-sm rounded-3 bg-light border-0" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'" :value="t('receivingUnitsValue')" disabled />
            </div>
            <div class="col-6 col-sm-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <label class="form-label fs-8 text-muted d-block">{{ t('unitsCountLabel') }}</label>
              <input v-model.number="addForm.units" type="number" min="1" class="form-control form-control-sm text-center rounded-3 bg-light border-0" required />
            </div>
            <div class="col-6 col-sm-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <label class="form-label fs-8 text-muted d-block">{{ t('chooseTypeLabel') }}</label>
              <select v-model="addForm.blood_type_id" class="form-select form-select-sm text-center rounded-3 bg-light border-0 fs-8" required>
                <option value="" disabled>{{ t('bloodTypePlaceholder') }}</option>
                <option v-for="type in bloodTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
              </select>
            </div>
          </div>
          <div class="mb-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            <label class="form-label fs-9 text-muted d-block">{{ t('notesLabel') }}</label>
            <input v-model="addForm.notes" type="text" class="form-control form-control-sm rounded-3 bg-light border-0" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'" :placeholder="t('notesPlaceholder')" />
          </div>
          <button type="submit" class="btn btn-success w-100 rounded-3 py-2 fw-bold fs-8" :disabled="loadingAdd">
            <span v-if="loadingAdd" class="spinner-border spinner-border-sm me-1" role="status"></span>
            {{ t('addBtn') }}
          </button>
        </form>
      </div>
    </div>

    <!-- نموذج سحب وحدات -->
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-center" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <h6 class="fw-bold text-danger mb-3">{{ t('deductUnitsTitle') }}</h6>
        <form @submit.prevent="submitDeduct">
          <div class="row g-2 g-md-3 mb-3">
            <div class="col-12 col-sm-6" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <label class="form-label fs-8 text-muted d-block">{{ t('hospitalLabel') }}</label>
              <input type="text" class="form-control form-control-sm rounded-3 bg-light border-0" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'" :value="t('receivingUnitsValue')" disabled />
            </div>
            <div class="col-6 col-sm-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <label class="form-label fs-8 text-muted d-block">{{ t('unitsCountLabel') }}</label>
              <input v-model.number="deductForm.units" type="number" min="1" class="form-control form-control-sm text-center rounded-3 bg-light border-0" required />
            </div>
            <div class="col-6 col-sm-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <label class="form-label fs-8 text-muted d-block">{{ t('chooseTypeLabel') }}</label>
              <select v-model="deductForm.blood_type_id" class="form-select form-select-sm text-center rounded-3 bg-light border-0 fs-8" required>
                <option value="" disabled>{{ t('bloodTypePlaceholder') }}</option>
                <option v-for="type in bloodTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
              </select>
            </div>
          </div>
          <div class="mb-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            <label class="form-label fs-9 text-muted d-block">{{ t('notesLabel') }}</label>
            <input v-model="deductForm.notes" type="text" class="form-control form-control-sm rounded-3 bg-light border-0" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'" :placeholder="t('notesPlaceholder')" />
          </div>
          <button type="submit" class="btn btn-danger w-100 rounded-3 py-2 fw-bold fs-8" :disabled="loadingDeduct">
            <span v-if="loadingDeduct" class="spinner-border spinner-border-sm me-1" role="status"></span>
            {{ t('deductBtn') }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useHospitalStore } from '@/stores/hospitalStore';

const emit = defineEmits(['refresh']);
const hospitalStore = useHospitalStore();

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    addUnitsTitle: 'إضافة وحدات',
    deductUnitsTitle: 'سحب وحدات',
    hospitalLabel: 'المستشفى',
    receivingUnitsValue: 'تلقي الوحدات',
    unitsCountLabel: 'عدد الوحدات',
    chooseTypeLabel: 'اختر الفصيلة',
    bloodTypePlaceholder: 'الفصيلة',
    notesLabel: 'أضف ملاحظات (اختياري)',
    notesPlaceholder: 'أدخل الملاحظات هنا...',
    addBtn: 'إضافة الوحدات',
    deductBtn: 'سحب الوحدات',
    addSuccess: '✅ تم إضافة الوحدات بنجاح!',
    deductSuccess: '✅ تم سحب الوحدات بنجاح!'
  },
  en: {
    addUnitsTitle: 'Add Blood Units',
    deductUnitsTitle: 'Deduct Blood Units',
    hospitalLabel: 'Hospital',
    receivingUnitsValue: 'Receiving Units',
    unitsCountLabel: 'Units Count',
    chooseTypeLabel: 'Select Type',
    bloodTypePlaceholder: 'Type',
    notesLabel: 'Add Notes (Optional)',
    notesPlaceholder: 'Enter notes here...',
    addBtn: 'Add Units',
    deductBtn: 'Deduct Units',
    addSuccess: '✅ Units added successfully!',
    deductSuccess: '✅ Units deducted successfully!'
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const loadingAdd = ref(false);
const loadingDeduct = ref(false);

const bloodTypes = [
  { id: 1, name: 'O-' },
  { id: 2, name: 'A+' },
  { id: 3, name: 'B-' },
  { id: 4, name: 'AB+' },
  { id: 5, name: 'O+' },
  { id: 6, name: 'A-' },
  { id: 7, name: 'B+' },
  { id: 8, name: 'AB-' }
];

const addForm = ref({ blood_type_id: '', units: 1, operation: 'add', notes: '' });
const deductForm = ref({ blood_type_id: '', units: 1, operation: 'sub', notes: '' });

const submitAdd = async () => {
  loadingAdd.value = true;
  try {
    const success = await hospitalStore.updateStockOperation(addForm.value);
    if (success !== false) {
      alert(t('addSuccess'));
      addForm.value = { blood_type_id: '', units: 1, operation: 'add', notes: '' };
      emit('refresh');
    }
  } catch (err) {
    const errorMsg = err.response?.data?.message || (currentLanguage.value === 'en' ? 'Error adding units' : 'حدث خطأ أثناء إضافة الوحدات');
    alert(`⚠️ ${errorMsg}`);
  } finally {
    loadingAdd.value = false;
  }
};

const submitDeduct = async () => {
  loadingDeduct.value = true;
  try {
    const success = await hospitalStore.updateStockOperation(deductForm.value);
    if (success !== false) {
      alert(t('deductSuccess'));
      deductForm.value = { blood_type_id: '', units: 1, operation: 'sub', notes: '' };
      emit('refresh');
    }
  } catch (err) {
    const errorMsg = err.response?.data?.message || (currentLanguage.value === 'en' ? 'Insufficient quantity to deduct' : 'لا توجد كمية كافية للخصم من المخزون');
    alert(`⚠️ ${errorMsg}`);
  } finally {
    loadingDeduct.value = false;
  }
};
</script>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
