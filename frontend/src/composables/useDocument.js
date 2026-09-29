// Provide reusable document-management logic.
import { ref } from 'vue'
import documentService from '@/services/documentService'
import { validateDocumentFile } from '@/utils/validators'
import { useNotification } from './useNotification'

export function useDocument(projectId) {
  const documents = ref([])
  const loading = ref(false)
  const uploading = ref(false)
  const uploadProgress = ref(0)
  const error = ref(null)
  const notification = useNotification()

  async function loadDocuments() {
    if (!projectId) return
    loading.value = true
    error.value = null
    try {
      const data = await documentService.getDocuments(projectId)
      documents.value = data || []
      return data
    } catch (err) {
      error.value = err.message || 'Gagal memuat dokumen.'
      notification.error(error.value)
    } finally {
      loading.value = false
    }
  }

  async function uploadFile(file, documentType = 'DOKUMEN_LAINNYA') {
    const validationError = validateDocumentFile(file)
    if (validationError) {
      notification.error(validationError)
      throw new Error(validationError)
    }

    uploading.value = true
    uploadProgress.value = 0
    error.value = null

    try {
      const formData = new FormData()
      formData.append('file', file)
      formData.append('document_type', documentType)

      const res = await documentService.uploadDocument(projectId, formData)
      notification.success(`Dokumen "${file.name}" berhasil diunggah.`)
      // Refresh documents
      await loadDocuments()
      return res
    } catch (err) {
      error.value = err.message || 'Gagal mengunggah dokumen.'
      notification.error(error.value)
      throw err
    } finally {
      uploading.value = false
      uploadProgress.value = 0
    }
  }

  async function removeDocument(documentId) {
    loading.value = true
    try {
      await documentService.deleteDocument(documentId)
      documents.value = documents.value.filter((d) => d.id !== documentId)
      notification.success('Dokumen berhasil dihapus.')
    } catch (err) {
      notification.error(err.message || 'Gagal menghapus dokumen.')
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    documents,
    loading,
    uploading,
    uploadProgress,
    error,
    loadDocuments,
    uploadFile,
    removeDocument,
  }
}

export default useDocument