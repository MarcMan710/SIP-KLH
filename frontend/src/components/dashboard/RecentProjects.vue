<template>
	<div class="recent-projects">
		<div class="header-row">
			<h3>Permohonan Terbaru</h3>
		</div>

		<ul v-if="projects.length" class="project-list">
			<li v-for="project in projects" :key="project.id" class="project-item">
				<div>
					<strong>{{ project.name || 'Permohonan' }}</strong>
					<small>{{ project.number || `PRJ-${project.id}` }}</small>
				</div>
				<div class="project-meta">
					<StatusBadge :status="project.status || 'DRAFT'" />
					<span>{{ formatDate(project.updated_at || project.created_at) }}</span>
				</div>
			</li>
		</ul>

		<div v-else class="empty-state">Belum ada permohonan terbaru.</div>
	</div>
</template>

<script setup>
import StatusBadge from '@/components/common/StatusBadge.vue'

defineProps({
	projects: {
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
.recent-projects {
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius-lg);
	padding: var(--spacing-5);
}

.header-row h3 {
	margin: 0 0 var(--spacing-4);
}

.project-list {
	list-style: none;
	margin: 0;
	padding: 0;
	display: flex;
	flex-direction: column;
	gap: var(--spacing-3);
}

.project-item {
	display: flex;
	justify-content: space-between;
	gap: var(--spacing-3);
	align-items: center;
	border-bottom: 1px solid var(--color-border);
	padding-bottom: var(--spacing-3);
}

.project-item:last-child {
	border-bottom: none;
	padding-bottom: 0;
}

.project-item strong,
.project-item small,
.project-item span {
	display: block;
}

.project-item small,
.project-meta {
	color: var(--color-text-muted);
}

.project-meta {
	display: flex;
	flex-direction: column;
	align-items: flex-end;
	gap: var(--spacing-2);
}

.empty-state {
	color: var(--color-text-muted);
	text-align: center;
	padding: var(--spacing-4) 0 0;
}
</style>
<!--
Display a small list of recently created or updated projects.

Show:
- Project number.
- Project name.
- Status.
- Last update.
- Action link.

Only retrieve the limited number of records
needed for the dashboard.
-->