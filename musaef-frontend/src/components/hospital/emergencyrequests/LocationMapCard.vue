<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end">
    <strong class="text-dark fs-8 d-block mb-2">الموقع على الخريطة</strong>
    <div class="rounded-3 overflow-hidden border position-relative mb-3" style="height: 120px;">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d108928.261234!2d34.45!3d31.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14fd7f0800000000%3A0x100!2sGaza!5e0!3m2!1sen!2s!4v1600000000000!5m2!1sen!2s"
        width="100%"
        height="100%"
        style="border:0;"
        allowfullscreen=""
        loading="lazy">
      </iframe>
    </div>

    <div class="d-flex gap-2">
      <button @click="$emit('accept')" class="btn btn-danger flex-fill rounded-3 py-2 fw-bold fs-8 text-nowrap">قبول الطلب</button>
      <button @click="$emit('reject')" class="btn btn-outline-danger flex-fill rounded-3 py-2 fw-bold fs-8 text-nowrap">رفض الطلب</button>
    </div>
  </div>
</template>

<script setup>
defineEmits(['accept', 'reject']);
</script>

<style scoped>
.fs-8 { font-size: 0.8rem; }
</style>
