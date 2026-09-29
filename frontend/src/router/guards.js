import { useAuthStore } from '@/stores/authStore'
import { ROLES } from '@/utils/constants'

export function requireAuth(to, from, next) {
  const authStore = useAuthStore()

  if (!authStore.isAuthenticated) {
    return next({ name: 'login', query: { redirect: to.fullPath } })
  }

  const allowedRoles = to.meta?.roles || []
  if (allowedRoles.length && !allowedRoles.includes(authStore.userRole)) {
    if (authStore.userRole === ROLES.PEMOHON) {
      return next({ name: 'applicant-dashboard' })
    }
    if (authStore.userRole === ROLES.PENILAI) {
      return next({ name: 'assessor-dashboard' })
    }
    return next({ name: 'login' })
  }

  return next()
}

export function redirectIfAuthenticated(to, from, next) {
  const authStore = useAuthStore()

  if (authStore.isAuthenticated) {
    if (authStore.isPenilai) {
      return next({ name: 'assessor-dashboard' })
    }
    return next({ name: 'applicant-dashboard' })
  }

  return next()
}

export default {
  requireAuth,
  redirectIfAuthenticated,
}
