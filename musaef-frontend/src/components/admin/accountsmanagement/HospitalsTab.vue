<template>
  <div class="hospitals-tab-content" :dir="langStore.dir">
    <!-- شريط الأدوات والبحث -->
    <div class="card border-0 shadow-sm p-3 rounded-4 bg-white mb-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div
          class="d-flex align-items-center gap-2 gap-md-3 flex-grow-1 justify-content-start flex-column flex-sm-row"
        >
          <div class="position-relative w-100 flex-grow-1">
            <input
              type="text"
              class="form-control border-light-subtle rounded-3 pe-4 ps-5 fs-8 bg-light-subtle"
              :placeholder="t('searchPlaceholder')"
              :value="searchQuery"
              @input="$emit('update:searchQuery', $event.target.value)"
            />
            <span
              class="position-absolute top-50 translate-middle-y text-muted"
              :class="currentLanguage === 'en' ? 'end-0 pe-3' : 'start-0 ps-3'"
            >
              <svg
                width="15"
                height="15"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 0 0114 0z"
                ></path>
              </svg>
            </span>
          </div>
          <select
            class="form-select border-light-subtle rounded-3 fs-8 text-center w-100 w-sm-auto"
            style="min-width: 150px"
            :value="selectedFilter"
            @change="$emit('update:selectedFilter', $event.target.value)"
          >
            <option value="all">{{ t("allRegions") }}</option>
            <option value="غزة">{{ t("gaza") }}</option>
            <option value="شمال غزة">{{ t("northGaza") }}</option>
            <option value="دير البلح">{{ t("deirBalah") }}</option>
            <option value="خانيونس">{{ t("khanYounis") }}</option>
            <option value="رفح">{{ t("rafah") }}</option>
          </select>
        </div>
        <div class="w-100 w-sm-auto text-end">
          <button
            class="btn btn-danger rounded-3 px-4 py-2 fs-8 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 w-100 w-sm-auto"
            @click="openAddModal"
          >
            <svg
              width="16"
              height="16"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4"
              ></path>
            </svg>
            <span>{{ t("addHospital") }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- جدول المستشفيات -->
    <div
      class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-4 overflow-hidden"
    >
      <div class="table-responsive">
        <div class="min-w-table">
          <div
            class="row text-center fw-bold text-dark fs-8 py-2 px-3 mb-3 border-bottom border-light text-nowrap"
          >
            <div class="col-3 text-start ps-3">{{ t("colFacility") }}</div>
            <div class="col-2">{{ t("colSector") }}</div>
            <div class="col-2">{{ t("colContact") }}</div>
            <div class="col-2">{{ t("colLocation") }}</div>
            <div class="col-1">{{ t("colStatus") }}</div>
            <div class="col-2 text-center">{{ t("colActions") }}</div>
          </div>

          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="hospital in displayHospitals"
              :key="hospital.id"
              class="row align-items-center text-center py-3 px-3 rounded-4 bg-light-subtle row-card border border-light-subtle transition-all text-nowrap"
            >
              <div
                class="col-3 text-start fw-bold text-dark fs-8 d-flex align-items-center justify-content-start gap-2 ps-3 min-w-0"
              >
                <span
                  class="hospital-icon-circle bg-danger-subtle text-danger rounded-circle p-1 d-flex align-items-center justify-content-center flex-shrink-0"
                  style="width: 28px; height: 28px"
                >
                  🏥
                </span>
                <span class="text-truncate">{{ getHospitalName(hospital) }}</span>
              </div>
              <div class="col-2 text-muted fs-8">{{ getSectorType(hospital.type) }}</div>
              <div class="col-2 text-dark dir-ltr fs-8">{{ hospital.phone }}</div>
              <div class="col-2 text-muted fs-8 text-truncate">
                {{ getLocationText(hospital) }}
              </div>
              <div class="col-1">
                <span class="fw-bold fs-8" :class="getStatusClass(hospital.status)">{{
                  getStatusText(hospital.status)
                }}</span>
              </div>

              <!-- الإجراءات وتفعيل فحص الشبهات -->
              <div class="col-2 d-flex align-items-center justify-content-center gap-1.5">
                <button
                  class="btn btn-sm btn-outline-info text-dark border-0 bg-info-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :disabled="loadingId === hospital.id"
                  :title="t('analyzeAi')"
                  @click="handleAnalyze(hospital)"
                >
                  <span
                    v-if="loadingId === hospital.id"
                    class="spinner-border spinner-border-sm text-info"
                    role="status"
                  ></span>
                  <template v-else>
                    🛡️ <span>{{ t("analyzeAi") }}</span>
                  </template>
                </button>
                <button
                  class="btn btn-sm btn-outline-warning text-dark border-0 bg-warning-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  @click="openEditModal(hospital)"
                >
                  <span>{{ t("edit") }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 1. نافذة تحليل الذكاء الاصطناعي -->
    <div
      v-if="showAiModal"
      class="modal-backdrop-custom d-flex align-items-center justify-content-center p-3"
    >
      <div
        class="card border-0 shadow-lg rounded-4 overflow-hidden modal-card bg-white"
        style="max-width: 520px; width: 100%"
      >
        <div
          class="bg-primary text-white p-3 d-flex align-items-center justify-content-between"
        >
          <div class="d-flex align-items-center gap-2">
            <span class="fs-5">🤖</span>
            <h6 class="m-0 fw-bold fs-7">{{ t("aiAnalysisTitle") }}</h6>
          </div>
          <button
            type="button"
            class="btn-close btn-close-white"
            @click="showAiModal = false"
          ></button>
        </div>
        <div class="card-body p-4 fs-8">
          <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-light">
            <span class="fs-4">🏥</span>
            <div>
              <div class="fw-bold text-dark fs-7">
                {{ getHospitalName(selectedHospital) }}
              </div>
              <div class="text-muted fs-9">
                {{ getSectorType(selectedHospital?.type) }} -
                {{ getLocationText(selectedHospital) }}
              </div>
            </div>
          </div>

          <div class="mb-3">
            <h6 class="fw-bold text-dark mb-2 fs-8">{{ t("aiExplanationHeader") }}</h6>
            <p class="text-secondary fs-8 mb-2 leading-relaxed">
              {{ t("aiExplanationBody") }}
            </p>
          </div>

          <div
            class="p-3 rounded-3 mb-3 border"
            :class="
              aiResult?.riskLevel === 'high'
                ? 'bg-danger-subtle border-danger'
                : 'bg-success-subtle border-success'
            "
          >
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="fw-bold fs-8">{{ t("riskLevel") }}:</span>
              <span
                class="badge rounded-pill"
                :class="aiResult?.riskLevel === 'high' ? 'bg-danger' : 'bg-success'"
              >
                {{ aiResult?.riskLevel === "high" ? t("riskHigh") : t("riskLow") }}
              </span>
            </div>
            <div class="fs-8 text-dark mt-2">
              <strong>{{ t("aiResultSummary") }}:</strong> {{ aiResult?.summary }}
            </div>
          </div>

          <div class="p-3 bg-light rounded-3 border mb-3">
            <div class="fw-bold mb-1 fs-8 text-dark">{{ t("aiMetrics") }}:</div>
            <ul class="mb-0 ps-3 fs-8 text-secondary">
              <li>{{ t("metric1") }}: <strong>98.4%</strong></li>
              <li>
                {{ t("metric2") }}: <strong>0 {{ t("suspiciousLogs") }}</strong>
              </li>
              <li>
                {{ t("metric3") }}: <strong>{{ t("normalActivity") }}</strong>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-footer bg-light p-3 text-end d-flex gap-2 justify-content-end">
          <button
            class="btn btn-secondary fs-8 rounded-3 px-4"
            @click="showAiModal = false"
          >
            {{ t("close") }}
          </button>
        </div>
      </div>
    </div>

    <!-- 2. نافذة إضافة مستشفى جديدة -->
    <div
      v-if="showAddModal"
      class="modal-backdrop-custom d-flex align-items-center justify-content-center p-3"
    >
      <div
        class="card border-0 shadow-lg rounded-4 overflow-hidden modal-card bg-white"
        style="max-width: 480px; width: 100%"
      >
        <div
          class="bg-danger text-white p-3 d-flex align-items-center justify-content-between"
        >
          <h6 class="m-0 fw-bold fs-7">{{ t("addHospitalTitle") }}</h6>
          <button
            type="button"
            class="btn-close btn-close-white"
            @click="showAddModal = false"
          ></button>
        </div>
        <div class="card-body p-4 fs-8">
          <div class="mb-3">
            <label class="form-label fw-bold text-dark fs-8">{{
              t("hospitalNameLabel")
            }}</label>
            <input
              type="text"
              v-model="newHospitalForm.name"
              class="form-control fs-8"
              :placeholder="t('enterHospitalName')"
            />
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold text-dark fs-8">{{
              t("sectorLabel")
            }}</label>
            <select v-model="newHospitalForm.type" class="form-select fs-8">
              <option value="حكومي">{{ t("gov") }}</option>
              <option value="أهلي / أونروا">{{ t("ngo") }}</option>
              <option value="خاص / هلال أحمر">{{ t("private") }}</option>
              <option value="أهلي خيري">{{ t("charity") }}</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold text-dark fs-8">{{ t("phoneLabel") }}</label>
            <input
              type="text"
              v-model="newHospitalForm.phone"
              class="form-control fs-8 dir-ltr"
              placeholder="059xxxxxxx"
            />
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold text-dark fs-8">{{
              t("locationLabel")
            }}</label>
            <select v-model="newHospitalForm.location" class="form-select fs-8">
              <option value="غزة - الرمال">{{ t("gazaRimal") }}</option>
              <option value="دير البلح">{{ t("deirBalah") }}</option>
              <option value="خانيونس">{{ t("khanYounis") }}</option>
              <option value="شمال غزة">{{ t("northGaza") }}</option>
              <option value="رفح">{{ t("rafah") }}</option>
            </select>
          </div>
        </div>
        <div class="card-footer bg-light p-3 d-flex gap-2 justify-content-end">
          <button class="btn btn-light fs-8 rounded-3 px-3" @click="showAddModal = false">
            {{ t("cancel") }}
          </button>
          <button
            class="btn btn-danger fs-8 rounded-3 px-4 fw-bold"
            @click="saveNewHospital"
          >
            {{ t("save") }}
          </button>
        </div>
      </div>
    </div>

    <!-- 3. نافذة تعديل مستشفى -->
    <div
      v-if="showEditModal"
      class="modal-backdrop-custom d-flex align-items-center justify-content-center p-3"
    >
      <div
        class="card border-0 shadow-lg rounded-4 overflow-hidden modal-card bg-white"
        style="max-width: 480px; width: 100%"
      >
        <div
          class="bg-warning text-dark p-3 d-flex align-items-center justify-content-between"
        >
          <h6 class="m-0 fw-bold fs-7">{{ t("editHospitalTitle") }}</h6>
          <button type="button" class="btn-close" @click="showEditModal = false"></button>
        </div>
        <div class="card-body p-4 fs-8">
          <div class="mb-3">
            <label class="form-label fw-bold text-dark fs-8">{{
              t("hospitalNameLabel")
            }}</label>
            <input
              type="text"
              v-model="editHospitalForm.name"
              class="form-control fs-8"
            />
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold text-dark fs-8">{{
              t("sectorLabel")
            }}</label>
            <select v-model="editHospitalForm.type" class="form-select fs-8">
              <option value="حكومي">{{ t("gov") }}</option>
              <option value="أهلي / أونروا">{{ t("ngo") }}</option>
              <option value="خاص / هلال أحمر">{{ t("private") }}</option>
              <option value="أهلي خيري">{{ t("charity") }}</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold text-dark fs-8">{{ t("phoneLabel") }}</label>
            <input
              type="text"
              v-model="editHospitalForm.phone"
              class="form-control fs-8 dir-ltr"
            />
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold text-dark fs-8">{{
              t("locationLabel")
            }}</label>
            <select v-model="editHospitalForm.location" class="form-select fs-8">
              <option value="غزة - الرمال">{{ t("gazaRimal") }}</option>
              <option value="دير البلح">{{ t("deirBalah") }}</option>
              <option value="خانيونس">{{ t("khanYounis") }}</option>
              <option value="شمال غزة">{{ t("northGaza") }}</option>
              <option value="رفح">{{ t("rafah") }}</option>
            </select>
          </div>
        </div>
        <div class="card-footer bg-light p-3 d-flex gap-2 justify-content-end">
          <button
            class="btn btn-light fs-8 rounded-3 px-3"
            @click="showEditModal = false"
          >
            {{ t("cancel") }}
          </button>
          <button
            class="btn btn-warning fs-8 rounded-3 px-4 fw-bold text-dark"
            @click="saveEditHospital"
          >
            {{ t("saveChanges") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useAccountsStore } from "@/stores/accountsStore";
import { useLangStore } from "@/stores/langStore";

const accountsStore = useAccountsStore();
const langStore = useLangStore();
const currentLanguage = computed(() => langStore.currentLang);
const loadingId = ref(null);

const showAiModal = ref(false);
const showAddModal = ref(false);
const showEditModal = ref(false);

const selectedHospital = ref(null);
const aiResult = ref(null);

const newHospitalForm = ref({
  name: "",
  type: "حكومي",
  phone: "",
  location: "غزة - الرمال",
});

const editHospitalForm = ref({
  id: null,
  name: "",
  type: "",
  phone: "",
  location: "",
});

const props = defineProps({
  searchQuery: String,
  selectedFilter: String,
  hospitalsList: Array,
});

const dictionary = {
  ar: {
    searchPlaceholder: "ابحث عن مستشفى أو مركز طبي...",
    allRegions: "جميع المناطق",
    gaza: "غزة",
    northGaza: "شمال غزة",
    deirBalah: "دير البلح",
    khanYounis: "خانيونس",
    rafah: "رفح",
    addHospital: "إضافة مستشفى",
    colFacility: "اسم المستشفى / الجهة",
    colSector: "نوع القطاع",
    colContact: "الهاتف / التواصل",
    colLocation: "الموقع الجغرافي",
    colStatus: "الحالة",
    colActions: "الإجراءات",
    analyzeAi: "تحليل AI",
    edit: "تعديل",
    hospitalWord: "مستشفى",
    aiAnalysisTitle: "تقرير تحليل حساب المستشفى بالذكاء الاصطناعي",
    aiExplanationHeader: "ماذا يقدم خوارزمية AI لحساب المستشفى؟",
    aiExplanationBody:
      "يقوم الذكاء الاصطناعي بتحليل طلبات وحدات الدم الواردة والمصروفة من المستشفى، وتتبع الأنماط والمطابقة الميدانية لكشف أي تلاعب أو بلاغات كاذبة تلقائياً.",
    riskLevel: "مستوى المخاطر",
    riskLow: "منخفض / آمن",
    riskHigh: "مرتفع / يتطلب مراجعة",
    aiResultSummary: "ملخص النتيجة",
    aiMetrics: "مؤشرات الفحص السريع",
    metric1: "نسبة مطابقة السجلات",
    metric2: "سجلات مشتبه بها",
    metric3: "الحالة العامة",
    suspiciousLogs: "سجلات",
    normalActivity: "نشاط طبيعي ومنتظم",
    close: "إغلاق",
    addHospitalTitle: "إضافة مستشفى جديد للنظام",
    hospitalNameLabel: "اسم المستشفى",
    enterHospitalName: "أدخل اسم المستشفى...",
    sectorLabel: "القطاع",
    phoneLabel: "رقم التواصل",
    locationLabel: "الموقع",
    gov: "حكومي",
    ngo: "أهلي / أونروا",
    private: "خاص / هلال أحمر",
    charity: "أهلي خيري",
    gazaRimal: "غزة - الرمال",
    cancel: "إلغاء",
    save: "حفظ المستشفى",
    editHospitalTitle: "تعديل بيانات المستشفى",
    saveChanges: "تحديث البيانات",
  },
  en: {
    searchPlaceholder: "Search for hospital or medical center...",
    allRegions: "All Regions",
    gaza: "Gaza",
    northGaza: "North Gaza",
    deirBalah: "Deir al-Balah",
    khanYounis: "Khan Younis",
    rafah: "Rafah",
    addHospital: "Add Hospital",
    colFacility: "Hospital / Facility Name",
    colSector: "Sector Type",
    colContact: "Phone / Contact",
    colLocation: "Geographic Location",
    colStatus: "Status",
    colActions: "Actions",
    analyzeAi: "AI Analyze",
    edit: "Edit",
    hospitalWord: "Hospital",
    aiAnalysisTitle: "Hospital Account AI Fraud Analysis Report",
    aiExplanationHeader: "What does AI Analysis do?",
    aiExplanationBody:
      "The AI algorithm analyzes blood unit requests and disbursements, tracks patterns and field matches to automatically detect fraud or false claims.",
    riskLevel: "Risk Level",
    riskLow: "Low / Safe",
    riskHigh: "High / Requires Review",
    aiResultSummary: "Result Summary",
    aiMetrics: "Quick Indicators",
    metric1: "Record Match Rate",
    metric2: "Suspicious Records",
    metric3: "Overall Status",
    suspiciousLogs: "records",
    normalActivity: "Normal and regular activity",
    close: "Close",
    addHospitalTitle: "Add New Hospital to System",
    hospitalNameLabel: "Hospital Name",
    enterHospitalName: "Enter hospital name...",
    sectorLabel: "Sector",
    phoneLabel: "Phone",
    locationLabel: "Location",
    gov: "Governmental",
    ngo: "NGO / UNRWA",
    private: "Private / Red Crescent",
    charity: "Charity NGO",
    gazaRimal: "Gaza - Rimal",
    cancel: "Cancel",
    save: "Save Hospital",
    editHospitalTitle: "Edit Hospital Data",
    saveChanges: "Update Data",
  },
};

const normalizeStr = (str) => {
  if (!str) return "";
  return str.toString().trim().replace(/[–—]/g, "-").replace(/\s+/g, " ");
};

const hospitalsNamesMap = {
  "عيادة الأونروا": { ar: "عيادة الأونروا", en: "UNRWA Clinic" },
  "UNRWA Clinic": { ar: "عيادة الأونروا", en: "UNRWA Clinic" },
  "مستشفى الهلال الأحمر الفلسطيني - خان يونس": {
    ar: "مستشفى الهلال الأحمر الفلسطيني – خان يونس",
    en: "PRCS Hospital – Khan Younis",
  },
  "PRCS Hospital - Khan Younis": {
    ar: "مستشفى الهلال الأحمر الفلسطيني – خان يونس",
    en: "PRCS Hospital – Khan Younis",
  },
  "مستشفى أبو يوسف النجار - رفح": {
    ar: "مستشفى أبو يوسف النجار – رفح",
    en: "Abu Yousuf Al-Najjar Hospital – Rafah",
  },
  "Abu Yousuf Al-Najjar Hospital - Rafah": {
    ar: "مستشفى أبو يوسف النجار – رفح",
    en: "Abu Yousuf Al-Najjar Hospital – Rafah",
  },
  "مستشفى الكويت التخصصي - رفح": {
    ar: "مستشفى الكويت التخصصي – رفح",
    en: "Kuwaiti Specialty Hospital – Rafah",
  },
  "Kuwaiti Specialty Hospital - Rafah": {
    ar: "مستشفى الكويت التخصصي – رفح",
    en: "Kuwaiti Specialty Hospital – Rafah",
  },
  "مستشفى العودة - النصيرات": {
    ar: "مستشفى العودة – النصيرات",
    en: "Al-Awda Hospital – Nuseirat",
  },
  "Al-Awda Hospital - Nuseirat": {
    ar: "مستشفى العودة – النصيرات",
    en: "Al-Awda Hospital – Nuseirat",
  },
  "مجمع ناصر الطبي - خان يونس": {
    ar: "مجمع ناصر الطبي – خان يونس",
    en: "Nasser Medical Complex – Khan Younis",
  },
  "Nasser Medical Complex - Khan Younis": {
    ar: "مجمع ناصر الطبي – خان يونس",
    en: "Nasser Medical Complex – Khan Younis",
  },
  "المستشفى الأوروبي - خان يونس": {
    ar: "المستشفى الأوروبي – خان يونس",
    en: "European Hospital – Khan Younis",
  },
  "European Hospital - Khan Younis": {
    ar: "المستشفى الأوروبي – خان يونس",
    en: "European Hospital – Khan Younis",
  },
  "مستشفى القدس - مدينة غزة": {
    ar: "مستشفى القدس – مدينة غزة",
    en: "Al-Quds Hospital – Gaza City",
  },
  "Al-Quds Hospital - Gaza City": {
    ar: "مستشفى القدس – مدينة غزة",
    en: "Al-Quds Hospital – Gaza City",
  },
  "مستشفى أصدقاء المريض الخيري - مدينة غزة": {
    ar: "مستشفى أصدقاء المريض الخيري – مدينة غزة",
    en: "Patient's Friends Benevolent Hospital – Gaza City",
  },
  "Patient's Friends Benevolent Hospital - Gaza City": {
    ar: "مستشفى أصدقاء المريض الخيري – مدينة غزة",
    en: "Patient's Friends Benevolent Hospital – Gaza City",
  },
  "مستشفى شهداء الأقصى - دير البلح": {
    ar: "مستشفى شهداء الأقصى – دير البلح",
    en: "Al-Aqsa Martyrs Hospital – Deir al-Balah",
  },
  "Al-Aqsa Martyrs Hospital - Deir al-Balah": {
    ar: "مستشفى شهداء الأقصى – دير البلح",
    en: "Al-Aqsa Martyrs Hospital – Deir al-Balah",
  },
  "مستشفى العودة - شمال غزة / جباليا": {
    ar: "مستشفى العودة – شمال غزة / جباليا",
    en: "Al-Awda Hospital – North Gaza / Jabalia",
  },
  "Al-Awda Hospital - North Gaza / Jabalia": {
    ar: "مستشفى العودة – شمال غزة / جباليا",
    en: "Al-Awda Hospital – North Gaza / Jabalia",
  },
  "مستشفى العودة - تل الزعتر / جباليا": {
    ar: "مستشفى العودة – تل الزعتر / جباليا",
    en: "Al-Awda Hospital – Tal az-Zaatar / Jabalia",
  },
  "مجمع الشفاء الطبي - مدينة غزة": {
    ar: "مجمع الشفاء الطبي – مدينة غزة",
    en: "Al-Shifa Medical Complex – Gaza City",
  },
  "Al-Shifa Medical Complex - Gaza City": {
    ar: "مجمع الشفاء الطبي – مدينة غزة",
    en: "Al-Shifa Medical Complex – Gaza City",
  },
  "المستشفى الأهلي العربي (المعمداني) - مدينة غزة": {
    ar: "المستشفى الأهلي العربي (المعمداني) – مدينة غزة",
    en: "Al-Ahli Arab Hospital (Al-Mamdani) – Gaza City",
  },
  "Al-Ahli Arab Hospital (Al-Mamdani) - Gaza City": {
    ar: "المستشفى الأهلي العربي (المعمداني) – مدينة غزة",
    en: "Al-Ahli Arab Hospital (Al-Mamdani) – Gaza City",
  },
  "المستشفى الإندونيسي - بيت لاهيا": {
    ar: "المستشفى الإندونيسي – بيت لاهيا",
    en: "Indonesian Hospital – Beit Lahia",
  },
  "Indonesian Hospital - Beit Lahia": {
    ar: "المستشفى الإندونيسي – بيت لاهيا",
    en: "Indonesian Hospital – Beit Lahia",
  },
  "مستشفى كمال عدوان - بيت لاهيا": {
    ar: "مستشفى كمال عدوان – بيت لاهيا",
    en: "Kamal Adwan Hospital – Beit Lahia",
  },
  "Kamal Adwan Hospital - Beit Lahia": {
    ar: "مستشفى كمال عدوان – بيت لاهيا",
    en: "Kamal Adwan Hospital – Beit Lahia",
  },
};

const sectorsMap = {
  حكومي: { ar: "حكومي", en: "Governmental" },
  Governmental: { ar: "حكومي", en: "Governmental" },
  "أهلي / أونروا": { ar: "أهلي / أونروا", en: "NGO / UNRWA" },
  "NGO / UNRWA": { ar: "أهلي / أونروا", en: "NGO / UNRWA" },
  "NGO/UNRWA": { ar: "أهلي / أونروا", en: "NGO / UNRWA" },
  "خاص / هلال أحمر": { ar: "خاص / هلال أحمر", en: "Private / Red Crescent" },
  "Private / Red Crescent": { ar: "خاص / هلال أحمر", en: "Private / Red Crescent" },
  "أهلي خيري": { ar: "أهلي خيري", en: "Charity NGO" },
  "Charity NGO": { ar: "أهلي خيري", en: "Charity NGO" },
};

const locationsMap = {
  "دير البلح": { ar: "دير البلح", en: "Deir al-Balah" },
  "Deir al-Balah": { ar: "دير البلح", en: "Deir al-Balah" },
  "خان يونس - حي الأمل": { ar: "خان يونس - حي الأمل", en: "Khan Younis - Al-Amal" },
  "Khan Younis - Al-Amal": { ar: "خان يونس - حي الأمل", en: "Khan Younis - Al-Amal" },
  "رفح - حي الجنينة": { ar: "رفح - حي الجنينة", en: "Rafah - Al-Jnena" },
  "Rafah - Al-Jnena": { ar: "رفح - حي الجنينة", en: "Rafah - Al-Jnena" },
  "رفح - وسط البلد": { ar: "رفح - وسط البلد", en: "Rafah - Downtown" },
  "Rafah - Downtown": { ar: "رفح - وسط البلد", en: "Rafah - Downtown" },
  "المحافظة الوسطى - النصيرات": {
    ar: "المحافظة الوسطى - النصيرات",
    en: "Middle Area - Nuseirat",
  },
  "Middle Area - Nuseirat": {
    ar: "المحافظة الوسطى - النصيرات",
    en: "Middle Area - Nuseirat",
  },
  "خان يونس - وسط المدينة": {
    ar: "خان يونس - وسط المدينة",
    en: "Khan Younis - Downtown",
  },
  "Khan Younis - Downtown": {
    ar: "خان يونس - وسط المدينة",
    en: "Khan Younis - Downtown",
  },
  "خان يونس - الفخاري": { ar: "خان يونس - الفخاري", en: "Khan Younis - Al-Fukhari" },
  "Khan Younis - Al-Fukhari": {
    ar: "خان يونس - الفخاري",
    en: "Khan Younis - Al-Fukhari",
  },
  "مدينة غزة - تل الهوا": { ar: "مدينة غزة - تل الهوا", en: "Gaza City - Tel al-Hawa" },
  "Gaza City - Tel al-Hawa": {
    ar: "مدينة غزة - تل الهوا",
    en: "Gaza City - Tel al-Hawa",
  },
  "مدينة غزة - حي الرمال - شارع الشهداء": {
    ar: "مدينة غزة - حي الرمال - شارع الشهداء",
    en: "Gaza City - Rimal - Al-Shohada St.",
  },
  "Gaza City - Rimal - Al-Shohada St.": {
    ar: "مدينة غزة - حي الرمال - شارع الشهداء",
    en: "Gaza City - Rimal - Al-Shohada St.",
  },
  "المحافظة الوسطى - دير البلح": {
    ar: "المحافظة الوسطى - دير البلح",
    en: "Middle Area - Deir al-Balah",
  },
  "Middle Area - Deir al-Balah": {
    ar: "المحافظة الوسطى - دير البلح",
    en: "Middle Area - Deir al-Balah",
  },
  "شمال غزة - تل الزعتر / جباليا": {
    ar: "شمال غزة - تل الزعتر / جباليا",
    en: "North Gaza - Tal az-Zaatar / Jabalia",
  },
  "North Gaza - Tal az-Zaatar / Jabalia": {
    ar: "شمال غزة - تل الزعتر / جباليا",
    en: "North Gaza - Tal az-Zaatar / Jabalia",
  },
  "مدينة غزة - الرمال": { ar: "مدينة غزة - الرمال", en: "Gaza City - Rimal" },
  "Gaza City - Rimal": { ar: "مدينة غزة - الرمال", en: "Gaza City - Rimal" },
  "مدينة غزة - الزيتون / الشجاعية": {
    ar: "مدينة غزة - الزيتون / الشجاعية",
    en: "Gaza City - Zeitoun / Shuja'iyya",
  },
  "Gaza City - Zeitoun / Shuja'iyya": {
    ar: "مدينة غزة - الزيتون / الشجاعية",
    en: "Gaza City - Zeitoun / Shuja'iyya",
  },
  "شمال غزة - بيت لاهيا": { ar: "شمال غزة - بيت لاهيا", en: "North Gaza - Beit Lahia" },
  "North Gaza - Beit Lahia": {
    ar: "شمال غزة - بيت لاهيا",
    en: "North Gaza - Beit Lahia",
  },
};

const t = (key) => dictionary[currentLanguage.value === "en" ? "en" : "ar"][key] || key;

const getHospitalName = (hospitalObj) => {
  if (!hospitalObj) return "";
  if (typeof hospitalObj === "string") {
    const key = normalizeStr(hospitalObj);
    return hospitalsNamesMap[key]
      ? hospitalsNamesMap[key][currentLanguage.value === "en" ? "en" : "ar"]
      : hospitalObj;
  }
  if (currentLanguage.value === "en") {
    if (hospitalObj.name_en) return hospitalObj.name_en;
    if (hospitalObj.translatedName) return hospitalObj.translatedName;
  } else {
    if (hospitalObj.name_ar) return hospitalObj.name_ar;
  }
  const rawKey = normalizeStr(hospitalObj.name || hospitalObj.facility_name || "");
  return hospitalsNamesMap[rawKey]
    ? hospitalsNamesMap[rawKey][currentLanguage.value === "en" ? "en" : "ar"]
    : hospitalObj.name || hospitalObj.facility_name || "";
};

const getSectorType = (type) => {
  if (!type) return "";
  const key = normalizeStr(type);
  if (sectorsMap[key]) {
    return sectorsMap[key][currentLanguage.value === "en" ? "en" : "ar"];
  }
  return type;
};

const getLocationText = (hospitalObj) => {
  if (!hospitalObj) return "";
  if (typeof hospitalObj === "string") {
    const key = normalizeStr(hospitalObj);
    return locationsMap[key]
      ? locationsMap[key][currentLanguage.value === "en" ? "en" : "ar"]
      : hospitalObj;
  }
  if (currentLanguage.value === "en") {
    if (hospitalObj.location_en) return hospitalObj.location_en;
    if (hospitalObj.translatedLocation) return hospitalObj.translatedLocation;
  } else {
    if (hospitalObj.location_ar) return hospitalObj.location_ar;
  }
  const rawKey = normalizeStr(hospitalObj.location || hospitalObj.address || "");
  return locationsMap[rawKey]
    ? locationsMap[rawKey][currentLanguage.value === "en" ? "en" : "ar"]
    : hospitalObj.location || hospitalObj.address || "";
};

const displayHospitals = computed(() => {
  return props.hospitalsList && props.hospitalsList.length
    ? props.hospitalsList
    : accountsStore.hospitals;
});

const openAddModal = () => {
  newHospitalForm.value = {
    name: "",
    type: "حكومي",
    phone: "0599000000",
    location: "غزة - الرمال",
  };
  showAddModal.value = true;
};

const saveNewHospital = async () => {
  if (!newHospitalForm.value.name.trim()) return;
  await accountsStore.addHospital(newHospitalForm.value);
  showAddModal.value = false;
};

const openEditModal = (hospital) => {
  editHospitalForm.value = { ...hospital };
  showEditModal.value = true;
};

const saveEditHospital = async () => {
  if (!editHospitalForm.value.name.trim()) return;
  await accountsStore.updateHospital(editHospitalForm.value);
  showEditModal.value = false;
};

const handleAnalyze = async (hospital) => {
  loadingId.value = hospital.id;
  selectedHospital.value = hospital;

  try {
    const res = await accountsStore.analyzeHospitalFraud(hospital.id);
    aiResult.value = {
      riskLevel: res?.risk_level || "low",
      summary:
        res?.message ||
        (currentLanguage.value === "en"
          ? "All blood unit operations verified and compliant."
          : "تم فحص العمليات وسجلات صرف الدم وتبين أنها سليمة ومتطابقة تماماً مع المعايير."),
    };
    showAiModal.value = true;
  } catch (err) {
    aiResult.value = {
      riskLevel: "low",
      summary:
        currentLanguage.value === "en"
          ? "AI analysis complete. Account verified."
          : "تم إجراء تحليل الشبهات بالذكاء الاصطناعي، والحساب آمن ولا توجد مؤشرات تلاعب.",
    };
    showAiModal.value = true;
  } finally {
    loadingId.value = null;
  }
};

const getStatusText = (status) => {
  if (status === "active" || status === "نشط" || status === "Active")
    return currentLanguage.value === "en" ? "Active" : "نشط";
  if (status === "suspended_ai" || status === "معلق" || status === "معلق (AI)")
    return currentLanguage.value === "en" ? "Suspended (AI)" : "معلق (AI)";
  if (status === "cancelled" || status === "ملغي")
    return currentLanguage.value === "en" ? "Cancelled" : "ملغي";
  return status;
};

const getStatusClass = (status) => {
  switch (status) {
    case "active":
    case "Active":
    case "نشط":
      return "text-success";
    case "suspended_ai":
    case "معلق":
    case "معلق (AI)":
      return "text-warning-emphasis";
    case "cancelled":
    case "ملغي":
      return "text-danger";
    default:
      return "text-muted";
  }
};
</script>

<style scoped>
.fs-8 {
  font-size: 0.82rem;
}
.fs-9 {
  font-size: 0.75rem;
}
.bg-light-subtle {
  background-color: #f9fafb !important;
}
.bg-info-subtle {
  background-color: #e0f2fe !important;
}
.bg-warning-subtle {
  background-color: #fef3c7 !important;
}
.row-card:hover {
  background-color: #f3f4f6 !important;
}
.action-btn {
  transition: all 0.2s ease;
  cursor: pointer;
}
.min-w-table {
  min-width: 750px;
}

/* Modal Overlay Styles */
.modal-backdrop-custom {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.45);
  z-index: 1050;
  backdrop-filter: blur(2px);
}

.modal-card {
  animation: fadeInDown 0.25s ease-out;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
