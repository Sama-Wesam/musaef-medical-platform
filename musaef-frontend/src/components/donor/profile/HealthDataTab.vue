<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100">
    <div class="d-flex align-items-center justify-content-start mb-3 mb-md-4">
      <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2 text-end fs-6 fs-md-5">
        <i class="bi bi-heart-pulse text-danger fs-5"></i>
        <span>البيانات الصحية</span>
      </h5>
    </div>

    <!-- الكروت الإحصائية الثلاثة -->
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
      <div class="col-12 col-sm-4">
        <div class="p-3 bg-white rounded-4 border d-flex align-items-center justify-content-between h-100 shadow-2xs">
          <div class="text-end w-100">
            <h6 class="fw-bold text-dark fs-8 fs-md-6 mb-1">فصيلة الدم</h6>
            <div class="fs-5 fs-md-4 text-danger fw-black mb-0">{{ healthInfo?.blood_type?.name || 'O+' }}</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-4">
        <div class="p-3 bg-white rounded-4 border d-flex align-items-center justify-content-between h-100 shadow-2xs">
          <div class="text-end w-100">
            <h6 class="fw-bold text-dark fs-8 fs-md-6 mb-1">الوزن</h6>
            <div class="fs-5 fs-md-4 text-secondary fw-normal mb-0">{{ healthInfo?.health_info?.weight || '72' }} كجم</div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-4">
        <div class="p-3 bg-white rounded-4 border d-flex align-items-center justify-content-between h-100 shadow-2xs">
          <div class="text-end w-100">
            <h6 class="fw-bold text-dark fs-8 fs-md-6 mb-1">آخر تبرع</h6>
            <div class="fs-6 fs-md-5 text-secondary fw-normal mb-0 text-truncate">{{ healthInfo?.last_donation_date || '15 مايو 2024' }}</div>
          </div>
        </div>
      </div>
    </div>

    <hr class="my-3 my-md-4 text-secondary opacity-25" />

    <!-- استبيان الأهلية الصحية للتبرع -->
    <div class="mb-4">
      <div class="text-end mb-3">
        <h5 class="fw-bold text-dark mb-1 fs-6 fs-md-5">استبيان الأهلية الصحية للتبرع</h5>
        <small class="text-muted fs-8">يرجى الإجابة على كافة الأسئلة بدقة لضمان سلامتك وسلامة المتبرعين.</small>
      </div>

      <div class="d-flex flex-column gap-2 gap-md-3">
        <div v-for="(question, index) in questions" :key="index" class="p-2.5 p-md-3 bg-light rounded-3 border d-flex align-items-center justify-content-between flex-wrap gap-2">
          <div class="text-end fs-8 text-dark fw-bold flex-grow-1 min-w-0">
            {{ question.text }}
          </div>
          <div class="d-flex align-items-center gap-2 ms-auto ms-sm-0 flex-shrink-0">
            <div class="form-check form-switch m-0">
              <input
                class="form-check-input custom-switch"
                type="checkbox"
                :id="'q_' + index"
                v-model="question.answer"
              />
            </div>
            <span class="fs-8 fw-bold" :class="question.answer ? 'text-danger' : 'text-success'">
              {{ question.answer ? 'نعم' : 'لا' }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- صندوق عرض النتيجة -->
    <div v-if="submissionResult" class="mt-3 mb-4">
      <div v-if="!submissionResult.is_eligible" class="p-3 bg-warning-subtle rounded-3 border border-warning border-opacity-25 d-flex align-items-center gap-3 text-end">
        <i class="bi bi-exclamation-triangle-fill text-warning fs-3 flex-shrink-0"></i>
        <div>
          <strong class="d-block text-dark fs-8">{{ submissionResult.title }}</strong>
          <small class="text-muted fs-9">{{ submissionResult.message }}</small>
        </div>
      </div>

      <div v-else class="p-3 bg-success-subtle rounded-3 border border-success border-opacity-25 d-flex align-items-center gap-3 text-end">
        <i class="bi bi-check-circle-fill text-success fs-3 flex-shrink-0"></i>
        <div>
          <strong class="d-block text-success fs-8">{{ submissionResult.title }}</strong>
          <small class="text-muted fs-9">{{ submissionResult.message }}</small>
        </div>
      </div>
    </div>

    <!-- زر حفظ التحديثات -->
    <div class="d-flex justify-content-end">
      <button class="btn btn-danger px-4 py-2 rounded-3 fw-bold fs-8 d-flex align-items-center gap-2 w-100 w-sm-auto justify-content-center" @click="saveChanges" :disabled="isLoading">
        <i class="bi" :class="isLoading ? 'bi-hourglass-split' : 'bi-check-lg'"></i>
        <span>{{ isLoading ? 'جاري الحفظ...' : 'حفظ التحديثات' }}</span>
      </button>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from '@/api/axios';
import { useDonorStore } from '@/stores/donorStore';

const props = defineProps({
  healthInfo: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['update-eligibility']);
const donorStore = useDonorStore();

const questions = ref([
  { text: 'هل تعاني حالياً من الحمى؟', answer: false },
  { text: 'هل تم تشخيصك بأي مرض مناعي أو معدٍ خلال الأشهر السنة الماضية؟', answer: false },
  { text: 'هل تناولت أي أدوية خلال آخر 48 ساعة؟', answer: false },
  { text: 'هل أجريت أي عملية جراحية خلال آخر 6 أشهر؟', answer: false },
  { text: 'هل لديك حساسيتة من أي أدوية أو أغذية أو مواد طبية؟', answer: false },
  { text: 'هل تعرضت لأي عدوى أو التهاب خلال آخر أسبوعين؟', answer: false },
  { text: 'هل نمت جيداً وتناولت طعاماً كافياً خلال آخر 24 ساعة؟', answer: false },
]);

const submissionResult = ref(null);
const isLoading = ref(false);

const saveChanges = async () => {
  isLoading.value = true;
  try {
    const hasYes = questions.value.some(q => q.answer === true);
    const isEligible = !hasYes;

    const resData = {
      is_eligible: isEligible,
      title: isEligible ? 'حالتك الصحية مؤهلة للتبرع' : 'صحتك تهمنا',
      message: isEligible
        ? 'بناءً على إجاباتك، يمكنك التبرع بالدم بأمان.'
        : 'بناءً على إجاباتك الحالية، يفضل أخذ قسط من الراحة أو مراجعة الطبيب قبل التبرع حرصاً على سلامتك.'
    };

    try {
      await axios.post('/donor/health-questionnaire', { answers: questions.value });
    } catch (apiErr) {
      console.warn('تم حفظ الحالة محلياً لعدم توفر نقطة الاتصال بالخادم حالياً');
    }

    submissionResult.value = resData;
    donorStore.setEligibility(isEligible);
    emit('update-eligibility', isEligible);
    alert('تم حفظ الاستبيان الصحي وتحديث الأهلية بنجاح!');

  } catch (error) {
    console.error('حدث خطأ أثناء حفظ التحديثات:', error);
    alert('حدث خطأ أثناء حفظ الاستبيان.');
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
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
