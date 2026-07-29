<template>
  <div class="home-view" dir="rtl">

    <!-- ===========================
            Navbar
    ============================ -->
    <Navbar />

    <!-- ===========================
            Hero Section
    ============================ -->
    <HeroSection />

    <!-- ==================================
            How It Works Section
    =================================== -->
    <section class="how-it-works py-4 py-md-5">
      <div class="container px-3 px-md-4">
        <!-- Section Title -->
        <div class="text-center mb-4 mb-md-5">
          <h2 class="section-title">كيف تعمل المنصة؟!</h2>
          <div class="title-underline"></div>
        </div>

        <!-- Steps -->
        <div class="row justify-content-center g-3 g-md-4">
          <div
            class="col-12 col-sm-6 col-lg-4 col-xl"
            v-for="(step, index) in steps"
            :key="index"
          >
            <div class="step-card position-relative h-100">
              <!-- Step Number -->
              <div class="step-badge">
                {{ step.number }}
              </div>

              <!-- Icon -->
              <div class="step-icon">
                <i :class="step.icon"></i>
              </div>

              <!-- Title -->
              <h5 class="step-title">
                {{ step.title }}
              </h5>

              <!-- Description -->
              <p class="step-description">
                {{ step.description }}
              </p>
            </div>

            <!-- Arrow -->
            <div
              v-if="index < steps.length - 1"
              class="step-arrow d-none d-xl-flex"
            >
              <i class="bi bi-arrow-left-short"></i>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ==================================
            Statistics Section
    =================================== -->
    <section class="stats-section py-4 py-md-5">
      <div class="container px-2 px-md-3">
        <div class="stats-wrapper">
          <div class="row g-0 text-center">
            <div
              class="col-12 col-sm-6 col-lg-3"
              v-for="(stat, index) in statistics"
              :key="index"
            >
              <div class="stat-item">
                <div class="stat-icon">
                  <i :class="stat.icon"></i>
                </div>
                <div class="stat-content">
                  <h2>{{ stat.number }}</h2>
                  <p>{{ stat.title }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ==================================
            Latest Emergency Cases
    =================================== -->
    <section class="latest-cases emergency-section py-4 py-md-5">
      <div class="container px-3 px-md-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 mb-md-5 flex-wrap gap-2 gap-md-3">
          <div class="text-end">
            <h2 class="section-title mb-1">أحدث الحالات الطارئة</h2>
            <div class="title-underline me-0 ms-auto"></div>
          </div>

          <router-link to="/register" class="view-all fs-8 fs-md-7">
            عرض جميع الحالات
            <i class="bi bi-chevron-left"></i>
          </router-link>
        </div>

        <!-- Cards -->
        <div class="row g-3 g-md-4" v-if="emergencyCases.length > 0">
          <div
            class="col-12 col-sm-6 col-xl-3"
            v-for="(item, index) in emergencyCases"
            :key="index"
          >
            <div class="emergency-card h-100">
              <!-- Card Header -->
              <div class="card-header-top">
                <span class="blood-group">
                  {{ item.blood }}
                </span>
                <span class="urgent-badge">
                  {{ item.urgency_label || 'عاجل' }}
                </span>
              </div>

              <!-- Card Body -->
              <div class="card-body-custom">
                <h5 class="hospital-name text-truncate">
                  {{ item.hospital }}
                </h5>

                <div class="case-info">
                  <i class="bi bi-geo-alt-fill"></i>
                  <span class="text-truncate">{{ item.location }}</span>
                </div>

                <div class="case-info">
                  <i class="bi bi-droplet-fill"></i>
                  <span>مطلوب: {{ item.units }} وحدات دم</span>
                </div>

                <div class="case-info">
                  <i class="bi bi-clock-fill"></i>
                  <span>{{ item.time }}</span>
                </div>
              </div>

              <!-- Card Footer / Buttons -->
              <div class="card-footer-custom">
                <router-link to="/register" class="btn donate-btn flex-grow-1 text-center">
                  تبرع الآن
                </router-link>

                <button class="btn share-btn" @click="shareCase(item)" aria-label="مشاركة">
                  <i class="bi bi-share-fill"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="text-center py-5 text-muted fs-6 bg-white rounded-4 shadow-sm">
          لا توجد حالات طارئة حالياً.
        </div>
      </div>
    </section>

    <!-- Footer -->
    <Footer />

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import apiClient from '@/api/axios' // استدعاء مثيل الـ Axios المخصص للمشروع
import Navbar from '@/components/common/Navbar.vue'
import HeroSection from '@/components/common/HeroSection.vue'
import Footer from '@/components/common/Footer.vue'

/* ===========================
      خطوات عمل المنصة
=========================== */
const steps = [
  {
    number: 1,
    icon: 'bi bi-person-plus',
    title: 'التسجيل في المنصة',
    description: 'إنشاء حساب المتبرع وتحديد موقعك.'
  },
  {
    number: 2,
    icon: 'bi bi-clipboard2-pulse',
    title: 'إكمال الملف الصحي',
    description: 'إدخال بياناتك الصحية بأمان وسرية.'
  },
  {
    number: 3,
    icon: 'bi bi-megaphone',
    title: 'استقبال طلبات التبرع',
    description: 'استقبال الطلبات القريبة حسب فصيلة دمك.'
  },
  {
    number: 4,
    icon: 'bi bi-shield-check',
    title: 'المطابقة الذكية',
    description: 'نظام ذكي يطابق بين المتبرع والمحتاج.'
  },
  {
    number: 5,
    icon: 'bi bi-geo-alt-fill',
    title: 'التوجه لمركز التبرع',
    description: 'التوجه للمركز المحدد للتبرع وإنقاذ حياة.'
  }
]

/* ===========================
      الإحصائيات
=========================== */
const statistics = ref([
  {
    number: '0',
    title: 'عدد الحالات التي تم دعمها',
    icon: 'bi bi-heart-pulse-fill'
  },
  {
    number: '0',
    title: 'عدد طلبات التبرع',
    icon: 'bi bi-droplet-fill'
  },
  {
    number: '0',
    title: 'عدد المستشفيات',
    icon: 'bi bi-hospital-fill'
  },
  {
    number: '0',
    title: 'عدد المتبرعين المسجلين',
    icon: 'bi bi-people-fill'
  }
])

/* ===========================
      أحدث الحالات
=========================== */
const emergencyCases = ref([])

const fetchHomeStats = async () => {
  try {
    const res = await apiClient.get('/public/home-stats')
    if (res && res.data) {
      const stats = res.data
      statistics.value[0].number = (stats.supported_cases || 0) + '+'
      statistics.value[1].number = (stats.total_requests || 0) + '+'
      statistics.value[2].number = (stats.hospitals_count || 0) + '+'
      statistics.value[3].number = (stats.donors_count || 0) + '+'
    }
  } catch (error) {
    console.error('خطأ في جلب الإحصائيات:', error)
  }
}

const fetchUrgentRequests = async () => {
  try {
    const res = await apiClient.get('/public/urgent-requests')
    if (res && res.data) {
      emergencyCases.value = res.data.map(req => ({
        blood: req.blood_type || req.blood,
        hospital: req.hospital_name || req.hospital || 'مستشفى غير محدد',
        location: req.location || 'غير محدد',
        units: req.units_needed || req.units || 1,
        urgency_label: req.urgency_label || 'عاجل',
        time: req.time_ago || req.created_at_human || 'منذ فترة قصيرة'
      }))
    }
  } catch (error) {
    console.error('خطأ في جلب الحالات الطارئة:', error)
  }
}

const shareCase = (item) => {
  if (navigator.share) {
    navigator.share({
      title: `حالة تبرع عاجلة: ${item.blood}`,
      text: `مطلوب تبرع بالدم فصيلة ${item.blood} في ${item.hospital}`,
      url: window.location.href
    }).catch(() => {})
  } else {
    navigator.clipboard.writeText(window.location.href)
    alert('تم نسخ رابط الصفحة لمشاركته!')
  }
}

onMounted(() => {
  fetchHomeStats()
  fetchUrgentRequests()
})
</script>

<style scoped>
/* ==========================================
   General
========================================== */
.home-view {
  direction: rtl;
  font-family: Arial, sans-serif;
  background: #ffffff;
  color: #1f2937;
}

section {
  overflow: hidden;
}

.container {
  max-width: 1280px;
}

/* ==========================================
   Section Title
========================================== */
.section-title {
  font-size: 26px;
  font-weight: 800;
  color: #111827;
  margin-bottom: 10px;
}

@media (min-width: 768px) {
  .section-title {
    font-size: 34px;
    margin-bottom: 14px;
  }
}

.title-underline {
  width: 70px;
  height: 4px;
  background: #dc2626;
  border-radius: 50px;
  margin: auto;
}

@media (min-width: 768px) {
  .title-underline {
    width: 90px;
    height: 5px;
  }
}

/* ==========================================
      HOW IT WORKS
========================================== */
.how-it-works {
  background: #ffffff;
}

.step-card {
  background: #ffffff;
  border: 1px solid #ececec;
  border-radius: 22px;
  padding: 32px 20px;
  text-align: center;
  transition: 0.3s;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
  position: relative;
  height: 100%;
}

@media (min-width: 768px) {
  .step-card {
    padding: 40px 28px;
  }
}

.step-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 18px 35px rgba(0, 0, 0, 0.09);
}

.step-badge {
  position: absolute;
  top: -18px;
  left: 50%;
  transform: translateX(-50%);
  width: 36px;
  height: 36px;
  background: #dc2626;
  color: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 16px;
  box-shadow: 0 6px 15px rgba(220, 38, 38, 0.35);
}

@media (min-width: 768px) {
  .step-badge {
    width: 40px;
    height: 40px;
    font-size: 18px;
  }
}

.step-icon {
  width: 64px;
  height: 64px;
  margin: auto;
  margin-bottom: 20px;
  background: #fff5f5;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

@media (min-width: 768px) {
  .step-icon {
    width: 80px;
    height: 80px;
    margin-bottom: 25px;
  }
}

.step-icon i {
  color: #dc2626;
  font-size: 28px;
  transition: 0.35s;
}

@media (min-width: 768px) {
  .step-icon i {
    font-size: 36px;
  }
}

.step-title {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 12px;
}

@media (min-width: 768px) {
  .step-title {
    font-size: 21px;
    margin-bottom: 15px;
  }
}

.step-description {
  color: #6b7280;
  line-height: 1.8;
  font-size: 14px;
}

@media (min-width: 768px) {
  .step-description {
    font-size: 15px;
    line-height: 1.9;
  }
}

.step-arrow {
  position: absolute;
  top: 50%;
  left: -24px;
  transform: translateY(-50%);
  z-index: 2;
}

.step-arrow i {
  color: #dc2626;
  font-size: 32px;
}

/* ==========================================
      Statistics
========================================== */
.stats-section {
  background: #ffffff;
}

.stats-wrapper {
  background: #dc2626;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 15px 35px rgba(220, 38, 38, 0.25);
}

@media (min-width: 768px) {
  .stats-wrapper {
    border-radius: 28px;
  }
}

.stat-item {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 16px;
  padding: 24px 15px;
  color: #fff;
  transition: all 0.35s ease;
}

@media (min-width: 768px) {
  .stat-item {
    padding: 38px 15px;
    gap: 18px;
  }
}

.stat-item:hover {
  background: rgba(255, 255, 255, 0.06);
}

.stat-icon {
  width: 54px;
  height: 54px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

@media (min-width: 768px) {
  .stat-icon {
    width: 70px;
    height: 70px;
  }
}

.stat-icon i {
  font-size: 24px;
}

@media (min-width: 768px) {
  .stat-icon i {
    font-size: 30px;
  }
}

.stat-content h2 {
  font-size: 26px;
  font-weight: 800;
  margin-bottom: 4px;
}

@media (min-width: 768px) {
  .stat-content h2 {
    font-size: 34px;
    margin-bottom: 6px;
  }
}

.stat-content p {
  margin: 0;
  font-size: 13px;
  opacity: 0.92;
}

@media (min-width: 768px) {
  .stat-content p {
    font-size: 15px;
  }
}

@media (min-width: 992px) {
  .stats-wrapper .col-lg-3:not(:last-child) {
    border-left: 1px solid rgba(255, 255, 255, 0.15);
  }
}

@media (max-width: 991px) {
  .stats-wrapper .col-12 {
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
  }
  .stats-wrapper .col-12:last-child {
    border-bottom: none;
  }
}

/* =====================================================
                Latest Emergency Cases
===================================================== */
.emergency-section {
  background: #f8fafc;
}

.view-all {
  color: #dc2626;
  text-decoration: none;
  font-weight: 700;
  transition: 0.25s;
}

.view-all:hover {
  color: #b91c1c;
}

.emergency-card {
  background: #fff;
  border-radius: 22px;
  border: 1px solid #ececec;
  overflow: hidden;
  transition: all 0.35s ease;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
}

.emergency-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 18px 35px rgba(0, 0, 0, 0.08);
}

.card-header-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 18px 20px;
}

@media (min-width: 768px) {
  .card-header-top {
    padding: 22px 24px;
  }
}

.urgent-badge {
  background: #fdecec;
  color: #dc2626;
  font-weight: bold;
  padding: 5px 14px;
  border-radius: 50px;
  font-size: 13px;
}

.blood-group {
  color: #dc2626;
  font-size: 28px;
  font-weight: 800;
}

@media (min-width: 768px) {
  .blood-group {
    font-size: 34px;
  }
}

.card-body-custom {
  padding: 0 20px 20px;
  flex-grow: 1;
}

@media (min-width: 768px) {
  .card-body-custom {
    padding: 0 24px 24px;
  }
}

.hospital-name {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  margin-bottom: 12px;
}

@media (min-width: 768px) {
  .hospital-name {
    font-size: 20px;
    margin-bottom: 15px;
  }
}

.case-info {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #6b7280;
  font-size: 14px;
  margin-bottom: 10px;
}

.case-info i {
  color: #dc2626;
  flex-shrink: 0;
}

.card-footer-custom {
  padding: 16px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
  border-top: 1px solid #efefef;
}

@media (min-width: 768px) {
  .card-footer-custom {
    padding: 20px 24px;
  }
}

.donate-btn {
  border: 2px solid #dc2626;
  color: #dc2626;
  background: #fff;
  border-radius: 999px;
  padding: 8px 20px;
  font-weight: bold;
  transition: 0.3s;
  text-decoration: none;
  font-size: 14px;
}

@media (min-width: 768px) {
  .donate-btn {
    padding: 10px 28px;
    font-size: 16px;
  }
}

.donate-btn:hover {
  background: #dc2626;
  color: #fff;
}

.share-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 1px solid #ececec;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: 0.3s;
  flex-shrink: 0;
}

@media (min-width: 768px) {
  .share-btn {
    width: 45px;
    height: 45px;
  }
}

.share-btn i {
  color: #dc2626;
  font-size: 16px;
}

@media (min-width: 768px) {
  .share-btn i {
    font-size: 19px;
  }
}

.share-btn:hover {
  background: #dc2626;
}

.share-btn:hover i {
  color: #fff;
}

.fs-8 { font-size: 0.85rem; }
.fs-7 { font-size: 0.95rem; }

/* Animations */
.step-card:hover .step-icon i {
  transform: scale(1.15);
}
</style>
