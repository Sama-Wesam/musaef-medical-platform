<template>
  <div class="auth-page-wrapper min-vh-100 d-flex align-items-center justify-content-center dir-rtl py-3 py-md-4">
    <div class="container px-2 px-md-3">
      <div class="row g-0 auth-main-card shadow-lg rounded-5 overflow-hidden bg-white mx-auto position-relative">

        <!-- ================= 1. القسم الأيمن: النماذج والمدخلات ================= -->
        <div class="col-12 col-lg-6 form-section-col p-3 p-sm-4 p-md-5 d-flex flex-column justify-content-center position-relative bg-light-gray order-2 order-lg-1">

          <!-- شعار قطرة الدم علوياً -->
          <div class="top-logo-container position-absolute top-0 end-0 p-3 p-md-4 z-3">
            <img :src="getImageUrl('auth-logo.png')" alt="مسعف" class="top-auth-logo" @error="handleLogoFallback" />
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
                تسجيل الدخول
              </button>
              <button
                type="button"
                class="btn flex-fill py-2 fw-bold text-center border-0 bg-transparent tab-item"
                :class="activeTab === 'register' ? 'text-danger active-tab' : 'text-muted opacity-50'"
                @click="activeTab = 'register'"
              >
                إنشاء حساب
              </button>
            </div>

            <!-- تنبيه الأخطاء إن وجدت -->
            <div v-if="error || authStore.error" class="alert alert-danger rounded-3 fs-8 mb-3 text-center">
              {{ error || authStore.error }}
            </div>

            <!-- تنبيه النجاح للمستشفيات -->
            <div v-if="successMessage" class="alert alert-success rounded-3 fs-8 mb-3 text-center">
              {{ successMessage }}
            </div>

            <!-- ================= 1.1 نموذج تسجيل الدخول ================= -->
            <form v-if="activeTab === 'login'" @submit.prevent="onLogin">
              <div class="mb-3 text-end">
                <label class="form-label fs-8 text-dark fw-bold mb-1 d-block text-end">البريد الإلكتروني</label>
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted border-start-0 order-2">
                    <i class="bi bi-envelope"></i>
                  </span>
                  <input
                    v-model="loginForm.email"
                    type="email"
                    class="form-control border-end-0 text-end fs-8 order-1"
                    placeholder="البريد الإلكتروني"
                    required
                  />
                </div>
              </div>

              <div class="mb-3 text-end">
                <label class="form-label fs-8 text-dark fw-bold mb-1 d-block text-end">كلمة المرور</label>
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted border-start-0 order-2">
                    <i class="bi bi-lock"></i>
                  </span>
                  <input
                    v-model="loginForm.password"
                    type="password"
                    class="form-control border-end-0 text-end fs-8 order-1"
                    placeholder="كلمة المرور"
                    required
                  />
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-4 fs-8 flex-wrap gap-2">
                <div class="form-check d-flex align-items-center gap-2 p-0">
                  <input v-model="loginForm.remember" type="checkbox" class="form-check-input ms-0 custom-checkbox" id="rememberMe" />
                  <label class="form-check-label text-dark fw-medium ms-1" for="rememberMe">تذكرني</label>
                </div>
                <router-link to="/forgot-password" class="text-danger text-decoration-none fw-bold fs-8">هل نسيت كلمة السر؟</router-link>
              </div>

              <button type="submit" class="btn btn-danger w-100 rounded-3 py-2 fw-bold text-white shadow-sm mb-4 btn-submit-red" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
                تسجيل الدخول
              </button>

              <div class="text-center position-relative mb-4">
                <hr class="text-muted opacity-25" />
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted fs-8">أو سجل الدخول عبر</span>
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
                ليس لديك حساب؟ <a href="#" class="text-danger fw-bold text-decoration-none ms-1" @click.prevent="activeTab = 'register'">إنشاء حساب جديد</a>
              </p>
            </form>

            <!-- ================= 1.2 نموذج إنشاء حساب ================= -->
            <form v-else @submit.prevent="onRegister">

              <!-- حقول المدخلات الأساسية -->
              <div class="mb-2 text-end">
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted border-start-0 order-2"><i class="bi bi-person"></i></span>
                  <input v-model="registerForm.fullName" type="text" class="form-control border-end-0 text-end fs-8 order-1" placeholder="الاسم كامل" required />
                </div>
              </div>

              <div class="mb-2 text-end">
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted border-start-0 order-2"><i class="bi bi-envelope"></i></span>
                  <input v-model="registerForm.email" type="email" class="form-control border-end-0 text-end fs-8 order-1" placeholder="البريد الإلكتروني" required />
                </div>
              </div>

              <div class="mb-2 text-end">
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted border-start-0 order-2"><i class="bi bi-telephone"></i></span>
                  <input v-model="registerForm.phone" type="tel" class="form-control border-end-0 text-end fs-8 order-1" placeholder="رقم الهاتف" required />
                </div>
              </div>

              <div class="mb-2 text-end">
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted border-start-0 order-2"><i class="bi bi-lock"></i></span>
                  <input v-model="registerForm.password" type="password" class="form-control border-end-0 text-end fs-8 order-1" placeholder="كلمة المرور (8 أحرف على الأقل)" required />
                </div>
              </div>

              <div class="mb-3 text-end">
                <div class="input-group custom-input-group">
                  <span class="input-group-text bg-white text-muted border-start-0 order-2"><i class="bi bi-three-dots"></i></span>
                  <input v-model="registerForm.password_confirmation" type="password" class="form-control border-end-0 text-end fs-8 order-1" placeholder="تأكيد كلمة المرور" required />
                </div>
              </div>

              <!-- اختيار نوع الحساب -->
              <div class="mb-3 text-end">
                <label class="form-label fs-8 text-dark fw-bold mb-2 d-block text-end">نوع الحساب</label>
                <div class="row g-2">
                  <div class="col-6">
                    <div
                      class="account-type-card border rounded-3 p-2 d-flex align-items-center justify-content-between cursor-pointer transition-all"
                      :class="accountType === 'hospital' ? 'border-danger bg-pink-light text-danger' : 'text-muted bg-white'"
                      @click="accountType = 'hospital'"
                    >
                      <input type="radio" name="accType" :checked="accountType === 'hospital'" class="form-check-input ms-0 custom-radio" />
                      <span class="fs-8 fw-bold text-truncate">مستشفى / بنك دم</span>
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
                      <span class="fs-8 fw-bold">متبرع</span>
                      <i class="bi bi-person-fill fs-6 ms-1 flex-shrink-0"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- البيانات الإضافية كمتبرع -->
              <template v-if="accountType === 'donor'">
                <div class="p-3 bg-light rounded-3 border mb-3">
                  <h6 class="text-danger fw-bold text-center mb-3 fs-8">بيانات إضافية للمتبرع</h6>

                  <div class="mb-2 text-end">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block text-end">فصيلة الدم</label>
                    <div class="input-group custom-input-group">
                      <span class="input-group-text bg-white text-muted border-start-0 order-2"><i class="bi bi-droplet"></i></span>
                      <select v-model="registerForm.bloodType" class="form-select border-end-0 text-end fs-8 order-1" required>
                        <option value="" disabled selected>اختر فصيلة الدم</option>
                        <option v-for="type in bloodTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                      </select>
                    </div>
                  </div>

                  <div class="mb-2 text-end">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block text-end">تاريخ الميلاد</label>
                    <div class="input-group custom-input-group">
                      <span class="input-group-text bg-white text-muted border-start-0 order-2"><i class="bi bi-calendar"></i></span>
                      <input v-model="registerForm.birthDate" type="date" class="form-control border-end-0 text-end fs-8 order-1" required />
                    </div>
                  </div>

                  <div class="mb-2 text-end">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block text-end">الجنس</label>
                    <div class="input-group custom-input-group">
                      <span class="input-group-text bg-white text-muted border-start-0 order-2"><i class="bi bi-gender-ambiguous"></i></span>
                      <select v-model="registerForm.gender" class="form-select border-end-0 text-end fs-8 order-1" required>
                        <option value="" disabled selected>اختر الجنس</option>
                        <option value="male">ذكر</option>
                        <option value="female">أنثى</option>
                      </select>
                    </div>
                  </div>
                </div>
              </template>

              <!-- البيانات الإضافية كمستشفى/بنك دم -->
              <template v-if="accountType === 'hospital'">
                <div class="p-3 bg-light rounded-3 border mb-3">
                  <h6 class="text-danger fw-bold text-center mb-3 fs-8">بيانات الجهة الطبية</h6>

                  <div class="mb-2 text-end">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block text-end">اسم الجهة (اسم المستشفى أو بنك الدم)</label>
                    <div class="input-group custom-input-group">
                      <span class="input-group-text bg-white text-muted border-start-0 order-2"><i class="bi bi-building"></i></span>
                      <input v-model="registerForm.facilityName" type="text" class="form-control border-end-0 text-end fs-8 order-1" placeholder="اسم الجهة" required />
                    </div>
                  </div>

                  <div class="mb-2 text-end">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block text-end">نوع الجهة</label>
                    <div class="row g-2">
                      <div class="col-6">
                        <div class="border rounded-3 p-2 text-center fs-8 bg-white d-flex align-items-center justify-content-between">
                          <input type="radio" v-model="registerForm.facilityType" value="blood_bank" class="form-check-input ms-0 custom-radio" />
                          <span class="fw-bold">بنك دم</span>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="border rounded-3 p-2 text-center fs-8 bg-white d-flex align-items-center justify-content-between">
                          <input type="radio" v-model="registerForm.facilityType" value="hospital" class="form-check-input ms-0 custom-radio" />
                          <span class="fw-bold">مستشفى</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="mb-2 text-end">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block text-end">رقم الترخيص</label>
                    <div class="input-group custom-input-group">
                      <span class="input-group-text bg-white text-muted border-start-0 order-2"><i class="bi bi-card-heading"></i></span>
                      <input v-model="registerForm.licenseNumber" type="text" class="form-control border-end-0 text-end fs-8 order-1" placeholder="Medical License Number" required />
                    </div>
                  </div>

                  <div class="mb-2 text-end">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block text-end">اسم المسؤول</label>
                    <div class="input-group custom-input-group">
                      <span class="input-group-text bg-white text-muted border-start-0 order-2"><i class="bi bi-person"></i></span>
                      <input v-model="registerForm.managerName" type="text" class="form-control border-end-0 text-end fs-8 order-1" placeholder="الاسم الأول" required />
                    </div>
                  </div>

                  <!-- رفع نسخة من الترخيص -->
                  <div class="mb-2 text-end">
                    <label class="form-label fs-8 text-dark fw-bold mb-1 d-block text-end">رفع نسخة من الترخيص</label>
                    <div class="upload-dashed-box border border-dashed rounded-3 p-3 text-center bg-white cursor-pointer" @click="triggerFileInput">
                      <i class="bi bi-file-earmark-arrow-up fs-3 text-muted d-block mb-1"></i>
                      <small class="text-muted d-block fs-8 text-truncate">
                        {{ registerForm.licenseFile ? registerForm.licenseFile.name : 'اسحب الملف هنا او اختر ملف' }}
                      </small>
                      <small class="text-muted d-block fs-9">PDF / Image</small>
                      <input type="file" ref="fileInput" @change="handleFileUpload" class="d-none" id="licenseFile" accept=".pdf, .jpg, .jpeg, .png" />
                    </div>
                  </div>
                </div>
              </template>

              <!-- الموافقة على الشروط -->
              <div class="form-check text-end mb-3 fs-8 d-flex align-items-center justify-content-center gap-1">
                <input v-model="registerForm.terms" type="checkbox" class="form-check-input ms-0 custom-checkbox" id="termsCheck" required />
                <label class="form-check-label text-dark fw-medium" for="termsCheck">
                  أوافق على <a href="#" class="text-danger fw-bold text-decoration-none">الشروط والأحكام وسياسة الخصوصية</a>
                </label>
              </div>

              <button type="submit" class="btn btn-danger w-100 rounded-3 py-2 fw-bold text-white shadow-sm mb-2 btn-submit-red" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                {{ accountType === 'hospital' ? 'إرسال طلب التسجيل' : 'إنشاء حساب' }}
              </button>

              <p class="text-center fs-8 text-muted mt-3 mb-0">
                لديك حساب بالفعل؟ <a href="#" class="text-danger fw-bold text-decoration-none ms-1" @click.prevent="activeTab = 'login'">تسجيل الدخول</a>
              </p>
            </form>

          </div>
        </div>

        <!-- ================= 2. القسم الأيسر: البانر البصري والعبارة ================= -->
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
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();
const { login, register, loading, error } = useAuth();

// فصائل الدم المطابقة لقاعدة البيانات (مفترض أن الـ IDs من 1 إلى 8 أو تطابق الجدول)
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
  return new URL(`../../assets/images/${fileName}`, import.meta.url).href;
};

// تنفيذ تسجيل الدخول عبر الـ Composable
const onLogin = async () => {
  successMessage.value = '';
  await login(loginForm.value);
};

// تنفيذ إنشاء الحساب (متبرع أو مستشفى)
const onRegister = async () => {
  successMessage.value = '';
  const result = await register(registerForm.value, accountType.value);
  if (result.success && accountType.value === 'hospital') {
    successMessage.value = 'تم إرسال طلب تسجيل الجهة الطبية بنجاح. سيتم مراجعة بياناتكم وقبول الطلب قريباً.';
    activeTab.value = 'login';
  }
};

const handleSocialLogin = (provider) => {
  console.log('تسجيل الدخول عبر:', provider);
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
</style>
