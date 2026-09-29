// Authentication API Service
import api from './api'

export const authService = {
  /**
   * Register a new Pemohon user.
   */
  async register(payload) {
    const res = await api.post('/auth/register', payload)
    return res.data
  },

  /**
   * Login with email and password.
   */
  async login(credentials) {
    const res = await api.post('/auth/login', credentials)
    return res.data
  },

  /**
   * Logout authenticated user.
   */
  async logout() {
    return api.post('/auth/logout')
  },

  /**
   * Fetch current authenticated user.
   */
  async getMe() {
    const res = await api.get('/auth/me')
    return res.data
  },
}

export default authService