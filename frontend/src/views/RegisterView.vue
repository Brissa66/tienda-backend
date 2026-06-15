<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const router = useRouter()
const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const error = ref('')

async function register() {
  try {
    const { data } = await api.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    })
    localStorage.setItem('token', data.token)
    localStorage.setItem('user', JSON.stringify(data.user))
    router.push('/catalogo')
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Error al registrarse'
  }
}
</script>

<template>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-icon">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--ml-blue)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
          <circle cx="8.5" cy="7" r="4"/>
          <line x1="20" y1="8" x2="20" y2="14"/>
          <line x1="23" y1="11" x2="17" y2="11"/>
        </svg>
      </div>
      <h2 class="auth-title">Crear cuenta</h2>

      <form @submit.prevent="register" class="auth-form">
        <div class="auth-field">
          <label for="name">Nombre</label>
          <input id="name" v-model="name" type="text" placeholder="Tu nombre" required />
        </div>
        <div class="auth-field">
          <label for="email">Email</label>
          <input id="email" v-model="email" type="email" placeholder="tu@email.com" required />
        </div>
        <div class="auth-field">
          <label for="password">Contrasena</label>
          <input id="password" v-model="password" type="password" placeholder="Min. 8 caracteres" required />
        </div>
        <div class="auth-field">
          <label for="passwordConfirm">Confirmar contrasena</label>
          <input id="passwordConfirm" v-model="passwordConfirmation" type="password" placeholder="Repite la contrasena" required />
        </div>
        <p v-if="error" class="auth-error">{{ error }}</p>
        <button type="submit" class="auth-submit">Crear cuenta</button>
      </form>

      <p class="auth-footer">
        Ya tienes cuenta?
        <router-link to="/login">Iniciar sesion</router-link>
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
