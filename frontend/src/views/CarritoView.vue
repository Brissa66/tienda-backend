<script setup lang="ts">
import { useCarritoStore } from '../stores/carrito'

const carrito = useCarritoStore()

function formatPrecio(n: number) {
  return n.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
</script>

<template>
  <div class="carrito">
    <h2 class="carrito-title">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"/>
        <circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
      </svg>
      Carrito ({{ carrito.cantidadItems }} items)
    </h2>

    <div v-if="carrito.items.length === 0" class="carrito-empty">
      <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"/>
        <circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
      </svg>
      <p>Tu carrito esta vacio</p>
      <router-link to="/catalogo" class="carrito-seguir">Ver productos</router-link>
    </div>

    <div v-else class="carrito-content">
      <div class="carrito-items">
        <div v-for="item in carrito.items" :key="item.producto_id" class="carrito-item">
          <div class="carrito-item-img">
            <img v-if="item.imagen" :src="item.imagen" :alt="item.nombre" class="carrito-item-img-src" />
            <svg v-else width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <polyline points="21 15 16 10 5 21"/>
            </svg>
          </div>
          <div class="carrito-item-info">
            <span class="carrito-item-nombre">{{ item.nombre }}</span>
            <span class="carrito-item-precio">$ {{ formatPrecio(item.precio) }}</span>
          </div>
          <div class="carrito-item-cant">
            <button class="cant-btn" @click="carrito.quitar(item.producto_id)">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"/>
              </svg>
            </button>
            <span class="cant-valor">{{ item.cantidad }}</span>
            <button class="cant-btn" @click="carrito.agregar({ id: item.producto_id, nombre: item.nombre, precio: item.precio, stock: item.stock, imagen: item.imagen })" :disabled="item.cantidad >= item.stock">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
              </svg>
            </button>
          </div>
          <span class="carrito-item-subtotal">$ {{ formatPrecio(item.precio * item.cantidad) }}</span>
        </div>
      </div>

      <div class="carrito-resumen">
        <div class="resumen-box">
          <h3 class="resumen-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
              <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
            </svg>
            Resumen
          </h3>
          <div class="resumen-row">
            <span>Productos ({{ carrito.cantidadItems }})</span>
            <span>$ {{ formatPrecio(carrito.total) }}</span>
          </div>
          <div class="resumen-row resumen-total">
            <span>Total</span>
            <span>$ {{ formatPrecio(carrito.total) }}</span>
          </div>
          <router-link to="/checkout" class="resumen-btn">Finalizar compra</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.carrito-title {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 16px;
}

.carrito-empty {
  text-align: center;
  padding: 60px 20px;
  color: var(--ml-text-muted);
}

.carrito-empty svg {
  margin-bottom: 16px;
}

.carrito-empty p {
  font-size: 16px;
  margin-bottom: 16px;
}

.carrito-seguir {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 24px;
  background: var(--ml-blue);
  color: #fff;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
}

.carrito-content {
  display: grid;
  grid-template-columns: 1fr 320px;
  gap: 20px;
  align-items: start;
}

.carrito-items {
  background: var(--ml-white);
  border-radius: var(--ml-radius);
  box-shadow: var(--ml-shadow);
}

.carrito-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  border-bottom: 1px solid var(--ml-border);
}

.carrito-item:last-child {
  border-bottom: none;
}

.carrito-item-img {
  width: 72px;
  height: 72px;
  background: #fafafa;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  overflow: hidden;
}

.carrito-item-img-src {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.carrito-item-info {
  flex: 1;
  min-width: 0;
}

.carrito-item-nombre {
  display: block;
  font-size: 15px;
  font-weight: 500;
  color: var(--ml-text);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.carrito-item-precio {
  display: block;
  font-size: 13px;
  color: var(--ml-text-light);
  margin-top: 2px;
}

.carrito-item-cant {
  display: flex;
  align-items: center;
  gap: 8px;
}

.cant-btn {
  width: 28px;
  height: 28px;
  border: 1px solid var(--ml-border);
  border-radius: 4px;
  background: var(--ml-white);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--ml-text);
  transition: border-color 0.2s;
}

.cant-btn:hover:not(:disabled) {
  border-color: var(--ml-blue);
}

.cant-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.cant-valor {
  font-size: 14px;
  font-weight: 500;
  min-width: 20px;
  text-align: center;
}

.carrito-item-subtotal {
  font-size: 16px;
  font-weight: 600;
  color: var(--ml-text);
  min-width: 80px;
  text-align: right;
}

.carrito-resumen {
  position: sticky;
  top: 80px;
}

.resumen-box {
  background: var(--ml-white);
  border-radius: var(--ml-radius);
  box-shadow: var(--ml-shadow);
  padding: 20px;
}

.resumen-title {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 16px;
  padding-bottom: 12px;
  border-bottom: 1px solid var(--ml-border);
}

.resumen-row {
  display: flex;
  justify-content: space-between;
  font-size: 14px;
  color: var(--ml-text-light);
  margin-bottom: 8px;
}

.resumen-total {
  font-size: 18px;
  font-weight: 700;
  color: var(--ml-text);
  padding-top: 12px;
  margin-top: 12px;
  border-top: 1px solid var(--ml-border);
  margin-bottom: 0;
}

.resumen-btn {
  display: block;
  width: 100%;
  text-align: center;
  padding: 12px;
  margin-top: 16px;
  background: var(--ml-blue);
  color: #fff;
  border-radius: 4px;
  font-size: 15px;
  font-weight: 600;
  transition: background 0.2s;
}

.resumen-btn:hover {
  background: var(--ml-blue-dark);
}

@media (max-width: 768px) {
  .carrito-content {
    grid-template-columns: 1fr;
  }
}
</style>
