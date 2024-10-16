<script setup lang="ts">

import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
</script>

<template>
  <div>
    <Header />

    <main>
      <div class="max-w-screen-xl mx-4">
      <router-view />
      </div>
    </main>

    <Footer />
  </div>
</template>

<style scoped>

</style>
