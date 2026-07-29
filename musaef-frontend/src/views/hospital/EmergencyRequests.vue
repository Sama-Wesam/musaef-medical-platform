<template>
  <HospitalLayout>
    <div class="emergency-requests-page container-fluid px-2 px-md-3" dir="rtl">

      <!-- أزرار الفلترة والهيدر -->
      <EmergencyFilters
        v-model:filter="filter"
        @export="handleExport"
      />

      <!-- مؤشر التحميل -->
      <div v-if="emergencyStore.loading" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">جاري التحميل...</span>
        </div>
      </div>

      <!-- محتوى الصفحة: الجدول والمربعات الجانبية -->
      <div v-else class="row g-3 g-lg-4">

        <!-- الجدول الرئيسي -->
        <div :class="emergencyStore.selectedRequest ? 'col-12 col-lg-7 col-xl-8' : 'col-12'">
          <EmergencyRequestsTable
            :requests="filteredRequests"
            :selectedRequestId="emergencyStore.selectedRequest?.id"
            @selectRequest="emergencyStore.selectRequest"
          />
        </div>

        <!-- المربعات الجانبية عند تحديد طلب -->
        <div v-if="emergencyStore.selectedRequest" class="col-12 col-lg-5 col-xl-4">
          <div class="d-flex flex-column gap-3 sticky-top-custom">
            <!-- 1. مربع تفاصيل الطلب -->
            <RequestDetailsCard :request="emergencyStore.selectedRequest" />

            <!-- 2. مربع المستجيبين -->
            <RespondersCard :donors="emergencyStore.responders" />

            <!-- 3. مربع الخريطة وإجراءات الطلب -->
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
import { useEmergencyRadarStore } from '@/stores/emergencyRadarStore';

const emergencyStore = useEmergencyRadarStore();
const filter = ref('all');

const filteredRequests = computed(() => {
  const list = emergencyStore.emergencyRequests || [];
  if (filter.value === 'covering') return list.filter(r => r.status === 'قيد التغطية' || r.status === 'active');
  if (filter.value === 'completed') return list.filter(r => r.status === 'مكتملة' || r.status === 'completed');
  return list;
});

const handleExport = () => {
  alert('جاري تصدير تقرير النداءات الطارئة...');
};

const handleAccept = async () => {
  await emergencyStore.acceptSelectedRequest();
  alert(`تم قبول الطلب بنجاح`);
};

const handleReject = async () => {
  await emergencyStore.rejectSelectedRequest();
  alert(`تم رفض الطلب`);
};

onMounted(() => {
  emergencyStore.fetchActiveEmergencies();
});
</script>

<style scoped>
.emergency-requests-page {
  font-family: 'Cairo', sans-serif;
  padding-bottom: 24px;
}

@media (min-width: 992px) {
  .sticky-top-custom {
    position: sticky;
    top: 20px;
  }
}
</style>
