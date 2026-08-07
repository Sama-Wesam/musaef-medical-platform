<template>
  <section class="side-card">
    <div class="side-card-title">
      <i class="bi bi-people"></i>
      <h3>المستجيبون ({{ responders.length + 3 }})</h3>
    </div>

    <div class="responders-list">
      <div
        v-for="responder in responders"
        :key="responder.id"
        class="responder-row"
      >
        <img
          :src="responder.image"
          :alt="`صورة ${responder.name}`"
          class="responder-image"
          @error="handleImageError"
        />

        <strong>{{ responder.name }}</strong>

        <span class="responder-blood">
          {{ responder.bloodType }}
        </span>

        <span
          class="compatibility"
          :class="compatibilityClass(responder.compatibility)"
        >
          {{ responder.compatibility }}%
        </span>
      </div>
    </div>

    <button type="button" class="all-responders-button">
      عرض جميع المستجيبين
      <i class="bi bi-chevron-left"></i>
    </button>
  </section>
</template>

<script setup>
defineProps({
  responders: {
    type: Array,
    default: () => [],
  },
})

const compatibilityClass = (value) => {
  if (value >= 90) {
    return 'high'
  }

  if (value >= 85) {
    return 'medium'
  }

  return 'low'
}

const handleImageError = (event) => {
  event.target.src = '/images/person.png'
}
</script>

<style scoped>
.side-card {
  padding: 15px;
  border: 1px solid #eceef2;
  border-radius: 10px;
  background-color: #ffffff;
}

.side-card-title {
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 7px;
}

.side-card-title i {
  color: #111827;
  font-size: 22px;
}

.side-card-title h3 {
  margin: 0;
  font-size: 12px;
  font-weight: 800;
}

.responders-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.responder-row {
  display: grid;
  grid-template-columns: 28px 1fr 26px 38px;
  align-items: center;
  gap: 6px;
}

.responder-image {
  width: 25px;
  height: 25px;
  border-radius: 50%;
  object-fit: cover;
  background-color: #f3f4f6;
}

.responder-row strong {
  font-size: 12px;
}

.responder-blood {
  color: #dc2626;
  font-size: 8px;
  font-weight: 800;
}

.compatibility {
  padding: 4px 5px;
  border-radius: 10px;
  text-align: center;
  font-size: 12px;
}

.compatibility.high {
  color: #16a34a;
  background-color: #edf9f0;
}

.compatibility.medium {
  color: #f97316;
  background-color: #fff1e7;
}

.compatibility.low {
  color: #dc2626;
  background-color: #fdebec;
}

.all-responders-button {
  width: 100%;
  height: 29px;
  margin-top: 13px;
  border: 0;
  border-radius: 15px;
  background-color: #f7f8fb;
  color: #111827;
  font-size: 12px;
  font-weight: 700;
  cursor: pointer;
}
</style>