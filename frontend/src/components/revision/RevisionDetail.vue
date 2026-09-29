<template>
	<div class="revision-detail">
		<div class="revision-header">
			<div>
				<p class="eyebrow">Revisi</p>
				<h3>{{ revision.title || 'Permintaan revisi' }}</h3>
			</div>
			<StatusBadge :status="revision.status || 'REVISION_REQUIRED'" :label="revision.statusLabel || 'Revision Required'" />
		</div>

		<div class="revision-body">
			<div class="revision-card">
				<h4>Alasan</h4>
				<p>{{ revision.reason || 'Tidak ada alasan yang dicantumkan.' }}</p>
			</div>

			<div class="revision-card">
				<h4>Catatan Penilai</h4>
				<p>{{ revision.notes || 'Belum ada catatan tambahan.' }}</p>
			</div>
		</div>

		<div class="revision-footer">
			<small>Diminta oleh {{ revision.requested_by || 'Penilai' }} · {{ formatDate(revision.created_at) }}</small>
			<AppButton v-if="allowResolve" @click="$emit('resolve', revision)">Selesaikan</AppButton>
		</div>
	</div>
</template>

<script setup>
import AppButton from '@/components/common/AppButton.vue'
import StatusBadge from '@/components/common/StatusBadge.vue'

defineProps({
	revision: {
		type: Object,
		default: () => ({})
	},
	allowResolve: {
		type: Boolean,
		default: false,
	},
})

defineEmits(['resolve'])

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
.revision-detail {
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius-lg);
	padding: var(--spacing-5);
	display: flex;
	flex-direction: column;
	gap: var(--spacing-4);
}

.revision-header {
	display: flex;
	justify-content: space-between;
	align-items: flex-start;
	gap: var(--spacing-3);
}

.eyebrow {
	margin: 0 0 var(--spacing-1);
	font-size: 0.72rem;
	letter-spacing: 0.08em;
	text-transform: uppercase;
	color: var(--color-primary);
	font-weight: 700;
}

.revision-header h3 {
	margin: 0;
}

.revision-body {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: var(--spacing-4);
}

.revision-card {
	border: 1px solid var(--color-border);
	border-radius: var(--radius-md);
	padding: var(--spacing-4);
}

.revision-card h4 {
	margin: 0 0 var(--spacing-2);
}

.revision-card p {
	margin: 0;
	color: var(--color-text-main);
	line-height: 1.6;
}

.revision-footer {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: var(--spacing-3);
	color: var(--color-text-muted);
}

@media (max-width: 768px) {
	.revision-body {
		grid-template-columns: 1fr;
	}
}
</style>
