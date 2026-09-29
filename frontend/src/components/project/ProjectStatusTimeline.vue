<template>
  <div class="timeline">
    <div
      v-for="(step, index) in steps"
      :key="step.key || index"
      class="timeline__item"
      :class="{ 'is-current': step.key === currentStatus, 'is-complete': isComplete(step.key) }"
    >
      <div class="timeline__dot"></div>
      <div class="timeline__content">
        <span class="timeline__label">{{ step.label }}</span>
        <small v-if="step.date">{{ formatDate(step.date) }}</small>
      </div>
      <span v-if="index < steps.length - 1" class="timeline__connector"></span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentStatus: {
    type: String,
    default: 'DRAFT',
  },
  history: {
    type: Array,
    default: () => [],
  },
})

const defaultSteps = [
  { key: 'DRAFT', label: 'Draft' },
  { key: 'SUBMITTED', label: 'Submitted' },
  { key: 'UNDER_REVIEW', label: 'Under Review' },
  { key: 'REVISION_REQUIRED', label: 'Revision Required' },
  { key: 'RESUBMITTED', label: 'Resubmitted' },
  { key: 'APPROVED', label: 'Approved' },
  { key: 'REJECTED', label: 'Rejected' },
]

const steps = computed(() => {
  const order = ['DRAFT', 'SUBMITTED', 'UNDER_REVIEW', 'REVISION_REQUIRED', 'RESUBMITTED', 'APPROVED', 'REJECTED']

  if (!props.history?.length) {
    return defaultSteps.filter((item) => order.includes(item.key))
  }

  return props.history.map((item) => ({
    key: item.status || item.action || 'DRAFT',
    label: item.label || item.status || item.action || 'Status',
    date: item.date || item.created_at,
  }))
})

function isComplete(key) {
  const order = ['DRAFT', 'SUBMITTED', 'UNDER_REVIEW', 'REVISION_REQUIRED', 'RESUBMITTED', 'APPROVED', 'REJECTED']
  const currentIndex = order.indexOf(props.currentStatus)
  const stepIndex = order.indexOf(key)
  return stepIndex < currentIndex
}

function formatDate(value) {
  if (!value) return ''
  const date = new Date(value)
  if (Number.isNaN(date.getTime())) return value

  return new Intl.DateTimeFormat('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  }).format(date)
}
</script>

<style scoped>
.timeline {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-4);
  position: relative;
}

.timeline__item {
  position: relative;
  display: grid;
  grid-template-columns: 16px 1fr;
  gap: var(--spacing-3);
  align-items: center;
  opacity: 0.8;
}

.timeline__item.is-current {
  opacity: 1;
}

.timeline__item.is-complete .timeline__dot {
  background: var(--color-primary);
  border-color: var(--color-primary);
}

.timeline__dot {
  width: 14px;
  height: 14px;
  border-radius: 50%;
  border: 2px solid var(--color-border);
  background: var(--color-bg-surface);
  position: relative;
  z-index: 1;
}

.timeline__content {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.timeline__label {
  font-weight: 600;
}

.timeline__content small {
  color: var(--color-text-muted);
}

.timeline__connector {
  position: absolute;
  left: 6px;
  top: 18px;
  width: 2px;
  height: calc(100% + 8px);
  background: var(--color-border);
}
</style>
<!--
Display the project's workflow visually.

Example:

Draft
  ↓
Submitted
  ↓
Under Review
  ↓
Revision Required
  ↓
Resubmitted
  ↓
Under Review
  ↓
Approved / Rejected

Highlight the current status.

Use history data when available to show
important status transitions.
-->