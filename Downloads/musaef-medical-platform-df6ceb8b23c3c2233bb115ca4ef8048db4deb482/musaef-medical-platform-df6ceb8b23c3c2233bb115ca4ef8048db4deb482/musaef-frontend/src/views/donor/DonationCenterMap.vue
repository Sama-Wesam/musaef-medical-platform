<template>
  <div class="account-settings-page dir-rtl text-end bg-light min-vh-100 pb-5">

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

    <!-- 3️⃣ Main Map Container (حاوية الخريطة الدائرية) -->
    <main class="container">
      <div class="map-card-wrapper position-relative rounded-4 overflow-hidden shadow-sm bg-white p-2" style="height: 780px;">

        <!-- صورة الخريطة كخلفية -->
        <img src="/map-bg.png" alt="Map" class="w-100 h-100 rounded-4 object-fit-cover">

        <!-- الكرت المنبثق في الوسط/اليمين مثل فيجما -->
       <div class="figma-hospital-popup bg-white rounded-4 shadow-lg p-4 position-absolute">

          <!-- 1️⃣ الهيدر -->
          <div class="position-relative d-flex align-items-center justify-content-center mb-3">
            <button class="btn-close xx-small p-0 position-absolute start-0" aria-label="Close"></button>
            <span class="fw-bold text-dark fs-6">تفاصيل المستشفى</span>
          </div>

          <!-- 2️⃣ صورة المستشفى على اليمين والاسم على اليسار -->
          <div class="row g-3 align-items-center mb-3">
            <div class="col-6">
              <img
                src="/hospital.png"
                alt="مستشفى غزة الأوروبي"
                class="rounded-3 w-100 object-fit-cover shadow-sm"
                style="height: 85px;"
                onerror="this.src='https://via.placeholder.com/160x85?text=Hospital'"
              >
            </div>
            <div class="col-6 text-start">
              <h6 class="fw-bold mb-1 text-dark x-small">مستشفى غزة الأوروبي</h6>
              <span class="text-muted xxx-small d-block">خان يونس</span>
            </div>
          </div>

         <div class="row g-2 mb-3">
  <!-- المسافة (يمين) -->
  <div class="col-6">
    <div class="border rounded-3 p-2 text-center bg-light h-100 d-flex align-items-center justify-content-between px-2">
      <span class="text-muted xxx-small">📍. . .📍</span>
      <div class="text-end">
        <span class="text-muted xxx-small d-block">المسافة</span>
        <strong class="text-dark xx-small d-block">5.0 كم</strong>
        <span class="text-muted xxx-small">من موقعك الحالي</span>
      </div>
    </div>
  </div>
  <!-- الوقت المتوقع مع السيارة (يسار) - أبيض وبدون لون زهري -->
  <div class="col-6">
    <div class="border rounded-3 p-2 text-center bg-light h-100 d-flex align-items-center justify-content-between px-2">
      <img src="/car.png" alt="Car" style="width: 24px; height: 24px; object-fit: contain;" onerror="this.style.display='none'">
      <div class="text-end">
        <span class="text-muted xxx-small d-block">الوقت المتوقع</span>
        <strong class="text-dark xx-small d-block">10 دقائق</strong>
        <span class="text-muted xxx-small">بالسيارة</span>
      </div>
    </div>
  </div>
</div>

         <!-- 4️⃣ الفصائل المطلوبة -->
<!-- 4️⃣ الفصائل المطلوبة -->
<div class="mb-3 text-center">
  <span class="fw-bold text-dark xx-small d-block mb-2">الفصائل المطلوبة</span>
  <div class="d-flex justify-content-center gap-2">
    <!-- الترتيب يصفّ من اليمين إلى اليسار بناءً على اتجاه RTL -->
    <span class="blood-box blood-inactive">O+</span>
    <span class="blood-box blood-inactive">AB+</span>
    <span class="blood-box blood-inactive">B-</span>
    <span class="blood-box blood-active">A+</span> <!-- هذه حمراء بالكامل -->
    <span class="blood-box blood-inactive">O-</span>
  </div>
</div>
          <!-- 5️⃣ عدد الوحدات (يمين) ومستوى الخطورة (يسار) -->
          <div class="row g-2 mb-3">
            <div class="col-6">
              <div class="border rounded-3 p-2 text-center bg-light">
                <span class="text-muted xxx-small d-block">عدد الوحدات المطلوبة</span>
                <strong class="text-dark xx-small">3 وحدات</strong>
              </div>
            </div>
            <div class="col-6">
              <div class="border rounded-3 p-2 text-center bg-light">
                <span class="text-muted xxx-small d-block mb-1">مستوى الخطورة</span>
                <span class="badge bg-danger rounded-2 px-3 py-1 fw-bold xx-small">حرجة</span>
              </div>
            </div>
          </div>

          <!-- 6️⃣ آخر تحديث والأزرار -->
          <div class="text-center text-muted xxx-small mb-3">آخر تحديث : منذ 5 دقائق</div>

          <div class="d-grid gap-2">
            <button class="btn btn-danger rounded-3 fw-bold py-2 xx-small shadow-sm">أبدأ التوجيه الآن</button>
            <button class="btn btn-outline-danger rounded-3 fw-bold py-2 xx-small">حفظ المستشفى</button>
          </div>

        </div> <!-- إغلاق المربع المنبثق -->

      </div> <!-- إغلاق حاوية الخريطة -->
    </main>
  </div>
</template>

<script>
export default {
  name: 'DonationCenter'
}
</script>

<style scoped>
.dir-rtl {
  direction: rtl;
}

/* أحجام النصوص المخصصة */
.xx-small { font-size: 0.78rem; }
.xxx-small { font-size: 0.68rem; }
.x-small { font-size: 0.88rem; }

/* أبعاد كرت المستشفى المنبثق */
.figma-hospital-popup {
  width: 400px;
  top: 50%;
  right: 25px;
  left: auto;
  transform: translateY(-50%);
  z-index: 10;
  border-radius: 20px !important;
  border: 1px solid #e0e0e0;
}

/* تنسيق مربعات فصائل الدم */
.blood-box {
  min-width: 42px;
  height: 36px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: bold;
  direction: ltr;
}

.blood-inactive {
  background-color: #fde8e8 !important;
  color: #e63946 !important;
  border: 1px solid #f8d7da;
}

.blood-active {
  background-color: #dc3545 !important;
  color: #ffffff !important;
  border: 1px solid #dc3545;
}

/* تنسيقات شريط التبويبات */
.tab-item {
  position: relative;
  transition: all 0.2s ease;
}

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
</style>
