// Maintain project/application state.
import { defineStore } from 'pinia'
import { ref } from 'vue'
import projectService from '@/services/projectService'

export const useProjectStore = defineStore('project', () => {
  const projects = ref([])
  const currentProject = ref(null)
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

  async function fetchProjects(page = 1, customFilters = {}) {
    loading.value = true
    error.value = null
    try {
      const params = {
        page,
        per_page: pagination.value.per_page,
        ...filters.value,
        ...customFilters,
      }
      const res = await projectService.getProjects(params)
      // Standard Laravel Paginated Resource Response: { data: [...], meta: { ... } } or { data: [...], current_page: ... }
      if (res?.data) {
        projects.value = res.data
        pagination.value = {
          current_page: res.meta?.current_page || res.current_page || page,
          per_page: res.meta?.per_page || res.per_page || pagination.value.per_page,
          total: res.meta?.total || res.total || 0,
          last_page: res.meta?.last_page || res.last_page || 1,
        }
      } else if (Array.isArray(res)) {
        projects.value = res
        pagination.value.total = res.length
      }
      return res
    } catch (err) {
      error.value = err.message || 'Gagal memuat data permohonan.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchProject(id) {
    loading.value = true
    error.value = null
    try {
      const data = await projectService.getProject(id)
      currentProject.value = data
      return data
    } catch (err) {
      error.value = err.message || 'Gagal memuat rincian permohonan.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function createProject(payload) {
    loading.value = true
    error.value = null
    try {
      const data = await projectService.createProject(payload)
      return data
    } catch (err) {
      error.value = err.message || 'Gagal membuat draft permohonan.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updateProject(id, payload) {
    loading.value = true
    error.value = null
    try {
      const data = await projectService.updateProject(id, payload)
      currentProject.value = data
      return data
    } catch (err) {
      error.value = err.message || 'Gagal memperbarui permohonan.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function deleteProject(id) {
    loading.value = true
    error.value = null
    try {
      await projectService.deleteProject(id)
      projects.value = projects.value.filter((p) => p.id !== id)
    } catch (err) {
      error.value = err.message || 'Gagal menghapus draft permohonan.'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function submitProject(id) {
    loading.value = true
    error.value = null
    try {
      const data = await projectService.submitProject(id)
      currentProject.value = data
      return data
    } catch (err) {
      error.value = err.message || 'Gagal mengajukan permohonan.'
      throw err
    } finally {
      loading.value = false
    }
  }

  function setFilter(key, value) {
    filters.value[key] = value
  }

  function resetFilters() {
    filters.value = {
      search: '',
      status: '',
    }
  }

  return {
    projects,
    currentProject,
    pagination,
    filters,
    loading,
    error,
    fetchProjects,
    fetchProject,
    createProject,
    updateProject,
    deleteProject,
    submitProject,
    setFilter,
    resetFilters,
  }
})

export default useProjectStore