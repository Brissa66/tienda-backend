<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useCarritoStore } from '../stores/carrito'
import api from '../services/api'

const carrito = useCarritoStore()
const productos = ref<any[]>([])

onMounted(async () => {
  try {
    const { data } = await api.get('/productos')
    productos.value = data.data.data
  } catch (e) {
    console.error('Error cargando productos', e)
  }
})

function formatPrecio(n: number) {
  return n.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
</script>

<template>
  <div class="catalogo">
    <h2 class="catalogo-title">Productos</h2>
    <div v-if="productos.length === 0" class="catalogo-empty">
      Cargando productos...
    </div>
    <div v-else class="productos-grid">
      <div v-for="p in productos" :key="p.id" class="producto-card">
        <div class="producto-img">
          <img v-if="p.imagen" :src="p.imagen" :alt="p.nombre" class="producto-img-src" />
          <svg v-else width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <polyline points="21 15 16 10 5 21"/>
          </svg>
        </div>
        <div class="producto-info">
          <h3 class="producto-nombre">{{ p.nombre }}</h3>
          <p class="producto-desc">{{ p.descripcion }}</p>
          <p class="producto-precio">$ {{ formatPrecio(p.precio) }}</p>
          <div class="producto-footer">
            <span v-if="p.stock > 0" class="producto-stock disponible">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              En stock
            </span>
            <span v-else class="producto-stock agotado">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
              Agotado
            </span>
            <button
              class="producto-btn"
              :disabled="p.stock === 0"
              @click="carrito.agregar({ id: p.id, nombre: p.nombre, precio: p.precio, stock: p.stock, imagen: p.imagen })"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
              </svg>
              Comprar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.catalogo-title {
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 16px;
  color: var(--ml-text);
}

.catalogo-empty {
  text-align: center;
  padding: 60px;
  color: var(--ml-text-muted);
  font-size: 16px;
}

.productos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 16px;
}

.producto-card {
  background: var(--ml-white);
  border-radius: var(--ml-radius);
  box-shadow: var(--ml-shadow);
  overflow: hidden;
  transition: box-shadow 0.2s;
}

.producto-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.producto-img {
  height: 180px;
  background: #fafafa;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ccc;
  border-bottom: 1px solid var(--ml-border);
  overflow: hidden;
}

.producto-img-src {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.producto-info {
  padding: 14px;
}

.producto-nombre {
  font-size: 16px;
  font-weight: 600;
  color: var(--ml-text);
  margin-bottom: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.producto-desc {
  font-size: 13px;
  color: var(--ml-text-light);
  margin-bottom: 8px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.producto-precio {
  font-size: 24px;
  font-weight: 400;
  color: var(--ml-text);
  margin-bottom: 10px;
}

.producto-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.producto-stock {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 13px;
  font-weight: 500;
}

.producto-stock.disponible {
  color: var(--ml-green);
}

.producto-stock.agotado {
  color: #ff4d4d;
}

.producto-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  background: var(--ml-blue);
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
  transition: background 0.2s;
}

.producto-btn:hover:not(:disabled) {
  background: var(--ml-blue-dark);
}

.producto-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
