// Document API Service
import api from './api'

export const documentService = {
  /**
   * Retrieve documents belonging to a project.
   */
  async getDocuments(projectId) {
    const res = await api.get(`/projects/${projectId}/documents`)
    return res.data
  },

  /**
   * Upload supporting document for a project.
   * Uses FormData multipart/form-data automatically.
   */
  async uploadDocument(projectId, formData) {
    const res = await api.post(`/projects/${projectId}/documents`, formData)
    return res.data
  },

  /**
   * Delete a document.
   */
  async deleteDocument(documentId) {
    const res = await api.delete(`/documents/${documentId}`)
    return res
  },
}

export default documentService