<template>
  <div class="admin-layout-wrapper">
    <!-- الهيدر الموحد في الأعلى -->
    <AdminHeader />

    <!-- هيكل الصفحة الأسفل (المحتوى والسايدبار) -->
    <div class="admin-container">

      <!-- السايدبار الموحد (يظهر في الجانب الأيمن حسب تصميم المنصة) -->
      <AdminSidebar />

      <!-- محتوى الصفحات المتغير -->
      <main class="admin-main-content">
        <slot />
      </main>

    </div>
  </div>
</template>

<script setup>
// استدعاء المكونين بالمسار الصحيح بناءً على هيكل المشروع[cite: 66]
import AdminHeader from '@/components/admin/AdminHeader.vue';
import AdminSidebar from '@/components/admin/AdminSidebar.vue';
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();
</script>

<style scoped>
/* الحاوية الكلية للتخطيط */
.admin-layout-wrapper {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background-color: #f8f9fa;
}

/* حاوية المحتوى والسايدبار تقع تحت الهيدر تماماً */
.admin-container {
  display: flex;
  flex: 1;
  width: 100%;
  flex-direction: row-reverse; /* لضمان ظهور السايدبار يمين والمحتوى يسار */
  align-items: flex-start;
}

/* مساحة المحتوى الأساسي */
.admin-main-content {
  flex-grow: 1;
  padding: 24px;
  background-color: #f8f9fa;
  min-height: calc(100vh - 96px); /* ارتفاع الشاشة مطروحاً منه ارتفاع الهيدر */
  overflow-x: hidden;
}

/* التجاوب مع الشاشات الصغيرة */
@media (max-width: 992px) {
  .admin-container {
    flex-direction: column;
  }
}
</style>
