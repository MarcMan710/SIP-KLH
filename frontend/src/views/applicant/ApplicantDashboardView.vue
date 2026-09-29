<template>
  <div class="page-shell">
    <div class="page-header">
      <div>
        <p class="eyebrow">Dashboard</p>
        <h1>Permohonan Saya</h1>
      </div>
      <router-link :to="{ name: 'applicant-project-create' }">
        <AppButton>+ Buat Permohonan</AppButton>
      </router-link>
    </div>

    <div v-if="loading" class="card-grid">Loading...</div>
    <div v-else class="card-grid">
      <div class="stat-card">
        <span>Total Permohonan</span>
        <strong>{{ stats.total || 0 }}</strong>
      </div>
      <div class="stat-card">
        <span>Draft</span>
        <strong>{{ stats.draft || 0 }}</strong>
      </div>
      <div class="stat-card">
        <span>Submitted</span>
        <strong>{{ stats.submitted || 0 }}</strong>
      </div>
      <div class="stat-card">
        <span>Under Review</span>
        <strong>{{ stats.under_review || 0 }}</strong>
      </div>
      <div class="stat-card">
        <span>Revision Required</span>
        <strong>{{ stats.revision_required || 0 }}</strong>
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
        <h3>Status Permohonan</h3>
        <div class="chart-placeholder">
          <div class="bar" style="height: 36%"></div>
          <div class="bar" style="height: 64%"></div>
          <div class="bar" style="height: 52%"></div>
          <div class="bar" style="height: 22%"></div>
          <div class="bar" style="height: 18%"></div>
        </div>
      </div>

      <div class="panel">
        <h3>Permohonan Terbaru</h3>
        <ul class="mini-list">
          <li v-for="project in recentProjects" :key="project.id">
            <div>
              <strong>{{ project.name || 'Permohonan' }}</strong>
              <small>{{ project.status || 'DRAFT' }}</small>
            </div>
            <router-link :to="{ name: 'applicant-project-detail', params: { id: project.id } }">Lihat</router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import AppButton from '@/components/common/AppButton.vue'
import { useDashboardStore } from '@/stores/dashboardStore'

const dashboardStore = useDashboardStore()
const stats = ref({})
const recentProjects = ref([
  { id: 1, name: 'Permohonan AMDAL', status: 'SUBMITTED' },
  { id: 2, name: 'Permohonan UKL-UPL', status: 'UNDER_REVIEW' },
])

const loading = computed(() => dashboardStore.loading)

onMounted(async () => {
  try {
    const data = await dashboardStore.fetchPemohonStats()
    stats.value = data || {}
  } catch {
    stats.value = {
      total: 0,
      draft: 0,
      submitted: 0,
      under_review: 0,
      revision_required: 0,
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
  gap: var(--spacing-4);
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
  background: linear-gradient(180deg, var(--color-primary) 0%, #14b8a6 100%);
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
