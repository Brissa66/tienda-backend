<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import api from '../services/api'

const props = defineProps<{ pedidoId: number }>()
const emailListo = ref(false)
let intervalo: ReturnType<typeof setInterval> | null = null

onMounted(() => {
  intervalo = setInterval(async () => {
    try {
      const { data } = await api.get(`/pedidos/${props.pedidoId}`)
      emailListo.value = !!data.email_enviado_at
      if (emailListo.value && intervalo) clearInterval(intervalo)
    } catch (e) {
      console.error(e)
    }
  }, 3000)
})

onUnmounted(() => {
  if (intervalo) clearInterval(intervalo)
})
</script>

<template>
  <div class="pedido-estado-card">
    <div v-if="!emailListo" class="pedido-procesando">
      <div class="pedido-icon-box procesando-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/>
        </svg>
      </div>
      <h3 class="pedido-titulo">Procesando tu pedido</h3>
      <p class="pedido-desc">Estamos confirmando tu compra, esto toma solo unos segundos.</p>
      <div class="pedido-spinner">
        <div class="spinner-dot"></div>
        <div class="spinner-dot"></div>
        <div class="spinner-dot"></div>
      </div>
    </div>
    <div v-else class="pedido-exitoso">
      <div class="pedido-icon-box exitoso-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
          <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
      </div>
      <h3 class="pedido-titulo">Pedido confirmado</h3>
      <p class="pedido-desc">Recibiras un correo con los detalles de tu compra.</p>
    </div>
  </div>
</template>

<style scoped>
.pedido-estado-card {
  width: 100%;
  max-width: 420px;
  background: var(--ml-white);
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  padding: 40px 24px;
  text-align: center;
}

.pedido-icon-box {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 16px;
}

.procesando-icon {
  background: #fff3cd;
  color: #856404;
}

.exitoso-icon {
  background: #d4edda;
  color: #155724;
}

.pedido-titulo {
  font-size: 20px;
  font-weight: 600;
  color: var(--ml-text);
  margin-bottom: 8px;
}

.pedido-desc {
  font-size: 14px;
  color: var(--ml-text-light);
  line-height: 1.4;
}

.pedido-spinner {
  display: flex;
  justify-content: center;
  gap: 6px;
  margin-top: 20px;
}

.spinner-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--ml-yellow);
  animation: bounce 1.4s infinite ease-in-out both;
}

.spinner-dot:nth-child(1) { animation-delay: -0.32s; }
.spinner-dot:nth-child(2) { animation-delay: -0.16s; }
.spinner-dot:nth-child(3) { animation-delay: 0s; }

@keyframes bounce {
  0%, 80%, 100% { transform: scale(0); }
  40% { transform: scale(1); }
}
</style>
