<template>
  <div :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="row g-3 g-lg-4">
      <!-- العمود الأيمن / الأيسر: التحكم بنموذج المطابقة والتنبؤ بالطلب -->
      <div class="col-12 col-lg-8">
        <div class="d-flex flex-column gap-3 gap-md-4">
          <!-- مربع التحكم بنموذج المطابقة التنبؤية (SmartMatchingEngine) -->
          <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white">
            <h6 class="fw-bold text-dark mb-3 mb-md-4 fs-6">
              {{ t("smartMatchingTitle") }}
            </h6>

            <div class="p-3 bg-white rounded-3 mb-3 mb-md-4 border border-light-subtle">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold text-dark fs-8">{{ t("minMatchingLabel") }}</span>
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
                    :style="tooltipPositionStyle"
                  >
                    {{ aiSettings.matchingThreshold }}%
                  </div>
                </div>
                <div
                  class="d-flex justify-content-between text-muted fs-9 mt-2 fw-semibold"
                >
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
            <div
              class="d-flex align-items-center justify-content-between p-2.5 p-md-3 border-top flex-wrap gap-2"
            >
              <span class="fw-bold text-dark fs-8">{{ t("searchRadiusLabel") }}</span>
              <div
                class="input-group counter-input"
                style="width: 120px"
                :class="currentLanguage === 'ar' ? 'ms-auto ms-sm-0' : 'me-auto me-sm-0'"
              >
                <button
                  type="button"
                  class="btn btn-outline-secondary btn-sm"
                  @click="aiSettings.searchRadius > 1 && aiSettings.searchRadius--"
                >
                  -
                </button>
                <input
                  type="text"
                  class="form-control text-center fw-bold fs-8"
                  readonly
                  :value="aiSettings.searchRadius"
                />
                <button
                  type="button"
                  class="btn btn-outline-secondary btn-sm"
                  @click="aiSettings.searchRadius++"
                >
                  +
                </button>
              </div>
            </div>

            <!-- خوارزمية كشف وتصفية الحسابات الوهمية (FraudDetectionAI) -->
            <div class="p-2.5 p-md-3 border-top">
              <div
                class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2"
              >
                <div class="min-w-0">
                  <span class="fw-bold text-dark fs-8 d-block mb-1 text-truncate">{{
                    t("fraudTitle")
                  }}</span>
                  <small class="text-muted fs-9 d-block text-truncate">{{
                    t("fraudDesc")
                  }}</small>
                </div>
                <div
                  class="form-check form-switch m-0"
                  :class="
                    currentLanguage === 'ar' ? 'ms-auto ms-sm-0' : 'me-auto me-sm-0'
                  "
                >
                  <input
                    class="form-check-input custom-switch"
                    type="checkbox"
                    v-model="aiSettings.fakeAccountFilter"
                  />
                </div>
              </div>

              <!-- تفاصيل ديناميكية عن حالة تشغيل سكربت Python -->
              <div
                v-if="aiSettings.fakeAccountFilter"
                class="bg-light p-2 rounded-3 mt-2 d-flex align-items-center justify-content-between fs-9 text-muted"
              >
                <div>
                  <span class="badge bg-success-subtle text-success ms-1 me-1">{{
                    t("scriptActive")
                  }}</span>
                  <span
                    >{{ t("lastAnalysis") }}:
                    <strong>{{ settingsStore.aiMetrics.lastAnalysisTime }}</strong></span
                  >
                </div>
                <button
                  type="button"
                  class="btn btn-sm btn-outline-danger py-0 px-2 fs-9 rounded-2 d-flex align-items-center gap-1"
                  :disabled="settingsStore.aiMetrics.analyzingFraud"
                  @click.prevent="settingsStore.triggerFraudAnalysis()"
                >
                  <span
                    v-if="settingsStore.aiMetrics.analyzingFraud"
                    class="spinner-border spinner-border-sm me-1"
                    role="status"
                  ></span>
                  {{ t("runAnalysisNow") }}
                </button>
              </div>
            </div>
          </div>

          <!-- مربع التحكم بنموذج التنبؤ بالطلب المستقبلي -->
          <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white">
            <h6 class="fw-bold text-dark mb-3 mb-md-4 fs-6">{{ t("forecastTitle") }}</h6>

            <div
              class="d-flex align-items-center justify-content-between p-2.5 p-md-3 border-bottom flex-wrap gap-2"
            >
              <span class="fw-bold text-dark fs-8">{{ t("heatmapFrequencyLabel") }}</span>
              <select
                class="form-select form-select-sm fs-8 rounded-3"
                style="width: 140px"
                :class="currentLanguage === 'ar' ? 'ms-auto ms-sm-0' : 'me-auto me-sm-0'"
                v-model="aiSettings.heatmapFrequency"
              >
                <option value="12">{{ t("every12h") }}</option>
                <option value="24">{{ t("every24h") }}</option>
                <option value="6">{{ t("every6h") }}</option>
              </select>
            </div>

            <div
              class="d-flex align-items-center justify-content-between p-2.5 p-md-3 flex-wrap gap-2"
            >
              <div class="min-w-0">
                <span class="fw-bold text-dark fs-8 d-block mb-1 text-truncate">{{
                  t("proactiveAlertsTitle")
                }}</span>
                <small class="text-muted fs-9 d-block text-truncate">{{
                  t("proactiveAlertsDesc")
                }}</small>
              </div>
              <div
                class="form-check form-switch m-0"
                :class="currentLanguage === 'ar' ? 'ms-auto ms-sm-0' : 'me-auto me-sm-0'"
              >
                <input
                  class="form-check-input custom-switch"
                  type="checkbox"
                  v-model="aiSettings.proactiveAlerts"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- العمود الآخر: حالة النماذج الذكية + مقاييس الأداء الديناميكية + زر الحفظ -->
      <div class="col-12 col-lg-4">
        <div class="d-flex flex-column gap-3">
          <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white">
            <h6 class="fw-bold text-dark mb-3 mb-md-4 fs-6">
              {{ t("modelsStatusTitle") }}
            </h6>

            <!-- كارت دقة نموذج التنبؤ -->
            <div
              class="ai-stat-card p-3 rounded-4 mb-3 position-relative bg-light-subtle"
            >
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="model-icon">
                  <img
                    :src="getIconUrl('Frame 2147226156.png')"
                    alt="Forecast Model"
                    width="32"
                    height="32"
                  />
                </div>
                <div :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                  <h6 class="fw-bold text-dark fs-8 mb-1">
                    {{ t("forecastModelName") }}
                  </h6>
                  <span
                    class="badge bg-success-subtle text-success rounded-pill px-2 py-1 fs-9"
                    >{{ t("statusEfficient") }}</span
                  >
                </div>
              </div>
              <div class="mt-3">
                <span class="text-muted fs-9 d-block mb-1">{{
                  t("modelMetricsLabel")
                }}</span>
                <h4 class="fw-bold text-purple mb-0 fs-4">
                  {{ dynamicPredictionAccuracy }}%
                </h4>
              </div>
            </div>

            <!-- كارت الطلبات المنفذة للمطابقة -->
            <div
              class="ai-stat-card p-3 rounded-4 mb-3 position-relative bg-light-subtle"
            >
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="model-icon">
                  <img
                    :src="getIconUrl('Group 1000002338.png')"
                    alt="Matching Model"
                    width="32"
                    height="32"
                  />
                </div>
                <div :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                  <h6 class="fw-bold text-dark fs-8 mb-1">
                    {{ t("instantMatchingModelName") }}
                  </h6>
                  <span
                    class="badge bg-success-subtle text-success rounded-pill px-2 py-1 fs-9"
                    >{{ t("statusActive") }}</span
                  >
                </div>
              </div>
              <div class="mt-3">
                <span class="text-muted fs-9 d-block mb-1">{{
                  t("processedSuccessfully")
                }}</span>
                <h4 class="fw-bold text-success mb-1 fs-4">
                  {{ dynamicExecutedRequests }}
                </h4>
                <span class="text-muted fs-9">{{ t("requestsExecuted") }}</span>
              </div>
            </div>

            <div
              class="ai-info-box p-3 rounded-3 border border-danger-subtle bg-danger-subtle text-danger text-center fs-9"
            >
              {{ t("infoBoxText") }}
            </div>
          </div>

          <!-- زر حفظ الإعدادات المتقدمة -->
          <button
            type="button"
            class="btn bg-red-main text-white py-2.5 py-md-3 rounded-4 fw-bold w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm fs-8 fs-md-7"
            :disabled="settingsStore.saving"
            @click="settingsStore.saveSettings()"
          >
            <span
              v-if="settingsStore.saving"
              class="spinner-border spinner-border-sm"
            ></span>
            <svg
              v-else
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"
              ></path>
              <polyline points="17 21 17 13 7 13 7 21"></polyline>
              <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            <span>{{ t("saveAdvancedSettings") }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useSettingsStore } from "@/stores/settingsStore";

const settingsStore = useSettingsStore();

const props = defineProps({
  aiSettings: {
    type: Object,
    required: true,
  },
  aiMetrics: {
    type: Object,
    default: () => ({}),
  },
});

const activeLanguage = ref(localStorage.getItem("musaef_lang") || "ar");

const syncLanguage = () => {
  activeLanguage.value = localStorage.getItem("musaef_lang") || "ar";
};

const currentLanguage = computed(() => activeLanguage.value);

onMounted(() => {
  window.addEventListener("storage", syncLanguage);
  window.addEventListener("musaef_lang_changed", syncLanguage);
});

onUnmounted(() => {
  window.removeEventListener("storage", syncLanguage);
  window.removeEventListener("musaef_lang_changed", syncLanguage);
});

const dynamicPredictionAccuracy = computed(() => {
  const value =
    props.aiMetrics?.predictionAccuracy ?? settingsStore.aiMetrics?.predictionAccuracy;
  return value !== undefined ? value : 94.2;
});

const dynamicExecutedRequests = computed(() => {
  const value =
    props.aiMetrics?.executedRequests ?? settingsStore.aiMetrics?.executedRequests;
  return value !== undefined ? value.toLocaleString() : "12";
});

const dictionary = {
  ar: {
    smartMatchingTitle: "التحكم بنموذج المطابقة التنبؤية (Smart Matching AI)",
    minMatchingLabel: "الحد الأدنى نسبة المطابقة الذكية لإرسال النداء للمتبرع",
    searchRadiusLabel: "نطاق البحث الجغرافي الأقصى للـ AI (حول المستشفى)",
    fraudTitle: "خوارزمية كشف وتصفية الحسابات الوهمية تلقائياً (Fraud Detection AI)",
    fraudDesc:
      "تقوم AI بتحليل السجلات عبر fraud_detection.py وإيقاف الحسابات الوهمية تلقائياً",
    scriptActive: "السكربت نشط",
    lastAnalysis: "آخر تحليل",
    runAnalysisNow: "فحص الآن",
    forecastTitle: "التحكم بنموذج التنبؤ بالطلب المستقبلي (Demand Forecast & Heatmap)",
    heatmapFrequencyLabel:
      "دورية تحديث الخريطة الحرارية لنقص الفصائل (Heat Map Analysis)",
    every12h: "كل 12 ساعة",
    every24h: "كل 24 ساعة",
    every6h: "كل 6 ساعات",
    proactiveAlertsTitle: "نظام التنبيهات الاستباقية للمستشفيات (Blood Demand Forecast)",
    proactiveAlertsDesc:
      "إرسال توصيات آليه للمستشفيات برفع الجاهزية عند توقع نقص فصيلة معينة خلال 48 ساعة.",
    modelsStatusTitle: "حالة النماذج الذكية ومقاييس الأداء",
    forecastModelName: "نموذج التنبؤ بالطلب",
    statusEfficient: "يعمل بكفاءة",
    modelMetricsLabel: "دقة التنبؤ (Model Metrics)",
    instantMatchingModelName: "نموذج المطابقة الفورية",
    statusActive: "نشط",
    processedSuccessfully: "تم المعالجة بنجاح",
    requestsExecuted: "طلب مطابقة منفذ",
    infoBoxText:
      "تعمل النماذج الذكية على تحليل البيانات باستمرار لتحسين الثقة وتقليل أوقات الاستجابة لإنقاذ المزيد من الأرواح",
    saveAdvancedSettings: "حفظ الإعدادات المتقدمة",
  },
  en: {
    smartMatchingTitle: "Smart Matching AI Control",
    minMatchingLabel: "Minimum Smart Matching Ratio for Donor Alert",
    searchRadiusLabel: "Max AI Search Radius Around Hospital",
    fraudTitle: "Automatic Fraud Detection Algorithm (Fraud Detection AI)",
    fraudDesc:
      "AI analyzes logs via fraud_detection.py and automatically suspends fake accounts",
    scriptActive: "Script Active",
    lastAnalysis: "Last Analysis",
    runAnalysisNow: "Run Check",
    forecastTitle: "Demand Forecast & Heatmap Control",
    heatmapFrequencyLabel: "Heatmap Update Frequency for Blood Shortages",
    every12h: "Every 12 Hours",
    every24h: "Every 24 Hours",
    every6h: "Every 6 Hours",
    proactiveAlertsTitle: "Proactive Alert System for Hospitals (Demand Forecast)",
    proactiveAlertsDesc:
      "Automated recommendations sent to hospitals to prepare when blood shortage is expected in 48h.",
    modelsStatusTitle: "Smart Models Status & Performance Metrics",
    forecastModelName: "Demand Forecast Model",
    statusEfficient: "Efficient",
    modelMetricsLabel: "Prediction Accuracy (Model Metrics)",
    instantMatchingModelName: "Instant Matching Model",
    statusActive: "Active",
    processedSuccessfully: "Successfully Processed",
    requestsExecuted: "Executed Requests",
    infoBoxText:
      "Smart AI models continuously analyze data to build trust and cut response times to save lives.",
    saveAdvancedSettings: "Save Advanced Settings",
  },
};

const t = (key) => dictionary[currentLanguage.value === "en" ? "en" : "ar"][key] || key;

const getIconUrl = (fileName) => {
  return new URL(`../../../assets/icons/${fileName}`, import.meta.url).href;
};

const matchingPercentage = computed(() => {
  const min = 50;
  const max = 100;
  return (((props.aiSettings?.matchingThreshold || 85) - min) / (max - min)) * 100;
});

const tooltipPositionStyle = computed(() => {
  if (currentLanguage.value === "en") {
    return { left: `calc(${matchingPercentage.value}% - 18px)` };
  }
  return { right: `calc(${matchingPercentage.value}% - 18px)` };
});
</script>

<style scoped>
.fs-6 {
  font-size: 1.05rem;
}
.fs-7 {
  font-size: 0.92rem;
}
.fs-8 {
  font-size: 0.82rem;
}
.fs-9 {
  font-size: 0.72rem;
}

.bg-red-main {
  background-color: #dc2626 !important;
}
.bg-danger-subtle {
  background-color: #fee2e2 !important;
}
.bg-success-subtle {
  background-color: #d1fae5 !important;
}
.bg-light-subtle {
  background-color: #f8fafc !important;
}
.text-purple {
  color: #9333ea !important;
}

.custom-switch {
  width: 2.6em !important;
  height: 1.3em !important;
  cursor: pointer;
}

.custom-switch:checked {
  background-color: #16a34a !important;
  border-color: #16a34a !important;
}

.slider-container {
  padding-top: 18px;
}

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
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.slider-tooltip {
  top: -10px;
  transform: translateX(50%);
  transition: right 0.1s ease, left 0.1s ease;
  pointer-events: none;
}

.dir-ltr .slider-tooltip {
  transform: translateX(-50%);
}

.counter-input .btn {
  border-color: #e5e7eb;
  color: #4b5563;
  padding: 0.25rem 0.6rem;
}

.counter-input .form-control {
  border-color: #e5e7eb;
}

.ai-stat-card {
  border: 1px solid #f1f5f9;
  transition: all 0.2s ease;
}

.ai-info-box {
  background-color: #fff5f5 !important;
  border-color: #feb2b2 !important;
  color: #e53e3e !important;
  line-height: 1.5;
}
.dir-rtl {
  direction: rtl;
}
.dir-ltr {
  direction: ltr;
}
</style>
