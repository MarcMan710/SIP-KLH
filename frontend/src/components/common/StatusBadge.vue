<template>
  <span
    :class="['status-badge', `status-badge--${normalizedStatus}`]"
  >
    <span class="status-dot"></span>
    {{ displayLabel }}
  </span>
</template>

<script setup>
import { computed } from 'vue'
import { STATUS_LABELS } from '@/utils/constants'

const props = defineProps({
  status: {
    type: String,
    default: '',
  },
  label: {
    type: String,
    default: '',
  },
})

const normalizedStatus = computed(() => {
  return (props.status || '').toLowerCase().replace(/_/g, '-')
})

const displayLabel = computed(() => {
  if (props.label) return props.label
  return STATUS_LABELS[props.status] || props.status?.replace(/_/g, ' ') || 'Unknown'
})
</script>

<style scoped>
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: var(--spacing-1);
  padding: 0.2rem 0.65rem;
  border-radius: var(--radius-full);
  font-size: var(--font-size-xs);
  font-weight: 600;
  text-transform: capitalize;
  border: 1px solid transparent;
  white-space: nowrap;
}

.status-dot {
  width: 6px;
  height: 6px;
  border-radius: var(--radius-full);
  background-color: currentColor;
}

/* DRAFT */
.status-badge--draft {
  background-color: var(--color-status-draft-bg);
  color: var(--color-status-draft-text);
  border-color: var(--color-status-draft-border);
}

/* SUBMITTED */
.status-badge--submitted {
  background-color: var(--color-status-submitted-bg);
  color: var(--color-status-submitted-text);
  border-color: var(--color-status-submitted-border);
}

/* UNDER_REVIEW */
.status-badge--under-review {
  background-color: var(--color-status-review-bg);
  color: var(--color-status-review-text);
  border-color: var(--color-status-review-border);
}

/* REVISION_REQUIRED */
.status-badge--revision-required {
  background-color: var(--color-status-revision-bg);
  color: var(--color-status-revision-text);
  border-color: var(--color-status-revision-border);
}

/* RESUBMITTED */
.status-badge--resubmitted {
  background-color: var(--color-status-resubmitted-bg);
  color: var(--color-status-resubmitted-text);
  border-color: var(--color-status-resubmitted-border);
}

/* APPROVED */
.status-badge--approved {
  background-color: var(--color-status-approved-bg);
  color: var(--color-status-approved-text);
  border-color: var(--color-status-approved-border);
}

/* REJECTED */
.status-badge--rejected {
  background-color: var(--color-status-rejected-bg);
  color: var(--color-status-rejected-text);
  border-color: var(--color-status-rejected-border);
}
</style>