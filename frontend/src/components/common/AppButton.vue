<template>
  <button
    :type="type"
    :class="['app-btn', `app-btn--${variant}`, `app-btn--${size}`, { 'is-loading': loading }]"
    :disabled="disabled || loading"
    @click="$emit('click', $event)"
  >
    <span v-if="loading" class="btn-spinner"></span>
    <span class="btn-content" :class="{ 'opacity-0': loading }">
      <slot></slot>
    </span>
  </button>
</template>

<script setup>
defineProps({
  type: {
    type: String,
    default: 'button',
  },
  variant: {
    type: String,
    default: 'primary', // 'primary' | 'secondary' | 'outline' | 'danger' | 'ghost'
  },
  size: {
    type: String,
    default: 'md', // 'sm' | 'md' | 'lg'
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  loading: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['click'])
</script>

<style scoped>
.app-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 500;
  border-radius: var(--radius-md);
  border: 1px solid transparent;
  cursor: pointer;
  transition: all var(--transition-fast);
  position: relative;
  text-decoration: none;
  white-space: nowrap;
}

.app-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Sizes */
.app-btn--sm {
  padding: var(--spacing-1) var(--spacing-3);
  font-size: var(--font-size-xs);
}

.app-btn--md {
  padding: var(--spacing-2) var(--spacing-4);
  font-size: var(--font-size-sm);
  min-height: 2.375rem;
}

.app-btn--lg {
  padding: var(--spacing-3) var(--spacing-6);
  font-size: var(--font-size-base);
  min-height: 2.875rem;
}

/* Variants */
.app-btn--primary {
  background-color: var(--color-primary);
  color: var(--color-text-inverse);
}
.app-btn--primary:hover:not(:disabled) {
  background-color: var(--color-primary-hover);
}
.app-btn--primary:active:not(:disabled) {
  background-color: var(--color-primary-active);
}

.app-btn--secondary {
  background-color: var(--color-secondary);
  color: var(--color-text-inverse);
}
.app-btn--secondary:hover:not(:disabled) {
  background-color: var(--color-secondary-hover);
}

.app-btn--outline {
  background-color: transparent;
  border-color: var(--color-border);
  color: var(--color-text-main);
}
.app-btn--outline:hover:not(:disabled) {
  background-color: var(--color-bg-subtle);
  border-color: var(--color-border-hover);
}

.app-btn--danger {
  background-color: var(--color-danger);
  color: var(--color-text-inverse);
}
.app-btn--danger:hover:not(:disabled) {
  background-color: var(--color-danger-hover);
}

.app-btn--ghost {
  background-color: transparent;
  color: var(--color-text-muted);
}
.app-btn--ghost:hover:not(:disabled) {
  background-color: var(--color-bg-subtle);
  color: var(--color-text-main);
}

/* Spinner */
.btn-spinner {
  position: absolute;
  width: 1rem;
  height: 1rem;
  border: 2px solid currentColor;
  border-right-color: transparent;
  border-radius: var(--radius-full);
  animation: spin 0.75s linear infinite;
}

.btn-content {
  display: inline-flex;
  align-items: center;
  gap: var(--spacing-2);
}

.opacity-0 {
  opacity: 0;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>