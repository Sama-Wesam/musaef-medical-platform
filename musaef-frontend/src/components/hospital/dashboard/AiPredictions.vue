<template>
  <div class="dashboard-card h-100 d-flex flex-column justify-content-between">
    <div>
      <div class="d-flex justify-content-start align-items-center gap-2 mb-3">
        <span class="fs-4">🧠</span>
        <h5 class="fw-bold mb-0 fs-6 fs-md-5">توقعات الذكاء الاصطناعي</h5>
      </div>
      <div class="row align-items-center g-3">
        <div class="col-12 col-md-7 text-end">
          <h6 class="fw-bold mb-2 lh-base fs-7 fs-md-6">{{ prediction.title || 'تم التنبؤ بارتفاع الطلب على فصيلة O+ خلال 72 ساعة القادمة.' }}</h6>
          <p class="text-muted small mb-0">{{ prediction.description || 'زيادة حملات التبرع لهذه الفصيلة لضمان توفر المخزون.' }}</p>
        </div>
        <div class="col-12 col-md-5">
          <div class="ai-line-chart">
            <svg viewBox="0 0 200 90" class="w-100">
              <line x1="0" y1="15" x2="200" y2="15" stroke="#f1f5f9" stroke-width="1" />
              <line x1="0" y1="45" x2="200" y2="45" stroke="#f1f5f9" stroke-width="1" />
              <line x1="0" y1="75" x2="200" y2="75" stroke="#f1f5f9" stroke-width="1" />
              <path d="M 10 65 Q 40 50, 70 58 T 130 58" fill="none" stroke="#dc2626" stroke-width="2" />
              <path d="M 130 58 Q 155 45, 175 32 T 192 18" fill="none" stroke="#dc2626" stroke-width="2" stroke-dasharray="4" />
              <polyline points="186,15 194,17 190,24" fill="none" stroke="#dc2626" stroke-width="2" />
            </svg>
            <div class="d-flex justify-content-between text-muted x-axis-labels">
              <span>الان</span>
              <span>24 ساعة</span>
              <span>48 ساعة</span>
              <span>72 ساعة</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <button class="btn btn-danger-subtle text-danger fw-bold w-100 py-2 py-md-2.5 rounded-3 border-0 mt-4 fs-8 fs-md-7">
      عرض التقرير الكامل
    </button>
  </div>
</template>

<script setup>
defineProps({
  prediction: {
    type: Object,
    default: () => ({})
  }
});
</script>

<style scoped>
.dashboard-card { background: #fff; border-radius: 16px; padding: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
@media (min-width: 768px) { .dashboard-card { padding: 20px; } }

.ai-line-chart { position: relative; }
.x-axis-labels { font-size: 9px; padding: 0 4px; margin-top: 4px; }

.fs-7 { font-size: 0.9rem; }
.fs-md-6 { font-size: 1rem; }
.fs-8 { font-size: 0.85rem; }
.fs-md-7 { font-size: 0.95rem; }

.btn-danger-subtle { background: #FDECEC; transition: 0.2s; }
.btn-danger-subtle:hover { background: #fcd3d3; }
</style>
