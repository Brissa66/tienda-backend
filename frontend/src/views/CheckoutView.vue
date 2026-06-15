<script setup lang="ts">
import { ref } from 'vue'
import { useCarritoStore } from '../stores/carrito'
import api from '../services/api'
import PedidoEstado from '../components/PedidoEstado.vue'

const carrito = useCarritoStore()
const pedidoId = ref<number | null>(null)
const error = ref('')

async function finalizarCompra() {
  try {
    const { data } = await api.post('/pedidos', {
      items: carrito.items.map(i => ({
        producto_id: i.producto_id,
        cantidad: i.cantidad,
        precio: i.precio,
      })),
    })
    pedidoId.value = data.pedido_id
    carrito.limpiar()
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Error al crear pedido'
  }
}

function formatPrecio(n: number) {
  return n.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
</script>

<template>
  <div class="checkout">
    <h2 class="checkout-title">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
      Checkout
    </h2>

    <div v-if="pedidoId" class="checkout-done">
      <PedidoEstado :pedido-id="pedidoId" />
    </div>

    <div v-else class="checkout-content">
      <div class="checkout-resumen">
        <h3 class="checkout-resumen-title">Resumen del pedido</h3>
        <div v-if="carrito.items.length === 0" class="checkout-empty">
          <p>No hay productos en tu carrito</p>
          <router-link to="/catalogo" class="checkout-link">Ir al catalogo</router-link>
        </div>
        <div v-else>
          <div v-for="item in carrito.items" :key="item.producto_id" class="checkout-item">
            <span class="checkout-item-nombre">{{ item.nombre }}</span>
            <span class="checkout-item-cant">x{{ item.cantidad }}</span>
            <span class="checkout-item-precio">$ {{ formatPrecio(item.precio * item.cantidad) }}</span>
          </div>
          <div class="checkout-total">
            <span>Total</span>
            <span>$ {{ formatPrecio(carrito.total) }}</span>
          </div>
          <button class="checkout-btn" @click="finalizarCompra">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            Confirmar Compra
          </button>
          <p v-if="error" class="checkout-error">{{ error }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.checkout-title {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 20px;
}

.checkout-content {
  display: flex;
  justify-content: center;
}

.checkout-resumen {
  width: 100%;
  max-width: 520px;
  background: var(--ml-white);
  border-radius: var(--ml-radius);
  box-shadow: var(--ml-shadow);
  padding: 24px;
}

.checkout-resumen-title {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 16px;
  padding-bottom: 12px;
  border-bottom: 1px solid var(--ml-border);
}

.checkout-empty {
  text-align: center;
  padding: 30px 0;
  color: var(--ml-text-muted);
}

.checkout-link {
  display: inline-block;
  margin-top: 12px;
  color: var(--ml-blue);
  font-weight: 500;
}

.checkout-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 0;
  border-bottom: 1px solid var(--ml-border);
  font-size: 14px;
}

.checkout-item-nombre {
  flex: 1;
  color: var(--ml-text);
}

.checkout-item-cant {
  color: var(--ml-text-muted);
}

.checkout-item-precio {
  font-weight: 600;
  min-width: 80px;
  text-align: right;
}

.checkout-total {
  display: flex;
  justify-content: space-between;
  padding: 16px 0;
  font-size: 20px;
  font-weight: 700;
}

.checkout-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  padding: 14px;
  background: var(--ml-blue);
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 16px;
  font-weight: 600;
  transition: background 0.2s;
}

.checkout-btn:hover {
  background: var(--ml-blue-dark);
}

.checkout-error {
  color: #ff4d4d;
  font-size: 14px;
  margin-top: 12px;
  text-align: center;
}

.checkout-done {
  display: flex;
  justify-content: center;
}
</style>
