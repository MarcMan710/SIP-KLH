// History API Service
import api from './api'

export const historyService = {
  /**
   * Retrieve activity logs for a specific project.
   */
  async getProjectHistory(projectId, params = {}) {
    const res = await api.get(`/projects/${projectId}/history`, params)
    return res.data
  },

  /**
   * Retrieve overall assessment history.
   */
  async getAssessmentHistory(params = {}) {
    const res = await api.get('/assessment-history', params)
    return res.data
  },
}

export default historyService