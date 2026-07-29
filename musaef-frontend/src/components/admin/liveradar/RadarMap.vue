<template>
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative h-100 radar-map-card">
    <!-- الخريطة الجغرافية التفاعلية -->
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d108928.261234!2d34.45!3d31.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14fd7f0800000000%3A0x100!2sGaza!5e0!3m2!1sen!2s!4v1600000000000!5m2!1sen!2s"
      width="100%"
      height="100%"
      class="map-iframe"
      style="border:0;"
      allowfullscreen
      loading="lazy">
    </iframe>

    <!-- دليل مستوى الخطورة -->
    <SeverityLegend />
  </div>
</template>

<script setup>
import SeverityLegend from '@/components/admin/liveradar/SeverityLegend.vue';
</script>

<style scoped>
.radar-map-card {
  min-height: 350px;
}

.map-iframe {
  min-height: 350px;
}

@media (min-width: 768px) {
  .radar-map-card, .map-iframe {
    min-height: 480px;
  }
}

@media (min-width: 992px) {
  .radar-map-card, .map-iframe {
    min-height: 560px;
  }
}
</style>
