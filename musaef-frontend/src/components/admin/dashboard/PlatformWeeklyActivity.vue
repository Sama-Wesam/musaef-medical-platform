<template>
  <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100" :class="currentLanguage === 'ar' ? 'dir-rtl text-end' : 'dir-ltr text-start'">
    <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 flex-wrap gap-2">
      <h6 class="fw-bold text-dark mb-0 fs-7">{{ t('weeklyTitle') }}</h6>

      <div class="d-flex align-items-center gap-3 fs-9 fw-semibold text-muted">
        <div class="d-flex align-items-center gap-1">
          <span>{{ t('donors') }}</span>
          <span class="dot-indicator" style="background-color: #DC2626;"></span>
        </div>
        <div class="d-flex align-items-center gap-1">
          <span>{{ t('requests') }}</span>
          <span class="dot-indicator" style="background-color: #2563EB;"></span>
        </div>
      </div>
    </div>

    <!-- المخطط المزدوج -->
    <div class="chart-scroll-wrapper overflow-x-auto flex-grow-1">
      <div class="position-relative pt-2 pb-1 min-chart-width" style="min-height: 180px;">
        <div class="chart-y-axis d-flex flex-column justify-content-between position-absolute w-100 h-100 pe-2 text-muted fs-9">
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>4K</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>3K</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>2K</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>1K</span></div>
          <div class="d-flex justify-content-between align-items-center"><span class="border-bottom flex-grow-1 ms-3 border-light-subtle"></span><span>0</span></div>
        </div>

        <svg class="w-100 h-100 position-absolute top-0 start-0 pe-4 ps-2" viewBox="0 0 500 130" preserveAspectRatio="none">
          <defs>
            <linearGradient id="redGradient" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#DC2626" stop-opacity="0.15"/>
              <stop offset="100%" stop-color="#DC2626" stop-opacity="0.0"/>
            </linearGradient>
            <linearGradient id="blueGradient2" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#2563EB" stop-opacity="0.15"/>
              <stop offset="100%" stop-color="#2563EB" stop-opacity="0.0"/>
            </linearGradient>
          </defs>

          <path d="M 10,70 L 80,45 L 150,65 L 220,15 L 300,25 L 380,40 L 460,10 L 460,130 L 10,130 Z" fill="url(#redGradient)" />
          <path d="M 10,70 L 80,45 L 150,65 L 220,15 L 300,25 L 380,40 L 460,10" fill="none" stroke="#DC2626" stroke-width="3" />

          <path d="M 10,95 L 80,75 L 150,100 L 220,55 L 300,48 L 380,78 L 460,98 L 460,130 L 10,130 Z" fill="url(#blueGradient2)" />
          <path d="M 10,95 L 80,75 L 150,100 L 220,55 L 300,48 L 380,78 L 460,98" fill="none" stroke="#2563EB" stroke-width="3" />

          <circle cx="10" cy="70" r="5" fill="#DC2626" />
          <circle cx="80" cy="45" r="5" fill="#DC2626" />
          <circle cx="150" cy="65" r="5" fill="#DC2626" />
          <circle cx="220" cy="15" r="5" fill="#DC2626" />
          <circle cx="300" cy="25" r="5" fill="#DC2626" />
          <circle cx="380" cy="40" r="5" fill="#DC2626" />
          <circle cx="460" cy="10" r="5" fill="#DC2626" />

          <circle cx="10" cy="95" r="5" fill="#2563EB" />
          <circle cx="80" cy="75" r="5" fill="#2563EB" />
          <circle cx="150" cy="100" r="5" fill="#2563EB" />
          <circle cx="220" cy="55" r="5" fill="#2563EB" />
          <circle cx="300" cy="48" r="5" fill="#2563EB" />
          <circle cx="380" cy="78" r="5" fill="#2563EB" />
          <circle cx="460" cy="98" r="5" fill="#2563EB" />
        </svg>
      </div>

      <div class="d-flex justify-content-between text-muted fs-8 pt-3 px-2 min-chart-width">
        <span>{{ translateDay('sat') }}</span>
        <span>{{ translateDay('sun') }}</span>
        <span>{{ translateDay('mon') }}</span>
        <span>{{ translateDay('tue') }}</span>
        <span>{{ translateDay('wed') }}</span>
        <span>{{ translateDay('thu') }}</span>
        <span>{{ translateDay('fri') }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const currentLanguage = computed(() => localStorage.getItem('musaef_lang') || 'ar');

const dictionary = {
  ar: { weeklyTitle: 'النشاط العام للمنصة (أسبوعي)', donors: 'المتبرعون', requests: 'الطلبات' },
  en: { weeklyTitle: 'General Platform Activity (Weekly)', donors: 'Donors', requests: 'Requests' }
};

const dayDict = {
  ar: { sat: 'السبت', sun: 'الأحد', mon: 'الإثنين', tue: 'الثلاثاء', wed: 'الأربعاء', thu: 'الخميس', fri: 'الجمعة' },
  en: { sat: 'Sat', sun: 'Sun', mon: 'Mon', tue: 'Tue', wed: 'Wed', thu: 'Thu', fri: 'Fri' }
};

const t = (key) => dictionary[currentLanguage.value === 'en' ? 'en' : 'ar'][key] || key;
const translateDay = (key) => dayDict[currentLanguage.value === 'en' ? 'en' : 'ar'][key];
</script>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }

.min-chart-width { min-width: 400px; }
@media (min-width: 768px) { .min-chart-width { min-width: 100%; } }

.chart-scroll-wrapper { scrollbar-width: none; -ms-overflow-style: none; }
.chart-scroll-wrapper::-webkit-scrollbar { display: none; }

.dot-indicator {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  display: inline-block;
  flex-shrink: 0;
}
.dir-rtl { direction: rtl; }
.dir-ltr { direction: ltr; }
</style>
