<template>
  <div class="page-shell">
    <div class="page-header">
      <div>
        <p class="eyebrow">Permohonan</p>
        <h1>Edit Draft</h1>
      </div>
    </div>

    <div class="card form-card">
      <form @submit.prevent="saveProject">
        <AppInput v-model="form.name" label="Judul Permohonan" />
        <AppInput v-model="form.number" label="Nomor Permohonan" />
        <AppSelect v-model="form.category" label="Jenis Dokumen" :options="documentTypes" />
        <AppInput v-model="form.description" label="Deskripsi" />

        <div class="actions">
          <AppButton type="button" variant="outline" @click="goBack">Batal</AppButton>
          <AppButton type="submit">Simpan</AppButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppButton from '@/components/common/AppButton.vue'
import AppInput from '@/components/common/AppInput.vue'
import AppSelect from '@/components/common/AppSelect.vue'

const route = useRoute()
const router = useRouter()

const form = reactive({
  name: 'Permohonan AMDAL',
  number: `PRJ-${route.params.id || 1}`,
  category: 'AMDAL',
  description: 'Sudah diperbarui sesuai kebutuhan.',
})

const documentTypes = [
  { value: 'AMDAL', label: 'AMDAL' },
  { value: 'UKL_UPL', label: 'UKL-UPL' },
  { value: 'SPPL', label: 'SPPL' },
]

function saveProject() {
  router.push({ name: 'applicant-project-detail', params: { id: route.params.id } })
}

function goBack() {
  router.back()
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

.card {
  background: var(--color-bg-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  padding: var(--spacing-5);
}

.form-card {
  max-width: 720px;
}

.actions {
  display: flex;
  justify-content: flex-end;
  gap: var(--spacing-3);
  margin-top: var(--spacing-4);
}
</style>
