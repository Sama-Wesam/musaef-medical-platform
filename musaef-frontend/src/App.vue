<template>
  <!-- هذا السطر هو الروح التي ستعرض الـ Layout والـ Dashboard -->
  <RouterView />
</template>

<script setup>
import { RouterView } from 'vue-router'
</script>

<style>
body {
  margin: 0;
  padding: 0;
  background-color: #f8f9fa;
}
</style>