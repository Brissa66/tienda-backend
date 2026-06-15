<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import echo from '../plugins/echo'

const pedidosNuevos = ref<any[]>([])
const alertasStock = ref<any[]>([])

onMounted(() => {
  echo.private('admin-panel')

    .listen('NuevoPedidoRecibido', (e: any) => {
      pedidosNuevos.value.unshift(e)
      setTimeout(() => pedidosNuevos.value.pop(), 10000)
    })

    .listen('StockBajoAlerta', (e: any) => {
      alertasStock.value.unshift(e)
    })
})

onUnmounted(() => echo.leave('admin-panel'))
</script>

<template>
  <div class="notificaciones">
    <TransitionGroup name="toast">
      <div v-for="p in pedidosNuevos" :key="p.id" class="toast toast-pedido">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
          <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <div class="toast-content">
          <strong>Nuevo pedido #{{ p.id }}</strong>
          <span>{{ p.cliente }} - ${{ p.total }}</span>
        </div>
      </div>
    </TransitionGroup>

    <TransitionGroup name="toast">
      <div v-for="a in alertasStock" :key="a.producto_id" class="toast toast-stock">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
          <line x1="12" y1="9" x2="12" y2="13"/>
          <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
        <div class="toast-content">
          <strong>Stock bajo: {{ a.nombre }}</strong>
          <span>{{ a.stock_actual }} unidades</span>
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>

<style scoped>
.notificaciones {
  position: fixed;
  top: 70px;
  right: 16px;
  z-index: 1000;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.toast {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 14px;
  border-radius: 6px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  min-width: 280px;
  max-width: 360px;
}

.toast-pedido {
  background: #e8f5e9;
  color: #2e7d32;
  border-left: 4px solid var(--ml-green);
}

.toast-stock {
  background: #fff3e0;
  color: #e65100;
  border-left: 4px solid #ff9800;
}

.toast-content {
  display: flex;
  flex-direction: column;
  font-size: 13px;
}

.toast-content strong {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 2px;
}

.toast-enter-active,
.toast-leave-active {
  transition: all 0.4s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(80px);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(80px);
}
</style>
