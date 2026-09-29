<template>
	<form class="assessment-form" @submit.prevent="submitForm">
		<div class="field-group">
			<label class="field-label">Catatan Penilaian</label>
			<textarea
				v-model="form.notes"
				rows="5"
				class="text-area"
				placeholder="Berikan catatan evaluasi, temuan, atau komentar terhadap dokumen yang diajukan"
			></textarea>
		</div>

		<div class="button-row">
			<AppButton type="button" variant="outline" @click="$emit('cancel')">Batal</AppButton>
			<AppButton
				v-if="canRequestRevision"
				type="button"
				variant="secondary"
				:loading="loading"
				@click="submitAction('REVISION')"
			>
				Permintaan Revisi
			</AppButton>
			<AppButton
				v-if="canReject"
				type="button"
				variant="danger"
				:loading="loading"
				@click="submitAction('REJECT')"
			>
				Tolak
			</AppButton>
			<AppButton
				v-if="canApprove"
				type="button"
				:loading="loading"
				@click="submitAction('APPROVE')"
			>
				Setujui
			</AppButton>
		</div>
	</form>
</template>

<script setup>
import { reactive, watch } from 'vue'
import AppButton from '@/components/common/AppButton.vue'

const props = defineProps({
	modelValue: {
		type: Object,
		default: () => ({ notes: '' }),
	},
	canApprove: {
		type: Boolean,
		default: true,
	},
	canReject: {
		type: Boolean,
		default: true,
	},
	canRequestRevision: {
		type: Boolean,
		default: true,
	},
	loading: {
		type: Boolean,
		default: false,
	},
})

const emit = defineEmits(['update:modelValue', 'submit', 'cancel'])

const form = reactive({ notes: '' })

watch(
	() => props.modelValue,
	(value) => {
		form.notes = value?.notes || ''
	},
	{ immediate: true },
)

function submitAction(action) {
	emit('update:modelValue', { ...form, action })
	emit('submit', { ...form, action })
}

function submitForm() {
	emit('update:modelValue', { ...form })
	emit('submit', { ...form, action: 'APPROVE' })
}
</script>

<style scoped>
.assessment-form {
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
	font-size: var(--font-size-sm);
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

.button-row {
	display: flex;
	flex-wrap: wrap;
	justify-content: flex-end;
	gap: var(--spacing-3);
}
</style>
<!--
Provide the assessor's assessment form.

Allow the Penilai to:
- Add assessment notes.
- Request revision.
- Approve.
- Reject.

Require confirmation before destructive or
final decisions.

Do not allow the assessor to create arbitrary
status values.
-->