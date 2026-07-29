<template>
  <div class="d-flex justify-content-center align-items-center gap-1 gap-sm-2 pt-2 dir-rtl flex-wrap">
    <!-- زر الصفحة التالية -->
    <button
      class="btn btn-light rounded-3 px-2 px-sm-3 py-1.5 fs-8 border"
      :disabled="accountsStore.currentPage >= accountsStore.totalPages"
      @click="accountsStore.setPage(accountsStore.currentPage + 1)"
    >
      &gt;
    </button>

    <!-- أزرار الأرقام التفاعلية -->
    <button
      v-for="page in accountsStore.totalPages"
      :key="page"
      class="btn rounded-3 px-2 px-sm-3 py-1.5 fs-8 transition-all"
      :class="accountsStore.currentPage === page ? 'btn-danger fw-bold text-white shadow-sm' : 'btn-light border text-dark'"
      @click="accountsStore.setPage(page)"
    >
      {{ page }}
    </button>

    <!-- زر الصفحة السابقة -->
    <button
      class="btn btn-light rounded-3 px-2 px-sm-3 py-1.5 fs-8 border"
      :disabled="accountsStore.currentPage <= 1"
      @click="accountsStore.setPage(accountsStore.currentPage - 1)"
    >
      &lt;
    </button>
  </div>
</template>

<script setup>
import { useAccountsStore } from '@/stores/accountsStore';

const accountsStore = useAccountsStore();
</script>

<style scoped>
.fs-8 { font-size: 0.82rem; }
.dir-rtl { direction: rtl; }
</style>
