<template>
	<nav class="breadcrumb" aria-label="Breadcrumb">
		<ol>
			<li v-for="(item, index) in items" :key="item.label || index">
				<span v-if="index > 0" class="separator">/</span>
				<router-link v-if="item.to && index < items.length - 1" :to="item.to">{{ item.label }}</router-link>
				<span v-else>{{ item.label }}</span>
			</li>
		</ol>
	</nav>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const items = computed(() => {
	const labels = route.meta?.breadcrumb || []
	if (labels.length) {
		return labels.map((label, index) => ({
			label,
			to: index < labels.length - 1 ? { name: route.name } : null,
		}))
	}

	return [{ label: 'Dashboard' }]
})
</script>

<style scoped>
.breadcrumb {
	margin-bottom: var(--spacing-4);
}

.breadcrumb ol {
	list-style: none;
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	margin: 0;
	padding: 0;
	color: var(--color-text-muted);
	font-size: var(--font-size-sm);
}

.breadcrumb li {
	display: inline-flex;
	align-items: center;
}

.breadcrumb a {
	color: var(--color-primary);
	text-decoration: none;
}

.separator {
	margin: 0 var(--spacing-2);
	color: var(--color-text-subtle);
}
</style>