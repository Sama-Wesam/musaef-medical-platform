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

      <!-- مؤشر التحميل عند أول فتح للمستشفى -->
      <div v-if="loading && emergencyRequests.length === 0" class="text-center py-5">
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

            <!-- بطاقة المستجيبين الديناميكية -->
            <RespondersCard
              :donors="selectedRequest.responders || selectedRequest.donor_responses || []"
              :requiredBloodType="selectedRequest.bloodType || selectedRequest.blood_type"
              :requestId="selectedRequest.id"
            />

            <!-- بطاقة الخريطة التفاعلية بالدوائر الحرارية -->
            <LocationMapCard
              :latitude="parseFloat(selectedRequest.latitude) || 31.5"
              :longitude="parseFloat(selectedRequest.longitude) || 34.45"
              :requestId="selectedRequest.id"
              :isProcessing="actionLoading"
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
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import HospitalLayout from '@/layouts/HospitalLayout.vue';
import EmergencyFilters from '@/components/hospital/emergencyrequests/EmergencyFilters.vue';
import EmergencyRequestsTable from '@/components/hospital/emergencyrequests/EmergencyRequestsTable.vue';
import RequestDetailsCard from '@/components/hospital/emergencyrequests/RequestDetailsCard.vue';
import RespondersCard from '@/components/hospital/emergencyrequests/RespondersCard.vue';
import LocationMapCard from '@/components/hospital/emergencyrequests/LocationMapCard.vue';
import apiClient from '@/api/axios';
import { useAuthStore } from "@/stores/authStore";

const authStore = useAuthStore();
const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const getLoggedInHospitalName = () => {
  if (authStore.user?.facility_name) return authStore.user.facility_name;
  if (authStore.user?.name) return authStore.user.name;

  const savedSettings = localStorage.getItem('musaef_hospital_settings');
  if (savedSettings) {
    try {
      const parsed = JSON.parse(savedSettings);
      if (parsed.name) return parsed.name;
    } catch (e) {
      console.error(e);
    }
  }
  return "الجهة الطبية";
};

const loggedHospitalName = ref(getLoggedInHospitalName());

watch(() => authStore.user, () => {
  loggedHospitalName.value = getLoggedInHospitalName();
  emergencyRequests.value.forEach(r => {
    r.hospital_name = loggedHospitalName.value;
  });
}, { deep: true });

const loading = ref(false);
const actionLoading = ref(false);
const filter = ref('all');
let pollingInterval = null;

const bloodTypeMap = {
  'O-': 1, 'O+': 2, 'A-': 3, 'A+': 4,
  'B-': 5, 'B+': 6, 'AB-': 7, 'AB+': 8
};

const emergencyRequests = ref([]);
const selectedRequest = ref(null);

const filterCounts = computed(() => {
  const all = emergencyRequests.value.length;
  const completed = emergencyRequests.value.filter(r => r.coverage >= 100 || r.status === 'completed').length;
  const covering = all - completed;
  return { all, covering, completed };
});

const filteredRequests = computed(() => {
  const list = emergencyRequests.value || [];
  if (filter.value === 'covering') {
    return list.filter(r => r.coverage < 100 && r.status !== 'completed' && r.status !== 'rejected' && r.status !== 'cancelled');
  }
  if (filter.value === 'completed') {
    return list.filter(r => r.coverage >= 100 || r.status === 'completed');
  }
  return list;
});

const selectRequestItem = (req) => {
  selectedRequest.value = req;
};

const fetchRequests = async (isBackground = false) => {
  if (!isBackground && emergencyRequests.value.length === 0) {
    loading.value = true;
  }
  try {
    const res = await apiClient.get('/hospital/requests');
    const data = res?.data?.data || res?.data || [];
    if (Array.isArray(data)) {
      const apiRequests = data.map((item, index) => {
        const isCompleted = item.status === 'completed' || (item.responders_percent && parseInt(item.responders_percent) >= 100);
        return {
          id: item.id,
          code: item.request_code || item.code || `ER-2026-${1840 + index}`,
          bloodType: item.blood_type?.name || item.blood_type || item.bloodType || 'O-',
          units: item.units_required || item.units || 1,
          urgency: item.urgency_level === 'critical' || item.emergency_level === 'critical' ? 'critical' : (item.urgency_level || item.urgency || 'high'),
          coverage: isCompleted ? 100 : (item.coverage ?? (item.responders_percent ? parseInt(item.responders_percent) : 0)),
          status: isCompleted ? 'completed' : (item.status || 'searching'),
          hospital_name: item.hospital?.facility_name || item.hospital_name || getLoggedInHospitalName(),
          location: item.hospital?.address || item.location || 'غزة - الرمال',
          latitude: parseFloat(item.hospital?.latitude || item.latitude) || 31.514,
          longitude: parseFloat(item.hospital?.longitude || item.longitude) || 34.448,
          created_at: item.created_at || (currentLanguage.value === 'en' ? 'Just now' : 'منذ قليل'),
          responders: item.responders || item.donor_responses || []
        };
      });

      emergencyRequests.value = apiRequests;

      if (apiRequests.length > 0) {
        if (!selectedRequest.value) {
          selectedRequest.value = apiRequests[0];
        } else {
          const match = apiRequests.find(r => r.id === selectedRequest.value.id);
          if (match) {
            selectedRequest.value = match;
          } else {
            selectedRequest.value = apiRequests[0];
          }
        }
      } else {
        selectedRequest.value = null;
      }
    }
  } catch (err) {
    console.error('خطأ في جلب نداءات الطوارئ:', err);
  } finally {
    loading.value = false;
  }
};

const createAndAppendRequest = async (formattedType, units) => {
  loading.value = true;
  try {
    await apiClient.post('/hospital/requests', {
      blood_type: formattedType,
      blood_type_id: bloodTypeMap[formattedType] || 1,
      units_required: Number(units),
      urgency_level: 'critical',
      status: 'searching'
    });

    await fetchRequests(true);

    window.dispatchEvent(new CustomEvent('emergency-request-created', {
      detail: { bloodType: formattedType, units }
    }));

    alert(currentLanguage.value === 'en'
      ? `🚨 Emergency call dispatched and matching donors notified instantly!`
      : `🚨 تم إطلاق النداء الطارئ وتم تنبيه كافة المتبرعين القريبين عبر نظام Smart Matching AI!`);
  } catch (err) {
    console.error('فشل إنشاء الطلب الطارئ:', err);
    const backendMessage = err.response?.data?.message || err.response?.data?.error;
    alert(backendMessage || (currentLanguage.value === 'en'
      ? 'Error creating emergency request.'
      : 'حدث خطأ أثناء إطلاق طلب التبرع الطارئ، يرجى المحاولة لاحقاً.'));
  } finally {
    loading.value = false;
  }
};

const handleCreateEmergency = async () => {
  const inputType = prompt(currentLanguage.value === 'en' ? "Enter required blood type (e.g. O+, O-, A+):" : "أدخل فصيلة الدم المطلوبة (مثال: O+, O-, A+):", "O-");
  if (!inputType) return;

  const formattedType = inputType.trim().toUpperCase();
  const unitsInput = prompt(currentLanguage.value === 'en' ? "Enter number of required units:" : "أدخل عدد الوحدات المطلوبة:", "3");
  const units = parseInt(unitsInput) || 2;

  await createAndAppendRequest(formattedType, units);
};

const handleHeaderTriggeredCreate = (e) => {
  const { bloodType, units } = e.detail || {};
  if (bloodType) {
    createAndAppendRequest(bloodType, units || 3);
  }
};

const handleAccept = async (reqId) => {
  const targetId = reqId || selectedRequest.value?.id;
  if (!targetId) return;

  actionLoading.value = true;
  try {
    await apiClient.put(`/hospital/emergency-requests/${targetId}/status`, { status: 'completed' });

    const localReq = emergencyRequests.value.find(r => r.id === targetId);
    if (localReq) {
      localReq.status = 'completed';
      localReq.coverage = 100;
    }
    if (selectedRequest.value && selectedRequest.value.id === targetId) {
      selectedRequest.value.status = 'completed';
      selectedRequest.value.coverage = 100;
    }

    await fetchRequests(true);
  } catch (err) {
    console.error('Error accepting request:', err);
  } finally {
    actionLoading.value = false;
  }
};

const handleReject = async (reqId) => {
  const targetId = reqId || selectedRequest.value?.id;
  if (!targetId) return;

  actionLoading.value = true;
  try {
    await apiClient.put(`/hospital/emergency-requests/${targetId}/status`, { status: 'rejected' });

    const localReq = emergencyRequests.value.find(r => r.id === targetId);
    if (localReq) {
      localReq.status = 'rejected';
    }
    if (selectedRequest.value && selectedRequest.value.id === targetId) {
      selectedRequest.value.status = 'rejected';
    }

    await fetchRequests(true);
  } catch (err) {
    console.error('Error rejecting request:', err);
  } finally {
    actionLoading.value = false;
  }
};

const handleExport = (type = 'excel') => {
  const dataToExport = filteredRequests.value;
  const isEn = currentLanguage.value === 'en';

  if (type === 'excel') {
    let csvContent = "\uFEFF";
    csvContent += isEn
      ? "Request Code,Required Blood Type,Units,Urgency Level,Response Coverage,Status,Created At\n"
      : "رقم الطلب,الفصيلة المطلوبة,عدد الوحدات,مستوى الخطورة,نسبة تغطية المستجيبين,حالة الطلب,تاريخ الطلب\n";

    dataToExport.forEach(item => {
      const urgencyText = isEn
        ? (item.urgency === 'critical' ? 'Critical' : 'High')
        : (item.urgency === 'critical' ? 'حرج' : 'خطر');
      const statusText = isEn
        ? (item.coverage >= 100 || item.status === 'completed' ? 'Completed' : 'Active')
        : (item.coverage >= 100 || item.status === 'completed' ? 'مكتملة' : 'نشط');
      const row = [
        `"${item.code}"`,
        `"${item.bloodType}"`,
        `"${item.units}"`,
        `"${urgencyText}"`,
        `"${item.coverage}%"`,
        `"${statusText}"`,
        `"${item.created_at}"`
      ].join(",");
      csvContent += row + "\n";
    });

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    const url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", isEn ? `Emergency_Requests_Report.csv` : `تقرير_النداءات_الطارئة.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } else if (type === 'pdf') {
    const printWindow = window.open('', '_blank');
    if (!printWindow) return;

    const dir = isEn ? 'ltr' : 'rtl';
    const title = isEn ? "Emergency Requests Report" : "تقرير النداءات الطارئة - منصة مسعف";
    const hospitalName = loggedHospitalName.value;

    let rowsHtml = '';
    dataToExport.forEach(item => {
      const urgencyText = isEn
        ? (item.urgency === 'critical' ? 'Critical' : 'High')
        : (item.urgency === 'critical' ? 'حرج' : 'خطر');
      const statusText = isEn
        ? (item.coverage >= 100 || item.status === 'completed' ? 'Completed' : 'Active')
        : (item.coverage >= 100 || item.status === 'completed' ? 'مكتملة' : 'نشط');

      rowsHtml += `
        <tr>
          <td>${item.code}</td>
          <td style="color: #dc3545; font-weight: bold;">${item.bloodType}</td>
          <td>${item.units}</td>
          <td><span class="badge ${item.urgency === 'critical' ? 'bg-critical' : 'bg-high'}">${urgencyText}</span></td>
          <td>${item.coverage}%</td>
          <td><span class="badge ${item.coverage >= 100 || item.status === 'completed' ? 'bg-success' : 'bg-active'}">${statusText}</span></td>
        </tr>
      `;
    });

    printWindow.document.write(`
      <!DOCTYPE html>
      <html dir="${dir}" lang="${isEn ? 'en' : 'ar'}">
      <head>
        <meta charset="UTF-8">
        <title>${title}</title>
        <style>
          body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; direction: ${dir}; text-align: center; }
          .header { margin-bottom: 25px; border-bottom: 2px solid #dc3545; padding-bottom: 15px; }
          .header h2 { color: #dc3545; margin: 0 0 8px 0; font-size: 22px; }
          .header p { color: #555; margin: 0; font-size: 14px; }
          table { width: 100%; border-collapse: collapse; margin-top: 15px; }
          th { background-color: #dc3545; color: white; padding: 10px; font-size: 13px; border: 1px solid #dc3545; }
          td { padding: 10px; font-size: 13px; border: 1px solid #e2e8f0; text-align: center; }
          tr:nth-child(even) { background-color: #f8fafc; }
          .badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold; display: inline-block; }
          .bg-critical { background-color: #fff1f2; color: #e11d48; }
          .bg-high { background-color: #fef2f2; color: #dc2626; }
          .bg-success { background-color: #f0fdf4; color: #16a34a; }
          .bg-active { background-color: #fff1f2; color: #e11d48; }
          @media print {
            body { padding: 0; }
            @page { size: auto; margin: 15mm; }
          }
        </style>
      </head>
      <body>
        <div class="header">
          <h2>${title}</h2>
          <p>${isEn ? 'Facility:' : 'الجهة الطبية:'} <strong>${hospitalName}</strong></p>
        </div>
        <table>
          <thead>
            <tr>
              <th>${isEn ? 'Request Code' : 'رقم الطلب'}</th>
              <th>${isEn ? 'Blood Type' : 'الفصيلة المطلوبة'}</th>
              <th>${isEn ? 'Units' : 'عدد الوحدات'}</th>
              <th>${isEn ? 'Urgency' : 'مستوى الخطورة'}</th>
              <th>${isEn ? 'Responders' : 'عدد المستجيبين'}</th>
              <th>${isEn ? 'Status' : 'حالة الطلب'}</th>
            </tr>
          </thead>
          <tbody>
            ${rowsHtml}
          </tbody>
        </table>
        <script>
          window.onload = function() {
            window.print();
            setTimeout(function() { window.close(); }, 500);
          };
        <\/script>
      </body>
      </html>
    `);

    printWindow.document.close();
  }
};

onMounted(() => {
  fetchRequests();
  pollingInterval = setInterval(() => {
    fetchRequests(true);
  }, 5000);

  window.addEventListener('trigger-create-emergency', handleHeaderTriggeredCreate);
});

onUnmounted(() => {
  if (pollingInterval) clearInterval(pollingInterval);
  window.removeEventListener('trigger-create-emergency', handleHeaderTriggeredCreate);
});
</script>

<style scoped>
.emergency-requests-page { padding-bottom: 24px; }
@media (min-width: 992px) {
  .sticky-top-custom { position: sticky; top: 20px; }
}
</style>
