<template>
  <footer class="footer" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <div class="container px-3 px-md-4">
      <div class="row g-4">
        <!-- Logo -->
        <div class="col-12 col-md-6 col-lg-3 footer-col logo-col">
          <img :src="logoWhite" class="footer-logo" alt="Musaef" />
          <p class="footer-desc">
            {{ $t('footer.desc') }}
          </p>
        </div>

        <!-- Quick Links -->
        <div class="col-12 col-sm-6 col-md-3 col-lg-2 footer-col">
          <h5>{{ $t('footer.quickLinks') }}</h5>
          <ul>
            <li><router-link to="/">{{ $t('navbar.home') }}</router-link></li>
            <li><router-link to="/about">{{ $t('navbar.about') }}</router-link></li>
            <li><router-link to="/blood-guide">{{ $t('navbar.bloodGuide') }}</router-link></li>
            <li>
              <router-link :to="authStore.isAuthenticated ? '/donor/donation-center' : '/login'">
                {{ $t('navbar.donateNow') }}
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Terms -->
        <div class="col-12 col-sm-6 col-md-3 col-lg-2 footer-col">
          <h5>{{ $t('footer.terms') }}</h5>
          <p>{{ $t('footer.termsDesc') }}</p>
          <router-link to="/terms" class="read-more">{{ $t('footer.readMore') }}</router-link>
        </div>

        <!-- Privacy -->
        <div class="col-12 col-sm-6 col-md-3 col-lg-2 footer-col">
          <h5>{{ $t('footer.privacy') }}</h5>
          <p>{{ $t('footer.privacyDesc') }}</p>
          <router-link to="/privacy" class="read-more">{{ $t('footer.readMore') }}</router-link>
        </div>

        <!-- Social -->
        <div class="col-12 col-md-6 col-lg-3 footer-col social-col">
          <h5>{{ $t('footer.followUs') }}</h5>
          <div class="social-icons">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
            <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
      </div>

      <div class="copyright text-center">
        {{ $t('footer.rights') }} {{ new Date().getFullYear() }}
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import logoWhite from '@/assets/icons/logo-white.png';
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();
const { locale } = useI18n();

const currentLanguage = computed(() => locale.value || 'ar');
</script>

<style scoped>
.footer {
  background: #0F2238;
  color: #fff;
  padding-top: 40px;
}

.footer .container { max-width: 1280px; }
.footer-col { padding: 0 15px; }

@media (min-width: 992px) {
  .footer-col {
    padding: 0 24px;
    min-height: 170px;
  }
  .footer-col:not(:last-child) {
    border-inline-end: 1px solid rgba(255, 255, 255, .12);
  }
}

.footer h5 {
  color: #fff;
  font-size: 16px;
  font-weight: 700;
  margin-bottom: 18px;
}

@media (min-width: 768px) {
  .footer h5 { font-size: 17px; margin-bottom: 22px; }
}

.footer ul { list-style: none; padding: 0; margin: 0; }
.footer ul li { margin-bottom: 10px; }
.footer a { color: #fff; text-decoration: none; font-size: 14px; transition: .3s; }
.footer a:hover { color: #E53935; }

.footer p { color: #D3D8E0; font-size: 13px; line-height: 24px; }
@media (min-width: 768px) { .footer p { font-size: 14px; line-height: 28px; } }

.footer-logo { width: 140px; margin-bottom: 16px; }
@media (min-width: 768px) { .footer-logo { width: 170px; margin-bottom: 20px; } }

.read-more { color: #fff; font-weight: 600; }
.read-more:hover { color: #E53935; }

.social-icons { display: flex; gap: 12px; margin-top: 14px; }
.social-icons a {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 1px solid rgba(255, 255, 255, .25);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: .3s;
  color: #fff;
}

/* التعديل هنا: تحديد لون الأيقونة بالأبيض وتغيير لون الخلفية عند التمرير بشكل واضح */
.social-icons a:hover {
  background: #E53935;
  border-color: #E53935;
  color: #fff !important; /* ضمان بقاء لون أيقونة الموقع أبيض وواضح فوق الخلفية الحمراء */
  transform: translateY(-2px); /* إضافة حركة بسيطة لإبراز الزر عند الهوفر */
}

.social-icons i { font-size: 16px; }

.copyright {
  border-top: 1px solid rgba(255, 255, 255, .1);
  margin-top: 30px;
  padding: 16px 0;
  color: #B8C2CF;
  font-size: 12px;
}

@media (min-width: 768px) {
  .copyright { margin-top: 40px; padding: 18px 0; font-size: 13px; }
}
</style>
