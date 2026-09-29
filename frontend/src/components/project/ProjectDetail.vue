<template>
	<div class="project-detail">
		<div class="detail-header">
			<div>
				<p class="eyebrow">Permohonan</p>
				<h2>{{ project.name || 'Permohonan' }}</h2>
			</div>
			<StatusBadge :status="project.status || 'DRAFT'" />
		</div>

		<div class="detail-grid">
			<div class="detail-card">
				<h3>Informasi Umum</h3>
				<dl class="detail-list">
					<div><dt>Nomor</dt><dd>{{ project.number || '-' }}</dd></div>
					<div><dt>Jenis</dt><dd>{{ project.category || '-' }}</dd></div>
					<div><dt>Status</dt><dd>{{ project.status || 'DRAFT' }}</dd></div>
					<div><dt>Pengajuan</dt><dd>{{ formatDate(project.created_at) }}</dd></div>
					<div><dt>Update terakhir</dt><dd>{{ formatDate(project.updated_at) }}</dd></div>
				</dl>
			</div>

			<div class="detail-card">
				<h3>Deskripsi</h3>
				<p>{{ project.description || 'Belum ada deskripsi yang disampaikan.' }}</p>
			</div>
		</div>

		<div class="detail-actions">
			<AppButton v-if="canEdit" variant="outline" @click="$emit('edit', project)">Edit</AppButton>
			<AppButton v-if="canSubmit" @click="$emit('submit', project)">Submit</AppButton>
			<AppButton v-if="canApprove" variant="secondary" @click="$emit('approve', project)">Approve</AppButton>
			<AppButton v-if="canReject" variant="danger" @click="$emit('reject', project)">Reject</AppButton>
		</div>
	</div>
</template>

<script setup>
import AppButton from '@/components/common/AppButton.vue'
import StatusBadge from '@/components/common/StatusBadge.vue'

defineProps({
	project: {
		type: Object,
		default: () => ({})
	},
	canEdit: {
		type: Boolean,
		default: true,
	},
	canSubmit: {
		type: Boolean,
		default: true,
	},
	canApprove: {
		type: Boolean,
		default: false,
	},
	canReject: {
		type: Boolean,
		default: false,
	},
})

defineEmits(['edit', 'submit', 'approve', 'reject'])

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
.project-detail {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-6);
}

.detail-header {
	display: flex;
	justify-content: space-between;
	gap: var(--spacing-3);
	align-items: flex-start;
}

.eyebrow {
	text-transform: uppercase;
	letter-spacing: 0.08em;
	font-size: 0.72rem;
	color: var(--color-primary);
	font-weight: 700;
	margin: 0 0 var(--spacing-1);
}

.detail-header h2 {
	margin: 0;
}

.detail-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: var(--spacing-5);
}

.detail-card {
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius-lg);
	padding: var(--spacing-5);
}

.detail-list {
	display: grid;
	gap: var(--spacing-3);
	margin: var(--spacing-4) 0 0;
}

.detail-list div {
	display: grid;
	grid-template-columns: 150px 1fr;
	gap: var(--spacing-3);
}

.detail-list dt {
	color: var(--color-text-muted);
}

.detail-list dd {
	margin: 0;
	font-weight: 600;
}

.detail-actions {
	display: flex;
	flex-wrap: wrap;
	gap: var(--spacing-3);
}

@media (max-width: 768px) {
	.detail-grid {
		grid-template-columns: 1fr;
	}
}
</style>