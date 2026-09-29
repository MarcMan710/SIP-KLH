// Provide reusable pagination logic.
import { ref, computed } from 'vue'

export function usePagination(initialPerPage = 10) {
  const currentPage = ref(1)
  const perPage = ref(initialPerPage)
  const total = ref(0)
  const lastPage = ref(1)

  const hasNextPage = computed(() => currentPage.value < lastPage.value)
  const hasPrevPage = computed(() => currentPage.value > 1)

  const fromRecord = computed(() => {
    if (total.value === 0) return 0
    return (currentPage.value - 1) * perPage.value + 1
  })

  const toRecord = computed(() => {
    return Math.min(currentPage.value * perPage.value, total.value)
  })

  function setPaginationData(data) {
    if (!data) return
    currentPage.value = Number(data.current_page) || 1
    perPage.value = Number(data.per_page) || initialPerPage
    total.value = Number(data.total) || 0
    lastPage.value = Number(data.last_page) || 1
  }

  function goToPage(page) {
    if (page >= 1 && page <= lastPage.value) {
      currentPage.value = page
      return true
    }
    return false
  }

  function nextPage() {
    if (hasNextPage.value) {
      currentPage.value++
      return true
    }
    return false
  }

  function prevPage() {
    if (hasPrevPage.value) {
      currentPage.value--
      return true
    }
    return false
  }

  return {
    currentPage,
    perPage,
    total,
    lastPage,
    hasNextPage,
    hasPrevPage,
    fromRecord,
    toRecord,
    setPaginationData,
    goToPage,
    nextPage,
    prevPage,
  }
}

export default usePagination