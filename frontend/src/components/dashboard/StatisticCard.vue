<template>
  <div class="stat-card" :class="[`stat-card--${variant}`]">
    <div class="stat-main">
      <div class="stat-info">
        <span class="stat-title">{{ title }}</span>
        <div class="stat-value">
          <span v-if="loading" class="stat-skeleton"></span>
          <span v-else>{{ value }}</span>
        </div>
      </div>
      <div v-if="$slots.icon || icon" class="stat-icon-wrapper">
        <slot name="icon">
          <span class="stat-icon-text">{{ icon }}</span>
        </slot>
      </div>
    </div>
    <div v-if="hint || $slots.hint" class="stat-footer">
      <slot name="hint">
        <span class="stat-hint">{{ hint }}</span>
      </slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  value: {
    type: [Number, String],
    default: 0,
  },
  icon: {
    type: String,
    default: '',
  },
  hint: {
    type: String,
    default: '',
  },
  variant: {
    type: String,
    default: 'default', // 'default' | 'primary' | 'success' | 'warning' | 'danger' | 'info'
  },
  loading: {
    type: Boolean,
    default: false,
  },
})
</script>

<style scoped>
.stat-card {
  background-color: var(--color-bg-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  padding: var(--spacing-5);
  box-shadow: var(--shadow-sm);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: transform var(--transition-fast), box-shadow var(--transition-fast);
}

.stat-card:hover {
  box-shadow: var(--shadow-md);
  transform: translateY(-2px);
}

.stat-main {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: var(--spacing-3);
}

.stat-info {
  display: flex;
  flex-direction: column;
}

.stat-title {
  font-size: var(--font-size-xs);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--color-text-muted);
  margin-bottom: var(--spacing-1);
}

.stat-value {
  font-size: var(--font-size-2xl);
  font-weight: 700;
  color: var(--color-text-main);
  line-height: 1.2;
}

.stat-icon-wrapper {
  width: 2.75rem;
  height: 2.75rem;
  border-radius: var(--radius-lg);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  background-color: var(--color-primary-light);
  color: var(--color-primary-active);
}

.stat-footer {
  margin-top: var(--spacing-3);
  padding-top: var(--spacing-2);
  border-top: 1px solid var(--color-border);
}

.stat-hint {
  font-size: var(--font-size-xs);
  color: var(--color-text-muted);
}

.stat-skeleton {
  display: inline-block;
  width: 3rem;
  height: 1.75rem;
  background-color: var(--color-bg-muted);
  border-radius: var(--radius-sm);
  animation: pulse 1.5s infinite;
}

/* Variant styles */
.stat-card--primary .stat-icon-wrapper {
  background-color: var(--color-primary-light);
  color: var(--color-primary);
}

.stat-card--success .stat-icon-wrapper {
  background-color: var(--color-success-bg);
  color: var(--color-success);
}

.stat-card--warning .stat-icon-wrapper {
  background-color: var(--color-warning-bg);
  color: var(--color-warning);
}

.stat-card--danger .stat-icon-wrapper {
  background-color: var(--color-danger-bg);
  color: var(--color-danger);
}

.stat-card--info .stat-icon-wrapper {
  background-color: var(--color-info-bg);
  color: var(--color-info);
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.4; }
}
</style>