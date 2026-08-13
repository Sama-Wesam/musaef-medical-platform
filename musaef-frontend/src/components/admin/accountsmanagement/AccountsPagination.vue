<template>
  <div class="d-flex justify-content-center align-items-center gap-1 gap-sm-2 pt-2 flex-wrap" :dir="langStore.dir">
    <!-- زر الصفحة السابقة (يتكيف فورياً مع اتجاه اللغة) -->
    <button
      class="btn btn-light rounded-3 px-2.5 px-sm-3 py-1.5 fs-8 border shadow-sm"
      :disabled="accountsStore.currentPage <= 1"
      @click="accountsStore.setPage(accountsStore.currentPage - 1)"
    >
      <span v-if="langStore.currentLang === 'en'">&lt;</span>
      <span v-else>&gt;</span>
    </button>

    <!-- أزرار أرقام الصفحات -->
    <button
      v-for="page in accountsStore.totalPages"
      :key="page"
      class="btn rounded-3 px-2.5 px-sm-3 py-1.5 fs-8 transition-all"
      :class="accountsStore.currentPage === page ? 'btn-danger fw-bold text-white shadow-sm' : 'btn-light border text-dark'"
      @click="accountsStore.setPage(page)"
    >
      {{ page }}
    </button>

    <!-- زر الصفحة التالية -->
    <button
      class="btn btn-light rounded-3 px-2.5 px-sm-3 py-1.5 fs-8 border shadow-sm"
      :disabled="accountsStore.currentPage >= accountsStore.totalPages"
      @click="accountsStore.setPage(accountsStore.currentPage + 1)"
    >
      <span v-if="langStore.currentLang === 'en'">&gt;</span>
      <span v-else>&lt;</span>
    </button>
  </div>
</template>

<script setup>
import { useAccountsStore } from '@/stores/accountsStore';
import { useLangStore } from '@/stores/langStore';

const accountsStore = useAccountsStore();
const langStore = useLangStore();
</script>

<style scoped>
.fs-8 { font-size: 0.82rem; }
.btn-light:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
