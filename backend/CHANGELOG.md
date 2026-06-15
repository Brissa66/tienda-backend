# CHANGELOG

## [2.0.0] - 2025-06-01
### Added
- Búsqueda full-text en productos (GET /v2/productos?q=)
- Controlador V2 de productos que extiende V1

### Changed
- Paginación por defecto cambia de 15 a 20 items

## [1.0.0] - 2025-01-15
### Added
- CRUD completo de productos y categorías
- Autenticación con Sanctum
- Filtros y paginación básica
- Roles de usuario (admin/user)
- Carrito de compras con Pinia
- Sistema de pedidos con confirmación por email
- Notificaciones en tiempo real con Reverb
- Documentación Swagger/OpenAPI
