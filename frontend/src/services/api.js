// Configure the central HTTP client used by the frontend.
//
// Responsibilities:
// - Define the Laravel API base URL.
// - Configure default HTTP headers.
// - Attach authentication credentials/token when required.
// - Handle common API errors.
// - Handle unauthorized responses.
// - Provide a consistent API communication layer.
//
// All other service files should use this client
// instead of creating separate HTTP configurations.

const BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api/v1'

/**
 * Standard HTTP error object wrapper.
 */
export class ApiError extends Error {
  constructor(message, status = 500, errors = null, data = null) {
    super(message)
    this.name = 'ApiError'
    this.status = status
    this.errors = errors
    this.data = data
  }
}

/**
 * Centralized request function.
 */
export async function request(endpoint, options = {}) {
  const url = endpoint.startsWith('http') ? endpoint : `${BASE_URL}${endpoint.startsWith('/') ? '' : '/'}${endpoint}`

  const headers = new Headers(options.headers || {})

  // Attach token if present in localStorage
  const token = localStorage.getItem('token')
  if (token && !headers.has('Authorization')) {
    headers.set('Authorization', `Bearer ${token}`)
  }

  // Set default Accept header
  if (!headers.has('Accept')) {
    headers.set('Accept', 'application/json')
  }

  // If body is plain object and not FormData, stringify and set JSON Content-Type
  let body = options.body
  if (body && !(body instanceof FormData) && typeof body === 'object') {
    if (!headers.has('Content-Type')) {
      headers.set('Content-Type', 'application/json')
    }
    body = JSON.stringify(body)
  }

  try {
    const response = await fetch(url, {
      ...options,
      headers,
      body,
    })

    // Handle 204 No Content
    if (response.status === 204) {
      return null
    }

    let responseData = null
    const contentType = response.headers.get('content-type')
    if (contentType && contentType.includes('application/json')) {
      responseData = await response.json()
    } else {
      responseData = await response.text()
    }

    // Handle 401 Unauthorized (Expired or invalid token)
    if (response.status === 401) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      // Dispatch custom event so authStore / router can handle it
      window.dispatchEvent(new CustomEvent('auth:unauthorized'))
      throw new ApiError('Sesi anda telah berakhir. Silakan login kembali.', 401, null, responseData)
    }

    // Handle non-OK status codes
    if (!response.ok) {
      const message =
        responseData?.message ||
        responseData?.error ||
        `Permintaan gagal dengan status ${response.status}`

      const validationErrors = responseData?.errors || null

      throw new ApiError(message, response.status, validationErrors, responseData)
    }

    return responseData
  } catch (err) {
    if (err instanceof ApiError) {
      throw err
    }
    // Network errors or fetch failures
    throw new ApiError(
      err.message || 'Gagal terhubung ke server backend.',
      0,
      null,
      null
    )
  }
}

export const api = {
  get: (endpoint, params = {}, options = {}) => {
    let url = endpoint
    const query = new URLSearchParams()
    Object.keys(params).forEach((key) => {
      if (params[key] !== undefined && params[key] !== null && params[key] !== '') {
        query.append(key, params[key])
      }
    })
    const queryString = query.toString()
    if (queryString) {
      url += (url.includes('?') ? '&' : '?') + queryString
    }
    return request(url, { ...options, method: 'GET' })
  },

  post: (endpoint, body, options = {}) => {
    return request(endpoint, { ...options, method: 'POST', body })
  },

  put: (endpoint, body, options = {}) => {
    return request(endpoint, { ...options, method: 'PUT', body })
  },

  delete: (endpoint, options = {}) => {
    return request(endpoint, { ...options, method: 'DELETE' })
  },
}

export default api