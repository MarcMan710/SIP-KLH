// Store frontend constants.
//
// Examples:
// - Application statuses.
// - User roles.
// - Pagination defaults.
// - Supported document types.
// - Maximum frontend file-size limits.
//
// Keep repeated values centralized.

export const ROLES = {
  PEMOHON: 'PEMOHON',
  PENILAI: 'PENILAI',
}

export const ROLE_LABELS = {
  [ROLES.PEMOHON]: 'Pemohon Dokumen',
  [ROLES.PENILAI]: 'Penilai Dokumen',
}

export const PROJECT_STATUS = {
  DRAFT: 'DRAFT',
  SUBMITTED: 'SUBMITTED',
  UNDER_REVIEW: 'UNDER_REVIEW',
  REVISION_REQUIRED: 'REVISION_REQUIRED',
  RESUBMITTED: 'RESUBMITTED',
  APPROVED: 'APPROVED',
  REJECTED: 'REJECTED',
}

export const STATUS_LABELS = {
  [PROJECT_STATUS.DRAFT]: 'Draft',
  [PROJECT_STATUS.SUBMITTED]: 'Submitted',
  [PROJECT_STATUS.UNDER_REVIEW]: 'Under Review',
  [PROJECT_STATUS.REVISION_REQUIRED]: 'Revision Required',
  [PROJECT_STATUS.RESUBMITTED]: 'Resubmitted',
  [PROJECT_STATUS.APPROVED]: 'Approved',
  [PROJECT_STATUS.REJECTED]: 'Rejected',
}

export const STATUS_COLORS = {
  [PROJECT_STATUS.DRAFT]: {
    bg: 'var(--color-status-draft-bg)',
    text: 'var(--color-status-draft-text)',
    border: 'var(--color-status-draft-border)',
  },
  [PROJECT_STATUS.SUBMITTED]: {
    bg: 'var(--color-status-submitted-bg)',
    text: 'var(--color-status-submitted-text)',
    border: 'var(--color-status-submitted-border)',
  },
  [PROJECT_STATUS.UNDER_REVIEW]: {
    bg: 'var(--color-status-review-bg)',
    text: 'var(--color-status-review-text)',
    border: 'var(--color-status-review-border)',
  },
  [PROJECT_STATUS.REVISION_REQUIRED]: {
    bg: 'var(--color-status-revision-bg)',
    text: 'var(--color-status-revision-text)',
    border: 'var(--color-status-revision-border)',
  },
  [PROJECT_STATUS.RESUBMITTED]: {
    bg: 'var(--color-status-resubmitted-bg)',
    text: 'var(--color-status-resubmitted-text)',
    border: 'var(--color-status-resubmitted-border)',
  },
  [PROJECT_STATUS.APPROVED]: {
    bg: 'var(--color-status-approved-bg)',
    text: 'var(--color-status-approved-text)',
    border: 'var(--color-status-approved-border)',
  },
  [PROJECT_STATUS.REJECTED]: {
    bg: 'var(--color-status-rejected-bg)',
    text: 'var(--color-status-rejected-text)',
    border: 'var(--color-status-rejected-border)',
  },
}

export const ASSESSMENT_ACTION = {
  APPROVE: 'APPROVE',
  REJECT: 'REJECT',
  REVISION: 'REVISION',
}

export const ASSESSMENT_ACTION_LABELS = {
  [ASSESSMENT_ACTION.APPROVE]: 'Approved',
  [ASSESSMENT_ACTION.REJECT]: 'Rejected',
  [ASSESSMENT_ACTION.REVISION]: 'Revision Requested',
}

export const DOCUMENT_TYPES = [
  { value: 'AMDAL', label: 'Analisis Mengenai Dampak Lingkungan (AMDAL)' },
  { value: 'UKL_UPL', label: 'UKL - UPL (Upaya Pengelolaan & Pemantauan Lingkungan)' },
  { value: 'SPPL', label: 'Surat Pernyataan Kesanggupan Pengelolaan Lingkungan (SPPL)' },
  { value: 'IZIN_LINGKUNGAN', label: 'Izin Lingkungan Hidup' },
  { value: 'SURAT_PERMOHONAN', label: 'Surat Permohonan Resmi' },
  { value: 'LAMPIRAN_TEKNIS', label: 'Lampiran Teknis / Peta Lokasi' },
  { value: 'DOKUMEN_LAINNYA', label: 'Dokumen Pendukung Lainnya' },
]

export const MAX_FILE_SIZE = 10 * 1024 * 1024 // 10MB in bytes
export const MAX_FILE_SIZE_MB = 10

export const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png']

export const PAGINATION_DEFAULTS = {
  PER_PAGE: 10,
  PAGE: 1,
}