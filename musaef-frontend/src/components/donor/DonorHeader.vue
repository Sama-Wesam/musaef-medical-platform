<template>
  <header class="top-navbar bg-white py-2 py-md-3 px-3 px-md-4 shadow-sm border-bottom mb-3 mb-md-4" dir="rtl">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2 gap-md-3">

      <!-- جهة اليمين: صورة المتبرع واسمه والقائمة المنسدلة -->
      <div class="dropdown order-1">
        <div
          class="d-flex align-items-center gap-2 gap-sm-3 cursor-pointer dropdown-toggle no-chevron"
          id="donorUserDropdown"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <img
            :src="userAvatar"
            :alt="userName"
            class="rounded-circle donor-avatar-img border shadow-2xs"
            @error="handleAvatarFallback"
          />
          <div class="text-start d-none d-sm-block">
            <!-- عرض اسم المتبرع الديناميكي -->
            <h6 class="fw-bold text-dark mb-0 fs-7 text-truncate" style="max-width: 140px;">{{ userName }}</h6>
            <small class="text-success fs-9 d-flex align-items-center gap-1">
              <span class="active-dot"></span> متبرع نشط <i class="bi bi-chevron-down text-muted"></i>
            </small>
          </div>
        </div>

        <!-- القائمة المنسدلة للمستخدم -->
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 py-2 mt-2 fs-8 text-end" aria-labelledby="donorUserDropdown">
          <li>
            <router-link to="/donor/dashboard" class="dropdown-item py-2 px-3 d-flex align-items-center justify-content-start gap-2">
              <i class="bi bi-speedometer2 text-danger fs-6"></i>
              <span class="fw-bold text-dark">لوحة التحكم</span>
            </router-link>
          </li>
          <li>
            <router-link to="/donor/donation-center" class="dropdown-item py-2 px-3 d-flex align-items-center justify-content-start gap-2">
              <i class="bi bi-geo-alt text-danger fs-6"></i>
              <span class="fw-bold text-dark">مركز التبرع</span>
            </router-link>
          </li>
          <li>
            <router-link to="/donor/profile" class="dropdown-item py-2 px-3 d-flex align-items-center justify-content-start gap-2">
              <i class="bi bi-person text-danger fs-6"></i>
              <span class="fw-bold text-dark">حسابي</span>
            </router-link>
          </li>
          <li>
            <router-link to="/donor/achievements" class="dropdown-item py-2 px-3 d-flex align-items-center justify-content-start gap-2">
              <i class="bi bi-trophy text-danger fs-6"></i>
              <span class="fw-bold text-dark">إنجازاتي</span>
            </router-link>
          </li>
          <li><hr class="dropdown-divider opacity-25"></li>
          <li>
            <button @click="handleLogout" class="dropdown-item py-2 px-3 text-danger fw-bold d-flex align-items-center justify-content-start gap-2 border-0 bg-transparent w-100">
              <i class="bi bi-box-arrow-right fs-6"></i>
              <span>تسجيل الخروج</span>
            </button>
          </li>
        </ul>
      </div>

      <!-- الجزء الأوسط: حقل البحث، الإشعارات، ومحول اللغة -->
      <div class="d-flex align-items-center gap-2 gap-md-3 flex-grow-1 justify-content-center max-w-600 order-3 order-md-2 w-100 w-md-auto mt-2 mt-md-0">
        <div class="search-input-wrapper position-relative flex-grow-1">
          <input
            type="text"
            class="form-control form-control-sm pe-4 ps-5 rounded-3 bg-light border-0 py-2 fs-8 text-end"
            placeholder="ابحث عن مريض..."
          />
          <i class="bi bi-search position-absolute top-50 translate-middle-y start-0 ms-3 text-muted fs-8"></i>
        </div>

        <!-- زر الإشعارات والقائمة المنسدلة -->
        <div class="dropdown position-relative flex-shrink-0">
          <button
            class="btn btn-light rounded-circle p-2 border-0 bg-transparent text-muted position-relative"
            type="button"
            id="notificationsDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            <i class="bi bi-bell fs-5"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger border border-light notification-badge">
              2
            </span>
          </button>

          <!-- القائمة المنسدلة للإشعارات -->
          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 p-3 mt-2 fs-8 text-end notifications-dropdown-menu" aria-labelledby="notificationsDropdown">
            <li class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
              <span class="fw-bold text-dark">الإشعارات</span>
              <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5 fs-9">2 جديدة</span>
            </li>

            <li class="py-2 border-bottom cursor-pointer">
              <div class="fw-bold text-danger fs-9 mb-1">مستشفى الشفاء - O+</div>
              <div class="text-dark fs-9 text-truncate" style="max-width: 220px;">مطلوب تبرع بالدم بشكل عاجل.</div>
              <small class="text-muted fs-9">منذ 10 دقائق</small>
            </li>

            <li class="py-2 cursor-pointer">
              <div class="fw-bold text-dark fs-9 mb-1">تذكير التبرع</div>
              <div class="text-secondary fs-9 text-truncate" style="max-width: 220px;">اقترب موعد جاهزيتك للتبرع.</div>
              <small class="text-muted fs-9">منذ ساعتين</small>
            </li>

            <li class="pt-2 text-center border-top mt-2">
              <router-link to="/donor/notifications" class="text-danger fw-bold fs-9 text-decoration-none d-block">
                عرض جميع الإشعارات &gt;
              </router-link>
            </li>
          </ul>
        </div>

        <!-- محول اللغة -->
        <div class="dropdown flex-shrink-0">
          <button class="btn btn-light btn-sm rounded-3 border d-flex align-items-center gap-1 fs-8 px-2 px-sm-3 py-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-translate text-muted"></i>
            <span class="fw-bold ms-1 d-none d-sm-inline">{{ currentLanguage === 'ar' ? 'العربية' : 'English' }}</span>
            <span class="fw-bold ms-1 d-inline d-sm-none">{{ currentLanguage === 'ar' ? 'AR' : 'EN' }}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 fs-8">
            <li>
              <button class="dropdown-item d-flex align-items-center justify-content-between py-2" @click="currentLanguage = 'ar'">
                <span>العربية</span>
                <i v-if="currentLanguage === 'ar'" class="bi bi-check2 text-danger fw-bold"></i>
              </button>
            </li>
            <li>
              <button class="dropdown-item d-flex align-items-center justify-content-between py-2" @click="currentLanguage = 'en'">
                <span>English</span>
                <i v-if="currentLanguage === 'en'" class="bi bi-check2 text-danger fw-bold"></i>
              </button>
            </li>
          </ul>
        </div>
      </div>

      <!-- جهة اليسار: الشعار الرئيسي "مسعف" -->
      <div class="d-flex align-items-center order-2 order-md-3">
        <router-link to="/donor/dashboard" class="d-flex align-items-center text-decoration-none">
          <img :src="getImageUrl('logo.png')" alt="مسعف" class="navbar-logo-img-large" @error="handleLogoFallback" />
        </router-link>
      </div>

    </div>
  </header>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';

const router = useRouter();
const authStore = useAuthStore();
const currentLanguage = ref('ar');

// جلب الاسم والصورة ديناميكياً من متجر المصادقة
const userName = computed(() => {
  return authStore.userName;
});

const userAvatar = computed(() => {
  return authStore.userAvatar || getImageUrl('user-avatar.png');
});

const getImageUrl = (fileName) => {
  return new URL(`../../assets/images/${fileName}`, import.meta.url).href;
};

const handleLogout = async () => {
  if (authStore.logout) {
    await authStore.logout();
  } else {
    authStore.setUser(null, null);
  }
  router.push('/login');
};

const handleLogoFallback = (e) => {
  e.target.src = getImageUrl('logo.png');
};

const handleAvatarFallback = (e) => {
  e.target.src = getImageUrl('user-avatar.png');
};
</script>

<style scoped>
.navbar-logo-img-large {
  height: 42px;
  width: auto;
  object-fit: contain;
}

@media (min-width: 768px) {
  .navbar-logo-img-large {
    height: 58px;
  }
}

.donor-avatar-img {
  width: 38px;
  height: 38px;
  object-fit: cover;
}

@media (min-width: 768px) {
  .donor-avatar-img {
    width: 46px;
    height: 46px;
  }
}

.active-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #22c55e;
  display: inline-block;
}

.max-w-600 {
  max-width: 600px;
}

.notification-badge {
  font-size: 0.65rem;
  padding: 3px 6px;
}

.notifications-dropdown-menu {
  width: 260px;
}

.bg-danger-subtle {
  background-color: #fee2e2 !important;
}

.no-chevron::after {
  display: none !important;
}

.dropdown-item {
  transition: all 0.2s ease;
}

.dropdown-item:hover {
  background-color: #fdecec;
}

.dropdown-item:hover span {
  color: #dc2626 !important;
}

.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.cursor-pointer { cursor: pointer; }
.shadow-2xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
</style>
