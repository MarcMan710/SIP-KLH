<template>
  <div class="dashboard-layout">
    <AppHeader @toggle-sidebar="sidebarOpen = !sidebarOpen" />
    <div class="layout-body">
      <AppSidebar v-model:open="sidebarOpen" />
      <main class="main-content">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AppHeader from '@/components/common/AppHeader.vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'

const sidebarOpen = ref(true)
</script>

<style scoped>
.dashboard-layout {
  min-height: 100vh;
  background: var(--color-bg-app);
}

.layout-body {
  display: flex;
  min-height: calc(100vh - 72px);
}

.main-content {
  flex: 1;
  padding: var(--spacing-6);
  overflow: auto;
}

@media (max-width: 768px) {
  .layout-body {
    flex-direction: column;
  }
}
</style>
