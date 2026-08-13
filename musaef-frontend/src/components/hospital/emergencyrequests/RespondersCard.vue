<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <!-- الهيدر -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="d-flex align-items-center gap-2">
        <h6 class="fw-bold text-dark mb-0 fs-7">
          {{ t('respondersTitle') }}
        </h6>
        <span class="badge bg-danger text-white rounded-pill px-2 py-0.5 fs-9 fw-bold">
          {{ respondersList.length }}
        </span>
      </div>
      <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 fs-9 fw-bold d-flex align-items-center gap-1">
        <span class="pulse-dot"></span>
        {{ t('smartMatching') }}
      </span>
    </div>

    <!-- قائمة المتبرعين المستجيبين -->
    <div class="d-flex flex-column gap-2.5">
      <div
        v-for="donor in respondersList"
        :key="donor.id || donor.user_id"
        class="p-2.5 bg-light rounded-3 border d-flex align-items-center justify-content-between gap-2 cursor-pointer responder-card-item"
        @click="recordDonation(donor)"
      >
        <!-- بيانات المتبرع -->
        <div class="d-flex align-items-center gap-2 min-w-0">
          <div class="avatar-circle bg-danger text-white fw-bold rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 38px; height: 38px; font-size: 0.82rem;">
            {{ donor.blood_type || donor.bloodType }}
          </div>
          <div class="min-w-0" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            <h6 class="fw-bold text-dark mb-0.5 fs-8 text-truncate">
              {{ donor.name || donor.full_name || t('anonymousDonor') }}
            </h6>
            <small class="text-muted fs-9 d-block">
              {{ formatDistanceAndEta(donor) }}
            </small>
          </div>
        </div>

        <!-- نسبة التطابق وقبول الطلب -->
        <div class="text-end flex-shrink-0">
          <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 fs-9 fw-bold d-block mb-1">
            {{ calculateMatchScore(donor) }}% {{ t('match') }}
          </span>
          <small class="text-success fw-bold fs-9">
            ✓ {{ t('acceptedCall') }}
          </small>
        </div>
      </div>

      <!-- حالة الانتظار عند عدم وجود مستجيبين بعد -->
      <div v-if="respondersList.length === 0" class="text-center py-4 px-2 rounded-3 border border-dashed bg-light-subtle">
        <div class="spinner-grow spinner-grow-sm text-danger mb-2" role="status"></div>
        <p class="text-dark fw-bold fs-8 mb-1">{{ t('waitingRespondersTitle') }}</p>
        <p class="text-muted fs-9 mb-3">{{ t('waitingRespondersDesc') }}</p>

        <!-- زر تجربة/محاكاة قبول متبرع طارئ للطلب -->
        <button
          class="btn btn-outline-danger btn-sm rounded-pill fs-9 px-3"
          @click="simulateDonorAcceptance"
        >
          ⚡ {{ t('simulateAcceptance') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  donors: {
    type: Array,
    default: () => []
  },
  requiredBloodType: {
    type: String,
    default: 'O-'
  },
  requestId: {
    type: [Number, String],
    default: null
  }
});

const emit = defineEmits(['donorAccepted']);
const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');
const localDynamicResponders = ref([]);

const dictionary = {
  ar: {
    respondersTitle: "المستجيبون للنداء",
    smartMatching: "مطابقة ذكية AI",
    match: "تطابق",
    acceptedCall: "وافق على الإشعار",
    anonymousDonor: "متبرع مسجل",
    waitingRespondersTitle: "جاري انتظار قبول المتبرعين للنداء...",
    waitingRespondersDesc: "تم إرسال إشعار طارئ للمتبرعين المتوافقين بالفصيلة والموقع الجغرافي القريب.",
    simulateAcceptance: "محاكاة قبول متبرع مطابق للنداء"
  },
  en: {
    respondersTitle: "Call Responders",
    smartMatching: "Smart Matching AI",
    match: "Match",
    acceptedCall: "Accepted Call",
    anonymousDonor: "Registered Donor",
    waitingRespondersTitle: "Waiting for donors to accept the call...",
    waitingRespondersDesc: "Emergency notifications dispatched to compatible blood type & nearby location donors.",
    simulateAcceptance: "Simulate Compatible Donor Acceptance"
  }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

// تجميع المستجيبين القادمين من Props أو الأحداث اللحظية
const respondersList = computed(() => {
  const combined = [...(props.donors || []), ...localDynamicResponders.value];
  // إزالة التكرار حسب المعرف أو الاسم
  const uniqueMap = new Map();
  combined.forEach(item => {
    const key = item.id || item.user_id || item.name;
    if (key && !uniqueMap.has(key)) {
      uniqueMap.set(key, item);
    }
  });
  return Array.from(uniqueMap.values());
});

// حساب نسبة التطابق الذكية
const calculateMatchScore = (donor) => {
  if (donor.match_score || donor.match_rate) {
    return donor.match_score || donor.match_rate;
  }
  const donorType = (donor.blood_type || donor.bloodType || '').toUpperCase();
  const reqType = (props.requiredBloodType || '').toUpperCase();

  if (donorType === reqType) return 99;
  if (donorType === 'O-') return 98; // المتبرع العام
  return 95;
};

// تنسيق وقت الوصول والمسافة
const formatDistanceAndEta = (donor) => {
  const eta = donor.eta_minutes || donor.eta || 12;
  const dist = donor.distance_km || donor.distance || 2.5;

  if (currentLanguage.value === 'en') {
    return `ETA: ${eta} mins (${dist} km away)`;
  }
  return `وصول مقدر: ${eta} دقائق (${dist} كم)`;
};

// محاكاة قبول متبرع للإشعار فورياً في البيئة المحلية
const simulateDonorAcceptance = () => {
  const mockNames = currentLanguage.value === 'en'
    ? ['Dr. Omar Khaled', 'Kareem Mansour', 'Sarah Ahmed', 'Yousef Ali']
    : ['د. عمر خالد', 'كريم منصور', 'سارة أحمد', 'يوسف علي'];

  const randomName = mockNames[Math.floor(Math.random() * mockNames.length)];
  const newResponder = {
    id: Date.now(),
    name: randomName,
    blood_type: props.requiredBloodType || 'O-',
    distance_km: (Math.random() * 3 + 0.8).toFixed(1),
    eta_minutes: Math.floor(Math.random() * 15 + 5),
    match_score: 98,
    accepted_at: new Date().toISOString()
  };

  localDynamicResponders.value.push(newResponder);
  syncRespondersToDonations([newResponder]);
  emit('donorAccepted', newResponder);
};

// مزامنة سجلات التبرع الحديثة
const syncRespondersToDonations = (donorList) => {
  if (!donorList || !donorList.length) return;
  try {
    const existingRecords = JSON.parse(localStorage.getItem('musaef_recent_donations') || '[]');

    donorList.forEach(donor => {
      const donorName = donor.name || donor.full_name || 'متبرع مستجيب';
      const bloodType = donor.blood_type || donor.bloodType || props.requiredBloodType || 'O-';

      const exists = existingRecords.some(r => (r.donor_name === donorName || r.name === donorName) && r.blood_type === bloodType);
      if (!exists) {
        existingRecords.unshift({
          id: donor.id || Date.now() + Math.random(),
          donor_name: donorName,
          name: donorName,
          blood_type: bloodType,
          created_at: currentLanguage.value === 'en' ? 'Just now' : 'منذ قليل',
          formatted_time: currentLanguage.value === 'en' ? 'Just now' : 'منذ قليل'
        });
      }
    });

    localStorage.setItem('musaef_recent_donations', JSON.stringify(existingRecords.slice(0, 10)));
    window.dispatchEvent(new Event('musaef_responders_updated'));
  } catch (e) {
    console.error('Error syncing responder:', e);
  }
};

// الاستماع لأي إشعارات استجابة حقيقية من WebSockets أو LocalStorage
const handleExternalAcceptance = (event) => {
  if (event.detail && event.detail.requestId === props.requestId) {
    localDynamicResponders.value.push(event.detail.donor);
  }
};

watch(() => props.donors, (newDonors) => {
  if (newDonors && newDonors.length > 0) {
    syncRespondersToDonations(newDonors);
  }
}, { immediate: true, deep: true });

watch(() => props.requestId, () => {
  localDynamicResponders.value = [];
});

onMounted(() => {
  window.addEventListener('musaef_donor_accepted_call', handleExternalAcceptance);
});

onUnmounted(() => {
  window.removeEventListener('musaef_donor_accepted_call', handleExternalAcceptance);
});

const recordDonation = (donor) => {
  syncRespondersToDonations([donor]);
};
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #dcfce7 !important; }
.border-dashed { border-style: dashed !important; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
.cursor-pointer { cursor: pointer; }

.responder-card-item {
  transition: all 0.2s ease;
}
.responder-card-item:hover {
  background-color: #f1f5f9 !important;
  transform: translateY(-1px);
}

.pulse-dot {
  width: 6px;
  height: 6px;
  background-color: #dc2626;
  border-radius: 50%;
  display: inline-block;
  animation: pulse-animation 1.5s infinite;
}

@keyframes pulse-animation {
  0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
  70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(220, 38, 38, 0); }
  100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
}
</style>
