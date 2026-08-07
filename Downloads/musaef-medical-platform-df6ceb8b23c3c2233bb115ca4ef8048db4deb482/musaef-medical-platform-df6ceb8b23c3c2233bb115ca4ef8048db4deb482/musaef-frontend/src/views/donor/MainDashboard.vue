<template>
  <div class="dashboard-wrapper bg-light min-vh-100" dir="rtl">

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



        </div>

      </div>
    </nav>

    <!-- 🔴 محتوى اللوحة الرئيسية -->
    <div class="container-fluid py-4 px-4">
      <main class="col-12 d-flex flex-column gap-4">

        <!-- 🎯 كرت الأهلية للتبرع (صورة العداد يمين، النصوص بالوسط، وقطرة الدم يسار) -->
<div class="eligibility-card card border-0 shadow-sm rounded-4 text-center overflow-hidden my-2">
  <div class="card-body d-flex align-items-center justify-content-between py-4 px-4 px-md-5">

    <!-- 1️⃣ أقصى اليمين: العداد العكسي (12 يوماً) -->
    <div class="countdown-section text-center flex-shrink-0 d-flex flex-column align-items-center justify-content-center">
      <img
        src="/12.png"
        alt="12 يوماً متبقية"
        style="width: 140px; height: 140px; object-fit: contain;"
      />
      <span class="text-muted small mt-1 fs-7">حتى التبرع التالي</span>
    </div>

    <!-- 2️⃣ المنتصف: النصوص والأيقونات الثلاث -->
    <div class="flex-grow-1 text-center px-4">
      <h2 class="fw-bold mb-2 text-danger fs-2">مؤهل للتبرع</h2>
      <p class="mb-2 text-dark fw-bold fs-5">يمكنك التبرع الآن، صحتك تسمح بذلك</p>
      <p class="text-muted mb-3 fs-6">لديك <span class="text-danger fw-bold fs-5">230</span> نقطة و <span class="text-danger fw-bold fs-5">3</span> شارات</p>

      <!-- الأيقونات الثلاث الصغيرة -->
      <div class="d-flex align-items-center justify-content-center gap-3 mt-3">
        <div class="icon-circle shadow-sm bg-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
          <img src="/heart.png" alt="قلب" style="width: 35px; height: 35px; object-fit: contain;" />
        </div>
        <div class="icon-circle shadow-sm bg-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
          <img src="/bl.png" alt="دم" style="width: 35px; height: 35px; object-fit: contain;" />
        </div>
        <div class="icon-circle shadow-sm bg-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
          <img src="/star.png" alt="نجمة" style="width: 35px; height: 35px; object-fit: contain;" />
        </div>
      </div>
    </div>

    <!-- 3️⃣ أقصى اليسار: صورة قطرة الدم الكبيرة -->
    <div class="blood-shield-container flex-shrink-0">
      <img
        src="/blood.png"
        alt="قطرة الدم"
        style="width: 160px; height: auto; object-fit: contain;"
      />
    </div>

  </div>
</div>

        <!-- 📊 كروت الإحصائيات الأربعة (مرتبة ومحدثة بالصور) -->
<div class="row g-4 stats-grid">

  <!-- 1️⃣ عدد التبرعات (أقصى اليمين) -->
  <div class="col-lg-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 text-center bg-white d-flex flex-column justify-content-between align-items-center gap-2">
      <img src="/bl.png" alt="عدد التبرعات" style="width: 45px; height: 45px; object-fit: contain;" />
      <p class="small text-muted mb-1 fs-7">عدد التبرعات</p>
      <h1 class="display-5 fw-bold mb-0 text-danger fs-3">8</h1>
      <p class="small text-muted mt-1 fs-7 mb-0">آخر تبرع منذ 45 يوم</p>
    </div>
  </div>

  <!-- 2️⃣ النقاط -->
  <div class="col-lg-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 text-center bg-white d-flex flex-column justify-content-between align-items-center gap-2">
      <img src="/star.png" alt="النقاط" style="width: 45px; height: 45px; object-fit: contain;" />
      <p class="small text-muted mb-1 fs-7">النقاط</p>
      <h1 class="display-5 fw-bold mb-0 text-warning fs-3">230</h1>
      <p class="small text-muted mt-1 fs-7 mb-0">المستوى : متقدم</p>
    </div>
  </div>

  <!-- 3️⃣ الطلبات القريبة -->
  <div class="col-lg-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 text-center bg-white d-flex flex-column justify-content-between align-items-center gap-2">
      <img src="/Frame 2147225248.png" alt="الطلبات القريبة" style="width: 45px; height: 45px; object-fit: contain;" />
      <p class="small text-muted mb-1 fs-7">الطلبات القريبة</p>
      <h1 class="display-5 fw-bold mb-0 text-primary fs-3">2</h1>
      <p class="small text-muted mt-1 fs-7 mb-0">يوجد طلبان قريبان منك ضمن مسافة 5 كيلومترات</p>
    </div>
  </div>

  <!-- 4️⃣ حالة الأهلية للتبرع (أقصى اليسار) -->
  <div class="col-lg-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 text-center bg-white d-flex flex-column justify-content-between align-items-center gap-2">
      <img src="/Frame 2147225275.png" alt="حالة الأهلية" style="width: 45px; height: 45px; object-fit: contain;" />
      <p class="small text-muted mb-1 fs-7">حالة الأهلية للتبرع</p>
      <h6 class="fw-bold mb-0 text-success fs-5">مؤهل</h6>
      <p class="small text-muted mt-1 fs-7 mb-0">يمكنك التبرع الآن</p>
    </div>
  </div>

</div>
       <!-- 🔴 قسم الطلبات والإشعارات (مطابق لتصميم Figma 100%) -->
<div class="row g-4 text-end">
<!-- 1️⃣ أقصى اليمين: إشعارات عاجلة (مكبرة ومليئة للكرت) -->
<div class="col-lg-4">
  <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
    <div>
      <h5 class="fw-bold text-dark mb-4 text-center">إشعارات عاجلة</h5>

      <div class="d-flex flex-column gap-3">
        <!-- إشعار 1 -->
        <div class="d-flex align-items-center justify-content-between p-3 py-4 rounded-4 bg-light bg-opacity-50">
          <div>
            <h6 class="fw-bold text-danger mb-1 fs-6">مستشفى القدس</h6>
            <p class="small text-dark mb-1 fs-7">نقص حاد في فصيلة O-</p>
            <span class="text-muted fs-8">منذ 10 دقائق</span>
          </div>
          <div class="text-center bg-danger bg-opacity-10 p-3 rounded-4 d-flex flex-column align-items-center justify-content-center" style="min-width: 95px;">
            <img src="/bl.png" alt="دم" style="width: 40px; height: 40px; object-fit: contain;" />
            <p class="text-danger fw-bold fs-8 mb-0 mt-2">أولوية قصوى</p>
          </div>
        </div>

        <!-- إشعار 2 -->
        <div class="d-flex align-items-center justify-content-between p-3 py-4 rounded-4 bg-light bg-opacity-50">
          <div>
            <h6 class="fw-bold text-danger mb-1 fs-6">مستشفى ناصر</h6>
            <p class="small text-dark mb-1 fs-7">نقص حاد في فصيلة A+</p>
            <span class="text-muted fs-8">منذ 25 دقيقة</span>
          </div>
          <div class="text-center bg-danger bg-opacity-10 p-3 rounded-4 d-flex flex-column align-items-center justify-content-center" style="min-width: 95px;">
            <img src="/bl.png" alt="دم" style="width: 40px; height: 40px; object-fit: contain;" />
            <p class="text-danger fw-bold fs-8 mb-0 mt-2">أولوية قصوى</p>
          </div>
        </div>

        <!-- إشعار 3 -->
        <div class="d-flex align-items-center justify-content-between p-3 py-4 rounded-4 bg-light bg-opacity-50">
          <div>
            <h6 class="fw-bold text-danger mb-1 fs-6">مستشفى الكويت</h6>
            <p class="small text-dark mb-1 fs-7">نقص حاد في فصيلة B-</p>
            <span class="text-muted fs-8">منذ ساعة</span>
          </div>
          <div class="text-center bg-danger bg-opacity-10 p-3 rounded-4 d-flex flex-column align-items-center justify-content-center" style="min-width: 95px;">
            <img src="/bl.png" alt="دم" style="width: 40px; height: 40px; object-fit: contain;" />
            <p class="text-danger fw-bold fs-8 mb-0 mt-2">أولوية قصوى</p>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center mt-4">
      <a href="#" class="text-danger text-decoration-none fw-bold fs-7">عرض جميع الإشعارات</a>
    </div>
  </div>
</div>
 <!-- 2️⃣ المنتصف: الطلبات المقترحة بالذكاء الاصطناعي (مطابق لتصميم Figma) -->
<div class="col-lg-5">
  <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
    <div>
      <h5 class="fw-bold text-dark mb-4 text-center">الطلبات المقترحة بالذكاء الاصطناعي</h5>

      <div class="d-flex flex-column gap-3">

        <!-- 🏥 طلب 1: مستشفى الشفاء -->
        <div class="border rounded-4 p-3 bg-white">
          <div class="d-flex align-items-center justify-content-between dir-ltr">

            <!-- 1️⃣ أقصى اليمين (في الواجهة العربية): صورة المستشفى + النصوص + الزر -->
            <div class="d-flex align-items-start gap-3 text-end dir-rtl">
              <img src="/Rectangle 22872.png" alt="مستشفى الشفاء" class="rounded-3 flex-shrink-0" style="width: 85px; height: 75px; object-fit: cover;" />
              <div>
                <h6 class="fw-bold mb-1 fs-6 text-dark">مستشفى الشفاء</h6>
                <p class="text-muted small mb-1 fs-8">غزة</p>
                <p class="mb-0 fs-8 text-dark">الفصيلة المطلوبة : <strong class="text-danger">A+</strong></p>
                <p class="mb-2 fs-8 text-muted">عدد الوحدات : 2 وحدات</p>

                <button class="btn btn-outline-danger btn-sm rounded-pill fs-8 px-3 py-1">عرض التفاصيل</button>
              </div>
            </div>

            <!-- 2️⃣ أقصى اليسار: الشارة + العداد + كلمة تطابق -->
            <div class="d-flex flex-column align-items-center gap-1 dir-rtl">
              <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1 fs-8 fw-normal">أولوية قصوى</span>
              <img src="/Frame 2147225604@2x.png" alt="تطابق" style="width: 45px; height: 45px; object-fit: contain;" />
              <span class="text-muted fs-8">تطابق مع ملفك</span>
            </div>

          </div>
        </div>

        <!-- 🏥 طلب 2: مستشفى غزة الأوروبي -->
        <div class="border rounded-4 p-3 bg-white">
          <div class="d-flex align-items-center justify-content-between dir-ltr">

            <!-- 1️⃣ أقصى اليمين: الصورة والنصوص والزر -->
            <div class="d-flex align-items-start gap-3 text-end dir-rtl">
              <img src="/Rectangle 22872 (1).png" alt="مستشفى غزة الأوروبي" class="rounded-3 flex-shrink-0" style="width: 85px; height: 75px; object-fit: cover;" />
              <div>
                <h6 class="fw-bold mb-1 fs-6 text-dark">مستشفى غزة الأوروبي</h6>
                <p class="text-muted small mb-1 fs-8">خانيونس</p>
                <p class="mb-0 fs-8 text-dark">الفصيلة المطلوبة : <strong class="text-danger">O-</strong></p>
                <p class="mb-2 fs-8 text-muted">عدد الوحدات : 5 وحدات</p>

                <button class="btn btn-outline-danger btn-sm rounded-pill fs-8 px-3 py-1">عرض التفاصيل</button>
              </div>
            </div>

            <!-- 2️⃣ أقصى اليسار: الشارة والعداد -->
            <div class="d-flex flex-column align-items-center gap-1 dir-rtl">
              <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1 fs-8 fw-normal">أولوية قصوى</span>
              <img src="/Frame 2147225604@2x.png" alt="تطابق" style="width: 45px; height: 45px; object-fit: contain;" />
              <span class="text-muted fs-8">تطابق مع ملفك</span>
            </div>

          </div>
        </div>

        <!-- 🏥 طلب 3: مستشفى شهداء الأقصى -->
        <div class="border rounded-4 p-3 bg-white">
          <div class="d-flex align-items-center justify-content-between dir-ltr">

            <!-- 1️⃣ أقصى اليمين: الصورة والنصوص والزر -->
            <div class="d-flex align-items-start gap-3 text-end dir-rtl">
              <img src="/Rectangle 22872 (2).png" alt="مستشفى شهداء الأقصى" class="rounded-3 flex-shrink-0" style="width: 85px; height: 75px; object-fit: cover;" />
              <div>
                <h6 class="fw-bold mb-1 fs-6 text-dark">مستشفى شهداء الأقصى</h6>
                <p class="text-muted small mb-1 fs-8">دير البلح</p>
                <p class="mb-0 fs-8 text-dark">الفصيلة المطلوبة : <strong class="text-danger">B+</strong></p>
                <p class="mb-2 fs-8 text-muted">عدد الوحدات : 3 وحدات</p>

                <button class="btn btn-outline-danger btn-sm rounded-pill fs-8 px-3 py-1">عرض التفاصيل</button>
              </div>
            </div>

            <!-- 2️⃣ أقصى اليسار: الشارة والعداد -->
            <div class="d-flex flex-column align-items-center gap-1 dir-rtl">
              <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1 fs-8 fw-normal">أولوية قصوى</span>
              <img src="/Frame 2147225604@2x.png" alt="تطابق" style="width: 45px; height: 45px; object-fit: contain;" />
              <span class="text-muted fs-8">تطابق مع ملفك</span>
            </div>

          </div>
        </div>

      </div>
    </div>

    <!-- 🔗 الرابط السفلي -->
    <div class="text-center mt-4">
      <a href="#" class="text-danger text-decoration-none fw-bold fs-7">عرض كل الطلبات</a>
    </div>
  </div>
</div>
  <!-- 3️⃣ أقصى اليسار: بطاقة حالة عدم وجود طلبات طارئة قريبًا -->
  <div class="col-lg-3">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column align-items-center justify-content-center text-center">
      <img src="/Rectangle 22873.png" alt="قائمة الحالات" class="mb-3" style="width: 140px; height: auto; object-fit: contain;" />

      <h6 class="fw-bold text-dark mb-2 fs-6">لا توجد حالات طارئة قريبة حالياً</h6>
      <p class="text-muted small mb-4 fs-7">شكراً لكونك مستعداً دائماً لإنقاذ حياة ❤️</p>

      <button class="btn btn-outline-danger px-4 py-2 rounded-pill fs-7 fw-bold">تصفح جميع الطلبات</button>
    </div>
          </div>

        </div>

      </main>
    </div>

  </div>
</template>
<script setup>
</script>

<style scoped>
.dashboard-wrapper {
  background-color: #f8f9fc;
}

.eligibility-card {
  background: linear-gradient(90deg, #fff0f1, #ffffff);
}

.urgent-notification {
  background-color: #f8f9fc !important;
}

.hospital-name {
  color: #1a1a1e;
}

.hospital-details {
  color: #8c8c8e;
  font-size: 0.75rem;
}

.notification-bell-icon {
  cursor: pointer;
  line-height: 1;
}

.fs-7 { font-size: 0.85rem; }
.fs-8 { font-size: 0.75rem; }

</style>
