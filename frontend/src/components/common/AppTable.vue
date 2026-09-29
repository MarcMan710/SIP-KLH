<template>
  <div class="app-table-wrapper">
    <div class="app-table-container">
      <table class="app-table">
        <thead>
          <tr>
            <th
              v-for="col in columns"
              :key="col.key"
              :style="{ width: col.width || 'auto', textAlign: col.align || 'left' }"
            >
              {{ col.label }}
            </th>
          </tr>
        </thead>

        <tbody>
          <!-- Loading State -->
          <tr v-if="loading">
            <td :colspan="columns.length" class="table-loading-cell">
              <AppLoading text="Memuat baris data..." />
            </td>
          </tr>

          <!-- Empty State -->
          <tr v-else-if="!data || data.length === 0">
            <td :colspan="columns.length" class="table-empty-cell">
              <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="40" height="40" class="empty-icon">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <p class="empty-text">{{ emptyText }}</p>
                <div v-if="$slots.emptyAction" class="empty-action">
                  <slot name="emptyAction"></slot>
                </div>
              </div>
            </td>
          </tr>

          <!-- Data Rows -->
          <tr v-else v-for="(item, index) in data" :key="item.id || index">
            <td
              v-for="col in columns"
              :key="col.key"
              :style="{ textAlign: col.align || 'left' }"
            >
              <slot :name="col.key" :item="item" :index="index">
                {{ item[col.key] ?? '-' }}
              </slot>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import AppLoading from './AppLoading.vue'

defineProps({
  columns: {
    type: Array,
    required: true,
  },
  data: {
    type: Array,
    default: () => [],
  },
  loading: {
    type: Boolean,
    default: false,
  },
  emptyText: {
    type: String,
    default: 'Tidak ada data ditemukan.',
  },
})
</script>

<style scoped>
.app-table-wrapper {
  width: 100%;
}

.table-loading-cell,
.table-empty-cell {
  padding: var(--spacing-8) !important;
  text-align: center !important;
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: var(--spacing-2);
  color: var(--color-text-subtle);
}

.empty-icon {
  stroke: var(--color-text-subtle);
}

.empty-text {
  font-size: var(--font-size-sm);
  color: var(--color-text-muted);
}

.empty-action {
  margin-top: var(--spacing-2);
}
</style>