<template>
  <div class="page-shell">
    <div class="page-header">
      <div>
        <p class="eyebrow">Permohonan</p>
        <h1>{{ project.name }}</h1>
      </div>
      <div class="header-actions">
        <StatusBadge :status="project.status" />
        <AppButton variant="outline" @click="editProject">Edit</AppButton>
        <AppButton @click="submitProject">Submit</AppButton>
      </div>
    </div>

    <div class="content-grid">
      <div class="card">
        <h3>Informasi Permohonan</h3>
        <dl class="meta-list">
          <div><dt>Nomor</dt><dd>{{ project.number }}</dd></div>
          <div><dt>Jenis</dt><dd>{{ project.category }}</dd></div>
          <div><dt>Deskripsi</dt><dd>{{ project.description }}</dd></div>
          <div><dt>Terakhir update</dt><dd>{{ project.updated_at }}</dd></div>
        </dl>
      </div>

      <div class="card">
        <h3>Dokumen</h3>
        <ul class="list">
          <li>AMDAL.pdf</li>
          <li>Surat Permohonan.docx</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppButton from '@/components/common/AppButton.vue'
import StatusBadge from '@/components/common/StatusBadge.vue'

const route = useRoute()
const router = useRouter()

const project = ref({
  id: Number(route.params.id) || 1,
  name: 'Permohonan AMDAL',
  number: 'PRJ-2026-001',
  category: 'AMDAL',
  description: 'Analisis dampak lingkungan untuk kegiatan pembangunan.',
  status: 'UNDER_REVIEW',
  updated_at: '2026-09-24',
})

function editProject() {
  router.push({ name: 'applicant-project-edit', params: { id: project.value.id } })
}

function submitProject() {
  project.value.status = 'SUBMITTED'
}
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

.header-actions {
  display: flex;
  gap: var(--spacing-3);
  align-items: center;
}

.content-grid {
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  gap: var(--spacing-5);
}

.card {
  background: var(--color-bg-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  padding: var(--spacing-5);
}

.meta-list {
  margin-top: var(--spacing-4);
  display: grid;
  gap: var(--spacing-3);
}

.meta-list div {
  display: grid;
  grid-template-columns: 140px 1fr;
  gap: var(--spacing-4);
}

.meta-list dt {
  color: var(--color-text-muted);
}

.list {
  margin-top: var(--spacing-4);
  display: grid;
  gap: var(--spacing-2);
  padding-left: var(--spacing-5);
}

@media (max-width: 768px) {
  .content-grid,
  .page-header {
    grid-template-columns: 1fr;
    display: grid;
  }
}
</style>
