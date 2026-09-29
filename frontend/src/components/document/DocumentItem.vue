<template>
	<div class="document-item">
		<div class="document-item__content">
			<div class="document-icon">PDF</div>
			<div>
				<strong>{{ document.name || 'Dokumen' }}</strong>
				<small>{{ document.type || 'Dokumen pendukung' }}</small>
			</div>
		</div>

		<div class="document-item__meta">
			<span>{{ formatSize(document.size) }}</span>
			<span>{{ formatDate(document.uploaded_at || document.created_at) }}</span>
		</div>

		<div class="document-item__actions">
			<AppButton size="sm" variant="outline" @click="$emit('view', document)">Lihat</AppButton>
			<AppButton size="sm" variant="ghost" @click="$emit('download', document)">Unduh</AppButton>
		</div>
	</div>
</template>

<script setup>
import AppButton from '@/components/common/AppButton.vue'

defineProps({
	document: {
		type: Object,
		default: () => ({})
	},
})

defineEmits(['view', 'download'])

function formatSize(size) {
	if (!size) return '—'
	const num = Number(size)
	if (Number.isNaN(num)) return size
	if (num < 1024) return `${num} B`
	if (num < 1024 * 1024) return `${(num / 1024).toFixed(1)} KB`
	return `${(num / (1024 * 1024)).toFixed(1)} MB`
}

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
.document-item {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: var(--spacing-4);
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius-md);
	padding: var(--spacing-4);
}

.document-item__content {
	display: flex;
	align-items: center;
	gap: var(--spacing-3);
}

.document-icon {
	width: 42px;
	height: 42px;
	border-radius: var(--radius-md);
	background: var(--color-primary-light);
	color: var(--color-primary);
	display: inline-flex;
	align-items: center;
	justify-content: center;
	font-size: var(--font-size-xs);
	font-weight: 700;
}

.document-item__content strong,
.document-item__content small {
	display: block;
}

.document-item__content small {
	margin-top: 2px;
	color: var(--color-text-muted);
}

.document-item__meta {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-1);
	color: var(--color-text-muted);
	font-size: var(--font-size-xs);
}

.document-item__actions {
	display: flex;
	gap: var(--spacing-2);
}

@media (max-width: 768px) {
	.document-item {
		flex-direction: column;
		align-items: flex-start;
	}
}
</style>
<!--
Display all documents belonging to a project.

Show:
- Document type.
- Filename.
- File size.
- Upload date.
- Uploader when appropriate.
- Available actions.

Allow users to access documents only when
authorized by the backend.
-->