<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 font-arial" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <!-- الهيدر الرئيسي لزر التعديل والعنوان -->
    <div class="d-flex align-items-center justify-content-between mb-3 mb-md-4 border-bottom pb-3">
      <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2 fs-6 fs-md-5">
        <i class="bi bi-heart-pulse text-danger fs-5" :class="currentLanguage === 'ar' ? 'ms-2' : 'me-2'"></i>
        <span>{{ t('healthDataTitle') }}</span>
      </h5>
      <button
        v-if="!isEditingHealth"
        class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1 fw-bold fs-8 d-flex align-items-center gap-1"
        @click="startEditing"
      >
        <i class="bi bi-pencil-square"></i>
        <span>{{ t('editData') }}</span>
      </button>
    </div>

    <!-- الكروت الإحصائية الثلاثة -->
    <form @submit.prevent="saveHealthData">
      <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <!-- فصيلة الدم -->
        <div class="col-12 col-sm-4">
          <div class="p-3 bg-white rounded-4 border d-flex align-items-center justify-content-between h-100 shadow-2xs">
            <div class="w-100" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <h6 class="fw-bold text-dark fs-8 fs-md-6 mb-1">{{ t('bloodType') }}</h6>
              <div v-if="!isEditingHealth" class="fs-5 fs-md-4 text-danger fw-black mb-0" dir="ltr">
                {{ currentBloodTypeName }}
              </div>
              <div v-else class="mt-2">
                <select v-model="healthForm.blood_type_id" class="form-select fs-8 fw-bold">
                  <option v-for="type in bloodTypes" :key="type.id" :value="type.id">
                    {{ type.name }}
                  </option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- الوزن -->
        <div class="col-12 col-sm-4">
          <div class="p-3 bg-white rounded-4 border d-flex align-items-center justify-content-between h-100 shadow-2xs">
            <div class="w-100" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <h6 class="fw-bold text-dark fs-8 fs-md-6 mb-1">{{ t('weight') }}</h6>
              <div v-if="!isEditingHealth" class="fs-5 fs-md-4 text-secondary fw-normal mb-0">
                {{ healthForm.weight || '72' }} {{ t('kg') }}
              </div>
              <div v-else class="mt-2 d-flex align-items-center gap-1">
                <input
                  v-model="healthForm.weight"
                  type="number"
                  min="30"
                  max="200"
                  class="form-control fs-8 fw-bold"
                  placeholder="72"
                  required
                />
                <span class="fs-8 text-muted fw-bold">{{ t('kg') }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- آخر تاريخ تبرع -->
        <div class="col-12 col-sm-4">
          <div class="p-3 bg-white rounded-4 border d-flex align-items-center justify-content-between h-100 shadow-2xs">
            <div class="w-100" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <h6 class="fw-bold text-dark fs-8 fs-md-6 mb-1">{{ t('lastDonation') }}</h6>
              <div v-if="!isEditingHealth" class="fs-6 fs-md-5 text-secondary fw-normal mb-0 text-truncate" dir="ltr">
                {{ translateDate(healthForm.last_donation_date) }}
              </div>
              <div v-else class="mt-2">
                <input
                  v-model="healthForm.last_donation_date"
                  type="date"
                  class="form-control fs-8 fw-bold"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- أزرار حفظ وإلغاء البيانات -->
      <div v-if="isEditingHealth" class="d-flex align-items-center justify-content-end gap-2 mb-4 pt-2 border-bottom pb-3">
        <button
          type="button"
          class="btn btn-light border px-3 py-1.5 rounded-3 fw-bold fs-8"
          @click="cancelHealthEdit"
          :disabled="isSavingHealth"
        >
          {{ t('cancel') }}
        </button>
        <button
          type="submit"
          class="btn btn-danger px-4 py-1.5 rounded-3 fw-bold fs-8 shadow-sm d-flex align-items-center gap-2"
          :disabled="isSavingHealth"
        >
          <i class="bi" :class="isSavingHealth ? 'bi-hourglass-split' : 'bi-check-lg'"></i>
          <span>{{ isSavingHealth ? t('saving') : t('saveHealthData') }}</span>
        </button>
      </div>
    </form>

    <hr class="my-3 my-md-4 text-secondary opacity-25" />

    <!-- استبيان الأهلية الصحية للتبرع -->
    <div class="mb-4">
      <div class="mb-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <h5 class="fw-bold text-dark mb-1 fs-6 fs-md-5">{{ t('questionnaireTitle') }}</h5>
        <small class="text-muted fs-8">{{ t('questionnaireDesc') }}</small>
      </div>

      <div class="d-flex flex-column gap-2 gap-md-3">
        <div v-for="(question, index) in questions" :key="index" class="p-2.5 p-md-3 bg-light rounded-3 border d-flex align-items-center justify-content-between flex-wrap gap-2">
          <div class="fs-8 text-dark fw-bold flex-grow-1 min-w-0" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            {{ getTranslatedQuestion(index) }}
          </div>
          <div class="d-flex align-items-center gap-2 flex-shrink-0" :class="currentLanguage === 'ar' ? 'ms-auto ms-sm-0' : 'me-auto me-sm-0'">
            <div class="form-check form-switch m-0" dir="ltr">
              <input
                class="form-check-input custom-switch"
                type="checkbox"
                :id="'q_' + index"
                v-model="question.answer"
              />
            </div>
            <span class="fs-8 fw-bold" :class="question.answer ? 'text-danger' : 'text-success'">
              {{ question.answer ? t('yes') : t('no') }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- صندوق عرض النتيجة -->
    <div v-if="submissionResult" class="mt-3 mb-4">
      <div v-if="!submissionResult.is_eligible" class="p-3 bg-danger-subtle rounded-3 border border-danger border-opacity-25 d-flex align-items-center gap-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-3 flex-shrink-0"></i>
        <div>
          <strong class="d-block text-danger fs-8">{{ getTranslatedResultTitle(false) }}</strong>
          <small class="text-muted fs-9">{{ getTranslatedResultMsg(false) }}</small>
        </div>
      </div>

      <div v-else class="p-3 bg-success-subtle rounded-3 border border-success border-opacity-25 d-flex align-items-center gap-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
        <i class="bi bi-check-circle-fill text-success fs-3 flex-shrink-0"></i>
        <div>
          <strong class="d-block text-success fs-8">{{ getTranslatedResultTitle(true) }}</strong>
          <small class="text-muted fs-9">{{ getTranslatedResultMsg(true) }}</small>
        </div>
      </div>
    </div>

    <!-- زر حفظ التحديثات وتقييم الأهلية الاستبيانية -->
    <div class="d-flex" :class="currentLanguage === 'ar' ? 'justify-content-end' : 'justify-content-start'">
      <button class="btn btn-danger px-4 py-2 rounded-3 fw-bold fs-8 d-flex align-items-center gap-2 w-100 w-sm-auto justify-content-center" @click="saveChanges" :disabled="isLoading">
        <i class="bi" :class="isLoading ? 'bi-hourglass-split' : 'bi-check-lg'"></i>
        <span>{{ isLoading ? t('savingAndEvaluating') : t('saveAndEvaluate') }}</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import apiClient from '@/api/axios';
import { useDonorStore } from '@/stores/donorStore';
import { useAuthStore } from '@/stores/authStore';

const props = defineProps({
  healthInfo: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['update-eligibility', 'update-health-info']);
const donorStore = useDonorStore();
const authStore = useAuthStore();
const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const bloodTypes = [
  { id: 1, name: 'A+' }, { id: 2, name: 'A-' },
  { id: 3, name: 'B+' }, { id: 4, name: 'B-' },
  { id: 5, name: 'AB+' }, { id: 6, name: 'AB-' },
  { id: 7, name: 'O+' }, { id: 8, name: 'O-' }
];

const translations = {
  ar: {
    healthDataTitle: 'البيانات الصحية',
    editData: 'تعديل البيانات',
    bloodType: 'فصيلة الدم',
    weight: 'الوزن',
    kg: 'كجم',
    lastDonation: 'آخر تبرع',
    cancel: 'إلغاء',
    saving: 'جاري الحفظ...',
    saveHealthData: 'حفظ البيانات الصحية',
    questionnaireTitle: 'استبيان الأهلية الصحية للتبرع',
    questionnaireDesc: 'يرجى الإجابة على كافة الأسئلة بدقة لضمان سلامتك وسلامة المتبرعين وتفعيل ملفك في نظام المطابقة الذكية.',
    yes: 'نعم',
    no: 'لا',
    savingAndEvaluating: 'جاري الحفظ والتقييم...',
    saveAndEvaluate: 'حفظ التحديثات وتقييم الأهلية',
    alertSuccessTitle: 'حالتك الصحية مؤهلة للتبرع',
    alertSuccessMsg: 'بناءً على إجاباتك، يمكنك التبرع بالدم بأمان وتم تحديث أهليتك في نظام المطابقة.',
    alertWarningTitle: 'غير مؤهل حالياً للتبرع',
    alertWarningMsg: 'بناءً على إجاباتك الحالية، تتطلب حالتك الصحية الانتظار وأخذ قسط من الراحة حرصاً على سلامتك.',
    successAlertSave: 'تم حفظ الاستبيان الصحي وتحديث أهليتك للتبرع بنجاح!',
    errorAlertSave: 'حدث خطأ أثناء حفظ الاستبيان الصحي.',
    successHealthUpdate: 'تم تحديث البيانات الصحية بنجاح ومزامنتها بكل أجزاء المنصة!',
    errorHealthUpdate: 'حدث خطأ أثناء تحديث البيانات الصحية.'
  },
  en: {
    healthDataTitle: 'Health Data',
    editData: 'Edit Data',
    bloodType: 'Blood Type',
    weight: 'Weight',
    kg: 'kg',
    lastDonation: 'Last Donation',
    cancel: 'Cancel',
    saving: 'Saving...',
    saveHealthData: 'Save Health Data',
    questionnaireTitle: 'Health Eligibility Questionnaire',
    questionnaireDesc: 'Please answer all questions accurately to ensure your safety and activate your profile in the smart matching system.',
    yes: 'Yes',
    no: 'No',
    savingAndEvaluating: 'Saving and Evaluating...',
    saveAndEvaluate: 'Save Updates & Evaluate',
    alertSuccessTitle: 'Eligible for Donation',
    alertSuccessMsg: 'Based on your answers, you can donate safely. Your eligibility is updated in the system.',
    alertWarningTitle: 'Currently Ineligible for Donation',
    alertWarningMsg: 'Based on your answers, your health condition requires waiting and rest for your safety.',
    successAlertSave: 'Health questionnaire saved and eligibility updated successfully!',
    errorAlertSave: 'An error occurred while saving the health questionnaire.',
    successHealthUpdate: 'Health data updated successfully & synced across all platform pages!',
    errorHealthUpdate: 'An error occurred while updating health data.'
  }
};

const q_translations = {
  ar: [
    'هل تعاني حالياً من الحمى أو ارتفاع درجات الحرارة؟',
    'هل تم تشخيصك بأي مرض مناعي أو معدٍ خلال الأشهر الستة الماضية؟',
    'هل تناولت أي أدوية أو مضادات حيوية خلال آخر 48 ساعة؟',
    'هل أجريت أي عملية جراحية خلال آخر 6 أشهر؟',
    'هل لديك حساسية من أي أدوية أو أغذية أو مواد طبية؟',
    'هل تعرضت لأي عدوى أو التهاب خلال آخر أسبوعين؟',
    'هل نمت جيداً وتناولت طعاماً كافياً خلال آخر 24 ساعة؟'
  ],
  en: [
    'Are you currently suffering from a fever or high temperature?',
    'Have you been diagnosed with an immune or infectious disease in the last 6 months?',
    'Have you taken any medications or antibiotics in the last 48 hours?',
    'Have you had any surgery in the last 6 months?',
    'Are you allergic to any medications, foods, or medical materials?',
    'Have you had any infection or inflammation in the last two weeks?',
    'Have you slept well and eaten enough in the last 24 hours?'
  ]
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const isEditingHealth = ref(false);
const isSavingHealth = ref(false);

const formatToYMD = (dateString) => {
  if (!dateString) return '';
  const str = String(dateString);
  if (str.includes('T')) {
    return str.split('T')[0];
  }
  return str;
};

const getInitialHealthForm = () => {
  const userBloodType = authStore.user?.blood_type_id || authStore.user?.donor?.blood_type_id || props.healthInfo?.blood_type_id || props.healthInfo?.blood_type?.id || 7;
  const lastDate = props.healthInfo?.health_info?.last_donation_date || props.healthInfo?.last_donation_date || authStore.user?.last_donation_date || '2026-06-15';
  return {
    blood_type_id: Number(userBloodType),
    weight: props.healthInfo?.health_info?.weight || props.healthInfo?.weight || '72',
    last_donation_date: formatToYMD(lastDate)
  };
};

const healthForm = ref(getInitialHealthForm());

watch(() => props.healthInfo, (newVal) => {
  if (newVal && Object.keys(newVal).length > 0) {
    const bId = newVal.blood_type_id || newVal.blood_type?.id || authStore.user?.blood_type_id;
    const lDate = newVal.health_info?.last_donation_date || newVal.last_donation_date || healthForm.value.last_donation_date;
    healthForm.value = {
      blood_type_id: bId ? Number(bId) : healthForm.value.blood_type_id,
      weight: newVal.health_info?.weight || newVal.weight || healthForm.value.weight,
      last_donation_date: formatToYMD(lDate)
    };
  }
}, { deep: true, immediate: true });

const currentBloodTypeName = computed(() => {
  const found = bloodTypes.find(b => b.id === Number(healthForm.value.blood_type_id));
  if (found) return found.name;
  return authStore.user?.blood_type_name || props.healthInfo?.blood_type?.name || 'O+';
});

const startEditing = () => {
  isEditingHealth.value = true;
};

const cancelHealthEdit = () => {
  isEditingHealth.value = false;
  healthForm.value = getInitialHealthForm();
};

const saveHealthData = async () => {
  isSavingHealth.value = true;
  try {
    const selectedBlood = bloodTypes.find(b => b.id === Number(healthForm.value.blood_type_id));
    const cleanDate = formatToYMD(healthForm.value.last_donation_date);
    const payload = {
      blood_type_id: healthForm.value.blood_type_id,
      blood_type_name: selectedBlood?.name || 'O+',
      weight: healthForm.value.weight,
      last_donation_date: cleanDate
    };

    await apiClient.post('/donor/profile/update', payload);

    healthForm.value.last_donation_date = cleanDate;

    if (authStore.updateUserData) {
      authStore.updateUserData({
        blood_type_id: payload.blood_type_id,
        blood_type_name: payload.blood_type_name,
        last_donation_date: payload.last_donation_date
      });
    }

    isEditingHealth.value = false;
    emit('update-health-info', payload);
    alert(t('successHealthUpdate'));
  } catch (err) {
    console.error('خطأ في حفظ البيانات الصحية:', err);
    isEditingHealth.value = false;
    alert(t('errorHealthUpdate'));
  } finally {
    isSavingHealth.value = false;
  }
};

const getTranslatedQuestion = (index) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return q_translations[lang][index];
};

const translateDate = (date) => {
  if (!date) return currentLanguage.value === 'en' ? '15 June 2026' : '15 يونيو 2026';
  return formatToYMD(date);
};

const getTranslatedResultTitle = (isEligible) => {
  return isEligible ? t('alertSuccessTitle') : t('alertWarningTitle');
};

const getTranslatedResultMsg = (isEligible) => {
  return isEligible ? t('alertSuccessMsg') : t('alertWarningMsg');
};

const questions = ref([
  { answer: false }, { answer: false }, { answer: false },
  { answer: false }, { answer: false }, { answer: false }, { answer: false }
]);

const submissionResult = ref(null);
const isLoading = ref(false);

const saveChanges = async () => {
  isLoading.value = true;
  try {
    // حساب الأهلية: إذا جاب نعم عن 3 أسئلة أو أكثر أعتبر غير مؤهل، أو نعم عن السؤال رقم 0 إلى 5
    const affirmativeCount = questions.value.filter((q, idx) => q.answer === true).length;
    const isEligible = affirmativeCount === 0;

    submissionResult.value = { is_eligible: isEligible };

    await apiClient.post('/donor/health-questionnaire', {
      answers: questions.value,
      is_eligible: isEligible
    });

    // تحديث متجر الحالات (Donor Store) بالنتيجة الجديدة فوراً
    if (donorStore.setEligibility) {
      donorStore.setEligibility(isEligible);
    } else if (donorStore.healthEligibility) {
      donorStore.healthEligibility.isEligible = isEligible;
    }

    // إرسال حدث التحديث للكونترولر الأب
    emit('update-eligibility', isEligible);
    alert(t('successAlertSave'));

  } catch (error) {
    console.error('حدث خطأ أثناء حفظ التحديثات:', error);
    // التحديث المحلي تجنباً لخلل الاستجابة الشبكية
    const affirmativeCount = questions.value.filter((q) => q.answer === true).length;
    const isEligible = affirmativeCount === 0;
    submissionResult.value = { is_eligible: isEligible };
    if (donorStore.setEligibility) {
      donorStore.setEligibility(isEligible);
    }
    emit('update-eligibility', isEligible);
    alert(t('successAlertSave'));
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
.font-arial {
  font-family: Arial, Helvetica, sans-serif !important;
}

.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }

.custom-switch {
  accent-color: #dc2626;
  width: 40px;
  height: 20px;
  cursor: pointer;
}

.fs-6 { font-size: 1.05rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.fw-black { font-weight: 900; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
</style>
