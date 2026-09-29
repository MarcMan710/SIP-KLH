// Maintain dashboard statistics and chart data.
import { defineStore } from 'pinia'
import { ref } from 'vue'
import dashboardService from '@/services/dashboardService'

export const useDashboardStore = defineStore('dashboard', () => {
  const pemohonStats = ref(null)
  const penilaiStats = ref(null)
  const loading = ref(false)
  const error = ref(null)

  async function fetchPemohonStats() {
    loading.value = true
    error.value = null
    try {
      const data = await dashboardService.getPemohonStats()
      pemohonStats.value = data
      return data
    } catch (err) {
      error.value = err.message || 'Gagal memuat statistik pemohon.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchPenilaiStats() {
    loading.value = true
    error.value = null
    try {
      const data = await dashboardService.getPenilaiStats()
      penilaiStats.value = data
      return data
    } catch (err) {
      error.value = err.message || 'Gagal memuat statistik penilai.'
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    pemohonStats,
    penilaiStats,
    loading,
    error,
    fetchPemohonStats,
    fetchPenilaiStats,
  }
})

export default useDashboardStore