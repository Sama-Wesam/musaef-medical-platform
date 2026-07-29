<template>
  <div class="auth-layout min-vh-100 d-flex align-items-center justify-content-center bg-light p-3 dir-rtl">
    <div class="container-fluid max-w-1200 bg-white rounded-5 shadow-lg overflow-hidden p-0">
      <div class="row g-0 min-h-650">

        <!-- الجانب الأيمن: النموذج التفاعلي -->
        <div class="col-lg-6 d-flex flex-column justify-content-center p-4 p-md-5 position-relative bg-light-gray">

          <!-- الشعار أعلى اليمين -->
          <div class="auth-logo-wrapper">
            <router-link to="/">
              <img
                :src="getImageUrl('auth-logo.png')"
                alt="مسعف"
                class="top-auth-logo"
                @error="handleLogoFallback"
              />
            </router-link>
          </div>

          <!-- مكان المكونات المتغيرة -->
          <div class="auth-form-wrapper mx-auto w-100 max-w-450 pt-4">
            <slot />
          </div>

        </div>

        <!-- الجانب الأيسر: الصورة البصرية -->
        <div class="col-lg-6 d-none d-lg-block position-relative hero-auth-col overflow-hidden text-center p-4 p-md-5">

          <img
            :src="getImageUrl('login.jpeg')"
            alt="لأن الدقيقة تساوي حياة"
            class="hero-bg-image"
          />

          <div class="position-relative z-2 h-100 d-flex flex-column justify-content-start pt-3">

            <div>
              <h2 class="fw-bold hero-heading text-dark mb-1">
                لأن الدقيقة تساوي <span class="text-danger">حياة</span>
              </h2>

              <!-- نبض القلب -->
              <div class="pulse-line-wrapper my-2 mx-auto text-center">
                <img
                  :src="getImageUrl('Vector 7.png')"
                  alt="نبض القلب"
                  class="pulse-vector-img mx-auto"
                />
              </div>

              <p class="hero-description text-dark fw-bold mb-4">
                نوصلك بالمتبرع المناسب في أسرع وقت لإنقاذ حياة محتاجة.
              </p>
            </div>

            <!-- المميزات -->
            <div class="row g-2 justify-content-center text-center mt-4">

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img
                    :src="getImageUrl('Frame 2147225319.png')"
                    alt="إنقاذ الأرواح"
                    class="feature-img"
                  />
                </div>
                <span class="d-block feature-label text-dark fw-bold">
                  إنقاذ الأرواح
                </span>
              </div>

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img
                    :src="getImageUrl('Frame 2147225318.png')"
                    alt="استجابة سريعة"
                    class="feature-img"
                  />
                </div>
                <span class="d-block feature-label text-dark fw-bold">
                  استجابة سريعة
                </span>
              </div>

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img
                    :src="getImageUrl('Frame 2147225317.png')"
                    alt="مجتمع المتبرعين"
                    class="feature-img"
                  />
                </div>
                <span class="d-block feature-label text-dark fw-bold">
                  مجتمع المتبرعين
                </span>
              </div>

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img
                    :src="getImageUrl('Frame 2147225316.png')"
                    alt="آمن وموثوق"
                    class="feature-img"
                  />
                </div>
                <span class="d-block feature-label text-dark fw-bold">
                  آمن وموثوق
                </span>
              </div>

            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();

// دالة جلب الصور لـ Vite[cite: 67]
const getImageUrl = (fileName) => {
  return new URL(`../assets/images/${fileName}`, import.meta.url).href;
};

const handleLogoFallback = (e) => {
  e.target.src = getImageUrl('logo.png');
};
</script>

<style scoped>
.dir-rtl {
  direction: rtl;
  font-family: Arial, sans-serif;
}

.max-w-1200 {
  max-width: 1080px;
}

.max-w-450 {
  max-width: 430px;
}

.min-h-650 {
  min-height: 620px;
}

.bg-light-gray {
  background-color: #f8fafc;
}

/* ==========================================
   شعار قطرة الدم
========================================== */

.auth-logo-wrapper {
  position: absolute;
  top: 24px;
  right: 24px;
  z-index: 100;
}

.top-auth-logo {
  height: 55px;
  width: auto;
  object-fit: contain;
  display: block;
}

/* ==========================================
   الجانب الأيسر البصري
========================================== */

.hero-auth-col {
  background-color: #ffffff;
  min-height: 600px;
}

.hero-bg-image {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: bottom center;
  z-index: 0;
}

.hero-heading {
  font-size: 32px;
  line-height: 1.3;
}

.pulse-line-wrapper {
  width: 100%;
  max-width: 220px;
  display: flex;
  justify-content: center;
}

.pulse-vector-img {
  width: 100%;
  max-width: 180px;
  height: auto;
  display: block;
}

.hero-description {
  font-size: 15px;
  line-height: 1.8;
}

/* ==========================================
   المميزات الأربع
========================================== */

.feature-icon-box {
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.feature-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.feature-label {
  font-size: 11px;
}
</style>
