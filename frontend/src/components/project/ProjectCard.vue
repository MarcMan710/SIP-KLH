<template>
	<article class="project-card" @click="$emit('click', project)">
		<div class="project-card__header">
			<div>
				<p class="project-card__number">{{ project.number || 'PRJ-' + project.id }}</p>
				<h3>{{ project.name || 'Permohonan baru' }}</h3>
			</div>
			<StatusBadge :status="project.status || 'DRAFT'" />
		</div>

		<p class="project-card__description">
			{{ project.description || 'Belum ada deskripsi permohonan.' }}
		</p>

		<dl class="project-card__meta">
			<div>
				<dt>Jenis</dt>
				<dd>{{ project.category || 'Dokumen' }}</dd>
			</div>
			<div>
				<dt>Terakhir update</dt>
				<dd>{{ formatDate(project.updated_at || project.created_at) }}</dd>
			</div>
		</dl>

		<div class="project-card__footer">
			<span class="project-card__action">Lihat detail</span>
			<div class="project-card__buttons">
				<AppButton v-if="showEdit" size="sm" variant="outline" @click.stop="$emit('edit', project)">Edit</AppButton>
				<AppButton v-if="showSubmit" size="sm" @click.stop="$emit('submit', project)">Submit</AppButton>
			</div>
		</div>
	</article>
</template>

<script setup>
import AppButton from '@/components/common/AppButton.vue'
import StatusBadge from '@/components/common/StatusBadge.vue'

const props = defineProps({
	project: {
		type: Object,
		default: () => ({})
	},
	showEdit: {
		type: Boolean,
		default: true,
	},
	showSubmit: {
		type: Boolean,
		default: true,
	},
})

defineEmits(['click', 'edit', 'submit'])

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

const project = props.project
</script>

<style scoped>
.project-card {
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius-lg);
	box-shadow: var(--shadow-sm);
	padding: var(--spacing-5);
	display: flex;
	flex-direction: column;
	gap: var(--spacing-4);
	cursor: pointer;
	transition: box-shadow var(--transition-fast), transform var(--transition-fast);
}

.project-card:hover {
	box-shadow: var(--shadow-md);
	transform: translateY(-1px);
}

.project-card__header {
	display: flex;
	justify-content: space-between;
	gap: var(--spacing-3);
	align-items: flex-start;
}

.project-card__number {
	margin: 0 0 var(--spacing-1);
	font-size: var(--font-size-xs);
	letter-spacing: 0.08em;
	text-transform: uppercase;
	color: var(--color-text-muted);
}

.project-card__header h3 {
	margin: 0;
	font-size: var(--font-size-lg);
}

.project-card__description {
	margin: 0;
	color: var(--color-text-muted);
	line-height: 1.6;
}

.project-card__meta {
	display: grid;
	gap: var(--spacing-2);
	margin: 0;
}

.project-card__meta div {
	display: flex;
	justify-content: space-between;
	gap: var(--spacing-4);
}

.project-card__meta dt {
	color: var(--color-text-muted);
}

.project-card__meta dd {
	margin: 0;
	font-weight: 600;
	text-align: right;
}

.project-card__footer {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: var(--spacing-3);
	border-top: 1px solid var(--color-border);
	padding-top: var(--spacing-3);
}

.project-card__action {
	color: var(--color-primary);
	font-weight: 600;
}

.project-card__buttons {
	display: flex;
	gap: var(--spacing-2);
}
</style>
