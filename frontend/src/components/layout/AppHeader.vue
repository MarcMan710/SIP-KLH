<template>
	<header class="app-header-layout">
		<div class="brand">
			<span class="brand-mark">SIP-KLH</span>
		</div>

		<div class="header-actions">
			<div v-if="user" class="user-box">
				<span class="avatar">{{ user.name?.charAt(0) || 'U' }}</span>
				<span>{{ user.name }}</span>
			</div>
			<button class="logout-btn" @click="logout">Keluar</button>
		</div>
	</header>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const user = computed(() => authStore.user)

function logout() {
	authStore.logout().then(() => router.push({ name: 'login' }))
}
</script>

<style scoped>
.app-header-layout {
	display: flex;
	justify-content: space-between;
	align-items: center;
	background: var(--color-bg-surface);
	border-bottom: 1px solid var(--color-border);
	padding: var(--spacing-4) var(--spacing-5);
}

.brand {
	font-size: var(--font-size-lg);
	font-weight: 700;
	color: var(--color-primary);
}

.header-actions {
	display: flex;
	align-items: center;
	gap: var(--spacing-3);
}

.user-box {
	display: flex;
	align-items: center;
	gap: var(--spacing-2);
	color: var(--color-text-main);
}

.avatar {
	width: 2rem;
	height: 2rem;
	border-radius: 999px;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	background: var(--color-primary-light);
	color: var(--color-primary);
	font-weight: 700;
}

.logout-btn {
	border: 1px solid var(--color-border);
	background: transparent;
	color: var(--color-text-main);
	border-radius: var(--radius-md);
	padding: var(--spacing-2) var(--spacing-3);
	cursor: pointer;
}
</style>