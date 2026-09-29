<template>
	<div class="document-list">
		<div class="document-list__header">
			<h3>{{ title }}</h3>
			<slot name="action" />
		</div>

		<div v-if="!documents.length" class="empty-state">Belum ada dokumen yang diunggah.</div>

		<div v-else class="document-list__items">
			<DocumentItem
				v-for="document in documents"
				:key="document.id || document.name"
				:document="document"
				@view="$emit('view', document)"
				@download="$emit('download', document)"
			/>
		</div>
	</div>
</template>

<script setup>
import DocumentItem from './DocumentItem.vue'

defineProps({
	title: {
		type: String,
		default: 'Dokumen',
	},
	documents: {
		type: Array,
		default: () => [],
	},
})

defineEmits(['view', 'download'])
</script>

<style scoped>
.document-list {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-4);
}

.document-list__header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: var(--spacing-3);
}

.document-list__header h3 {
	margin: 0;
}

.document-list__items {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-3);
}

.empty-state {
	color: var(--color-text-muted);
	border: 1px dashed var(--color-border);
	border-radius: var(--radius-md);
	padding: var(--spacing-5);
	text-align: center;
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