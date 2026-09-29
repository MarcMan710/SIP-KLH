<template>
  <div class="page-shell">
    <div class="page-header">
      <div>
        <p class="eyebrow">Penilaian</p>
        <h1>{{ application.number }}</h1>
      </div>
      <div class="header-actions">
        <StatusBadge :status="application.status" />
      </div>
    </div>

    <div class="content-grid">
      <div class="card">
        <h3>Detail Permohonan</h3>
        <dl class="meta-list">
          <div><dt>Pemohon</dt><dd>{{ application.applicant }}</dd></div>
          <div><dt>Judul</dt><dd>{{ application.name }}</dd></div>
          <div><dt>Jenis</dt><dd>{{ application.category }}</dd></div>
          <div><dt>Catatan</dt><dd>{{ application.notes }}</dd></div>
        </dl>
      </div>

      <div class="card">
        <h3>Dokumen</h3>
        <ul class="list">
          <li>AMDAL.pdf</li>
          <li>Peta Lokasi.jpg</li>
        </ul>

        <div class="actions">
          <AppButton variant="outline" @click="requestRevision">Request Revision</AppButton>
          <AppButton variant="secondary" @click="approveApplication">Approve</AppButton>
          <AppButton variant="danger" @click="rejectApplication">Reject</AppButton>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AppButton from '@/components/common/AppButton.vue'
import StatusBadge from '@/components/common/StatusBadge.vue'

const application = ref({
  id: 1,
  number: 'PRJ-2026-001',
  applicant: 'Budi Santoso',
  name: 'Permohonan AMDAL',
  category: 'AMDAL',
  status: 'UNDER_REVIEW',
  notes: 'Dokumen sudah lengkap, namun perlu penjelasan peta lokasi.',
})

function requestRevision() {
  application.value.status = 'REVISION_REQUIRED'
}

function approveApplication() {
  application.value.status = 'APPROVED'
}

function rejectApplication() {
  application.value.status = 'REJECTED'
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

.actions {
  display: flex;
  flex-wrap: wrap;
  gap: var(--spacing-3);
  margin-top: var(--spacing-5);
}

@media (max-width: 768px) {
  .content-grid,
  .page-header {
    display: grid;
    grid-template-columns: 1fr;
  }
}
</style>
