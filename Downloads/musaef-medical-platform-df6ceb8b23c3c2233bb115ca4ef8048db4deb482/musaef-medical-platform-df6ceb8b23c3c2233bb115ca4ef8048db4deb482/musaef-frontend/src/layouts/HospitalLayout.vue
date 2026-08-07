<template>
  <div class="hospital-layout" dir="rtl">
    <!-- الهيدر العلوي -->
    <header class="topbar">
      <!-- زر القائمة للتابلت والموبايل -->
      <button
        type="button"
        class="menu-toggle"
        :aria-expanded="mobileMenu"
        aria-label="فتح القائمة"
        @click="toggleMobileMenu"
      >
        <i :class="mobileMenu ? 'bi bi-x-lg' : 'bi bi-list'"></i>
      </button>

      <!-- معلومات الطبيب -->
      <div class="header-doctor">
        <img src="/images/doctor.png" alt="صورة الطبيب" class="header-doctor-image" />

        <div class="header-doctor-info">
          <h5>د. سعيد عبد</h5>
          <p>مدير بنك الدم</p>
        </div>
      </div>

      <!-- البحث -->
      <div class="search-box">
        <input v-model="searchText" type="text" placeholder="ابحث عن مريض" />

        <i class="bi bi-search"></i>
      </div>

      <!-- الإشعارات -->
      <button type="button" class="notification-button" aria-label="الإشعارات">
        <i class="bi bi-bell"></i>
        <span>2</span>
      </button>

      <!-- حالة الدم -->
      <div class="blood-status">
        <span>O- منخفض</span>
      </div>
      <!-- اللوجو -->
      <div class="header-logo">
        <img src="/images/logo.png" alt="Musaef Logo" />
      </div>
    </header>

    <!-- المنطقة تحت الهيدر -->
    <div class="layout-body">
      <!-- القائمة الجانبية -->
      <aside class="sidebar" :class="{ open: mobileMenu }">
        <div>
          <nav class="sidebar-menu">
            <RouterLink to="/hospital/dashboard" class="menu-link" @click="closeMobileMenu">
              <i class="bi bi-grid-fill"></i>
              <span>لوحة التحكم</span>
            </RouterLink>

            <RouterLink to="/hospital/notifications" class="menu-link" @click="closeMobileMenu">
              <i class="bi bi-bell"></i>
              <span>مركز الإشعارات والتنبيهات</span>
            </RouterLink>

            <RouterLink to="/hospital/requests" class="menu-link" @click="closeMobileMenu">
              <i class="bi bi-alarm"></i>
              <span>إدارة النداءات الطارئة</span>
            </RouterLink>

            <RouterLink to="/hospital/inventory" class="menu-link" @click="closeMobileMenu">
              <i class="bi bi-droplet-half"></i>
              <span>إدارة بنك الدم</span>
            </RouterLink>

            <RouterLink to="/hospital/settings" class="menu-link" @click="closeMobileMenu">
              <i class="bi bi-gear-fill"></i>
              <span>إعدادات الجهة الطبية</span>
            </RouterLink>
          </nav>
          <div class="quick-help">
            <h6>مساعدة سريعة</h6>

            <p>تحتاج مساعدة عاجلة؟ فريق الدعم متاح على مدار الساعة.</p>

            <button type="button">تواصل مع الدعم</button>
          </div>
        </div>

        <button type="button" class="logout-button" @click="handleLogout">
          <i class="bi bi-box-arrow-right"></i>
          <span>تسجيل الخروج</span>
        </button>
      </aside>

      <!-- الطبقة المظلمة خلف القائمة -->
      <Transition name="overlay">
        <button
          v-if="mobileMenu"
          type="button"
          class="sidebar-overlay"
          aria-label="إغلاق القائمة"
          @click="closeMobileMenu"
        ></button>
      </Transition>

      <!-- المحتوى الرئيسي -->
      <main class="main-area">
        <div class="page-wrapper">
          <RouterView />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink, RouterView, useRouter } from 'vue-router'

const router = useRouter()

const searchText = ref('')
const mobileMenu = ref(false)

const toggleMobileMenu = () => {
  mobileMenu.value = !mobileMenu.value
}

const closeMobileMenu = () => {
  mobileMenu.value = false
}

const handleLogout = () => {
  mobileMenu.value = false
  localStorage.removeItem('token')
  router.push('/login')
}
</script>
