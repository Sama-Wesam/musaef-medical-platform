<template>
  <div class="auth-page-wrapper min-vh-100 d-flex align-items-center justify-content-center dir-rtl py-3 py-md-4">
    <div class="container px-2 px-md-3">
      <div class="row g-0 auth-main-card shadow-lg rounded-5 overflow-hidden bg-white mx-auto position-relative">

        <!-- ================= 1. القسم الأيمن: نموذج استعادة كلمة المرور ================= -->
        <div class="col-12 col-lg-6 form-section-col p-3 p-sm-4 p-md-5 d-flex flex-column justify-content-center position-relative bg-light-gray order-2 order-lg-1">

          <!-- شعار قطرة الدم علوياً -->
          <div class="top-logo-container position-absolute top-0 end-0 p-3 p-md-4 z-3">
            <img :src="getImageUrl('auth-logo.png')" alt="مسعف" class="top-auth-logo" @error="handleLogoFallback" />
          </div>

          <div class="auth-card-inner bg-white rounded-4 p-3 p-sm-4 shadow-sm mt-5 mt-lg-4 mx-auto w-100">

            <!-- العنوان الوصفي -->
            <div class="text-end mb-4">
              <h4 class="fw-bold text-dark mb-2 page-title">استعادة كلمة المرور</h4>
              <p class="text-secondary fs-8 page-desc mb-0">
                أدخل بريدك الإلكتروني، وسنرسل لك رابط استعادة كلمة المرور.
              </p>
            </div>

            <!-- رسالة الخطأ إن وجدت -->
            <div v-if="error" class="alert alert-danger rounded-3 fs-8 mb-3 text-center">
              {{ error }}
            </div>

            <!-- النموذج -->
            <form @submit.prevent="handleReset">
              <div class="mb-4 text-end">
                <label class="form-label fs-8 text-dark fw-bold mb-1 d-block text-end">البريد الإلكتروني</label>
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted border-start-0 order-2">
                    <i class="bi bi-envelope"></i>
                  </span>
                  <input
                    v-model="email"
                    type="email"
                    class="form-control border-end-0 text-end fs-8 order-1"
                    placeholder="أدخل بريدك الإلكتروني"
                    required
                  />
                </div>
              </div>

              <!-- زر إرسال رابط الاستعادة -->
              <button
                type="submit"
                class="btn btn-danger w-100 rounded-3 py-2 fw-bold text-white shadow-sm mb-3 btn-submit-red"
                :disabled="loading"
              >
                <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                إرسال رابط الاستعادة
              </button>

              <!-- رابط العودة -->
              <div class="text-center mt-3">
                <router-link to="/login" class="text-secondary fs-8 text-decoration-none hover-danger">
                  العودة لصفحة تسجيل الدخول &gt;
                </router-link>
              </div>
            </form>

            <!-- رسالة إشعار النجاح -->
            <div v-if="sent" class="alert alert-success border-success rounded-3 mt-3 text-center fs-8 shadow-sm">
              <i class="bi bi-check-circle-fill text-success ms-1"></i>
              تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني بنجاح!
            </div>

          </div>
        </div>

        <!-- ================= 2. القسم الأيسر: البانر البصري والعبارة والأيقونات الأربع ================= -->
        <div class="col-12 col-lg-6 hero-visual-col position-relative d-flex flex-column justify-content-start p-4 p-md-5 order-1 order-lg-2">
          <img
            :src="getImageUrl('login.jpeg')"
            alt="لأن الدقيقة تساوي حياة"
            class="hero-bg-image"
            @error="handleImgFallback"
          />

          <div class="hero-content position-relative z-2 text-center pt-3 mx-auto">
            <h2 class="fw-bold hero-heading text-dark mb-1 text-center">
              لأن الدقيقة تساوي <span class="text-danger">حياة</span>
            </h2>

            <div class="pulse-line-wrapper my-2 mx-auto text-center">
              <img :src="getImageUrl('Vector 7.png')" alt="نبض القلب" class="pulse-vector-img mx-auto" />
            </div>

            <p class="hero-description text-dark fw-bold mb-3 mb-md-4 text-center">
              نوصلك بالمتبرع المناسب في أسرع وقت لإنقاذ حياة محتاجة.
            </p>

            <!-- الأيقونات الأربع -->
            <div class="row g-2 justify-content-center text-center mt-3 mt-md-4">
              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img :src="getImageUrl('Frame 2147225319.png')" alt="إنقاذ الأرواح" class="feature-img" />
                </div>
                <span class="d-block feature-label text-dark fw-bold">إنقاذ الأرواح</span>
              </div>

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img :src="getImageUrl('Frame 2147225318.png')" alt="استجابة سريعة" class="feature-img" />
                </div>
                <span class="d-block feature-label text-dark fw-bold">استجابة سريعة</span>
              </div>

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img :src="getImageUrl('Frame 2147225317.png')" alt="مجتمع المتبرعين" class="feature-img" />
                </div>
                <span class="d-block feature-label text-dark fw-bold">مجتمع المتبرعين</span>
              </div>

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img :src="getImageUrl('Frame 2147225316.png')" alt="آمن وموثوق" class="feature-img" />
                </div>
                <span class="d-block feature-label text-dark fw-bold">آمن وموثوق</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuth } from '@/composables/useAuth';

const email = ref('');
const sent = ref(false);

const { sendPasswordResetEmail, loading, error } = useAuth();

const getImageUrl = (fileName) => {
  return new URL(`../../assets/images/${fileName}`, import.meta.url).href;
};

const handleReset = async () => {
  sent.value = false;
  if (sendPasswordResetEmail) {
    const result = await sendPasswordResetEmail(email.value);
    if (result.success) sent.value = true;
  } else {
    sent.value = true;
  }
};

const handleImgFallback = (e) => {
  e.target.src = getImageUrl('hero-drop.png');
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

.auth-page-wrapper {
  background-color: #f3f4f6;
}

.auth-main-card {
  max-width: 1080px;
  min-height: 600px;
}

.hero-visual-col {
  background-color: #ffffff;
  overflow: hidden;
  min-height: 280px;
}

@media (min-width: 992px) {
  .hero-visual-col {
    min-height: 600px;
  }
}

.hero-bg-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: bottom center;
  z-index: 0;
  opacity: 1 !important;
}

.hero-heading {
  font-size: 24px;
  line-height: 1.3;
}

@media (min-width: 768px) {
  .hero-heading {
    font-size: 32px;
  }
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
  font-size: 13px;
  line-height: 1.7;
}

@media (min-width: 768px) {
  .hero-description {
    font-size: 15px;
    line-height: 1.8;
  }
}

.feature-icon-box {
  width: 38px;
  height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
}

@media (min-width: 768px) {
  .feature-icon-box {
    width: 48px;
    height: 48px;
  }
}

.feature-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.feature-label {
  font-size: 10px;
}

@media (min-width: 768px) {
  .feature-label {
    font-size: 11px;
  }
}

.form-section-col {
  background-color: #f8fafc;
}

.top-auth-logo {
  height: 42px;
  width: auto;
  object-fit: contain;
}

@media (min-width: 768px) {
  .top-auth-logo {
    height: 55px;
  }
}

.auth-card-inner {
  max-width: 430px;
}

.page-title {
  font-size: 20px;
  color: #111827;
}

@media (min-width: 768px) {
  .page-title {
    font-size: 22px;
  }
}

.page-desc {
  font-size: 13px;
  color: #6b7280;
  line-height: 1.6;
}

.custom-input-group .form-control {
  border-color: #e2e8f0;
  padding: 8px 12px;
}

.custom-input-group .input-group-text {
  border-color: #e2e8f0;
}

.btn-submit-red {
  background-color: #dc2626;
  border: none;
  padding: 10px;
  font-size: 15px;
  transition: background-color 0.2s ease;
}

.btn-submit-red:hover {
  background-color: #b91c1c;
}

.hover-danger:hover {
  color: #dc2626 !important;
}

.fs-8 { font-size: 0.8rem; }
</style>
