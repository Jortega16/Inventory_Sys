<p align="center">
    <img src="public/images/inventary-logo.svg" width="320" alt="InventarySys">
</p>

# InventarySys

InventarySys es un sistema web de gestión de inventario multiempresa (SaaS). Cada empresa registrada obtiene un espacio de trabajo aislado, accesible mediante su propio subdominio y respaldado por una base de datos independiente.

Desde el panel administrativo, cada empresa puede gestionar productos, existencias, almacenes, proveedores, compras, clientes, ventas y miembros del equipo sin mezclar información con otros negocios.

## Funcionalidades

- Registro autoservicio de empresas y creación automática de su espacio de trabajo.
- Aislamiento por empresa de base de datos, caché, archivos y colas.
- Catálogo de productos con SKU, unidad, costo, precio de venta y punto de reorden.
- Gestión de almacenes, niveles de existencias, ajustes de inventario y transferencias.
- Gestión de proveedores y órdenes de compra con sus líneas de productos.
- Gestión de clientes y órdenes de venta.
- Administración de usuarios, roles y permisos por empresa.
- Roles iniciales: Administrador, Almacenista, Comprador, Vendedor y Solo lectura.
- Panel administrativo construido con Filament.

## Arquitectura y tecnologías

- PHP 8.3 o superior y Laravel 13.
- Filament 3 para el panel administrativo.
- PostgreSQL como base de datos recomendada.
- Redis para caché y servicios auxiliares en el entorno Docker.
- `stancl/tenancy` para la arquitectura multiempresa con una base de datos por tenant.
- `nwidart/laravel-modules` para separar los módulos Catalog, Warehouse, Purchasing y Sales.
- `spatie/laravel-permission` para roles y permisos.
- Tailwind CSS 4 y Vite 8 para los recursos del frontend.
- Laravel Sail y Docker Compose para el entorno de desarrollo.

## Instalación recomendada con Docker

### Requisitos

- Git.
- Docker Desktop con Docker Compose.

En Windows se recomienda ejecutar los comandos desde PowerShell o WSL 2. En Linux y macOS se puede usar la terminal habitual.

### 1. Obtener el proyecto

```bash
git clone <URL_DEL_REPOSITORIO> inventary_sys
cd inventary_sys
```

### 2. Instalar las dependencias de Composer

Si PHP y Composer están instalados localmente:

```bash
composer install
```

Si se desea usar solamente Docker, en Linux, macOS o WSL:

```bash
docker run --rm -v "$(pwd):/opt" -w /opt laravelsail/php85-composer:latest composer install --ignore-platform-reqs
```

En PowerShell:

```powershell
docker run --rm -v "${PWD}:/opt" -w /opt laravelsail/php85-composer:latest composer install --ignore-platform-reqs
```

### 3. Crear y configurar el archivo de entorno

Linux, macOS o WSL:

```bash
cp .env.example .env
```

PowerShell:

```powershell
Copy-Item .env.example .env
```

Configure estas variables en `.env`:

```dotenv
APP_NAME=InventarySys
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=secret

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
```

Si el puerto 80 está ocupado, agregue por ejemplo `APP_PORT=8000` y cambie `APP_URL` a `http://localhost:8000`.

### 4. Iniciar los contenedores

```bash
docker compose up -d --build
```

### 5. Preparar Laravel y la base de datos central

```bash
docker compose exec laravel.test php artisan key:generate
docker compose exec laravel.test php artisan migrate
```

Las bases de datos de cada empresa no se crean manualmente. Al registrar una empresa, el sistema crea su base de datos, ejecuta las migraciones de `database/migrations/tenant` y carga automáticamente los roles y permisos iniciales.

### 6. Compilar los recursos del frontend

```bash
docker compose exec laravel.test npm install
docker compose exec laravel.test npm run build
```

La aplicación estará disponible en [http://localhost](http://localhost), o en el puerto definido mediante `APP_PORT`.

Para detener el entorno:

```bash
docker compose down
```

## Instalación local sin Docker

Se necesita PHP 8.3 o superior con las extensiones habituales de Laravel, además de `intl` y `pdo_pgsql`; Composer, Node.js con npm y PostgreSQL. Redis es opcional si se configura como backend de caché, sesiones o colas.

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
php artisan migrate
php artisan serve
```

Antes de migrar, configure en `.env` una conexión PostgreSQL accesible desde el equipo local. En este caso, `DB_HOST` normalmente será `127.0.0.1` en lugar de `pgsql`.

## Primer uso

1. Abra la página principal y seleccione **Crear cuenta gratis**.
2. Introduzca el nombre de la empresa, el usuario propietario y su contraseña.
3. El sistema creará un subdominio con el formato `nombre-empresa.localhost`.
4. Inicie sesión en `http://nombre-empresa.localhost/admin/login`.

Los navegadores modernos resuelven automáticamente los subdominios de `.localhost`. Si se utiliza un dominio diferente en desarrollo o producción, debe configurarse el DNS comodín y actualizar los dominios centrales de la aplicación.

## Pruebas y calidad de código

Con Docker:

```bash
docker compose exec laravel.test php artisan test
docker compose exec laravel.test vendor/bin/pint
```

Sin Docker:

```bash
php artisan test
vendor/bin/pint
```

## Estructura principal

```text
app/                         Aplicación central, usuarios, tenants y panel Filament
Modules/Catalog/             Productos y catálogo
Modules/Warehouse/           Almacenes, existencias, ajustes y transferencias
Modules/Purchasing/          Proveedores y órdenes de compra
Modules/Sales/               Clientes y órdenes de venta
database/migrations/         Esquema de la base de datos central
database/migrations/tenant/  Esquema independiente de cada empresa
resources/views/             Landing, registro y acceso a espacios de trabajo
```
