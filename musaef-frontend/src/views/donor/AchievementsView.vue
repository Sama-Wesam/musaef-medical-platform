<template>
  <div
    class="achievements-page bg-light-gray min-vh-100 pb-5 font-arial"
    :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'"
  >
    <DonorHeader />

    <main class="container-fluid px-2 px-md-4">
      <!-- مؤشر التحميل -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-danger" role="status">
          <span class="visually-hidden">{{ t('loading') }}</span>
        </div>
        <p class="text-muted mt-2 fs-8">{{ t('loading') }}</p>
      </div>

      <template v-else>
        <!-- 1. كرت البطاقة الذكية الحمراء -->
        <div class="card border-0 rounded-4 shadow-lg p-3 p-md-4 mb-3 mb-md-4 text-white donor-red-card position-relative overflow-hidden">
          <div class="d-flex align-items-center justify-content-between flex-column flex-md-row flex-xl-row gap-3 gap-md-4">

            <!-- قسم فصيلة الدم والمستوى -->
            <div class="blood-type-section d-flex flex-column align-items-center justify-content-center text-center px-2">
              <div class="d-flex align-items-center gap-2 mb-1 justify-content-center">
                <div class="text-center">
                  <small class="text-white-50 fs-9 d-block text-center">{{ t('bloodType') }}</small>
                  <h2 class="fw-black text-white mb-0 lh-1 fs-3 fs-md-2 text-center" dir="ltr">{{ donorBloodType }}</h2>
                </div>
                <img :src="getIconUrl('blood-icon.png')" alt="قطرة" class="blood-drop-header-img" @error="handleImageFallback" />
              </div>

              <hr class="red-card-hr my-2 mx-auto" />

              <div class="text-center w-100">
                <small class="text-white-50 fs-9 d-block mb-0.5 text-center">{{ t('currentLevel') }}</small>
                <span class="fw-bold text-white fs-8 d-block text-center">{{ translateLevel(cardData?.level) }}</span>
              </div>
            </div>

            <div class="card-vertical-divider d-none d-xl-block"></div>

            <!-- اسم المتبرع والموقع -->
            <div class="text-center text-md-start ps-xl-3 flex-grow-1 w-100 w-md-auto" :class="currentLanguage === 'ar' ? 'text-md-end' : 'text-md-start'">
              <h3 class="fw-black text-white mb-1 fs-5 fs-md-4 text-truncate">{{ donorName }}</h3>
              <small class="text-white-50 fs-8 d-block">
                <i class="bi bi-geo-alt-fill me-1 text-white-50"></i>
                {{ translateLocation(cardData?.location) }}
              </small>
            </div>

            <!-- صورة المتبرع -->
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
            <div class="d-flex flex-column align-items-center text-center flex-grow-1 pe-xl-3 w-100 w-md-auto" :class="currentLanguage === 'ar' ? 'align-items-md-end text-md-end' : 'align-items-md-start text-md-start'">
              <h4 class="fw-bold text-white mb-1 fs-6 fs-md-5">{{ t('smartCardTitle') }}</h4>
              <small class="text-white-50 fs-9 d-block mb-1">{{ t('donorCode') }}</small>
              <strong class="fs-7 font-monospace tracking-wider mb-2 text-white">{{ cardData?.donor_code || 'BD123456789' }}</strong>

              <div class="badge-status-pill border border-warning border-opacity-75 rounded-pill px-3 py-1 fs-9 fw-bold text-warning d-inline-flex align-items-center gap-1 shadow-2xs">
                <i class="bi bi-star-fill text-warning"></i>
                <span>{{ t('activeDonor') }}</span>
              </div>
            </div>

            <!-- الباركود وزر التحميل -->
            <div class="barcode-wrapper text-center mx-auto ms-md-auto me-md-0">
              <div class="barcode-box bg-white p-2 rounded-3 d-inline-block shadow-sm">
                <img :src="getImageUrl('Barcode-Sticker-PNG-Transparent-Image 1.png')" alt="الباركود" class="barcode-img-large" />
              </div>
              <button
                @click="openReportModal"
                class="btn btn-outline-light btn-sm rounded-pill w-100 mt-2 fs-9 fw-bold border-white btn-download-card py-1.5 d-flex align-items-center justify-content-center gap-1"
              >
                <i class="bi bi-download"></i>
                <span>{{ t('downloadReport') }}</span>
              </button>
            </div>

          </div>
        </div>

        <!-- 2. كرت الأثر الإنساني والنقاط والمكافآت -->
        <div class="row g-3 g-lg-4 mb-3 mb-md-4">
          <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
              <div>
                <h5 class="fw-bold text-dark mb-3 mb-md-4 fs-6 fs-md-5 d-flex align-items-center justify-content-between" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                  <span>{{ t('humanImpactTitle') }}</span>
                  <span class="badge bg-danger-subtle text-danger fs-10 rounded-pill px-2 py-1 fw-bold"><i class="bi bi-robot me-1"></i>AI Impact Analytics</span>
                </h5>

                <div class="row align-items-center text-center gy-3">
                  <div class="col-6" :class="currentLanguage === 'ar' ? 'border-start' : 'border-end'">
                    <div class="d-flex align-items-center justify-content-center gap-2 gap-md-3 mb-1">
                      <h2 class="fw-black text-danger mb-0 fs-3 fs-md-2">{{ cardData?.units_donated || 8 }}</h2>
                      <img :src="getImageUrl('22 (2).png')" alt="وحدات الدم" class="impact-icon-img-large" @error="handleImageFallback" />
                    </div>
                    <small class="text-muted fs-8 d-block fw-bold mt-1">{{ t('unitsDonated') }}</small>
                  </div>

                  <div class="col-6">
                    <div class="d-flex align-items-center justify-content-center gap-2 gap-md-3 mb-1">
                      <h2 class="fw-black text-danger mb-0 fs-3 fs-md-2">{{ cardData?.cases_supported || 12 }}</h2>
                      <img :src="getIconUrl('hands.png')" alt="حالات" class="impact-icon-img-large" @error="handleImageFallback" />
                    </div>
                    <small class="text-muted fs-8 d-block fw-bold mt-1">{{ t('casesSupported') }}</small>
                  </div>
                </div>
              </div>

              <!-- رسالة التحليل الذكي المخصصة من الذكاء الاصطناعي -->
              <div class="p-2.5 p-md-3 bg-pink-light rounded-3 text-center mt-3 mt-md-4 border-0 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-stars text-danger fs-6 flex-shrink-0"></i>
                <span class="text-danger fw-bold fs-8">{{ cardData?.ai_impact_statement || t('lifeImpactMsg') }}</span>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
              <div>
                <h5 class="fw-bold text-dark mb-3 mb-md-4 fs-6 fs-md-5" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                  {{ t('pointsAndRewardsTitle') }}
                </h5>

                <div class="d-flex align-items-center justify-content-between gap-3 mb-3 mb-md-4 flex-wrap flex-sm-nowrap">
                  <div class="flex-grow-1 min-w-0 w-100" :class="currentLanguage === 'ar' ? 'text-start' : 'text-start'">
                    <div class="d-flex align-items-baseline justify-content-start gap-2 mb-1">
                      <h2 class="fw-black text-danger mb-0 fs-2 fs-md-1">{{ userPoints }}</h2>
                      <span class="text-muted fs-8 fw-bold">{{ t('totalPointsText') }}</span>
                    </div>

                    <div class="progress my-2 bg-light rounded-pill" style="height: 10px;">
                      <div class="progress-bar bg-warning rounded-pill" role="progressbar" :style="{ width: pointsProgress + '%' }"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center fs-9 text-muted fw-bold">
                      <span class="text-truncate">{{ cardData?.points_needed || 150 }} {{ t('pointsNeededText') }}</span>
                      <span>{{ cardData?.target_points || 500 }}</span>
                    </div>
                  </div>

                  <img :src="getImageUrl('coins.png')" alt="النقاط والمكافآت" class="coins-img-large mx-auto mx-sm-0" @error="handleImageFallback" />
                </div>
              </div>

              <!-- زر استبدل نقاطك -->
              <button
                @click="openRedeemModal"
                class="btn btn-pink-light text-danger w-100 rounded-3 py-2 fw-bold fs-8 border-0 mt-2 mt-md-3 d-flex align-items-center justify-content-center gap-2 shadow-sm"
              >
                <i class="bi bi-gift-fill text-danger"></i>
                <span>{{ t('redeemPointsBtn') }}</span>
              </button>
            </div>
          </div>
        </div>

        <!-- 3. شارات الإنجاز (تم ربطها ديناميكياً) -->
        <AchievementBadges :badges="badgesList" class="mb-3 mb-md-4" />

        <!-- 4. قسم سجل التبرعات -->
        <div class="card border-0 rounded-4 p-3 p-md-4 bg-white shadow-sm">
          <h5 class="fw-bold text-dark mb-3 mb-md-4 text-center position-relative d-inline-block mx-auto section-title-line fs-6 fs-md-5">
            {{ t('donationHistoryTitle') }}
          </h5>

          <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0 custom-history-table min-w-table" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
              <thead>
                <tr class="text-dark fs-8 border-bottom fw-bold text-nowrap">
                  <th class="py-3 pe-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">{{ t('dateCol') }}</th>
                  <th class="py-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">{{ t('hospitalCol') }}</th>
                  <th class="py-3 text-center">{{ t('bloodTypeCol') }}</th>
                  <th class="py-3 text-center">{{ t('unitsCol') }}</th>
                  <th class="py-3 text-center">{{ t('statusCol') }}</th>
                  <th class="py-3 text-center pe-4">{{ t('pointsEarnedCol') }}</th>
                </tr>
              </thead>
              <tbody class="fs-8">
                <tr v-for="(item, index) in activeDonationHistory" :key="index" class="border-bottom text-nowrap">
                  <td class="text-dark fw-bold pe-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                    {{ item?.date || item?.created_at?.substring(0, 10) || '2026-06-01' }}
                  </td>
                  <td class="text-dark fw-medium" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                    {{ translateHospital(item?.hospital_name || item?.hospital?.name || item?.facility_name) }}
                  </td>
                  <td class="fw-bold text-dark text-center" dir="ltr">
                    {{ item?.blood_type || item?.blood_type_name || donorBloodType }}
                  </td>
                  <td class="fw-bold text-dark text-center">
                    {{ item?.units || item?.units_donated || 1 }}
                  </td>
                  <td class="text-center">
                    <span :class="['badge px-3 px-md-4 py-2 rounded-3 fs-9 fw-bold', (item?.status === 'عاجلة' || item?.status === 'urgent') ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success']">
                      {{ t('completedStatus') }}
                    </span>
                  </td>
                  <td class="fw-bold text-secondary text-center pe-4">+{{ item?.points_earned || 50 }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </template>

      <!-- 5. نافذة التقرير الشامل (Report Modal) -->
      <div v-if="showReportModal" class="modal-backdrop-custom d-flex align-items-center justify-content-center p-2 p-md-3">
        <div class="card border-0 rounded-4 shadow-lg bg-white overflow-hidden modal-report-box w-100 max-w-750 position-relative" :class="currentLanguage === 'ar' ? 'dir-rtl' : 'dir-ltr'">

          <!-- الهيدر الخاص بالتقرير -->
          <div class="bg-danger text-white p-3 p-md-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-award-fill fs-4 text-warning"></i>
              <div>
                <h5 class="fw-bold mb-0 fs-6 fs-md-5">{{ t('reportModalTitle') }}</h5>
                <small class="text-white-50 fs-9">{{ t('reportModalSubTitle') }}</small>
              </div>
            </div>
            <button type="button" class="btn-close btn-close-white" @click="showReportModal = false"></button>
          </div>

          <div class="p-3 p-md-4 modal-scroll-body" style="max-height: 75vh; overflow-y: auto;">

            <!-- بطاقة تعريف المتبرع -->
            <div class="bg-light rounded-4 p-3 mb-3 border d-flex align-items-center justify-content-between flex-wrap gap-3">
              <div class="d-flex align-items-center gap-3">
                <img :src="donorAvatar" class="rounded-circle border border-2 border-danger shadow-sm" style="width: 55px; height: 55px; object-fit: cover;" @error="onAvatarError" />
                <div :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                  <h6 class="fw-bold text-dark mb-1 fs-7">{{ donorName }}</h6>
                  <span class="badge bg-danger-subtle text-danger fs-9 px-2 py-1 rounded-pill">{{ t('donorCode') }}: {{ cardData?.donor_code || 'BD123456789' }}</span>
                </div>
              </div>
              <div :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <small class="text-muted fs-9 d-block">{{ t('bloodType') }}</small>
                <span class="fw-black text-danger fs-5" dir="ltr">{{ donorBloodType }}</span>
              </div>
            </div>

            <!-- المؤشرات -->
            <div class="alert alert-success border-0 bg-success-subtle text-success rounded-3 p-3 mb-3 d-flex align-items-center gap-3">
              <i class="bi bi-heart-pulse-fill fs-3 text-success flex-shrink-0"></i>
              <div :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <h6 class="fw-bold mb-1 fs-8">{{ t('modalHeroTitle') }}</h6>
                <p class="mb-0 fs-9 text-dark">
                  {{ t('modalHeroMsgPart1') }} <strong class="text-danger">{{ cardData?.cases_supported || 12 }} {{ t('modalHeroMsgPart2') }}</strong> {{ t('modalHeroMsgPart3') }} <strong class="text-danger">{{ cardData?.units_donated || 8 }} {{ t('modalHeroMsgPart4') }}</strong>. {{ t('modalHeroMsgPart5') }}
                </p>
              </div>
            </div>

            <!-- جميع الإنجازات -->
            <div class="mb-4">
              <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 fs-8 d-flex align-items-center gap-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <i class="bi bi-trophy text-warning"></i>
                <span>{{ t('modalBadgesTitle') }}</span>
              </h6>
              <div class="row g-2">
                <div v-for="badge in badgesList" :key="badge.id" class="col-6 col-sm-3 text-center">
                  <div class="p-2 border rounded-3 bg-white h-100 shadow-2xs">
                    <img :src="getIconUrl(badge.image)" class="mb-1" style="width: 36px; height: 36px; object-fit: contain;" />
                    <strong class="d-block text-dark fs-9 text-truncate">{{ translateBadgeTitle(badge.title) }}</strong>
                    <small class="text-muted fs-10 d-block">{{ translateBadgeDate(badge.date) }}</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- سجل التبرعات -->
            <div class="mb-3">
              <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 fs-8 d-flex align-items-center gap-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                <i class="bi bi-journal-text text-danger"></i>
                <span>{{ t('modalHistoryTitle') }}</span>
              </h6>
              <div class="table-responsive">
                <table class="table table-sm align-middle fs-9 mb-0" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">
                  <thead class="bg-light">
                    <tr>
                      <th class="py-2 pe-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">{{ t('dateCol') }}</th>
                      <th class="py-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">{{ t('hospitalCol') }}</th>
                      <th class="py-2 text-center">{{ t('unitsCol') }}</th>
                      <th class="py-2 text-center">{{ t('pointsEarnedCol') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, idx) in activeDonationHistory" :key="idx" class="border-bottom">
                      <td class="fw-bold text-dark py-2 pe-3" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">{{ item?.date || '2026-06-15' }}</td>
                      <td class="text-dark py-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">{{ translateHospital(item?.hospital_name || item?.facility_name) }}</td>
                      <td class="text-center fw-bold text-dark py-2">{{ item?.units || item?.units_donated || 1 }}</td>
                      <td class="text-center text-success fw-bold py-2">+{{ item?.points_earned || 50 }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>

          <!-- الفوتر -->
          <div class="p-3 bg-light border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-pill fw-bold fs-9" @click="showReportModal = false">
              {{ t('closeBtn') }}
            </button>
            <button type="button" class="btn btn-danger btn-sm px-4 rounded-pill fw-bold fs-9 d-flex align-items-center gap-1 shadow-sm" @click="printOrDownloadReport">
              <i class="bi bi-printer"></i>
              <span>{{ t('printReportBtn') }}</span>
            </button>
          </div>

        </div>
      </div>

      <!-- 6. النافذة التفاعلية لاستبدال النقاط (Redeem Points Modal) -->
      <div v-if="showRedeemModal" class="modal-backdrop-custom d-flex align-items-center justify-content-center p-2 p-md-3">
        <div class="card border-0 rounded-4 shadow-lg bg-white overflow-hidden modal-report-box w-100 max-w-750 position-relative" :class="currentLanguage === 'ar' ? 'dir-rtl' : 'dir-ltr'">

          <div class="bg-dark text-white p-3 p-md-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-gift-fill fs-4 text-warning"></i>
              <div>
                <h5 class="fw-bold mb-0 fs-6 fs-md-5">{{ t('redeemModalTitle') }}</h5>
                <small class="text-white-50 fs-9">{{ t('redeemModalSub') }}</small>
              </div>
            </div>
            <button type="button" class="btn-close btn-close-white" @click="showRedeemModal = false"></button>
          </div>

          <div class="p-3 p-md-4">
            <div class="alert alert-warning border-0 bg-warning-subtle text-dark rounded-3 p-3 mb-3 d-flex align-items-center justify-content-between">
              <span class="fw-bold fs-8">{{ t('yourCurrentBalance') }}:</span>
              <span class="fs-5 fw-black text-danger">{{ userPoints }} {{ t('pointsText') }}</span>
            </div>

            <div class="row g-3">
              <div v-for="reward in rewardsList" :key="reward.id" class="col-12 col-md-6">
                <div class="p-3 border rounded-4 bg-white h-100 d-flex flex-column justify-content-between shadow-2xs">
                  <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <span class="badge bg-danger-subtle text-danger fw-bold fs-9">{{ translateRewardCategory(reward.category) }}</span>
                      <strong class="text-warning fs-8"><i class="bi bi-coin me-1"></i>{{ reward.cost }} {{ t('pointsText') }}</strong>
                    </div>
                    <h6 class="fw-bold text-dark fs-7 mb-1" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">{{ translateRewardTitle(reward) }}</h6>
                    <p class="text-muted fs-9 mb-2" :class="currentLanguage === 'ar' ? 'text-end' : 'text-start'">{{ translateRewardDesc(reward) }}</p>
                  </div>
                  <button
                    @click="redeemReward(reward)"
                    class="btn btn-outline-danger btn-sm rounded-pill w-100 fw-bold fs-9 mt-2"
                    :disabled="userPoints < reward.cost"
                  >
                    {{ userPoints >= reward.cost ? t('confirmRedeem') : t('notEnoughPoints') }}
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="p-3 bg-light border-top text-end">
            <button type="button" class="btn btn-secondary btn-sm px-4 rounded-pill fw-bold fs-9" @click="showRedeemModal = false">
              {{ t('closeBtn') }}
            </button>
          </div>

        </div>
      </div>

    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import donor from '@/api/donor';
import DonorHeader from '@/components/donor/DonorHeader.vue';
import AchievementBadges from '@/components/donor/Achievements/AchievementBadges.vue';
import { useAuthStore } from '@/stores/authStore';

import defaultAvatarImg from '@/assets/images/pngtree-whatsapp-default-profile-photo-vector-png-image_17034397.webp';

const authStore = useAuthStore();

const loading = ref(true);
const cardData = ref({});
const donationHistory = ref([]);
const showReportModal = ref(false);
const showRedeemModal = ref(false);

const userPoints = ref(350);

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const translations = {
  ar: {
    loading: 'جاري تحميل الإنجازات وسجل التبرعات...',
    bloodType: 'فصيلة الدم',
    currentLevel: 'المستوى الحالي',
    levelAdvanced: 'متقدم',
    levelBeginner: 'متبرع مبتدئ',
    smartCardTitle: 'بطاقة متبرع ذكية',
    donorCode: 'رقم المتبرع',
    activeDonor: 'متبرع نشط',
    downloadReport: 'تحميل Report',
    humanImpactTitle: 'الأثر الإنساني',
    unitsDonated: 'وحدات الدم المتبرع بها',
    casesSupported: 'حالة تم دعمها',
    lifeImpactMsg: 'بفضلك، تستمر الحياة في الأمل',
    pointsAndRewardsTitle: 'النقاط والمكافآت',
    totalPointsText: 'نقطة إجمالية',
    pointsNeededText: 'نقطة حتى المستوى الثاني',
    redeemPointsBtn: 'استبدل نقاطك',
    donationHistoryTitle: 'سجل التبرعات',
    dateCol: 'التاريخ',
    hospitalCol: 'المستشفى',
    bloodTypeCol: 'فصيلة الدم',
    unitsCol: 'الوحدات',
    statusCol: 'الحالة',
    pointsEarnedCol: 'النقاط المكتسبة',
    completedStatus: 'مكتمل',
    reportModalTitle: 'تقرير السجل الشامل للمتبرع',
    reportModalSubTitle: 'منصة مسعف الذكية للتبرع بالدم',
    modalHeroTitle: 'أنت بطل حقيقي ومساهم فعال!',
    modalHeroMsgPart1: 'ساهمت في إنقاذ',
    modalHeroMsgPart2: 'حالة طارئة',
    modalHeroMsgPart3: 'عبر تقديم',
    modalHeroMsgPart4: 'وحدات دم',
    modalHeroMsgPart5: 'استمرارك في التبرع يمنح الحياة أملًا جديدًا!',
    modalBadgesTitle: 'الإنجازات والشارات المحققة',
    modalHistoryTitle: 'سجل التبرعات السابقة',
    closeBtn: 'إغلاق',
    printReportBtn: 'طباعة / حفظ التقرير',
    redeemModalTitle: 'استبدال النقاط والمكافآت',
    redeemModalSub: 'استبدل نقاطك المكتسبة بمكافآت وقسائم حصرية',
    yourCurrentBalance: 'رصيدك الحالي',
    pointsText: 'نقطة',
    confirmRedeem: 'استبدال الآن',
    notEnoughPoints: 'نقاط غير كافية'
  },
  en: {
    loading: 'Loading achievements & history...',
    bloodType: 'Blood Type',
    currentLevel: 'Current Level',
    levelAdvanced: 'Advanced',
    levelBeginner: 'Beginner Donor',
    smartCardTitle: 'Smart Donor Card',
    donorCode: 'Donor Code',
    activeDonor: 'Active Donor',
    downloadReport: 'Download Report',
    humanImpactTitle: 'Human Impact',
    unitsDonated: 'Donated Blood Units',
    casesSupported: 'Cases Supported',
    lifeImpactMsg: 'Thanks to you, life continues with hope',
    pointsAndRewardsTitle: 'Points & Rewards',
    totalPointsText: 'Total Points',
    pointsNeededText: 'points until Level 2',
    redeemPointsBtn: 'Redeem Points',
    donationHistoryTitle: 'Donation History',
    dateCol: 'Date',
    hospitalCol: 'Hospital',
    bloodTypeCol: 'Blood Type',
    unitsCol: 'Units',
    statusCol: 'Status',
    pointsEarnedCol: 'Points Earned',
    completedStatus: 'Completed',
    reportModalTitle: 'Donor Comprehensive History Report',
    reportModalSubTitle: 'Musaef Smart Blood Donation Platform',
    modalHeroTitle: 'You are a true hero and active contributor!',
    modalHeroMsgPart1: 'You helped save',
    modalHeroMsgPart2: 'emergency cases',
    modalHeroMsgPart3: 'by donating',
    modalHeroMsgPart4: 'blood units',
    modalHeroMsgPart5: 'Your continued donation gives life new hope!',
    modalBadgesTitle: 'Achieved Badges & Achievements',
    modalHistoryTitle: 'Past Donation History',
    closeBtn: 'Close',
    printReportBtn: 'Print / Save Report',
    redeemModalTitle: 'Redeem Points & Rewards',
    redeemModalSub: 'Exchange your earned points for exclusive rewards',
    yourCurrentBalance: 'Current Balance',
    pointsText: 'Points',
    confirmRedeem: 'Redeem Now',
    notEnoughPoints: 'Not Enough Points'
  }
};

const rewardsList = ref([
  {
    id: 1,
    title_ar: 'قسيمة فحص طبي شامل مجاني',
    title_en: 'Free Full Medical Checkup Voucher',
    desc_ar: 'خصم 100% على الفحوصات الدورية في المختبرات المركزية',
    desc_en: '100% discount on routine tests at central labs',
    cost: 200,
    category_ar: 'صحي',
    category_en: 'Health'
  },
  {
    id: 2,
    title_ar: 'بطاقة خصم للمستلزمات الصيدلانية',
    title_en: 'Pharmaceutical Supplies Discount Card',
    desc_ar: 'خصم 20% على المنتجات الطبية والعناية بالصحة',
    desc_en: '20% discount on medical & healthcare products',
    cost: 150,
    category_ar: 'صيدليات',
    category_en: 'Pharmacy'
  },
  {
    id: 3,
    title_ar: 'وسام الشرف الرقمي للتبرع',
    title_en: 'Digital Honor Badge for Donation',
    desc_ar: 'إبراز الشرف الخاص بملفك على منصة مسعف',
    desc_en: 'Highlight special honor on your Musaef profile',
    cost: 100,
    category_ar: 'شارات',
    category_en: 'Badges'
  },
  {
    id: 4,
    title_ar: 'حقيبة متبرع صحية متكاملة',
    title_en: 'Comprehensive Donor Health Kit',
    desc_ar: 'مجموعة مستلزمات الفيتامينات والعناية الشاملة',
    desc_en: 'Comprehensive vitamins & care kit',
    cost: 300,
    category_ar: 'منتجات',
    category_en: 'Products'
  }
]);

const pointsProgress = computed(() => {
  const max = cardData.value?.target_points || 500;
  return Math.min(100, Math.round((userPoints.value / max) * 100));
});

const hospitalDict = {
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex',
  'مستشفى القدس تخصّصي': 'Al-Quds Specialized Hospital',
  'المستشفى الأندونيسي': 'Indonesian Hospital',
  'المستشفى الاندونيسي': 'Indonesian Hospital',
  'مستشفى الأندونيسي': 'Indonesian Hospital',
  'بنك الدم المركزي - وزارة الصحة': 'Central Blood Bank - Ministry of Health'
};

const locationDict = {
  'غزة - فلسطين': 'Gaza - Palestine',
  'غزة - الرمال': 'Gaza - Rimal',
  'شمال غزة - بيت لاهيا': 'North Gaza - Beit Lahia'
};

const badgeDict = {
  'منقذ حياة': 'Life Saver',
  '10 تبرعات': '10 Donations',
  '5 تبرعات': '5 Donations',
  'أول تبرع': 'First Donation'
};

const dateDict = {
  '1 يونيو 2024': '1 June 2024',
  '20 مايو 2025': '20 May 2025',
  '10 أبريل 2024': '10 April 2024',
  '15 مارس 2024': '15 March 2024'
};

const t = (key) => {
  const lang = currentLanguage.value === 'en' ? 'en' : 'ar';
  return translations[lang][key] || key;
};

const translateRewardTitle = (reward) => {
  return currentLanguage.value === 'en' ? reward.title_en : reward.title_ar;
};

const translateRewardDesc = (reward) => {
  return currentLanguage.value === 'en' ? reward.desc_en : reward.desc_ar;
};

const translateRewardCategory = (category) => {
  if (currentLanguage.value === 'en') {
    if (category === 'صحي') return 'Health';
    if (category === 'صيدليات') return 'Pharmacy';
    if (category === 'شارات') return 'Badges';
    if (category === 'منتجات') return 'Products';
  }
  return category;
};

const translateHospital = (name) => {
  if (!name) return currentLanguage.value === 'en' ? 'Al-Shifa Medical Complex' : 'مجمع الشفاء الطبي';
  const cleanName = name.trim();
  return currentLanguage.value === 'en' ? (hospitalDict[cleanName] || cleanName) : name;
};

const translateLocation = (loc) => {
  if (!loc) return currentLanguage.value === 'en' ? 'Gaza - Palestine' : 'غزة - فلسطين';
  return currentLanguage.value === 'en' ? (locationDict[loc] || loc) : loc;
};

const translateLevel = (lvl) => {
  if (!lvl) return currentLanguage.value === 'en' ? translations.en.levelBeginner : translations.ar.levelBeginner;
  if (currentLanguage.value === 'en') {
    if (lvl === 'متقدم' || lvl === 'Advanced') return translations.en.levelAdvanced;
    if (lvl === 'متبرع مبتدئ' || lvl === 'Beginner Donor') return translations.en.levelBeginner;
  }
  return lvl;
};

const translateBadgeTitle = (title) => {
  if (!title) return '';
  return currentLanguage.value === 'en' ? (badgeDict[title] || title) : title;
};

const translateBadgeDate = (date) => {
  if (!date) return '';
  return currentLanguage.value === 'en' ? (dateDict[date] || date) : date;
};

const fallbackHistory = [
  { date: '2026-06-15', hospital_name: 'مجمع الشفاء الطبي', blood_type: 'O+', units: 2, status: 'مكتمل', points_earned: 50 },
  { date: '2026-04-10', hospital_name: 'مستشفى القدس تخصّصي', blood_type: 'O+', units: 1, status: 'مكتمل', points_earned: 50 },
  { date: '2026-01-20', hospital_name: 'المستشفى الأندونيسي', blood_type: 'O+', units: 1, status: 'مكتمل', points_earned: 50 }
];

const activeDonationHistory = computed(() => {
  if (donationHistory.value && donationHistory.value.length > 0) {
    return donationHistory.value;
  }
  return fallbackHistory;
});

const donorName = computed(() => {
  return authStore.user?.name || cardData.value?.donor_name || 'Sama Wesam';
});

const donorBloodType = computed(() => {
  return authStore.user?.blood_type_name || authStore.user?.donor?.blood_type?.name || cardData.value?.blood_type || 'O+';
});

const openReportModal = () => {
  showReportModal.value = true;
};

const openRedeemModal = () => {
  showRedeemModal.value = true;
};

const redeemReward = (reward) => {
  if (userPoints.value >= reward.cost) {
    userPoints.value -= reward.cost;
    const rewardTitle = translateRewardTitle(reward);
    alert(currentLanguage.value === 'en'
      ? `Successfully redeemed: ${rewardTitle}!`
      : `تم استبدال المكافأة بنجاح: ${rewardTitle}!`);
  }
};

const printOrDownloadReport = () => {
  window.print();
};

const getIconUrl = (fileName) => {
  if (!fileName) return '';
  if (fileName.startsWith('http') || fileName.startsWith('data:')) return fileName;
  try {
    return new URL(`../../assets/icons/${fileName}`, import.meta.url).href;
  } catch (e) {
    return '';
  }
};

const getImageUrl = (fileName) => {
  if (!fileName) return '';
  if (fileName.startsWith('http') || fileName.startsWith('data:')) return fileName;
  try {
    return new URL(`../../assets/images/${fileName}`, import.meta.url).href;
  } catch (e) {
    return '';
  }
};

const donorAvatar = computed(() => {
  const userAvatar = authStore.userAvatar || authStore.user?.avatar || authStore.user?.donor?.user?.avatar;
  if (userAvatar) {
    if (userAvatar.startsWith('http') || userAvatar.startsWith('blob:')) {
      return userAvatar;
    }
    return `http://localhost:8000/storage/${userAvatar}`;
  }
  return defaultAvatarImg;
});

// استقبال الشارات المستحقة ديناميكياً من الـ Backend
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

    if (payload.points) {
      userPoints.value = payload.points;
    }

    // استبدال الشارات الافتراضية بالشارات الحقيقية الآتية من Backend الـ AI
    if (payload.badges && Array.isArray(payload.badges) && payload.badges.length) {
      badgesList.value = payload.badges.filter(b => b !== null);
    }

    let history = [];
    if (payload.donation_history) {
      history = payload.donation_history;
    } else {
      const historyRes = await donor.getDonationHistory();
      history = historyRes?.data?.data || historyRes?.data || [];
    }

    donationHistory.value = Array.isArray(history) ? history.filter(item => item !== null) : [];

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
  if (e?.target) {
    e.target.src = defaultAvatarImg;
  }
};

const handleImageFallback = (e) => {
  if (e?.target?.style) {
    e.target.style.display = 'none';
  }
};
</script>

<style scoped>
.font-arial {
  font-family: Arial, Helvetica, sans-serif !important;
}

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
.custom-history-table th { color: #1f2937; }
.custom-history-table td { padding: 12px 8px; }
@media (min-width: 768px) { .custom-history-table td { padding: 16px 8px; } }

/* Modal Styles */
.modal-backdrop-custom {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.55);
  z-index: 1050;
  backdrop-filter: blur(4px);
}

.modal-report-box {
  animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

.max-w-750 { max-width: 750px; }
.fs-7 { font-size: 0.92rem; }
.fs-8 { font-size: 0.82rem; }
.fs-9 { font-size: 0.72rem; }
.fs-10 { font-size: 0.65rem; }
.fw-black { font-weight: 900; }
.shadow-2xs { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
.bg-danger-subtle { background-color: #fee2e2 !important; }
.bg-success-subtle { background-color: #d1fae5 !important; }
.bg-warning-subtle { background-color: #fef3c7 !important; }
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
