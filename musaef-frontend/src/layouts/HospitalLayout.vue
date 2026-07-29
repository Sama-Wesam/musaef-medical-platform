<template>
  <div class="hospital-layout" dir="rtl">

    <!-- 1. الهيدر الموحد بأعلى الصفحة (عرض كامل) -->
    <HospitalHeader />

    <!-- 2. المنطقة السفلى للهيدر (تجمع السايدبار على اليمين والمحتوى على اليسار) -->
    <div class="hospital-body">

      <!-- السايدبار يمين الشاشة وتحت الهيدر -->
      <HospitalSidebar />

      <!-- منطقة محتوى الصفحات المعروضة -->
      <main class="hospital-page">
        <slot />
      </main>

    </div>

  </div>
</template>

<script setup>
import HospitalHeader from "@/components/hospital/HospitalHeader.vue";
import HospitalSidebar from "@/components/hospital/HospitalSidebar.vue";
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();
</script>

<style scoped>
.hospital-layout {
  display: flex;
  flex-direction: column;
  direction: rtl;
  min-height: 100vh;
  background: #F7F8FC;
}

/* الحاوية التي تجمع السايدبار مع الصفحة تحت الهيدر */
.hospital-body {
  display: flex;
  flex: 1;
  min-height: calc(100vh - 96px); /* 96px هو ارتفاع الهيدر */
}

.hospital-page {
  flex: 1;
  padding: 30px;
  background: #F7F8FC;
  min-width: 0;
}
</style>
