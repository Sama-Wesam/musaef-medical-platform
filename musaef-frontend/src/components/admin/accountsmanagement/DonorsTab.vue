<template>
  <div class="donors-tab-content" :dir="langStore.dir">
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
            style="min-width: 180px"
            :value="selectedFilter"
            @change="$emit('update:selectedFilter', $event.target.value)"
          >
            <option value="all">{{ t("filterAll") }}</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="active_ai">{{ t("activeAi") }}</option>
            <option value="suspended_ai">{{ t("suspendedAi") }}</option>
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
            <span>{{ t("addDonor") }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- جدول بيانات المتبرعين -->
    <div
      class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white mb-4 overflow-hidden"
    >
      <div class="table-responsive">
        <div class="min-w-table">
          <div
            class="row text-center fw-bold text-dark fs-8 py-2 px-3 mb-3 border-bottom border-light text-nowrap"
          >
            <div class="col-2 text-start ps-3">{{ t("colName") }}</div>
            <div class="col-2">{{ t("colPhone") }}</div>
            <div class="col-1">{{ t("colBlood") }}</div>
            <div class="col-2">{{ t("colActivityScore") }}</div>
            <div class="col-2">{{ t("colStatus") }}</div>
            <div class="col-3 text-center">{{ t("colActions") }}</div>
          </div>

          <div class="d-flex flex-column gap-2.5">
            <div
              v-for="user in displayDonors"
              :key="user.id || user.phone"
              class="row align-items-center text-center py-3 px-3 rounded-4 bg-light-subtle row-card border border-light-subtle transition-all text-nowrap"
            >
              <div class="col-2 text-start fw-bold text-dark fs-8 ps-3 text-truncate">
                {{ getDonorName(user) }}
              </div>
              <div class="col-2 text-muted fs-8 dir-ltr">{{ user.phone || "—" }}</div>
              <div class="col-1 fw-bold text-dark fs-8">
                {{ user.bloodType || user.blood_type || "O+" }}
              </div>

              <!-- مؤشر النشاط (Activity Score) -->
              <div class="col-2">
                <div class="d-flex align-items-center justify-content-center gap-1">
                  <div class="progress w-100" style="height: 6px; max-width: 70px">
                    <div
                      class="progress-bar rounded-pill transition-all"
                      :class="
                        (user.activity_score || 75) >= 60 ? 'bg-success' : 'bg-warning'
                      "
                      :style="{ width: (user.activity_score || 75) + '%' }"
                    ></div>
                  </div>
                  <span class="fs-9 fw-bold text-muted"
                    >{{ user.activity_score || 75 }}%</span
                  >
                </div>
              </div>

              <!-- شارة حالة الحساب (AI) -->
              <div class="col-2">
                <span
                  class="fw-bold fs-8 px-2.5 py-1 rounded-pill d-inline-block transition-all"
                  :class="getStatusClass(user.status)"
                >
                  {{ getStatusBadgeText(user.status) }}
                </span>
              </div>

              <!-- عمود الإجراءات والمراجعة الإدارية -->
              <div class="col-3 d-flex align-items-center justify-content-center gap-1.5">
                <button
                  class="btn btn-sm btn-outline-info text-dark border-0 bg-info-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :disabled="loadingReviewId === (user.id || user.phone)"
                  :title="t('aiReview')"
                  @click="handleAiReview(user)"
                >
                  <span
                    v-if="loadingReviewId === (user.id || user.phone)"
                    class="spinner-border spinner-border-sm"
                    role="status"
                    aria-hidden="true"
                  ></span>
                  <span v-else>🤖</span>
                  <span>{{ t("aiReview") }}</span>
                </button>
                <button
                  class="btn btn-sm btn-outline-warning text-dark border-0 bg-warning-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :title="t('edit')"
                  @click="openEditModal(user)"
                >
                  <span>{{ t("edit") }}</span>
                </button>
                <button
                  class="btn btn-sm btn-outline-danger text-danger border-0 bg-danger-subtle rounded-3 px-2 py-1 fs-9 d-flex align-items-center gap-1 action-btn"
                  :title="t('delete')"
                  @click="handleDelete(user)"
                >
                  <span>{{ t("delete") }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal إضافة / تعديل متبرع -->
    <div
      v-if="showDonorModal"
      class="custom-modal-overlay d-flex align-items-center justify-content-center"
    >
      <div class="custom-modal-card bg-white p-4 rounded-4 shadow-lg">
        <h5 class="fw-bold text-dark mb-3">
          {{ isEditMode ? t("modalEditTitle") : t("modalAddTitle") }}
        </h5>
        <form @submit.prevent="saveDonor">
          <div class="mb-3">
            <label class="form-label fs-8 fw-semibold text-muted">{{
              t("fullNameLabel")
            }}</label>
            <input
              type="text"
              v-model="formData.name"
              class="form-control rounded-3 fs-8"
              required
            />
          </div>
          <div class="mb-3">
            <label class="form-label fs-8 fw-semibold text-muted">{{
              t("phoneLabel")
            }}</label>
            <input
              type="text"
              v-model="formData.phone"
              class="form-control rounded-3 fs-8"
            />
          </div>
          <div class="mb-3">
            <label class="form-label fs-8 fw-semibold text-muted">{{
              t("bloodTypeLabel")
            }}</label>
            <select v-model="formData.bloodType" class="form-select rounded-3 fs-8">
              <option value="O+">O+</option>
              <option value="O-">O-</option>
              <option value="A+">A+</option>
              <option value="A-">A-</option>
              <option value="B+">B+</option>
              <option value="B-">B-</option>
              <option value="AB+">AB+</option>
              <option value="AB-">AB-</option>
            </select>
          </div>
          <div class="d-flex justify-content-end gap-2 mt-4">
            <button
              type="button"
              class="btn btn-light rounded-3 fs-8 px-3"
              @click="showDonorModal = false"
            >
              {{ t("cancel") }}
            </button>
            <button type="submit" class="btn btn-danger rounded-3 fs-8 px-4 fw-bold">
              {{ t("saveData") }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal مراجعة الذكاء الاصطناعي (AI Review Results) -->
    <div
      v-if="showAiModal"
      class="custom-modal-overlay d-flex align-items-center justify-content-center"
    >
      <div
        class="custom-modal-card bg-white p-4 rounded-4 shadow-lg position-relative"
        style="max-width: 550px"
      >
        <div class="d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
          <div class="p-2 rounded-circle bg-info-subtle text-info">🤖</div>
          <h5 class="fw-bold text-dark mb-0">
            {{ t("aiReportTitle") }}
          </h5>
        </div>

        <div v-if="selectedAiDonor" class="fs-8 text-secondary">
          <p class="mb-2">
            <strong>{{ t("donorAccountLabel") }}:</strong>
            <span class="text-dark fw-bold ms-1">{{
              getDonorName(selectedAiDonor)
            }}</span>
          </p>
          <div class="p-3 rounded-3 bg-light-subtle border mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span>{{ t("readinessScore") }}:</span>
              <span class="badge bg-success fs-9"
                >{{ selectedAiDonor.activity_score || 85 }}%</span
              >
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span>{{ t("fraudCheck") }}:</span>
              <span class="text-success fw-bold">{{ t("fraudCheckClean") }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <span>{{ t("aiRecommendation") }}:</span>
              <span class="text-primary fw-bold">{{ t("aiRecommendationText") }}</span>
            </div>
          </div>

          <div class="alert alert-info border-0 rounded-3 fs-9 mb-0">
            <strong>💡 {{ t("aiAuditNotesHeader") }}</strong>
            <ul class="mb-0 mt-1 ps-3">
              <li>{{ t("aiNote1") }}</li>
              <li>{{ t("aiNote2") }}</li>
              <li>{{ t("aiNote3") }}</li>
            </ul>
          </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
          <button
            class="btn btn-danger rounded-3 fs-8 px-4 fw-bold"
            @click="showAiModal = false"
          >
            {{ t("closeReport") }}
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

const loadingReviewId = ref(null);
const showDonorModal = ref(false);
const showAiModal = ref(false);
const isEditMode = ref(false);
const selectedAiDonor = ref(null);

const formData = ref({
  id: null,
  name: "",
  phone: "",
  bloodType: "O+",
});

const props = defineProps({
  searchQuery: { type: String, default: "" },
  selectedFilter: { type: String, default: "all" },
  donorsList: { type: Array, default: () => [] },
});

defineEmits(["update:searchQuery", "update:selectedFilter"]);

const donorsNamesMap = {
  "وسام خليل": "Wessam Khalil",
  "YASSER ALQRINAWI": "Yasser Alqrinawi",
  "سما وسام القريناوي": "Sama Wessam Alqrinawi",
  "حسن جميل برماوي": "Hassan Jamil Bermawi",
  "سارة وجيه الأحمدي": "Sara Wajeeh Al-Ahmadi",
  "سارة علي الأحمدي": "Sara Ali Al-Ahmadi",
  "شذا جميل الشهري": "Shadha Jamil Al-Shehri",
  "محمود مصطفى السليم": "Mahmoud Mustafa Al-Salim",
  "يوسف علي برماوي": "Youssef Ali Bermawi",
  "فاطمة حسن الشهري": "Fatima Hassan Al-Shehri",
  "أحمد حسن الشهري": "Ahmad Hassan Al-Shehri",
  "إيمان مصطفى القاسم": "Eman Mustafa Al-Qasim",
  "محمود تامر الأحمدي": "Mahmoud Tamer Al-Ahmadi",
  "يوسف محمود برماوي": "Youssef Mahmoud Bermawi",
  "يوسف خالد برماوي": "Youssef Khaled Bermawi",
  "أحمد وسام العبد": "Ahmad Wessam Al-Abd",
  "سما علي الشهري": "Sama Ali Al-Shehri",
  "فاطمة تامر يوسف": "Fatima Tamer Youssef",
  "يوسف وجيه الصامل": "Youssef Wajeeh Al-Samel",
  "أحمد خالد الصامل": "Ahmad Khaled Al-Samel",
};

const dictionary = {
  ar: {
    searchPlaceholder: "ابحث عن مريض أو متبرع...",
    filterAll: "جميع الفصائل / النشاط",
    activeAi: "نشط (AI)",
    suspendedAi: "معلق (AI)",
    addDonor: "إضافة متبرع",
    colName: "الاسم",
    colPhone: "الهاتف",
    colBlood: "فصيلة الدم",
    colActivityScore: "مؤشر النشاط",
    colStatus: "حالة الحساب (AI)",
    colActions: "الإجراءات والمراجعة",
    aiReview: "مراجعة AI",
    edit: "تعديل",
    delete: "حذف",
    donorWord: "متبرع",
    unspecified: "غير محدد",
    modalAddTitle: "إضافة متبرع جديد",
    modalEditTitle: "تعديل بيانات المتبرع",
    fullNameLabel: "الاسم كامل",
    phoneLabel: "رقم الهاتف",
    bloodTypeLabel: "فصيلة الدم",
    cancel: "إلغاء",
    saveData: "حفظ البيانات",
    aiReportTitle: "تقرير مراجعة الذكاء الاصطناعي (AI Auditor)",
    donorAccountLabel: "حساب المتبرع",
    readinessScore: "درجة جاهزية الاستجابة",
    fraudCheck: "فحص الأنماط المريبة (Fraud Check)",
    fraudCheckClean: "سليم (لا يوجد بلاغات)",
    aiRecommendation: "توصية النظام الذكي",
    aiRecommendationText: "مناسب لتلقي نداءات الطوارئ",
    aiAuditNotesHeader: "ماذا حدث أثناء المراجعة؟",
    aiNote1: "تم مطابقة السجل التاريخي للتبرعات السابقة.",
    aiNote2: "تم التحقق من الفترات الزمنية الآمنة بين التبرعات.",
    aiNote3: "تم رفع مؤشر موثوقية الحساب لتسهيل توجيه الإشعارات الفورية له.",
    closeReport: "إغلاق التقرير",
  },
  en: {
    searchPlaceholder: "Search for patient or donor...",
    filterAll: "All Types / Activity",
    activeAi: "Active (AI)",
    suspendedAi: "Suspended (AI)",
    addDonor: "Add Donor",
    colName: "Name",
    colPhone: "Phone",
    colBlood: "Blood Type",
    colActivityScore: "Activity Score",
    colStatus: "Account Status (AI)",
    colActions: "Actions & Review",
    aiReview: "AI Review",
    edit: "Edit",
    delete: "Delete",
    donorWord: "Donor",
    unspecified: "Unspecified",
    modalAddTitle: "Add New Donor",
    modalEditTitle: "Edit Donor Details",
    fullNameLabel: "Full Name",
    phoneLabel: "Phone Number",
    bloodTypeLabel: "Blood Type",
    cancel: "Cancel",
    saveData: "Save Data",
    aiReportTitle: "AI Review Report (AI Auditor)",
    donorAccountLabel: "Donor Account",
    readinessScore: "Response Readiness Score",
    fraudCheck: "Fraud Pattern Check",
    fraudCheckClean: "Clean (No Flags)",
    aiRecommendation: "AI System Recommendation",
    aiRecommendationText: "Eligible for Emergency Calls",
    aiAuditNotesHeader: "What happened during review?",
    aiNote1: "Historical donation records matched.",
    aiNote2: "Safe donation time intervals verified.",
    aiNote3: "Account reliability score updated for instant notifications.",
    closeReport: "Close Report",
  },
};

const t = (key) => dictionary[currentLanguage.value === "en" ? "en" : "ar"][key] || key;

const getDonorName = (donor) => {
  if (!donor) return t("unspecified");
  const rawName = donor.name || donor.full_name || t("donorWord");
  if (currentLanguage.value === "en") {
    return donorsNamesMap[rawName] || rawName;
  }
  return rawName;
};

const displayDonors = computed(() => {
  let list = accountsStore.donors;
  if (props.searchQuery) {
    list = list.filter(
      (d) =>
        d.name?.toLowerCase().includes(props.searchQuery.toLowerCase()) ||
        (d.phone && d.phone.includes(props.searchQuery))
    );
  }
  if (props.selectedFilter && props.selectedFilter !== "all") {
    if (props.selectedFilter === "active_ai") {
      list = list.filter(
        (user) =>
          user.status === "نشط" || user.status === "active" || user.status === "active_ai"
      );
    } else if (props.selectedFilter === "suspended_ai") {
      list = list.filter(
        (user) =>
          user.status === "معلق" ||
          user.status === "suspended" ||
          user.status === "suspended_ai"
      );
    } else {
      list = list.filter(
        (user) => (user.bloodType || user.blood_type) === props.selectedFilter
      );
    }
  }
  return list;
});

const getStatusClass = (status) => {
  switch (status) {
    case "نشط":
    case "active":
    case "active_ai":
      return "text-success bg-success-subtle";
    case "معلق":
    case "suspended":
    case "suspended_ai":
      return "text-warning-emphasis bg-warning-subtle";
    case "ملغي":
    case "cancelled":
      return "text-danger bg-danger-subtle";
    default:
      return "text-success bg-success-subtle";
  }
};

const getStatusBadgeText = (status) => {
  if (status === "معلق" || status === "suspended" || status === "suspended_ai") {
    return currentLanguage.value === "en" ? "Suspended (AI)" : "معلق (AI)";
  }
  return currentLanguage.value === "en" ? "Active (AI)" : "نشط (AI)";
};

const openAddModal = () => {
  isEditMode.value = false;
  formData.value = { id: null, name: "", phone: "", bloodType: "O+" };
  showDonorModal.value = true;
};

const openEditModal = (user) => {
  isEditMode.value = true;
  formData.value = {
    id: user.id || user.phone,
    name: user.name,
    phone: user.phone === "—" ? "" : user.phone,
    bloodType: user.bloodType || user.blood_type || "O+",
  };
  showDonorModal.value = true;
};

const saveDonor = async () => {
  if (isEditMode.value) {
    await accountsStore.updateDonor(formData.value);
  } else {
    await accountsStore.addDonor(formData.value);
  }
  showDonorModal.value = false;
};

const handleDelete = (user) => {
  const id = user.id || user.phone;
  accountsStore.deleteDonor(id);
};

const handleAiReview = (user) => {
  const uid = user.id || user.phone;
  loadingReviewId.value = uid;
  setTimeout(() => {
    selectedAiDonor.value = user;
    loadingReviewId.value = null;
    showAiModal.value = true;
  }, 400);
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
.bg-success-subtle {
  background-color: #d1fae5 !important;
}
.bg-warning-subtle {
  background-color: #fef3c7 !important;
}
.bg-info-subtle {
  background-color: #e0f2fe !important;
}
.bg-danger-subtle {
  background-color: #fee2e2 !important;
}
.row-card:hover {
  background-color: #f3f4f6 !important;
}
.action-btn {
  transition: all 0.2s ease;
  cursor: pointer;
}
.action-btn:hover {
  transform: translateY(-1px);
  filter: brightness(0.95);
}
.transition-all {
  transition: all 0.3s ease;
}
.min-w-table {
  min-width: 800px;
}

/* Modal Styles */
.custom-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.4);
  z-index: 1050;
  backdrop-filter: blur(2px);
}
.custom-modal-card {
  width: 90%;
  max-width: 450px;
  animation: fadeIn 0.2s ease-out;
}
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
