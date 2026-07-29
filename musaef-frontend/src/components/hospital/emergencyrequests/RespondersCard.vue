<template>
  <div class="card border-0 shadow-sm p-3 rounded-4 bg-white text-end">
    <div class="d-flex justify-content-between align-items-center mb-3 fs-8 flex-wrap gap-1">
      <strong class="text-dark">المستجيبون ({{ donors.length }})</strong>
      <a href="#" class="text-danger text-decoration-none fs-9 fw-bold">عرض جميع المستجيبين</a>
    </div>

    <div class="d-flex flex-column gap-2">
      <div v-for="person in donors" :key="person.id || getPersonName(person)" class="p-2 bg-light rounded-3 d-flex align-items-center justify-content-between fs-8">
        <div class="d-flex align-items-center gap-2 min-w-0">
          <img
            :src="getAvatarUrl(person)"
            @error="handleImageError"
            class="rounded-circle object-fit-cover flex-shrink-0 border"
            width="32"
            height="32"
            alt="صورة المستجيب"
          />
          <span class="fw-bold text-dark text-truncate">{{ getPersonName(person) }}</span>
        </div>
        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 fs-9 flex-shrink-0">{{ person.score || person.compatibility || '95' }}%</span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  donors: {
    type: Array,
    required: true
  }
});

const getPersonName = (person) => {
  return person.name || person.user?.name || person.donor?.user?.name || 'متبرع كريم';
};

const getAvatarUrl = (person) => {
  const avatarPath = person.avatar || person.image || person.user?.avatar || person.donor?.user?.avatar;

  if (!avatarPath) return 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
  if (avatarPath.startsWith('http')) return avatarPath;
  return `http://localhost:8000/storage/${avatarPath}`;
};

const handleImageError = (event) => {
  event.target.src = 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
};
</script>

<style scoped>
.fs-8 { font-size: 0.8rem; }
.fs-9 { font-size: 0.72rem; }
.bg-success-subtle { background-color: #d1fae5 !important; }
</style>
