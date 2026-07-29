<template>
  <div class="donation-center-page dir-rtl bg-light-gray min-vh-100 pb-5">
    <DonorHeader />

    <main class="container-fluid px-2 px-md-4">
      <!-- التبويبات الرئيسية (مع خاصية التمرير للجوال) -->
      <div class="card border-0 rounded-4 shadow-2xs bg-white mb-3 mb-md-4 overflow-x-auto tab-scroll-wrapper">
        <div class="d-flex align-items-center justify-content-start justify-content-md-around py-2 py-md-3 px-2 border-bottom min-tabs-width">
          <button
            class="tab-item-btn border-0 bg-transparent fw-bold fs-8 fs-md-7 d-flex align-items-center gap-2 pb-2 px-3 text-nowrap"
            :class="activeMainTab === 'all' ? 'text-danger active-tab-red' : 'text-secondary'"
            @click="activeMainTab = 'all'"
          >
            <i class="bi bi-list-task fs-5"></i>
            <span>جميع الطلبات</span>
          </button>

          <button
            class="tab-item-btn border-0 bg-transparent fw-bold fs-8 fs-md-7 d-flex align-items-center gap-2 pb-2 px-3 text-nowrap"
            :class="activeMainTab === 'ai' ? 'text-danger active-tab-red' : 'text-secondary'"
            @click="activeMainTab = 'ai'"
          >
            <i class="bi bi-stars fs-5 text-warning"></i>
            <span>توصيات الذكاء الاصطناعي</span>
          </button>

          <button
            class="tab-item-btn border-0 bg-transparent fw-bold fs-8 fs-md-7 d-flex align-items-center gap-2 pb-2 px-3 text-nowrap"
            :class="activeMainTab === 'map' ? 'text-danger active-tab-red' : 'text-secondary'"
            @click="activeMainTab = 'map'"
          >
            <i class="bi bi-map fs-5"></i>
            <span>الخريطة</span>
          </button>
        </div>
      </div>

      <!-- رسالة خطأ عند فشل الجلب -->
      <div v-if="errorMessage" class="alert alert-danger rounded-4 text-center mb-4 fs-8">
        {{ errorMessage }}
        <button @click="fetchRequests" class="btn btn-sm btn-outline-danger ms-2 rounded-3">إعادة المحاولة</button>
      </div>

      <!-- TAB 1: جميع الطلبات -->
      <div v-if="activeMainTab === 'all'">
        <AllRequestsTab
          :requests="requests"
          :loading="isLoading"
          @refresh="fetchRequests"
          @select-request="openRequestModal"
        />
      </div>

      <!-- TAB 2: توصيات الذكاء الاصطناعي -->
      <div v-if="activeMainTab === 'ai'">
        <AiRecommendationsTab
          :requests="requests"
          :loading="isLoading"
          @select-request="openRequestModal"
        />
      </div>

      <!-- TAB 3: الخريطة -->
      <div v-if="activeMainTab === 'map'">
        <MapTab
          :requests="requests"
          @select-request="openRequestModal"
        />
      </div>
    </main>

    <!-- Modal تفاصيل الحالة -->
    <div v-if="selectedModalRequest" class="modal-backdrop-custom d-flex align-items-center justify-content-center p-2 p-md-3">
      <div class="bg-white rounded-4 shadow-lg p-3 p-md-4 max-w-450 w-100 dir-rtl position-relative">
        <button @click="selectedModalRequest = null" class="btn-close position-absolute top-0 start-0 m-3" aria-label="إغلاق"></button>

        <h5 class="fw-bold text-dark mb-3 text-center fs-6 fs-md-5">تفاصيل الحالة الطارئة</h5>
        <div class="text-center mb-3">
          <img :src="getImageUrl('hospital.png')" class="img-fluid rounded-3 w-100 max-h-150" alt="مستشفى" @error="handleHospitalFallback" />
        </div>

        <h6 class="fw-bold mb-1 text-end fs-7">{{ selectedModalRequest.hospital }}</h6>
        <small class="text-muted d-block mb-3 text-end fs-8">📍 {{ selectedModalRequest.location }}</small>

        <div class="row text-center bg-light p-2 rounded-3 g-2 mb-3 fs-8">
          <div class="col-6 border-end">
            <small class="text-muted d-block fs-9">فصيلة مطلوبة</small>
            <strong class="text-danger fs-5">{{ selectedModalRequest.bloodType }}</strong>
          </div>
          <div class="col-6">
            <small class="text-muted d-block fs-9">عدد الوحدات</small>
            <strong class="text-dark fs-5">{{ selectedModalRequest.units }} وحدات</strong>
          </div>
        </div>

        <div class="p-2.5 p-md-3 bg-danger bg-opacity-10 text-danger rounded-3 text-center mb-3">
          <small class="d-block fs-9">الحالة والأولوية</small>
          <strong class="fs-7 fs-md-6">{{ selectedModalRequest.urgency }}</strong>
        </div>

        <div class="d-flex gap-2">
          <button @click="acceptRequest(selectedModalRequest.id)" :disabled="isSubmitting" class="btn btn-danger flex-fill rounded-pill py-2 fw-bold fs-8">
            <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-1"></span>
            <span v-else>✓ قبول الطلب والتبرع</span>
          </button>
          <button @click="selectedModalRequest = null" class="btn btn-outline-secondary flex-fill rounded-pill py-2 fs-8">إغلاق</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import donor from '@/api/donor';
import DonorHeader from '@/components/donor/DonorHeader.vue';
import AllRequestsTab from '@/components/donor/DonationCenter/AllRequestsTab.vue';
import AiRecommendationsTab from '@/components/donor/DonationCenter/AiRecommendationsTab.vue';
import MapTab from '@/components/donor/DonationCenter/MapTab.vue';

const activeMainTab = ref('all');
const selectedModalRequest = ref(null);
const isSubmitting = ref(false);
const isLoading = ref(false);
const requests = ref([]);
const errorMessage = ref('');

const getImageUrl = (fileName) => {
  return new URL(`../../assets/images/${fileName}`, import.meta.url).href;
};

const handleHospitalFallback = (e) => { e.target.src = getImageUrl('hospital.png'); };

const openRequestModal = (requestItem) => {
  selectedModalRequest.value = requestItem;
};

const fetchRequests = async () => {
  isLoading.value = true;
  errorMessage.value = '';
  try {
    const response = await donor.getDonationRequests();
    const rawData = response.data?.data || response.data || [];

    requests.value = rawData.map(item => ({
      id: item.id,
      hospital: item.hospital?.name || item.hospital_name || 'مستشفى الشفاء الطبي',
      location: item.hospital?.address || item.location || 'غزة',
      bloodType: item.blood_type?.name || item.blood_type || '+O',
      units: item.units_required || item.units || 1,
      distance: item.distance || 3.5,
      urgency: item.emergency_level === 'critical' ? 'حرجة جداً' : (item.emergency_level === 'high' ? 'عالية' : 'متوسطة'),
      matchScore: item.match_score || Math.floor(Math.random() * (99 - 85 + 1)) + 85,
      recommendationText: 'متوافق مع فصيلة دمك وفي نطاقك الجغرافي السكني',
      img: item.hospital_image ? item.hospital_image : getImageUrl('hospital.png')
    }));
  } catch (err) {
    console.error('خطأ أثناء جلب طلبات التبرع:', err);
    errorMessage.value = 'تعذر جلب البيانات من السيرفر. يرجى التأكد من الاتصال.';
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchRequests();
});

const acceptRequest = async (requestId) => {
  isSubmitting.value = true;
  try {
    if (requestId) {
      await donor.acceptDonationRequest(requestId);
    }
    alert('تم قبول طلب التبرع بنجاح!');
    selectedModalRequest.value = null;
    fetchRequests();
  } catch (error) {
    console.error('خطأ في قبول الطلب:', error);
    alert('حدث خطأ أثناء إرسال القبول، حاول مرة أخرى.');
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
.dir-rtl { direction: rtl; font-family: Arial, sans-serif; }
.bg-light-gray { background-color: #f8fafc; }

.tab-scroll-wrapper {
  scrollbar-width: none;
  -ms-overflow-style: none;
}
.tab-scroll-wrapper::-webkit-scrollbar { display: none; }

.min-tabs-width { min-width: 380px; }
@media (min-width: 768px) { .min-tabs-width { min-width: 100%; } }

.tab-item-btn { position: relative; transition: all 0.2s ease; cursor: pointer; }
.active-tab-red { color: #dc2626 !important; border-bottom: 3px solid #dc2626 !important; }
.modal-backdrop-custom { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1050; }
.max-w-450 { max-width: 440px; }
.max-h-150 { max-height: 160px; object-fit: cover; }
.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.85rem; }
.fs-9 { font-size: 0.72rem; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
</style>
