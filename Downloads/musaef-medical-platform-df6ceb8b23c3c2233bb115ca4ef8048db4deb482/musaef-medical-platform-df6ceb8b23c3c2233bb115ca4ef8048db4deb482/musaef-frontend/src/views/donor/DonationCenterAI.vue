<!-- src/views/donor/DonationCenterAI.vue -->
<template>
  <div class="ai-recommendations-wrapper bg-light min-vh-100" dir="rtl">

    <!-- 🟢 1. النافبار العلوي (مطابق تماماً لـ Figma) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 py-2 border-bottom">
      <div class="container px-3 px-md-4 d-flex align-items-center justify-content-between">

        <!-- 1️⃣ أقصى اليمين: الملف الشخصي المصغر -->
        <div class="d-flex align-items-center gap-2">
          <img
            src="/image.png"
            alt="صورة المستخدم"
            class="rounded-circle border border-2 border-light shadow-sm"
            style="width: 40px; height: 40px; object-fit: cover;"
          />
          <div class="text-start d-none d-sm-block">
            <h6 class="fw-bold mb-0 x-small">حمزة من غزة</h6>
            <span class="text-muted xx-small">متبرع نشط</span>
          </div>
        </div>

        <!-- 2️⃣ المنتصف: شريط البحث + الإشعارات -->
        <div class="d-flex align-items-center gap-3 my-2 my-lg-0 col-12 col-md-5">
          <!-- حقل البحث -->
          <div class="input-group flex-grow-1">
            <span class="input-group-text bg-light border-0 ps-3 rounded-end-pill text-muted">
              <i class="bi bi-search"></i>
            </span>
            <input
              type="text"
              class="form-control bg-light border-0 rounded-start-pill x-small py-2"
              placeholder="ابحث عن مريض..."
            />
          </div>

          <!-- أيقونة الإشعارات -->
          <div class="position-relative cursor-pointer flex-shrink-0">
            <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
              <i class="bi bi-bell fs-5 text-secondary"></i>
            </div>
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
              <span class="visually-hidden">تنبيهات جديدة</span>
            </span>
          </div>
        </div>

        <!-- اليسار: اختيار اللغة واللوجو -->
        <div class="d-flex align-items-center gap-3">
          <div class="dropdown">
            <button class="btn btn-sm text-dark dropdown-toggle fs-8 p-0 border-0 fw-normal" type="button" data-bs-toggle="dropdown">
              🌐 العربية
            </button>
            <ul class="dropdown-menu dropdown-menu-start fs-8">
              <li><a class="dropdown-item active" href="#">العربية</a></li>
              <li><a class="dropdown-item" href="#">English</a></li>
            </ul>
          </div>
          <img src="/logo.png" alt="مسعف Logo" style="height: 32px;">
        </div>

      </div>
    </nav>

    <!-- 🔴 2. شريط التبويبات (الخط الأحمر تحت توصيات الذكاء الاصطناعي فقط) -->
    <div class="bg-white border-bottom shadow-sm mb-3">
      <div class="container d-flex justify-content-around align-items-center py-2">

        <!-- جميع الطلبات -->
        <router-link
          to="/donor/donation-center"
          class="tab-item text-center fw-bold text-muted cursor-pointer px-4 py-1 text-decoration-none"
          active-class="active-tab"
        >
          <i class="bi bi-list-task me-1"></i> جميع الطلبات
        </router-link>

        <!-- توصيات الذكاء الاصطناعي (النشط حالياً باللون الأحمر) -->
        <router-link
          to="/donor/donation-center/ai"
          class="tab-item text-center fw-bold text-muted cursor-pointer px-4 py-1 text-decoration-none"
          active-class="active-tab"
        >
          <i class="bi bi-stars me-1"></i> توصيات الذكاء الاصطناعي
        </router-link>

        <!-- الخريطة (غير نشط - لون رمادي) -->
        <router-link
          to="/donor/donation-center/map"
          class="tab-item text-center fw-bold text-muted cursor-pointer px-4 py-1 text-decoration-none"
          active-class="active-tab"
        >
          <i class="bi bi-map me-1"></i> الخريطة
        </router-link>

      </div>
    </div>
<!-- 3. محتوى الصفحة الرئيسي -->
<div class="container py-4">

  <!-- العنوان في أقصى اليمين -->
  <div class="text-end mb-4">
    <h5 class="fw-bold text-dark mb-1">توصيات الذكاء الاصطناعي</h5>
    <p class="text-muted fs-8 mb-0">تم ترتيب هذه الطلبات بناءً على موقعك، وفصيلتك، ودرجة الاستجابة المتوقعة.</p>
  </div>

  <!-- 🎴 شبكة الكروت (عمودين + الكارت الأخير بالمنتصف) -->
<div class="row g-4 justify-content-center">

  <div
    v-for="(item, index) in aiRequests"
    :key="index"
    :class="index === aiRequests.length - 1 ? 'col-12 col-md-7' : 'col-12 col-md-6'"
  >
    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">

      <div>
        <!-- 🖼️ الصورة على اليمين + الاسم والموقع على اليسار -->
        <div class="d-flex justify-content-between align-items-start mb-3">
          <img :src="item.image" alt="المستشفى" class="rounded-3" style="width: 100px; height: 62px; object-fit: cover;" />

          <div class="text-end">
            <h6 class="fw-bold mb-1 fs-6 text-dark">{{ item.hospital }}</h6>
            <span class="text-muted fs-8 d-block">📍 {{ item.location }}</span>
          </div>
        </div>

        <!-- 📊 التفاصيل: عدد الوحدات على اليمين وفصيلة الدم على الشمال -->
        <div class="row g-0 align-items-center bg-light rounded-3 p-2 mb-2 text-center">
          <div class="col-6 border-end">
            <strong class="text-dark fs-6 d-block">{{ item.units }}</strong>
            <span class="text-muted fs-8 d-block">عدد الوحدات</span>
          </div>
          <div class="col-6">
            <strong class="text-danger fs-5 d-block fw-bold">{{ item.bloodType }}</strong>
            <span class="text-muted fs-8">فصيلة الدم المطلوبة</span>
          </div>
        </div>

        <!-- المسافة والخطورة -->
        <div class="d-flex justify-content-between align-items-center px-1 mb-2">
          <span class="badge rounded-pill px-3 py-1 fs-8" :class="item.severityClass">{{ item.severity }}</span>
          <span class="text-muted fs-8">📍 {{ item.distance }}</span>
        </div>

        <!-- مربع تطابق الذكاء الاصطناعي (سيظهر فقط للكارت الذي يملك نسبة تطابق) -->
        <div v-if="item.matchPercentage" class="ai-match-box text-center p-2 rounded-3 mb-2 bg-success bg-opacity-10 border border-success border-opacity-25">
          <span class="text-success fw-bold fs-8 d-block">تطابق الذكاء الاصطناعي {{ item.matchPercentage }}%</span>
          <span class="text-muted fs-8">{{ item.matchReason }}</span>
        </div>
      </div>

      <!-- زر عرض التفاصيل -->
      <button class="btn btn-outline-danger btn-sm w-100 rounded-pill fs-8 py-1 mt-2" @click="selectedItem = item; showModal = true">
        <i class="bi bi-eye me-1"></i> عرض التفاصيل
      </button>

    </div>
  </div>

</div>
  <!-- زر عرض المزيد -->
  <div class="text-center mt-4">
    <button class="btn btn-outline-danger btn-sm rounded-pill px-5 py-2 fs-8 fw-bold">عرض المزيد من الطلبات</button>
  </div>

</div>

<!-- 🟣 المربع المنبثق (في منتصف الشاشة تماماً) -->
<div v-if="showModal" class="modal-backdrop-custom d-flex align-items-center justify-content-center">
  <div class="modal-card bg-white rounded-4 p-4 shadow-lg position-relative text-center m-auto" style="max-width: 440px; width: 90%;">

    <button class="btn-close position-absolute top-0 start-0 m-3 fs-8" @click="showModal = false"></button>

    <h6 class="fw-bold text-dark mb-3">تفاصيل الحالة</h6>

    <div class="d-flex align-items-center justify-content-between text-end mb-3 bg-light p-2 rounded-3">
      <img :src="selectedItem?.image || '/Rectangle 22872.png'" class="rounded-3" style="width: 80px; height: 50px; object-fit: cover;" />
      <div class="text-end">
        <h6 class="fw-bold mb-0 fs-7 text-dark">{{ selectedItem?.hospital || 'مستشفى الشفاء الطبي' }}</h6>
        <span class="text-muted fs-8">📍 {{ selectedItem?.location || 'غزة' }}</span>
      </div>
    </div>

    <div class="row g-0 bg-light rounded-3 p-2 mb-3 text-center">
      <div class="col-4 border-end">
        <strong class="text-danger fs-6 d-block">{{ selectedItem?.bloodType || 'A+' }}</strong>
        <span class="text-muted fs-8">فصيلة مطلوبة</span>
      </div>
      <div class="col-4 border-end">
        <strong class="text-dark fs-6 d-block">{{ selectedItem?.units || '2' }}</strong>
        <span class="text-muted fs-8">عدد الوحدات</span>
      </div>
      <div class="col-4">
        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill fs-8">حرجة جداً</span>
        <span class="text-muted fs-8 d-block mt-1">مستوى الخطورة</span>
      </div>
    </div>

    <div class="timer-box bg-light p-2 rounded-3 mb-3 border">
      <span class="text-muted fs-8 d-block">الوقت المتبقي للاستجابة</span>
      <span class="text-danger fw-bold fs-4 d-block" dir="ltr">00 : 45 : 32</span>
    </div>

    <div class="text-start bg-light p-3 rounded-3 mb-3 fs-8">
      <strong class="d-block mb-1 text-dark">لماذا تم ترشيح هذا الطلب لك؟</strong>
      <ul class="mb-0 ps-3 text-muted">
        <li>متطابق مع فصيلتك</li>
        <li>أقرب من موقعك الحالي (2.4 كم)</li>
      </ul>
    </div>

    <div class="d-flex flex-column gap-2">
      <button class="btn btn-danger w-100 rounded-pill btn-sm py-2 fw-bold" @click="showModal = false">قبول الطلب</button>
      <button class="btn btn-outline-secondary w-100 rounded-pill btn-sm py-2" @click="showModal = false">رفض الطلب</button>
    </div>
</div>
  </div>
</div>
</template>

<script setup>
import { ref } from 'vue';

const showModal = ref(false);
const selectedItem = ref(null);

const aiRequests = ref([
  {
    hospital: 'مستشفى غزة الأوروبي',
    location: 'خانيونس',
    image: '/Rectangle 22872 (1).png',
    bloodType: 'A+',
    units: 2,
    distance: '8.7 كم',
    severity: 'متوسطة',
    severityClass: 'bg-warning bg-opacity-10 text-warning'
    // تم حذف الذكاء الاصطناعي هنا
  },
  {
    hospital: 'مستشفى الشفاء الطبي',
    location: 'غزة',
    image: '/Rectangle 22872.png',
    bloodType: 'O-',
    units: 3,
    distance: '2.4 كم',
    severity: 'حرجة جداً',
    severityClass: 'bg-danger bg-opacity-10 text-danger',
    matchPercentage: 96,
    matchReason: 'متوافق مع فصيلتك والموقع'
  },
  {
    hospital: 'مستشفى شهداء الأقصى',
    location: 'دير البلح',
    image: '/Rectangle 22878.png',
    bloodType: 'A-',
    units: 2,
    distance: '2.4 كم',
    severity: 'متوسطة',
    severityClass: 'bg-warning bg-opacity-10 text-warning'
  },
  {
    hospital: 'مستشفى ناصر',
    location: 'خانيونس',
    image: '/Rectangle 22883 (2).png',
    bloodType: 'O+',
    units: 2,
    distance: '6.1 كم',
    severity: 'متوسطة',
    severityClass: 'bg-warning bg-opacity-10 text-warning'
  },
  {
    hospital: 'المستشفى الكويتي',
    location: 'رفح',
    image: '/Rectangle 22881.png',
    bloodType: 'B-',
    units: 2,
    distance: '4.3 كم',
    severity: 'حرجة جداً',
    severityClass: 'bg-danger bg-opacity-10 text-danger'
  }
]);
</script>

<style scoped>
.ai-recommendations-wrapper {
  background-color: #f8f9fc;
}

/* التبويب والتنقل */
.tab-item {
  position: relative;
  transition: all 0.2s ease;
}

/* التبويب النشط (اللون الأحمر والخط السفلي) */
.active-tab {
  color: #dc3545 !important;
  font-weight: bold;
}

.active-tab::after {
  content: '';
  position: absolute;
  bottom: -9px;
  right: 0;
  width: 100%;
  height: 3px;
  background-color: #dc3545;
  border-radius: 2px 2px 0 0;
}

/* النافذة المنبثقة المتموّضعة في المنتصف تماماً */
.modal-backdrop-custom {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.4);
  z-index: 1050;
}

.fs-7 { font-size: 0.85rem; }
.fs-8 { font-size: 0.75rem; }
.x-small { font-size: 0.8rem; }
.xx-small { font-size: 0.7rem; }
</style>
