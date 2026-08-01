<template>
  <div class="row g-3 g-lg-4 dir-rtl">
    <!-- نموذج إضافة وحدات -->
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-center text-end">
        <h6 class="fw-bold text-success mb-3">إضافة وحدات</h6>
        <form @submit.prevent="submitAdd">
          <div class="row g-2 g-md-3 mb-3">
            <div class="col-12 col-sm-6 text-end">
              <label class="form-label fs-8 text-muted d-block">المستشفى</label>
              <input type="text" class="form-control form-control-sm text-end rounded-3 bg-light border-0" value="تلقي الوحدات" disabled />
            </div>
            <div class="col-6 col-sm-3 text-end">
              <label class="form-label fs-8 text-muted d-block">عدد الوحدات</label>
              <input v-model.number="addForm.units" type="number" min="1" class="form-control form-control-sm text-center rounded-3 bg-light border-0" required />
            </div>
            <div class="col-6 col-sm-3 text-end">
              <label class="form-label fs-8 text-muted d-block">اختر الفصيلة</label>
              <select v-model="addForm.blood_type_id" class="form-select form-select-sm text-center rounded-3 bg-light border-0 fs-8" required>
                <option value="" disabled>الفصيلة</option>
                <option v-for="type in bloodTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
              </select>
            </div>
          </div>
          <div class="mb-3 text-end">
            <label class="form-label fs-9 text-muted d-block">أضف ملاحظات (اختياري)</label>
            <input v-model="addForm.notes" type="text" class="form-control form-control-sm rounded-3 bg-light border-0" placeholder="أدخل الملاحظات هنا..." />
          </div>
          <button type="submit" class="btn btn-success w-100 rounded-3 py-2 fw-bold fs-8" :disabled="loadingAdd">
            <span v-if="loadingAdd" class="spinner-border spinner-border-sm me-1" role="status"></span>
            إضافة الوحدات
          </button>
        </form>
      </div>
    </div>

    <!-- نموذج سحب وحدات -->
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-center text-end">
        <h6 class="fw-bold text-danger mb-3">سحب وحدات</h6>
        <form @submit.prevent="submitDeduct">
          <div class="row g-2 g-md-3 mb-3">
            <div class="col-12 col-sm-6 text-end">
              <label class="form-label fs-8 text-muted d-block">المستشفى</label>
              <input type="text" class="form-control form-control-sm text-end rounded-3 bg-light border-0" value="تلقي الوحدات" disabled />
            </div>
            <div class="col-6 col-sm-3 text-end">
              <label class="form-label fs-8 text-muted d-block">عدد الوحدات</label>
              <input v-model.number="deductForm.units" type="number" min="1" class="form-control form-control-sm text-center rounded-3 bg-light border-0" required />
            </div>
            <div class="col-6 col-sm-3 text-end">
              <label class="form-label fs-8 text-muted d-block">اختر الفصيلة</label>
              <select v-model="deductForm.blood_type_id" class="form-select form-select-sm text-center rounded-3 bg-light border-0 fs-8" required>
                <option value="" disabled>الفصيلة</option>
                <option v-for="type in bloodTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
              </select>
            </div>
          </div>
          <div class="mb-3 text-end">
            <label class="form-label fs-9 text-muted d-block">أضف ملاحظات (اختياري)</label>
            <input v-model="deductForm.notes" type="text" class="form-control form-control-sm rounded-3 bg-light border-0" placeholder="أدخل الملاحظات هنا..." />
          </div>
          <button type="submit" class="btn btn-danger w-100 rounded-3 py-2 fw-bold fs-8" :disabled="loadingDeduct">
            <span v-if="loadingDeduct" class="spinner-border spinner-border-sm me-1" role="status"></span>
            سحب الوحدات
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useHospitalStore } from '@/stores/hospitalStore';

const emit = defineEmits(['refresh']);
const hospitalStore = useHospitalStore();

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
      alert('✅ تم إضافة الوحدات بنجاح!');
      addForm.value = { blood_type_id: '', units: 1, operation: 'add', notes: '' };
      emit('refresh');
    }
  } catch (err) {
    const errorMsg = err.response?.data?.message || 'حدث خطأ أثناء إضافة الوحدات';
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
      alert('✅ تم سحب الوحدات بنجاح!');
      deductForm.value = { blood_type_id: '', units: 1, operation: 'sub', notes: '' };
      emit('refresh');
    }
  } catch (err) {
    const errorMsg = err.response?.data?.message || 'لا توجد كمية كافية للخصم من المخزون، تأكد من وجود كمية كافية للخصم';
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
</style>
