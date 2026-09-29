<template>
  <aside class="app-sidebar" :class="{ open: isOpen }">
    <nav class="nav-links">
      <router-link
        v-for="item in navigation"
        :key="item.name"
        :to="item.to"
        class="nav-item"
        active-class="active"
        @click="closeOnMobile"
      >
        <i :class="item.icon"></i>
        <span>{{ item.label }}</span>
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { ROLES } from '@/utils/constants'

const props = defineProps({
  open: { type: Boolean, default: true },
})

const emit = defineEmits(['update:open'])

const isOpen = ref(props.open)
watch(() => props.open, (val) => (isOpen.value = val))

const authStore = useAuthStore()
const router = useRouter()

const navigation = computed(() => {
  if (authStore.isPemohon) {
    return [
      { name: 'Dashboard', label: 'Dashboard', to: { name: 'applicant-dashboard' }, icon: 'fas fa-home' },
      { name: 'Projects', label: 'My Projects', to: { name: 'applicant-projects' }, icon: 'fas fa-folder' },
      { name: 'Revision', label: 'Revision', to: { name: 'applicant-revision' }, icon: 'fas fa-edit' },
      { name: 'History', label: 'History', to: { name: 'applicant-history' }, icon: 'fas fa-history' },
      { name: 'Profile', label: 'Profile', to: { name: 'profile' }, icon: 'fas fa-user' },
    ]
  }
  if (authStore.isPenilai) {
    return [
      { name: 'Dashboard', label: 'Dashboard', to: { name: 'assessor-dashboard' }, icon: 'fas fa-home' },
      { name: 'Applications', label: 'Applications', to: { name: 'assessor-applications' }, icon: 'fas fa-clipboard-list' },
      { name: 'AssessmentHistory', label: 'Assessment History', to: { name: 'assessor-history' }, icon: 'fas fa-history' },
      { name: 'Profile', label: 'Profile', to: { name: 'profile' }, icon: 'fas fa-user' },
    ]
  }
  return []
})

function closeOnMobile() {
  if (window.innerWidth <= 768) {
    emit('update:open', false)
  }
}
</script>

<style scoped>
.app-sidebar {
  width: 250px;
  background: var(--color-sidebar-bg, rgba(255,255,255,0.9));
  backdrop-filter: blur(8px);
  height: 100vh;
  position: sticky;
  top: 0;
  overflow-y: auto;
  transition: transform 0.3s ease;
}
.app-sidebar:not(.open) {
  transform: translateX(-100%);
}
.nav-links {
  display: flex;
  flex-direction: column;
  padding: var(--spacing-4);
}
.nav-item {
  display: flex;
  align-items: center;
  gap: var(--spacing-2);
  padding: var(--spacing-2) var(--spacing-3);
  color: var(--color-text);
  text-decoration: none;
  border-radius: var(--border-radius);
  margin-bottom: var(--spacing-1);
}
.nav-item:hover,
.nav-item.active {
  background: var(--color-primary-light);
  color: var(--color-primary-dark);
}
</style>