<template>
	<div class="revision-list">
		<div v-if="!revisions.length" class="empty-state">Belum ada riwayat revisi.</div>

		<ul v-else class="revision-items">
			<li v-for="(revision, index) in revisions" :key="revision.id || index" class="revision-item">
				<div class="item-top">
					<strong>{{ revision.title || 'Revisi' }}</strong>
					<StatusBadge :status="revision.status || 'REVISION_REQUIRED'" :label="revision.statusLabel || 'Revision Required'" />
				</div>
				<p>{{ revision.reason || 'Tidak ada keterangan revisi.' }}</p>
				<small>{{ revision.requested_by || 'Penilai' }} · {{ formatDate(revision.created_at) }}</small>
			</li>
		</ul>
	</div>
</template>

<script setup>
import StatusBadge from '@/components/common/StatusBadge.vue'

defineProps({
	revisions: {
		type: Array,
		default: () => [],
	},
})

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
.revision-list {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-4);
}

.empty-state {
	color: var(--color-text-muted);
	border: 1px dashed var(--color-border);
	border-radius: var(--radius-md);
	padding: var(--spacing-5);
	text-align: center;
}

.revision-items {
	list-style: none;
	padding: 0;
	margin: 0;
	display: flex;
	flex-direction: column;
	gap: var(--spacing-3);
}

.revision-item {
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius-md);
	padding: var(--spacing-4);
}

.item-top {
	display: flex;
	justify-content: space-between;
	gap: var(--spacing-3);
	align-items: center;
}

.revision-item p {
	margin: var(--spacing-3) 0 var(--spacing-2);
	line-height: 1.6;
}

.revision-item small {
	color: var(--color-text-muted);
}
</style>
<!--
Display all revision requests for a project.

Show:
- Revision number.
- Requester.
- Reason.
- Date.
- Resolution status.

Preserve the complete revision history.
-->