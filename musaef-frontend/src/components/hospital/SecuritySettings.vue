<template>
  <div class="security-layout">
    <!-- تغيير كلمة المرور -->
    <form
      class="security-card password-card"
      @submit.prevent="submitPassword"
    >
      <div class="card-heading">
        <h2>تغيير كلمة المرور</h2>

        <p>
          قم بتحديث كلمة المرور الخاصة بحسابك
        </p>
      </div>

      <div class="password-fields">
        <!-- كلمة المرور الحالية -->
        <div class="form-group">
          <label for="currentPassword">
            كلمة المرور الحالية
          </label>

          <div
            class="password-input"
            :class="{ invalid: errors.currentPassword }"
          >
            <input
              id="currentPassword"
              v-model="passwordForm.currentPassword"
              :type="showCurrentPassword ? 'text' : 'password'"
              placeholder="كلمة المرور الحالية"
            />

            <button
              type="button"
              class="toggle-password"
              aria-label="إظهار أو إخفاء كلمة المرور الحالية"
              @click="showCurrentPassword = !showCurrentPassword"
            >
              <i
                :class="
                  showCurrentPassword
                    ? 'bi bi-eye-slash'
                    : 'bi bi-eye'
                "
              ></i>
            </button>

            <i class="bi bi-lock input-lock"></i>
          </div>

          <small
            v-if="errors.currentPassword"
            class="error-message"
          >
            {{ errors.currentPassword }}
          </small>
        </div>

        <!-- كلمة المرور الجديدة -->
        <div class="form-group">
          <label for="newPassword">
            كلمة المرور الجديدة
          </label>

          <div
            class="password-input"
            :class="{ invalid: errors.newPassword }"
          >
            <input
              id="newPassword"
              v-model="passwordForm.newPassword"
              :type="showNewPassword ? 'text' : 'password'"
              placeholder="كلمة المرور الجديدة"
            />

            <button
              type="button"
              class="toggle-password"
              aria-label="إظهار أو إخفاء كلمة المرور الجديدة"
              @click="showNewPassword = !showNewPassword"
            >
              <i
                :class="
                  showNewPassword
                    ? 'bi bi-eye-slash'
                    : 'bi bi-eye'
                "
              ></i>
            </button>

            <i class="bi bi-lock input-lock"></i>
          </div>

          <small
            v-if="errors.newPassword"
            class="error-message"
          >
            {{ errors.newPassword }}
          </small>
        </div>

        <!-- تأكيد كلمة المرور -->
        <div class="form-group">
          <label for="confirmPassword">
            تأكيد كلمة المرور الجديدة
          </label>

          <div
            class="password-input"
            :class="{ invalid: errors.confirmPassword }"
          >
            <input
              id="confirmPassword"
              v-model="passwordForm.confirmPassword"
              :type="showConfirmPassword ? 'text' : 'password'"
              placeholder="تأكيد كلمة المرور الجديدة"
            />

            <button
              type="button"
              class="toggle-password"
              aria-label="إظهار أو إخفاء تأكيد كلمة المرور"
              @click="showConfirmPassword = !showConfirmPassword"
            >
              <i
                :class="
                  showConfirmPassword
                    ? 'bi bi-eye-slash'
                    : 'bi bi-eye'
                "
              ></i>
            </button>

            <i class="bi bi-lock input-lock"></i>
          </div>

          <small
            v-if="errors.confirmPassword"
            class="error-message"
          >
            {{ errors.confirmPassword }}
          </small>
        </div>
      </div>

      <button
        type="submit"
        class="update-password-button"
        :disabled="loading"
      >
        <span
          v-if="loading"
          class="spinner-border spinner-border-sm"
        ></span>

        <span>
          {{ loading ? 'جارٍ التحديث...' : 'تحديث كلمة المرور' }}
        </span>
      </button>
    </form>

    <!-- الجلسات النشطة -->
    <section class="security-card sessions-card">
      <div class="card-heading">
        <h2>الجلسات النشطة</h2>

        <p>
          الأجهزة والجلسات المتصلة بحسابك حالياً
        </p>
      </div>

      <div class="current-session">
        <div>
          <strong>الجلسة الحالية</strong>
          <span>غزة · Chrome · قطاع غزة</span>
        </div>

        <span class="active-badge">
          <i class="bi bi-circle-fill"></i>
          نشط الآن
        </span>
      </div>

      <div class="sessions-list">
        <article
          v-for="session in sessions"
          :key="session.id"
          class="session-item"
        >
          <strong>{{ session.date }}</strong>

          <span>
            {{ session.device }} · {{ session.location }}
          </span>
        </article>
      </div>

      <button
        type="button"
        class="logout-devices-button"
        @click="logoutOtherDevices"
      >
        تسجيل الخروج من جميع الأجهزة الأخرى
      </button>
    </section>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'

const loading = ref(false)

const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)

const passwordForm = reactive({
  currentPassword: '',
  newPassword: '',
  confirmPassword: '',
})

const errors = reactive({})

const sessions = ref([
  {
    id: 1,
    date: 'آخر نشاط أمس 10:24 ص',
    device: 'Dell',
    location: 'قطاع غزة',
  },
  {
    id: 2,
    date: 'آخر نشاط 12 مايو 2026',
    device: 'MacBook Pro',
    location: 'قطاع غزة',
  },
  {
    id: 3,
    date: 'آخر نشاط 10 مايو 2026',
    device: 'Android',
    location: 'قطاع غزة',
  },
])

const clearErrors = () => {
  Object.keys(errors).forEach((key) => {
    delete errors[key]
  })
}

const validatePassword = () => {
  clearErrors()

  if (!passwordForm.currentPassword) {
    errors.currentPassword = 'كلمة المرور الحالية مطلوبة'
  }

  if (!passwordForm.newPassword) {
    errors.newPassword = 'كلمة المرور الجديدة مطلوبة'
  } else if (passwordForm.newPassword.length < 8) {
    errors.newPassword = 'يجب أن تكون كلمة المرور 8 أحرف على الأقل'
  }

  if (!passwordForm.confirmPassword) {
    errors.confirmPassword = 'يرجى تأكيد كلمة المرور الجديدة'
  } else if (
    passwordForm.confirmPassword !== passwordForm.newPassword
  ) {
    errors.confirmPassword = 'كلمتا المرور غير متطابقتين'
  }

  return Object.keys(errors).length === 0
}

const submitPassword = async () => {
  if (!validatePassword()) {
    return
  }

  loading.value = true

  try {
    await new Promise((resolve) => {
      setTimeout(resolve, 700)
    })

    alert('تم تحديث كلمة المرور بنجاح')

    passwordForm.currentPassword = ''
    passwordForm.newPassword = ''
    passwordForm.confirmPassword = ''
  } catch (error) {
    console.error(error)
    alert('حدث خطأ أثناء تحديث كلمة المرور')
  } finally {
    loading.value = false
  }
}

const logoutOtherDevices = () => {
  const confirmed = confirm(
    'هل تريد تسجيل الخروج من جميع الأجهزة الأخرى؟',
  )

  if (!confirmed) {
    return
  }

  sessions.value = []

  alert('تم تسجيل الخروج من جميع الأجهزة الأخرى')
}
</script>

<style scoped>
.security-layout {
  display: grid;
  grid-template-columns: minmax(0, 1.55fr) minmax(280px, 0.95fr);
  gap: 20px;
  align-items: start;
}

.security-card {
  border: 1px solid #eceef2;
  border-radius: 14px;
  background-color: #ffffff;
}

.password-card {
  padding: 28px 24px 36px;
}

.sessions-card {
  padding: 24px 14px 30px;
}

.card-heading {
  margin-bottom: 25px;
  text-align: right;
}

.card-heading h2 {
  margin: 0 0 8px;
  color: #111827;
  font-size: 18px;
  font-weight: 800;
}

.card-heading p {
  margin: 0;
  color: #9ca3af;
  font-size: 12px;
}

.password-fields {
  display: flex;
  flex-direction: column;
  gap: 21px;
}

.form-group label {
  display: block;
  margin-bottom: 9px;
  color: #d1d5db;
  font-size: 12px;
  font-weight: 500;
}

.password-input {
  position: relative;
}

.password-input input {
  width: 100%;
  height: 46px;
  padding: 0 44px 0 44px;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  outline: none;
  color: #374151;
  font-family: inherit;
  font-size: 13px;
  direction: rtl;
}

.password-input input:focus {
  border-color: #ef4444;
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.password-input.invalid input {
  border-color: #dc3545;
}

.input-lock {
  position: absolute;
  top: 50%;
  left: 14px;
  color: #d1d5db;
  transform: translateY(-50%);
}

.toggle-password {
  width: 34px;
  height: 34px;
  position: absolute;
  top: 50%;
  right: 7px;
  display: grid;
  place-items: center;
  border: 0;
  background-color: transparent;
  color: #9ca3af;
  transform: translateY(-50%);
  cursor: pointer;
}

.error-message {
  display: block;
  margin-top: 6px;
  color: #dc3545;
  font-size: 11px;
}

.update-password-button {
  width: 100%;
  min-height: 44px;
  margin-top: 38px;
  border: 0;
  border-radius: 5px;
  background-color: #df272b;
  color: #ffffff;
  font-family: inherit;
  font-size: 14px;
  font-weight: 800;
  cursor: pointer;
}

.update-password-button:hover:not(:disabled) {
  background-color: #c91f24;
}

.update-password-button:disabled {
  cursor: not-allowed;
  opacity: 0.7;
}

.current-session {
  min-height: 54px;
  padding: 10px 8px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  border: 1px solid #d1d5db;
  border-radius: 5px;
}

.current-session div,
.session-item {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.current-session strong,
.session-item strong {
  color: #111827;
  font-size: 11px;
  font-weight: 800;
}

.current-session span,
.session-item span {
  color: #9ca3af;
  font-size: 10px;
}

.active-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 8px;
  border-radius: 4px;
  background-color: #eaf8ee;
  color: #38a169 !important;
  white-space: nowrap;
}

.active-badge i {
  font-size: 7px;
}

.sessions-list {
  display: flex;
  flex-direction: column;
  gap: 13px;
  margin-top: 14px;
}

.session-item {
  min-height: 56px;
  padding: 11px 9px;
  justify-content: center;
  border: 1px solid #e5e7eb;
  border-radius: 5px;
}

.logout-devices-button {
  width: 100%;
  margin-top: 23px;
  border: 0;
  background-color: transparent;
  color: #ef4444;
  font-family: inherit;
  font-size: 13px;
  font-weight: 800;
  cursor: pointer;
}

.logout-devices-button:hover {
  color: #c91f24;
}

@media (max-width: 900px) {
  .security-layout {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 600px) {
  .password-card,
  .sessions-card {
    padding: 20px 15px;
  }
}
</style>