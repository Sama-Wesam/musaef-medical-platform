<template>
  <div class="card border-0 rounded-4 shadow-sm p-3 p-md-4 mb-3 mb-md-4 hero-status-banner position-relative overflow-hidden">
    <div class="pink-heart-bg heart-1">💖</div>
    <div class="pink-heart-bg heart-2">💕</div>
    <div class="pink-heart-bg heart-3">💖</div>
    <div class="pink-heart-bg heart-4">✨</div>

    <div class="row align-items-center gy-3 gy-md-4 position-relative z-2">
      <!-- قسم العداد الدائري -->
      <div class="col-12 col-md-4 col-lg-3 text-center order-2 order-md-1">
        <div class="d-flex flex-column align-items-center justify-content-center mx-auto">
          <div class="figma-red-progress-ring position-relative d-flex flex-column align-items-center justify-content-center shadow-sm">
            <div class="inner-circle bg-white rounded-circle d-flex flex-column align-items-center justify-content-center">
              <i class="bi bi-calendar3 text-danger fs-6 fs-md-5 mb-1"></i>
              <span class="fs-3 fs-md-2 fw-black text-dark lh-1">{{ stats.daysUntilNextDonation || 45 }}</span>
              <span class="fs-9 fw-bold text-dark mt-1">يوماً متبقية</span>
            </div>
          </div>
          <small class="text-muted fs-9 mt-2 text-center d-block">حتى التبرع التالي</small>
        </div>
      </div>

      <!-- الجزء الأوسط: النصوص والأيقونات مرتبطة بحالة الاستبيان -->
      <div class="col-12 col-md-4 col-lg-6 text-center order-1 order-md-2">
        <h2 class="fw-black mb-2 hero-status-title" :class="donorStore.healthEligibility.isEligible ? 'text-success' : 'text-danger'">
          {{ donorStore.healthEligibility.statusTitle }}
        </h2>

        <h5 class="fw-bold text-dark mb-2 fs-6 fs-md-5">
          {{ donorStore.healthEligibility.statusDescription }}
        </h5>

        <p class="text-secondary fs-8 mb-2 mb-md-3">
          {{ donorStore.healthEligibility.detailedMessage }}
        </p>

        <div class="text-secondary fs-8 mb-3">
          لديك <strong class="text-danger fw-bold fs-7">{{ stats.points || 230 }}</strong> نقطة و <strong class="text-danger fw-bold fs-7">{{ stats.badgesCount || 3 }}</strong> شارات
        </div>

        <div class="d-flex align-items-center justify-content-center gap-2 gap-sm-3">
          <div class="badge-icon-item yellow-circle shadow-sm">
            <img :src="starIcon" alt="نجمة" class="badge-img" />
          </div>
          <div class="badge-icon-item pink-circle shadow-sm">
            <img :src="bloodIcon" alt="قطرة" class="badge-img" />
          </div>
          <div class="badge-icon-item pink-circle shadow-sm">
            <img :src="heartIcon" alt="قلب" class="badge-img" />
          </div>
        </div>
      </div>

      <!-- جهة اليسار: قطرة الدم والشعار -->
      <div class="col-12 col-md-4 col-lg-3 text-center text-md-end order-3">
        <div class="hero-left-drop-wrapper position-relative d-inline-block">
          <img :src="vectorIcon" alt="نبض" class="pulse-vector-bg" />
          <img :src="bloodShieldImg" alt="مؤهل" class="drop-shield-img position-relative z-2" @error="handleHeroDropFallback" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useDonorStore } from '@/stores/donorStore';

// استيراد الأيقونات مباشرة لضمان تحميلها بدون مشاكل المسارات في Vite
import starIcon from '@/assets/icons/star.png';
import bloodIcon from '@/assets/icons/blood-icon.png';
import heartIcon from '@/assets/icons/heart.png';
import vectorIcon from '@/assets/icons/Vector 9.png';
import bloodShieldImg from '@/assets/icons/blood.png';

defineProps({
  stats: {
    type: Object,
    default: () => ({})
  }
});

const donorStore = useDonorStore();

const handleHeroDropFallback = (e) => { e.target.src = bloodShieldImg; };
</script>

<style scoped>
.hero-status-banner { background: linear-gradient(135deg, #fff5f5 0%, #fdecec 100%); border: 1px solid #fca5a5 !important; }
.pink-heart-bg { position: absolute; font-size: 1.2rem; opacity: 0.25; pointer-events: none; z-index: 1; }
.heart-1 { top: 15%; left: 10%; }
.heart-2 { top: 70%; left: 25%; }
.heart-3 { top: 20%; right: 40%; }
.heart-4 { top: 75%; right: 15%; }

.figma-red-progress-ring {
  width: 105px;
  height: 105px;
  border-radius: 50%;
  background: conic-gradient(#dc2626 0deg 135deg, #f1f5f9 135deg 360deg);
  padding: 6px;
}

@media (min-width: 768px) {
  .figma-red-progress-ring {
    width: 120px;
    height: 120px;
    padding: 8px;
  }
}

.figma-red-progress-ring .inner-circle { width: 100%; height: 100%; }

.hero-left-drop-wrapper { position: relative; min-height: 130px; display: flex; align-items: center; justify-content: center; }
@media (min-width: 768px) { .hero-left-drop-wrapper { min-height: 160px; } }

.pulse-vector-bg { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 130%; max-width: 280px; height: auto; z-index: 1; pointer-events: none; }
.drop-shield-img { max-height: 120px; width: auto; filter: drop-shadow(0 10px 15px rgba(220, 38, 38, 0.15)); }
@media (min-width: 768px) { .drop-shield-img { max-height: 160px; } }

.hero-status-title { font-size: 24px; }
@media (min-width: 768px) { .hero-status-title { font-size: 32px; } }

.badge-icon-item { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
@media (min-width: 768px) { .badge-icon-item { width: 44px; height: 44px; } }

.badge-img { width: 20px; height: 20px; object-fit: contain; }
@media (min-width: 768px) { .badge-img { width: 24px; height: 24px; } }

.pink-circle { background-color: #fdecec; }
.yellow-circle { background-color: #fef9c3; }

.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.fw-black { font-weight: 900; }
</style>
