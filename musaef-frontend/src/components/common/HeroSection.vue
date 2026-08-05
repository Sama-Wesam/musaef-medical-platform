<template>
  <section class="hero-section" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <div class="container-fluid p-0">
      <div class="row g-0 align-items-center hero-row">

        <!-- النص والأزرار -->
        <div class="col-lg-6 hero-content d-flex align-items-center justify-content-end px-3 px-md-5">
          <div class="hero-text-wrapper">

            <span class="hero-title-small">
              {{ $t('hero.smallTitle') }}
            </span>

            <h1 class="hero-title" v-html="$t('hero.titleHtml')"></h1>

            <p class="hero-description">
              {{ $t('hero.description') }}
            </p>

            <div class="hero-buttons">
              <RouterLink to="/about" class="btn hero-btn-outline">
                {{ $t('hero.explore') }}
              </RouterLink>

              <RouterLink to="/login" class="btn hero-btn-primary">
                <i class="bi bi-heart-fill"></i>
                <span>{{ $t('hero.donateNow') }}</span>
              </RouterLink>
            </div>

          </div>
        </div>

        <!-- الصورة -->
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
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();
const { locale } = useI18n();

const currentLanguage = computed(() => locale.value || 'ar');
</script>

<style scoped>
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

[dir="ltr"] .hero-text-wrapper {
  align-items: flex-start;
  text-align: left;
  margin-left: 0;
  margin-right: 200px;
}

.hero-title-small {
  color: #DC2626;
  font-size: 45px;
  font-weight: 700;
  line-height: 1.2;
  margin-bottom: 12px;
  width: 100%;
  text-align: inherit;
}

.hero-title {
  width: 100%;
  font-size: 35px;
  font-weight: 800;
  line-height: 1.3;
  color: #0F172A;
  text-align: inherit;
  margin-bottom: 14px;
}

.hero-description {
  width: 100%;
  font-size: 30px;
  color: #6B7280;
  line-height: 1.6;
  text-align: inherit;
  margin-bottom: 24px;
}

.hero-buttons {
  width: 100%;
  display: flex;
  justify-content: flex-end;
  gap: 16px;
  margin-left: 160px;
}

[dir="ltr"] .hero-buttons {
  justify-content: flex-start;
  margin-left: 0;
  margin-right: 160px;
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

[dir="ltr"] .hero-image {
  transform: translateX(60px);
  margin-left: 0;
  margin-right: 40px;
}

@media (max-width: 1200px) {
  .hero-text-wrapper,
  [dir="ltr"] .hero-text-wrapper {
    margin-left: 0;
    margin-right: 0;
  }
  .hero-buttons,
  [dir="ltr"] .hero-buttons {
    margin-left: 0;
    margin-right: 0;
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
    margin: 0 auto !important;
    align-items: center !important;
    text-align: center !important;
    max-width: 100%;
  }

  .hero-title-small,
  .hero-title,
  .hero-description {
    text-align: center !important;
  }

  .hero-title-small { font-size: 24px; }
  .hero-title { font-size: 26px; }
  .hero-description { font-size: 15px; }

  .hero-buttons {
    justify-content: center !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
  }

  .hero-image {
    width: 100%;
    max-width: 420px;
    height: auto;
    object-fit: contain;
    transform: none !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
  }
}
</style>
