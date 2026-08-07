<template>
  <div class="stats-grid">
    <DashboardStatCard
      title="الحالات الحرجة"
      value="7"
      label="حالة عاجلة"
      icon="bi bi-exclamation-triangle"
      icon-color="#ef4444"
      icon-background="#fdebec"
      progress-color="#ef4444"
      progress-width="60%"
      footer="+3"
      footer-text="من أمس"
      footer-color="#ef4444"
      footer-background="#fdebec"
    />

    <DashboardStatCard
      title="المتبرعون المسجلون اليوم"
      value="42"
      label="متبرع"
      icon="bi bi-people"
      icon-color="#22a447"
      icon-background="#edf9f0"
      progress-color="#22a447"
      progress-width="60%"
      footer="+15"
      footer-text="اليوم"
      footer-color="#22a447"
      footer-background="#edf9f0"
    />

    <DashboardStatCard
      title="وحدات الدم المتوفرة"
      value="156"
      label="وحدة"
      icon="bi bi-droplet"
      icon-color="#7c3aed"
      icon-background="#f4edff"
      progress-color="#7c3aed"
      progress-width="60%"
      footer="+3"
      footer-text="من أمس"
      footer-color="#7c3aed"
      footer-background="#f4edff"
    />

    <DashboardStatCard
      title="الطلبات النشطة"
      value="23"
      label="طلب"
      icon="bi bi-calendar2-check"
      icon-color="#2563eb"
      icon-background="#eef3ff"
      progress-color="#2563eb"
      progress-width="60%"
      footer="+3"
      footer-text="من أمس"
      footer-color="#2563eb"
      footer-background="#eef3ff"
    />
  </div>
</template>

<script setup>
import DashboardStatCard from './DashboardStatCard.vue'
</script>

<style scoped>
.stats-grid {
  width: 100%;
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 14px;
  margin-bottom: 18px;
}

@media (max-width: 1100px) {
  .stats-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 650px) {
  .stats-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }
}
</style>