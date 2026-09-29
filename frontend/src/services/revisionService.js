// Revision API Service
import api from './api'

export const revisionService = {
  /**
   * Retrieve revision requests for a project.
   */
  async getRevisions(projectId, params = {}) {
    const res = await api.get(`/projects/${projectId}/revisions`, params)
    return res.data
  },

  /**
   * Request revision (Penilai only).
   */
  async requestRevision(projectId, notes) {
    const res = await api.post(`/projects/${projectId}/revision`, { notes })
    return res.data
  },

  /**
   * Resubmit revised application (Pemohon only).
   */
  async resubmitProject(projectId) {
    const res = await api.post(`/projects/${projectId}/resubmit`)
    return res.data
  },
}

export default revisionService