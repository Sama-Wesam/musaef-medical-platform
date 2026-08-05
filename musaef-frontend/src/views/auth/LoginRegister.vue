<template>
  <div
    class="auth-page-wrapper min-vh-100 d-flex align-items-center justify-content-center py-3 py-md-4"
    :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'"
  >
    <div class="container px-2 px-md-3">
      <div class="row g-0 auth-main-card shadow-lg rounded-5 overflow-hidden bg-white mx-auto position-relative">

        <!-- ================= 1. قسم النماذج والمدخلات ================= -->
        <div class="col-12 col-lg-6 form-section-col p-3 p-sm-4 p-md-5 d-flex flex-column justify-content-center position-relative bg-light-gray order-2 order-lg-1">

          <!-- زر تحويل اللغة والشعار أعلى المربع -->
          <div class="top-logo-container position-absolute top-0 p-3 p-md-4 z-3 d-flex align-items-center gap-2" :class="currentLanguage === 'ar' ? 'end-0' : 'start-0'">
            <img :src="getImageUrl('auth-logo.png')" alt="مسعف" class="top-auth-logo" @error="handleLogoFallback" />

            <button class="btn btn-sm btn-light rounded-pill border fs-9 fw-bold px-2 py-1 ms-2" @click="toggleLanguage">
              <i class="bi bi-translate text-danger me-1"></i>
              <span>{{ currentLanguage === 'ar' ? 'English' : 'العربية' }}</span>
            </button>
          </div>

          <div class="auth-card-inner bg-white rounded-4 p-3 p-sm-4 shadow-sm mt-5 mt-lg-4 mx-auto w-100">

            <!-- التبويبات الرئيسية (تسجيل الدخول / إنشاء حساب) -->
            <div class="nav-tabs-header d-flex border-bottom mb-3 mb-md-4 position-relative text-center">
              <button
                type="button"
                class="btn flex-fill py-2 fw-bold text-center border-0 bg-transparent tab-item"
                :class="activeTab === 'login' ? 'text-danger active-tab' : 'text-muted opacity-50'"
                @click="activeTab = 'login'"
              >
                {{ t('loginTab') }}
              </button>
              <button
                type="button"
                class="btn flex-fill py-2 fw-bold text-center border-0 bg-transparent tab-item"
                :class="activeTab === 'register' ? 'text-danger active-tab' : 'text-muted opacity-50'"
                @click="activeTab = 'register'"
              >
                {{ t('registerTab') }}
              </button>
            </div>

            <!-- تنبيه الأخطاء إن وجدت -->
            <div v-if="error || authStore.error" class="alert alert-danger rounded-3 fs-8 mb-3 text-center">
              {{ translateBackendError(error || authStore.error) }}
            </div>

            <!-- تنبيه النجاح للمستشفيات -->
            <div v-if="successMessage" class="alert alert-success rounded-3 fs-8 mb-3 text-center">
              {{ successMessage }}
            </div>

            <!-- ================= 1.1 نموذج تسجيل الدخول ================= -->
            <form v-if="activeTab === 'login'" @submit.prevent="onLogin">
              <div class="mb-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <label class="form-label fs-8 text-dark fw-bold mb-1 d-block">{{ t('email') }}</label>
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'">
                    <i class="bi bi-envelope"></i>
                  </span>
                  <input
                    v-model="loginForm.email"
                    type="email"
                    class="form-control fs-8"
                    :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'"
                    :placeholder="t('emailPlaceholder')"
                    required
                  />
                </div>
              </div>

              <div class="mb-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <label class="form-label fs-8 text-dark fw-bold mb-1 d-block">{{ t('password') }}</label>
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'">
                    <i class="bi bi-lock"></i>
                  </span>
                  <input
                    v-model="loginForm.password"
                    type="password"
                    class="form-control fs-8"
                    :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'"
                    :placeholder="t('passwordPlaceholder')"
                    required
                  />
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-4 fs-8 flex-wrap gap-2">
                <div class="form-check d-flex align-items-center gap-2 p-0">
                  <input v-model="loginForm.remember" type="checkbox" class="form-check-input ms-0 custom-checkbox" id="rememberMe" />
                  <label class="form-check-label text-dark fw-medium ms-1" for="rememberMe">{{ t('rememberMe') }}</label>
                </div>
                <router-link to="/forgot-password" class="text-danger text-decoration-none fw-bold fs-8">{{ t('forgotPassword') }}</router-link>
              </div>

              <button type="submit" class="btn btn-danger w-100 rounded-3 py-2 fw-bold text-white shadow-sm mb-4 btn-submit-red" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
                {{ t('loginBtn') }}
              </button>

              <div class="text-center position-relative mb-4">
                <hr class="text-muted opacity-25" />
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted fs-8">{{ t('orSocial') }}</span>
              </div>

              <!-- أزرار الدخول الاجتماعي -->
              <div class="d-flex gap-2 mb-3 flex-wrap">
                <button type="button" class="btn btn-social flex-fill rounded-3 py-2 d-flex align-items-center justify-content-center gap-1.5 border" @click="handleSocialLogin('facebook')" :disabled="loading">
                  <i class="bi bi-facebook text-primary fs-6"></i>
                  <span class="fs-8 text-dark fw-medium">Facebook</span>
                </button>
                <button type="button" class="btn btn-social flex-fill rounded-3 py-2 d-flex align-items-center justify-content-center gap-1.5 border" @click="handleSocialLogin('apple')" :disabled="loading">
                  <i class="bi bi-apple text-dark fs-6"></i>
                  <span class="fs-8 text-dark fw-medium">Apple</span>
                </button>
                <button type="button" class="btn btn-social flex-fill rounded-3 py-2 d-flex align-items-center justify-content-center gap-1.5 border" @click="handleSocialLogin('google')" :disabled="loading">
                  <i class="bi bi-google text-danger fs-6"></i>
                  <span class="fs-8 text-dark fw-medium">Google</span>
                </button>
              </div>

              <p class="text-center fs-8 text-muted mt-3 mb-0">
                {{ t('noAccount') }} <a href="#" class="text-danger fw-bold text-decoration-none ms-1" @click.prevent="activeTab = 'register'">{{ t('registerNew') }}</a>
              </p>
            </form>

            <!-- ================= 1.2 نموذج إنشاء حساب ================= -->
            <form v-else @submit.prevent="onRegister">

              <!-- حقول المدخلات الأساسية -->
              <div class="mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'"><i class="bi bi-person"></i></span>
                  <input v-model="registerForm.fullName" type="text" class="form-control fs-8" :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'" :placeholder="t('fullNamePlaceholder')" required />
                </div>
              </div>

              <div class="mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'"><i class="bi bi-envelope"></i></span>
                  <input v-model="registerForm.email" type="email" class="form-control fs-8" :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'" :placeholder="t('emailPlaceholder')" required />
                </div>
              </div>

              <div class="mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'"><i class="bi bi-telephone"></i></span>
                  <input v-model="registerForm.phone" type="tel" class="form-control fs-8" :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'" :placeholder="t('phonePlaceholder')" required />
                </div>
              </div>

              <div class="mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'"><i class="bi bi-lock"></i></span>
                  <input v-model="registerForm.password" type="password" class="form-control fs-8" :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'" :placeholder="t('passwordMinPlaceholder')" required />
                </div>
              </div>

              <div class="mb-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'"><i class="bi bi-three-dots"></i></span>
                  <input v-model="registerForm.password_confirmation" type="password" class="form-control fs-8" :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'" :placeholder="t('confirmPasswordPlaceholder')" required />
                </div>
              </div>

              <!-- اختيار نوع الحساب -->
              <div class="mb-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <label class="form-label fs-8 text-dark fw-bold mb-2 d-block">{{ t('accountTypeLabel') }}</label>
                <div class="row g-2">
                  <div class="col-6">
                    <div
                      class="account-type-card border rounded-3 p-2 d-flex align-items-center justify-content-between cursor-pointer transition-all"
                      :class="accountType === 'hospital' ? 'border-danger bg-pink-light text-danger' : 'text-muted bg-white'"
                      @click="accountType = 'hospital'"
                    >
                      <input type="radio" name="accType" :checked="accountType === 'hospital'" class="form-check-input ms-0 custom-radio" />
                      <span class="fs-8 fw-bold text-truncate">{{ t('hospitalAccount') }}</span>
                      <i class="bi bi-building fs-6 ms-1 flex-shrink-0"></i>
                    </div>
                  </div>
                  <div class="col-6">
                    <div
                      class="account-type-card border rounded-3 p-2 d-flex align-items-center justify-content-between cursor-pointer transition-all"
                      :class="accountType === 'donor' ? 'border-danger bg-pink-light text-danger' : 'text-muted bg-white'"
                      @click="accountType = 'donor'"
                    >
                      <input type="radio" name="accType" :checked="accountType === 'donor'" class="form-check-input ms-0 custom-radio" />
                      <span class="fs-8 fw-bold">{{ t('donorAccount') }}</span>
                      <i class="bi bi-person-fill fs-6 ms-1 flex-shrink-0"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- البيانات الإضافية كمتبرع -->
              <template v-if="accountType === 'donor'">
                <div class="p-3 bg-light rounded-3 border mb-3">
                  <h6 class="text-danger fw-bold text-center mb-3 fs-8">{{ t('donorExtraInfoTitle') }}</h6>

                  <div class="mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block">{{ t('bloodTypeSelectLabel') }}</label>
                    <div class="input-group custom-input-group">
                      <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'"><i class="bi bi-droplet"></i></span>
                      <select v-model="registerForm.bloodType" class="form-select fs-8" :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'" required>
                        <option value="" disabled selected>{{ t('chooseBloodType') }}</option>
                        <option v-for="type in bloodTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                      </select>
                    </div>
                  </div>

                  <div class="mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block">{{ t('birthDate') }}</label>
                    <div class="input-group custom-input-group">
                      <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'"><i class="bi bi-calendar"></i></span>
                      <input v-model="registerForm.birthDate" type="date" class="form-control fs-8" :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'" required />
                    </div>
                  </div>

                  <div class="mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block">{{ t('gender') }}</label>
                    <div class="input-group custom-input-group">
                      <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'"><i class="bi bi-gender-ambiguous"></i></span>
                      <select v-model="registerForm.gender" class="form-select fs-8" :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'" required>
                        <option value="" disabled selected>{{ t('chooseGender') }}</option>
                        <option value="male">{{ t('male') }}</option>
                        <option value="female">{{ t('female') }}</option>
                      </select>
                    </div>
                  </div>
                </div>
              </template>

              <!-- البيانات الإضافية كمستشفى/بنك دم -->
              <template v-if="accountType === 'hospital'">
                <div class="p-3 bg-light rounded-3 border mb-3">
                  <h6 class="text-danger fw-bold text-center mb-3 fs-8">{{ t('facilityExtraInfoTitle') }}</h6>

                  <div class="mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block">{{ t('facilityName') }}</label>
                    <div class="input-group custom-input-group">
                      <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'"><i class="bi bi-building"></i></span>
                      <input v-model="registerForm.facilityName" type="text" class="form-control fs-8" :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'" :placeholder="t('facilityNamePlaceholder')" required />
                    </div>
                  </div>

                  <div class="mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block">{{ t('facilityType') }}</label>
                    <div class="row g-2">
                      <div class="col-6">
                        <div class="border rounded-3 p-2 text-center fs-8 bg-white d-flex align-items-center justify-content-between">
                          <input type="radio" v-model="registerForm.facilityType" value="blood_bank" class="form-check-input ms-0 custom-radio" />
                          <span class="fw-bold">{{ t('bloodBankType') }}</span>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="border rounded-3 p-2 text-center fs-8 bg-white d-flex align-items-center justify-content-between">
                          <input type="radio" v-model="registerForm.facilityType" value="hospital" class="form-check-input ms-0 custom-radio" />
                          <span class="fw-bold">{{ t('hospitalType') }}</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block">{{ t('licenseNumber') }}</label>
                    <div class="input-group custom-input-group">
                      <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'"><i class="bi bi-card-heading"></i></span>
                      <input v-model="registerForm.licenseNumber" type="text" class="form-control fs-8" :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'" placeholder="Medical License Number" required />
                    </div>
                  </div>

                  <div class="mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block">{{ t('managerName') }}</label>
                    <div class="input-group custom-input-group">
                      <span class="input-group-text bg-white text-muted" :class="currentLanguage === 'ar' ? 'border-start-0 order-2' : 'border-end-0 order-1'"><i class="bi bi-person"></i></span>
                      <input v-model="registerForm.managerName" type="text" class="form-control fs-8" :class="currentLanguage === 'ar' ? 'border-end-0 text-end order-1' : 'border-start-0 text-start order-2'" :placeholder="t('managerNamePlaceholder')" required />
                    </div>
                  </div>

                  <!-- رفع نسخة من الترخيص -->
                  <div class="mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block">{{ t('uploadLicenseLabel') }}</label>
                    <div class="upload-dashed-box border border-dashed rounded-3 p-3 text-center bg-white cursor-pointer" @click="triggerFileInput">
                      <i class="bi bi-file-earmark-arrow-up fs-3 text-muted d-block mb-1"></i>
                      <small class="text-muted d-block fs-8 text-truncate">
                        {{ registerForm.licenseFile ? registerForm.licenseFile.name : t('dragOrChooseFile') }}
                      </small>
                      <small class="text-muted d-block fs-9">PDF / Image</small>
                      <input type="file" ref="fileInput" @change="handleFileUpload" class="d-none" id="licenseFile" accept=".pdf, .jpg, .jpeg, .png" />
                    </div>
                  </div>
                </div>
              </template>

              <!-- الموافقة على الشروط -->
              <div class="form-check mb-3 fs-8 d-flex align-items-center justify-content-center gap-1" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <input v-model="registerForm.terms" type="checkbox" class="form-check-input ms-0 custom-checkbox" id="termsCheck" required />
                <label class="form-check-label text-dark fw-medium" for="termsCheck">
                  {{ t('agreeTerms') }} <a href="#" class="text-danger fw-bold text-decoration-none">{{ t('termsAndPrivacyLink') }}</a>
                </label>
              </div>

              <button type="submit" class="btn btn-danger w-100 rounded-3 py-2 fw-bold text-white shadow-sm mb-2 btn-submit-red" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                {{ accountType === 'hospital' ? t('submitHospitalRegister') : t('createAccountBtn') }}
              </button>

              <p class="text-center fs-8 text-muted mt-3 mb-0">
                {{ t('alreadyHaveAccount') }} <a href="#" class="text-danger fw-bold text-decoration-none ms-1" @click.prevent="activeTab = 'login'">{{ t('loginTab') }}</a>
              </p>
            </form>

          </div>
        </div>

        <!-- ================= 2. القسم الأيسر: البانر البصري والعبارة والأيقونات الأربع ================= -->
        <div class="col-12 col-lg-6 hero-visual-col position-relative d-flex flex-column justify-content-start p-4 p-md-5 order-1 order-lg-2">
          <!-- صورة الخلفية تسحب من مجلد images -->
          <img
            :src="getImageUrl('login.jpeg')"
            alt="لأن الدقيقة تساوي حياة"
            class="hero-bg-image"
            @error="handleImgFallback"
          />

          <div class="hero-content position-relative z-2 text-center pt-3 mx-auto">
            <h2 class="fw-bold hero-heading text-dark mb-1 text-center">
              {{ t('heroHeadingPart1') }} <span class="text-danger">{{ t('heroHeadingPart2') }}</span>
            </h2>

            <!-- رسمة نبض القلب -->
            <div class="pulse-line-wrapper my-2 mx-auto text-center">
              <img :src="getIconUrl('Vector 7.png')" alt="نبض القلب" class="pulse-vector-img mx-auto" />
            </div>

            <p class="hero-description text-dark fw-bold mb-3 mb-md-4 text-center">
              {{ t('heroDesc') }}
            </p>

            <!-- الأيقونات الأربع تسحب بشكل صحيح من مجلد icons -->
            <div class="row g-2 justify-content-center text-center mt-3 mt-md-4">
              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img :src="getIconUrl('Frame 2147225319.png')" alt="إنقاذ الأرواح" class="feature-img" />
                </div>
                <span class="d-block feature-label text-dark fw-bold">{{ t('featureSavingLives') }}</span>
              </div>

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img :src="getIconUrl('Frame 2147225318.png')" alt="استجابة سريعة" class="feature-img" />
                </div>
                <span class="d-block feature-label text-dark fw-bold">{{ t('featureFastResponse') }}</span>
              </div>

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img :src="getIconUrl('Frame 2147225317.png')" alt="مجتمع المتبرعين" class="feature-img" />
                </div>
                <span class="d-block feature-label text-dark fw-bold">{{ t('featureCommunity') }}</span>
              </div>

              <div class="col-3">
                <div class="feature-icon-box mx-auto mb-2">
                  <img :src="getIconUrl('Frame 2147225316.png')" alt="آمن وموثوق" class="feature-img" />
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
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();
const { login, register, loading, error } = useAuth();

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
    loginTab: 'تسجيل الدخول',
    registerTab: 'إنشاء حساب',
    email: 'البريد الإلكتروني',
    emailPlaceholder: 'البريد الإلكتروني',
    password: 'كلمة المرور',
    passwordPlaceholder: 'كلمة المرور',
    rememberMe: 'تذكرني',
    forgotPassword: 'هل نسيت كلمة السر؟',
    loginBtn: 'تسجيل الدخول',
    orSocial: 'أو سجل الدخول عبر',
    noAccount: 'ليس لديك حساب؟',
    registerNew: 'إنشاء حساب جديد',
    fullNamePlaceholder: 'الاسم كامل',
    phonePlaceholder: 'رقم الهاتف',
    passwordMinPlaceholder: 'كلمة المرور (8 أحرف على الأقل)',
    confirmPasswordPlaceholder: 'تأكيد كلمة المرور',
    accountTypeLabel: 'نوع الحساب',
    hospitalAccount: 'مستشفى / بنك دم',
    donorAccount: 'متبرع',
    donorExtraInfoTitle: 'بيانات إضافية للمتبرع',
    bloodTypeSelectLabel: 'فصيلة الدم',
    chooseBloodType: 'اختر فصيلة الدم',
    birthDate: 'تاريخ الميلاد',
    gender: 'الجنس',
    chooseGender: 'اختر الجنس',
    male: 'ذكر',
    female: 'أنثى',
    facilityExtraInfoTitle: 'بيانات الجهة الطبية',
    facilityName: 'اسم الجهة (اسم المستشفى أو بنك الدم)',
    facilityNamePlaceholder: 'اسم الجهة',
    facilityType: 'نوع الجهة',
    bloodBankType: 'بنك دم',
    hospitalType: 'مستشفى',
    licenseNumber: 'رقم الترخيص',
    managerName: 'اسم المسؤول',
    managerNamePlaceholder: 'الاسم الأول',
    uploadLicenseLabel: 'رفع نسخة من الترخيص',
    dragOrChooseFile: 'اسحب الملف هنا أو اختر ملف',
    agreeTerms: 'أوافق على',
    termsAndPrivacyLink: 'الشروط والأحكام وسياسة الخصوصية',
    submitHospitalRegister: 'إرسال طلب التسجيل',
    createAccountBtn: 'إنشاء حساب',
    alreadyHaveAccount: 'لديك حساب بالفعل؟',
    heroHeadingPart1: 'لأن الدقيقة تساوي',
    heroHeadingPart2: 'حياة',
    heroDesc: 'نوصلك بالمتبرع المناسب في أسرع وقت لإنقاذ حياة محتاجة.',
    featureSavingLives: 'إنقاذ الأرواح',
    featureFastResponse: 'استجابة سريعة',
    featureCommunity: 'مجتمع المتبرعين',
    featureSafe: 'آمن وموثوق',
    hospitalSuccessMsg: 'تم إرسال طلب تسجيل الجهة الطبية بنجاح. سيتم مراجعته والتواصل معكم قريباً.'
  },
  en: {
    loginTab: 'Login',
    registerTab: 'Register',
    email: 'Email Address',
    emailPlaceholder: 'Enter your email',
    password: 'Password',
    passwordPlaceholder: 'Enter password',
    rememberMe: 'Remember Me',
    forgotPassword: 'Forgot password?',
    loginBtn: 'Sign In',
    orSocial: 'Or sign in with',
    noAccount: 'Don\'t have an account?',
    registerNew: 'Create New Account',
    fullNamePlaceholder: 'Full Name',
    phonePlaceholder: 'Phone Number',
    passwordMinPlaceholder: 'Password (min 8 chars)',
    confirmPasswordPlaceholder: 'Confirm Password',
    accountTypeLabel: 'Account Type',
    hospitalAccount: 'Hospital / Blood Bank',
    donorAccount: 'Donor',
    donorExtraInfoTitle: 'Additional Donor Info',
    bloodTypeSelectLabel: 'Blood Type',
    chooseBloodType: 'Select blood type',
    birthDate: 'Birth Date',
    gender: 'Gender',
    chooseGender: 'Select gender',
    male: 'Male',
    female: 'Female',
    facilityExtraInfoTitle: 'Medical Facility Info',
    facilityName: 'Facility Name (Hospital / Blood Bank)',
    facilityNamePlaceholder: 'Facility name',
    facilityType: 'Facility Type',
    bloodBankType: 'Blood Bank',
    hospitalType: 'Hospital',
    licenseNumber: 'License Number',
    managerName: 'Manager Name',
    managerNamePlaceholder: 'First Name',
    uploadLicenseLabel: 'Upload License Copy',
    dragOrChooseFile: 'Drag file here or choose file',
    agreeTerms: 'I agree to',
    termsAndPrivacyLink: 'Terms, Conditions & Privacy Policy',
    submitHospitalRegister: 'Submit Application',
    createAccountBtn: 'Create Account',
    alreadyHaveAccount: 'Already have an account?',
    heroHeadingPart1: 'Because every minute equals',
    heroHeadingPart2: 'Life',
    heroDesc: 'Connecting you with the right donor quickly to save lives in need.',
    featureSavingLives: 'Saving Lives',
    featureFastResponse: 'Fast Response',
    featureCommunity: 'Donor Community',
    featureSafe: 'Safe & Trusted',
    hospitalSuccessMsg: 'Medical facility registration request submitted successfully. We will review and contact you soon.'
  }
};

const backendErrorDict = {
  'يرجى إدخال بريد إلكتروني صحيح': 'Please enter a valid email address',
  'كلمة المرور يجب ألا تقل عن 6 أحرف': 'Password must be at least 6 characters',
  'كلمة المرور يجب ألا تقل عن 8 أحرف': 'Password must be at least 8 characters',
  'كلمتا المرور غير متطابقتين': 'Passwords do not match',
  'يجب الموافقة على الشروط والأحكام وسياسة الخصوصية': 'You must agree to Terms and Privacy Policy',
  'بيانات الدخول غير صحيحة': 'Invalid login credentials',
  'فشل تسجيل الدخول': 'Login failed',
  'تعذر إنشاء الحساب حالياً': 'Unable to create account currently'
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const translateBackendError = (err) => {
  if (!err) return '';
  if (currentLanguage.value === 'en') {
    return backendErrorDict[err] || err;
  }
  return err;
};

const bloodTypes = ref([
  { id: 1, name: 'A+' },
  { id: 2, name: 'A-' },
  { id: 3, name: 'B+' },
  { id: 4, name: 'B-' },
  { id: 5, name: 'AB+' },
  { id: 6, name: 'AB-' },
  { id: 7, name: 'O+' },
  { id: 8, name: 'O-' }
]);

const activeTab = ref('login');
const accountType = ref('donor');
const fileInput = ref(null);
const successMessage = ref('');

const loginForm = ref({
  email: '',
  password: '',
  remember: false
});

const registerForm = ref({
  fullName: '',
  email: '',
  phone: '',
  bloodType: '',
  gender: '',
  birthDate: '',
  password: '',
  password_confirmation: '',
  facilityName: '',
  facilityType: 'hospital',
  licenseNumber: '',
  managerName: '',
  licenseFile: null,
  terms: false
});

const getImageUrl = (fileName) => {
  return new URL(`/src/assets/images/${fileName}`, import.meta.url).href;
};

const getIconUrl = (fileName) => {
  return new URL(`/src/assets/icons/${fileName}`, import.meta.url).href;
};

const onLogin = async () => {
  successMessage.value = '';
  await login(loginForm.value);
};

const onRegister = async () => {
  successMessage.value = '';
  const result = await register(registerForm.value, accountType.value);
  if (result.success && accountType.value === 'hospital') {
    successMessage.value = t('hospitalSuccessMsg');
    activeTab.value = 'login';
  }
};

const handleSocialLogin = (provider) => {
  console.log('Login via:', provider);
};

const triggerFileInput = () => {
  if (fileInput.value) fileInput.value.click();
};

const handleFileUpload = (e) => {
  const file = e.target.files[0];
  if (file) {
    registerForm.value.licenseFile = file;
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
.tab-item {
  font-size: 15px;
  position: relative;
  transition: all 0.2s ease;
}
@media (min-width: 768px) {
  .tab-item {
    font-size: 16px;
  }
}
.active-tab {
  color: #dc2626 !important;
  font-weight: 700;
}
.active-tab::after {
  content: '';
  position: absolute;
  bottom: -1px;
  left: 0;
  width: 100%;
  height: 3px;
  background-color: #dc2626;
  border-radius: 3px 3px 0 0;
}
.custom-input-group .form-control,
.custom-input-group .form-select {
  border-color: #e2e8f0;
  padding: 8px 12px;
}
.custom-input-group .input-group-text {
  border-color: #e2e8f0;
}
.custom-checkbox,
.custom-radio {
  accent-color: #dc2626;
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
.btn-social {
  background-color: #ffffff;
  border-color: #e2e8f0 !important;
  transition: all 0.2s ease;
  cursor: pointer;
  padding: 6px 8px;
}
.btn-social:hover {
  background-color: #f8fafc;
  border-color: #cbd5e1 !important;
  transform: translateY(-1px);
}
.bg-pink-light {
  background-color: #fdecec !important;
}
.border-dashed {
  border-style: dashed !important;
  border-color: #cbd5e1 !important;
}
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.cursor-pointer { cursor: pointer; }
.transition-all { transition: all 0.2s ease-in-out; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
