<template>
  <div class="row g-3 g-lg-4 align-items-stretch dir-rtl">
    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100 text-end">
        <h6 class="fw-bold text-dark mb-4 fs-6">معلومات الجهة الطبية</h6>

        <div class="d-flex flex-column gap-3 fs-7">
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">🏢</span>
            <span class="text-muted ms-1">اسم المستشفى:</span>
            <span class="fw-bold text-dark">{{ hospitalData.name }}</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">📍</span>
            <span class="text-muted ms-1">العنوان:</span>
            <span class="text-dark">{{ hospitalData.address || 'غير محدد' }}</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">⏰</span>
            <span class="text-muted ms-1">ساعات العمل:</span>
            <span class="text-dark">{{ hospitalData.working_hours }}</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">📞</span>
            <span class="text-muted ms-1">رقم الهاتف:</span>
            <span class="text-dark" dir="ltr">{{ hospitalData.phone_number || 'غير متوفر' }}</span>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="text-danger">✉️</span>
            <span class="text-muted ms-1">البريد الإلكتروني:</span>
            <span class="text-dark text-break" dir="ltr">{{ hospitalData.contact_email }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden h-100 position-relative" style="min-height: 280px;">
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
  </div>
</template>

<script setup>
defineProps({
  hospitalData: {
    type: Object,
    required: true
  }
});
</script>

<style scoped>
.fs-7 { font-size: 0.85rem; }
.dir-rtl { direction: rtl; }
</style>
