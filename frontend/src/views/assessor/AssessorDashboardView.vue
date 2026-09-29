<template>
  <div class="page-shell">
    <div class="page-header">
      <div>
        <p class="eyebrow">Dashboard</p>
        <h1>Penilaian Dokumen</h1>
      </div>
    </div>

    <div v-if="loading" class="card-grid">Loading...</div>
    <div v-else class="card-grid">
      <div class="stat-card">
        <span>Total Aplikasi</span>
        <strong>{{ stats.total || 0 }}</strong>
      </div>
      <div class="stat-card">
        <span>Pending Review</span>
        <strong>{{ stats.pending_review || 0 }}</strong>
      </div>
      <div class="stat-card">
        <span>Revision Requests</span>
        <strong>{{ stats.revision_requests || 0 }}</strong>
      </div>
      <div class="stat-card">
        <span>Approved</span>
        <strong>{{ stats.approved || 0 }}</strong>
      </div>
      <div class="stat-card">
        <span>Rejected</span>
        <strong>{{ stats.rejected || 0 }}</strong>
      </div>
    </div>

    <div class="content-grid">
      <div class="panel">
        <h3>Distribusi Status</h3>
        <div class="chart-placeholder">
          <div class="bar" style="height: 45%"></div>
          <div class="bar" style="height: 68%"></div>
          <div class="bar" style="height: 32%"></div>
          <div class="bar" style="height: 22%"></div>
        </div>
      </div>

      <div class="panel">
        <h3>Permohonan Terbaru</h3>
        <ul class="mini-list">
          <li v-for="item in recentApplications" :key="item.id">
            <div>
              <strong>{{ item.project_name || 'Permohonan' }}</strong>
              <small>{{ item.status || 'SUBMITTED' }}</small>
            </div>
            <router-link :to="{ name: 'assessor-application-detail', params: { id: item.id } }">Lihat</router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useDashboardStore } from '@/stores/dashboardStore'

const dashboardStore = useDashboardStore()
const stats = ref({})
const recentApplications = ref([
  { id: 1, project_name: 'Permohonan AMDAL', status: 'UNDER_REVIEW' },
  { id: 2, project_name: 'Permohonan UKL-UPL', status: 'REVISION_REQUIRED' },
])

const loading = computed(() => dashboardStore.loading)

onMounted(async () => {
  try {
    const data = await dashboardStore.fetchPenilaiStats()
    stats.value = data || {}
  } catch {
    stats.value = {
      total: 0,
      pending_review: 0,
      revision_requests: 0,
      approved: 0,
      rejected: 0,
    }
  }
})
</script>

<style scoped>
.page-shell {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-6);
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.eyebrow {
  color: var(--color-primary);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  font-size: 0.72rem;
  font-weight: 700;
}

.card-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: var(--spacing-4);
}

.stat-card,
.panel {
  background: var(--color-bg-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  padding: var(--spacing-5);
}

.stat-card {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-2);
}

.stat-card span {
  color: var(--color-text-muted);
  font-size: var(--font-size-sm);
}

.stat-card strong {
  font-size: clamp(1.5rem, 2vw, 2rem);
}

.content-grid {
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  gap: var(--spacing-5);
}

.chart-placeholder {
  height: 180px;
  display: flex;
  align-items: end;
  gap: var(--spacing-3);
  padding-top: var(--spacing-4);
}

.bar {
  flex: 1;
  background: linear-gradient(180deg, var(--color-secondary) 0%, #38bdf8 100%);
  border-radius: 8px 8px 0 0;
}

.mini-list {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: var(--spacing-3);
  margin-top: var(--spacing-4);
}

.mini-list li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: var(--spacing-2);
  border-bottom: 1px solid var(--color-border);
}

.mini-list small {
  display: block;
  color: var(--color-text-muted);
  margin-top: 3px;
}

@media (max-width: 768px) {
  .content-grid {
    grid-template-columns: 1fr;
  }
}
</style>
