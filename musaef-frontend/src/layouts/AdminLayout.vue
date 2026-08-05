<template>
  <div class="admin-layout min-vh-100 bg-light-gray d-flex flex-column" dir="rtl">
    <AdminHeader />

    <div class="d-flex flex-grow-1 main-body-wrapper">
      <!-- السايدبار ثابت في أقصى اليمين دائماً -->
      <aside class="sidebar-container flex-shrink-0">
        <AdminSidebar />
      </aside>

      <!-- محتوى الصفحة يتغير اتجاه نصها بحسب اللغة دون نقل السايدبار -->
      <main class="main-content-container flex-grow-1 p-3 p-md-4 overflow-x-hidden" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import AdminHeader from '@/components/admin/AdminHeader.vue';
import AdminSidebar from '@/components/admin/AdminSidebar.vue';

const currentLocale = computed(() => localStorage.getItem('musaef_lang') || 'ar');
</script>

<style scoped>
.admin-layout {
  background-color: #f8fafc;
}

.main-body-wrapper {
  width: 100%;
}

.bg-light-gray {
  background-color: #f8fafc;
}

@media (max-width: 991.98px) {
  .sidebar-container {
    display: none;
  }
}
</style>
