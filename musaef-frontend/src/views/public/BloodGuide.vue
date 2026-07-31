<template>
  <div class="guide-page d-flex flex-column min-vh-100 dir-rtl">
    <!-- Navbar -->
    <Navbar />

    <!-- 1. الهيرو العلوي -->
    <section class="hero-guide-section py-5">
      <div class="container-fluid p-0">
        <div class="row g-0 align-items-center hero-row">

          <!-- النص الوصفي (يمين) -->
          <div class="col-lg-6 hero-content d-flex align-items-center justify-content-end px-3 px-md-5">
            <div class="hero-text-wrapper">
              <h1 class="fw-bold hero-main-title text-dark mb-1">دليل التبرع</h1>
              <h2 class="fw-bold hero-sub-title text-danger mb-3">والإرشادات الطبية</h2>
              <p class="hero-guide-desc text-secondary">
                دليل مختصر لفهم توافق فصائل الدم وشروط التبرع الآمن والإرشادات الطبية الأساسية لضمان تبرع آمن وإنقاذ الأرواح.
              </p>
            </div>
          </div>

          <!-- صورة الهيرو (يسار) - تجلب من src/assets/images -->
          <div class="col-lg-6 hero-image-col">
            <div class="hero-image-wrapper">
              <img
                :src="getImageUrl('blood-types-hero.png')"
                alt="دليل التبرع والإرشادات الطبية"
                class="hero-guide-img"
                @error="handleHeroFallback"
              />
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- 2. جدول توافق فصائل الدم + ميزة الذكاء الاصطناعي (Facility Recommendation AI) -->
    <section class="py-4 py-md-5 bg-light-gray">
      <div class="container px-3 px-md-4">
        <div class="text-center mb-4 mb-md-5">
          <h3 class="fw-bold text-dark section-title">جدول توافق فصائل الدم</h3>
          <div class="title-red-line mx-auto mt-2"></div>
        </div>

        <div class="row g-3 g-lg-4 align-items-stretch">
          <!-- البطاقات الجانبية للجدول -->
          <div class="col-12 col-lg-4 d-flex flex-column gap-3">
            <!-- بطاقة التبرع لمن - تجلب من src/assets/icons -->
            <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white flex-fill d-flex flex-row align-items-center justify-content-start gap-3 text-end">
              <div class="card-icon-box flex-shrink-0">
                <img :src="getIconUrl('Frame 2147225421.png')" alt="التوافق في التبرع" class="icon-img" />
              </div>
              <div class="text-end">
                <h6 class="fw-bold text-dark mb-1 fs-6">التوافق في التبرع (من يستطيع التبرع لمن؟)</h6>
                <p class="text-muted fs-8 mb-0 lh-base">يوضح من يمكنه التبرع لكل فصيلة دم بناءً على توافق فصائل الدم.</p>
              </div>
            </div>

            <!-- بطاقة الاستقبال من من - تجلب من src/assets/icons -->
            <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white flex-fill d-flex flex-row align-items-center justify-content-start gap-3 text-end">
              <div class="card-icon-box flex-shrink-0">
                <img :src="getIconUrl('streamline-sharp_blood-bag-donation-remix.png')" alt="التوافق في الاستقبال" class="icon-img" />
              </div>
              <div class="text-end">
                <h6 class="fw-bold text-dark mb-1 fs-6">التوافق في الاستقبال (من يستطيع استقبال الدم؟)</h6>
                <p class="text-muted fs-8 mb-0 lh-base">يوضح من يمكنه استقبال الدم من كل فصيلة دم بأمان.</p>
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
                    <span class="fw-bold text-dark d-block">متوافق</span>
                    <small class="text-muted fs-8">(يمكن التبرع / الاستقبال)</small>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="badge-dash"></span>
                  <div>
                    <span class="fw-bold text-dark d-block">غير متوافق</span>
                    <small class="text-muted fs-8">(لا يمكن التبرع / الاستقبال)</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- مربع الذكاء الاصطناعي (Facility Recommendation AI) -->
        <div class="row mt-4 mt-md-5">
          <div class="col-12">
            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white ai-finder-card">
              <div class="d-flex align-items-center gap-3 mb-3 flex-wrap justify-content-between">
                <div class="d-flex align-items-center gap-2">
                  <div class="ai-badge-icon bg-pink-light text-danger rounded-circle p-2">
                    <i class="bi bi-geo-alt-fill fs-5"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-0 fs-6">ابحث عن أقرب مركز تبرع في منطقتك (الذكاء الاصطناعي)</h5>
                    <small class="text-muted fs-8">يحدد النظام أقرب المراكز المسجلة بها مخزون متوفر وزمن الوصول المتوقع (ETA)</small>
                  </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                  <select v-model="selectedBloodType" class="form-select form-select-sm rounded-3 border-light-subtle fs-8">
                    <option value="O+">فصيلة O+</option>
                    <option value="O-">فصيلة O-</option>
                    <option value="A+">فصيلة A+</option>
                    <option value="A-">فصيلة A-</option>
                    <option value="B+">فصيلة B+</option>
                    <option value="B-">فصيلة B-</option>
                    <option value="AB+">فصيلة AB+</option>
                    <option value="AB-">فصيلة AB-</option>
                  </select>

                  <button @click="searchNearbyFacilities" class="btn btn-danger btn-sm rounded-3 px-3 fw-bold text-white flex-shrink-0 d-flex align-items-center gap-1">
                    <i class="bi bi-search"></i>
                    <span>بحث بالأقرب</span>
                  </button>
                </div>
              </div>

              <!-- نتائج البحث والتوصيات الذكية -->
              <div v-if="isLoadingFacilities" class="text-center py-4 text-muted fs-8">
                <div class="spinner-border spinner-border-sm text-danger me-2" role="status"></div>
                جاري حساب المسافة والبحث عن أقرب المراكز...
              </div>

              <div v-else-if="nearbyFacilities.length > 0" class="row g-3 mt-1">
                <div v-for="(center, idx) in nearbyFacilities" :key="idx" class="col-12 col-md-4">
                  <div class="p-3 rounded-3 bg-light-gray border border-light-subtle text-end h-100 d-flex flex-column justify-content-between">
                    <div>
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-danger-subtle text-danger rounded-pill fs-8 px-2 py-1">{{ center.facility_type }}</span>
                        <small class="text-muted fs-8"><i class="bi bi-clock me-1"></i>{{ center.eta_minutes }} دقيقة وصول</small>
                      </div>
                      <h6 class="fw-bold text-dark mb-1 fs-7">{{ center.facility_name }}</h6>
                      <p class="text-muted fs-8 mb-2">{{ center.recommendation_message }}</p>
                    </div>

                    <div class="pt-2 border-top border-light-subtle d-flex justify-content-between align-items-center">
                      <small class="text-secondary fs-8"><i class="bi bi-droplet-fill text-danger me-1"></i>متوفر: <strong>{{ center.available_units }} وحدات</strong></small>
                      <small class="text-danger fw-bold fs-8"><i class="bi bi-cursor-fill me-1"></i>{{ center.distance_km }} كم</small>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="text-center py-3 text-muted fs-8 bg-light-gray rounded-3">
                اضغطي على "بحث بالأقرب" لعرض التوصيات والمسافات الزمانية لأقرب 3 بنوك دم أو مستشفيات بها مخزون.
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- 3. قسم نصائح وإرشادات التبرع - تجلب الأيقونات من src/assets/icons -->
    <section id="medical-tips" class="py-4 py-md-5 bg-white">
      <div class="container text-center px-3 px-md-4">
        <h3 class="fw-bold text-dark section-title mb-1">قسم النصائح والإرشادات</h3>
        <div class="title-red-line mx-auto mb-4 mb-md-5"></div>

        <div class="row g-3 g-md-4 justify-content-center">
          <div class="col-12 col-md-4">
            <div class="p-4 bg-light-gray rounded-4 h-100 tip-card shadow-sm">
              <div class="tip-icon-box mb-3 mx-auto">
                <img :src="getIconUrl('mdi_user.png')" alt="العمر المناسب" class="tip-icon-img" />
              </div>
              <h5 class="fw-bold text-dark mb-2 fs-6">العمر المناسب</h5>
              <p class="text-muted fs-7 mb-0 lh-lg">يجب أن يكون عمرك بين 18 و 65 عاماً للتبرع بالدم.</p>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="p-4 bg-light-gray rounded-4 h-100 tip-card shadow-sm">
              <div class="tip-icon-box mb-3 mx-auto">
                <img :src="getIconUrl('game-icons_weight-scale.png')" alt="الوزن المناسب" class="tip-icon-img" />
              </div>
              <h5 class="fw-bold text-dark mb-2 fs-6">الوزن المناسب</h5>
              <p class="text-muted fs-7 mb-0 lh-lg">يجب أن يكون وزنك 50 كجم على الأقل للتبرع بالدم.</p>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="p-4 bg-light-gray rounded-4 h-100 tip-card shadow-sm">
              <div class="tip-icon-box mb-3 mx-auto">
                <img :src="getIconUrl('material-symbols_credit-card-clock-outline-rounded.png')" alt="مدة التبرع" class="tip-icon-img" />
              </div>
              <h5 class="fw-bold text-dark mb-2 fs-6">متى يمكن التبرع مرة أخرى؟</h5>
              <p class="text-muted fs-7 mb-0 lh-lg">يمكنك التبرع كل 8 أسابيع (56 يوماً) للرجال، وكل 12 أسبوعاً (84 يوماً) للنساء.</p>
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
              <h5 class="fw-bold text-dark mb-4 text-center">الأسئلة الشائعة</h5>

              <div class="position-relative mb-4">
                <input
                  type="text"
                  class="form-control faq-search-input pe-5 rounded-3 fs-8"
                  placeholder="ابحث عن سؤال..."
                  v-model="searchQuery"
                />
                <i class="bi bi-search position-absolute top-50 translate-middle-y me-3 text-muted"></i>
              </div>

              <div class="accordion" id="faqAccordion">
                <div
                  v-for="(faq, index) in filteredFaqs"
                  :key="index"
                  class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-sm"
                >
                  <h2 class="accordion-header">
                    <button
                      class="accordion-button fs-8 fw-bold"
                      :class="{ 'collapsed': index !== activeFaqIndex, 'active-faq-btn': index === activeFaqIndex }"
                      type="button"
                      data-bs-toggle="collapse"
                      :data-bs-target="'#faq' + index"
                      @click="activeFaqIndex = index"
                    >
                      {{ faq.question }}
                    </button>
                  </h2>
                  <div
                    :id="'faq' + index"
                    class="accordion-collapse collapse"
                    :class="{ 'show': index === activeFaqIndex }"
                    data-bs-parent="#faqAccordion"
                  >
                    <div class="accordion-body fs-8 text-secondary lh-lg" :class="{ 'text-danger bg-pink-light': faq.isHighlighted }">
                      {{ faq.answer }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="bg-white p-3 p-md-4 rounded-4 shadow-sm h-100">
              <div class="d-flex align-items-center gap-3 mb-4">
                <!-- أيقونة تواصل معنا تجلب من src/assets/icons -->
                <img :src="getIconUrl('stash_headset-solid.png')" alt="تواصل معنا" class="support-icon-img" />
                <div>
                  <h6 class="fw-bold text-dark mb-1">إذا لم تجد الإجابة</h6>
                  <small class="text-muted fs-8">تواصل معنا وفريقنا جاهز للرد على استفساراتك.</small>
                </div>
              </div>

              <form @submit.prevent="handleSubmit">
                <div class="mb-3">
                  <input
                    type="text"
                    class="form-control form-control-custom rounded-3 fs-8"
                    placeholder="أدخل اسمك الكامل"
                    v-model="contactForm.name"
                    required
                  />
                </div>
                <div class="mb-3">
                  <input
                    type="email"
                    class="form-control form-control-custom rounded-3 fs-8"
                    placeholder="أدخل بريدك الإلكتروني"
                    v-model="contactForm.email"
                    required
                  />
                </div>
                <div class="mb-3">
                  <input
                    type="text"
                    class="form-control form-control-custom rounded-3 fs-8"
                    placeholder="موضوع الرسالة"
                    v-model="contactForm.subject"
                    required
                  />
                </div>
                <div class="mb-3">
                  <textarea
                    class="form-control form-control-custom rounded-3 fs-8"
                    rows="4"
                    placeholder="اكتب رسالتك هنا..."
                    v-model="contactForm.message"
                    required
                  ></textarea>
                </div>

                <div v-if="successMessage" class="alert alert-success fs-8 py-2 mb-3">
                  {{ successMessage }}
                </div>
                <div v-if="errorMessage" class="alert alert-danger fs-8 py-2 mb-3">
                  {{ errorMessage }}
                </div>

                <button
                  type="submit"
                  class="btn btn-danger w-100 rounded-3 py-2 fw-bold text-white shadow-sm mt-2"
                  :disabled="isSubmitting"
                >
                  <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
                  {{ isSubmitting ? 'جاري الإرسال...' : 'تواصل معنا' }}
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
import { ref, computed, onMounted, onUpdated } from 'vue'
import apiClient from '@/api/axios'
import Navbar from '@/components/common/Navbar.vue'
import Footer from '@/components/common/Footer.vue'

// دالة لجلب الصور من مجلد src/assets/images
const getImageUrl = (fileName) => {
  return new URL(`../../assets/images/${fileName}`, import.meta.url).href
}

// دالة لجلب الأيقونات من مجلد src/assets/icons
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

/* ========================================================
   ميزة الذكاء الاصطناعي (Facility Recommendation AI)
======================================================== */
const selectedBloodType = ref('O+')
const nearbyFacilities = ref([])
const isLoadingFacilities = ref(false)

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
    console.error('خطأ في تحديد المراكز القريبة:', error)
    isLoadingFacilities.value = false
  }
}

const fetchFacilitiesApi = async (lat, lng) => {
  try {
    const res = await apiClient.get('/public/nearby-facilities', {
      params: { lat, lng, blood_type: selectedBloodType.value }
    })
    const data = Array.isArray(res) ? res : (res?.data || [])
    nearbyFacilities.value = data
  } catch (err) {
    console.error('خطأ الاستجابة:', err)
  } finally {
    isLoadingFacilities.value = false
  }
}

const searchQuery = ref('')
const activeFaqIndex = ref(3)

const faqs = ref([
  { question: 'هل أستطيع التبرع إذا كنت أتناول أدويـة؟', answer: 'تعتمد إمكانية التبرع على نوع الدواء. يرجى استشارة الطبيب في المركز قبل التبرع.' },
  { question: 'كم مرة يمكنني التبرع؟', answer: 'يمكنك التبرع كل 8 أسابيع للرجال، وكل 12 أسبوعاً للنساء.' },
  { question: 'ما العمر المناسب للتبرع؟', answer: 'العمر المناسب للتبرع بالدم هو من 18 إلى 65 سنة.' },
  { question: 'هل التبرع آمن؟', answer: 'يعتبر التبرع بالدم آمن تماماً، يتم استخدام أدوات معقمة وجديدة لمرة واحدة فقط لضمان سلامتك، ويتم اتباع أعلى معايير السلامة والإجراءات الطبية.', isHighlighted: true }
])

const filteredFaqs = computed(() => {
  if (!searchQuery.value) return faqs.value
  return faqs.value.filter(f => f.question.includes(searchQuery.value) || f.answer.includes(searchQuery.value))
})

const handleHeroFallback = (e) => {
  e.target.src = getImageUrl('hero-drop.png')
}

/* ===========================
      نموذج التواصل معنا
=========================== */
const contactForm = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const isSubmitting = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const handleSubmit = async () => {
  isSubmitting.value = true
  successMessage.value = ''
  errorMessage.value = ''

  try {
    const res = await apiClient.post('/public/contact', contactForm.value)
    successMessage.value = res?.message || 'تم إرسال رسالتك بنجاح، وسنقوم بالرد عليك في أقرب وقت!'
    contactForm.value = { name: '', email: '', subject: '', message: '' }
  } catch (error) {
    errorMessage.value = error?.message || error?.response?.data?.message || 'حدث خطأ أثناء إرسال الرسالة، يرجى المحاولة لاحقاً.'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.dir-rtl {
  direction: rtl;
  font-family: Arial, sans-serif;
}

.bg-light-gray {
  background-color: #f8fafc;
}

/* ==========================================
   Hero Section Styles (Matching Homepage Exactly)
========================================== */
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

.hero-main-title {
  width: 100%;
  font-size: 52px;
  font-weight: 800;
  line-height: 1.35;
  color: #0F172A;
  text-align: right;
  margin-bottom: 5px;
}

.hero-sub-title {
  width: 100%;
  font-size: 42px;
  font-weight: 800;
  line-height: 1.2;
  color: #dc2626;
  text-align: right;
  margin-bottom: 18px;
}

.hero-guide-desc {
  width: 100%;
  font-size: 25px;
  color: #6B7280;
  line-height: 2;
  text-align: right;
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

/* ==========================================
   Compatibility Table Section
========================================== */
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

/* بطاقة ميزة الذكاء الاصطناعي AI Finder */
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

/* Responsive Adjustments */
@media (max-width: 1200px) {
  .hero-text-wrapper {
    margin-left: 0;
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
    margin: 0 auto;
    align-items: center;
    text-align: center;
    max-width: 100%;
  }

  .hero-main-title,
  .hero-sub-title,
  .hero-guide-desc {
    text-align: center;
  }

  .hero-main-title { font-size: 32px; }
  .hero-sub-title { font-size: 26px; }
  .hero-guide-desc { font-size: 16px; }

  .hero-guide-img {
    width: 100%;
    max-width: 420px;
    height: auto;
    object-fit: contain;
    transform: none;
    margin-left: 0;
  }
}
</style>
