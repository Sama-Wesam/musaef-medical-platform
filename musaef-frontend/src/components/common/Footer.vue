<script setup>
import logoWhite from '@/assets/images/logo-white.png';
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();
</script>

<template>
  <footer class="footer">
    <div class="container px-3 px-md-4">
      <div class="row g-4">
        <!-- Logo -->
        <div class="col-12 col-md-6 col-lg-3 footer-col logo-col">
          <img :src="logoWhite" class="footer-logo" alt="Musaef" />
          <p class="footer-desc">
            منصة ذكية لربط المتبرعين بالمحتاجين وتسهيل التبرع بالدم في وقت الطوارئ
          </p>
        </div>

        <!-- Quick Links -->
        <div class="col-12 col-sm-6 col-md-3 col-lg-2 footer-col">
          <h5>روابط سريعة</h5>
          <ul>
            <li><router-link to="/">الرئيسية</router-link></li>
            <li><router-link to="/about">من نحن</router-link></li>
            <li><router-link to="/blood-guide">دليل التبرع</router-link></li>
            <li>
              <router-link :to="authStore.isAuthenticated ? '/cases' : '/login'">
                تبرع الآن
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Terms -->
        <div class="col-12 col-sm-6 col-md-3 col-lg-2 footer-col">
          <h5>شروط الاستخدام</h5>
          <p>الموافقة على سياسة الاستخدام</p>
          <router-link to="/terms" class="read-more">اقرأ المزيد</router-link>
        </div>

        <!-- Privacy -->
        <div class="col-12 col-sm-6 col-md-3 col-lg-2 footer-col">
          <h5>سياسة الخصوصية</h5>
          <p>نحن نحترم بياناتك الشخصية</p>
          <router-link to="/privacy" class="read-more">اقرأ المزيد</router-link>
        </div>

        <!-- Social -->
        <div class="col-12 col-md-6 col-lg-3 footer-col social-col">
          <h5>تابعنا</h5>
          <div class="social-icons">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
            <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
      </div>

      <div class="copyright">
        جميع الحقوق محفوظة © منصة مسعف {{ new Date().getFullYear() }}
      </div>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  background: #0F2238;
  color: #fff;
  direction: rtl;
  padding-top: 40px;
}

.footer .container {
  max-width: 1280px;
}

.footer-col {
  padding: 0 15px;
}

@media (min-width: 992px) {
  .footer-col {
    padding: 0 24px;
    min-height: 170px;
  }

  .footer-col:not(:last-child) {
    border-left: 1px solid rgba(255, 255, 255, .12);
  }
}

.footer h5 {
  color: #fff;
  font-size: 16px;
  font-weight: 700;
  margin-bottom: 18px;
}

@media (min-width: 768px) {
  .footer h5 {
    font-size: 17px;
    margin-bottom: 22px;
  }
}

.footer ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer ul li {
  margin-bottom: 10px;
}

.footer a {
  color: #fff;
  text-decoration: none;
  font-size: 14px;
  transition: .3s;
}

.footer a:hover {
  color: #E53935;
}

.footer p {
  color: #D3D8E0;
  font-size: 13px;
  line-height: 24px;
}

@media (min-width: 768px) {
  .footer p {
    font-size: 14px;
    line-height: 28px;
  }
}

.footer-logo {
  width: 140px;
  margin-bottom: 16px;
}

@media (min-width: 768px) {
  .footer-logo {
    width: 170px;
    margin-bottom: 20px;
  }
}

.read-more {
  color: #fff;
  font-weight: 600;
}

.read-more:hover {
  color: #E53935;
}

.social-icons {
  display: flex;
  gap: 12px;
  margin-top: 14px;
}

.social-icons a {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 1px solid rgba(255, 255, 255, .25);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: .3s;
}

.social-icons a:hover {
  background: #E53935;
  border-color: #E53935;
}

.social-icons i {
  font-size: 16px;
}

.copyright {
  border-top: 1px solid rgba(255, 255, 255, .1);
  margin-top: 30px;
  padding: 16px 0;
  text-align: center;
  color: #B8C2CF;
  font-size: 12px;
}

@media (min-width: 768px) {
  .copyright {
    margin-top: 40px;
    padding: 18px 0;
    font-size: 13px;
  }
}
</style>
