<template>
  <div class="donor-dashboard-page dir-rtl bg-light-gray min-vh-100 pb-5" dir="rtl">
    <!-- الهيدر -->
    <DonorHeader />

    <main class="container-fluid px-2 px-md-4 pt-3">
      <!-- مؤشر التحميل -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">جاري التحميل...</span>
        </div>
        <p class="text-muted mt-2 fs-8">جاري تحميل بيانات اللوحة...</p>
      </div>

      <template v-else>
        <!-- 1. البانر العلوي الذكي (حالة الأهلية والعد التنازلي) -->
        <DonorHeroBanner :stats="stats" />

        <!-- 2. الكروت الإحصائية الأربعة -->
        <DonorStatsCards :stats="stats" />

        <!-- 3. الأقسام السفلية (الإشعارات العاجلة، الطلبات المقترحة، والحالة الفارغة) -->
        <div class="row g-3 g-lg-4">

          <!-- العمود الأول: إشعارات عاجلة -->
          <div class="col-12 col-lg-6 col-xl-4">
            <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
              <div>
                <h5 class="fw-bold text-dark mb-3 mb-md-4 text-end fs-6 fs-md-5">إشعارات عاجلة</h5>
                <div class="d-flex flex-column gap-2.5 gap-md-3">
                  <div v-for="item in notifications" :key="item.id" class="p-2.5 p-md-3 bg-pink-light rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2 text-end min-w-0">
                      <div class="drop-circle-icon bg-white shadow-2xs flex-shrink-0">
                        <img :src="getImageUrl('blood-icon.png')" alt="دم" class="notice-blood-icon" />
                      </div>
                      <div class="min-w-0">
                        <h6 class="fw-bold text-danger mb-0.5 fs-7 text-truncate">{{ item.hospital_name || item.title }}</h6>
                        <p class="text-dark fs-8 mb-0.5 fw-medium text-truncate">{{ item.message || item.description }}</p>
                        <small class="text-muted fs-9 d-block">{{ item.created_at || 'الآن' }}</small>
                      </div>
                    </div>
                    <div class="ms-auto ms-sm-0">
                      <span class="badge bg-pink-dark text-danger rounded-pill px-2.5 py-1 fs-9 fw-bold">أولوية قصوى</span>
                    </div>
                  </div>

                  <div v-if="!notifications.length" class="text-center text-muted py-4 fs-8">
                    لا توجد إشعارات عاجلة حالياً.
                  </div>
                </div>
              </div>

              <div class="text-center mt-4">
                <router-link to="/donor/notifications" class="text-danger fw-bold fs-8 text-decoration-none">
                  عرض جميع الإشعارات
                </router-link>
              </div>
            </div>
          </div>

          <!-- العمود الثاني: الطلبات المقترحة بالذكاء الاصطناعي -->
          <div class="col-12 col-lg-6 col-xl-5">
            <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
              <div>
                <h5 class="fw-bold text-dark mb-3 mb-md-4 text-end fs-6 fs-md-5">الطلبات المقترحة بالذكاء الاصطناعي</h5>
                <div class="d-flex flex-column gap-2.5 gap-md-3">
                  <div v-for="request in suggestedRequests" :key="request.id" class="p-3 border rounded-4 bg-white d-flex align-items-start justify-content-between gap-2 gap-sm-3 shadow-2xs flex-wrap flex-sm-nowrap">
                    <div class="d-flex align-items-start gap-2 gap-sm-3 flex-grow-1 text-end min-w-0">
                      <img :src="request.hospital_image || getImageUrl('shifa-hospital (2).png')" alt="المستشفى" class="hospital-card-img rounded-3 flex-shrink-0" @error="handleHospitalFallback" />
                      <div class="min-w-0">
                        <h6 class="fw-bold text-dark mb-1 fs-7 text-truncate">{{ request.hospital_name }}</h6>
                        <small class="text-muted d-block fs-9 mb-1 text-truncate">{{ request.location }}</small>
                        <p class="text-secondary fs-9 mb-1">الفصيلة المطلوبة : {{ request.blood_type }}</p>
                        <p class="text-secondary fs-9 mb-2">عدد الوحدات : {{ request.units_needed }} وحدات</p>
                        <router-link to="/donor/donation-center" class="btn btn-outline-danger btn-xs rounded-pill px-3 py-1 fs-9 fw-bold text-nowrap text-decoration-none">عرض التفاصيل</router-link>
                      </div>
                    </div>

                    <div class="d-flex flex-column align-items-center text-center ms-auto ms-sm-0 flex-shrink-0">
                      <span class="badge bg-pink-light text-danger rounded-pill px-2.5. py-1 fs-9 fw-bold mb-2">أولوية قصوى</span>
                      <div class="figma-green-progress-ring position-relative d-flex align-items-center justify-content-center mb-1">
                        <div class="inner-match-circle bg-white rounded-circle d-flex align-items-center justify-content-center">
                          <span class="text-dark fw-bold fs-9">{{ request.match_rate || '90' }}%</span>
                        </div>
                      </div>
                      <small class="text-muted fs-9 text-nowrap">تطابق مع ملفك</small>
                    </div>
                  </div>

                  <div v-if="!suggestedRequests.length" class="text-center text-muted py-4 fs-8">
                    لا توجد مقترحات حالياً.
                  </div>
                </div>
              </div>

              <div class="text-center mt-4">
                <router-link to="/donor/donation-center" class="text-danger fw-bold fs-8 text-decoration-none">
                  عرض كل الطلبات
                </router-link>
              </div>
            </div>
          </div>

          <!-- العمود الثالث: الحالة الفارغة التفاعلية -->
          <div class="col-12 col-xl-3">
            <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column align-items-center justify-content-center text-center">
              <div class="empty-state-icon-wrapper mb-3">
                <img :src="getImageUrl('Rectangle 22873.png')" alt="لا توجد حالات" class="empty-state-img" @error="handleEmptyStateFallback" />
              </div>
              <h5 class="fw-bold text-dark mb-2 fs-6">لا توجد حالات طارئة قريبة حالياً</h5>
              <p class="text-muted fs-8 mb-4 d-flex align-items-center justify-content-center gap-1 flex-wrap">
                <span>شكراً لكونك مستعداً دائماً لإنقاذ حياة</span>
                <i class="bi bi-heart text-danger"></i>
              </p>
              <router-link to="/donor/donation-center" class="btn btn-outline-danger rounded-3 px-4 py-2 fw-bold fs-8 shadow-2xs text-decoration-none">
                تصفح جميع الطلبات
              </router-link>
            </div>
          </div>

        </div>
      </template>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import donorApi from '@/api/donor';
import DonorHeader from '@/components/donor/DonorHeader.vue';
import DonorHeroBanner from '@/components/donor/dashboard/DonorHeroBanner.vue';
import DonorStatsCards from '@/components/donor/dashboard/DonorStatsCards.vue';

const loading = ref(true);
const stats = ref({
  donationsCount: 8,
  points: 230,
  badgesCount: 3,
  daysUntilNextDonation: 12,
  isEligible: true,
  lastDonationText: 'آخر تبرع منذ 45 يوم',
  level: 'متقدم',
  nearbyRequestsCount: 2
});

const notifications = ref([]);
const suggestedRequests = ref([]);

const fetchData = async () => {
  loading.value = true;
  try {
    // جلب الإحصائيات عبر مسار home-stats
    const statsRes = await donorApi.getDashboardData();
    if (statsRes) {
      const payload = statsRes.data?.data || statsRes.data || {};

      stats.value.donationsCount = payload.donations_count ?? stats.value.donationsCount;
      stats.value.points = payload.points ?? stats.value.points;
      stats.value.badgesCount = payload.badges_count ?? stats.value.badgesCount;
      stats.value.daysUntilNextDonation = payload.days_until_next_donation ?? stats.value.daysUntilNextDonation;
      stats.value.isEligible = payload.health_info ? payload.health_info.is_eligible : (payload.is_eligible ?? true);
      stats.value.lastDonationText = payload.last_donation_text || stats.value.lastDonationText;
      stats.value.level = payload.level || stats.value.level;
      stats.value.nearbyRequestsCount = payload.nearby_requests_count ?? stats.value.nearbyRequestsCount;
    }

    // جلب النداءات العاجلة والطلبات المقترحة عبر مسار urgent-requests
    const urgentRes = await donorApi.getUrgentRequests();
    const urgentData = urgentRes.data?.data || urgentRes.data || [];

    if (Array.isArray(urgentData)) {
      notifications.value = urgentData.slice(0, 3);
      suggestedRequests.value = urgentData.slice(0, 3);
    }
  } catch (error) {
    console.error('خطأ في جلب بيانات لوحة تحكم المتبرع:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchData();
});

const getImageUrl = (fileName) => {
  return new URL(`../../assets/images/${fileName}`, import.meta.url).href;
};

const handleHospitalFallback = (e) => { e.target.src = getImageUrl('shifa-hospital (2).png'); };
const handleEmptyStateFallback = (e) => { e.target.src = getImageUrl('Rectangle 22873.png'); };
</script>

<style scoped>
.dir-rtl { direction: rtl; font-family: Arial, sans-serif; }
.bg-light-gray { background-color: #f8fafc; }

.figma-green-progress-ring {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: conic-gradient(#22c55e 0deg 335deg, #e2e8f0 335deg 360deg);
  padding: 4px;
}
.inner-match-circle { width: 100%; height: 100%; }

.bg-pink-light { background-color: #fdecec; }
.bg-pink-dark { background-color: #fca5a5; }

.drop-circle-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.notice-blood-icon { width: 18px; height: 18px; object-fit: contain; }
.hospital-card-img { width: 70px; height: 65px; object-fit: cover; flex-shrink: 0; }

.btn-xs { font-size: 0.75rem; padding: 4px 12px; }
.empty-state-img { max-height: 130px; width: auto; object-fit: contain; }

.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
</style>
