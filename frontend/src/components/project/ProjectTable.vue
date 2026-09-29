<template>
	<div class="project-table">
		<div class="project-table__header" v-if="title || $slots.header">
			<h3 v-if="title">{{ title }}</h3>
			<slot name="header" />
		</div>

		<AppTable :columns="columns" :data="rows" :loading="loading" :empty-text="emptyText">
			<template v-for="column in columns" #[column.key]="{ item }">
				<slot :name="column.key" :item="item">
					<template v-if="column.type === 'status'">
						<StatusBadge :status="item[column.key] || 'DRAFT'" />
					</template>
					<template v-else>
						{{ item[column.key] ?? '-' }}
					</template>
				</slot>
			</template>
		</AppTable>
	</div>
</template>

<script setup>
import AppTable from '@/components/common/AppTable.vue'
import StatusBadge from '@/components/common/StatusBadge.vue'

defineProps({
	title: {
		type: String,
		default: '',
	},
	columns: {
		type: Array,
		required: true,
	},
	rows: {
		type: Array,
		default: () => [],
	},
	loading: {
		type: Boolean,
		default: false,
	},
	emptyText: {
		type: String,
		default: 'Tidak ada data yang tersedia.',
	},
})
</script>

<style scoped>
.project-table {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-4);
}

.project-table__header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: var(--spacing-3);
}

.project-table__header h3 {
	margin: 0;
}
</style>
<!--
Display a paginated list of projects.

Columns may include:
- Project number.
- Project name.
- Status.
- Submitted date.
- Updated date.
- Actions.

Provide:
- Search.
- Status filter.
- Pagination.
- Detail action.

For Pemohon, display the user's own projects.

For Penilai, display applications available for assessment.
-->