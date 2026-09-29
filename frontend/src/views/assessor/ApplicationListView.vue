<template>
  <div class="page-shell">
    <div class="page-header">
      <div>
        <p class="eyebrow">Penilaian</p>
        <h1>Daftar Permohonan</h1>
      </div>
    </div>

    <div class="toolbar">
      <AppInput v-model="search" placeholder="Cari aplikasi" />
      <AppSelect v-model="selectedStatus" :options="statusOptions" placeholder="Semua status" />
    </div>

    <div class="card">
      <table class="app-table">
        <thead>
          <tr>
            <th>Nomor</th>
            <th>Nama Pemohon</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="app in filteredApplications" :key="app.id">
            <td>{{ app.number }}</td>
            <td>{{ app.applicant }}</td>
            <td><StatusBadge :status="app.status" /></td>
            <td>
              <router-link :to="{ name: 'assessor-application-detail', params: { id: app.id } }">Review</router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import AppInput from '@/components/common/AppInput.vue'
import AppSelect from '@/components/common/AppSelect.vue'
import StatusBadge from '@/components/common/StatusBadge.vue'

const search = ref('')
const selectedStatus = ref('')
const applications = ref([
  { id: 1, number: 'PRJ-2026-001', applicant: 'Budi Santoso', status: 'UNDER_REVIEW' },
  { id: 2, number: 'PRJ-2026-002', applicant: 'Sari Dewi', status: 'REVISION_REQUIRED' },
  { id: 3, number: 'PRJ-2026-003', applicant: 'Andi Wijaya', status: 'APPROVED' },
])

const statusOptions = [
  { value: 'SUBMITTED', label: 'Submitted' },
  { value: 'UNDER_REVIEW', label: 'Under Review' },
  { value: 'REVISION_REQUIRED', label: 'Revision Required' },
  { value: 'APPROVED', label: 'Approved' },
  { value: 'REJECTED', label: 'Rejected' },
]

const filteredApplications = computed(() => {
  return applications.value.filter((item) => {
    const matchesSearch = item.applicant.toLowerCase().includes(search.value.toLowerCase()) || item.number.toLowerCase().includes(search.value.toLowerCase())
    const matchesStatus = !selectedStatus.value || item.status === selectedStatus.value
    return matchesSearch && matchesStatus
  })
})
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

.toolbar {
  display: grid;
  grid-template-columns: 1fr 0.8fr;
  gap: var(--spacing-4);
}

.card {
  background: var(--color-bg-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  padding: var(--spacing-4);
}

@media (max-width: 768px) {
  .toolbar {
    grid-template-columns: 1fr;
  }
}
</style>
