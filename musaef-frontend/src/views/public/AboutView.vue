<template>
  <div class="about-page d-flex flex-column min-vh-100" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <!-- Navbar -->
    <Navbar />

    <!-- 1. قسم الهيرو (من نحن) -->
    <section class="hero-about-section py-5">
      <div class="container-fluid p-0">
        <div class="row g-0 align-items-center hero-row">

          <!-- النص الوصفي -->
          <div class="col-lg-6 hero-content d-flex align-items-center justify-content-end px-3 px-md-5">
            <div class="hero-text-wrapper">
              <h1 class="fw-bold hero-title mb-3">
                {{ $t('about.title') }}<span class="text-danger">...</span>
              </h1>
              <p class="hero-description text-secondary" v-html="$t('about.descriptionHtml')"></p>
            </div>
          </div>

          <!-- صورة الهيرو -->
          <div class="col-lg-6 hero-image-col">
            <div class="hero-image-wrapper">
              <img
                :src="getImageUrl('about-doctor.png')"
                :alt="$t('about.title')"
                class="hero-about-img"
                @error="handleImgFallback"
              />
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- 2. بطاقات (رؤيتنا، رسالتنا، أهدافنا) -->
    <section class="py-4 py-md-5 bg-light-gray">
      <div class="container px-3 px-md-4">
        <div class="row g-3 g-md-4 justify-content-center">

          <!-- رؤيتنا -->
          <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center rounded-4 h-100 bg-white main-info-card">
              <div class="icon-circle bg-pink-light text-danger mx-auto mb-3">
                <i class="bi bi-eye-fill fs-4"></i>
              </div>
              <h4 class="fw-bold text-danger mb-3 fs-5 fs-md-4">{{ $t('about.visionTitle') }}</h4>
              <p class="text-secondary fs-7 mb-0 lh-lg">
                {{ $t('about.visionDesc') }}
              </p>
            </div>
          </div>

          <!-- رسالتنا -->
          <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center rounded-4 h-100 bg-white main-info-card">
              <div class="icon-circle bg-pink-light text-danger mx-auto mb-3">
                <i class="bi bi-envelope-fill fs-4"></i>
              </div>
              <h4 class="fw-bold text-danger mb-3 fs-5 fs-md-4">{{ $t('about.missionTitle') }}</h4>
              <p class="text-secondary fs-7 mb-0 lh-lg">
                {{ $t('about.missionDesc') }}
              </p>
            </div>
          </div>

          <!-- أهدافنا -->
          <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center rounded-4 h-100 bg-white main-info-card">
              <div class="icon-circle bg-pink-light mx-auto mb-3">
                <img
                  :src="getIconUrl('Frame 2147225414.png')"
                  :alt="$t('about.goalsTitle')"
                  class="goal-icon"
                />
              </div>
              <h4 class="fw-bold text-danger mb-3 fs-5 fs-md-4">{{ $t('about.goalsTitle') }}</h4>
              <p class="text-secondary fs-7 mb-0 lh-lg">
                {{ $t('about.goalsDesc') }}
              </p>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- 3. مميزات المنصة -->
    <section id="features" class="py-4 py-md-5 bg-white">
      <div class="container text-center px-3 px-md-4">
        <h3 class="fw-bold text-dark section-title-center mb-1 fs-4 fs-md-3">{{ $t('about.featuresTitle') }}</h3>
        <div class="title-red-line mx-auto mb-4 mb-md-5"></div>

        <div class="row g-3 justify-content-center">
          <div v-for="(feature, idx) in features" :key="idx" class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="p-3 border rounded-4 bg-white h-100 feature-card shadow-sm d-flex flex-column justify-content-center align-items-center text-center">
              <div class="feature-icon mb-2 text-danger fs-2">
                <i :class="feature.icon"></i>
              </div>
              <h6 class="fw-bold fs-7 mb-2 text-dark">{{ $t(feature.titleKey) }}</h6>
              <p class="text-muted fs-8 mb-0 lh-base">{{ $t(feature.descKey) }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. التقييمات والآراء -->
    <section id="reviews" class="py-4 py-md-5 bg-light-gray">
      <div class="container text-center px-3 px-md-4">
        <h3 class="fw-bold text-dark section-title-center mb-1 fs-4 fs-md-3">{{ $t('about.reviewsTitle') }}</h3>
        <div class="title-red-line mx-auto mb-4 mb-md-5"></div>

        <div class="row g-3 g-md-4">
          <div v-for="(review, idx) in reviews" :key="idx" class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-start-dir review-card d-flex flex-column justify-content-between">
              <p class="text-dark fw-medium fs-7 mb-4 lh-lg">"{{ $t(review.textKey) }}"</p>

              <div class="d-flex align-items-center gap-3 mt-auto pt-3 border-top border-light-subtle">
                <img
                  :src="getImageUrl(review.avatar)"
                  class="rounded-circle avatar-img flex-shrink-0"
                  :alt="$t(review.nameKey)"
                  @error="handleAvatarFallback"
                />
                <div class="min-w-0">
                  <h6 class="fw-bold mb-0.5 text-dark fs-7 text-truncate">{{ $t(review.nameKey) }}</h6>
                  <small class="text-muted fs-8 text-truncate d-block">{{ $t(review.roleKey) }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. بالتعاون مع -->
    <section id="partners" class="py-5 bg-white">
      <div class="container text-center px-3 px-md-4">
        <h3 class="fw-bold text-dark section-title-center mb-1 fs-4 fs-md-3">{{ $t('about.partnersTitle') }}</h3>
        <div class="title-red-line mx-auto mb-4 mb-md-5"></div>

        <div class="row g-3 justify-content-center align-items-stretch" v-if="localizedPartners.length > 0">
          <div v-for="partner in localizedPartners" :key="partner.id || partner.facility_name" class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="partner-card p-3 border rounded-4 bg-white shadow-sm d-flex align-items-center gap-3 h-100">
              <div class="partner-icon-wrapper rounded-circle bg-pink-light text-danger flex-shrink-0 d-flex align-items-center justify-content-center">
                <i class="bi bi-hospital fs-4"></i>
              </div>
              <div class="text-start-dir min-w-0 flex-grow-1 overflow-hidden">
                <h6 class="partner-name fw-bold fs-7 text-dark mb-1" :title="partner.facility_name || partner.name">
                  {{ partner.facility_name || partner.name }}
                </h6>
                <small class="partner-address text-muted fs-8 d-block">
                  <i class="bi bi-geo-alt me-1 text-danger"></i>{{ partner.address || $t('about.defaultLocation') }}
                </small>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="text-muted py-4">
          <div class="spinner-border text-danger spinner-border-sm me-2" role="status"></div>
          <span>{{ $t('about.loadingPartners') }}</span>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import apiClient from '@/api/axios';
import Navbar from '@/components/common/Navbar.vue';
import Footer from '@/components/common/Footer.vue';

const { locale, te, t } = useI18n();
const currentLanguage = computed(() => locale.value || 'ar');

const getImageUrl = (name) => {
  return new URL(`../../assets/images/${name}`, import.meta.url).href;
};

const getIconUrl = (name) => {
  return new URL(`../../assets/icons/${name}`, import.meta.url).href;
};

const features = ref([
  { titleKey: 'about.features.predict.title', descKey: 'about.features.predict.desc', icon: 'bi bi-graph-up-arrow' },
  { titleKey: 'about.features.inventory.title', descKey: 'about.features.inventory.desc', icon: 'bi bi-droplet' },
  { titleKey: 'about.features.rewards.title', descKey: 'about.features.rewards.desc', icon: 'bi bi-gift' },
  { titleKey: 'about.features.alerts.title', descKey: 'about.features.alerts.desc', icon: 'bi bi-bell' },
  { titleKey: 'about.features.maps.title', descKey: 'about.features.maps.desc', icon: 'bi bi-geo-alt' },
  { titleKey: 'about.features.matching.title', descKey: 'about.features.matching.desc', icon: 'bi bi-magic' }
]);

const reviews = ref([
  {
    nameKey: 'about.reviews.r1.name',
    roleKey: 'about.reviews.r1.role',
    textKey: 'about.reviews.r1.text',
    avatar: 'review-ahmed.png'
  },
  {
    nameKey: 'about.reviews.r2.name',
    roleKey: 'about.reviews.r2.role',
    textKey: 'about.reviews.r2.text',
    avatar: 'review-maryam.png'
  },
  {
    nameKey: 'about.reviews.r3.name',
    roleKey: 'about.reviews.r3.role',
    textKey: 'about.reviews.r3.text',
    avatar: 'shifa-hospital.png'
  }
]);

const defaultHospitalsKeys = [
  { id: 1, name: 'مجمع الشفاء الطبي', address: 'غزة - الرمال' },
  { id: 2, name: 'جمعية بنك الدم المركزي', address: 'غزة - الرمال شارع الوحدة' },
  { id: 3, name: 'بنك الدم المركزي - وزارة الصحة', address: 'غزة - النصر' },
  { id: 4, name: 'مستشفى الأهلي العربي (المعمداني)', address: 'غزة - الزيتون' },
  { id: 5, name: 'مستشفى القدس - الهلال الأحمر', address: 'غزة - تل الهوى' },
  { id: 6, name: 'مستشفى أصدقاء المريض الخيري', address: 'غزة - حي الرمال - شارع الشهداء' },
  { id: 7, name: 'مستشفى كمال عدوان', address: 'شمال غزة - بيت لاهيا' },
  { id: 8, name: 'المستشفى الإندونيسي', address: 'شمال غزة - بيت لاهيا' },
  { id: 9, name: 'مستشفى العودة - النصيرات', address: 'المحافظة الوسطى - النصيرات' }
];

const partnersDictionary = {
  // Hospitals
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex',
  'جمعية بنك الدم المركزي': 'Central Blood Bank Society',
  'بنك الدم المركزي - وزارة الصحة': 'Central Blood Bank - Ministry of Health',
  'مستشفى الأهلي العربي (المعمداني)': 'Ahli Arab Hospital (Al-Maamadani)',
  'مستشفى القدس - الهلال الأحمر': 'Al-Quds Hospital - Red Crescent',
  'مستشفى أصدقاء المريض الخيري': 'Patient\'s Friends Benevolent Society Hospital',
  'مستشفى كمال عدوان': 'Kamal Adwan Hospital',
  'المستشفى الإندونيسي': 'Indonesian Hospital',
  'مستشفى العودة - النصيرات': 'Al-Awda Hospital - Nuseirat',

  // Locations
  'غزة - الرمال': 'Gaza - Rimal',
  'غزة - الرمال شارع الوحدة': 'Gaza - Rimal, Al-Wehda St.',
  'غزة - النصر': 'Gaza - Al-Nasr',
  'غزة - الزيتون': 'Gaza - Zeitoun',
  'غزة - تل الهوى': 'Gaza - Tel Al-Hawa',
  'غزة - حي الرمال - شارع الشهداء': 'Gaza - Rimal, Al-Shohada St.',
  'شمال غزة - بيت لاهيا': 'North Gaza - Beit Lahia',
  'المحافظة الوسطى - النصيرات': 'Middle Area - Nuseirat'
};

const partners = ref([]);

const localizedPartners = computed(() => {
  const sourceList = partners.value.length > 0 ? partners.value : defaultHospitalsKeys;
  const isEn = currentLanguage.value === 'en';

  return sourceList.map(item => {
    let nameVal = item.facility_name || item.name || (item.nameKey && te(item.nameKey) ? t(item.nameKey) : '');
    let addressVal = item.address || (item.addressKey && te(item.addressKey) ? t(item.addressKey) : '');

    if (isEn) {
      nameVal = item.facility_name_en || item.name_en || partnersDictionary[nameVal] || nameVal;
      addressVal = item.address_en || partnersDictionary[addressVal] || addressVal;
    }

    return {
      ...item,
      facility_name: nameVal,
      address: addressVal
    };
  });
});

const fetchPartners = async () => {
  try {
    const res = await apiClient.get('/public/partners');
    if (res && res.data && (Array.isArray(res.data) ? res.data.length > 0 : res.data.data?.length > 0)) {
      partners.value = Array.isArray(res.data) ? res.data : res.data.data;
    } else {
      partners.value = defaultHospitalsKeys;
    }
  } catch (error) {
    partners.value = defaultHospitalsKeys;
  }
};

onMounted(() => {
  fetchPartners();
});

const handleImgFallback = (e) => {
  e.target.src = getImageUrl('hero-drop.png');
};

const handleAvatarFallback = (e) => {
  e.target.src = getImageUrl('user-avatar.jpg');
};
</script>

<style scoped>
.about-page,
.about-page * {
  font-family: Arial, sans-serif !important;
}

.hero-about-section {
  background: #f8fafc;
  height: 500px;
  overflow: hidden;
  display: flex;
  align-items: center;
}

.hero-row {
  height: 500px;
  width: 100%;
}

.hero-content {
  display: flex;
  justify-content: center;
  align-items: center;
}

.hero-text-wrapper {
  width: 460px;
  max-width: 460px;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  text-align: right;
  margin-left: 200px;
}

[dir="ltr"] .hero-text-wrapper {
  align-items: flex-start;
  text-align: left;
  margin-left: 0;
  margin-right: 200px;
}

.hero-title {
  width: 100%;
  font-size: 52px;
  font-weight: 800;
  line-height: 1.35;
  color: #0F172A;
  text-align: inherit;
  margin-bottom: 18px;
}

.hero-description {
  width: 100%;
  font-size: 25px;
  color: #6B7280;
  line-height: 2;
  text-align: inherit;
  margin-bottom: 28px;
}

.hero-image-col {
  height: 500px;
}

.hero-image-wrapper {
  width: 100%;
  height: 500px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.hero-about-img {
  width: 115%;
  max-width: none;
  height: auto;
  object-fit: contain;
  transform: translateX(-60px);
  user-select: none;
  pointer-events: none;
  margin-left: 40px;
}

[dir="ltr"] .hero-about-img {
  transform: translateX(60px);
  margin-left: 0;
  margin-right: 40px;
}

.bg-light-gray {
  background-color: #f8fafc;
}

.title-red-line {
  width: 50px;
  height: 3px;
  background-color: #dc2626;
  border-radius: 2px;
}

.main-info-card {
  transition: transform 0.3s ease;
  border: 1px solid #f1f5f9 !important;
}

.main-info-card:hover {
  transform: translateY(-5px);
}

.bg-pink-light {
  background-color: #fdecec;
}

.icon-circle {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.goal-icon {
  width: 28px;
  height: 28px;
  object-fit: contain;
  display: block;
}

.feature-card {
  transition: all 0.3s ease;
  border: 1px solid #f1f5f9;
}

.feature-card:hover {
  transform: translateY(-3px);
  border-color: #fca5a5;
}

.review-card {
  border: 1px solid #f1f5f9;
}

.text-start-dir {
  text-align: right;
}

[dir="ltr"] .text-start-dir {
  text-align: left;
}

.avatar-img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  border: 1px solid #e2e8f0;
}

.partner-card {
  border: 1px solid #e2e8f0 !important;
  transition: all 0.3s ease;
}

.partner-card:hover {
  transform: translateY(-3px);
  border-color: #fca5a5 !important;
  box-shadow: 0 4px 12px rgba(220, 38, 38, 0.08) !important;
}

.partner-icon-wrapper {
  width: 45px;
  height: 45px;
}

/* حل مشكلة طفح وتجاوز النص حدود البطاقة */
.partner-name {
  white-space: normal;
  word-break: break-word;
  line-height: 1.35;
}

.partner-address {
  white-space: normal;
  word-break: break-word;
  line-height: 1.3;
}

.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }

@media (max-width: 1200px) {
  .hero-text-wrapper,
  [dir="ltr"] .hero-text-wrapper {
    margin-left: 0;
    margin-right: 0;
  }
}

@media (max-width: 991px) {
  .hero-about-section,
  .hero-row,
  .hero-content,
  .hero-image-col,
  .hero-image-wrapper {
    height: auto;
    min-height: auto;
  }

  .hero-content {
    padding-top: 40px;
    padding-bottom: 20px;
    text-align: center !important;
  }

  .hero-text-wrapper {
    margin: 0 auto !important;
    align-items: center !important;
    text-align: center !important;
    max-width: 100%;
  }

  .hero-title,
  .hero-description {
    text-align: center !important;
  }

  .hero-about-img {
    width: 100%;
    max-width: 420px;
    height: auto;
    object-fit: contain;
    transform: none !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
  }

  .hero-title {
    font-size: 32px;
  }

  .hero-description {
    font-size: 18px;
  }
}
</style>
