<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="fw-bold text-dark mb-0 fs-7">
        {{ t('respondersTitle') }} ({{ donors.length }})
      </h6>
      <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 fs-9 fw-bold">
        {{ t('smartMatching') }}
      </span>
    </div>

    <div class="d-flex flex-column gap-2.5">
      <div v-for="donor in donors" :key="donor.id" class="p-2.5 bg-light rounded-3 border d-flex align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <div class="avatar-circle bg-danger-subtle text-danger fw-bold rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; font-size: 0.8rem;">
            {{ donor.blood_type || 'O+' }}
          </div>
          <div class="min-w-0" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
            <h6 class="fw-bold text-dark mb-0.5 fs-8 text-truncate">
              {{ translateDonorName(donor.name) }}
            </h6>
            <small class="text-muted fs-9 d-block">
              {{ translateEta(donor.eta_minutes || 10, donor.distance_km || 2.4) }}
            </small>
          </div>
        </div>

        <div class="text-center flex-shrink-0">
          <span class="badge bg-success text-white rounded-pill px-2 py-1 fs-9 fw-bold">
            {{ donor.match_score || 94 }}% {{ t('match') }}
          </span>
        </div>
      </div>

      <div v-if="!donors.length" class="text-center text-muted py-3 fs-8">
        {{ t('waitingResponders') }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    respondersTitle: "المستجيبون للنداء",
    smartMatching: "مطابقة ذكية AI",
    match: "تطابق",
    waitingResponders: "جاري استجابة المتبرعين المطابقين عبر خوارزمية Smart Matching..."
  },
  en: {
    respondersTitle: "Call Responders",
    smartMatching: "Smart Matching AI",
    match: "Match",
    waitingResponders: "Matching donors are responding via Smart Matching algorithm..."
  }
};

const donorNameDict = {
  'متبرع استجابة فورية': 'Instant Response Donor',
  'أحمد محمد': 'Ahmed Mohamed',
  'سليم حسن': 'Saleem Hassan',
  'فهد محمود': 'Fahad Mahmoud',
  'محمود علي': 'Mahmoud Ali',
  'خالد عبد الله': 'Khaled Abdullah',
  'يوسف رامي': 'Youssef Rami',
  'متبرع نشط': 'Active Donor'
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;

const translateDonorName = (name) => {
  if (!name) return currentLanguage.value === 'en' ? 'Instant Response Donor' : 'متبرع استجابة فورية';
  return currentLanguage.value === 'en' ? (donorNameDict[name] || name) : name;
};

const translateEta = (eta, distance) => {
  if (currentLanguage.value === 'en') {
    return `ETA: ${eta} mins (${distance} km)`;
  }
  return `وصول مقدر: ${eta} دقائق (${distance} كم)`;
};

defineProps({
  donors: {
    type: Array,
    default: () => []
  }
});
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
