<template>
  <div class="auth-form">
    <h2>Masuk</h2>
    <p class="subtitle">Silakan masuk ke akun Anda</p>

    <AppAlert v-if="loginError" type="error" :message="loginError" />

    <form @submit.prevent="submitLogin">
      <AppInput
        v-model="form.email"
        label="Email"
        type="email"
        placeholder="nama@email.com"
        autocomplete="email"
        :error="errors.email"
      />

      <AppInput
        v-model="form.password"
        label="Password"
        type="password"
        placeholder="Masukkan password"
        autocomplete="current-password"
        :error="errors.password"
      />

      <AppButton type="submit" size="lg" :loading="authStore.loading" class="submit-btn">
        Masuk
      </AppButton>
    </form>

    <p class="auth-link">
      Belum punya akun?
      <router-link :to="{ name: 'register' }">Daftar</router-link>
    </p>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import AppInput from '@/components/common/AppInput.vue'
import AppButton from '@/components/common/AppButton.vue'
import AppAlert from '@/components/common/AppAlert.vue'
import { useAuthStore } from '@/stores/authStore'
import { ROLES } from '@/utils/constants'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: '',
})

const errors = reactive({ email: '', password: '' })
const loginError = ref('')

function clearErrors() {
  errors.email = ''
  errors.password = ''
  loginError.value = ''
}

async function submitLogin() {
  clearErrors()

  if (!form.email) errors.email = 'Email wajib diisi.'
  if (!form.password) errors.password = 'Password wajib diisi.'

  if (errors.email || errors.password) return

  try {
    const data = await authStore.login({
      email: form.email,
      password: form.password,
    })

    const role = data?.user?.role || authStore.userRole
    if (role === ROLES.PENILAI) {
      router.push({ name: 'assessor-dashboard' })
    } else {
      router.push({ name: 'applicant-dashboard' })
    }
  } catch (err) {
    loginError.value = err?.message || 'Login gagal. Silakan cek email dan password.'
  }
}
</script>

<style scoped>
.auth-form {
  width: min(420px, 100%);
}

.auth-form h2 {
  font-size: var(--font-size-2xl);
  margin-bottom: var(--spacing-2);
}

.subtitle {
  margin-bottom: var(--spacing-5);
  color: var(--color-text-muted);
}

.submit-btn {
  width: 100%;
  margin-top: var(--spacing-2);
}

.auth-link {
  text-align: center;
  margin-top: var(--spacing-5);
  color: var(--color-text-muted);
}
</style>
