<template>
  <section class="facility-card">
    <h2>معلومات الجهة الطبية</h2>

    <div class="information-list">
      <div class="information-row">
        <i class="bi bi-building"></i>

        <span class="information-label">
          اسم المستشفى
        </span>

        <strong>{{ facility.name }}</strong>
      </div>

      <div class="information-row">
        <i class="bi bi-geo-alt"></i>

        <span class="information-label">
          العنوان
        </span>

        <strong>{{ facility.address }}</strong>
      </div>

      <div class="information-row">
        <i class="bi bi-clock"></i>

        <span class="information-label">
          ساعات العمل
        </span>

        <strong>{{ facility.workingHours }}</strong>
      </div>

      <div class="information-row">
        <i class="bi bi-telephone-fill"></i>

        <span class="information-label">
          رقم الهاتف
        </span>

        <a :href="`tel:${facility.phone}`">
          {{ facility.phone }}
        </a>
      </div>

      <div class="information-row">
        <i class="bi bi-telephone-fill"></i>

        <span class="information-label">
          رقم الطوارئ
        </span>

        <a :href="`tel:${facility.emergencyPhone}`">
          {{ facility.emergencyPhone }}
        </a>
      </div>

      <div class="information-row">
        <i class="bi bi-envelope"></i>

        <span class="information-label">
          البريد الإلكتروني
        </span>

        <a :href="`mailto:${facility.email}`">
          {{ facility.email }}
        </a>
      </div>
    </div>
  </section>
</template>

<script setup>
defineProps({
  facility: {
    type: Object,
    required: true,
  },
})
</script>

<style scoped>
.facility-card {
  width: 100%;
  min-height: 380px;
  padding: 28px 20px;

  border: 1px solid #eceef2;
  border-radius: 12px;

  background-color: #ffffff;
}

.facility-card h2 {
  margin: 0 0 25px;

  color: #111827;
  font-size: 16px;
  font-weight: 800;
  text-align: center;
}

.information-list {
  display: flex;
  flex-direction: column;
}

.information-row {
  min-height: 48px;

  display: grid;
  grid-template-columns:
    22px
    88px
    minmax(0, 1fr);

  align-items: center;
  gap: 8px;

  border-bottom: 1px solid #eceef2;
}

.information-row:last-child {
  border-bottom: 0;
}

.information-row i {
  color: #ef4444;
  font-size: 16px;
  text-align: center;
}

.information-label {
  color: #9ca3af;
  font-size: 9px;
}

.information-row strong,
.information-row a {
  min-width: 0;

  color: #6b7280;
  text-align: left;
  text-decoration: none;

  font-size: 9px;
  font-weight: 500;

  overflow-wrap: anywhere;
}

.information-row a:hover {
  color: #dc2626;
}

@media (max-width: 600px) {
  .facility-card {
    min-height: auto;
    padding: 20px 15px;
  }

  .information-row {
    grid-template-columns:
      22px
      75px
      minmax(0, 1fr);
  }
}
</style>