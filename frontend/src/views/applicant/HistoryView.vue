<template>
  <div class="page-shell">
    <div class="page-header">
      <div>
        <p class="eyebrow">History</p>
        <h1>Riwayat Aktivitas</h1>
      </div>
    </div>

    <div class="card">
      <table class="app-table">
        <thead>
          <tr>
            <th>Proyek</th>
            <th>Aksi</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in history" :key="item.id">
            <td>{{ item.project }}</td>
            <td>{{ item.action }}</td>
            <td><StatusBadge :status="item.status" /></td>
            <td>{{ item.date }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import StatusBadge from '@/components/common/StatusBadge.vue'
import { ref } from 'vue'

const history = ref([
  { id: 1, project: 'Permohonan AMDAL', action: 'Diajukan', status: 'SUBMITTED', date: '2026-09-20' },
  { id: 2, project: 'Permohonan AMDAL', action: 'Direview', status: 'UNDER_REVIEW', date: '2026-09-22' },
])
</script>

<style scoped>
.page-shell {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-6);
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.eyebrow {
  color: var(--color-primary);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  font-size: 0.72rem;
  font-weight: 700;
}

.card {
  background: var(--color-bg-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  padding: var(--spacing-4);
}
</style>
