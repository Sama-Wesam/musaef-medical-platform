<template>
  <div class="home-view" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">

    <!-- Navbar -->
    <Navbar />

    <!-- Hero Section -->
    <HeroSection />

    <!-- How It Works Section -->
    <section class="how-it-works py-4 py-md-5">
      <div class="container px-3 px-md-4">
        <!-- Section Title -->
        <div class="text-center mb-4 mb-md-5">
          <h2 class="section-title">{{ $t('home.howItWorksTitle') }}</h2>
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
                {{ $t(step.titleKey) }}
              </h5>

              <!-- Description -->
              <p class="step-description">
                {{ $t(step.descKey) }}
              </p>
            </div>

            <!-- Arrow -->
            <div
              v-if="index < steps.length - 1"
              class="step-arrow d-none d-xl-flex"
            >
              <i :class="currentLanguage === 'ar' ? 'bi bi-arrow-left-short' : 'bi bi-arrow-right-short'"></i>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Statistics Section -->
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
                  <!-- مؤشر تحميل خفيف في حال جاري جلب الأرقام الحقيقية لتجنب القيم الصفرية -->
                  <h2 v-if="isLoadingStats" class="placeholder-glow">
                    <span class="placeholder col-6 bg-white opacity-50 rounded"></span>
                  </h2>
                  <h2 v-else>{{ stat.number }}</h2>
                  <p>{{ $t(stat.titleKey) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest Emergency Cases -->
    <section class="latest-cases emergency-section py-4 py-md-5">
      <div class="container px-3 px-md-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 mb-md-5 flex-wrap gap-2 gap-md-3">
          <div class="text-start-dir">
            <h2 class="section-title mb-1">{{ $t('home.urgentTitle') }}</h2>
            <div class="title-underline ms-0 me-auto" v-if="currentLanguage === 'en'"></div>
            <div class="title-underline me-0 ms-auto" v-else></div>
          </div>

          <router-link to="/register" class="view-all fs-8 fs-md-7">
            {{ $t('home.viewAll') }}
            <i :class="currentLanguage === 'ar' ? 'bi bi-chevron-left' : 'bi bi-chevron-right'"></i>
          </router-link>
        </div>

        <!-- Cards -->
        <div class="row g-3 g-md-4" v-if="sortedEmergencyCases.length > 0">
          <div
            class="col-12 col-sm-6 col-xl-3"
            v-for="(item, index) in sortedEmergencyCases"
            :key="index"
          >
            <div class="emergency-card h-100">
              <!-- Card Header -->
              <div class="card-header-top">
                <span class="blood-group" dir="ltr">
                  {{ item.blood }}
                </span>
                <span :class="['urgent-badge', getSeverityClass(item.severity)]">
                  {{ getSeverityText(item.severity, item.urgency_label) }}
                </span>
              </div>

              <!-- Card Body -->
              <div class="card-body-custom text-start-dir">
                <h5 class="hospital-name text-truncate">
                  {{ getLocalizedField(item, 'hospital') }}
                </h5>

                <div class="case-info">
                  <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                  <span class="text-truncate">{{ getLocalizedField(item, 'location') }}</span>
                </div>

                <div class="case-info">
                  <i class="bi bi-droplet-fill me-1 text-danger"></i>
                  <span>{{ $t('home.requiredUnits', { count: item.units }) }}</span>
                </div>

                <div class="case-info">
                  <i class="bi bi-clock-fill me-1 text-danger"></i>
                  <span>{{ item.time }}</span>
                </div>
              </div>

              <!-- Card Footer / Buttons -->
              <div class="card-footer-custom">
                <router-link to="/register" class="btn donate-btn flex-grow-1 text-center">
                  {{ $t('home.donate') }}
                </router-link>

                <button class="btn share-btn" @click="shareCase(item)" :aria-label="$t('home.share')">
                  <i class="bi bi-share-fill"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="text-center py-5 text-muted fs-6 bg-white rounded-4 shadow-sm">
          {{ $t('home.noCases') }}
        </div>
      </div>
    </section>

    <!-- Footer -->
    <Footer />

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import apiClient from '@/api/axios'
import Navbar from '@/components/common/Navbar.vue'
import HeroSection from '@/components/common/HeroSection.vue'
import Footer from '@/components/common/Footer.vue'

const { locale, t } = useI18n()
const currentLanguage = computed(() => locale.value || 'ar')

const steps = [
  {
    number: 1,
    icon: 'bi bi-person-plus',
    titleKey: 'home.step1Title',
    descKey: 'home.step1Desc'
  },
  {
    number: 2,
    icon: 'bi bi-clipboard2-pulse',
    titleKey: 'home.step2Title',
    descKey: 'home.step2Desc'
  },
  {
    number: 3,
    icon: 'bi bi-megaphone',
    titleKey: 'home.step3Title',
    descKey: 'home.step3Desc'
  },
  {
    number: 4,
    icon: 'bi bi-shield-check',
    titleKey: 'home.step4Title',
    descKey: 'home.step4Desc'
  },
  {
    number: 5,
    icon: 'bi bi-geo-alt-fill',
    titleKey: 'home.step5Title',
    descKey: 'home.step5Desc'
  }
]

const statistics = ref([
  {
    number: '...',
    titleKey: 'home.statSupported',
    icon: 'bi bi-heart-pulse-fill'
  },
  {
    number: '...',
    titleKey: 'home.statRequests',
    icon: 'bi bi-droplet-fill'
  },
  {
    number: '...',
    titleKey: 'home.statHospitals',
    icon: 'bi bi-hospital-fill'
  },
  {
    number: '...',
    titleKey: 'home.statDonors',
    icon: 'bi bi-people-fill'
  }
])

const isLoadingStats = ref(true)
const emergencyCases = ref([])

const translationsDictionary = {
  // Hospitals
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex',
  'مستشفى الأهلي العربي (المعمداني)': 'Ahli Arab Hospital (Al-Maamadani)',
  'المستشفى الإندونيسي': 'Indonesian Hospital',
  'بنك الدم المركزي - وزارة الصحة': 'Central Blood Bank - Ministry of Health',
  'مستشفى أصدقاء المريض الخيري': 'Patient\'s Friends Benevolent Society Hospital',
  'جمعية بنك الدم المركزي': 'Central Blood Bank Society',

  // Locations
  'غزة - الرمال': 'Gaza - Rimal',
  'غزة - الزيتون': 'Gaza - Zeitoun',
  'شمال غزة - بيت لاهيا': 'North Gaza - Beit Lahia',
  'غزة - النصر': 'Gaza - Al-Nasr',
  'غزه - فلسطين': 'Gaza - Palestine',
  'غزة - فلسطين': 'Gaza - Palestine',
  'غزه - الرمال شارع الوحده': 'Gaza - Rimal, Al-Wehda St.',
  'غزة - الرمال شارع الوحدة': 'Gaza - Rimal, Al-Wehda St.'
}

const fetchHomeStats = async () => {
  isLoadingStats.value = true
  try {
    const res = await apiClient.get('/public/home-stats')
    const statsData = (res && res.data && res.data.data) ? res.data.data : ((res && res.data) ? res.data : res)
    if (statsData) {
      statistics.value[0].number = (statsData.supported_cases || 0) + '+'
      statistics.value[1].number = (statsData.total_requests || 0) + '+'
      statistics.value[2].number = (statsData.hospitals_count || 0) + '+'
      statistics.value[3].number = (statsData.donors_count || 0) + '+'
    }
  } catch (error) {
    console.error('Error fetching stats:', error)
  } finally {
    isLoadingStats.value = false
  }
}

const fetchUrgentRequests = async () => {
  try {
    const res = await apiClient.get('/public/urgent-requests')

    let casesData = []

    if (Array.isArray(res)) {
      casesData = res
    } else if (res && res.data) {
      if (Array.isArray(res.data)) {
        casesData = res.data
      } else if (res.data.data && Array.isArray(res.data.data)) {
        casesData = res.data.data
      }
    }

    if (casesData.length > 0) {
      emergencyCases.value = casesData.map(req => ({
        blood: req.blood || 'O+',
        hospital: req.hospital || '',
        hospital_ar: req.hospital_ar || req.hospital || '',
        hospital_en: req.hospital_en || translationsDictionary[req.hospital] || req.hospital || '',
        location: req.location || '',
        location_ar: req.location_ar || req.location || '',
        location_en: req.location_en || translationsDictionary[req.location] || req.location || '',
        units: req.units_needed || req.units_required || 1,
        severity: req.severity || 'Critical',
        urgency_label: req.condition_type || '',
        time: req.created_at || 'منذ قليل',
        ai_priority_score: req.ai_priority_score || req.priority_score || 0
      }))
    }
  } catch (error) {
    console.error('Error fetching urgent requests:', error)
  }
}

const sortedEmergencyCases = computed(() => {
  return [...emergencyCases.value].sort((a, b) => {
    return (b.ai_priority_score || 0) - (a.ai_priority_score || 0)
  })
})

const getSeverityText = (severity, fallback) => {
  if (severity === 'Critical') return t('home.veryCritical')
  if (severity === 'High') return t('home.urgent')
  return fallback || t('home.urgent')
}

const getLocalizedField = (item, fieldName) => {
  if (currentLanguage.value === 'en') {
    const rawVal = item[fieldName] || ''
    return item[`${fieldName}_en`] || translationsDictionary[rawVal] || rawVal
  }
  return item[`${fieldName}_ar`] || item[fieldName] || ''
}

const getSeverityClass = (severity) => {
  if (severity === 'Critical') return 'severity-critical'
  if (severity === 'High') return 'severity-high'
  return 'severity-normal'
}

const shareCase = (item) => {
  const hospital = getLocalizedField(item, 'hospital')
  if (navigator.share) {
    navigator.share({
      title: `Emergency Case: ${item.blood}`,
      text: `Blood donation required for ${item.blood} at ${hospital}`,
      url: window.location.href
    }).catch(() => {})
  } else {
    navigator.clipboard.writeText(window.location.href)
    alert(t('home.shareSuccess'))
  }
}

onMounted(() => {
  fetchHomeStats()
  fetchUrgentRequests()
})
</script>

<style scoped>
.home-view,
.home-view * {
  font-family: Arial, sans-serif !important;
}

.home-view {
  background: #ffffff;
  color: #1f2937;
  width: 100%;
  overflow-x: hidden;
}

section {
  overflow: hidden;
}

.container {
  max-width: 1280px;
}

.text-start-dir {
  text-align: right;
}

[dir="ltr"] .text-start-dir {
  text-align: left;
}

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

[dir="ltr"] .step-arrow {
  left: auto;
  right: -24px;
}

.step-arrow i {
  color: #dc2626;
  font-size: 32px;
}

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
  [dir="ltr"] .stats-wrapper .col-lg-3:not(:last-child) {
    border-left: none;
    border-right: 1px solid rgba(255, 255, 255, 0.15);
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
  font-weight: bold;
  padding: 5px 14px;
  border-radius: 50px;
  font-size: 13px;
}

.severity-critical {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fca5a5;
}

.severity-high {
  background: #fdecec;
  color: #dc2626;
}

.severity-normal {
  background: #f3f4f6;
  color: #4b5563;
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
  display: none;
  align-items: center;
  justify-content: center;
  transition: 0.3s;
  flex-shrink: 0;
}

@media (min-width: 768px) {
  .share-btn {
    display: flex;
    width: 45px;
    height: 45px;
  }
}

.share-btn i {
  color: #dc2626;
  font-size: 19px;
}

.share-btn:hover {
  background: #dc2626;
}

.share-btn:hover i {
  color: #fff;
}

.fs-8 { font-size: 0.85rem; }
.fs-7 { font-size: 0.95rem; }

.step-card:hover .step-icon i {
  transform: scale(1.15);
}
</style>
