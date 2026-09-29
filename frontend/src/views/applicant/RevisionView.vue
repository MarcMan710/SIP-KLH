<template>
  <div class="page-shell">
    <div class="page-header">
      <div>
        <p class="eyebrow">Revision</p>
        <h1>Perbaikan Permohonan</h1>
      </div>
    </div>

    <div class="content-grid">
      <div class="card">
        <h3>Catatan Penilai</h3>
        <p>Mohon sertakan peta lokasi yang lebih jelas dan tambahkan dokumen pendukung.</p>
      </div>

      <div class="card">
        <h3>Perbaikan</h3>
        <AppInput v-model="form.title" label="Judul Baru" />
        <AppInput v-model="form.notes" label="Catatan Perbaikan" />
        <AppButton @click="resubmit">Resubmit</AppButton>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import AppButton from '@/components/common/AppButton.vue'
import AppInput from '@/components/common/AppInput.vue'

const router = useRouter()
const form = reactive({
  title: 'Permohonan AMDAL Revisi',
  notes: 'Dokumen peta dan lampiran teknis sudah ditambahkan.',
})

function resubmit() {
  router.push({ name: 'applicant-project-detail', params: { id: 1 } })
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
  grid-template-columns: 1fr 1fr;
  gap: var(--spacing-5);
}

.card {
  background: var(--color-bg-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  padding: var(--spacing-5);
}

@media (max-width: 768px) {
  .content-grid {
    grid-template-columns: 1fr;
  }
}
</style>
