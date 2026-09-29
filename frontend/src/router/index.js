import { createRouter, createWebHistory } from 'vue-router'
import { requireAuth, redirectIfAuthenticated } from './guards'
import { ROLES } from '@/utils/constants'

import LoginView from '@/views/auth/LoginView.vue'
import RegisterView from '@/views/auth/RegisterView.vue'

import ApplicantDashboardView from '@/views/applicant/ApplicantDashboardView.vue'
import ProjectListView from '@/views/applicant/ProjectListView.vue'
import CreateProjectView from '@/views/applicant/CreateProjectView.vue'
import ProjectDetailView from '@/views/applicant/ProjectDetailView.vue'
import EditProjectView from '@/views/applicant/EditProjectView.vue'
import RevisionView from '@/views/applicant/RevisionView.vue'
import HistoryView from '@/views/applicant/HistoryView.vue'

import AssessorDashboardView from '@/views/assessor/AssessorDashboardView.vue'
import ApplicationListView from '@/views/assessor/ApplicationListView.vue'
import ApplicationDetailView from '@/views/assessor/ApplicationDetailView.vue'
import AssessmentHistoryView from '@/views/assessor/AssessmentHistoryView.vue'

const routes = [
  {
    path: '/',
    redirect: '/login',
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
    meta: { layout: 'auth', guest: true },
    beforeEnter: redirectIfAuthenticated,
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterView,
    meta: { layout: 'auth', guest: true },
    beforeEnter: redirectIfAuthenticated,
  },
  {
    path: '/applicant',
    meta: { layout: 'dashboard', requiresAuth: true, roles: [ROLES.PEMOHON] },
    beforeEnter: requireAuth,
    children: [
      {
        path: 'dashboard',
        name: 'applicant-dashboard',
        component: ApplicantDashboardView,
      },
      {
        path: 'projects',
        name: 'applicant-projects',
        component: ProjectListView,
      },
      {
        path: 'projects/create',
        name: 'applicant-project-create',
        component: CreateProjectView,
      },
      {
        path: 'projects/:id',
        name: 'applicant-project-detail',
        component: ProjectDetailView,
      },
      {
        path: 'projects/:id/edit',
        name: 'applicant-project-edit',
        component: EditProjectView,
      },
      {
        path: 'projects/:id/revision',
        name: 'applicant-revision',
        component: RevisionView,
      },
      {
        path: 'projects/:id/history',
        name: 'applicant-history',
        component: HistoryView,
      },
    ],
  },
  {
    path: '/assessor',
    meta: { layout: 'dashboard', requiresAuth: true, roles: [ROLES.PENILAI] },
    beforeEnter: requireAuth,
    children: [
      {
        path: 'dashboard',
        name: 'assessor-dashboard',
        component: AssessorDashboardView,
      },
      {
        path: 'applications',
        name: 'assessor-applications',
        component: ApplicationListView,
      },
      {
        path: 'applications/:id',
        name: 'assessor-application-detail',
        component: ApplicationDetailView,
      },
      {
        path: 'history',
        name: 'assessor-history',
        component: AssessmentHistoryView,
      },
    ],
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/login',
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth) {
    return requireAuth(to, from, next)
  }

  if (to.meta.guest) {
    return redirectIfAuthenticated(to, from, next)
  }

  return next()
})

export default router
