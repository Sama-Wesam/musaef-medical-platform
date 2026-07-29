<template>
  <section class="hero-section dir-rtl">
    <div class="container-fluid p-0">
      <div class="row g-0 align-items-center hero-row">

        <!-- النص والأزرار (يمين) -->
        <div class="col-lg-6 hero-content d-flex align-items-center justify-content-end px-3 px-md-5">
          <div class="hero-text-wrapper">

            <span class="hero-title-small">
              مسعف...
            </span>

            <h1 class="hero-title">
              المنصة الذكية للتبرع
              <br />
              بالدم وقت الطوارئ
            </h1>

            <p class="hero-description">
              نربط المتبرعين والمستشفيات بسرعة وذكاء لنضمن وصول الدم إلى من تحتاجه في الوقت المناسب.
            </p>

            <div class="hero-buttons">
              <RouterLink to="/about" class="btn hero-btn-outline">
                استكشف المنصة
              </RouterLink>

              <RouterLink :to="authStore.isAuthenticated ? '/cases' : '/donate'" class="btn hero-btn-primary">
                <i class="bi bi-heart-fill"></i>
                <span>تبرع الآن</span>
              </RouterLink>
            </div>

          </div>
        </div>

        <!-- الصورة (يسار) بنفس الحجم والتنسيق الأصلي تماماً -->
        <div class="col-lg-6 hero-image-col">
          <div class="hero-image-wrapper">
            <img
              src="@/assets/images/hero-drop.png"
              class="hero-image"
              alt="Hero Drop"
            />
          </div>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();
</script>

<style scoped>
.dir-rtl {
  direction: rtl;
  font-family: Arial, sans-serif;
}

.hero-section {
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

.hero-title-small {
  color: #DC2626;
  font-size: 50px;
  font-weight: 700;
  line-height: 1;
  margin-bottom: 18px;
  width: 100%;
  text-align: right;
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

.hero-buttons {
  width: 100%;
  display: flex;
  justify-content: flex-end;
  gap: 16px;
  margin-left: 160px;
}

.hero-btn-primary {
  height: 48px;
  padding: 0 30px;
  border-radius: 8px;
  background: #dc2626;
  border: 2px solid #dc2626;
  color: #fff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-size: 16px;
  font-weight: 700;
  text-decoration: none;
  transition: .3s;
  box-shadow: 0 8px 20px rgba(220, 38, 38, .20);
}

.hero-btn-primary:hover {
  background: #b91c1c;
  border-color: #b91c1c;
  color: #fff;
  transform: translateY(-2px);
}

.hero-btn-outline {
  height: 48px;
  padding: 0 30px;
  border-radius: 8px;
  border: 2px solid #dc2626;
  background: #fff;
  color: #dc2626;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  font-weight: 700;
  text-decoration: none;
  transition: .3s;
}

.hero-btn-outline:hover {
  background: #dc2626;
  color: white;
  transform: translateY(-2px);
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

.hero-image {
  width: 115%;
  max-width: none;
  height: auto;
  object-fit: contain;
  transform: translateX(-60px);
  user-select: none;
  pointer-events: none;
  margin-left: 40px;
}

/* التجاوب مع الهواتف والشاشات الصغيرة */
@media (max-width: 1200px) {
  .hero-text-wrapper {
    margin-left: 0;
  }
  .hero-buttons {
    margin-left: 0;
  }
}

@media (max-width: 991px) {
  .hero-section,
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

  .hero-title-small,
  .hero-title,
  .hero-description {
    text-align: center;
  }

  .hero-title-small { font-size: 32px; }
  .hero-title { font-size: 32px; }
  .hero-description { font-size: 18px; }

  .hero-buttons {
    justify-content: center !important;
    margin-left: 0;
  }

  .hero-image {
    width: 100%;
    max-width: 420px;
    height: auto;
    object-fit: contain;
    transform: none;
    margin-left: 0;
  }
}
</style>
