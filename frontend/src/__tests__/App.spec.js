import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import { createRouter, createMemoryHistory } from 'vue-router'
import { createPinia, setActivePinia } from 'pinia'
import App from '../App.vue'

const routes = [
  { path: '/login', name: 'login', component: { template: '<div>Login</div>' } },
  { path: '/applicant/dashboard', name: 'applicant-dashboard', component: { template: '<div>Applicant</div>' } },
  { path: '/assessor/dashboard', name: 'assessor-dashboard', component: { template: '<div>Assessor</div>' } },
]

describe('App', () => {
  it('mounts the application shell', async () => {
    setActivePinia(createPinia())
    const router = createRouter({
      history: createMemoryHistory(),
      routes,
    })
    await router.push('/login')
    await router.isReady()

    const wrapper = mount(App, {
      global: {
        plugins: [router],
      },
    })

    expect(wrapper.exists()).toBe(true)
    expect(wrapper.text()).toContain('SIP-KLH')
  })
})
