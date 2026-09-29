// Provide frontend validation helpers.
//
// Validate:
// - Required fields.
// - Email format.
// - Password rules.
// - Document extension.
// - Document size.
//
// Frontend validation improves UX,
// but backend Laravel validation remains authoritative.

import { MAX_FILE_SIZE, ALLOWED_EXTENSIONS } from './constants'

/**
 * Validate that value is not empty or whitespace-only.
 */
export function validateRequired(value, fieldName = 'Bidang ini') {
  if (value === undefined || value === null) {
    return `${fieldName} wajib diisi.`
  }
  if (typeof value === 'string' && value.trim().length === 0) {
    return `${fieldName} wajib diisi.`
  }
  return null
}

/**
 * Validate email format.
 */
export function validateEmail(email) {
  if (!email || !email.trim()) {
    return 'Alamat email wajib diisi.'
  }
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!emailRegex.test(email.trim())) {
    return 'Format alamat email tidak valid.'
  }
  return null
}

/**
 * Validate password requirements.
 */
export function validatePassword(password, minLength = 8) {
  if (!password) {
    return 'Kata sandi wajib diisi.'
  }
  if (password.length < minLength) {
    return `Kata sandi minimal ${minLength} karakter.`
  }
  return null
}

/**
 * Validate confirmation password match.
 */
export function validatePasswordConfirm(password, passwordConfirm) {
  if (!passwordConfirm) {
    return 'Konfirmasi kata sandi wajib diisi.'
  }
  if (password !== passwordConfirm) {
    return 'Konfirmasi kata sandi tidak cocok.'
  }
  return null
}

/**
 * Validate document file extension and size.
 */
export function validateDocumentFile(file) {
  if (!file) {
    return 'Berkas dokumen wajib dipilih.'
  }

  // File size check (max 10MB)
  if (file.size > MAX_FILE_SIZE) {
    return `Ukuran berkas (${(file.size / (1024 * 1024)).toFixed(1)} MB) melebihi batas maksimum 10 MB.`
  }

  // File extension check
  const ext = file.name.split('.').pop().toLowerCase()
  if (!ALLOWED_EXTENSIONS.includes(ext)) {
    return `Format berkas .${ext} tidak diizinkan. Format yang didukung: ${ALLOWED_EXTENSIONS.join(', ').toUpperCase()}.`
  }

  return null
}