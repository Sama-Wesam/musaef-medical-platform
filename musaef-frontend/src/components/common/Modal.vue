<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div
        v-if="isOpen"
        class="modal-backdrop-custom d-flex align-items-center justify-content-center p-3 dir-rtl"
        @click.self="handleBackdropClick"
      >
        <div
          class="modal-card bg-white rounded-4 shadow-lg p-4 w-100 position-relative"
          :style="{ maxWidth: maxWidth }"
        >
          <!-- زر الإغلاق (X) العلوي -->
          <button
            @click="closeModal"
            type="button"
            class="btn-close position-absolute top-0 start-0 m-3 fs-9"
            aria-label="Close"
          ></button>

          <!-- رأس النافذة المنبثقة (Header) -->
          <div v-if="title || $slots.header" class="modal-header border-bottom pb-3 mb-3 text-start">
            <slot name="header">
              <h5 class="fw-bold text-dark mb-0">{{ title }}</h5>
            </slot>
          </div>

          <!-- جسم النافذة الرئيسي (Body) -->
          <div class="modal-body py-2">
            <slot />
          </div>

          <!-- أزرار الإجراءات السفلى (Footer) -->
          <div v-if="$slots.footer" class="modal-footer border-top pt-3 mt-3 d-flex gap-2">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: ''
  },
  maxWidth: {
    type: String,
    default: '450px'
  },
  closeOnBackdrop: {
    type: Boolean,
    default: true
  }
});

const emit = defineEmits(['close']);

const closeModal = () => {
  emit('close');
};

const handleBackdropClick = () => {
  if (props.closeOnBackdrop) {
    closeModal();
  }
};

const handleKeyDown = (e) => {
  if (e.key === 'Escape' && props.isOpen) {
    closeModal();
  }
};

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown);
});
</script>

<style scoped>
.modal-backdrop-custom {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(15, 23, 42, 0.6);
  backdrop-filter: blur(4px);
  z-index: 1050;
}

.modal-card {
  z-index: 1051;
  max-height: 90vh;
  overflow-y: auto;
}

.fs-9 {
  font-size: 0.72rem;
}

.dir-rtl {
  direction: rtl;
}

.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.25s ease, transform 0.25s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style>
