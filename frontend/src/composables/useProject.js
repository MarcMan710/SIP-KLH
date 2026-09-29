// Provide reusable project-related UI logic.
import { storeToRefs } from 'pinia'
import { useProjectStore } from '@/stores/projectStore'
import { useNotification } from './useNotification'

export function useProject() {
  const projectStore = useProjectStore()
  const notification = useNotification()
  const { projects, currentProject, pagination, filters, loading, error } = storeToRefs(projectStore)

  async function loadProjects(page = 1, customFilters = {}) {
    try {
      return await projectStore.fetchProjects(page, customFilters)
    } catch (err) {
      notification.error(err.message || 'Gagal memuat daftar permohonan.')
      throw err
    }
  }

  async function loadProject(id) {
    try {
      return await projectStore.fetchProject(id)
    } catch (err) {
      notification.error(err.message || 'Gagal memuat detail permohonan.')
      throw err
    }
  }

  async function saveProject(payload, id = null) {
    try {
      if (id) {
        const res = await projectStore.updateProject(id, payload)
        notification.success('Draft permohonan berhasil diperbarui.')
        return res
      } else {
        const res = await projectStore.createProject(payload)
        notification.success('Draft permohonan baru berhasil dibuat.')
        return res
      }
    } catch (err) {
      notification.error(err.message || 'Gagal menyimpan permohonan.')
      throw err
    }
  }

  async function removeProject(id) {
    try {
      await projectStore.deleteProject(id)
      notification.success('Draft permohonan berhasil dihapus.')
    } catch (err) {
      notification.error(err.message || 'Gagal menghapus draft.')
      throw err
    }
  }

  async function submitProjectForReview(id) {
    try {
      const res = await projectStore.submitProject(id)
      notification.success('Permohonan berhasil diajukan untuk proses penilaian.')
      return res
    } catch (err) {
      notification.error(err.message || 'Gagal mengajukan permohonan.')
      throw err
    }
  }

  return {
    projects,
    currentProject,
    pagination,
    filters,
    loading,
    error,
    loadProjects,
    loadProject,
    saveProject,
    removeProject,
    submitProjectForReview,
    setFilter: projectStore.setFilter,
    resetFilters: projectStore.resetFilters,
  }
}

export default useProject