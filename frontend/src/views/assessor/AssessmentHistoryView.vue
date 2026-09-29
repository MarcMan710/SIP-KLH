<template>
  <div class="page-shell">
    <div class="page-header">
      <div>
        <p class="eyebrow">History</p>
        <h1>Riwayat Penilaian</h1>
      </div>
    </div>

    <div class="card">
      <table class="app-table">
        <thead>
          <tr>
            <th>Proyek</th>
            <th>Pemohon</th>
            <th>Aksi</th>
            <th>Catatan</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in history" :key="item.id">
            <td>{{ item.project }}</td>
            <td>{{ item.applicant }}</td>
            <td><StatusBadge :status="item.action" /></td>
            <td>{{ item.notes }}</td>
            <td>{{ item.date }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import StatusBadge from '@/components/common/StatusBadge.vue'

const history = ref([
  { id: 1, project: 'PRJ-2026-001', applicant: 'Budi Santoso', action: 'APPROVED', notes: 'Dokumen lengkap dan sesuai.', date: '2026-09-21' },
  { id: 2, project: 'PRJ-2026-002', applicant: 'Sari Dewi', action: 'REVISION_REQUIRED', notes: 'Perlu revisi peta lokasi.', date: '2026-09-22' },
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
