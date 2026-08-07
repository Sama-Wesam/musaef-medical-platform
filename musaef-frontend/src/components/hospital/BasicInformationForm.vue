<template>
  <form class="settings-card" @submit.prevent="$emit('submit')">
    <!-- بيانات الحساب الأساسية -->
    <section class="form-section">
      <div class="section-title">
        <i class="bi bi-person"></i>

        <h2>بيانات الحساب الأساسية</h2>
      </div>

      <div class="form-grid form-grid-three">
        <!-- اسم الجهة الطبية -->
        <div class="form-group">
          <label for="facilityName">اسم الجهة الطبية</label>

          <div
            class="input-wrapper"
            :class="{ invalid: errors.name }"
          >
            <input
              id="facilityName"
              :value="modelValue.name"
              type="text"
              placeholder="مجمع الشفاء الطبي"
              @input="updateField('name', $event.target.value)"
            />

            <i class="bi bi-building"></i>
          </div>

          <small v-if="errors.name" class="error-message">
            {{ errors.name }}
          </small>
        </div>

        <!-- البريد الإلكتروني -->
        <div class="form-group">
          <label for="facilityEmail">البريد الإلكتروني الرسمي</label>

          <div
            class="input-wrapper"
            :class="{ invalid: errors.email }"
          >
            <input
              id="facilityEmail"
              :value="modelValue.email"
              type="email"
              dir="ltr"
              placeholder="info@hospital.ps"
              @input="updateField('email', $event.target.value)"
            />

            <i class="bi bi-envelope"></i>
          </div>

          <small v-if="errors.email" class="error-message">
            {{ errors.email }}
          </small>
        </div>

        <!-- رقم الهاتف -->
        <div class="form-group">
          <label for="facilityPhone">رقم الهاتف الأساسي</label>

          <div
            class="input-wrapper"
            :class="{ invalid: errors.phone }"
          >
            <input
              id="facilityPhone"
              :value="modelValue.phone"
              type="tel"
              dir="ltr"
              placeholder="+970 562145896"
              @input="updateField('phone', $event.target.value)"
            />

            <i class="bi bi-telephone"></i>
          </div>

          <small v-if="errors.phone" class="error-message">
            {{ errors.phone }}
          </small>
        </div>
      </div>
    </section>

    <!-- البيانات الجغرافية والتشغيلية -->
    <section class="form-section operational-section">
      <div class="section-title">
        <i class="bi bi-geo-alt"></i>

        <h2>البيانات الجغرافية والتشغيلية</h2>
      </div>

      <div class="form-grid form-grid-three">
        <!-- المنطقة -->
        <div class="form-group">
          <label for="facilityCity">المنطقة / المدينة</label>

          <div
            class="input-wrapper"
            :class="{ invalid: errors.city }"
          >
            <select
              id="facilityCity"
              :value="modelValue.city"
              @change="updateField('city', $event.target.value)"
            >
              <option value="" disabled>اختر المدينة</option>
              <option value="غزة">غزة</option>
              <option value="خان يونس">خان يونس</option>
              <option value="رفح">رفح</option>
              <option value="دير البلح">دير البلح</option>
              <option value="شمال غزة">شمال غزة</option>
              <option value="رام الله">رام الله</option>
              <option value="نابلس">نابلس</option>
              <option value="الخليل">الخليل</option>
              <option value="القدس">القدس</option>
              <option value="جنين">جنين</option>
            </select>

            <i class="bi bi-geo-alt"></i>
          </div>

          <small v-if="errors.city" class="error-message">
            {{ errors.city }}
          </small>
        </div>

        <!-- العنوان -->
        <div class="form-group">
          <label for="facilityAddress">العنوان التفصيلي</label>

          <div
            class="input-wrapper"
            :class="{ invalid: errors.address }"
          >
            <input
              id="facilityAddress"
              :value="modelValue.address"
              type="text"
              placeholder="شارع المستشفى"
              @input="updateField('address', $event.target.value)"
            />

            <i class="bi bi-geo"></i>
          </div>

          <small v-if="errors.address" class="error-message">
            {{ errors.address }}
          </small>
        </div>

        <!-- ساعات العمل -->
        <div class="form-group">
          <label for="workingHours">ساعات العمل</label>

          <div
            class="input-wrapper"
            :class="{ invalid: errors.workingHours }"
          >
            <select
              id="workingHours"
              :value="modelValue.workingHours"
              @change="updateField('workingHours', $event.target.value)"
            >
              <option value="" disabled>حدد ساعات العمل</option>
              <option value="24/7">24 ساعة / 7 أيام في الأسبوع</option>
              <option value="08:00-16:00">من 8 صباحاً حتى 4 مساءً</option>
              <option value="08:00-20:00">من 8 صباحاً حتى 8 مساءً</option>
              <option value="custom">ساعات عمل مخصصة</option>
            </select>

            <i class="bi bi-clock"></i>
          </div>

          <small v-if="errors.workingHours" class="error-message">
            {{ errors.workingHours }}
          </small>
        </div>
      </div>
    </section>

    <!-- الأزرار -->
    <div class="form-actions">
      <button
        type="submit"
        class="save-button"
        :disabled="loading"
      >
        <span
          v-if="loading"
          class="spinner-border spinner-border-sm"
          aria-hidden="true"
        ></span>

        <i v-else class="bi bi-check2-circle"></i>

        <span>
          {{ loading ? 'جارٍ الحفظ...' : 'تحديث المستندات الرسمية' }}
        </span>
      </button>

      <button
        type="button"
        class="history-button"
        :disabled="loading"
        @click="$emit('show-history')"
      >
        <i class="bi bi-clock-history"></i>
        عرض سجل المراجعات
      </button>
    </div>
  </form>
</template>

<script setup>
const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },

  loading: {
    type: Boolean,
    default: false,
  },

  errors: {
    type: Object,
    default: () => ({}),
  },
})

const emit = defineEmits([
  'update:modelValue',
  'submit',
  'show-history',
])

const updateField = (field, value) => {
  emit('update:modelValue', {
    ...props.modelValue,
    [field]: value,
  })
}
</script>

<style scoped>
.settings-card {
  padding: 58px 25px 46px;
  border: 1px solid #eeeeF1;
  border-radius: 15px;
  background-color: #ffffff;
}

.form-section + .form-section {
  margin-top: 45px;
}

.section-title {
  display: flex;
  align-items: center;
  gap: 9px;
  margin-bottom: 28px;
}

.section-title i {
  color: #df272b;
  font-size: 24px;
}

.section-title h2 {
  margin: 0;
  font-size: 18px;
  font-weight: 800;
}

.form-grid {
  display: grid;
  gap: 26px;
}

.form-grid-three {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}

.form-group {
  min-width: 0;
}

.form-group label {
  display: block;
  margin-bottom: 11px;
  color: #292929;
  font-size: 13px;
  font-weight: 700;
}

.input-wrapper {
  position: relative;
}

.input-wrapper input,
.input-wrapper select {
  width: 100%;
  height: 44px;
  padding: 0 16px 0 44px;
  border: 1px solid #dedee3;
  border-radius: 4px;
  outline: none;
  background-color: #ffffff;
  color: #5d5d5d;
  font-family: inherit;
  font-size: 13px;
  transition:
    border-color 0.2s ease,
    box-shadow 0.2s ease;
}

.input-wrapper select {
  cursor: pointer;
  appearance: none;
}

.input-wrapper input::placeholder {
  color: #9b9b9f;
}

.input-wrapper input:focus,
.input-wrapper select:focus {
  border-color: #df272b;
  box-shadow: 0 0 0 3px rgba(223, 39, 43, 0.1);
}

.input-wrapper > i {
  position: absolute;
  top: 50%;
  left: 15px;
  color: #c4c4c9;
  font-size: 15px;
  pointer-events: none;
  transform: translateY(-50%);
}

.input-wrapper.invalid input,
.input-wrapper.invalid select {
  border-color: #dc3545;
}

.error-message {
  display: block;
  margin-top: 7px;
  color: #dc3545;
  font-size: 12px;
}

.form-actions {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-top: 83px;
}

.save-button,
.history-button {
  display: inline-flex;
  min-height: 46px;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border-radius: 4px;
  font-family: inherit;
  font-size: 14px;
  font-weight: 800;
  transition:
    background-color 0.2s ease,
    border-color 0.2s ease,
    transform 0.2s ease;
}

.save-button {
  min-width: 310px;
  padding: 10px 25px;
  border: 1px solid #df272b;
  background-color: #df272b;
  color: #ffffff;
}

.save-button:hover:not(:disabled) {
  border-color: #c81f23;
  background-color: #c81f23;
  transform: translateY(-1px);
}

.history-button {
  min-width: 188px;
  padding: 10px 20px;
  border: 1px solid #dcdce1;
  background-color: #ffffff;
  color: #212121;
}

.history-button:hover:not(:disabled) {
  border-color: #df272b;
  color: #df272b;
}

.save-button:disabled,
.history-button:disabled {
  cursor: not-allowed;
  opacity: 0.65;
}

@media (max-width: 991px) {
  .form-grid-three {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .settings-card {
    padding: 40px 22px;
  }
}

@media (max-width: 650px) {
  .form-grid-three {
    grid-template-columns: 1fr;
  }

  .settings-card {
    padding: 30px 16px;
  }

  .form-actions {
    flex-direction: column;
    align-items: stretch;
    margin-top: 50px;
  }

  .save-button,
  .history-button {
    width: 100%;
    min-width: 0;
  }
}
</style>