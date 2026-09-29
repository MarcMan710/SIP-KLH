<template>
  <div class="app-input-group" :class="{ 'has-error': !!error, 'is-disabled': disabled }">
    <label v-if="label" :for="id" class="input-label">
      {{ label }}
      <span v-if="required" class="required-star">*</span>
    </label>

    <div class="input-wrapper">
      <input
        :id="id"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :required="required"
        :autocomplete="autocomplete"
        class="input-control"
        @input="$emit('update:modelValue', $event.target.value)"
        @blur="$emit('blur', $event)"
        @focus="$emit('focus', $event)"
      />
      <div v-if="$slots.suffix" class="input-suffix">
        <slot name="suffix"></slot>
      </div>
    </div>

    <span v-if="error" class="input-error-msg">{{ error }}</span>
    <span v-else-if="hint" class="input-hint">{{ hint }}</span>
  </div>
</template>

<script setup>
defineProps({
  modelValue: {
    type: [String, Number],
    default: '',
  },
  id: {
    type: String,
    default: () => `input-${Math.random().toString(36).substring(2, 9)}`,
  },
  label: {
    type: String,
    default: '',
  },
  type: {
    type: String,
    default: 'text',
  },
  placeholder: {
    type: String,
    default: '',
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
  autocomplete: {
    type: String,
    default: 'off',
  },
})

defineEmits(['update:modelValue', 'blur', 'focus'])
</script>

<style scoped>
.app-input-group {
  display: flex;
  flex-direction: column;
  margin-bottom: var(--spacing-4);
  width: 100%;
}

.input-label {
  font-size: var(--font-size-sm);
  font-weight: 500;
  color: var(--color-text-main);
  margin-bottom: var(--spacing-1);
}

.required-star {
  color: var(--color-danger);
  margin-left: 2px;
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.input-control {
  width: 100%;
  padding: var(--spacing-2) var(--spacing-3);
  font-size: var(--font-size-sm);
  color: var(--color-text-main);
  background-color: var(--color-bg-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
}

.input-control:hover:not(:disabled) {
  border-color: var(--color-border-hover);
}

.input-control:focus {
  border-color: var(--color-border-focus);
  box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
}

.input-control:disabled {
  background-color: var(--color-bg-subtle);
  color: var(--color-text-subtle);
  cursor: not-allowed;
}

.has-error .input-control {
  border-color: var(--color-danger);
}

.has-error .input-control:focus {
  box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.15);
}

.input-error-msg {
  font-size: var(--font-size-xs);
  color: var(--color-danger);
  margin-top: var(--spacing-1);
}

.input-hint {
  font-size: var(--font-size-xs);
  color: var(--color-text-muted);
  margin-top: var(--spacing-1);
}

.input-suffix {
  position: absolute;
  right: var(--spacing-3);
  display: flex;
  align-items: center;
}
</style>