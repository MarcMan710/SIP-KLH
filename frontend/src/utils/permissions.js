// Define frontend permission-checking helpers.
//
// Determine whether the current user can:
// - View project.
// - Edit project.
// - Submit project.
// - Upload documents.
// - Assess project.
// - Request revision.
// - Approve.
// - Reject.
//
// These checks control UI visibility only.
// Backend authorization remains mandatory.

import { ROLES, PROJECT_STATUS } from './constants'

/**
 * Check if the user is a Pemohon Dokumen (Applicant).
 */
export function isPemohon(user) {
  return user?.role === ROLES.PEMOHON
}

/**
 * Check if the user is a Penilai Dokumen (Assessor).
 */
export function isPenilai(user) {
  return user?.role === ROLES.PENILAI
}

/**
 * Check if project can be edited by applicant (only when DRAFT or REVISION_REQUIRED).
 */
export function canEditProject(user, project) {
  if (!user || !project) return false
  if (!isPemohon(user)) return false
  const isOwner = project.user_id ? project.user_id === user.id : true
  return isOwner && (project.status === PROJECT_STATUS.DRAFT || project.status === PROJECT_STATUS.REVISION_REQUIRED)
}

/**
 * Check if applicant can delete draft project.
 */
export function canDeleteProject(user, project) {
  if (!user || !project) return false
  if (!isPemohon(user)) return false
  const isOwner = project.user_id ? project.user_id === user.id : true
  return isOwner && project.status === PROJECT_STATUS.DRAFT
}

/**
 * Check if applicant can submit the draft project for review.
 */
export function canSubmitProject(user, project) {
  if (!user || !project) return false
  if (!isPemohon(user)) return false
  const isOwner = project.user_id ? project.user_id === user.id : true
  return isOwner && project.status === PROJECT_STATUS.DRAFT
}

/**
 * Check if applicant can resubmit project after completing revision.
 */
export function canResubmitProject(user, project) {
  if (!user || !project) return false
  if (!isPemohon(user)) return false
  const isOwner = project.user_id ? project.user_id === user.id : true
  return isOwner && project.status === PROJECT_STATUS.REVISION_REQUIRED
}

/**
 * Check if user can upload documents to this project.
 */
export function canUploadDocument(user, project) {
  if (!user || !project) return false
  if (!isPemohon(user)) return false
  return canEditProject(user, project)
}

/**
 * Check if user can delete a document.
 */
export function canDeleteDocument(user, project, document) {
  if (!user || !project) return false
  if (!isPemohon(user)) return false
  // Can only delete if project is editable and user is uploader or project owner
  return canEditProject(user, project)
}

/**
 * Check if assessor can assess/review the project.
 */
export function canAssessProject(user, project) {
  if (!user || !project) return false
  if (!isPenilai(user)) return false
  const assessableStatuses = [
    PROJECT_STATUS.SUBMITTED,
    PROJECT_STATUS.UNDER_REVIEW,
    PROJECT_STATUS.RESUBMITTED,
  ]
  return assessableStatuses.includes(project.status)
}