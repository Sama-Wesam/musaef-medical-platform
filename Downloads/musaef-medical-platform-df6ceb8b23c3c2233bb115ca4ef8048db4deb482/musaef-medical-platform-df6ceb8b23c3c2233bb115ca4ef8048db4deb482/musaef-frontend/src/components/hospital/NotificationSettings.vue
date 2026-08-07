<template>
  <form
    class="notification-settings-card"
    @submit.prevent="saveSettings"
  >
    <!-- إشعارات النظام العامة -->
    <section class="notification-section">
      <div class="section-heading">
        <i class="bi bi-bell"></i>

        <h2>إشعارات النظام العامة</h2>
      </div>

      <div class="settings-list">
        <article class="setting-row">
          <div class="setting-icon">
            <i class="bi bi-bell"></i>
          </div>

          <div class="setting-content">
            <h3>الإشعارات الفورية</h3>

            <p>
              استقبال تنبيهات فورية على المتصفح عند استلام طلب جديد
            </p>
          </div>

          <label class="switch">
            <input
              v-model="notificationForm.pushNotifications"
              type="checkbox"
            />

            <span class="slider"></span>
          </label>
        </article>

        <article class="setting-row">
          <div class="setting-icon">
            <i class="bi bi-envelope"></i>
          </div>

          <div class="setting-content">
            <h3>إشعارات البريد الإلكتروني</h3>

            <p>
              تلقي تقارير دورية وتنبيهات مهمة عبر البريد الإلكتروني
            </p>
          </div>

          <label class="switch">
            <input
              v-model="notificationForm.emailNotifications"
              type="checkbox"
            />

            <span class="slider"></span>
          </label>
        </article>
      </div>
    </section>

    <!-- تنبيهات المخزون الذكية -->
    <section class="notification-section">
      <div class="section-heading warning-heading">
        <i class="bi bi-exclamation-triangle"></i>

        <h2>
          تنبيهات المخزون الذكية
          <span>(المهمة جداً للمستشفى)</span>
        </h2>
      </div>

      <div class="settings-list">
        <article class="setting-row">
          <div class="setting-icon">
            <i class="bi bi-graph-up"></i>
          </div>

          <div class="setting-content">
            <h3>تنبيهات نقص المخزون الحرج</h3>

            <p>
              إرسال تنبيه فوري عند انخفاض أي فصيلة دم عن الحد الأدنى للمخزون
            </p>
          </div>

          <label class="switch">
            <input
              v-model="notificationForm.criticalStockAlerts"
              type="checkbox"
            />

            <span class="slider"></span>
          </label>
        </article>

        <article class="setting-row">
          <div class="setting-icon">
            <i class="bi bi-droplet"></i>
          </div>

          <div class="setting-content">
            <h3>تفعيل الذكاء الاصطناعي للطلب المستقبلي</h3>

            <p>
              توقع النقص المتوقع خلال 24 ساعة القادمة واقتراح طلبات استباقية
            </p>
          </div>

          <label class="switch">
            <input
              v-model="notificationForm.aiPrediction"
              type="checkbox"
            />

            <span class="slider"></span>
          </label>
        </article>
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
        ></span>

        <span>
          {{ loading ? 'جارٍ الحفظ...' : 'حفظ الإعدادات' }}
        </span>
      </button>

      <button
        type="button"
        class="cancel-button"
        :disabled="loading"
        @click="resetSettings"
      >
        إلغاء
      </button>
    </div>
  </form>
</template>

<script setup>
import { reactive, ref } from 'vue'

const loading = ref(false)

const initialSettings = {
  pushNotifications: true,
  emailNotifications: true,
  criticalStockAlerts: true,
  aiPrediction: true,
}

const notificationForm = reactive({
  ...initialSettings,
})

const saveSettings = async () => {
  loading.value = true

  try {
    const payload = {
      pushNotifications: notificationForm.pushNotifications,
      emailNotifications: notificationForm.emailNotifications,
      criticalStockAlerts: notificationForm.criticalStockAlerts,
      aiPrediction: notificationForm.aiPrediction,
    }

    console.log('إعدادات الإشعارات:', payload)

    await new Promise((resolve) => {
      setTimeout(resolve, 700)
    })

    window.alert('تم حفظ إعدادات الإشعارات بنجاح')
  } catch (error) {
    console.error('فشل حفظ إعدادات الإشعارات:', error)

    window.alert('حدث خطأ أثناء حفظ الإعدادات')
  } finally {
    loading.value = false
  }
}

const resetSettings = () => {
  Object.assign(notificationForm, initialSettings)
}
</script>

<style scoped>
.notification-settings-card {
  padding: 24px 16px;
  border: 1px solid #eceef2;
  border-radius: 14px;
  background-color: #ffffff;
}

.notification-section + .notification-section {
  margin-top: 28px;
}

.section-heading {
  display: flex;
  align-items: center;
  gap: 9px;
  margin-bottom: 18px;
}

.section-heading i {
  color: #ef4444;
  font-size: 21px;
}

.section-heading h2 {
  margin: 0;
  color: #111827;
  font-size: 18px;
  font-weight: 800;
}

.section-heading h2 span {
  font-size: 16px;
}

.settings-list {
  overflow: hidden;
  border: 1px solid #f0f1f4;
  border-radius: 9px;
}

.setting-row {
  min-height: 82px;
  padding: 15px 14px;

  display: grid;
  grid-template-columns: 42px minmax(0, 1fr) auto;
  align-items: center;
  gap: 14px;

  border-bottom: 1px solid #f0f1f4;
}

.setting-row:last-child {
  border-bottom: 0;
}

.setting-icon {
  width: 38px;
  height: 38px;

  display: grid;
  place-items: center;

  border-radius: 5px;
  background-color: #fdecec;
  color: #ef4444;
  font-size: 19px;
}

.setting-content h3 {
  margin: 0 0 7px;
  color: #111827;
  font-size: 14px;
  font-weight: 800;
}

.setting-content p {
  margin: 0;
  color: #9ca3af;
  font-size: 11px;
  line-height: 1.7;
}

.switch {
  width: 48px;
  height: 24px;
  position: relative;
  display: inline-block;
  direction: ltr;
}

.switch input {
  width: 0;
  height: 0;
  opacity: 0;
}

.slider {
  position: absolute;
  inset: 0;

  border-radius: 999px;
  background-color: #d1d5db;
  cursor: pointer;

  transition: background-color 0.2s ease;
}

.slider::before {
  content: '';
  width: 20px;
  height: 20px;

  position: absolute;
  top: 2px;
  left: 2px;

  border-radius: 50%;
  background-color: #ffffff;
  box-shadow: 0 1px 4px rgba(15, 23, 42, 0.25);

  transition: transform 0.2s ease;
}

.switch input:checked + .slider {
  background-color: #df272b;
}

.switch input:checked + .slider::before {
  transform: translateX(24px);
}

.form-actions {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 36px;
}

.save-button,
.cancel-button {
  min-height: 42px;
  border-radius: 4px;
  font-family: inherit;
  font-size: 14px;
  font-weight: 800;
  cursor: pointer;
}

.save-button {
  width: 215px;
  border: 1px solid #df272b;
  background-color: #df272b;
  color: #ffffff;
}

.save-button:hover:not(:disabled) {
  background-color: #c91f24;
}

.cancel-button {
  width: 130px;
  border: 1px solid #dcdfe4;
  background-color: #ffffff;
  color: #111827;
}

.cancel-button:hover:not(:disabled) {
  border-color: #ef4444;
  color: #ef4444;
}

.save-button:disabled,
.cancel-button:disabled {
  cursor: not-allowed;
  opacity: 0.65;
}

@media (max-width: 700px) {
  .notification-settings-card {
    padding: 18px 12px;
  }

  .setting-row {
    grid-template-columns: 38px minmax(0, 1fr);
  }

  .switch {
    grid-column: 1 / -1;
    justify-self: end;
  }

  .form-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .save-button,
  .cancel-button {
    width: 100%;
  }
}
</style>