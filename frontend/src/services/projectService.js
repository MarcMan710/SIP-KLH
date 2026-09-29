// Project API Service
import api from './api'

export const projectService = {
  /**
   * Retrieve paginated project list with filters.
   */
  async getProjects(params = {}) {
    const res = await api.get('/projects', params)
    return res.data
  },

  /**
   * Retrieve project details by ID.
   */
  async getProject(id) {
    const res = await api.get(`/projects/${id}`)
    return res.data
  },

  /**
   * Create a new draft project.
   */
  async createProject(payload) {
    const res = await api.post('/projects', payload)
    return res.data
  },

  /**
   * Update an editable project.
   */
  async updateProject(id, payload) {
    const res = await api.put(`/projects/${id}`, payload)
    return res.data
  },

  /**
   * Delete a draft project.
   */
  async deleteProject(id) {
    const res = await api.delete(`/projects/${id}`)
    return res
  },

  /**
   * Submit draft project for review.
   */
  async submitProject(id) {
    const res = await api.post(`/projects/${id}/submit`)
    return res.data
  },
}

export default projectService