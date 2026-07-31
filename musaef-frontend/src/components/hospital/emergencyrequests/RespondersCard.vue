<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="fw-bold text-dark mb-0 fs-7">المستجيبون للنداء ({{ donors.length }})</h6>
      <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 fs-9 fw-bold">مطابقة ذكية AI</span>
    </div>

    <div class="d-flex flex-column gap-2.5">
      <div v-for="donor in donors" :key="donor.id" class="p-2.5 bg-light rounded-3 border d-flex align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <div class="avatar-circle bg-danger-subtle text-danger fw-bold rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; font-size: 0.8rem;">
            {{ donor.blood_type || 'O+' }}
          </div>
          <div class="min-w-0">
            <h6 class="fw-bold text-dark mb-0.5 fs-8 text-truncate">{{ donor.name || 'متبرع نشط' }}</h6>
            <small class="text-muted fs-9 d-block">وصول مقدر: {{ donor.eta_minutes || 10 }} دقائق ({{ donor.distance_km || 2.4 }} كم)</small>
          </div>
        </div>

        <div class="text-center flex-shrink-0">
          <span class="badge bg-success text-white rounded-pill px-2 py-1 fs-9 fw-bold">
            {{ donor.match_score || 94 }}% تطابق
          </span>
        </div>
      </div>

      <div v-if="!donors.length" class="text-center text-muted py-3 fs-8">
        جاري استجابة المتبرعين المطابقين عبر خوارزمية Smart Matching...
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  donors: {
    type: Array,
    default: () => [
      { id: 1, name: 'أحمد محمد', blood_type: 'O+', match_score: 96, eta_minutes: 5, distance_km: 1.2 },
      { id: 2, name: 'محمود خالد', blood_type: 'O+', match_score: 89, eta_minutes: 12, distance_km: 3.8 }
    ]
  }
});
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-danger-subtle { background-color: #fee2e2 !important; }
</style>
