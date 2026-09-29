<template>
  <div :class="['app-loading', { 'app-loading--fullscreen': fullscreen, 'app-loading--inline': !fullscreen }]">
    <div class="loading-spinner"></div>
    <div v-if="text" class="loading-text">{{ text }}</div>
  </div>
</template>

<script setup>
defineProps({
  text: {
    type: String,
    default: 'Memuat data...',
  },
  fullscreen: {
    type: Boolean,
    default: false,
  },
})
</script>

<style scoped>
.app-loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: var(--spacing-3);
  padding: var(--spacing-8);
}

.app-loading--fullscreen {
  position: fixed;
  inset: 0;
  background-color: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(4px);
  z-index: 9999;
}

.app-loading--inline {
  width: 100%;
  min-height: 12rem;
}

.loading-spinner {
  width: 2.25rem;
  height: 2.25rem;
  border: 3px solid var(--color-bg-muted);
  border-top-color: var(--color-primary);
  border-radius: var(--radius-full);
  animation: spin 0.8s linear infinite;
}

.loading-text {
  font-size: var(--font-size-sm);
  color: var(--color-text-muted);
  font-weight: 500;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>