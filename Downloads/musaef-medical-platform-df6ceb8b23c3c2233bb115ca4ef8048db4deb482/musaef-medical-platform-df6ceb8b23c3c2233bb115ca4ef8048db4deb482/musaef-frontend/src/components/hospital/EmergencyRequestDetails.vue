<template>
  <section class="side-card request-details">
    <div class="side-card-title">
      <i class="bi bi-geo-alt"></i>
      <h3>تفاصيل الطلب</h3>
    </div>

    <dl>
      <div>
        <dt>رقم الطلب</dt>
        <dd>{{ request.id }}</dd>
      </div>

      <div>
        <dt>حالة الطلب</dt>
        <dd class="active-status">
          {{ request.status }}
        </dd>
      </div>

      <div>
        <dt>المستشفى</dt>
        <dd>{{ request.location }}</dd>
      </div>

      <div>
        <dt>فصيلة الدم</dt>
        <dd class="blood-value">
          {{ request.bloodType }}
        </dd>
      </div>

      <div>
        <dt>عدد الوحدات</dt>
        <dd>{{ request.requiredUnits }}</dd>
      </div>

      <div>
        <dt>تاريخ الإنشاء</dt>
        <dd>{{ request.date }}</dd>
      </div>
    </dl>

    <p class="request-description">
      <i class="bi bi-exclamation-triangle"></i>
      {{ request.description }}
    </p>
  </section>
</template>

<script setup>
defineProps({
  request: {
    type: Object,
    required: true,
  },
})
</script>

<style scoped>
.side-card {
  padding: 15px;
  border: 1px solid #eceef2;
  border-radius: 10px;
  background-color: #ffffff;
}

.side-card-title {
  margin-bottom: 13px;
  display: flex;
  align-items: center;
  gap: 7px;
}

.side-card-title i {
  color: #ef4444;
  font-size: 13px;
}

.side-card-title h3 {
  margin: 0;
  font-size: 11px;
  font-weight: 800;
}

dl {
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

dl > div {
  display: flex;
  justify-content: space-between;
  gap: 10px;
}

dt {
  color: #6b7280;
  font-size: 1px;
}

dd {
  margin: 0;
  color: #111827;
  text-align: left;
  font-size: 8px;
  font-weight: 700;
}

.active-status {
  padding: 3px 12px;
  border-radius: 10px;
  color: #dc2626;
  background-color: #fdebec;
}

.blood-value {
  color: #dc2626;
  font-size: 12px;
}

.request-description {
  margin: 13px 0 0;
  color: #374151;
  font-size: 10px;
  line-height: 1.7;
}

.request-description i {
  margin-left: 4px;
  color: #ef4444;
}
</style>