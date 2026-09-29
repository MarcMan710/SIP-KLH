<template>
	<form class="revision-form" @submit.prevent="submitForm">
		<div class="field-group">
			<label class="field-label">Permintaan Revisi</label>
			<textarea v-model="form.reason" rows="4" class="text-area" placeholder="Masukkan alasan revisi berdasarkan catatan penilai"></textarea>
		</div>

		<div class="field-group">
			<label class="field-label">Perbaikan yang Diajukan</label>
			<textarea v-model="form.details" rows="5" class="text-area" placeholder="Jelaskan bagian yang akan diperbaiki atau dokumen yang diganti"></textarea>
		</div>

		<DocumentUploader @files-selected="handleFiles" />

		<div class="form-actions">
			<AppButton type="button" variant="outline" @click="$emit('cancel')">Batal</AppButton>
			<AppButton type="submit" :loading="loading">Resubmit</AppButton>
		</div>
	</form>
</template>

<script setup>
import { reactive, watch } from 'vue'
import AppButton from '@/components/common/AppButton.vue'
import DocumentUploader from '@/components/document/DocumentUploader.vue'

const props = defineProps({
	modelValue: {
		type: Object,
		default: () => ({ reason: '', details: '', files: [] })
	},
	loading: {
		type: Boolean,
		default: false,
	}
})

const emit = defineEmits(['update:modelValue', 'submit', 'cancel'])

const form = reactive({ reason: '', details: '', files: [] })

watch(
	() => props.modelValue,
	(value) => {
		if (!value) return
		form.reason = value.reason || ''
		form.details = value.details || ''
		form.files = value.files || []
	},
	{ immediate: true },
)

function handleFiles(files) {
	form.files = files
	emit('update:modelValue', { ...form, files })
}

function submitForm() {
	emit('update:modelValue', { ...form })
	emit('submit', { ...form })
}
</script>

<style scoped>
.revision-form {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-5);
}

.field-group {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-2);
}

.field-label {
	font-weight: 600;
}

.text-area {
	width: 100%;
	resize: vertical;
	border: 1px solid var(--color-border);
	border-radius: var(--radius-md);
	background: var(--color-bg-surface);
	padding: var(--spacing-3);
	font: inherit;
	color: var(--color-text-main);
}

.form-actions {
	display: flex;
	justify-content: flex-end;
	gap: var(--spacing-3);
}
</style>
<!--
Provide the applicant interface for addressing
a revision request.

Display:
- Revision request.
- Assessor's notes.
- Fields that can be corrected.
- Document replacement/upload.
- Resubmit action.

Only allow editing when the project is
REVISION_REQUIRED.
-->