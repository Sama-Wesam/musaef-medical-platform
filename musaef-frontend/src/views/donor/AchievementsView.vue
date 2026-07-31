<template>
  <div class="achievements-page dir-rtl bg-light-gray min-vh-100 pb-5">
    <DonorHeader />

    <main class="container-fluid px-2 px-md-4">
      <!-- مؤشر التحميل -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">جاري التحميل...</span>
        </div>
        <p class="text-muted mt-2 fs-8">جاري تحميل الإنجازات وسجل التبرعات...</p>
      </div>

      <template v-else>
        <!-- 1. كرت البطاقة الذكية الحمراء -->
        <div class="card border-0 rounded-4 shadow-lg p-3 p-md-4 mb-3 mb-md-4 text-white donor-red-card position-relative overflow-hidden">
          <div class="d-flex align-items-center justify-content-between flex-column flex-md-row flex-xl-row gap-3 gap-md-4">

            <!-- قسم فصيلة الدم والمستوى -->
            <div class="blood-type-section d-flex flex-column align-items-center justify-content-center text-center px-2">
              <div class="d-flex align-items-center gap-2 mb-1 justify-content-center">
                <div class="text-center">
                  <small class="text-white-50 fs-9 d-block text-center">فصيلة الدم</small>
                  <h2 class="fw-black text-white mb-0 lh-1 fs-3 fs-md-2 text-center" dir="ltr">{{ donorBloodType }}</h2>
                </div>
                <img :src="getIconUrl('blood-icon.png')" alt="قطرة" class="blood-drop-header-img" @error="handleImageFallback" />
              </div>

              <hr class="red-card-hr my-2 mx-auto" />

              <div class="text-center w-100">
                <small class="text-white-50 fs-9 d-block mb-0.5 text-center">المستوى الحالي</small>
                <span class="fw-bold text-white fs-8 d-block text-center">{{ cardData.level || 'متبرع مبتدئ' }}</span>
              </div>
            </div>

            <div class="card-vertical-divider d-none d-xl-block"></div>

            <!-- اسم المتبرع والموقع -->
            <div class="text-center text-md-end ps-xl-3 flex-grow-1 w-100 w-md-auto">
              <h3 class="fw-black text-white mb-1 fs-5 fs-md-4 text-truncate">{{ donorName }}</h3>
              <small class="text-white-50 fs-8 d-block"><i class="bi bi-geo-alt-fill me-1 text-white-50"></i> {{ cardData.location || 'غزة - فلسطين' }}</small>
            </div>

            <!-- صورة المتبرع (الدائرة البرتقالية) -->
            <div class="donor-avatar-center-wrapper text-center my-1 my-lg-0 mx-auto">
              <div class="position-relative d-inline-block avatar-gold-container">
                <img
                  :src="donorAvatar"
                  :alt="donorName"
                  class="rounded-circle donor-main-avatar-large shadow"
                  @error="onAvatarError"
                />
                <div class="avatar-heart-badge position-absolute bottom-0 start-50 translate-middle-x rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                  <i class="bi bi-heart-fill text-white fs-8"></i>
                </div>
              </div>
            </div>

            <!-- تفاصيل بطاقة المتبرع -->
            <div class="d-flex flex-column align-items-center align-items-md-end text-center text-md-end flex-grow-1 pe-xl-3 w-100 w-md-auto">
              <h4 class="fw-bold text-white mb-1 fs-6 fs-md-5">بطاقة متبرع ذكية</h4>
              <small class="text-white-50 fs-9 d-block mb-1">رقم المتبرع</small>
              <strong class="fs-7 font-monospace tracking-wider mb-2 text-white">{{ cardData.donor_code || 'BD123456789' }}</strong>

              <div class="badge-status-pill border border-warning border-opacity-75 rounded-pill px-3 py-1 fs-9 fw-bold text-warning d-inline-flex align-items-center gap-1 shadow-2xs">
                <i class="bi bi-star-fill text-warning"></i>
                <span>{{ cardData.status_text || 'متبرع نشط' }}</span>
              </div>
            </div>

            <!-- الباركود وزر التحميل -->
            <div class="barcode-wrapper text-center mx-auto ms-md-auto me-md-0">
              <div class="barcode-box bg-white p-2 rounded-3 d-inline-block shadow-sm">
                <img :src="getImageUrl('Barcode-Sticker-PNG-Transparent-Image 1.png')" alt="الباركود" class="barcode-img-large" />
              </div>
              <button class="btn btn-outline-light btn-sm rounded-pill w-100 mt-2 fs-9 fw-bold border-white btn-download-card py-1.5">
                تحميل
              </button>
            </div>

          </div>
        </div>

        <!-- 2. كرت الأثر الإنساني والنقاط والمكافآت -->
        <div class="row g-3 g-lg-4 mb-3 mb-md-4">
          <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
              <div>
                <h5 class="fw-bold text-dark mb-3 mb-md-4 text-end fs-6 fs-md-5">الأثر الإنساني</h5>

                <div class="row align-items-center text-center gy-3">
                  <div class="col-6 border-start">
                    <div class="d-flex align-items-center justify-content-center gap-2 gap-md-3 mb-1">
                      <h2 class="fw-black text-danger mb-0 fs-3 fs-md-2">{{ cardData.units_donated || 8 }}</h2>
                      <img :src="getImageUrl('22 (2).png')" alt="وحدات الدم" class="impact-icon-img-large" @error="handleImageFallback" />
                    </div>
                    <small class="text-muted fs-8 d-block fw-bold mt-1">وحدات الدم المتبرع بها</small>
                  </div>

                  <div class="col-6">
                    <div class="d-flex align-items-center justify-content-center gap-2 gap-md-3 mb-1">
                      <h2 class="fw-black text-danger mb-0 fs-3 fs-md-2">{{ cardData.cases_supported || 12 }}</h2>
                      <img :src="getIconUrl('hands.png')" alt="حالات" class="impact-icon-img-large" @error="handleImageFallback" />
                    </div>
                    <small class="text-muted fs-8 d-block fw-bold mt-1">حالة تم دعمها</small>
                  </div>
                </div>
              </div>

              <div class="p-2.5 p-md-3 bg-pink-light rounded-3 text-center mt-3 mt-md-4 border-0">
                <span class="text-danger fw-bold fs-8">بفضلك، تستمر الحياة في الأمل</span>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
              <div>
                <h5 class="fw-bold text-dark mb-3 mb-md-4 text-end fs-6 fs-md-5">النقاط والمكافآت</h5>

                <div class="d-flex align-items-center justify-content-between gap-3 mb-3 mb-md-4 flex-wrap flex-sm-nowrap">
                  <div class="text-start flex-grow-1 min-w-0 w-100">
                    <div class="d-flex align-items-baseline justify-content-start gap-2 mb-1">
                      <h2 class="fw-black text-danger mb-0 fs-2 fs-md-1">{{ cardData.points || 350 }}</h2>
                      <span class="text-muted fs-8 fw-bold">نقطة إجمالية</span>
                    </div>

                    <div class="progress my-2 bg-light rounded-pill" style="height: 10px;">
                      <div class="progress-bar bg-warning rounded-pill" role="progressbar" :style="{ width: (cardData.points_progress || 70) + '%' }"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center fs-9 text-muted fw-bold">
                      <span class="text-truncate">{{ cardData.points_needed || 150 }} نقطة حتى المستوى الثاني</span>
                      <span>{{ cardData.target_points || 500 }}</span>
                    </div>
                  </div>

                  <img :src="getImageUrl('coins.png')" alt="النقاط والمكافآت" class="coins-img-large mx-auto mx-sm-0" @error="handleImageFallback" />
                </div>
              </div>

              <button class="btn btn-pink-light text-danger w-100 rounded-3 py-2 fw-bold fs-8 border-0 mt-2 mt-md-3">
                استبدل نقاطك
              </button>
            </div>
          </div>
        </div>

        <!-- 3. شارات الإنجاز -->
        <AchievementBadges :badges="badgesList" class="mb-3 mb-md-4" />

        <!-- 4. قسم سجل التبرعات -->
        <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm">
          <h5 class="fw-bold text-dark mb-3 mb-md-4 text-center position-relative d-inline-block mx-auto section-title-line fs-6 fs-md-5">
            سجل التبرعات
          </h5>

          <div class="table-responsive">
            <table class="table table-borderless align-middle text-end mb-0 dir-rtl custom-history-table min-w-table">
              <thead>
                <tr class="text-dark fs-8 border-bottom fw-bold text-nowrap">
                  <th class="py-3 text-end pe-3">التاريخ</th>
                  <th class="py-3 text-end">المستشفى</th>
                  <th class="py-3 text-center">فصيلة الدم</th>
                  <th class="py-3 text-center">الوحدات</th>
                  <th class="py-3 text-center">الحالة</th>
                  <th class="py-3 text-center pe-4">النقاط المكتسبة</th>
                </tr>
              </thead>
              <tbody class="fs-8">
                <tr v-for="(item, index) in donationHistory" :key="index" class="border-bottom text-nowrap">
                  <td class="text-dark fw-bold text-end pe-3">{{ item.date || item.created_at?.substring(0, 10) || '2026-06-01' }}</td>
                  <td class="text-dark fw-medium text-end">{{ item.hospital_name || item.hospital?.name || item.facility_name || 'مستشفى الشفاء الطبي' }}</td>
                  <td class="fw-bold text-dark text-center" dir="ltr">{{ item.blood_type || item.blood_type_name || '+O' }}</td>
                  <td class="fw-bold text-dark text-center">{{ item.units || item.units_donated || 1 }}</td>
                  <td class="text-center">
                    <span :class="['badge px-3 px-md-4 py-2 rounded-3 fs-9 fw-bold', (item.status === 'عاجلة' || item.status === 'urgent') ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success']">
                      {{ item.status === 'successful' || item.status === 'مكتمل' ? 'مكتمل' : (item.status || 'مكتمل') }}
                    </span>
                  </td>
                  <td class="fw-bold text-secondary text-center pe-4">+{{ item.points_earned || 50 }}</td>
                </tr>

                <template v-if="!donationHistory.length">
                  <tr class="border-bottom text-nowrap">
                    <td class="text-dark fw-bold text-end pe-3">2026-06-15</td>
                    <td class="text-dark fw-medium text-end">مجمع الشفاء الطبي</td>
                    <td class="fw-bold text-dark text-center" dir="ltr">O+</td>
                    <td class="fw-bold text-dark text-center">2</td>
                    <td class="text-center">
                      <span class="badge px-3 px-md-4 py-2 rounded-3 fs-9 fw-bold bg-success-subtle text-success">مكتمل</span>
                    </td>
                    <td class="fw-bold text-secondary text-center pe-4">+50</td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </template>

    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import donor from '@/api/donor';
import DonorHeader from '@/components/donor/DonorHeader.vue';
import AchievementBadges from '@/components/donor/Achievements/AchievementBadges.vue';
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();

const loading = ref(true);
const cardData = ref({});
const donationHistory = ref([]);

const donorName = computed(() => {
  return authStore.user?.name || cardData.value.donor_name || 'Sama Wesam';
});

const donorBloodType = computed(() => {
  return authStore.user?.blood_type_name || authStore.user?.donor?.blood_type?.name || cardData.value.blood_type || 'O+';
});

// دالة جلب الصورة من مجلد icons بمسار نسبي دقيق من مجلد views
const getIconUrl = (fileName) => {
  if (!fileName) return '';
  if (fileName.startsWith('http') || fileName.startsWith('data:')) return fileName;
  try {
    return new URL(`../../assets/icons/${fileName}`, import.meta.url).href;
  } catch (e) {
    return '';
  }
};

// دالة جلب الصورة من مجلد images
const getImageUrl = (fileName) => {
  if (!fileName) return '';
  if (fileName.startsWith('http') || fileName.startsWith('data:')) return fileName;
  try {
    return new URL(`../../assets/images/${fileName}`, import.meta.url).href;
  } catch (e) {
    return '';
  }
};

// جلب الأفاتار من مجلد icons بحال عدم توفر صورة مخصصة من السيرفر
const donorAvatar = computed(() => {
  const userAvatar = authStore.userAvatar || authStore.user?.avatar || authStore.user?.donor?.user?.avatar;
  if (userAvatar) {
    if (userAvatar.startsWith('http') || userAvatar.startsWith('blob:')) {
      return userAvatar;
    }
    return `http://localhost:8000/storage/${userAvatar}`;
  }
  return getIconUrl('user-avatar.png');
});

const badgesList = ref([
  { id: 1, title: 'منقذ حياة', desc: 'تم إنقاذ أكثر من 10 حالات', date: '1 يونيو 2024', image: 'badge-hero.png' },
  { id: 2, title: '10 تبرعات', desc: 'تم إنجاز 10 تبرعات', date: '20 مايو 2025', image: 'badge-10.png' },
  { id: 3, title: '5 تبرعات', desc: 'تم إنجاز 5 تبرعات', date: '10 أبريل 2024', image: 'badge-5.png' },
  { id: 4, title: 'أول تبرع', desc: 'تم إنجاز أول تبرع', date: '15 مارس 2024', image: 'badge-1.png' }
]);

const fetchAchievementsData = async () => {
  loading.value = true;
  try {
    const res = await donor.getRewardsAndCard();
    const payload = res?.data?.data || res?.data || res || {};
    cardData.value = payload;
    if (payload.badges && payload.badges.length) {
      badgesList.value = payload.badges;
    }
    if (payload.donation_history) {
      donationHistory.value = payload.donation_history;
    } else {
      const historyRes = await donor.getDonationHistory();
      donationHistory.value = historyRes?.data?.data || historyRes?.data || [];
    }
  } catch (error) {
    console.error('خطأ في جلب البيانات:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchAchievementsData();
});

const onAvatarError = (e) => {
  e.target.src = getIconUrl('user-avatar.png');
};

const handleImageFallback = (e) => {
  e.target.style.display = 'none';
};
</script>

<style scoped>
.dir-rtl { direction: rtl; font-family: Arial, sans-serif; }
.bg-light-gray { background-color: #f8fafc; }

.donor-red-card {
  background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 50%, #b91c1c 100%);
  border: 1px solid rgba(255, 255, 255, 0.15);
}

.blood-type-section { min-width: 120px; }
.blood-drop-header-img { width: 30px; height: 30px; object-fit: contain; }
@media (min-width: 768px) { .blood-drop-header-img { width: 34px; height: 34px; } }

.red-card-hr {
  width: 90px;
  height: 1px;
  background-color: rgba(255, 255, 255, 0.35);
  border: none;
  opacity: 1;
}

.card-vertical-divider {
  width: 1px;
  height: 75px;
  background-color: rgba(255, 255, 255, 0.22);
  margin: 0 10px;
}

.avatar-gold-container {
  padding: 3px;
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
  border-radius: 50%;
}

.donor-main-avatar-large { width: 80px; height: 80px; object-fit: cover; display: block; }
@media (min-width: 768px) { .donor-main-avatar-large { width: 95px; height: 95px; } }

.avatar-heart-badge {
  background-color: #f59e0b;
  width: 24px;
  height: 24px;
  border: 2px solid #ffffff;
}

.badge-status-pill { background-color: rgba(0, 0, 0, 0.25); }
.barcode-box { width: 105px; }
@media (min-width: 768px) { .barcode-box { width: 115px; } }

.barcode-img-large { width: 100%; height: auto; object-fit: contain; }
.btn-download-card { background-color: transparent; color: #ffffff; transition: all 0.2s ease; border-color: rgba(255, 255, 255, 0.7); }
.btn-download-card:hover { background-color: #ffffff; color: #991b1b !important; border-color: #ffffff; }

.coins-img-large { width: 100px; height: auto; object-fit: contain; }
@media (min-width: 768px) { .coins-img-large { width: 135px; } }

.impact-icon-img-large { width: 38px; height: 38px; object-fit: contain; }
@media (min-width: 768px) { .impact-icon-img-large { width: 48px; height: 48px; } }

.btn-pink-light { background-color: #fdecec; transition: background-color 0.2s ease; }
.btn-pink-light:hover { background-color: #fca5a5; color: #ffffff !important; }
.bg-pink-light { background-color: #fdecec; }

.section-title-line { border-bottom: 2px solid #dc2626; padding-bottom: 4px; }
.min-w-table { min-width: 580px; }
.custom-history-table { text-align: right !important; }
.custom-history-table th { color: #1f2937; }
.custom-history-table td { padding: 12px 8px; }
@media (min-width: 768px) { .custom-history-table td { padding: 16px 8px; } }

.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.fw-black { font-weight: 900; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
</style>
