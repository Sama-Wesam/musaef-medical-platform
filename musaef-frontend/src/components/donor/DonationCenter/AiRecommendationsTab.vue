<template>
  <div class="ai-recommendations-tab">
    <!-- عنوان ووصف التبويب -->
    <div class="text-end mb-3 mb-md-4">
      <h5 class="fw-bold text-dark mb-1 fs-6 fs-md-5">توصيات الذكاء الاصطناعي لك</h5>
      <p class="text-muted fs-8 mb-0">
        تم ترتيب هذه الطلبات بناءً على توافقك وقربك وسرعة الاستجابة المتوقعة.
      </p>
    </div>

    <!-- حالة التحميل -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-danger mb-2" role="status"></div>
      <p class="text-muted fs-8">جاري تحليل البيانات وتوليد التوصيات الذكية...</p>
    </div>

    <!-- لا توجد توصيات -->
    <div v-else-if="sortedAiRequests.length === 0" class="text-center py-5 bg-white rounded-4 shadow-2xs mb-4">
      <i class="bi bi-stars fs-1 text-warning d-block mb-2"></i>
      <p class="text-muted fs-8 mb-0">لا توجد توصيات متاحة حالياً.</p>
    </div>

    <!-- شبكة كروت التوصيات -->
    <div v-else class="row g-3 g-md-4 mb-4">
      <div v-for="aiItem in sortedAiRequests" :key="aiItem.id" class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 rounded-4 p-3 bg-white shadow-2xs h-100 d-flex flex-column justify-content-between">
          <div>
            <!-- شارة التطابق ومستوى الخطورة -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
              <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-9 fw-bold text-truncate">
                ✨ تطابق الذكاء الاصطناعي {{ aiItem.matchScore }}%
              </span>
              <span
                class="badge rounded-pill px-2.5 py-1 fs-9 fw-bold flex-shrink-0"
                :class="aiItem.urgency === 'حرجة' || aiItem.urgency === 'حرجة جداً' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning-emphasis'"
              >
                {{ aiItem.urgency }}
              </span>
            </div>

            <!-- تفاصيل المستشفى والمدينة -->
            <div class="d-flex align-items-center gap-2 text-end mb-3 min-w-0">
              <img :src="aiItem.img" alt="مستشفى" class="hospital-box-img rounded-3 flex-shrink-0" @error="handleHospitalFallback" />
              <div class="min-w-0">
                <h6 class="fw-bold text-dark mb-0 fs-7 text-truncate">{{ aiItem.hospital }}</h6>
                <small class="text-muted fs-9 d-block text-truncate">{{ aiItem.location }}</small>
              </div>
            </div>

            <!-- التفاصيل الأساسية: المسافة، عدد الوحدات، فصيلة الدم -->
            <div class="row text-center align-items-center my-3 py-2 bg-light-subtle rounded-3 g-0 border border-light">
              <div class="col-4">
                <small class="text-muted fs-9 d-block mb-1">المسافة</small>
                <span class="fw-bold text-dark fs-8">{{ aiItem.distance }} كم</span>
              </div>
              <div class="col-4 border-start border-end">
                <small class="text-muted fs-9 d-block mb-1">الوحدات</small>
                <span class="fw-bold text-dark fs-8">{{ aiItem.units }}</span>
              </div>
              <div class="col-4">
                <small class="text-muted fs-9 d-block mb-1">الفصيلة</small>
                <span class="fw-black text-danger fs-6">{{ aiItem.bloodType }}</span>
              </div>
            </div>

            <!-- سبب الترشيح من الذكاء الاصطناعي -->
            <div class="p-2 bg-success bg-opacity-10 text-success rounded-3 fs-9 text-center mb-3">
              <i class="bi bi-check-circle-fill me-1"></i> {{ aiItem.recommendationText }}
            </div>
          </div>

          <!-- زر عرض التفاصيل والقبول -->
          <button @click="$emit('select-request', aiItem)" class="btn btn-outline-danger w-100 py-2 fw-bold fs-8 border-danger-subtle figma-btn-radius">
            عرض التفاصيل
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  requests: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
});

defineEmits(['select-request']);

const getImageUrl = (fileName) => {
  return new URL(`../../../assets/images/${fileName}`, import.meta.url).href;
};

const handleHospitalFallback = (e) => {
  e.target.src = getImageUrl('hospital.png');
};

// ترتيب الطلبات تلقائياً بناءً على نسبة التطابق (Match Score) لتظهر في التبويب الذكي
const sortedAiRequests = computed(() => {
  return [...props.requests].sort((a, b) => b.matchScore - a.matchScore);
});
</script>

<style scoped>
.hospital-box-img { width: 55px; height: 50px; object-fit: cover; }
@media (min-width: 768px) { .hospital-box-img { width: 65px; height: 55px; } }

.border-danger-subtle { border-color: #fca5a5 !important; }
.figma-btn-radius { border-radius: 6px !important; }

.fs-6 { font-size: 1.05rem; }
.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.fw-black { font-weight: 900; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
</style>
