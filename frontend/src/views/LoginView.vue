<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref('')

async function login() {
  try {
    const { data } = await api.post('/login', { email: email.value, password: password.value })
    localStorage.setItem('token', data.token)
    localStorage.setItem('user', JSON.stringify(data.user))
    router.push('/catalogo')
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Error al iniciar sesion'
  }
}
</script>

<template>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-icon">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--ml-blue)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
          <path d="M7 11V7a5 5 0 0110 0v4"/>
        </svg>
      </div>
      <h2 class="auth-title">Iniciar sesion</h2>

      <form @submit.prevent="login" class="auth-form">
        <div class="auth-field">
          <label for="email">Email</label>
          <input id="email" v-model="email" type="email" placeholder="tu@email.com" required />
        </div>
        <div class="auth-field">
          <label for="password">Contrasena</label>
          <input id="password" v-model="password" type="password" placeholder="********" required />
        </div>
        <p v-if="error" class="auth-error">{{ error }}</p>
        <button type="submit" class="auth-submit">Ingresar</button>
      </form>

      <p class="auth-footer">
        No tienes cuenta?
        <router-link to="/register">Crear cuenta</router-link>
      </p>
    </div>
  </div>
</template>

<style scoped>
.auth-page {
  display: flex;
  justify-content: center;
  padding-top: 40px;
}

.auth-card {
  width: 100%;
  max-width: 380px;
  background: var(--ml-white);
  border-radius: 8px;
  box-shadow: var(--ml-shadow);
  padding: 32px 24px;
  text-align: center;
}

.auth-icon {
  margin-bottom: 12px;
}

.auth-title {
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 24px;
}

.auth-form {
  text-align: left;
}

.auth-field {
  margin-bottom: 16px;
}

.auth-field label {
  display: block;
  font-size: 13px;
  font-weight: 500;
  color: var(--ml-text-light);
  margin-bottom: 4px;
}

.auth-field input {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid var(--ml-border);
  border-radius: 4px;
  font-size: 14px;
  outline: none;
  transition: border-color 0.2s;
}

.auth-field input:focus {
  border-color: var(--ml-blue);
}

.auth-error {
  color: #ff4d4d;
  font-size: 13px;
  margin-bottom: 12px;
  text-align: center;
}

.auth-submit {
  width: 100%;
  padding: 12px;
  background: var(--ml-blue);
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 15px;
  font-weight: 600;
  transition: background 0.2s;
}

.auth-submit:hover {
  background: var(--ml-blue-dark);
}

.auth-footer {
  margin-top: 20px;
  font-size: 14px;
  color: var(--ml-text-muted);
}

.auth-footer a {
  color: var(--ml-blue);
  font-weight: 500;
}
</style>
