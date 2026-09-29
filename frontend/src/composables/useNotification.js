// Centralized Notification / Toast System
import { ref } from 'vue'

const notifications = ref([])
let nextId = 1

export function useNotification() {
  function notify({ message, type = 'info', timeout = 4000 }) {
    const id = nextId++
    const notification = {
      id,
      message,
      type, // 'success' | 'error' | 'warning' | 'info'
    }

    notifications.value.push(notification)

    if (timeout > 0) {
      setTimeout(() => {
        dismiss(id)
      }, timeout)
    }

    return id
  }

  function success(message, timeout = 4000) {
    return notify({ message, type: 'success', timeout })
  }

  function error(message, timeout = 5000) {
    return notify({ message, type: 'error', timeout })
  }

  function warning(message, timeout = 4000) {
    return notify({ message, type: 'warning', timeout })
  }

  function info(message, timeout = 4000) {
    return notify({ message, type: 'info', timeout })
  }

  function dismiss(id) {
    const index = notifications.value.findIndex((n) => n.id === id)
    if (index !== -1) {
      notifications.value.splice(index, 1)
    }
  }

  return {
    notifications,
    notify,
    success,
    error,
    warning,
    info,
    dismiss,
  }
}

export default useNotification