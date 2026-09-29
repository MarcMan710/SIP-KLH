<template>
	<form class="project-form" @submit.prevent="submitForm">
		<div class="form-grid">
			<AppInput
				v-model="form.name"
				label="Judul Permohonan"
				required
				:error="errors.name"
				placeholder="Masukkan judul permohonan"
			/>

			<AppInput
				v-model="form.number"
				label="Nomor Permohonan"
				:error="errors.number"
				placeholder="PRJ-2026-001"
				:disabled="mode === 'edit'"
			/>

			<AppSelect
				v-model="form.category"
				label="Jenis Permohonan"
				:options="documentTypes"
				:error="errors.category"
			/>
		</div>

		<div class="field-group">
			<label class="field-label">Deskripsi</label>
			<textarea
				v-model="form.description"
				rows="5"
				class="text-area"
				:class="{ 'has-error': errors.description }"
				placeholder="Jelaskan kebutuhan dokumen atau kegiatan yang diajukan"
			></textarea>
			<small v-if="errors.description" class="field-error">{{ errors.description }}</small>
		</div>

		<div class="field-group">
			<label class="field-label">Catatan Tambahan</label>
			<textarea
				v-model="form.notes"
				rows="4"
				class="text-area"
				placeholder="Tambahkan catatan tambahan bila diperlukan"
			></textarea>
		</div>

		<div class="form-actions">
			<AppButton type="button" variant="outline" @click="$emit('cancel')">Batal</AppButton>
			<AppButton type="submit" :loading="loading">
				{{ submitLabel }}
			</AppButton>
		</div>
	</form>
</template>

<script setup>
import { reactive, watch } from 'vue'
import AppInput from '@/components/common/AppInput.vue'
import AppSelect from '@/components/common/AppSelect.vue'
import AppButton from '@/components/common/AppButton.vue'
import { DOCUMENT_TYPES } from '@/utils/constants'

const props = defineProps({
	modelValue: {
		type: Object,
		default: () => ({})
	},
	mode: {
		type: String,
		default: 'create',
	},
	loading: {
		type: Boolean,
		default: false,
	},
	error: {
		type: String,
		default: '',
	},
})

const emit = defineEmits(['update:modelValue', 'submit', 'cancel'])

const form = reactive({
	name: '',
	number: '',
	category: DOCUMENT_TYPES[0]?.value || 'AMDAL',
	description: '',
	notes: '',
})

const errors = reactive({
	name: '',
	number: '',
	category: '',
	description: '',
})

const submitLabel = props.mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Draft'

watch(
	() => props.modelValue,
	(value) => {
		if (!value) return

		Object.assign(form, {
			name: value.name || '',
			number: value.number || '',
			category: value.category || DOCUMENT_TYPES[0]?.value || 'AMDAL',
			description: value.description || '',
			notes: value.notes || '',
		})
	},
	{ immediate: true },
)

function validate() {
	let isValid = true
	errors.name = ''
	errors.number = ''
	errors.category = ''
	errors.description = ''

	if (!form.name.trim()) {
		errors.name = 'Judul permohonan wajib diisi.'
		isValid = false
	}
	if (!form.description.trim()) {
		errors.description = 'Deskripsi permohonan wajib diisi.'
		isValid = false
	}
	if (!form.category) {
		errors.category = 'Jenis dokumen wajib dipilih.'
		isValid = false
	}

	return isValid
}

function submitForm() {
	if (!validate()) return

	emit('update:modelValue', { ...form })
	emit('submit', { ...form })
}
</script>

<style scoped>
.project-form {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-5);
}

.form-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: var(--spacing-4);
}

.field-group {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-2);
}

.field-label {
	font-size: var(--font-size-sm);
	font-weight: 600;
	color: var(--color-text-main);
}

.text-area {
	width: 100%;
	resize: vertical;
	background-color: var(--color-bg-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius-md);
	padding: var(--spacing-3);
	font: inherit;
	color: var(--color-text-main);
}

.text-area.has-error {
	border-color: var(--color-danger);
}

.field-error {
	color: var(--color-danger);
	font-size: var(--font-size-xs);
}

.form-actions {
	display: flex;
	justify-content: flex-end;
	gap: var(--spacing-3);
}

@media (max-width: 768px) {
	.form-grid {
		grid-template-columns: 1fr;
	}
}
</style>
<!--
Provide the reusable project/application form.

Fields should represent the information required
to create a document application.

Support:
- Create mode.
- Edit mode.
- Validation.
- Loading state.
- Error handling.

Do not allow status to be edited manually.

The initial project status should be controlled by
the backend and start as Draft.
-->