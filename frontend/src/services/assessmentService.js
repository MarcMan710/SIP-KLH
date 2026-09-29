// Assessment API Service (Penilai actions)
import api from './api'

export const assessmentService = {
  /**
   * Retrieve applications awaiting assessment.
   */
  async getAssessments(params = {}) {
    const res = await api.get('/assessments', params)
    return res.data
  },

  /**
   * Retrieve application assessment details.
   */
  async getProjectAssessment(projectId) {
    const res = await api.get(`/projects/${projectId}/assessment`)
    return res.data
  },

  /**
   * Submit assessment notes / review.
   */
  async review(projectId, notes) {
    const res = await api.post(`/projects/${projectId}/assessment`, { notes })
    return res.data
  },

  /**
   * Approve application.
   */
  async approve(projectId) {
    const res = await api.post(`/projects/${projectId}/approve`)
    return res.data
  },

  /**
   * Reject application.
   */
  async reject(projectId) {
    const res = await api.post(`/projects/${projectId}/reject`)
    return res.data
  },
}

export default assessmentService