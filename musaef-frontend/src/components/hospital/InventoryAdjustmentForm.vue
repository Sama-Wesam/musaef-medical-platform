<template>
  <section
    class="adjustment-card"
    :class="variant"
  >
    <h3>
      {{ variant === 'add' ? 'إضافة وحدات' : 'سحب وحدات' }}
    </h3>

    <div class="form-grid">
      <label>
        <span>فصيلة الدم</span>

        <select v-model="form.bloodType">
          <option
            v-for="type in bloodTypes"
            :key="type"
            :value="type"
          >
            {{ type }}
          </option>
        </select>
      </label>

      <label>
        <span>عدد الوحدات</span>

        <input
          v-model.number="form.units"
          type="number"
          min="1"
        />
      </label>

      <label>
        <span>المصدر</span>

        <select v-model="form.source">
          <option value="تبرع مباشر">تبرع مباشر</option>
          <option value="بنك دم آخر">بنك دم آخر</option>
          <option value="طلب مستشفى">طلب مستشفى</option>
        </select>
      </label>
    </div>

    <p>أضف ملاحظات عند الضرورة.</p>

    <button
      type="button"
      @click="submitForm"
    >
      {{
        variant === 'add'
          ? 'إضافة الوحدات'
          : 'سحب الوحدات'
      }}
    </button>
  </section>
</template>

<script setup>
import { reactive } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    validator: (value) =>
      ['add', 'remove'].includes(value),
    required: true,
  },
})

const emit = defineEmits(['submit'])

const bloodTypes = [
  'O-',
  'O+',
  'A-',
  'A+',
  'B-',
  'B+',
  'AB-',
  'AB+',
]

const form = reactive({
  bloodType: 'O-',
  units: 1,
  source: 'تبرع مباشر',
})

const submitForm = () => {
  if (!form.bloodType || form.units < 1) {
    return
  }

  emit('submit', {
    bloodType: form.bloodType,
    units: form.units,
    source: form.source,
  })

  form.units = 1
}
</script>

<style scoped>
.adjustment-card {
  padding: 15px;
  border: 1px solid #eceef2;
  border-radius: 9px;
  background-color: #ffffff;
}

.adjustment-card h3 {
  margin: 0 0 13px;
  text-align: center;
  font-size: 11px;
  font-weight: 800;
}

.adjustment-card.add h3 {
  color: #16a34a;
}

.adjustment-card.remove h3 {
  color: #dc2626;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 9px;
}

label {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

label span {
  color: #374151;
  font-size: 7px;
}

select,
input {
  width: 100%;
  height: 30px;
  padding: 0 8px;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  outline: none;
  background-color: #ffffff;
  color: #111827;
  font-size: 8px;
}

.adjustment-card p {
  margin: 10px 0;
  color: #9ca3af;
  text-align: center;
  font-size: 7px;
}

.adjustment-card button {
  width: 100%;
  min-height: 31px;
  border: 0;
  border-radius: 5px;
  color: #ffffff;
  font-size: 19px;
  font-weight: 700;
  cursor: pointer;
}

.adjustment-card.add button {
  background-color: #22a447;
}

.adjustment-card.remove button {
  background-color: #dc2626;
}

@media (max-width: 550px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
}
</style>