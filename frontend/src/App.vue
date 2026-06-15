<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useCarritoStore } from './stores/carrito'

const router = useRouter()
const carrito = useCarritoStore()
const searchQuery = ref('')

const user = computed(() => {
  const u = localStorage.getItem('user')
  return u ? JSON.parse(u) : null
})

function logout() {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  router.push('/login')
}
</script>

<template>
  <header class="ml-header">
    <div class="ml-header-inner">
      <router-link to="/catalogo" class="ml-logo">
        <svg width="44" height="32" viewBox="0 0 44 32" fill="none">
          <rect width="44" height="32" rx="6" fill="#fff"/>
          <path d="M10 8h4l6 16h-4L10 8z" fill="#3483fa"/>
          <circle cx="24" cy="22" r="6" fill="#ffe600" stroke="#3483fa" stroke-width="1.5"/>
          <path d="M22 22h4M24 20v4" stroke="#3483fa" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <span class="ml-logo-text">Mi Tienda</span>
      </router-link>

      <div class="ml-search">
        <svg class="ml-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/>
          <path d="M21 21l-4.35-4.35"/>
        </svg>
        <input v-model="searchQuery" type="text" placeholder="Buscar productos..." class="ml-search-input" />
      </div>

      <div class="ml-header-right">
        <template v-if="user">
          <div class="ml-user-menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
            <span class="ml-user-name">{{ user.name }}</span>
          </div>
          <button class="ml-btn-logout" @click="logout">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
          </button>
        </template>
        <template v-else>
          <router-link to="/login" class="ml-btn-login">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
              <polyline points="10 17 15 12 10 7"/>
              <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            Ingresar
          </router-link>
        </template>

        <router-link to="/carrito" class="ml-cart-btn">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"/>
            <circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
          </svg>
          <span v-if="carrito.cantidadItems > 0" class="ml-cart-badge">{{ carrito.cantidadItems }}</span>
        </router-link>
      </div>
    </div>
  </header>

  <main class="ml-main">
    <router-view />
  </main>
</template>

<style scoped>
.ml-header {
  background: var(--ml-yellow);
  padding: 8px 0;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.ml-header-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 16px;
  display: flex;
  align-items: center;
  gap: 20px;
}

.ml-logo {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}

.ml-logo-text {
  font-size: 20px;
  font-weight: 700;
  color: var(--ml-text);
}

.ml-search {
  flex: 1;
  max-width: 600px;
  position: relative;
}

.ml-search-icon {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--ml-text-muted);
  pointer-events: none;
}

.ml-search-input {
  width: 100%;
  padding: 10px 16px 10px 40px;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  outline: none;
  box-shadow: 0 1px 2px rgba(0,0,0,0.08);
}

.ml-search-input::placeholder {
  color: var(--ml-text-muted);
}

.ml-header-right {
  display: flex;
  align-items: center;
  gap: 16px;
  flex-shrink: 0;
}

.ml-user-menu {
  display: flex;
  align-items: center;
  gap: 6px;
  color: var(--ml-text);
}

.ml-user-name {
  font-size: 14px;
  font-weight: 500;
}

.ml-btn-login {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
  color: var(--ml-blue);
  border: 1px solid var(--ml-blue);
  transition: background 0.2s;
}

.ml-btn-login:hover {
  background: rgba(52, 131, 250, 0.05);
}

.ml-btn-logout {
  background: none;
  border: none;
  padding: 6px;
  color: var(--ml-text);
  opacity: 0.6;
  transition: opacity 0.2s;
}

.ml-btn-logout:hover {
  opacity: 1;
}

.ml-cart-btn {
  position: relative;
  display: flex;
  align-items: center;
  padding: 6px;
  color: var(--ml-text);
}

.ml-cart-badge {
  position: absolute;
  top: 0;
  right: -2px;
  background: var(--ml-blue);
  color: #fff;
  font-size: 10px;
  font-weight: 700;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.ml-main {
  max-width: 1200px;
  margin: 20px auto;
  padding: 0 16px;
}
</style>
