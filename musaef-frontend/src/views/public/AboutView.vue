<template>
  <div class="about-page d-flex flex-column min-vh-100 dir-rtl">
    <!-- Navbar -->
    <Navbar />

    <!-- 1. قسم الهيرو (من نحن - بتنسيق وحجم أبعاد أصلي مطابق تماماً) -->
    <section class="hero-about-section py-5">
      <div class="container-fluid p-0">
        <div class="row g-0 align-items-center hero-row">

          <!-- النص الوصفي (يمين) -->
          <div class="col-lg-6 hero-content d-flex align-items-center justify-content-end px-3 px-md-5">
            <div class="hero-text-wrapper">
              <h1 class="fw-bold hero-title mb-3">
                من نحن<span class="text-danger">...</span>
              </h1>
              <p class="hero-description text-secondary">
                منصة ذكية تهدف إلى تسهيل التبرع بالدم<br />
                إنقاذ الأرواح باستخدام الذكاء الاصطناعي والتقنيات الحديثة.<br />
                نربط بين المتبرعين والمحتاجين في لحظات الطوارئ<br />
                لضمان وصول الدم في الوقت المناسب.
              </p>
            </div>
          </div>

          <!-- صورة الطبيب والواجهة البرمجية (يسار) بنفس العرض والتنسيق الأصلي -->
          <div class="col-lg-6 hero-image-col">
            <div class="hero-image-wrapper">
              <img
                :src="getImageUrl('about-doctor.png')"
                alt="من نحن - مسعف"
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
              <h4 class="fw-bold text-danger mb-3 fs-5 fs-md-4">رؤيتنا:</h4>
              <p class="text-secondary fs-7 mb-0 lh-lg">
                أن نكون المنصة الرائدة والأكثر موثوقية في مجال التبرع بالدم على مستوى الوطن العربي.
              </p>
            </div>
          </div>

          <!-- رسالتنا -->
          <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center rounded-4 h-100 bg-white main-info-card">
              <div class="icon-circle bg-pink-light text-danger mx-auto mb-3">
                <i class="bi bi-envelope-fill fs-4"></i>
              </div>
              <h4 class="fw-bold text-danger mb-3 fs-5 fs-md-4">رسالتنا:</h4>
              <p class="text-secondary fs-7 mb-0 lh-lg">
                توفير الدم بأسرع وقت وبأعلى معايير الأمان من خلال منصة موثوقة تعتمد على التقنية والتعاون الإنساني لإنقاذ الأرواح.
              </p>
            </div>
          </div>

          <!-- أهدافنا -->
          <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center rounded-4 h-100 bg-white main-info-card">
              <div class="icon-circle bg-pink-light mx-auto mb-3">
                <img
                  :src="getImageUrl('Frame 2147225414.png')"
                  alt="أهدافنا"
                  class="goal-icon"
                />
              </div>
              <h4 class="fw-bold text-danger mb-3 fs-5 fs-md-4">أهدافنا</h4>
              <p class="text-secondary fs-7 mb-0 lh-lg">
                إنقاذ الأرواح عبر تسهيل التبرع بدم آمن وسريع وفعال باستخدام التقنية والذكاء الاصطناعي.
              </p>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- 3. مميزات المنصة (شبكة متجاوبة) -->
    <section id="features" class="py-4 py-md-5 bg-white">
      <div class="container text-center px-3 px-md-4">
        <h3 class="fw-bold text-dark section-title-center mb-1 fs-4 fs-md-3">مميزات المنصة؟!</h3>
        <div class="title-red-line mx-auto mb-4 mb-md-5"></div>

        <div class="row g-3 justify-content-center">
          <div v-for="feature in features" :key="feature.title" class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="p-3 border rounded-4 bg-white h-100 feature-card shadow-sm d-flex flex-column justify-content-center align-items-center text-center">
              <div class="feature-icon mb-2 text-danger fs-2">
                <i :class="feature.icon"></i>
              </div>
              <h6 class="fw-bold fs-7 mb-2 text-dark">{{ feature.title }}</h6>
              <p class="text-muted fs-8 mb-0 lh-base">{{ feature.desc }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. التقييمات والآراء -->
    <section id="reviews" class="py-4 py-md-5 bg-light-gray">
      <div class="container text-center px-3 px-md-4">
        <h3 class="fw-bold text-dark section-title-center mb-1 fs-4 fs-md-3">التقييمات والآراء</h3>
        <div class="title-red-line mx-auto mb-4 mb-md-5"></div>

        <div class="row g-3 g-md-4">
          <div v-for="review in reviews" :key="review.name" class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-end review-card d-flex flex-column justify-content-between">
              <p class="text-dark fw-medium fs-7 mb-4 lh-lg">"{{ review.text }}"</p>

              <div class="d-flex align-items-center gap-3 mt-auto pt-3 border-top border-light-subtle">
                <img
                  :src="getImageUrl(review.avatar)"
                  class="rounded-circle avatar-img flex-shrink-0"
                  :alt="review.name"
                  @error="handleAvatarFallback"
                />
                <div class="min-w-0">
                  <h6 class="fw-bold mb-0.5 text-dark fs-7 text-truncate">{{ review.name }}</h6>
                  <small class="text-muted fs-8 text-truncate d-block">{{ review.role }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. بالتعاون مع (شركاء النجاح - جلب ديناميكي من قاعدة البيانات) -->
    <section id="partners" class="py-4 py-md-5 bg-white">
      <div class="container text-center px-3 px-md-4">
        <h4 class="fw-bold text-dark mb-1 fs-5 fs-md-4">بالتعاون مع</h4>
        <div class="title-red-line mx-auto mb-4"></div>

        <div class="row g-2 g-md-3 justify-content-center align-items-center" v-if="partners.length > 0">
          <div v-for="partner in partners" :key="partner.id" class="col-6 col-sm-4 col-lg-2">
            <div class="partner-box p-2 border rounded-4 bg-white shadow-sm d-flex align-items-center justify-content-center gap-2 h-100">
              <i class="bi bi-hospital text-danger fs-5"></i>
              <span class="fw-bold fs-8 text-dark text-truncate" :title="partner.name">{{ partner.name }}</span>
            </div>
          </div>
        </div>

        <div v-else class="text-muted py-3">
          جاري تحميل الجهات الشريكة...
        </div>
      </div>
    </section>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '@/api/axios'; // استدعاء مثيل الـ Axios المخصص
import Navbar from '@/components/common/Navbar.vue';
import Footer from '@/components/common/Footer.vue';

// دالة لجلب مسار الصورة ديناميكياً
const getImageUrl = (name) => {
  return new URL(`../../assets/images/${name}`, import.meta.url).href;
};

const features = ref([
  { title: 'التنبؤ بالطلب المستقبلي', desc: 'تحليل ذكي للتنبؤ بالطلب وتوفير الدم مسبقاً.', icon: 'bi bi-graph-up-arrow' },
  { title: 'إدارة مخزون الدم', desc: 'متابعة دقيقة لمخزون الدم وتحديثه لحظياً.', icon: 'bi bi-droplet' },
  { title: 'نظام المكافآت', desc: 'نقاط ومكافآت لتشجيع العطاء المستمر.', icon: 'bi bi-gift' },
  { title: 'إشعارات الطوارئ', desc: 'تنبيهات فورية للحالات الطارئة القريبة منك.', icon: 'bi bi-bell' },
  { title: 'الخرائط الذكية', desc: 'تحديد أقرب المراكز والمستشفيات بسهولة.', icon: 'bi bi-geo-alt' },
  { title: 'المطابقة الذكية', desc: 'نظام ذكي يطابق بين المتبرع والمحتاج بدقة وسرعة.', icon: 'bi bi-magic' }
]);

/* التقييمات والآراء */
const reviews = ref([
  {
    name: 'أحمد سامي',
    role: '(متبرع منتظم)',
    text: 'التبرع عبر "مسعف" ليس مجرد عمل إنساني، بل هو تجربة سلسة ومتكاملة تشعرك بالقيمة الحقيقية لكل قطرة دم تمنحها، ويبقى الأثر الذي تتركه في حياة الآخرين.',
    avatar: 'review-ahmed.png'
  },
  {
    name: 'مريم خالد',
    role: '(متبرعة)',
    text: 'لم أكن أتخيل أن ضغطة زر واحدة قد تفصل بين حياة إنسان وموته. "مسعف" أرشدتني في الوقت والمكان المناسبين لأكون سبباً حقيقياً في إحياء نفس.',
    avatar: 'review-maryam.png'
  },
  {
    name: 'مستشفى الشفاء (غزة)',
    role: '',
    text: 'في أوقات الطوارئ، كل ثانية تصنع فارقاً بين الحياة والموت. منصة "مسعف" كانت الشريان الأساسي الذي أمد مستشفياتنا بالدم المستمر ومكننا من الاستجابة الفوريه لإنقاذ المصابين.',
    avatar: 'shifa-hospital.png'
  }
]);

/* بالتعاون مع (يتم جلبها ديناميكياً من الباك إند) */
const partners = ref([]);

const fetchPartners = async () => {
  try {
    const res = await apiClient.get('/public/partners');
    if (res && res.data) {
      partners.value = res.data;
    }
  } catch (error) {
    console.error('خطأ في جلب المستشفيات الشريكة:', error);
  }
};

onMounted(() => {
  fetchPartners();
});

// صور بديلة في حالة عدم وجود الصورة المطلوبة في المجلد
const handleImgFallback = (e) => {
  e.target.src = getImageUrl('hero-drop.png');
};

const handleAvatarFallback = (e) => {
  e.target.src = getImageUrl('user-avatar.jpg');
};
</script>

<style scoped>
.dir-rtl {
  direction: rtl;
  font-family: Arial, sans-serif;
}

/* ==========================================
   Hero Section Styles (Matching Homepage Exactly)
========================================== */
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

.hero-title {
  width: 100%;
  font-size: 52px;
  font-weight: 800;
  line-height: 1.35;
  color: #0F172A;
  text-align: right;
  margin-bottom: 18px;
}

.hero-description {
  width: 100%;
  font-size: 25px;
  color: #6B7280;
  line-height: 2;
  text-align: right;
  margin-bottom: 28px;
}

/* ==========================================
   Hero Image (Identical to Home Section)
========================================== */
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

/* ==========================================
   General Sections
========================================== */
.bg-light-gray {
  background-color: #f8fafc;
}

.title-red-line {
  width: 50px;
  height: 3px;
  background-color: #dc2626;
  border-radius: 2px;
}

/* بطاقات المعلومات الثلاثية */
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

/* أيقونة أهدافنا */
.goal-icon {
  width: 28px;
  height: 28px;
  object-fit: contain;
  display: block;
}

/* بطاقات المميزات */
.feature-card {
  transition: all 0.3s ease;
  border: 1px solid #f1f5f9;
}

.feature-card:hover {
  transform: translateY(-3px);
  border-color: #fca5a5;
}

/* بطاقات التقييمات */
.review-card {
  border: 1px solid #f1f5f9;
}

.avatar-img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  border: 1px solid #e2e8f0;
}

/* شركاء النجاح */
.partner-box {
  min-height: 55px;
  border: 1px solid #e2e8f0 !important;
}

/* الأحجام */
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }

/* Responsive adjustments */
@media (max-width: 1200px) {
  .hero-text-wrapper {
    margin-left: 0;
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
    margin: 0 auto;
    align-items: center;
    text-align: center;
    max-width: 100%;
  }

  .hero-title,
  .hero-description {
    text-align: center;
  }

  .hero-about-img {
    width: 100%;
    max-width: 420px;
    height: auto;
    object-fit: contain;
    transform: none;
    margin-left: 0;
  }

  .hero-title {
    font-size: 32px;
  }

  .hero-description {
    font-size: 18px;
  }
}
</style>
