import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCarritoStore = defineStore('carrito', () => {
  const items = ref<Array<{ producto_id: number; nombre: string; precio: number; cantidad: number; stock: number; imagen?: string }>>([])

  const total = computed(() =>
    items.value.reduce((sum, item) => sum + item.precio * item.cantidad, 0)
  )

  const cantidadItems = computed(() =>
    items.value.reduce((sum, item) => sum + item.cantidad, 0)
  )

  function agregar(producto: { id: number; nombre: string; precio: number; stock: number; imagen?: string }) {
    const existente = items.value.find(i => i.producto_id === producto.id)
    if (existente) {
      if (existente.cantidad < producto.stock) existente.cantidad++
    } else {
      items.value.push({
        producto_id: producto.id,
        nombre: producto.nombre,
        precio: producto.precio,
        cantidad: 1,
        stock: producto.stock,
        imagen: producto.imagen,
      })
    }
  }

  function quitar(productoId: number) {
    const idx = items.value.findIndex(i => i.producto_id === productoId)
    if (idx !== -1) {
      if (items.value[idx].cantidad > 1) {
        items.value[idx].cantidad--
      } else {
        items.value.splice(idx, 1)
      }
    }
  }

  function limpiar() {
    items.value = []
  }

  return { items, total, cantidadItems, agregar, quitar, limpiar }
})
