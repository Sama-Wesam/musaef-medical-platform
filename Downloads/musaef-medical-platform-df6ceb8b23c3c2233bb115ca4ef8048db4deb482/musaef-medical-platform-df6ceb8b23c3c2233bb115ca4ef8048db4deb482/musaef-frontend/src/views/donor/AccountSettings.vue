<template>
  <div class="account-settings-page dir-rtl text-end bg-light min-vh-100 pb-5">

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

    <!-- ========================================== -->
    <!-- 📄 محتوى الصفحة الرئيسي -->
    <!-- ========================================== -->
    <div class="container px-3 px-md-4">

      <!-- عنوان الصفحة: حسابي (على اليمين فوق القائمة) -->
      <div class="mb-4 text-end">
        <h2 class="fw-bold text-dark mb-1">حسابي</h2>
        <p class="text-muted small mb-0">إدارة معلوماتك الشخصية وإعدادات الحساب الشخصية</p>
      </div>

      <div class="row g-4">

        <!-- 📌 1. القائمة الجانبية (يمين) -->
        <div class="col-lg-3">
          <div class="card border border-light-subtle shadow-sm rounded-4 p-2 bg-white">
            <div class="nav flex-column nav-pills gap-2">

              <!-- البيانات الشخصية -->
              <button class="nav-link text-end text-dark py-2 px-3 rounded-3 border-0 bg-transparent x-small d-flex align-items-center justify-content-start gap-2">
                <i class="bi bi-person fs-5"></i>
                <span class="fw-semibold">البيانات الشخصية</span>
              </button>

              <!-- البيانات الصحية (النشط - وردي فاتح) -->
              <button class="nav-link text-end py-2 px-3 rounded-3 border-0 x-small d-flex align-items-center justify-content-start gap-2 active-pink-tab">
                <i class="bi bi-heart-pulse fs-5 text-danger"></i>
                <span class="fw-bold text-danger">البيانات الصحية</span>
              </button>

              <!-- الإعدادات -->
              <button class="nav-link text-end text-dark py-2 px-3 rounded-3 border-0 bg-transparent x-small d-flex align-items-center justify-content-start gap-2">
                <i class="bi bi-gear fs-5"></i>
                <span class="fw-semibold">الإعدادات</span>
              </button>

            </div>
          </div>
        </div>

        <!-- 🩺 2. المحتوى الرئيسي (يسار) -->
        <div class="col-lg-9">

          <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">

            <!-- عنوان "البيانات الصحية" على اليمين تم التعديل هُنا -->
            <div class="d-flex align-items-center justify-content-start gap-2 mb-4">
              <i class="bi bi-heart-pulse text-danger fs-4"></i>
              <h5 class="fw-bold text-dark mb-0">البيانات الصحية</h5>
            </div>

            <!-- كروت الصحة الثلاثة (الكلام على اليمين والأيقونة على اليسار) -->
<div class="row g-3 mb-4 text-end">

  <!-- 1. كارت فصيلة الدم -->
  <div class="col-md-4">
    <div class="health-card p-3 rounded-4 d-flex align-items-center justify-content-between">
      <!-- الكلام والنص جهة اليمين -->
      <div class="text-end">
        <h6 class="fw-bold text-dark mb-1">فصيلة الدم</h6>
        <h3 class="fw-bold mb-0 text-dark">+O</h3>
        <span class="xx-small text-muted">موجب</span>
      </div>
      <!-- الأيقونة جهة اليسار -->
      <img
  src="/blood-icon.png"
  alt="أيقونة فصيلة الدم"
  style="width: 45px; height: 45px; object-fit: contain;"
/>
    </div>
  </div>

  <!-- 2. كارت الوزن -->
  <div class="col-md-4">
    <div class="health-card p-3 rounded-4 d-flex align-items-center justify-content-between">
      <!-- الكلام والنص جهة اليمين -->
      <div class="text-end">
        <h6 class="fw-bold text-dark mb-1">الوزن</h6>
        <h3 class="fw-bold mb-0 text-dark">72 <span class="fs-6 fw-normal">كجم</span></h3>
        <span class="xx-small text-muted">آخر تحديث: 15 يونيو 2024</span>
      </div>
      <!-- الأيقونة جهة اليسار -->
      <!-- صورة أيقونة الميزان الجديدة -->
<img
 src="/donor-card.png.png"
  alt="أيقونة الميزان"
  style="width: 45px; height: 45px; object-fit: contain;"
/>
    </div>
  </div>

  <!-- 3. كارت آخر تبرع -->
  <div class="col-md-4">
    <div class="health-card p-3 rounded-4 d-flex align-items-center justify-content-between">
      <!-- الكلام والنص جهة اليمين -->
      <div class="text-end">
        <h6 class="fw-bold text-dark mb-1">آخر تبرع</h6>
        <h6 class="fw-bold mb-1 text-dark">15 مايو 2024</h6>
        <span class="xx-small text-muted">منذ 45 يوماً</span>
      </div>
      <!-- الأيقونة جهة اليسار -->
      <img src="/Frame 2147225614.png" alt="أيقونة التقويم" style="width: 45px; height: 45px; object-fit: contain;" />
    </div>
  </div>

</div>

         <!-- 2️⃣ استبيان الأهلية الصحية -->
            <div class="survey-section mt-4">

              <!-- عنوان الاستبيان على اليمين أيضاً -->
              <div class="d-flex align-items-center justify-content-start gap-2 mb-1">
                <i class="bi bi-file-earmark-medical text-danger fs-4"></i>
                <h5 class="fw-bold text-dark mb-0">استبيان الأهلية الصحية للتبرع</h5>
              </div>
              <p class="text-end text-muted x-small mb-4">يرجى الإجابة على كافة الأسئلة بدقة لضمان سلامتك وسلامة المتبرع لهم.</p>
              <!-- الأسئلة بأسلوب الأزرار في Figma -->
              <div class="questions-list d-flex flex-column gap-3 mb-4">

                <div v-for="(question, index) in questions" :key="index" class="question-row d-flex align-items-center justify-content-between py-2 border-bottom">

                  <!-- نص السؤال جهة اليمين -->
                  <span class="small text-dark fw-medium text-end pe-2">{{ question.title }}</span>

                  <!-- الأزرار جهة اليسار -->
                  <div class="d-flex align-items-center gap-3">

                    <!-- زر نعم بنفس تصميم Figma (حدود وردية، خلفية بيضاء، دائرة حمراء صغيرة) -->
                    <button
                      class="btn rounded-pill bg-white border border-danger-subtle px-3 py-1 x-small d-flex align-items-center justify-content-between cursor-pointer position-relative"
                      style="width: 100px; height: 32px;"
                      @click="question.answer = 'yes'"
                    >
                      <span class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 22px; height: 22px; font-size: 11px;">
                        لا
                      </span>
                      <span class="fw-bold text-dark flex-grow-1 text-center">نعم</span>
                    </button>

                    <!-- زر لا (كبسولة رمادية خفيفة) -->
                    <button
                      class="btn rounded-pill bg-white border border-secondary-subtle px-3 py-1 x-small text-secondary fw-semibold cursor-pointer text-center"
                      style="width: 80px; height: 32px;"
                      @click="question.answer = 'no'"
                    >
                      لا
                    </button>

                    <!-- أيقونة المعلومات على اليسار -->
                    <i class="bi bi-info-circle text-muted x-small cursor-pointer"></i>

                  </div>

                </div>

              </div>

              <!-- الشريط السفلي -->
              <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 pt-2">

                <!-- كارت الحالة الصحية -->
                <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 px-3 py-2 text-success x-small d-flex align-items-center gap-2 flex-grow-1">
                  <i class="bi bi-check-circle-fill fs-5"></i>
                  <div class="text-end">
                    <span class="fw-bold d-block">حالتك الصحية مؤهلة للتبرع</span>
                    <span class="xx-small text-muted">بناءً على إجاباتك، يمكنك التبرع بالدم بأمان.</span>
                  </div>
                </div>

                <!-- زر حفظ التحديثات -->
                <button class="btn btn-danger px-4 py-2.5 rounded-3 fw-bold x-small d-flex align-items-center justify-content-center gap-2 shadow-sm" style="min-width: 200px;">
                  <span>حفظ التحديثات</span>
                  <i class="bi bi-printer"></i>
                </button>

              </div>

            </div>

          </div>

        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue';

const questions = ref([
  { title: 'هل تعاني حالياً من الحمى؟', answer: 'yes' },
  { title: 'هل تم تشخيصك بأي مرض مناعي أو معدٍ خلال الأشهر الستة الماضية؟', answer: 'yes' },
  { title: 'هل تناولت أي أدوية خلال آخر 48 ساعة؟', answer: 'yes' },
  { title: 'هل أجريت أي عملية جراحية خلال آخر 6 أشهر؟', answer: 'yes' },
  { title: 'هل لديك حساسية من أي أدوية أو أغذية أو مواد طبية؟', answer: 'yes' },
  { title: 'هل تعرضت لأي عدوى أو التهاب خلال أسبوعين؟', answer: 'yes' },
  { title: 'هل نمت جيداً وتناولت طعاماً كافياً خلال آخر 24 ساعة؟', answer: 'yes' },
]);
</script>

<style scoped>
.dir-rtl {
  direction: rtl;
}
.x-small {
  font-size: 0.78rem;
}
.xx-small {
  font-size: 0.65rem;
}
.cursor-pointer {
  cursor: pointer;
}

/* التبويب النشط باللون الوردي المعتمد بالمشروع */
.active-pink-tab {
  background-color: #fff0f3 !important;
  border: 1px solid #ffccd5 !important;
}

/* كروت الصحة */
.health-card {
  border: 1px solid #f0f0f0;
  background-color: #ffffff;
}

.question-row:last-child {
  border-bottom: none !important;
}
</style>
