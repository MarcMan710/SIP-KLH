// Provide reusable authentication-related logic.
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { ROLES } from '@/utils/constants'

export function useAuth() {
  const router = useRouter()
  const authStore = useAuthStore()
  const { user, token, loading, error, isAuthenticated, isPemohon, isPenilai, userRole } = storeToRefs(authStore)

  async function handleLogin(credentials) {
    const data = await authStore.login(credentials)
    if (data.user.role === ROLES.PENILAI) {
      router.push('/assessor/dashboard')
    } else {
      router.push('/applicant/dashboard')
    }
    return data
  }

  async function handleRegister(payload) {
    const data = await authStore.register(payload)
    router.push('/applicant/dashboard')
    return data
  }

  async function handleLogout() {
    await authStore.logout()
    router.push('/login')
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    isPemohon,
    isPenilai,
    userRole,
    login: handleLogin,
    register: handleRegister,
    logout: handleLogout,
    fetchCurrentUser: authStore.fetchCurrentUser,
  }
}

export default useAuth