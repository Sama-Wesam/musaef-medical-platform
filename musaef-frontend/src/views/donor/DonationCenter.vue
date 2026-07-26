<!-- src/views/donor/DonationCenter.vue -->
<template>
  <div class="donation-center-wrapper bg-light min-vh-100" dir="rtl">

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

        <!-- 3️⃣ أقصى اليسار: اختيار اللغة + شعار مسعف -->
        <div class="d-flex align-items-center gap-3">
          <!-- اختيار اللغة -->
          <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center gap-1 border-0 bg-transparent x-small fw-bold" type="button">
              <i class="bi bi-globe me-1"></i> العربية
            </button>
          </div>

          <!-- الشعار Logo (أقصى اليسار) -->
          <div class="d-flex align-items-center">
  <img
    src="/logo.png"
    alt="شعار مسعف"
    style="height: 45px; width: auto; object-fit: contain;"
  />
</div>
    </div>
  </div>

    </nav>
<!-- شريط التبويبات -->
<div class="bg-white border-bottom shadow-sm mb-3">
  <div class="container d-flex justify-content-around align-items-center py-2">

    <!-- 1️⃣ جميع الطلبات -->
    <router-link
      to="/donor/donation-center"
      class="tab-item text-center fw-bold text-muted cursor-pointer px-4 py-1 text-decoration-none"
      active-class="active-tab"
      exact
    >
      <i class="bi bi-list-task me-1"></i> جميع الطلبات
    </router-link>

    <!-- 2️⃣ توصيات الذكاء الاصطناعي -->
    <router-link
      to="/donor/donation-center/ai"
      class="tab-item text-center fw-bold text-muted cursor-pointer px-4 py-1 text-decoration-none"
      active-class="active-tab"
    >
      <i class="bi bi-stars me-1"></i> توصيات الذكاء الاصطناعي
    </router-link>

    <!-- 3️⃣ الخريطة -->
    <router-link
      to="/donor/donation-center/map"
      class="tab-item text-center fw-bold text-muted cursor-pointer px-4 py-1 text-decoration-none"
      active-class="active-tab"
    >
      <i class="bi bi-map me-1"></i> الخريطة
    </router-link>

  </div>
</div>

    <!-- 🔴 3. محتوى الصفحة الرئيسي -->
    <div class="container-fluid py-4 px-4">
      <main class="col-12 d-flex flex-column gap-4">

        <!-- 🔹 أ. شريط الفلاتر والبحث -->
        <div class="bg-white rounded-4 p-3 shadow-sm border">
          <div class="row g-2 align-items-center">

            <div class="col-lg-3 col-md-6">
              <input type="text" class="form-control form-control-sm rounded-3 fs-8 py-2" placeholder="🔍 ابحث عن مستشفى أو فصيلة..." />
            </div>

            <div class="col-lg-2 col-md-4">
              <select class="form-select form-select-sm rounded-3 fs-8 py-2">
                <option selected>ترتيب حسب : الأحدث</option>
                <option>الأقرب مسافة</option>
                <option>الأعلى أولوية</option>
              </select>
            </div>

            <div class="col-lg-2 col-md-4">
              <select class="form-select form-select-sm rounded-3 fs-8 py-2">
                <option selected>فصيلة الدم : الكل</option>
                <option>A+</option><option>A-</option>
                <option>B+</option><option>B-</option>
                <option>O+</option><option>O-</option>
                <option>AB+</option><option>AB-</option>
              </select>
            </div>

            <div class="col-lg-2 col-md-4">
              <select class="form-select form-select-sm rounded-3 fs-8 py-2">
                <option selected>مستوى الخطورة : الكل</option>
                <option>حرجة جداً</option>
                <option>متوسطة</option>
                <option>عادية</option>
              </select>
            </div>

            <div class="col-lg-2 col-md-4">
              <select class="form-select form-select-sm rounded-3 fs-8 py-2">
                <option selected>المسافة : الكل</option>
                <option>أقل من 5 كم</option>
                <option>أقل من 10 كم</option>
              </select>
            </div>

            <div class="col-lg-1 col-md-4 text-center">
              <button class="btn btn-outline-secondary btn-sm w-100 rounded-3 fs-8 py-2">🔄 إعادة</button>
            </div>

          </div>
        </div>

        <!-- 🔹 ب. عنوان عدد الطلبات -->
        <div class="d-flex justify-content-between align-items-center px-1">
          <div>
            <h6 class="fw-bold mb-0 text-dark">جميع الطلبات <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">12 طلب</span></h6>
            <span class="text-muted fs-8">تم تحديث البيانات الآن</span>
          </div>
        </div>

       <!-- شبكة الكروت (3 أعمدة بكل صف) -->
<div class="row g-4 justify-content-center">
  <div
    v-for="(item, index) in allRequests"
    :key="index"
    class="col-12 col-md-6 col-lg-4"
  >
    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-between">

      <div>
        <!-- 🖼️ الهيدر: الاسم على اليسار والصورة على اليمين -->
        <div class="d-flex justify-content-between align-items-start mb-3">
          <img :src="item.image" alt="المستشفى" class="rounded-3" style="width: 90px; height: 58px; object-fit: cover;" />

          <div class="text-end">
            <h6 class="fw-bold mb-1 fs-6 text-dark">{{ item.hospital }}</h6>
            <span class="text-muted fs-8 d-block">📍 {{ item.location }}</span>
          </div>
        </div>

        <!-- 📊 التفاصيل مقسمة لـ 3 أجزاء متساوية -->
        <div class="row g-0 align-items-center bg-light rounded-3 p-2 mb-3 text-center">

          <!-- 1. أقصى اليمين: مستوى الخطورة والمسافة -->
          <div class="col-4 border-start">
            <span class="text-muted fs-8 d-block mb-1">مستوى الخطورة</span>
            <span class="badge rounded-pill px-2 py-1 fs-8 mb-1" :class="item.severityClass">{{ item.severity }}</span>
            <span class="text-muted fs-8 d-block">📍 {{ item.distance }}</span>
          </div>

          <!-- 2. المنتصف: عدد الوحدات -->
          <div class="col-4 border-start">
            <strong class="text-dark fs-5 d-block fw-bold">{{ item.units }}</strong>
            <span class="text-muted fs-8 d-block">عدد الوحدات</span>
          </div>

          <!-- 3. أقصى اليسار: فصيلة الدم -->
          <div class="col-4">
            <strong class="text-danger fs-5 d-block fw-bold">{{ item.bloodType }}</strong>
            <span class="text-muted fs-8 d-block">فصيلة الدم المطلوبة</span>
          </div>

        </div>
      </div>

      <!-- زر عرض التفاصيل -->
      <button class="btn btn-outline-danger btn-sm w-100 rounded-pill fs-8 py-2 mt-2" @click="selectedItem = item; showModal = true">
        عرض التفاصيل
      </button>

    </div>
  </div>
</div>

<!-- 🔢 أرقام التنقل + عرض 6 لكل صفحة (في سطر واحد متناسق) -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center my-4 gap-3">

        <!-- 1️⃣ جهة اليمين في تصميم RTL: عرض 6 لكل صفحة -->
        <div class="d-flex align-items-center gap-2 text-muted fs-8">
          <span>عرض</span>
          <select class="form-select form-select-sm text-center fw-bold rounded-3 fs-8 py-1" style="width: 65px;">
            <option value="6" selected>6</option>
            <option value="12">12</option>
            <option value="24">24</option>
          </select>
          <span>لكل صفحة</span>
        </div>

        <!-- 2️⃣ المنتصف: أرقام الصفحات -->
        <div class="d-flex align-items-center gap-2" dir="ltr">
          <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 fs-8">السابق &gt;</button>
          <button class="btn btn-danger btn-sm rounded-circle px-3 py-1 fs-8 fw-bold">1</button>
          <button class="btn btn-light btn-sm rounded-circle px-3 py-1 fs-8 text-muted border">2</button>
          <button class="btn btn-light btn-sm rounded-circle px-3 py-1 fs-8 text-muted border">3</button>
          <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 fs-8">&lt; التالي</button>
        </div>

        <!-- 3️⃣ عنصر موازنة وهمي ليضمن بقاء الأرقام في المنتصف تماماً -->
        <div class="d-none d-md-block" style="min-width: 140px;"></div>


</div>


      </main>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue';

const allRequests = ref([
  {
    hospital: 'مستشفى غزة الأوروبي',
    location: 'خانيونس',
    image: '/Rectangle 22872 (1).png',
    bloodType: '+A',
    units: 2,
    distance: '8.7 كم',
    severity: 'متوسطة',
    severityClass: 'bg-warning bg-opacity-10 text-warning'
  },
  {
    hospital: 'مستشفى ناصر',
    location: 'خانيونس',
    image: '/Rectangle 22883 (2).png',
    bloodType: '+O',
    units: 2,
    distance: '6.1 كم',
    severity: 'حرجة',
    severityClass: 'bg-danger bg-opacity-10 text-danger'
  },
  {
    hospital: 'مستشفى الشفاء الطبي',
    location: 'غزة',
    image: '/Rectangle 22872.png',
    bloodType: '-O',
    units: 3,
    distance: '2.4 كم',
    severity: 'حرجة',
    severityClass: 'bg-danger bg-opacity-10 text-danger'
  },
  {
    hospital: 'مستشفى الكويت',
    location: 'رفح',
    image: '/Rectangle 22881.png',
    bloodType: '-B',
    units: 1,
    distance: '4.3 كم',
    severity: 'منخفضة',
    severityClass: 'bg-info bg-opacity-10 text-info'
  },
  {
    hospital: 'مستشفى العودة',
    location: 'النصيرات',
    image: '/Rectangle 22878.png',
    bloodType: '+AB',
    units: 4,
    distance: '6.1 كم',
    severity: 'حرجة',
    severityClass: 'bg-danger bg-opacity-10 text-danger'
  },
  {
    hospital: 'مستشفى شهداء الأقصى',
    location: 'دير البلح',
    image: '/Rectangle 22878.png',
    bloodType: '-A',
    units: 2,
    distance: '2.4 كم',
    severity: 'متوسطة',
    severityClass: 'bg-warning bg-opacity-10 text-warning'
  }
]);
</script>

<style scoped>
.donation-center-wrapper {
  background-color: #f8f9fc;
}

/* ستايل التبويبات والخط الأحمر */
.nav-link {
  text-decoration: none;
  font-weight: 600;
  position: relative;
  transition: all 0.2s ease;
}

.active-tab {
  color: #dc3545 !important;
}

/* الخط الأحمر السفلي تحت "جميع الطلبات" */
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

.fs-7 { font-size: 0.85rem; }
.fs-8 { font-size: 0.75rem; }

.page-link {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
