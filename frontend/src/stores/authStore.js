// Maintain global authentication state.
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import authService from '@/services/authService'
import { ROLES } from '@/utils/constants'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('token') || null)
  const user = ref(JSON.parse(localStorage.getItem('user') || 'null'))
  const loading = ref(false)
  const error = ref(null)

  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const isPemohon = computed(() => user.value?.role === ROLES.PEMOHON)
  const isPenilai = computed(() => user.value?.role === ROLES.PENILAI)
  const userRole = computed(() => user.value?.role || null)

  function setAuth(data) {
    if (data?.token) {
      token.value = data.token
      localStorage.setItem('token', data.token)
    }
    if (data?.user) {
      user.value = data.user
      localStorage.setItem('user', JSON.stringify(data.user))
    }
  }

  function clearAuth() {
    token.value = null
    user.value = null
    localStorage.removeItem('token')
    localStorage.removeItem('user')
  }

  async function login(credentials) {
    loading.value = true
    error.value = null
    try {
      const data = await authService.login(credentials)
      setAuth(data)
      return data
    } catch (err) {
      error.value = err.message || 'Login gagal.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function register(payload) {
    loading.value = true
    error.value = null
    try {
      const data = await authService.register(payload)
      setAuth(data)
      return data
    } catch (err) {
      error.value = err.message || 'Registrasi gagal.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    loading.value = true
    try {
      if (token.value) {
        await authService.logout().catch(() => {})
      }
    } finally {
      clearAuth()
      loading.value = false
    }
  }

  async function fetchCurrentUser() {
    if (!token.value) return null
    loading.value = true
    try {
      const data = await authService.getMe()
      if (data) {
        user.value = data
        localStorage.setItem('user', JSON.stringify(data))
      }
      return data
    } catch (err) {
      if (err.status === 401) {
        clearAuth()
      }
      return null
    } finally {
      loading.value = false
    }
  }

  // Listen for global unauthorized events to automatically reset auth state
  if (typeof window !== 'undefined') {
    window.addEventListener('auth:unauthorized', () => {
      clearAuth()
    })
  }

  return {
    token,
    user,
    loading,
    error,
    isAuthenticated,
    isPemohon,
    isPenilai,
    userRole,
    setAuth,
    clearAuth,
    login,
    register,
    logout,
    fetchCurrentUser,
  }
})

export default useAuthStore