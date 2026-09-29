<template>
  <component :is="layoutComponent">
    <router-view />
  </component>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AuthLayout from '@/layouts/AuthLayout.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

const route = useRoute()

const layoutComponent = computed(() => {
  return route.meta.layout === 'dashboard' ? DashboardLayout : AuthLayout
})
</script>

<style scoped>
:global(body) {
  margin: 0;
}
</style>
