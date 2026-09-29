<template>
  <div class="app-select-group" :class="{ 'has-error': !!error, 'is-disabled': disabled }">
    <label v-if="label" :for="id" class="select-label">
      {{ label }}
      <span v-if="required" class="required-star">*</span>
    </label>

    <div class="select-wrapper">
      <select
        :id="id"
        :value="modelValue"
        :disabled="disabled"
        :required="required"
        class="select-control"
        @change="$emit('update:modelValue', $event.target.value)"
      >
        <option v-if="placeholder" value="" disabled selected>{{ placeholder }}</option>
        <option
          v-for="opt in normalizedOptions"
          :key="opt.value"
          :value="opt.value"
        >
          {{ opt.label }}
        </option>
      </select>
      <div class="select-arrow">
        <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </div>
    </div>

    <span v-if="error" class="select-error-msg">{{ error }}</span>
    <span v-else-if="hint" class="select-hint">{{ hint }}</span>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: '',
  },
  id: {
    type: String,
    default: () => `select-${Math.random().toString(36).substring(2, 9)}`,
  },
  label: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: '',
  },
  options: {
    type: Array,
    default: () => [],
  },
  error: {
    type: String,
    default: '',
  },
  hint: {
    type: String,
    default: '',
  },
  required: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['update:modelValue'])

const normalizedOptions = computed(() => {
  return props.options.map((opt) => {
    if (typeof opt === 'string' || typeof opt === 'number') {
      return { value: opt, label: opt }
    }
    return opt
  })
})
</script>

<style scoped>
.app-select-group {
  display: flex;
  flex-direction: column;
  margin-bottom: var(--spacing-4);
  width: 100%;
}

.select-label {
  font-size: var(--font-size-sm);
  font-weight: 500;
  color: var(--color-text-main);
  margin-bottom: var(--spacing-1);
}

.required-star {
  color: var(--color-danger);
  margin-left: 2px;
}

.select-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.select-control {
  width: 100%;
  padding: var(--spacing-2) var(--spacing-8) var(--spacing-2) var(--spacing-3);
  font-size: var(--font-size-sm);
  color: var(--color-text-main);
  background-color: var(--color-bg-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  appearance: none;
  cursor: pointer;
  transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
}

.select-control:hover:not(:disabled) {
  border-color: var(--color-border-hover);
}

.select-control:focus {
  border-color: var(--color-border-focus);
  box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
}

.select-control:disabled {
  background-color: var(--color-bg-subtle);
  color: var(--color-text-subtle);
  cursor: not-allowed;
}

.select-arrow {
  position: absolute;
  right: var(--spacing-3);
  pointer-events: none;
  color: var(--color-text-subtle);
  display: flex;
  align-items: center;
}

.has-error .select-control {
  border-color: var(--color-danger);
}

.select-error-msg {
  font-size: var(--font-size-xs);
  color: var(--color-danger);
  margin-top: var(--spacing-1);
}

.select-hint {
  font-size: var(--font-size-xs);
  color: var(--color-text-muted);
  margin-top: var(--spacing-1);
}
</style>