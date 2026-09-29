<template>
  <div class="page-shell">
    <div class="page-header">
      <div>
        <p class="eyebrow">Permohonan</p>
        <h1>Daftar Permohonan</h1>
      </div>
      <router-link :to="{ name: 'applicant-project-create' }">
        <AppButton>+ Buat Draft</AppButton>
      </router-link>
    </div>

    <div class="toolbar">
      <AppInput v-model="search" placeholder="Cari nama atau nomor proyek" />
      <AppSelect v-model="selectedStatus" :options="statusOptions" placeholder="Semua status" />
    </div>

    <div class="card wrap">
      <table class="app-table" v-if="filteredProjects.length">
        <thead>
          <tr>
            <th>Nomor</th>
            <th>Judul</th>
            <th>Status</th>
            <th>Terakhir Update</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="project in filteredProjects" :key="project.id">
            <td>{{ project.number || `PRJ-${project.id}` }}</td>
            <td>{{ project.name }}</td>
            <td><StatusBadge :status="project.status" /></td>
            <td>{{ project.updated_at || '-' }}</td>
            <td>
              <div class="action-links">
                <router-link :to="{ name: 'applicant-project-detail', params: { id: project.id } }">Detail</router-link>
                <router-link v-if="project.status === 'DRAFT'" :to="{ name: 'applicant-project-edit', params: { id: project.id } }">Edit</router-link>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-else class="empty-state">Belum ada data permohonan.</div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import AppButton from '@/components/common/AppButton.vue'
import AppInput from '@/components/common/AppInput.vue'
import AppSelect from '@/components/common/AppSelect.vue'
import StatusBadge from '@/components/common/StatusBadge.vue'

const search = ref('')
const selectedStatus = ref('')
const projects = ref([
  { id: 1, number: 'PRJ-2026-001', name: 'Permohonan AMDAL', status: 'SUBMITTED', updated_at: '2026-09-20' },
  { id: 2, number: 'PRJ-2026-002', name: 'Permohonan UKL-UPL', status: 'DRAFT', updated_at: '2026-09-24' },
  { id: 3, number: 'PRJ-2026-003', name: 'Permohonan UKL-UPL Baru', status: 'REVISION_REQUIRED', updated_at: '2026-09-22' },
])

const statusOptions = [
  { value: 'DRAFT', label: 'Draft' },
  { value: 'SUBMITTED', label: 'Submitted' },
  { value: 'UNDER_REVIEW', label: 'Under Review' },
  { value: 'REVISION_REQUIRED', label: 'Revision Required' },
  { value: 'APPROVED', label: 'Approved' },
  { value: 'REJECTED', label: 'Rejected' },
]

const filteredProjects = computed(() => {
  return projects.value.filter((project) => {
    const matchesSearch = project.name.toLowerCase().includes(search.value.toLowerCase())
    const matchesStatus = !selectedStatus.value || project.status === selectedStatus.value
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
  gap: var(--spacing-4);
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
  grid-template-columns: 1.5fr 0.8fr;
  gap: var(--spacing-4);
}

.card {
  background: var(--color-bg-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  padding: var(--spacing-4);
}

.action-links {
  display: flex;
  gap: var(--spacing-3);
}

.empty-state {
  padding: var(--spacing-6);
  text-align: center;
  color: var(--color-text-muted);
}

@media (max-width: 768px) {
  .toolbar {
    grid-template-columns: 1fr;
  }
}
</style>
