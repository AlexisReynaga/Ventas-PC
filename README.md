# Ventas-PC - Sistema de Gestión de Ventas

Sistema web completo para la gestión de ventas de productos y servicios de computación, desarrollado con Laravel 12 y Livewire. Este proyecto actúa como un frontend/proxy que se integra con una API externa para la gestión de productos, servicios y usuarios.

## 📋 Descripción

Ventas-PC es una aplicación web moderna que permite:
- **Catálogo de Productos**: Visualización y gestión de productos de computación con imágenes, precios, stock y categorías
- **Servicios**: Gestión y agendamiento de servicios técnicos
- **Carrito de Compras**: Sistema de carrito con sesiones que permite agregar productos y servicios
- **Gestión de Compras**: Generación de tickets y PDFs de compra
- **Panel de Administración**: Gestión completa de productos, servicios, usuarios y finanzas
- **Autenticación**: Sistema de registro y login integrado con API externa
- **Roles de Usuario**: Sistema de roles (admin/cliente) con permisos diferenciados

## 🚀 Características Principales

### Para Clientes
- Navegación pública del catálogo de productos y servicios
- Carrito de compras con gestión de cantidades
- Agendamiento de servicios técnicos
- Generación de tickets de compra en PDF
- Sistema de autenticación con registro

### Para Administradores
- Panel de administración de productos (CRUD completo)
- Panel de administración de servicios (CRUD completo)
- Gestión de usuarios y roles
- Vista de finanzas y compras realizadas
- Acceso a todos los tickets generados

## 🛠️ Stack Tecnológico

### Backend
- **Laravel 12** - Framework PHP
- **Laravel Livewire** - Componentes reactivos
- **Laravel Fortify** - Autenticación con soporte 2FA
- **Livewire Volt** - Componentes SFC (Single File Components)
- **DomPDF** - Generación de PDFs

### Frontend
- **Livewire Flux** - Componentes UI modernos
- **Tailwind CSS 4** - Framework CSS utility-first
- **Vite** - Build tool y bundler
- **Axios** - Cliente HTTP

### Base de Datos
- **MySQL** - Base de datos relacional
- Migraciones para `users`, `purchases`, `cache`, `jobs`

### Arquitectura
- **Patrón Proxy**: El sistema actúa como proxy hacia una API REST externa
- **Service Layer**: `ExternalApiClient` para comunicación con la API
- **MVC**: Modelo-Vista-Controlador estándar de Laravel
- **Middleware personalizado**: Control de acceso por roles (admin)

## 📁 Estructura del Proyecto

```
Ventas-PC/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ProductProxyController.php    # Gestión de productos (proxy API)
│   │   │   ├── ServiceProxyController.php    # Gestión de servicios (proxy API)
│   │   │   ├── CartController.php            # Carrito de compras
│   │   │   ├── AuthApiController.php         # Autenticación vía API
│   │   │   ├── UserManagementController.php  # Gestión de usuarios
│   │   │   ├── FinanceController.php         # Panel de finanzas
│   │   │   └── PurchaseAdminController.php   # Gestión de compras
│   │   └── Middleware/                       # Middlewares personalizados
│   ├── Models/
│   │   ├── User.php                          # Modelo de usuario con roles
│   │   └── Purchase.php                      # Modelo de compras
│   ├── Services/
│   │   └── ExternalApi/
│   │       └── ExternalApiClient.php         # Cliente para API externa
│   └── Livewire/                             # Componentes Livewire
├── config/
│   └── external_api.php                      # Configuración de API externa
├── database/
│   ├── migrations/                           # Migraciones de BD
│   └── seeders/                              # Seeders
├── resources/
│   ├── views/
│   │   ├── productos/                        # Vistas de productos
│   │   ├── servicios/                        # Vistas de servicios
│   │   ├── carrito/                          # Vistas del carrito
│   │   ├── admin/                            # Vistas de administración
│   │   ├── pdf/                              # Templates de PDFs
│   │   └── auth/                             # Vistas de autenticación
│   ├── css/                                  # Estilos CSS
│   └── js/                                   # JavaScript
└── routes/
    └── web.php                               # Rutas de la aplicación
```

## ⚙️ Instalación y Configuración

### Requisitos Previos
- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL >= 8.0
- API externa funcionando (configurada en .env)

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone <repository-url>
cd Ventas-PC
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de Node.js**
```bash
npm install
```

4. **Configurar archivo de entorno**
```bash
cp .env.example .env
```

5. **Editar el archivo `.env`** con tus configuraciones:
```env
APP_NAME="Ventas-PC"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8100

# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ventaspc
DB_USERNAME=root
DB_PASSWORD=

# Configuración de API Externa
API_BASE_URL=http://localhost:8000/api/v1
API_ADMIN_EMAIL=admin@example.com
API_ADMIN_PASSWORD=your-admin-password
```

6. **Generar clave de aplicación**
```bash
php artisan key:generate
```

7. **Ejecutar migraciones**
```bash
php artisan migrate
```

8. **Compilar assets**
```bash
npm run build
```

### Ejecutar el Proyecto

#### Modo Desarrollo (recomendado)
```bash
composer dev
```
Este comando ejecutará simultáneamente:
- Servidor Laravel (`php artisan serve`)
- Queue worker (`php artisan queue:listen`)
- Vite dev server (`npm run dev`)

#### Modo Manual
```bash
# Terminal 1: Servidor Laravel
php artisan serve --port=8100

# Terminal 2: Vite
npm run dev

# Terminal 3: Queue Worker (opcional)
php artisan queue:listen
```

La aplicación estará disponible en: `http://localhost:8100`

## 🔌 API Externa

El sistema se comunica con una API REST externa a través del servicio `ExternalApiClient`:

### Endpoints Utilizados
- `GET /products` - Listar productos
- `POST /products` - Crear producto
- `PUT /products/{id}` - Actualizar producto
- `DELETE /products/{id}` - Eliminar producto
- `GET /services` - Listar servicios
- `POST /auth/register` - Registro de usuario
- `POST /auth/login` - Login de usuario
- `GET /users` - Listar usuarios
- `PATCH /users/{id}/role` - Cambiar rol de usuario

## 🤝 Contribuir

1. Fork del proyecto
2. Crear rama para feature (`git checkout -b feature/AmazingFeature`)
3. Commit de cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir Pull Request


## 👥 Créditos

- Desarrollado con Laravel 12, Livewire.
- Erik Axel Loredo Lopez.
- Alexis Saul Lopez Reynaga.

---

**Nota**: Asegúrate de tener configurada correctamente la API externa antes de ejecutar el proyecto, ya que la mayoría de funcionalidades dependen de ella.
