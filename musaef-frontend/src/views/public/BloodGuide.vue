<template>
  <div class="guide-page d-flex flex-column min-vh-100" :dir="currentLanguage === 'ar' ? 'rtl' : 'ltr'">
    <!-- Navbar -->
    <Navbar />

    <!-- 1. الهيرو العلوي -->
    <section class="hero-guide-section py-5">
      <div class="container-fluid p-0">
        <div class="row g-0 align-items-center hero-row">

          <!-- النص الوصفي -->
          <div class="col-lg-6 hero-content d-flex align-items-center justify-end px-3 px-md-5">
            <div class="hero-text-wrapper">
              <h1 class="fw-bold hero-main-title text-dark mb-1">{{ $t('guide.heroTitle') }}</h1>
              <h2 class="fw-bold hero-sub-title text-danger mb-3">{{ $t('guide.heroSubtitle') }}</h2>
              <p class="hero-guide-desc text-secondary">
                {{ $t('guide.heroDesc') }}
              </p>
            </div>
          </div>

          <!-- صورة الهيرو -->
          <div class="col-lg-6 hero-image-col">
            <div class="hero-image-wrapper">
              <img
                :src="getImageUrl('blood-types-hero.png')"
                :alt="$t('guide.heroTitle')"
                class="hero-guide-img"
                loading="lazy"
                @error="handleHeroFallback"
              />
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- 2. جدول توافق فصائل الدم + ميزة الذكاء الاصطناعي -->
    <section class="py-4 py-md-5 bg-light-gray">
      <div class="container px-3 px-md-4">
        <div class="text-center mb-4 mb-md-5">
          <h3 class="fw-bold text-dark section-title">{{ $t('guide.compatibilityTitle') }}</h3>
          <div class="title-red-line mx-auto mt-2"></div>
        </div>

        <div class="row g-3 g-lg-4 align-items-stretch">
          <!-- البطاقات الجانبية للجدول -->
          <div class="col-12 col-lg-4 d-flex flex-column gap-3">
            <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white flex-fill d-flex flex-row align-items-center justify-content-start gap-3 text-start-dir">
              <div class="card-icon-box flex-shrink-0">
                <img :src="getIconUrl('Frame 2147225421.png')" alt="Icon" class="icon-img" loading="lazy" />
              </div>
              <div class="text-start-dir">
                <h6 class="fw-bold text-dark mb-1 fs-6">{{ $t('guide.donateCompatTitle') }}</h6>
                <p class="text-muted fs-8 mb-0 lh-base">{{ $t('guide.donateCompatDesc') }}</p>
              </div>
            </div>

            <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white flex-fill d-flex flex-row align-items-center justify-content-start gap-3 text-start-dir">
              <div class="card-icon-box flex-shrink-0">
                <img :src="getIconUrl('streamline-sharp_blood-bag-donation-remix.png')" alt="Icon" class="icon-img" loading="lazy" />
              </div>
              <div class="text-start-dir">
                <h6 class="fw-bold text-dark mb-1 fs-6">{{ $t('guide.receiveCompatTitle') }}</h6>
                <p class="text-muted fs-8 mb-0 lh-base">{{ $t('guide.receiveCompatDesc') }}</p>
              </div>
            </div>
          </div>

          <!-- الجدول التفاعلي -->
          <div class="col-12 col-lg-8">
            <div class="table-container bg-white p-3 p-md-4 rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between">
              <div class="table-responsive">
                <table class="table table-bordered text-center align-middle compatibility-table mb-0">
                  <thead>
                    <tr>
                      <th class="bg-gray-header"></th>
                      <th class="bg-gray-header">-O</th>
                      <th class="bg-gray-header">+O</th>
                      <th class="bg-gray-header">-AB</th>
                      <th class="bg-gray-header">+AB</th>
                      <th class="bg-gray-header">-B</th>
                      <th class="bg-gray-header">+B</th>
                      <th class="bg-gray-header">-A</th>
                      <th class="bg-gray-header">+A</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(row, rowType) in compatibilityMatrix" :key="rowType">
                      <td class="fw-bold bg-gray-header text-dark">{{ rowType }}</td>
                      <td v-for="(status, colType) in row" :key="colType">
                        <span v-if="status" class="status-icon success-icon">
                          <i class="bi bi-check-circle-fill"></i>
                        </span>
                        <span v-else class="status-icon dash-icon">–</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 gap-md-5 mt-4 pt-3 border-top fs-7">
                <div class="d-flex align-items-center gap-2">
                  <i class="bi bi-check-circle-fill text-success fs-5"></i>
                  <div>
                    <span class="fw-bold text-dark d-block">{{ $t('guide.compatible') }}</span>
                    <small class="text-muted fs-8">({{ $t('guide.compatibleSub') }})</small>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="badge-dash"></span>
                  <div>
                    <span class="fw-bold text-dark d-block">{{ $t('guide.incompatible') }}</span>
                    <small class="text-muted fs-8">({{ $t('guide.incompatibleSub') }})</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- مربع الذكاء الاصطناعي / البحث الذكي عن المراكز -->
        <div class="row mt-4 mt-md-5">
          <div class="col-12">
            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white ai-finder-card">
              <div class="d-flex align-items-center gap-3 mb-3 flex-wrap justify-content-between">
                <div class="d-flex align-items-center gap-2">
                  <div class="ai-badge-icon bg-pink-light text-danger rounded-circle p-2">
                    <i class="bi bi-geo-alt-fill fs-5"></i>
                  </div>
                  <div class="text-start-dir">
                    <h5 class="fw-bold text-dark mb-0 fs-6">{{ $t('guide.aiSearchTitle') }}</h5>
                    <small class="text-muted fs-8">{{ $t('guide.aiSearchDesc') }}</small>
                  </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                  <select v-model="selectedBloodType" class="form-select form-select-sm rounded-3 border-light-subtle fs-8">
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                  </select>

                  <button @click="searchNearbyFacilities" class="btn btn-danger btn-sm rounded-3 px-3 fw-bold text-white flex-shrink-0 d-flex align-items-center gap-1">
                    <i class="bi bi-search"></i>
                    <span>{{ $t('guide.searchBtn') }}</span>
                  </button>
                </div>
              </div>

              <!-- نتائج البحث القريبة -->
              <div v-if="isLoadingFacilities" class="text-center py-4 text-muted fs-8">
                <div class="spinner-border spinner-border-sm text-danger me-2" role="status"></div>
                {{ $t('guide.searching') }}
              </div>

              <div v-else-if="localizedFacilities.length > 0" class="row g-3 mt-1">
                <div v-for="(center, idx) in localizedFacilities" :key="idx" class="col-12 col-md-4">
                  <div class="p-3 rounded-3 bg-light-gray border border-light-subtle text-start-dir h-100 d-flex flex-column justify-content-between">
                    <div>
                      <!-- رأس البطاقة -->
                      <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                        <span class="badge bg-danger-subtle text-danger rounded-pill fs-8 px-2 py-1 flex-shrink-0">
                          {{ center.facility_type }}
                        </span>
                        <small class="text-muted fs-8 flex-shrink-0 ms-auto">
                          <i class="bi bi-clock me-1"></i>{{ center.eta_minutes }} {{ $t('guide.etaUnit') || 'min' }}
                        </small>
                      </div>

                      <!-- اسم المستشفى ووصف التوصية -->
                      <h6 class="fw-bold text-dark mb-1 fs-7 text-truncate" :title="center.facility_name">
                        {{ center.facility_name }}
                      </h6>
                      <p class="text-muted fs-8 mb-2 lh-sm">
                        {{ center.recommendation_message }}
                      </p>
                    </div>

                    <!-- أسفل البطاقة -->
                    <div class="pt-2 border-top border-light-subtle d-flex justify-content-between align-items-center">
                      <small class="text-secondary fs-8">
                        <i class="bi bi-droplet-fill text-danger me-1"></i>
                        {{ $t('guide.available') || 'Available' }}: <strong>{{ center.available_units }} {{ $t('guide.unitsUnit') || 'units' }}</strong>
                      </small>
                      <small class="text-danger fw-bold fs-8">
                        <i class="bi bi-cursor-fill me-1"></i>{{ center.distance_km }} {{ $t('guide.kmUnit') || 'km' }}
                      </small>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="text-center py-3 text-muted fs-8 bg-light-gray rounded-3">
                {{ $t('guide.aiPromptPlaceholder') }}
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- 3. قسم نصائح وإرشادات التبرع -->
    <section id="medical-tips" class="py-4 py-md-5 bg-white">
      <div class="container text-center px-3 px-md-4">
        <h3 class="fw-bold text-dark section-title mb-1">{{ $t('guide.tipsSectionTitle') }}</h3>
        <div class="title-red-line mx-auto mb-4 mb-md-5"></div>

        <div class="row g-3 g-md-4 justify-content-center">
          <div class="col-12 col-md-4">
            <div class="p-4 bg-light-gray rounded-4 h-100 tip-card shadow-sm">
              <div class="tip-icon-box mb-3 mx-auto">
                <img :src="getIconUrl('mdi_user.png')" alt="Icon" class="tip-icon-img" loading="lazy" />
              </div>
              <h5 class="fw-bold text-dark mb-2 fs-6">{{ $t('guide.tips.age.title') }}</h5>
              <p class="text-muted fs-7 mb-0 lh-lg">{{ $t('guide.tips.age.desc') }}</p>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="p-4 bg-light-gray rounded-4 h-100 tip-card shadow-sm">
              <div class="tip-icon-box mb-3 mx-auto">
                <img :src="getIconUrl('game-icons_weight-scale.png')" alt="Icon" class="tip-icon-img" loading="lazy" />
              </div>
              <h5 class="fw-bold text-dark mb-2 fs-6">{{ $t('guide.tips.weight.title') }}</h5>
              <p class="text-muted fs-7 mb-0 lh-lg">{{ $t('guide.tips.weight.desc') }}</p>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="p-4 bg-light-gray rounded-4 h-100 tip-card shadow-sm">
              <div class="tip-icon-box mb-3 mx-auto">
                <img :src="getIconUrl('material-symbols_credit-card-clock-outline-rounded.png')" alt="Icon" class="tip-icon-img" loading="lazy" />
              </div>
              <h5 class="fw-bold text-dark mb-2 fs-6">{{ $t('guide.tips.frequency.title') }}</h5>
              <p class="text-muted fs-7 mb-0 lh-lg">{{ $t('guide.tips.frequency.desc') }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. الأسئلة الشائعة + نموذج التواصل -->
    <section id="faq" class="py-4 py-md-5 bg-light-gray">
      <div class="container px-3 px-md-4">
        <div class="row g-3 g-lg-4">
          <div class="col-12 col-lg-6">
            <div class="bg-white p-3 p-md-4 rounded-4 shadow-sm h-100">
              <h5 class="fw-bold text-dark mb-4 text-center">{{ $t('guide.faqTitle') }}</h5>

              <div class="position-relative mb-4">
                <input
                  type="text"
                  class="form-control faq-search-input pe-5 rounded-3 fs-8 text-start-dir"
                  :placeholder="$t('guide.faqSearchPlaceholder')"
                  v-model="searchQuery"
                />
                <i class="bi bi-search position-absolute top-50 translate-middle-y text-muted" :class="currentLanguage === 'ar' ? 'left-icon' : 'right-icon'"></i>
              </div>

              <div class="accordion" id="faqAccordion">
                <div
                  v-for="(faq, index) in filteredFaqs"
                  :key="index"
                  class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-sm"
                >
                  <h2 class="accordion-header">
                    <button
                      class="accordion-button fs-8 fw-bold text-start-dir"
                      :class="{ 'collapsed': index !== activeFaqIndex, 'active-faq-btn': index === activeFaqIndex }"
                      type="button"
                      data-bs-toggle="collapse"
                      :data-bs-target="'#faq' + index"
                      @click="activeFaqIndex = index"
                    >
                      {{ $t(faq.questionKey) }}
                    </button>
                  </h2>
                  <div
                    :id="'faq' + index"
                    class="accordion-collapse collapse"
                    :class="{ 'show': index === activeFaqIndex }"
                    data-bs-parent="#faqAccordion"
                  >
                    <div class="accordion-body fs-8 text-secondary lh-lg text-start-dir" :class="{ 'text-danger bg-pink-light': faq.isHighlighted }">
                      {{ $t(faq.answerKey) }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="bg-white p-3 p-md-4 rounded-4 shadow-sm h-100">
              <div class="d-flex align-items-center gap-3 mb-4 text-start-dir">
                <img :src="getIconUrl('stash_headset-solid.png')" alt="Icon" class="support-icon-img" loading="lazy" />
                <div>
                  <h6 class="fw-bold text-dark mb-1">{{ $t('guide.contactHeading') }}</h6>
                  <small class="text-muted fs-8">{{ $t('guide.contactSubheading') }}</small>
                </div>
              </div>

              <form @submit.prevent="handleSubmit">
                <!-- Honeypot Field -->
                <div style="display:none;">
                  <input type="text" v-model="contactForm.website_hp" />
                </div>

                <div class="mb-3">
                  <input
                    type="text"
                    class="form-control form-control-custom rounded-3 fs-8 text-start-dir"
                    :placeholder="$t('guide.contactForm.name')"
                    v-model="contactForm.name"
                    required
                  />
                </div>
                <div class="mb-3">
                  <input
                    type="email"
                    class="form-control form-control-custom rounded-3 fs-8 text-start-dir"
                    :placeholder="$t('guide.contactForm.email')"
                    v-model="contactForm.email"
                    required
                  />
                </div>
                <div class="mb-3">
                  <input
                    type="text"
                    class="form-control form-control-custom rounded-3 fs-8 text-start-dir"
                    :placeholder="$t('guide.contactForm.subject')"
                    v-model="contactForm.subject"
                    required
                  />
                </div>
                <div class="mb-3">
                  <textarea
                    class="form-control form-control-custom rounded-3 fs-8 text-start-dir"
                    rows="4"
                    :placeholder="$t('guide.contactForm.message')"
                    v-model="contactForm.message"
                    required
                  ></textarea>
                </div>

                <div v-if="successMessage" class="alert alert-success fs-8 py-2 mb-3 text-start-dir">
                  {{ successMessage }}
                </div>
                <div v-if="errorMessage" class="alert alert-danger fs-8 py-2 mb-3 text-start-dir">
                  {{ errorMessage }}
                </div>

                <button
                  type="submit"
                  class="btn btn-danger w-100 rounded-3 py-2 fw-bold text-white shadow-sm mt-2"
                  :disabled="isSubmitting"
                >
                  <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
                  {{ isSubmitting ? $t('guide.contactForm.sending') : $t('guide.contactForm.send') }}
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUpdated, defineAsyncComponent } from 'vue'
import { useI18n } from 'vue-i18n'
import apiClient from '@/api/axios'
import Navbar from '@/components/common/Navbar.vue'

const Footer = defineAsyncComponent(() => import('@/components/common/Footer.vue'))

const { locale, te, t } = useI18n()
const currentLanguage = computed(() => locale.value || 'ar')

const getImageUrl = (fileName) => {
  return new URL(`../../assets/images/${fileName}`, import.meta.url).href
}

const getIconUrl = (fileName) => {
  return new URL(`../../assets/icons/${fileName}`, import.meta.url).href
}

const handleScrollToHash = () => {
  const hash = window.location.hash
  if (hash) {
    const targetElement = document.querySelector(hash)
    if (targetElement) {
      targetElement.scrollIntoView({ behavior: 'smooth' })
    }
  }
}

onMounted(() => {
  handleScrollToHash()
  searchNearbyFacilities()
})

onUpdated(() => {
  handleScrollToHash()
})

const compatibilityMatrix = ref({
  '+A':  { '-O': true,  '+O': true,  '-AB': false, '+AB': true,  '-B': false, '+B': false, '-A': true,  '+A': true  },
  '-A':  { '-O': true,  '+O': true,  '-AB': true,  '+AB': true,  '-B': false, '+B': false, '-A': true,  '+A': true  },
  '+B':  { '-O': true,  '+O': true,  '-AB': false, '+AB': true,  '-B': true,  '+B': true,  '-A': false, '+A': false },
  '-B':  { '-O': true,  '+O': false, '-AB': true,  '+AB': true,  '-B': true,  '+B': true,  '-A': false, '+A': false },
  '+AB': { '-O': true,  '+O': true,  '-AB': true,  '+AB': true,  '-B': true,  '+B': true,  '-A': true,  '+A': true  },
  '-AB': { '-O': true,  '+O': true,  '-AB': true,  '+AB': true,  '-B': true,  '+B': true,  '-A': true,  '+A': false },
  '+O':  { '-O': true,  '+O': false, '-AB': true,  '+AB': false, '-B': false, '+B': false, '-A': false, '+A': false },
  '-O':  { '-O': true,  '+O': false, '-AB': true,  '+AB': false, '-B': false, '+B': false, '-A': false, '+A': false }
})

const selectedBloodType = ref('O+')
const nearbyFacilities = ref([])
const isLoadingFacilities = ref(false)

const facilityTranslationMap = {
  'مستشفى حكومي': 'Government Hospital',
  'بنك دم مركزي': 'Central Blood Bank',
  'مستشفى أهلي': 'Private Hospital',
  'مستشفى': 'Hospital',
  'مجمع الشفاء الطبي': 'Al-Shifa Medical Complex',
  'جمعية بنك الدم المركزي': 'Central Blood Bank Society',
  'بنك الدم المركزي - وزارة الصحة': 'Central Blood Bank - Ministry of Health',
  'مستشفى القدس': 'Al-Quds Hospital'
}

const defaultFacilitiesKeys = [
  {
    facility_type_key: 'guide.facilities.f1.type',
    facility_name_key: 'guide.facilities.f1.name',
    default_type: 'مستشفى حكومي',
    default_name: 'مجمع الشفاء الطبي',
    eta_minutes: 5,
    available_units: 8,
    distance_km: 1.2
  },
  {
    facility_type_key: 'guide.facilities.f2.type',
    facility_name_key: 'guide.facilities.f2.name',
    default_type: 'بنك دم مركزي',
    default_name: 'جمعية بنك الدم المركزي',
    eta_minutes: 9,
    available_units: 14,
    distance_km: 2.8
  },
  {
    facility_type_key: 'guide.facilities.f3.type',
    facility_name_key: 'guide.facilities.f3.name',
    default_type: 'مستشفى أهلي',
    default_name: 'بنك الدم المركزي - وزارة الصحة',
    eta_minutes: 12,
    available_units: 5,
    distance_km: 4.1
  }
]

const localizedFacilities = computed(() => {
  const isEn = currentLanguage.value === 'en'
  let list = []

  // ضمان تحويل القيمة لمصفوفة سليمة لمنع أخطاء .map()
  const safeFacilities = Array.isArray(nearbyFacilities.value) ? nearbyFacilities.value : []

  if (safeFacilities.length === 0) {
    list = defaultFacilitiesKeys.map(item => {
      let type = te(item.facility_type_key) ? t(item.facility_type_key) : item.default_type
      let name = te(item.facility_name_key) ? t(item.facility_name_key) : item.default_name
      let rec = ''

      if (isEn) {
        type = facilityTranslationMap[type] || type
        name = facilityTranslationMap[name] || name
        rec = `Recommended: Highest compatibility for ${selectedBloodType.value} (${item.available_units} units available).`
      } else {
        rec = `يوصى به: الأعلى ملاءمة لفصيلة ${selectedBloodType.value} (متوفر ${item.available_units} وحدة).`
      }

      return {
        facility_type: type,
        facility_name: name,
        recommendation_message: rec,
        eta_minutes: item.eta_minutes,
        available_units: item.available_units,
        distance_km: item.distance_km
      }
    })
  } else {
    list = safeFacilities.map(item => {
      let type = item.facility_type || item.type || ''
      let name = item.facility_name || item.name || ''
      let rec = item.recommendation_message || ''

      if (isEn) {
        type = item.facility_type_en || facilityTranslationMap[type] || type
        name = item.facility_name_en || facilityTranslationMap[name] || name
        if (item.recommendation_message_en) {
          rec = item.recommendation_message_en
        } else if (!rec || /[\u0600-\u06FF]/.test(rec)) {
          rec = `Recommended: Highest compatibility for ${selectedBloodType.value} (${item.available_units || 0} units available).`
        }
      } else {
        if (!rec || !/[\u0600-\u06FF]/.test(rec)) {
          rec = `يوصى به: الأعلى ملاءمة لفصيلة ${selectedBloodType.value} (متوفر ${item.available_units || 0} وحدة).`
        }
      }

      return {
        ...item,
        facility_type: type,
        facility_name: name,
        recommendation_message: rec
      }
    })
  }

  // تحديد الحد الأقصى للعرض بـ 3 بطاقات فقط
  return list.slice(0, 3)
})

const searchNearbyFacilities = async () => {
  isLoadingFacilities.value = true
  try {
    let lat = 31.5017
    let lng = 34.4668

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (pos) => {
          lat = pos.coords.latitude
          lng = pos.coords.longitude
          fetchFacilitiesApi(lat, lng)
        },
        () => {
          fetchFacilitiesApi(lat, lng)
        },
        { timeout: 3000 }
      )
    } else {
      fetchFacilitiesApi(lat, lng)
    }
  } catch (error) {
    isLoadingFacilities.value = false
  }
}

const fetchFacilitiesApi = async (lat, lng) => {
  const cacheKey = `facilities_cache_${selectedBloodType.value}_${currentLanguage.value}`
  try {
    const res = await apiClient.get('/public/nearby-facilities', {
      params: { lat, lng, blood_type: selectedBloodType.value }
    })

    // استخراج مصفوفة المراكز بدقة متوافقة مع جميع أشكال الاستجابات
    let data = []
    if (Array.isArray(res)) {
      data = res
    } else if (res && res.data) {
      if (Array.isArray(res.data)) {
        data = res.data
      } else if (res.data.data && Array.isArray(res.data.data)) {
        data = res.data.data
      }
    }

    nearbyFacilities.value = data
    if (data.length > 0) {
      localStorage.setItem(cacheKey, JSON.stringify(data))
    }
  } catch (err) {
    const cachedData = localStorage.getItem(cacheKey)
    if (cachedData) {
      try {
        const parsed = JSON.parse(cachedData)
        nearbyFacilities.value = Array.isArray(parsed) ? parsed : []
      } catch (e) {
        nearbyFacilities.value = []
      }
    } else {
      nearbyFacilities.value = []
    }
  } finally {
    isLoadingFacilities.value = false
  }
}

const searchQuery = ref('')
const activeFaqIndex = ref(3)

const faqs = ref([
  { questionKey: 'guide.faqs.q1.q', answerKey: 'guide.faqs.q1.a' },
  { questionKey: 'guide.faqs.q2.q', answerKey: 'guide.faqs.q2.a' },
  { questionKey: 'guide.faqs.q3.q', answerKey: 'guide.faqs.q3.a' },
  { questionKey: 'guide.faqs.q4.q', answerKey: 'guide.faqs.q4.a', isHighlighted: true }
])

const filteredFaqs = computed(() => {
  if (!searchQuery.value) return faqs.value
  const query = searchQuery.value.toLowerCase()
  return faqs.value.filter(f => t(f.questionKey).toLowerCase().includes(query) || t(f.answerKey).toLowerCase().includes(query))
})

const handleHeroFallback = (e) => {
  e.target.src = getImageUrl('hero-drop.png')
}

const contactForm = ref({
  name: '',
  email: '',
  subject: '',
  message: '',
  website_hp: ''
})

const isSubmitting = ref(false)
const lastSubmitTime = ref(0)
const successMessage = ref('')
const errorMessage = ref('')

const handleSubmit = async () => {
  if (contactForm.value.website_hp) return;

  const now = Date.now()
  if (now - lastSubmitTime.value < 10000) {
    errorMessage.value = t('guide.contactForm.rateLimitMsg') || 'الرجاء الانتظار قليلاً قبل إعادة الإرسال.'
    return
  }

  isSubmitting.value = true
  successMessage.value = ''
  errorMessage.value = ''

  try {
    const res = await apiClient.post('/public/contact', contactForm.value)
    successMessage.value = res?.message || t('guide.contactForm.successMsg')
    contactForm.value = { name: '', email: '', subject: '', message: '', website_hp: '' }
    lastSubmitTime.value = Date.now()
  } catch (error) {
    errorMessage.value = error?.message || error?.response?.data?.message || t('guide.contactForm.errorMsg')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.guide-page,
.guide-page * {
  font-family: Arial, sans-serif !important;
}

.bg-light-gray {
  background-color: #f8fafc;
}

.hero-guide-section {
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

.hero-main-title {
  width: 100%;
  font-size: 52px;
  font-weight: 800;
  line-height: 1.35;
  color: #0F172A;
  text-align: inherit;
  margin-bottom: 5px;
}

.hero-sub-title {
  width: 100%;
  font-size: 42px;
  font-weight: 800;
  line-height: 1.2;
  color: #dc2626;
  text-align: inherit;
  margin-bottom: 18px;
}

.hero-guide-desc {
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

.hero-guide-img {
  width: 115%;
  max-width: none;
  height: auto;
  object-fit: contain;
  transform: translateX(-60px);
  user-select: none;
  pointer-events: none;
  margin-left: 40px;
}

[dir="ltr"] .hero-guide-img {
  transform: translateX(60px);
  margin-left: 0;
  margin-right: 40px;
}

.section-title {
  font-size: 28px;
}

.title-red-line {
  width: 50px;
  height: 3px;
  background-color: #dc2626;
  border-radius: 2px;
}

.bg-gray-header {
  background-color: #f1f5f9 !important;
  color: #111827;
  font-weight: 700;
  font-size: 0.9rem;
}

.compatibility-table th,
.compatibility-table td {
  padding: 10px 8px;
  border: 1px solid #e2e8f0;
  white-space: nowrap;
}

.status-icon.success-icon {
  color: #16a34a;
  font-size: 1.1rem;
}

.status-icon.dash-icon {
  color: #9ca3af;
  font-weight: bold;
}

.badge-dash {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background-color: #cbd5e1;
  display: inline-block;
  position: relative;
}

.badge-dash::after {
  content: '';
  width: 10px;
  height: 2px;
  background-color: #64748b;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.card-icon-box {
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.ai-finder-card {
  border: 1px solid #f1f5f9;
  border-top: 3px solid #dc2626 !important;
}

.bg-pink-light {
  background-color: #fdecec !important;
}

.tip-card {
  border: 1px solid #f1f5f9;
  transition: transform 0.3s ease;
}

.tip-card:hover {
  transform: translateY(-5px);
}

.tip-icon-box {
  width: 50px;
  height: 50px;
}

.tip-icon-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.faq-search-input {
  background-color: #f8fafc;
  border: 1px solid #e2e8f0;
  padding: 10px 15px;
}

.left-icon {
  left: 15px;
}

.right-icon {
  right: 15px;
}

.accordion-button {
  background-color: #f8fafc;
  color: #1f2937;
  box-shadow: none !important;
  padding: 14px 18px;
}

.accordion-button:not(.collapsed) {
  background-color: #f8fafc;
  color: #1f2937;
}

.accordion-button.active-faq-btn {
  color: #dc2626 !important;
  background-color: #fdecec !important;
}

.support-icon-img {
  width: 45px;
  height: 45px;
  object-fit: contain;
}

.text-start-dir {
  text-align: right;
}

[dir="ltr"] .text-start-dir {
  text-align: left;
}

.form-control-custom {
  background-color: #f8fafc;
  border: 1px solid #e2e8f0;
  padding: 10px 14px;
}

.form-control-custom:focus {
  border-color: #fca5a5;
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.82rem; }

@media (max-width: 1200px) {
  .hero-text-wrapper,
  [dir="ltr"] .hero-text-wrapper {
    margin-left: 0;
    margin-right: 0;
  }
}

@media (max-width: 991px) {
  .hero-guide-section,
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
    justify-content: center !important;
  }

  .hero-text-wrapper {
    margin: 0 auto !important;
    align-items: center !important;
    text-align: center !important;
    max-width: 100%;
  }

  .hero-main-title,
  .hero-sub-title,
  .hero-guide-desc {
    text-align: center !important;
  }

  .hero-main-title { font-size: 32px; }
  .hero-sub-title { font-size: 26px; }
  .hero-guide-desc { font-size: 16px; }

  .hero-guide-img {
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
