<template>
  <div class="ai-section dir-rtl">
    <div class="row g-3 g-lg-4">

      <!-- العمود الأيمن: التحكم بنموذج المطابقة التنبؤية والتنبؤ بالطلب -->
      <div class="col-12 col-lg-8">
        <div class="d-flex flex-column gap-3 gap-md-4">
          <!-- مربع التحكم بنموذج المطابقة التنبؤية (SmartMatchingEngine) -->
          <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end">
            <h6 class="fw-bold text-dark mb-3 mb-md-4 fs-6 text-end">التحكم بنموذج المطابقة التنبؤية (Smart Matching AI)</h6>

            <div class="p-3 bg-white rounded-3 mb-3 mb-md-4 border border-light-subtle">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold text-dark fs-8">الحد الأدنى نسبة المطابقة الذكية لإرسال النداء للمتبرع</span>
              </div>

              <div class="position-relative my-4 px-2">
                <div class="slider-container position-relative">
                  <input
                    type="range"
                    class="form-range custom-slider"
                    min="50"
                    max="100"
                    v-model="aiSettings.matchingThreshold"
                  />
                  <div
                    class="slider-tooltip position-absolute bg-red-main text-white fw-bold rounded-2 px-2 py-0.5 fs-9"
                    :style="{ right: `calc(${matchingPercentage}% - 18px)` }"
                  >
                    {{ aiSettings.matchingThreshold }}%
                  </div>
                </div>
                <div class="d-flex justify-content-between text-muted fs-9 mt-2 fw-semibold">
                  <span>50%</span>
                  <span>60%</span>
                  <span>70%</span>
                  <span>80%</span>
                  <span>90%</span>
                  <span>100%</span>
                </div>
              </div>
            </div>

            <!-- أزرار الزيادة والنقصان لنطاق البحث -->
            <div class="d-flex align-items-center justify-content-between p-2.5 p-md-3 border-top flex-wrap gap-2">
              <span class="fw-bold text-dark fs-8">نطاق البحث الجغرافي الأقصى للـ AI (حول المستشفى)</span>
              <div class="input-group counter-input ms-auto ms-sm-0" style="width: 120px;">
                <button class="btn btn-outline-secondary btn-sm" @click="aiSettings.searchRadius > 1 && aiSettings.searchRadius--">-</button>
                <input type="text" class="form-control text-center fw-bold fs-8" readonly :value="aiSettings.searchRadius" />
                <button class="btn btn-outline-secondary btn-sm" @click="aiSettings.searchRadius++">+</button>
              </div>
            </div>

            <!-- خوارزمية كشف وتصفية الحسابات الوهمية (FraudDetectionAI) -->
            <div class="d-flex align-items-center justify-content-between p-2.5 p-md-3 border-top flex-wrap gap-2">
              <div class="min-w-0">
                <span class="fw-bold text-dark fs-8 d-block mb-1 text-truncate">خوارزمية كشف وتصفية الحسابات الوهمية تلقائياً (Fraud Detection AI)</span>
                <small class="text-muted fs-9 d-block text-truncate">تقوم AI بتحليل السجلات عبر fraud_detection.py وإيقاف الحسابات الوهمية تلقائياً</small>
              </div>
              <div class="form-check form-switch m-0 ms-auto ms-sm-0">
                <input class="form-check-input custom-switch" type="checkbox" v-model="aiSettings.fakeAccountFilter" />
              </div>
            </div>
          </div>

          <!-- مربع التحكم بنموذج التنبؤ بالطلب المستقبلي (BloodDemandForecast & HeatMapAnalysis) -->
          <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end">
            <h6 class="fw-bold text-dark mb-3 mb-md-4 fs-6 text-end">التحكم بنموذج التنبؤ بالطلب المستقبلي (Demand Forecast & Heatmap)</h6>

            <div class="d-flex align-items-center justify-content-between p-2.5 p-md-3 border-bottom flex-wrap gap-2">
              <span class="fw-bold text-dark fs-8">دورية تحديث الخريطة الحرارية لنقص الفصائل (Heat Map Analysis)</span>
              <select class="form-select form-select-sm fs-8 rounded-3 ms-auto ms-sm-0" style="width: 130px;" v-model="aiSettings.heatmapFrequency">
                <option value="12">كل 12 ساعة</option>
                <option value="24">كل 24 ساعة</option>
                <option value="6">كل 6 ساعات</option>
              </select>
            </div>

            <div class="d-flex align-items-center justify-content-between p-2.5 p-md-3 flex-wrap gap-2">
              <div class="min-w-0">
                <span class="fw-bold text-dark fs-8 d-block mb-1 text-truncate">نظام التنبيهات الاستباقية للمستشفيات (Blood Demand Forecast)</span>
                <small class="text-muted fs-9 d-block text-truncate">إرسال توصيات آليه للمستشفيات برفع الجاهزية عند توقع نقص فصيلة معينة خلال 48 ساعة.</small>
              </div>
              <div class="form-check form-switch m-0 ms-auto ms-sm-0">
                <input class="form-check-input custom-switch" type="checkbox" v-model="aiSettings.proactiveAlerts" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- العمود الأيسر: حالة النماذج الذكية + مقاييس الأداء + زر الحفظ -->
      <div class="col-12 col-lg-4">
        <div class="d-flex flex-column gap-3">
          <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white text-end">
            <h6 class="fw-bold text-dark mb-3 mb-md-4 text-end fs-6">حالة النماذج الذكية ومقاييس الأداء</h6>

            <div class="ai-stat-card p-3 rounded-4 mb-3 position-relative bg-light-subtle text-end">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="model-icon">
                  <img :src="getIconUrl('Frame 2147226156.png')" alt="نموذج التنبؤ بالطلب" width="32" height="32" />
                </div>
                <div class="text-end">
                  <h6 class="fw-bold text-dark fs-8 mb-1">نموذج التنبؤ بالطلب</h6>
                  <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 fs-9">يعمل بكفاءة</span>
                </div>
              </div>
              <div class="mt-3 text-end">
                <span class="text-muted fs-9 d-block mb-1">دقة التنبؤ (Model Metrics)</span>
                <h4 class="fw-bold text-purple mb-0 fs-4">49.2%</h4>
              </div>
            </div>

            <div class="ai-stat-card p-3 rounded-4 mb-3 position-relative bg-light-subtle text-end">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="model-icon">
                  <img :src="getIconUrl('Group 1000002338.png')" alt="نموذج المطابقة الفورية" width="32" height="32" />
                </div>
                <div class="text-end">
                  <h6 class="fw-bold text-dark fs-8 mb-1">نموذج المطابقة الفورية</h6>
                  <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 fs-9">نشط</span>
                </div>
              </div>
              <div class="mt-3 text-end">
                <span class="text-muted fs-9 d-block mb-1">تم المعالجة بنجاح</span>
                <h4 class="fw-bold text-success mb-1 fs-4">2,482</h4>
                <span class="text-muted fs-9">طلب مطابقة منفذ</span>
              </div>
            </div>

            <div class="ai-info-box p-3 rounded-3 border border-danger-subtle bg-danger-subtle text-danger text-center fs-9">
              تعمل النماذج الذكية على تحليل البيانات باستمرار لتحسين الثقة وتقليل أوقات الاستجابة لإنقاذ المزيد من الأرواح
            </div>
          </div>

          <!-- زر حفظ الإعدادات المتقدمة المربوط بالمتجر -->
          <button
            class="btn bg-red-main text-white py-2.5 py-md-3 rounded-4 fw-bold w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm fs-8 fs-md-7"
            :disabled="settingsStore.saving"
            @click="settingsStore.saveSettings()"
          >
            <span v-if="settingsStore.saving" class="spinner-border spinner-border-sm"></span>
            <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
              <polyline points="17 21 17 13 7 13 7 21"></polyline>
              <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            <span>حفظ الإعدادات المتقدمة</span>
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useSettingsStore } from '@/stores/settingsStore';

const settingsStore = useSettingsStore();

const props = defineProps({
  aiSettings: Object
});

const getIconUrl = (fileName) => {
  return new URL(`../../../assets/icons/${fileName}`, import.meta.url).href;
};

const matchingPercentage = computed(() => {
  const min = 50;
  const max = 100;
  return (((props.aiSettings?.matchingThreshold || 85) - min) / (max - min)) * 100;
});
</script>

<style scoped>
.fs-6 { font-size: 1.05rem; }
.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }

.bg-red-main { background-color: #dc2626 !important; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-light-subtle { background-color: #f8fafc !important; }
.text-purple { color: #9333ea !important; }

.custom-switch {
  width: 2.6em !important;
  height: 1.3em !important;
  cursor: pointer;
}

.custom-switch:checked {
  background-color: #16a34a !important;
  border-color: #16a34a !important;
}

.slider-container { padding-top: 18px; }

.custom-slider {
  -webkit-appearance: none;
  appearance: none;
  height: 8px;
  background: #e5e7eb;
  border-radius: 5px;
  outline: none;
}

.custom-slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #dc2626;
  cursor: pointer;
  border: 2px solid white;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.slider-tooltip {
  top: -10px;
  transform: translateX(50%);
  transition: right 0.1s ease;
  pointer-events: none;
}

.counter-input .btn {
  border-color: #e5e7eb;
  color: #4b5563;
  padding: 0.25rem 0.6rem;
}

.counter-input .form-control { border-color: #e5e7eb; }

.ai-stat-card { border: 1px solid #f1f5f9; transition: all 0.2s ease; }

.ai-info-box {
  background-color: #fff5f5 !important;
  border-color: #feb2b2 !important;
  color: #e53e3e !important;
  line-height: 1.5;
}
.dir-rtl { direction: rtl; }
</style>
