<template>
  <div class="page-shell">
    <div class="page-header">
      <div>
        <p class="eyebrow">Permohonan</p>
        <h1>Buat Draft Permohonan</h1>
      </div>
    </div>

    <div class="card form-card">
      <form @submit.prevent="submitForm">
        <AppInput v-model="form.name" label="Judul Permohonan" placeholder="Contoh: AMDAL Pabrik X" :error="errors.name" />
        <AppInput v-model="form.number" label="Nomor Permohonan" placeholder="PRJ-2026-001" :error="errors.number" />
        <AppSelect v-model="form.category" label="Jenis Dokumen" :options="documentTypes" placeholder="Pilih jenis" :error="errors.category" />
        <AppInput v-model="form.description" label="Deskripsi" placeholder="Deskripsikan kebutuhan dokumen" :error="errors.description" />

        <div class="actions">
          <AppButton type="button" variant="outline" @click="goBack">Batal</AppButton>
          <AppButton type="submit">Simpan Draft</AppButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import AppButton from '@/components/common/AppButton.vue'
import AppInput from '@/components/common/AppInput.vue'
import AppSelect from '@/components/common/AppSelect.vue'

const router = useRouter()

const form = reactive({
  name: '',
  number: '',
  category: '',
  description: '',
})

const errors = reactive({
  name: '',
  number: '',
  category: '',
  description: '',
})

const documentTypes = [
  { value: 'AMDAL', label: 'AMDAL' },
  { value: 'UKL_UPL', label: 'UKL-UPL' },
  { value: 'SPPL', label: 'SPPL' },
]

function goBack() {
  router.back()
}

function submitForm() {
  Object.keys(errors).forEach((key) => { errors[key] = '' })
  if (!form.name) errors.name = 'Judul wajib diisi.'
  if (!form.number) errors.number = 'Nomor wajib diisi.'
  if (!form.category) errors.category = 'Jenis dokumen wajib dipilih.'
  if (!form.description) errors.description = 'Deskripsi wajib diisi.'

  if (Object.values(errors).some(Boolean)) return

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
