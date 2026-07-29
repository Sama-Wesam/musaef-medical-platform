<template>
  <div class="all-requests-section">
    <!-- 1. شريط الفلترة والبحث -->
    <div class="card border-0 rounded-4 shadow-sm p-3 mb-3 mb-md-4 bg-white">
      <div class="row g-2 align-items-center">
        <div class="col-12 col-md-4 col-lg-3">
          <div class="position-relative">
            <input
              type="text"
              v-model="searchQuery"
              class="form-control form-control-sm pe-3 ps-5 rounded-3 bg-light border-0 py-2 fs-8 text-end"
              placeholder="ابحث عن مستشفى أو فصيلة..."
            />
            <i class="bi bi-search position-absolute top-50 translate-middle-y start-0 ms-3 text-muted fs-9"></i>
          </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <select v-model="sortBy" class="form-select form-select-sm text-end rounded-3 py-2 fs-8 text-secondary">
            <option value="latest">ترتيب: الأحدث</option>
            <option value="closest">الأقرب مسافة</option>
          </select>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <select v-model="selectedBloodType" class="form-select form-select-sm text-end rounded-3 py-2 fs-8 text-secondary">
            <option value="">فصيلة الدم: الكل</option>
            <option value="+A">+A</option>
            <option value="-O">-O</option>
            <option value="+O">+O</option>
            <option value="-B">-B</option>
            <option value="+AB">+AB</option>
            <option value="-A">-A</option>
          </select>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <select v-model="selectedUrgency" class="form-select form-select-sm text-end rounded-3 py-2 fs-8 text-secondary">
            <option value="">الخطورة: الكل</option>
            <option value="حرجة">حرجة</option>
            <option value="متوسطة">متوسطة</option>
            <option value="منخفضة">منخفضة</option>
          </select>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
          <select v-model="selectedDistance" class="form-select form-select-sm text-end rounded-3 py-2 fs-8 text-secondary">
            <option value="">المسافة: الكل</option>
            <option value="5">أقل من 5 كم</option>
            <option value="10">أقل من 10 كم</option>
          </select>
        </div>

        <div class="col-12 col-md-4 col-lg-1">
          <button @click="resetFilters" class="btn btn-outline-secondary btn-sm w-100 rounded-3 py-2 fs-8 fw-bold d-flex align-items-center justify-content-center gap-1 text-nowrap">
            <i class="bi bi-arrow-counterclockwise"></i>
            <span>إعادة تعيين</span>
          </button>
        </div>
      </div>
    </div>

    <!-- 2. عداد إجمالي الطلبات -->
    <div class="d-flex flex-column align-items-start mb-3 px-1 text-end">
      <div class="d-flex align-items-center gap-2 mb-1">
        <h5 class="fw-bold text-dark mb-0 fs-6">جميع الطلبات</h5>
        <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1 fs-8 fw-bold">
          {{ filteredRequests.length }} طلب
        </span>
      </div>
      <small class="text-muted fs-9 fs-md-8">تم تحديث البيانات الآن</small>
    </div>

    <!-- حالة التحميل -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-danger mb-2" role="status"></div>
      <p class="text-muted fs-8">جاري تحميل طلبات التبرع...</p>
    </div>

    <!-- لا توجد طلبات -->
    <div v-else-if="filteredRequests.length === 0" class="text-center py-5 bg-white rounded-4 shadow-2xs mb-4">
      <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
      <p class="text-muted fs-8 mb-0">لا توجد طلبات تبرع مطابقة للفلتر أو من السيرفر حالياً.</p>
    </div>

    <!-- 3. شبكة عرض كروت التبرع -->
    <div v-else class="row g-3 g-md-4 mb-4">
      <div v-for="item in filteredRequests" :key="item.id" class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 rounded-4 p-3 bg-white shadow-2xs h-100 d-flex flex-column justify-content-between">
          <div>
            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
              <div class="d-flex align-items-center gap-2 text-end min-w-0">
                <img :src="item.img" alt="مستشفى" class="hospital-box-img rounded-3 flex-shrink-0" @error="handleHospitalFallback" />
                <div class="min-w-0">
                  <h6 class="fw-bold text-dark mb-0 fs-7 text-truncate">{{ item.hospital }}</h6>
                  <small class="text-muted fs-9 d-block text-truncate">{{ item.location }}</small>
                </div>
              </div>
              <span class="badge rounded-pill px-2.5 py-1 fs-9 fw-bold flex-shrink-0" :class="getUrgencyBadgeClass(item.urgency)">
                {{ item.urgency }}
              </span>
            </div>

            <div class="row text-center align-items-center my-3 py-2 bg-light-subtle rounded-3 g-0 border border-light">
              <div class="col-4">
                <small class="text-muted fs-9 d-block mb-1">المسافة</small>
                <span class="fw-bold text-dark fs-8">{{ item.distance }} كم</span>
              </div>
              <div class="col-4 border-start border-end">
                <small class="text-muted fs-9 d-block mb-1">الوحدات</small>
                <span class="fw-bold text-dark fs-8">{{ item.units }}</span>
              </div>
              <div class="col-4">
                <small class="text-muted fs-9 d-block mb-1">الفصيلة</small>
                <span class="fw-black text-danger fs-6">{{ item.bloodType }}</span>
              </div>
            </div>
          </div>

          <button @click="$emit('select-request', item)" class="btn btn-outline-danger w-100 py-2 fw-bold fs-8 border-danger-subtle figma-btn-radius">
            عرض التفاصيل
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

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

const searchQuery = ref('');
const sortBy = ref('latest');
const selectedBloodType = ref('');
const selectedUrgency = ref('');
const selectedDistance = ref('');

const getImageUrl = (fileName) => {
  return new URL(`../../../assets/images/${fileName}`, import.meta.url).href;
};

const handleHospitalFallback = (e) => {
  e.target.src = getImageUrl('hospital.png');
};

const filteredRequests = computed(() => {
  return props.requests.filter(item => {
    const matchesSearch = !searchQuery.value || item.hospital.toLowerCase().includes(searchQuery.value.toLowerCase()) || item.bloodType.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesBlood = !selectedBloodType.value || item.bloodType === selectedBloodType.value;
    const matchesUrgency = !selectedUrgency.value || item.urgency === selectedUrgency.value;
    const matchesDistance = !selectedDistance.value || item.distance <= parseFloat(selectedDistance.value);
    return matchesSearch && matchesBlood && matchesUrgency && matchesDistance;
  });
});

const resetFilters = () => {
  searchQuery.value = '';
  sortBy.value = 'latest';
  selectedBloodType.value = '';
  selectedUrgency.value = '';
  selectedDistance.value = '';
};

const getUrgencyBadgeClass = (urgency) => {
  if (urgency === 'حرجة' || urgency === 'حرجة جداً') return 'bg-danger-subtle text-danger';
  if (urgency === 'متوسطة') return 'bg-warning-subtle text-warning-emphasis';
  return 'bg-secondary-subtle text-secondary';
};
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
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
</style>
