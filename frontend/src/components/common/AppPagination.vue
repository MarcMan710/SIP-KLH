<template>
  <div v-if="total > 0" class="app-pagination">
    <div class="pagination-info">
      Menampilkan <span class="font-medium">{{ from }}</span> sampai <span class="font-medium">{{ to }}</span> dari <span class="font-medium">{{ total }}</span> data
    </div>

    <div class="pagination-controls">
      <!-- Previous Button -->
      <button
        type="button"
        class="page-btn page-btn-prev"
        :disabled="currentPage <= 1 || loading"
        @click="$emit('change', currentPage - 1)"
      >
        <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
          <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        <span>Sebelumnya</span>
      </button>

      <!-- Page Numbers -->
      <div class="page-numbers">
        <button
          v-for="page in visiblePages"
          :key="page"
          type="button"
          :class="['page-num-btn', { 'is-active': page === currentPage, 'is-dots': page === '...' }]"
          :disabled="page === '...' || loading"
          @click="page !== '...' && $emit('change', page)"
        >
          {{ page }}
        </button>
      </div>

      <!-- Next Button -->
      <button
        type="button"
        class="page-btn page-btn-next"
        :disabled="currentPage >= lastPage || loading"
        @click="$emit('change', currentPage + 1)"
      >
        <span>Selanjutnya</span>
        <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  lastPage: {
    type: Number,
    required: true,
  },
  total: {
    type: Number,
    default: 0,
  },
  perPage: {
    type: Number,
    default: 10,
  },
  loading: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['change'])

const from = computed(() => {
  if (props.total === 0) return 0
  return (props.currentPage - 1) * props.perPage + 1
})

const to = computed(() => {
  return Math.min(props.currentPage * props.perPage, props.total)
})

const visiblePages = computed(() => {
  const current = props.currentPage
  const last = props.lastPage
  const delta = 2
  const range = []
  const rangeWithDots = []
  let l

  for (let i = 1; i <= last; i++) {
    if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
      range.push(i)
    }
  }

  for (const i of range) {
    if (l) {
      if (i - l === 2) {
        rangeWithDots.push(l + 1)
      } else if (i - l !== 1) {
        rangeWithDots.push('...')
      }
    }
    rangeWithDots.push(i)
    l = i
  }

  return rangeWithDots
})
</script>

<style scoped>
.app-pagination {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: var(--spacing-4) 0;
  flex-wrap: wrap;
  gap: var(--spacing-4);
}

.pagination-info {
  font-size: var(--font-size-sm);
  color: var(--color-text-muted);
}

.pagination-controls {
  display: flex;
  align-items: center;
  gap: var(--spacing-2);
}

.page-btn {
  display: inline-flex;
  align-items: center;
  gap: var(--spacing-1);
  padding: var(--spacing-1) var(--spacing-3);
  font-size: var(--font-size-sm);
  border: 1px solid var(--color-border);
  background-color: var(--color-bg-surface);
  color: var(--color-text-main);
  border-radius: var(--radius-md);
  cursor: pointer;
  transition: all var(--transition-fast);
}

.page-btn:hover:not(:disabled) {
  background-color: var(--color-bg-subtle);
  border-color: var(--color-border-hover);
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-numbers {
  display: flex;
  align-items: center;
  gap: 2px;
}

.page-num-btn {
  min-width: 2rem;
  height: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 var(--spacing-2);
  font-size: var(--font-size-sm);
  border: 1px solid transparent;
  background-color: transparent;
  color: var(--color-text-main);
  border-radius: var(--radius-md);
  cursor: pointer;
  transition: all var(--transition-fast);
}

.page-num-btn:hover:not(:disabled):not(.is-active) {
  background-color: var(--color-bg-subtle);
}

.page-num-btn.is-active {
  background-color: var(--color-primary);
  color: var(--color-text-inverse);
  font-weight: 600;
}

.page-num-btn.is-dots {
  cursor: default;
  color: var(--color-text-subtle);
}
</style>