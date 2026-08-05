<template>
  <header class="header-wrapper" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <!-- 1. الشريط الأحمر العلوي -->
    <div class="top-announcement-bar bg-danger text-white text-center py-2 px-3 fw-bold">
      <span class="announcement-text">{{ t('announcement') }}</span>
    </div>

    <!-- 2. الناف بار الرئيسي -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-2 py-lg-3">
      <div class="container d-flex align-items-center justify-content-between">

        <!-- الشعار -->
        <router-link to="/" class="navbar-brand me-0 p-0" :class="currentLanguage === 'ar' ? 'ms-lg-4' : 'me-lg-4'">
          <img src="../../assets/images/logo.png" alt="Musaef" class="main-logo-img" />
        </router-link>

        <button class="navbar-toggler border-0 shadow-none p-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMusaef">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse w-100" id="navbarMusaef">

          <ul class="navbar-nav mx-auto mb-3 mb-lg-0 align-items-lg-center gap-2 gap-lg-3 pt-3 pt-lg-0">

            <!-- 1. الرئيسية -->
            <li class="nav-item">
              <router-link class="nav-link" to="/" active-class="active-link" exact>
                {{ t('home') }}
              </router-link>
            </li>

            <!-- 2. من نحن -->
            <li class="nav-item dropdown hover-dropdown position-relative">
              <div class="d-flex align-items-center justify-content-between justify-content-lg-start">
                <router-link class="nav-link" to="/about" active-class="active-link">
                  {{ t('about') }}
                </router-link>
                <span class="dropdown-caret-icon ms-1 text-muted">
                  <i class="bi bi-chevron-down fs-7"></i>
                </span>
              </div>
              <ul class="dropdown-menu shadow border-0" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <li>
                  <a class="dropdown-item" href="/about#features" @click.prevent="navigateToSection('/about', '#features')">
                    {{ t('features') }}
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="/about#reviews" @click.prevent="navigateToSection('/about', '#reviews')">
                    {{ t('reviews') }}
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="/about#partners" @click.prevent="navigateToSection('/about', '#partners')">
                    {{ t('partners') }}
                  </a>
                </li>
              </ul>
            </li>

            <!-- 3. دليل التبرع -->
            <li class="nav-item dropdown hover-dropdown position-relative">
              <div class="d-flex align-items-center justify-content-between justify-content-lg-start">
                <router-link class="nav-link" to="/blood-guide" active-class="active-link">
                  {{ t('bloodGuide') }}
                </router-link>
                <span class="dropdown-caret-icon ms-1 text-muted">
                  <i class="bi bi-chevron-down fs-7"></i>
                </span>
              </div>
              <ul class="dropdown-menu shadow border-0" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <li>
                  <a class="dropdown-item" href="/blood-guide#medical-tips" @click.prevent="navigateToSection('/blood-guide', '#medical-tips')">
                    {{ t('medicalTips') }}
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="/blood-guide#faq" @click.prevent="navigateToSection('/blood-guide', '#faq')">
                    {{ t('faq') }}
                  </a>
                </li>
              </ul>
            </li>

          </ul>

          <!-- أزرار العمليات وزر تبديل اللغة المضاف تماماً على يمين زر تسجيل الدخول -->
          <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap pt-2 pt-lg-0 border-top border-top-lg-0">

            <!-- زر تبديل اللغة -->
            <button
              @click="toggleLanguage"
              class="btn btn-light btn-sm rounded-3 border d-flex align-items-center gap-1 fs-8 px-3 nav-btn text-dark fw-bold"
              title="Change Language"
            >
              <i class="bi bi-translate text-danger fs-6"></i>
              <span>{{ currentLanguage === 'ar' ? 'English' : 'العربية' }}</span>
            </button>

            <!-- تسجيل الدخول -->
            <router-link to="/login" class="btn btn-outline-danger px-3 nav-btn flex-fill flex-lg-grow-0 d-flex align-items-center justify-content-center">
              {{ t('login') }}
            </router-link>

            <!-- إنشاء حساب -->
            <router-link to="/register" class="btn btn-outline-danger px-3 nav-btn flex-fill flex-lg-grow-0 d-flex align-items-center justify-content-center">
              {{ t('register') }}
            </router-link>

            <!-- تبرع الآن -->
            <router-link to="/register" class="btn btn-danger px-3 nav-btn flex-fill flex-lg-grow-0 d-flex align-items-center justify-content-center gap-2">
              <span>{{ t('donateNow') }}</span>
              <i class="bi bi-heart-fill"></i>
            </router-link>
          </div>

        </div>

      </div>
    </nav>
  </header>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();

const currentLanguage = ref(localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: {
    announcement: 'تم إنقاذ 3,580 مريضاً بفضل الله ثم المتبرعين بالدم.',
    home: 'الرئيسية',
    about: 'من نحن',
    features: 'مميزات المنصة',
    reviews: 'التقييمات والآراء',
    partners: 'بالتعاون مع',
    bloodGuide: 'دليل التبرع',
    medicalTips: 'الإرشادات الطبية',
    faq: 'الأسئلة الشائعة',
    login: 'تسجيل الدخول',
    register: 'إنشاء حساب',
    donateNow: 'تبرع الآن'
  },
  en: {
    announcement: '3,580 patients have been saved thanks to donors.',
    home: 'Home',
    about: 'About Us',
    features: 'Features',
    reviews: 'Reviews',
    partners: 'In Collaboration With',
    bloodGuide: 'Donation Guide',
    medicalTips: 'Medical Tips',
    faq: 'FAQ',
    login: 'Login',
    register: 'Register',
    donateNow: 'Donate Now'
  }
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return dictionary[lang][key] || key;
};

const toggleLanguage = () => {
  const targetLang = currentLanguage.value === 'ar' ? 'en' : 'ar';
  currentLanguage.value = targetLang;
  localStorage.setItem('musaef_lang', targetLang);
  document.documentElement.setAttribute('dir', targetLang === 'ar' ? 'rtl' : 'ltr');
  document.documentElement.setAttribute('lang', targetLang);
  window.location.reload();
};

const navigateToSection = async (path, hash) => {
  if (route.path === path) {
    const element = document.querySelector(hash);
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
  } else {
    await router.push({ path, hash });
  }
};
</script>

<style scoped>
.header-wrapper,
.header-wrapper * {
  font-family: Arial, sans-serif !important;
}

.dir-rtl { direction: rtl; }
.top-announcement-bar { background-color: #dc2626 !important; }

.announcement-text { font-size: 15px; letter-spacing: 0.2px; }
@media (min-width: 768px) { .announcement-text { font-size: 18px; } }

.navbar { min-height: 70px; background: #fff; }
.main-logo-img { height: 55px; width: auto; object-fit: contain; }
@media (min-width: 992px) { .main-logo-img { height: 75px; } }

.nav-link {
  color: #1b1b1b !important;
  font-weight: 700;
  font-size: 16px;
  padding: 6px 0 !important;
  transition: color 0.3s ease;
  text-decoration: none;
  cursor: pointer;
}

@media (min-width: 992px) {
  .nav-link { font-size: 18px; padding: 0 5px !important; }
}

.nav-link:hover, .active-link { color: #dc2626 !important; }
.dropdown-caret-icon { font-size: 12px; transition: transform 0.2s ease, color 0.2s ease; }
.hover-dropdown:hover .dropdown-caret-icon { color: #dc2626 !important; transform: rotate(180deg); }

@media (min-width: 992px) {
  .hover-dropdown:hover .dropdown-menu { display: block; margin-top: 0; }
}

.dropdown-menu { border-radius: 12px; z-index: 1050; }
.dropdown-item { padding: 8px 16px; font-weight: 500; color: #1b1b1b; text-decoration: none; display: block; cursor: pointer; font-size: 14px; }
.dropdown-item:hover { background: #f8f8f8; color: #dc2626; }

.nav-btn {
  height: 40px;
  min-width: 105px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 14px;
  white-space: nowrap;
}

@media (min-width: 992px) {
  .nav-btn { height: 42px; min-width: 115px; font-size: 15px; }
}

.btn-danger { background: #dc2626; border-color: #dc2626; color: white; }
.btn-danger:hover { background: #c71c1c; border-color: #c71c1c; }
.btn-outline-danger { color: #dc2626; border: 1.5px solid #dc2626; }
.btn-outline-danger:hover { background: #dc2626; color: white; }

.fs-7 { font-size: 0.88rem; }
.fs-8 { font-size: 0.82rem; }

@media (max-width: 991.98px) {
  .border-top-lg-0 { border-top: 1px solid #f1f5f9; }
}
</style>
