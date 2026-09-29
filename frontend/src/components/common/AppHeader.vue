<template>
  <header class="app-header">
    <div class="logo" @click="goHome">
      <span class="logo-text">SIP‑KLH</span>
    </div>
    <div class="spacer"></div>
    <div class="user-info">
      <img v-if="user?.avatar" :src="user.avatar" alt="Avatar" class="avatar" />
      <span class="user-name" v-if="user">{{ user.name }}</span>
      <button class="logout-btn" @click="handleLogout">
        <i class="fas fa-sign-out-alt"></i> Logout
      </button>
    </div>
    <button class="hamburger" @click="$emit('toggle-sidebar')">
      <i class="fas fa-bars"></i>
    </button>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

const user = computed(() => authStore.user)

function handleLogout() {
  authStore.logout().then(() => {
    router.replace({ name: 'login' })
  })
}

function goHome() {
  if (authStore.isPemohon) {
    router.push({ name: 'applicant-dashboard' })
  } else if (authStore.isPenilai) {
    router.push({ name: 'assessor-dashboard' })
  } else {
    router.push({ name: 'login' })
  }
}
</script>

<style scoped>
.app-header {
  display: flex;
  align-items: center;
  padding: var(--spacing-2) var(--spacing-4);
  background: var(--color-header-bg, rgba(255, 255, 255, 0.8));
  backdrop-filter: blur(10px);
  box-shadow: 0 1px 4px var(--color-shadow, rgba(0,0,0,0.1));
  position: sticky;
  top: 0;
  z-index: 10;
}
.logo {
  font-weight: 700;
  font-size: 1.25rem;
  color: var(--color-primary);
  cursor: pointer;
}
.spacer { flex: 1; }
.user-info {
  display: flex;
  align-items: center;
  gap: var(--spacing-2);
}
.avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  object-fit: cover;
}
.logout-btn {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  background: transparent;
  border: none;
  color: var(--color-text);
  cursor: pointer;
  font-weight: 500;
}
.logout-btn:hover { color: var(--color-primary); }
.hamburger { display: none; background: transparent; border: none; font-size: 1.5rem; margin-left: var(--spacing-2); }
@media (max-width: 768px) {
  .hamburger { display: block; }
  .user-info { display: none; }
}
</style>
