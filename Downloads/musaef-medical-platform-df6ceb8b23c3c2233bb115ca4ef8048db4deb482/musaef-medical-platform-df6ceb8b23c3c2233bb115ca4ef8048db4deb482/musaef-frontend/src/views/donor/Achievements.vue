<template>
  <div class="dashboard-page text-end pb-5 bg-light min-vh-100" dir="rtl">
    <div class="container px-3 px-md-4 py-3">

      <!-- ========================================== -->
      <!-- 1️⃣ النافبار العلوي -->
      <!-- ========================================== -->
      <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 py-2 border-bottom rounded-4">
        <div class="container px-3 px-md-4 d-flex align-items-center justify-content-between">

          <!-- أقصى اليمين: الملف الشخصي المصغر -->
          <div class="d-flex align-items-center gap-2">
            <img
            src="/image.png"
            alt="صورة المستخدم"
            class="rounded-circle border border-2 border-light shadow-sm"
            style="width: 40px; height: 40px; object-fit: cover;"
          />
            <div class="text-end d-none d-sm-block">
              <h6 class="fw-bold mb-0 x-small">حمزة من غزة</h6>
              <span class="text-muted xx-small">متبرع نشط</span>
            </div>
          </div>

          <!-- المنتصف: شريط البحث + الإشعارات -->
          <div class="d-flex align-items-center gap-3 my-2 my-lg-0 col-12 col-md-5">
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

            <div class="position-relative cursor-pointer flex-shrink-0">
              <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                <i class="bi bi-bell fs-5 text-secondary"></i>
              </div>
              <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                <span class="visually-hidden">تنبيهات جديدة</span>
              </span>
            </div>
          </div>

          <!-- أقصى اليسار: اللغة + اللوجو -->
          <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
              <button class="btn btn-sm text-dark dropdown-toggle fs-8 p-0 border-0 fw-normal" type="button">
                🌐 العربية
              </button>
            </div>
            <img :src="getImg('logo.png')" alt="مسعف Logo" style="height: 36px;">
          </div>

        </div>
      </nav>

      <!-- ========================================== -->
      <!-- 2️⃣ بطاقة المتبرع الذكية (مضبوطة بالملم) -->
      <!-- ========================================== -->
      <div class="donor-card-banner text-white p-4 rounded-4 position-relative shadow-sm mb-4" style="background: linear-gradient(135deg, #8b0000 0%, #5e0000 100%);">
        <div class="row align-items-center">

          <!-- 1. اليمين: فصيلة الدم + الاسم والمدينة -->
          <div class="col-md-5 d-flex align-items-center justify-content-start gap-4">
            <!-- فصيلة الدم والمستوى -->
            <div class="text-end">
              <span class="d-block text-white-50 xx-small mb-1">فصيلة الدم</span>
              <div class="d-flex align-items-center justify-content-end gap-1 mb-1">
                <span class="fs-3 fw-bold lh-1">+O</span>
                <img :src="getImg('blood-drop.png.png')" alt="Blood Drop" style="width: 25px; height: 30px; object-fit: contain;">
              </div>
              <span class="d-block text-white-50 xx-small">المستوى الحالي</span>
              <span class="text-warning fw-bold xx-small">متبرع مبتدئ</span>
            </div>

            <!-- الاسم والمدينة (مباشرة جنب فصيلة الدم ومقتربة من الصورة) -->
            <div class="text-end ms-2">
              <h4 class="fw-bold mb-1 text-nowrap fs-4">حمزة نبيل</h4>
              <p class="text-white-50 mb-0 small text-nowrap">
                <i class="bi bi-geo-alt-fill me-1"></i> غزة - فلسطين
              </p>
            </div>
          </div>

          <!-- 2. المنتصف: الصورة الشخصية -->
          <div class="col-md-2 text-center my-3 my-md-0">
            <div class="position-relative d-inline-block">
              <img
                :src="getImg('user-avatar.png.png')"
                @error="$event.target.src = 'https://via.placeholder.com/150'"
                alt="حمزة نبيل"
                class="rounded-circle border border-3 border-warning shadow"
                style="width: 105px; height: 105px; object-fit: cover;"
              />
            </div>
          </div>

          <!-- 3. اليسار: تفاصيل البطاقة + رمز QR -->
          <div class="col-md-5 d-flex align-items-center justify-content-end gap-3">
            <!-- تفاصيل المتبرع -->
            <div class="text-end me-2">
              <h6 class="fw-bold text-white mb-1 xx-small">بطاقة متبرع ذكية</h6>
              <span class="d-block text-white-50 xx-small mb-1">رقم المتبرع</span>
              <span class="text-white xx-small fw-bold d-block mb-1">BD123456789</span>
              <span class="badge border border-light-subtle rounded-pill px-2 py-1 fw-normal bg-white bg-opacity-10 xx-small">
                <i class="bi bi-star-fill text-warning me-1"></i> متبرع نشط
              </span>
            </div>

            <!-- QR Code -->
            <div class="bg-white bg-opacity-10 p-2 rounded-3 text-center border border-white-10" style="width: 90px;">
              <div class="bg-white rounded-3 p-1 mb-2 d-flex align-items-center justify-content-center mx-auto" style="width: 100%; height: 55px;">
                <i class="bi bi-qr-code-scan fs-2 text-dark"></i>
              </div>
              <button class="btn btn-sm btn-outline-light w-100 fw-bold rounded-2 xx-small py-1" style="font-size: 10px;">تحميل</button>
            </div>
          </div>

        </div>
      </div>
      <!-- ========================================== -->
      <!-- 3️⃣ كروت الإحصائيات (الأثر الإنساني | النقاط والمكافآت) -->
      <!-- ========================================== -->
      <div class="row g-3 mb-4">

        <!-- الأثر الإنساني (يمين) -->
        <div class="col-md-6">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
            <h6 class="fw-bold text-secondary mb-3 text-center">الأثر الإنساني</h6>
            <div class="row text-center my-auto align-items-center">

              <!-- حالة تم دعمها (اليمين) -->
              <div class="col-6 border-start">
                <img :src="getImg('hands.png.png')" alt="Hands Heart" style="width: 75px; height: 75px; object-fit: contain;" class="mb-1">
                <h3 class="fw-bold mb-0 fs-2">12</h3>
                <span class="text-muted x-small">حالة تم دعمها</span>
              </div>

              <!-- وحدات الدم المتبرع بها (اليسار - صورة bloo.png) -->
              <div class="col-6">
                <img :src="getImg('bloo.png')" alt="Blood Drop" style="width: 65px; height: 75px; object-fit: contain;" class="mb-1">
                <h3 class="fw-bold mb-0 fs-2">8</h3>
                <span class="text-muted x-small">وحدات الدم المتبرع بها</span>
              </div>

            </div>
            <div class="bg-danger bg-opacity-10 rounded-3 p-2 text-center mt-3">
              <span class="text-danger small fw-bold">بفضلِك، تستمر الحياة في الأمل</span>
            </div>
          </div>
        </div>

        <!-- النقاط والمكافآت (يسار) -->
        <div class="col-md-6">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
            <h6 class="fw-bold text-secondary mb-3 text-center">النقاط والمكافآت</h6>
            <div class="d-flex align-items-center justify-content-center gap-3 my-auto">
              <img :src="getImg('coins.png.png')" alt="Gold Coins" style="width: 90px; height: 90px; object-fit: contain;">
              <div class="text-start">
                <h2 class="fw-bold text-danger mb-0 fs-1">350</h2>
                <span class="text-muted x-small d-block">نقطة إجمالية</span>
              </div>
            </div>
            <div class="mt-3">
              <div class="d-flex justify-content-between x-small text-muted mb-1">
                <span>150 نقطة حتى المستوى التالي</span>
                <span>500</span>
              </div>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 70%"></div>
              </div>
            </div>
            <button class="btn btn-sm btn-outline-danger w-100 rounded-pill mt-3 py-2 fw-bold">استبدال نقاطك</button>
          </div>
        </div>

      </div>

      <!-- ========================================== -->
      <!-- 4️⃣ شارات الإنجاز -->
      <!-- ========================================== -->
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <h6 class="fw-bold text-secondary mb-4 text-center">شارات الإنجاز</h6>
        <div class="row text-center g-3">

          <div class="col-6 col-md-3">
            <div class="p-2">
              <img :src="getImg('badge-hero.png.png')" alt="منقذ حياة" style="width: 95px; height: 95px; object-fit: contain;" class="mb-2">
              <h6 class="fw-bold mb-1 fs-6">منقذ حياة</h6>
              <span class="text-muted x-small d-block">تم إنقاذ أكثر من 10 حالات</span>
              <span class="text-muted xx-small">1 يونيو 2024</span>
            </div>
          </div>

          <div class="col-6 col-md-3">
            <div class="p-2">
              <img :src="getImg('badge-10.png.png')" alt="10 تبرعات" style="width: 95px; height: 95px; object-fit: contain;" class="mb-2">
              <h6 class="fw-bold mb-1 fs-6">10 تبرعات</h6>
              <span class="text-muted x-small d-block">تم إنجاز 10 تبرعات</span>
              <span class="text-muted xx-small">20 مايو 2025</span>
            </div>
          </div>

          <div class="col-6 col-md-3">
            <div class="p-2">
              <img :src="getImg('badge-5.png.png')" alt="5 تبرعات" style="width: 95px; height: 95px; object-fit: contain;" class="mb-2">
              <h6 class="fw-bold mb-1 fs-6">5 تبرعات</h6>
              <span class="text-muted x-small d-block">تم إنجاز 5 تبرعات</span>
              <span class="text-muted xx-small">10 أبريل 2024</span>
            </div>
          </div>

          <div class="col-6 col-md-3">
            <div class="p-2">
              <img :src="getImg('badge-1.png.png')" alt="أول تبرع" style="width: 95px; height: 95px; object-fit: contain;" class="mb-2">
              <h6 class="fw-bold mb-1 fs-6">أول تبرع</h6>
              <span class="text-muted x-small d-block">تم إنجاز أول تبرع</span>
              <span class="text-muted xx-small">15 مارس 2024</span>
            </div>
          </div>

        </div>
      </div>

      <!-- ========================================== -->
      <!-- 5️⃣ سجل التبرعات -->
      <!-- ========================================== -->
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h6 class="fw-bold text-secondary mb-4 text-center">سجل التبرعات</h6>

        <div class="table-responsive">
          <table class="table table-hover align-middle text-center x-small mb-0">
            <thead class="table-light">
              <tr>
                <th scope="col">التاريخ</th>
                <th scope="col">المستشفى</th>
                <th scope="col">فصيلة الدم</th>
                <th scope="col">الوحدات</th>
                <th scope="col">الحالة</th>
                <th scope="col">النقاط المكتسبة</th>
              </tr>
            </thead>
           <tbody>
              <tr>
                <td>10 مايو 2024</td>
                <td>مجمع الشفاء الطبي - مدينة غزة</td>
                <td><span class="badge bg-danger-subtle text-secondary px-2 py-1" style="direction: ltr; display: inline-block;">A+</span></td>
                <td>2</td>
                <td><span class="badge bg-success-subtle text-success px-3 py-1">مكتمل</span></td>
                <td class="fw-bold text-secondary" style="direction: ltr; display: inline-block;">+100</td>
              </tr>
              <tr>
                <td>22 مارس 2024</td>
                <td>مجمع ناصر الطبي - خان يونس</td>
                <td><span class="badge bg-danger-subtle text-secondary px-2 py-1" style="direction: ltr; display: inline-block;">O+</span></td>
                <td>5</td>
                <td><span class="badge bg-danger-subtle text-danger px-3 py-1">عاجلة</span></td>
                <td class="fw-bold text-secondary" style="direction: ltr; display: inline-block;">-5</td>
              </tr>
              <tr>
                <td>15 يناير 2024</td>
                <td>مستشفى شهداء الأقصى - دير البلح</td>
                <td><span class="badge bg-danger-subtle text-secondary px-2 py-1" style="direction: ltr; display: inline-block;">B+</span></td>
                <td>1</td>
                <td><span class="badge bg-success-subtle text-success px-3 py-1">مكتمل</span></td>
                <td class="fw-bold text-secondary" style="direction: ltr; display: inline-block;">+90</td>
              </tr>

              <template v-if="showAllRecords">
                <tr>
                  <td>05 ديسمبر 2023</td>
                  <td>مستشفى الإندونيسي - شمال غزة</td>
                  <td><span class="badge bg-danger-subtle text-secondary px-2 py-1" style="direction: ltr; display: inline-block;">O+</span></td>
                  <td>1</td>
                  <td><span class="badge bg-success-subtle text-success px-3 py-1">مكتمل</span></td>
                  <td class="fw-bold text-secondary" style="direction: ltr; display: inline-block;">+80</td>
                </tr>
                <tr>
                  <td>18 نوفمبر 2023</td>
                  <td>مستشفى الأوروبي - خان يونس</td>
                  <td><span class="badge bg-danger-subtle text-secondary px-2 py-1" style="direction: ltr; display: inline-block;">AB+</span></td>
                  <td>2</td>
                  <td><span class="badge bg-success-subtle text-success px-3 py-1">مكتمل</span></td>
                  <td class="fw-bold text-secondary" style="direction: ltr; display: inline-block;">+110</td>
                </tr>
                <tr>
                  <td>02 أكتوبر 2023</td>
                  <td>مستشفى القدس - تل الهوا</td>
                  <td><span class="badge bg-danger-subtle text-secondary px-2 py-1" style="direction: ltr; display: inline-block;">A+</span></td>
                  <td>1</td>
                  <td><span class="badge bg-success-subtle text-success px-3 py-1">مكتمل</span></td>
                  <td class="fw-bold text-secondary" style="direction: ltr; display: inline-block;">+75</td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>

        <div class="text-center mt-3">
          <button
            @click="showAllRecords = !showAllRecords"
            class="btn btn-link text-danger text-decoration-none x-small fw-bold d-inline-flex align-items-center gap-1"
          >
            <span>{{ showAllRecords ? 'إخفاء بقية السجلات' : 'عرض كل سجلات التبرعات' }}</span>
            <i :class="['bi', showAllRecords ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
          </button>
        </div>

      </div>

      </div>

    </div>

</template>

<script setup>
import { ref } from 'vue'

const showAllRecords = ref(false)

const getImg = (name) => {
  return '/' + name
}
</script>

<style scoped>
.x-small {
  font-size: 0.85rem;
}
.xx-small {
  font-size: 0.75rem;
}
.border-white-10 {
  border-color: rgba(255, 255, 255, 0.15) !important;
}
.bg-danger-subtle {
  background-color: #fde8e8 !important;
}
.bg-success-subtle {
  background-color: #e6f4ea !important;
}
</style>
