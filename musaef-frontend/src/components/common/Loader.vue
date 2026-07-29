<template>
  <div v-if="activeLoading" class="loader-overlay d-flex flex-column align-items-center justify-content-center dir-rtl">
    <!-- أنيميشن قطرة الدم مع النبض -->
    <div class="blood-pulse-wrapper mb-3">
      <div class="pulse-ring"></div>
      <div class="blood-drop-icon">🩸</div>
    </div>

    <!-- نص التحميل المتغير -->
    <h6 class="fw-bold text-danger mb-1 fs-7">{{ message }}</h6>
    <small v-if="subMessage" class="text-muted fs-8">{{ subMessage }}</small>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useUiStore } from '@/stores/uiStore';

const props = defineProps({
  isLoading: {
    type: Boolean,
    default: null
  },
  message: {
    type: String,
    default: 'جاري تحميل البيانات...'
  },
  subMessage: {
    type: String,
    default: 'يرجى الانتظار لحظات'
  }
});

const uiStore = useUiStore();

// الربط الديناميكي مع الـ Prop أو الـ Store العام
const activeLoading = computed(() => {
  if (props.isLoading !== null) {
    return props.isLoading;
  }
  return uiStore.isLoading;
});
</script>

<style scoped>
.loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(4px);
  z-index: 9999;
}

.blood-pulse-wrapper {
  position: relative;
  width: 80px;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.blood-drop-icon {
  font-size: 2.8rem;
  z-index: 2;
  animation: pulse-heart 1.2s infinite ease-in-out;
}

.pulse-ring {
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: rgba(220, 53, 69, 0.2);
  z-index: 1;
  animation: expand-ring 1.5s infinite ease-out;
}

@keyframes pulse-heart {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.15);
  }
}

@keyframes expand-ring {
  0% {
    transform: scale(0.6);
    opacity: 0.8;
  }
  100% {
    transform: scale(1.4);
    opacity: 0;
  }
}

.fs-7 {
  font-size: 0.95rem;
}

.fs-8 {
  font-size: 0.82rem;
}

.dir-rtl {
  direction: rtl;
}
</style>
