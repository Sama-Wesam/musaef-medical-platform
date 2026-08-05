<template>
  <div
    class="auth-page-wrapper min-vh-100 d-flex align-items-center justify-content-center py-3 py-md-4"
    :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'"
  >
    <div class="container px-2 px-md-3">
      <div class="row g-0 auth-main-card shadow-lg rounded-5 overflow-hidden bg-white mx-auto position-relative">

        <!-- ================= 1. القسم الأيمن: نموذج استعادة كلمة المرور ================= -->
        <div class="col-12 col-lg-6 form-section-col p-3 p-sm-4 p-md-5 d-flex flex-column justify-content-center position-relative bg-light-gray order-2 order-lg-1">

          <!-- شعار قطرة الدم علوياً مع زر تبديل اللغة -->
          <div class="top-logo-container position-absolute top-0 p-3 p-md-4 z-3 d-flex align-items-center gap-2" :class="currentLanguage === 'ar' ? 'end-0' : 'start-0'">
            <img :src="authLogoImg" alt="مسعف" class="top-auth-logo" />

            <button class="btn btn-sm btn-light rounded-pill border fs-9 fw-bold px-2 py-1 ms-2" @click="toggleLanguage">
              <i class="bi bi-translate text-danger me-1"></i>
              <span>{{ currentLanguage === 'ar' ? 'English' : 'العربية' }}</span>
            </button>
          </div>

          <div class="auth-card-inner bg-white rounded-4 p-3 p-sm-4 shadow-sm mt-5 mt-lg-4 mx-auto w-100">

            <!-- العنوان الوصفي -->
            <div class="mb-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <h4 class="fw-bold text-dark mb-2 page-title">{{ t('resetTitle') }}</h4>
              <p class="text-secondary fs-8 page-desc mb-0">
                {{ t('resetDesc') }}
              </p>
            </div>

            <!-- رسالة الخطأ إن وجدت -->
            <div v-if="error" class="alert alert-danger rounded-3 fs-8 mb-3 text-center">
              {{ error }}
            </div>

            <!-- النموذج -->
            <form @submit.prevent="handleReset">
              <div class="mb-4" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <label class="form-label fs-8 text-dark fw-bold mb-1 d-block">{{ t('email') }}</label>
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'">
                    <i class="bi bi-envelope"></i>
                  </span>
                  <input
                    v-model="email"
                    type="email"
                    class="form-control fs-8"
                    :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'"
                    :placeholder="t('emailPlaceholder')"
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
                {{ t('sendResetLinkBtn') }}
              </button>

              <!-- رابط العودة -->
              <div class="text-center mt-3">
                <router-link to="/login" class="text-secondary fs-8 text-decoration-none hover-danger">
                  {{ t('backToLogin') }}
                </router-link>
              </div>
            </form>

            <!-- رسالة إشعار النجاح -->
            <div v-if="sent" class="alert alert-success border-success rounded-3 mt-3 text-center fs-8 shadow-sm">
              <i class="bi bi-check-circle-fill text-success ms-1"></i>
              {{ t('resetSuccessMsg') }}
            </div>

          </div>
        </div>

        <!-- ================= 2. القسم الأيسر: البانر البصري والعبارة والأيقونات الأربع ================= -->
        <div class="col-12 col-lg-6 hero-visual-col position-relative d-flex flex-column justify-content-start p-4 p-md-5 order-1 order-lg-2">
          <!-- صورة الخلفية الرئيسية -->
          <img
            :src="heroBgImg"
            alt="لأن الدقيقة تساوي حياة"
            class="hero-bg-image"
          />

          <div class="hero-content position-relative z-2 text-center pt-3 mx-auto">
            <h2 class="fw-bold hero-heading text-dark mb-1 text-center">
              {{ t('heroHeadingPart1') }} <span class="text-danger">{{ t('heroHeadingPart2') }}</span>
            </h2>

            <!-- صورة خط النبض -->
            <div class="pulse-line-wrapper my-2 mx-auto text-center">
              <img :src="vectorPulseImg" alt="نبض القلب" class="pulse-vector-img mx-auto" />
            </div>

            <p class="hero-description text-dark fw-bold mb-3 mb-md-4 text-center">
              {{ t('heroDesc') }}
            </p>

            <!-- الأيقونات الأربع -->
            <div class="row g-2 justify-content-center text-center mt-3 mt-md-4">
              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img :src="iconLivesImg" alt="إنقاذ الأرواح" class="feature-img" />
                </div>
                <span class="d-block feature-label text-dark fw-bold">{{ t('featureSavingLives') }}</span>
              </div>

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img :src="iconResponseImg" alt="استجابة سريعة" class="feature-img" />
                </div>
                <span class="d-block feature-label text-dark fw-bold">{{ t('featureFastResponse') }}</span>
              </div>

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img :src="iconCommunityImg" alt="مجتمع المتبرعين" class="feature-img" />
                </div>
                <span class="d-block feature-label text-dark fw-bold">{{ t('featureCommunity') }}</span>
              </div>

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img :src="iconSafeImg" alt="آمن وموثوق" class="feature-img" />
                </div>
                <span class="d-block feature-label text-dark fw-bold">{{ t('featureSafe') }}</span>
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

import heroBgImg from '@/assets/images/login.jpeg';
import authLogoImg from '@/assets/images/auth-logo.png';

import vectorPulseImg from '@/assets/icons/Vector 7.png';
import iconLivesImg from '@/assets/icons/Frame 2147225319.png';
import iconResponseImg from '@/assets/icons/Frame 2147225318.png';
import iconCommunityImg from '@/assets/icons/Frame 2147225317.png';
import iconSafeImg from '@/assets/icons/Frame 2147225316.png';

const email = ref('');
const sent = ref(false);

const { sendPasswordResetEmail, loading, error } = useAuth();

const currentLanguage = ref(localStorage.getItem('musaef_lang') || 'ar');

const toggleLanguage = () => {
  const newLang = currentLanguage.value === 'ar' ? 'en' : 'ar';
  currentLanguage.value = newLang;
  localStorage.setItem('musaef_lang', newLang);
  document.documentElement.setAttribute('dir', newLang === 'ar' ? 'rtl' : 'ltr');
  document.documentElement.setAttribute('lang', newLang);
};

const translations = {
  ar: {
    resetTitle: 'استعادة كلمة المرور',
    resetDesc: 'أدخل بريدك الإلكتروني، وسنرسل لك رابط استعادة كلمة المرور.',
    email: 'البريد الإلكتروني',
    emailPlaceholder: 'أدخل بريدك الإلكتروني',
    sendResetLinkBtn: 'إرسال رابط الاستعادة',
    backToLogin: 'العودة لصفحة تسجيل الدخول >',
    resetSuccessMsg: 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني بنجاح!',
    heroHeadingPart1: 'لأن الدقيقة تساوي',
    heroHeadingPart2: 'حياة',
    heroDesc: 'نوصلك بالمتبرع المناسب في أسرع وقت لإنقاذ حياة محتاجة.',
    featureSavingLives: 'إنقاذ الأرواح',
    featureFastResponse: 'استجابة سريعة',
    featureCommunity: 'مجتمع المتبرعين',
    featureSafe: 'آمن وموثوق'
  },
  en: {
    resetTitle: 'Password Recovery',
    resetDesc: 'Enter your email address and we will send you a password reset link.',
    email: 'Email Address',
    emailPlaceholder: 'Enter your email',
    sendResetLinkBtn: 'Send Recovery Link',
    backToLogin: 'Back to Login >',
    resetSuccessMsg: 'Password reset link has been sent to your email successfully!',
    heroHeadingPart1: 'Because every minute equals',
    heroHeadingPart2: 'Life',
    heroDesc: 'Connecting you with the right donor quickly to save lives in need.',
    featureSavingLives: 'Saving Lives',
    featureFastResponse: 'Fast Response',
    featureCommunity: 'Donor Community',
    featureSafe: 'Safe & Trusted'
  }
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const handleReset = async () => {
  sent.value = false;
  if (sendPasswordResetEmail) {
    const result = await sendPasswordResetEmail(email.value);
    if (result && result.success) {
      sent.value = true;
    }
  } else {
    sent.value = true;
  }
};
</script>

<style scoped>
.auth-page-wrapper,
.auth-page-wrapper * {
  font-family: Arial, sans-serif !important;
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
.fs-9 { font-size: 0.72rem; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
