// Dashboard API Service
import api from './api'

export const dashboardService = {
  /**
   * Retrieve Pemohon (applicant) dashboard statistics.
   */
  async getPemohonStats() {
    const res = await api.get('/dashboard/pemohon')
    return res.data
  },

  /**
   * Retrieve Penilai (assessor) dashboard statistics.
   */
  async getPenilaiStats() {
    const res = await api.get('/dashboard/penilai')
    return res.data
  },
}

export default dashboardService