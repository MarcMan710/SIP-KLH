// Provide reusable formatting functions.
//
// Format:
// - Dates.
// - File sizes.
// - Project numbers.
// - Status labels.
//
// Keep formatting logic out of individual components.

import { STATUS_LABELS, ROLE_LABELS, ASSESSMENT_ACTION_LABELS } from './constants'

/**
 * Format ISO date string into readable Indonesian/localized date.
 * Example: 24 Sep 2026
 */
export function formatDate(isoString) {
  if (!isoString) return '-'
  try {
    const date = new Date(isoString)
    if (isNaN(date.getTime())) return isoString
    return new Intl.DateTimeFormat('id-ID', {
      day: 'numeric',
      month: 'short',
      year: 'numeric',
    }).format(date)
  } catch {
    return isoString
  }
}

/**
 * Format ISO date string into readable date and time.
 * Example: 24 Sep 2026, 14:30
 */
export function formatDateTime(isoString) {
  if (!isoString) return '-'
  try {
    const date = new Date(isoString)
    if (isNaN(date.getTime())) return isoString
    return new Intl.DateTimeFormat('id-ID', {
      day: 'numeric',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    }).format(date)
  } catch {
    return isoString
  }
}

/**
 * Format bytes into human readable format (KB, MB, GB).
 */
export function formatFileSize(bytes) {
  if (bytes === undefined || bytes === null || isNaN(bytes)) return '0 B'
  if (bytes === 0) return '0 B'
  const k = 1024
  const sizes = ['B', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return `${parseFloat((bytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`
}

/**
 * Format status string into human readable title.
 */
export function formatStatus(status) {
  if (!status) return '-'
  return STATUS_LABELS[status] || status.replace(/_/g, ' ')
}

/**
 * Format role string into human readable title.
 */
export function formatRole(role) {
  if (!role) return '-'
  return ROLE_LABELS[role] || role
}

/**
 * Format assessment action into label.
 */
export function formatAssessmentAction(action) {
  if (!action) return '-'
  return ASSESSMENT_ACTION_LABELS[action] || action
}

/**
 * Format project number or return placeholder.
 */
export function formatProjectNumber(projectNumber) {
  return projectNumber || 'PRJ-UNASSIGNED'
}