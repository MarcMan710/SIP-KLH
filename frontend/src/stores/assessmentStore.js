// Maintain assessor workflow state.
import { defineStore } from 'pinia'
import { ref } from 'vue'
import assessmentService from '@/services/assessmentService'

export const useAssessmentStore = defineStore('assessment', () => {
  const applications = ref([])
  const currentApplication = ref(null)
  const pagination = ref({
    current_page: 1,
    per_page: 10,
    total: 0,
    last_page: 1,
  })
  const filters = ref({
    search: '',
    status: '',
  })
  const loading = ref(false)
  const error = ref(null)

  async function fetchApplications(page = 1, customFilters = {}) {
    loading.value = true
    error.value = null
    try {
      const params = {
        page,
        per_page: pagination.value.per_page,
        ...filters.value,
        ...customFilters,
      }
      const res = await assessmentService.getAssessments(params)
      if (res?.data) {
        applications.value = res.data
        pagination.value = {
          current_page: res.meta?.current_page || res.current_page || page,
          per_page: res.meta?.per_page || res.per_page || pagination.value.per_page,
          total: res.meta?.total || res.total || 0,
          last_page: res.meta?.last_page || res.last_page || 1,
        }
      } else if (Array.isArray(res)) {
        applications.value = res
        pagination.value.total = res.length
      }
      return res
    } catch (err) {
      error.value = err.message || 'Gagal memuat daftar permohonan penilaian.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchApplication(id) {
    loading.value = true
    error.value = null
    try {
      const data = await assessmentService.getProjectAssessment(id)
      currentApplication.value = data
      return data
    } catch (err) {
      error.value = err.message || 'Gagal memuat rincian permohonan penilaian.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function submitReview(projectId, notes) {
    loading.value = true
    error.value = null
    try {
      const res = await assessmentService.review(projectId, notes)
      return res
    } catch (err) {
      error.value = err.message || 'Gagal menyimpan catatan penilaian.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function approve(projectId) {
    loading.value = true
    error.value = null
    try {
      const res = await assessmentService.approve(projectId)
      return res
    } catch (err) {
      error.value = err.message || 'Gagal menyetujui permohonan.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function reject(projectId) {
    loading.value = true
    error.value = null
    try {
      const res = await assessmentService.reject(projectId)
      return res
    } catch (err) {
      error.value = err.message || 'Gagal menolak permohonan.'
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    applications,
    currentApplication,
    pagination,
    filters,
    loading,
    error,
    fetchApplications,
    fetchApplication,
    submitReview,
    approve,
    reject,
  }
})

export default useAssessmentStore