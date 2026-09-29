<template>
	<div class="assessment-history">
		<div v-if="!records.length" class="empty-state">Belum ada riwayat penilaian.</div>
		<ul v-else class="history-list">
			<li v-for="(record, index) in records" :key="record.id || index" class="history-item">
				<div class="history-meta">
					<strong>{{ record.assessor || 'Penilai' }}</strong>
					<StatusBadge :status="mapActionToStatus(record.action)" :label="getActionLabel(record.action)" />
				</div>
				<p>{{ record.notes || 'Tidak ada catatan.' }}</p>
				<small>{{ formatDate(record.created_at || record.date) }}</small>
			</li>
		</ul>
	</div>
</template>

<script setup>
import StatusBadge from '@/components/common/StatusBadge.vue'

defineProps({
	records: {
		type: Array,
		default: () => [],
	},
})

function mapActionToStatus(action) {
	if (!action) return 'DRAFT'
	const map = {
		APPROVE: 'APPROVED',
		REJECT: 'REJECTED',
		REVISION: 'REVISION_REQUIRED',
	}
	return map[action] || 'SUBMITTED'
}

function getActionLabel(action) {
	const map = {
		APPROVE: 'Approved',
		REJECT: 'Rejected',
		REVISION: 'Revision Requested',
	}
	return map[action] || action || 'Reviewed'
}

function formatDate(value) {
	if (!value) return '-'
	const date = new Date(value)
	if (Number.isNaN(date.getTime())) return value

	return new Intl.DateTimeFormat('id-ID', {
		day: '2-digit',
		month: 'short',
		year: 'numeric',
		hour: '2-digit',
		minute: '2-digit',
	}).format(date)
}
</script>

<style scoped>
.assessment-history {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-4);
}

.empty-state {
	padding: var(--spacing-5);
	border: 1px dashed var(--color-border);
	border-radius: var(--radius-md);
	color: var(--color-text-muted);
	text-align: center;
}

.history-list {
	list-style: none;
	padding: 0;
	margin: 0;
	display: flex;
	flex-direction: column;
	gap: var(--spacing-3);
}

.history-item {
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius-md);
	padding: var(--spacing-4);
}

.history-meta {
	display: flex;
	justify-content: space-between;
	gap: var(--spacing-3);
	align-items: center;
}

.history-item p {
	margin: var(--spacing-3) 0 var(--spacing-2);
	color: var(--color-text-main);
	line-height: 1.6;
}

.history-item small {
	color: var(--color-text-muted);
}
</style>
<!--
Display previous assessment records.

Show:
- Assessor.
- Assessment action.
- Notes.
- Date.

Display records chronologically or newest-first
according to the application's UX design.

Use pagination if the history becomes large.
-->