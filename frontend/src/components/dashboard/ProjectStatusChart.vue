<template>
	<div class="chart-box">
		<div class="chart-header">
			<h3>Statistik Permohonan</h3>
		</div>

		<div class="chart-bars" v-if="stats.length">
			<div v-for="item in stats" :key="item.label" class="chart-bar-group">
				<div class="chart-bar-meta">
					<span>{{ item.label }}</span>
					<strong>{{ item.value }}</strong>
				</div>
				<div class="chart-bar-track">
					<div
						class="chart-bar-fill"
						:style="{ width: getPercent(item.value), background: item.color || '#0f766e' }"
					></div>
				</div>
			</div>
		</div>

		<div v-else class="empty-state">Belum ada data statistik.</div>
	</div>
</template>

<script setup>
const props = defineProps({
	stats: {
		type: Array,
		default: () => [],
	},
})

function getPercent(value) {
	const max = Math.max(...props.stats.map((item) => Number(item.value) || 0), 1)
	return `${((Number(value) || 0) / max) * 100}%`
}
</script>

<style scoped>
.chart-box {
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius-lg);
	padding: var(--spacing-5);
	box-shadow: var(--shadow-sm);
}

.chart-header h3 {
	margin: 0 0 var(--spacing-4);
}

.chart-bars {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-4);
}

.chart-bar-group {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-2);
}

.chart-bar-meta {
	display: flex;
	justify-content: space-between;
	align-items: center;
	font-size: var(--font-size-sm);
}

.chart-bar-track {
	width: 100%;
	height: 10px;
	border-radius: var(--radius-full);
	background: var(--color-bg-subtle);
	overflow: hidden;
}

.chart-bar-fill {
	height: 100%;
	border-radius: inherit;
	transition: width var(--transition-fast);
}

.empty-state {
	color: var(--color-text-muted);
	padding: var(--spacing-4) 0 0;
}
</style>
<!--
Display project/application statistics as a chart.

Use Chart.js or ApexCharts if implemented.

Possible chart:
- Projects grouped by status.
- Monthly project submissions.

Receive aggregated data from the dashboard API.

Do not calculate large datasets inside the browser.
-->