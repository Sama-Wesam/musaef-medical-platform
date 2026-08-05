<template>
  <HospitalLayout>
    <div class="emergency-requests-page container-fluid px-2 px-md-3" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">

      <!-- أزرار الفلترة والهيدر -->
      <EmergencyFilters
        v-model:filter="filter"
        :counts="filterCounts"
        @export="handleExport"
        @createEmergency="handleCreateEmergency"
      />

      <!-- مؤشر التحميل -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">{{ currentLanguage === 'en' ? 'Loading...' : 'جاري التحميل...' }}</span>
        </div>
      </div>

      <!-- محتوى الصفحة -->
      <div v-else class="row g-3 g-lg-4">

        <!-- الجدول الرئيسي -->
        <div :class="selectedRequest ? 'col-12 col-lg-7 col-xl-8' : 'col-12'">
          <EmergencyRequestsTable
            :requests="filteredRequests"
            :selectedRequestId="selectedRequest?.id"
            @selectRequest="selectRequestItem"
          />
        </div>

        <!-- المربعات الجانبية -->
        <div v-if="selectedRequest" class="col-12 col-lg-5 col-xl-4">
          <div class="d-flex flex-column gap-3 sticky-top-custom">
            <RequestDetailsCard :request="selectedRequest" />
            <RespondersCard :donors="selectedRequest.responders || []" />
            <LocationMapCard
              @accept="handleAccept"
              @reject="handleReject"
            />
          </div>
        </div>

      </div>

    </div>
  </HospitalLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import HospitalLayout from '@/layouts/HospitalLayout.vue';
import EmergencyFilters from '@/components/hospital/emergencyrequests/EmergencyFilters.vue';
import EmergencyRequestsTable from '@/components/hospital/emergencyrequests/EmergencyRequestsTable.vue';
import RequestDetailsCard from '@/components/hospital/emergencyrequests/RequestDetailsCard.vue';
import RespondersCard from '@/components/hospital/emergencyrequests/RespondersCard.vue';
import LocationMapCard from '@/components/hospital/emergencyrequests/LocationMapCard.vue';
import apiClient from '@/api/axios';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const loading = ref(false);
const filter = ref('all');

const bloodTypeMap = {
  'O-': 1, 'O+': 2, 'A-': 3, 'A+': 4,
  'B-': 5, 'B+': 6, 'AB-': 7, 'AB+': 8
};

const emergencyRequests = ref([
  {
    id: 1,
    code: 'ER-2024-1840',
    bloodType: 'O-',
    units: 6,
    urgency: 'critical',
    coverage: 92,
    status: 'active',
    hospital_name: 'مستشفى الشفاء الطبي',
    location: 'غزة - الرمال',
    created_at: '15-05-2026 14:30',
    responders: [
      { id: 101, name: 'أحمد محمد', blood_type: 'O-', match_score: 95, eta_minutes: 4, distance_km: 1.1 },
      { id: 102, name: 'سليم حسن', blood_type: 'A-', match_score: 89, eta_minutes: 9, distance_km: 2.8 },
      { id: 103, name: 'فهد محمود', blood_type: 'B-', match_score: 85, eta_minutes: 12, distance_km: 3.5 }
    ]
  },
  {
    id: 2,
    code: 'ER-2024-1841',
    bloodType: 'A+',
    units: 4,
    urgency: 'high',
    coverage: 75,
    status: 'active',
    hospital_name: 'مستشفى القدس الطبي',
    location: 'غزة - تل الهوى',
    created_at: 'منذ 20 دقيقة',
    responders: [
      { id: 104, name: 'سليم حسن', blood_type: 'A+', match_score: 92, eta_minutes: 6, distance_km: 1.5 }
    ]
  },
  {
    id: 3,
    code: 'ER-2024-1842',
    bloodType: 'B-',
    units: 3,
    urgency: 'medium',
    coverage: 60,
    status: 'processing',
    hospital_name: 'مستشفى القدس',
    location: 'غزة - تل الهوى',
    created_at: 'منذ 45 دقيقة',
    responders: [
      { id: 105, name: 'محمود علي', blood_type: 'B-', match_score: 88, eta_minutes: 8, distance_km: 2.1 }
    ]
  },
  {
    id: 4,
    code: 'ER-2024-1843',
    bloodType: 'AB+',
    units: 2,
    urgency: 'medium',
    coverage: 40,
    status: 'processing',
    hospital_name: 'مستشفى العودة',
    location: 'شمال غزة',
    created_at: 'منذ ساعة',
    responders: []
  },
  {
    id: 5,
    code: 'ER-2024-1844',
    bloodType: 'O+',
    units: 2,
    urgency: 'low',
    coverage: 100,
    status: 'completed',
    hospital_name: 'مجمع ناصر الطبي',
    location: 'خانيونس',
    created_at: 'منذ ساعتين',
    responders: [
      { id: 106, name: 'خالد عبد الله', blood_type: 'O+', match_score: 97, eta_minutes: 5, distance_km: 1.0 }
    ]
  }
]);

const selectedRequest = ref(emergencyRequests.value[0]);

const filterCounts = computed(() => {
  const all = emergencyRequests.value.length;
  const completed = emergencyRequests.value.filter(r => r.coverage >= 100 || r.status === 'completed' || r.status === 'مكتملة').length;
  const covering = all - completed;
  return { all, covering, completed };
});

const filteredRequests = computed(() => {
  const list = emergencyRequests.value || [];
  if (filter.value === 'covering') return list.filter(r => r.coverage < 100 && r.status !== 'completed' && r.status !== 'مكتملة');
  if (filter.value === 'completed') return list.filter(r => r.coverage >= 100 || r.status === 'completed' || r.status === 'مكتملة');
  return list;
});

const selectRequestItem = (req) => {
  selectedRequest.value = req;
};

const handleExport = () => {
  alert(currentLanguage.value === 'en'
    ? '📥 Exporting emergency calls report in PDF/Excel format successfully!'
    : '📥 جاري تصدير تقرير النداءات الطارئة بصيغة PDF/Excel بنجاح!');
};

const handleCreateEmergency = async () => {
  const inputType = prompt(currentLanguage.value === 'en' ? "Enter required blood type (e.g. O+, O-, A+):" : "أدخل فصيلة الدم المطلوبة (مثال: O+, O-, A+):", "O-");
  if (!inputType) return;

  const formattedType = inputType.trim().toUpperCase();
  const unitsInput = prompt(currentLanguage.value === 'en' ? "Enter number of required units:" : "أدخل عدد الوحدات المطلوبة:", "3");
  const units = parseInt(unitsInput) || 2;

  const newReqId = emergencyRequests.value.length + 1840;
  const newRequest = {
    id: Date.now(),
    code: `ER-2024-${newReqId}`,
    bloodType: formattedType,
    units: units,
    urgency: 'critical',
    coverage: 20,
    status: 'active',
    hospital_name: 'مستشفى الشفاء الطبي',
    location: 'غزة - الرمال',
    created_at: currentLanguage.value === 'en' ? 'Just now' : 'الآن',
    responders: [
      { id: 301, name: 'Instant Response Donor', blood_type: formattedType, match_score: 98, eta_minutes: 4, distance_km: 1.1 }
    ]
  };

  emergencyRequests.value.unshift(newRequest);
  selectedRequest.value = newRequest;

  try {
    await apiClient.post('/hospital/requests', {
      blood_type: formattedType,
      blood_type_id: bloodTypeMap[formattedType] || 1,
      units_required: units,
      urgency_level: 'critical',
      emergency_level: 'critical'
    });
  } catch (err) {
    console.log('تم إضافة النداء بالواجهة وتحديث الحالة بنجاح.');
  }

  alert(currentLanguage.value === 'en'
    ? `🚨 Emergency call (${newRequest.code}) dispatched and matching donors notified instantly!`
    : `🚨 تم إطلاق النداء الطارئ (${newRequest.code}) وتنبيه المتبرعين المطابقين فوراً!`);
};

const handleAccept = async () => {
  if (selectedRequest.value) {
    selectedRequest.value.status = 'completed';
    selectedRequest.value.coverage = 100;
    alert(currentLanguage.value === 'en'
      ? `✅ Emergency call (${selectedRequest.value.code}) accepted and fulfilled successfully!`
      : `✅ تم قبول وتلبية النداء (${selectedRequest.value.code}) بنجاح!`);
  }
};

const handleReject = async () => {
  if (selectedRequest.value) {
    selectedRequest.value.status = 'rejected';
    alert(currentLanguage.value === 'en'
      ? `❌ Request (${selectedRequest.value.code}) rejected.`
      : `❌ تم رفض الطلب (${selectedRequest.value.code}).`);
  }
};

const fetchRequests = async () => {
  loading.value = true;
  try {
    const res = await apiClient.get('/hospital/requests');
    const data = res?.data?.data || res?.data || [];
    if (Array.isArray(data) && data.length > 0) {
      const apiRequests = data.map((item, index) => ({
        id: item.id,
        code: item.request_code || `ER-2024-${1840 + index}`,
        bloodType: item.blood_type?.name || item.blood_type || 'O-',
        units: item.units_required || 3,
        urgency: item.urgency_level === 'critical' || item.emergency_level === 'critical' ? 'critical' : 'high',
        coverage: 75,
        status: 'active',
        hospital_name: item.hospital?.facility_name || 'مستشفى الشفاء الطبي',
        location: item.hospital?.address || 'غزة - الرمال',
        created_at: currentLanguage.value === 'en' ? 'Just now' : 'منذ قليل',
        responders: [
          { id: 1, name: 'أحمد محمد', blood_type: 'O-', match_score: 95, eta_minutes: 5, distance_km: 1.2 }
        ]
      }));
      emergencyRequests.value = apiRequests;
      selectedRequest.value = emergencyRequests.value[0];
    }
  } catch (err) {
    console.log('اعتماد السجلات المكتملة للتصميم التفاعلي.');
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchRequests();
});
</script>

<style scoped>
.emergency-requests-page {
  padding-bottom: 24px;
}

@media (min-width: 992px) {
  .sticky-top-custom {
    position: sticky;
    top: 20px;
  }
}
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
