# E-commerce Headless API

Este repositorio contiene el Backend de una plataforma de comercio electrónico construida con arquitectura **Headless**. Actúa como una API RESTful que sirve datos al cliente y gestiona la lógica de negocio crítica, separada de la interfaz de usuario.

Incluye un panel de administración robusto para la gestión del inventario y pedidos.

## 🛠️ Stack Tecnológico

- **Framework:** Laravel 12 (PHP 8.3)
- **Admin Panel:** FilamentPHP v4
- **Base de Datos:** MySQL
- **Autenticación:** Laravel Sanctum (API Tokens) & Laravel Session (Admin Panel)
- **Arquitectura:** MVC, Service Layer, API Resources

## ✅ Funcionalidades Implementadas

### Gestión de Catálogo
- **Productos y Categorías:** CRUD completo a través del panel de administración (Filament).
- **Gestión de Imágenes:** Almacenamiento local con enlace simbólico público.
- **API Pública:** Endpoints para listar productos con paginación y filtrado por nombre/categoría.

### Sistema de Pedidos (Core)
- **Transacciones Atómicas:** Uso de `DB::transaction` para asegurar la integridad de datos al crear pedidos.
- **Control de Stock:** Validación y decremento automático del inventario al confirmar una compra.
- **Estados:** Gestión de estados de pedido (Nuevo, Procesando, Enviado) mediante Enums tipados.
- **Historial:** Endpoint para que el cliente consulte sus pedidos anteriores.

### Seguridad y Usuarios
- **Autenticación Híbrida:** Login independiente para Administradores (web) y Clientes (API).
- **Roles:** Sistema de roles (Admin/Customer) usando Enums para restringir acceso al panel.
- **Perfil:** Endpoints para actualización de datos personales y cambio seguro de contraseña.
- **Validación:** Uso de `FormRequests` para separar la lógica de validación de los controladores.

## 🚀 Instalación Local

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/jmiranda0/ecommerce-api-laravel.git
   ```

2. **Instalar dependencias:**
   ```bash
   composer install
   ```

3. **Configurar entorno:**
   ```bash
   cp .env.example .env
   # Configura tu base de datos en el archivo .env
   php artisan key:generate
   ```

4. **Preparar Base de Datos:**
   ```bash
   php artisan migrate
   php artisan storage:link
   ```

5. **Crear usuario administrador:**
   ```bash
   php artisan make:filament-user
   ```

6. **Ejecutar servidor:**
   ```bash
   php artisan serve
   ```

## 🗺️ Roadmap (Próximos Pasos)

- [ ] Sistema de Cupones de Descuento.
- [ ] Lista de Deseos (Wishlist).
- [ ] Tests Automatizados (Pest PHP).
- [ ] Widgets de Analítica en el Dashboard.
- [ ] Implementación de Pasarela de Pagos (Stripe SDK).

---
*Desarrollado por Jahzeel Miranda Pérez*
```
