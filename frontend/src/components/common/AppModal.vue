<template>
  <Teleport to="body">
    <div v-if="modelValue" class="modal-backdrop" @click.self="handleBackdropClick">
      <div class="modal-container" :class="[`modal-size--${size}`]">
        <div class="modal-header">
          <h3 class="modal-title">{{ title }}</h3>
          <button type="button" class="modal-close-btn" @click="close">
            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>

        <div class="modal-body">
          <slot></slot>
        </div>

        <div v-if="$slots.footer || showDefaultFooter" class="modal-footer">
          <slot name="footer">
            <AppButton variant="outline" @click="close">{{ cancelText }}</AppButton>
            <AppButton
              :variant="confirmVariant"
              :loading="loading"
              @click="$emit('confirm')"
            >
              {{ confirmText }}
            </AppButton>
          </slot>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import AppButton from './AppButton.vue'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: 'Konfirmasi',
  },
  size: {
    type: String,
    default: 'md', // 'sm' | 'md' | 'lg'
  },
  confirmText: {
    type: String,
    default: 'Konfirmasi',
  },
  cancelText: {
    type: String,
    default: 'Batal',
  },
  confirmVariant: {
    type: String,
    default: 'primary', // 'primary' | 'danger'
  },
  loading: {
    type: Boolean,
    default: false,
  },
  closeOnBackdrop: {
    type: Boolean,
    default: true,
  },
  showDefaultFooter: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(['update:modelValue', 'confirm', 'cancel'])

function close() {
  emit('update:modelValue', false)
  emit('cancel')
}

function handleBackdropClick() {
  if (props.closeOnBackdrop && !props.loading) {
    close()
  }
}
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  inset: 0;
  background-color: rgba(15, 23, 42, 0.6);
  backdrop-filter: blur(2px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
  padding: var(--spacing-4);
  animation: fadeIn 0.15s ease-out;
}

.modal-container {
  background-color: var(--color-bg-surface);
  border-radius: var(--radius-xl);
  box-shadow: var(--shadow-xl);
  width: 100%;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  animation: scaleIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.modal-size--sm { max-width: 24rem; }
.modal-size--md { max-width: 32rem; }
.modal-size--lg { max-width: 48rem; }

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: var(--spacing-4) var(--spacing-6);
  border-bottom: 1px solid var(--color-border);
}

.modal-title {
  font-size: var(--font-size-lg);
  font-weight: 600;
  color: var(--color-text-main);
}

.modal-close-btn {
  background: transparent;
  border: none;
  cursor: pointer;
  color: var(--color-text-subtle);
  padding: var(--spacing-1);
  border-radius: var(--radius-sm);
  display: flex;
  transition: color var(--transition-fast);
}

.modal-close-btn:hover {
  color: var(--color-text-main);
}

.modal-body {
  padding: var(--spacing-6);
  overflow-y: auto;
  font-size: var(--font-size-sm);
  color: var(--color-text-muted);
}

.modal-footer {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: var(--spacing-3);
  padding: var(--spacing-4) var(--spacing-6);
  border-top: 1px solid var(--color-border);
  background-color: var(--color-bg-subtle);
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
</style>