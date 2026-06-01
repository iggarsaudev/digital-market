# 📦 DigitalMarket - E-Commerce de Productos Digitales

DigitalMarket es una plataforma Full-Stack de comercio electrónico diseñada para la venta y distribución automatizada de productos digitales (cursos, e-books, recursos). Construida con una arquitectura desacoplada (API RESTful + SPA), integra procesamiento de pagos reales y descargas seguras protegidas por autenticación.

🔗 **Demo en vivo:** [https://iggarsaudev-career-hub.vercel.app/](https://iggarsaudev-digital-market.vercel.app/)

## 🚀 Características Principales

* **Autenticación SPA Segura:** Sistema de registro y login utilizando Laravel Sanctum y gestión de estado reactivo con Pinia.
* **Procesamiento de Pagos:** Integración completa con **Stripe Checkout** para pagos con tarjeta, incluyendo webhooks/verificación de sesiones.
* **Descargas Protegidas:** Endpoints que validan la propiedad de las órdenes antes de servir archivos binarios, evitando el acceso público no autorizado.
* **Panel de Administración (CMS):** Interfaz para la creación dinámica de productos con subida de imágenes mediante `multipart/form-data`.
* **Carrito de Compras Reactivo:** Gestión del estado del carrito persistente en el cliente.
* **Diseño Moderno y Responsivo:** Interfaz construida íntegramente con Tailwind CSS.

## 💻 Stack Tecnológico

**Frontend:**
* Vue.js 3 (Composition API)
* Vite
* Tailwind CSS
* Pinia (State Management)
* Vue Router
* Axios

**Backend:**
* Laravel 11
* PHP 8.2+
* SQLite (Desarrollo) / PostgreSQL (Producción)
* Stripe PHP SDK

## 🛠️ Instalación y Configuración Local

El proyecto está dividido en dos partes. Necesitarás tener instalados PHP, Composer y Node.js.

### 1. Backend (API)
```bash
cd digital-market-api
composer install
cp .env.example .env
php artisan key:generate
```

Configura tus credenciales en el archivo .env (especialmente STRIPE_SECRET). Luego, prepara la base de datos.
```bash
php artisan migrate
php artisan storage:link
php artisan serve
```

Nota: El backend está desplegado en la capa gratuita de Render (discos efímeros). Las imágenes subidas manualmente desde el panel de control pueden desaparecer tras periodos de inactividad del servidor, pero los datos de la base de datos PostgreSQL permanecen intactos

### 2. Frontend (SPA)

Abre una nueva terminal
```bash
cd digital-market-front
npm install
```

Asegúrate de que la URL base de tu API en src/lib/axios.js apunte a tu servidor local de Laravel.
```bash
npm run dev
```

