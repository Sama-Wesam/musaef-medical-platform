<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white h-100 overflow-hidden text-end dir-rtl">
    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
      <h6 class="fw-bold text-dark mb-0 fs-7">رادار الطوارئ المباشر</h6>
    </div>
    <div class="rounded-3 overflow-hidden position-relative flex-grow-1" style="min-height: 220px;">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d108928.261234!2d34.45!3d31.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14fd7f0800000000%3A0x100!2sGaza!5e0!3m2!1sen!2s!4v1600000000000!5m2!1sen!2s"
        width="100%"
        height="100%"
        style="border:0;"
        allowfullscreen
        loading="lazy">
      </iframe>
    </div>
  </div>
</template>

<style scoped>
.fs-7 { font-size: 0.9rem; }
.dir-rtl { direction: rtl; }
</style>
