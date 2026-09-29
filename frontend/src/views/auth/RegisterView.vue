<template>
  <div class="auth-form">
    <h2>Daftar Akun</h2>
    <p class="subtitle">Buat akun Pemohon Dokumen</p>

    <AppAlert v-if="registerError" type="error" :message="registerError" />

    <form @submit.prevent="submitRegister">
      <AppInput v-model="form.name" label="Nama Lengkap" placeholder="Nama lengkap" :error="errors.name" />
      <AppInput v-model="form.email" label="Email" type="email" placeholder="nama@email.com" :error="errors.email" />
      <AppInput v-model="form.password" label="Password" type="password" placeholder="Minimal 8 karakter" :error="errors.password" />
      <AppInput v-model="form.password_confirmation" label="Konfirmasi Password" type="password" placeholder="Ulangi password" :error="errors.password_confirmation" />

      <AppButton type="submit" size="lg" :loading="authStore.loading" class="submit-btn">
        Daftar
      </AppButton>
    </form>

    <p class="auth-link">
      Sudah punya akun?
      <router-link :to="{ name: 'login' }">Masuk</router-link>
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

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const errors = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})
const registerError = ref('')

function clearErrors() {
  Object.keys(errors).forEach((key) => {
    errors[key] = ''
  })
  registerError.value = ''
}

async function submitRegister() {
  clearErrors()

  if (!form.name) errors.name = 'Nama wajib diisi.'
  if (!form.email) errors.email = 'Email wajib diisi.'
  if (!form.password) errors.password = 'Password wajib diisi.'
  if (!form.password_confirmation) errors.password_confirmation = 'Konfirmasi password wajib diisi.'
  if (form.password && form.password_confirmation && form.password !== form.password_confirmation) {
    errors.password_confirmation = 'Konfirmasi password tidak cocok.'
  }

  if (Object.values(errors).some(Boolean)) return

  try {
    await authStore.register({
      name: form.name,
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation,
    })
    router.push({ name: 'login' })
  } catch (err) {
    registerError.value = err?.message || 'Registrasi gagal.'
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
