<template>
  <div class="donor-layout bg-light min-vh-100 d-flex flex-column dir-rtl">
    <!-- الهيدر العلوي الخاص بالمتبرع -->
    <header class="bg-white border-bottom sticky-top py-2 shadow-sm z-3">
      <div class="container">
        <div class="d-flex align-items-center justify-content-between gap-3">

          <!-- الشعار والرابط الرئيسي للمتبرع المسجل -->
          <router-link to="/donor/dashboard" class="d-flex align-items-center text-decoration-none">
            <span class="fs-3 me-2">🩸</span>
            <span class="fw-bold text-danger fs-4">مُسْـعِـف</span>
          </router-link>

          <!-- شريط البحث السريع -->
          <div class="flex-grow-1 mx-lg-4 d-none d-md-block max-w-500">
            <div class="input-group input-group-sm">
              <input type="text" class="form-control bg-light border-0 text-end pe-3" placeholder="ابحث عن مستشفى أو طلب تبرع..." />
              <span class="input-group-text bg-light border-0 text-muted">🔍</span>
            </div>
          </div>

          <!-- أدوات المستخدم (التنبيهات، اللغة، والبروفايل) -->
          <div class="d-flex align-items-center gap-3">
            <!-- تغيير اللغة -->
            <div class="dropdown">
              <button class="btn btn-light btn-sm dropdown-toggle rounded-pill px-3 border-0 bg-light" type="button" data-bs-toggle="dropdown">
                🌐 العربية
              </button>
              <ul class="dropdown-menu text-end border-0 shadow-sm fs-8">
                <li><a class="dropdown-item" href="#">العربية</a></li>
                <li><a class="dropdown-item" href="#">English</a></li>
              </ul>
            </div>

            <!-- زر الإشعارات مربوط بالمتجر -->
            <router-link to="/donor/notifications" class="position-relative text-dark text-decoration-none">
              <span class="fs-5">🔔</span>
              <span v-if="unreadNotificationsCount > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light fs-9">
                {{ unreadNotificationsCount }}
              </span>
            </router-link>

            <!-- معلومات البروفايل مربوطة بالمتجر -->
            <router-link to="/donor/profile" class="d-flex align-items-center gap-2 text-decoration-none text-dark border-start ps-3 ms-1">
              <div class="text-end d-none d-sm-block">
                <h6 class="fw-bold mb-0 fs-8">{{ currentUser?.name || 'حمزة من غزة' }}</h6>
                <small class="text-success fs-9 fw-semibold">● {{ currentUser?.status || 'متبرع نشط' }}</small>
              </div>
              <img :src="currentUser?.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=150&auto=format&fit=crop'" class="rounded-circle border border-2 border-danger" width="40" height="40" :alt="currentUser?.name || 'حمزة'" />
            </router-link>
          </div>

        </div>
      </div>
    </header>

    <!-- شريط التنقل الفرعي للمتبرع -->
    <nav class="bg-white border-bottom py-2 shadow-xs">
      <div class="container d-flex gap-4 overflow-auto text-nowrap fs-8 fw-semibold">
        <router-link to="/donor/dashboard" class="nav-sub-link text-decoration-none py-1" active-class="active-sub-link">
          📊 لوحة التحكم
        </router-link>
        <router-link to="/donor/donation-center" class="nav-sub-link text-decoration-none py-1" active-class="active-sub-link">
          🏥 مركز التبرع والطلبات
        </router-link>
        <router-link to="/donor/achievements" class="nav-sub-link text-decoration-none py-1" active-class="active-sub-link">
          🏆 إنجازاتي وبطاقتي
        </router-link>
        <router-link to="/donor/profile" class="nav-sub-link text-decoration-none py-1" active-class="active-sub-link">
          👤 حسابي والملف الصحي
        </router-link>
      </div>
    </nav>

    <!-- محتوى الصفحة -->
    <main class="flex-grow-1 py-4">
      <div class="container">
        <slot />
      </div>
    </main>

    <!-- الفوتر المصغر للمتبرع -->
    <footer class="bg-white border-top py-3 text-center text-muted fs-8">
      جميع الحقوق محفوظة © {{ currentYear }} منصة مسعف الطبية
    </footer>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useAuthStore } from '@/stores/authStore';
import { useNotificationStore } from '@/stores/notificationStore';

const authStore = useAuthStore();
const notificationStore = useNotificationStore();

const currentUser = computed(() => authStore.user);
const unreadNotificationsCount = computed(() => notificationStore.unreadCount || 2);
const currentYear = new Date().getFullYear();
</script>

<style scoped>
.max-w-500 { max-width: 480px; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.nav-sub-link { color: #64748b; transition: all 0.2s; }
.nav-sub-link:hover { color: #dc3545; }
.active-sub-link { color: #dc3545; font-weight: bold; border-bottom: 2px solid #dc3545; }
</style>
