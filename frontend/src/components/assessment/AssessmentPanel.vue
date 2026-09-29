<template>
	<div class="assessment-panel">
		<div class="panel-header">
			<h3>Penilaian Saat Ini</h3>
			<StatusBadge :status="assessment.status || 'UNDER_REVIEW'" :label="assessment.statusLabel || 'Under Review'" />
		</div>

		<dl class="panel-list">
			<div><dt>Penilai</dt><dd>{{ assessment.assessor || '-' }}</dd></div>
			<div><dt>Tanggal</dt><dd>{{ formatDate(assessment.date || assessment.created_at) }}</dd></div>
		</dl>

		<div class="notes-box">
			<strong>Catatan</strong>
			<p>{{ assessment.notes || 'Belum ada catatan penilaian.' }}</p>
		</div>

		<div class="panel-actions">
			<AppButton v-if="allowApprove" @click="$emit('approve')">Approve</AppButton>
			<AppButton v-if="allowReject" variant="danger" @click="$emit('reject')">Reject</AppButton>
			<AppButton v-if="allowRevision" variant="secondary" @click="$emit('revision')">Revision</AppButton>
		</div>
	</div>
</template>

<script setup>
import AppButton from '@/components/common/AppButton.vue'
import StatusBadge from '@/components/common/StatusBadge.vue'

defineProps({
	assessment: {
		type: Object,
		default: () => ({})
	},
	allowApprove: {
		type: Boolean,
		default: false,
	},
	allowReject: {
		type: Boolean,
		default: false,
	},
	allowRevision: {
		type: Boolean,
		default: false,
	},
})

defineEmits(['approve', 'reject', 'revision'])

function formatDate(value) {
	if (!value) return '-'
	const date = new Date(value)
	if (Number.isNaN(date.getTime())) return value

	return new Intl.DateTimeFormat('id-ID', {
		day: '2-digit',
		month: 'short',
		year: 'numeric',
	}).format(date)
}
</script>

<style scoped>
.assessment-panel {
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius-lg);
	padding: var(--spacing-5);
	display: flex;
	flex-direction: column;
	gap: var(--spacing-4);
}

.panel-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: var(--spacing-3);
}

.panel-header h3 {
	margin: 0;
}

.panel-list {
	display: grid;
	gap: var(--spacing-3);
	margin: 0;
}

.panel-list div {
	display: flex;
	justify-content: space-between;
	gap: var(--spacing-3);
}

.panel-list dt {
	color: var(--color-text-muted);
}

.panel-list dd {
	margin: 0;
	font-weight: 600;
}

.notes-box {
	border-top: 1px solid var(--color-border);
	border-bottom: 1px solid var(--color-border);
	padding: var(--spacing-3) 0;
}

.notes-box p {
	margin: var(--spacing-2) 0 0;
	color: var(--color-text-main);
	line-height: 1.6;
}

.panel-actions {
	display: flex;
	flex-wrap: wrap;
	gap: var(--spacing-3);
}
</style>
<!--
Display the current assessment state.

Show:
- Assessment status.
- Assessor.
- Notes.
- Assessment date.
- Available actions.

Only show actions that are valid for the
current application state.
-->