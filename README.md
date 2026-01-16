# Sistema de Gestión de Pacientes - Backend

API RESTful para la gestión de pacientes, desarrollada conforme a los requisitos de la prueba técnica de Sinergia 2025. Implementada con Laravel 12 y PHP 8.2, proporciona endpoints seguros con autenticación JWT para operaciones CRUD de pacientes y gestión de catálogos maestros.

## 🛠️ Tecnologías

### Backend
- PHP 8.2+
- Laravel 12
- JWT Auth (tymon/jwt-auth) para autenticación
- Eloquent ORM para manejo de datos
- API Resources para transformación de respuestas
- Validación de requests con Form Requests

### Base de Datos
- MySQL 8.0+
- Migraciones y seeders para gestión de esquema

### Infraestructura
- Docker con PHP-FPM
- Composer para gestión de dependencias
- PHPUnit para pruebas automatizadas

## 🏗️ Estructura del Proyecto

```
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Controladores de API
│   │   ├── Middleware/        # Middleware personalizado (JWT)
│   │   └── Requests/          # Validación de requests
│   ├── Models/                # Modelos Eloquent
│   └── Providers/             # Service Providers
│
├── config/                    # Archivos de configuración
│   ├── database.php           # Configuración de BD
│   ├── jwt.php                # Configuración JWT
│   └── cors.php               # Configuración CORS
│
├── database/
│   ├── migrations/            # Migraciones de base de datos
│   ├── seeders/               # Datos iniciales
│   └── factories/             # Factories para testing
│
├── routes/
│   ├── api.php                # Rutas de API
│   └── web.php                # Rutas web
│
├── tests/                     # Pruebas automatizadas
│   ├── Feature/               # Tests de integración
│   └── Unit/                  # Tests unitarios
│
├── .env.example               # Ejemplo de variables de entorno
├── Dockerfile                 # Configuración de contenedor
├── composer.json              # Dependencias PHP
└── README.md                  # Este archivo
```

## 🔧 Instalación y Configuración

### Requisitos Previos

- PHP 8.2 o superior
- Composer
- MySQL 8.0+
- Docker (para despliegue containerizado)

### 1. Clonar el repositorio

```bash
git clone https://github.com/Elian-Canas/backend_pacientes.git
cd backend_pacientes
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar variables de entorno

```bash
cp .env.example .env
```

Editar el archivo `.env` con tus configuraciones:

```env
APP_NAME="Sistema Gestión Pacientes"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de datos MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_pacientes
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# JWT Configuration
JWT_SECRET=tu_secreto_jwt_generado
JWT_TTL=60

# CORS (permitir frontend)
FRONTEND_URL=http://localhost:8080
```

### 4. Generar clave de aplicación y JWT

```bash
php artisan key:generate
php artisan jwt:secret
```

### 5. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

Esto creará las tablas y cargará los datos iniciales:
- Departamentos y municipios de Colombia
- Tipos de documento (CC, CE, TI, etc.)
- Géneros
- Usuario de prueba para autenticación

## 🐳 Despliegue con Docker

### Construir la imagen

```bash
docker build -t pacientes-backend .
```

### Ejecutar el contenedor

```bash
docker run -d \
  --name backend_pacientes \
  -p 9000:9000 \
  -v $(pwd):/var/www/html \
  -e DB_HOST=host.docker.internal \
  pacientes-backend
```

**Nota:** Se requiere un servidor web adicional (Nginx/Apache) como reverse proxy para servir la aplicación PHP-FPM.

## 🔌 Endpoints de la API

### Autenticación

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| POST | `/api/auth/login` | Iniciar sesión y obtener token JWT | No |
| POST | `/api/auth/logout` | Cerrar sesión | Sí |
| POST | `/api/auth/refresh` | Renovar token JWT | Sí |

**Ejemplo de login:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

### Pacientes (Requiere autenticación)

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | `/api/pacientes` | Listar pacientes (paginado) | Sí |
| GET | `/api/pacientes/{id}` | Obtener detalles de un paciente | Sí |
| POST | `/api/pacientes` | Crear nuevo paciente | Sí |
| PUT | `/api/pacientes/{id}` | Actualizar paciente existente | Sí |
| DELETE | `/api/pacientes/{id}` | Eliminar paciente | Sí |

**Headers requeridos para rutas protegidas:**
```
Authorization: Bearer {token_jwt}
Content-Type: application/json
Accept: application/json
```

### Catálogos Maestros (Públicos)

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| GET | `/api/departamentos` | Listar departamentos | No |
| GET | `/api/municipios?departamento_id={id}` | Listar municipios por departamento | No |
| GET | `/api/tipo-documentos` | Listar tipos de documento | No |
| GET | `/api/generos` | Listar géneros | No |

## 📊 Estructura de Datos

### Paciente
```json
{
  "id": 1,
  "tipo_documento_id": 1,
  "numero_documento": "1234567890",
  "primer_nombre": "Juan",
  "segundo_nombre": "Carlos",
  "primer_apellido": "Pérez",
  "segundo_apellido": "López",
  "fecha_nacimiento": "1990-05-15",
  "genero_id": 1,
  "departamento_id": 5,
  "municipio_id": 150,
  "zona_residencia": "Urbana",
  "direccion": "Calle 123 #45-67",
  "telefono": "3001234567",
  "email": "juan.perez@example.com",
  "created_at": "2026-01-15T10:30:00.000000Z",
  "updated_at": "2026-01-15T10:30:00.000000Z"
}
```

## 🧪 Pruebas

### Ejecutar todas las pruebas

```bash
php artisan test
```

### Ejecutar pruebas con cobertura

```bash
php artisan test --coverage
```

### Ejecutar pruebas específicas

```bash
php artisan test --filter PacienteTest
```

## 🔒 Seguridad

- **Autenticación JWT:** Todas las operaciones de pacientes requieren token válido
- **CORS configurado:** Solo permite requests desde el frontend autorizado
- **Validación de datos:** Form Requests validan todos los inputs
- **Sanitización:** Protección contra SQL Injection mediante Eloquent ORM
- **Rate Limiting:** Límite de requests por minuto en endpoints sensibles

## 🚀 Comandos Útiles

### Desarrollo

```bash
# Limpiar caché de configuración
php artisan config:clear

# Limpiar caché de rutas
php artisan route:clear

# Ver todas las rutas disponibles
php artisan route:list

# Crear nuevo controlador
php artisan make:controller NombreController --api

# Crear nueva migración
php artisan make:migration create_tabla_name

# Ejecutar seeders específicos
php artisan db:seed --class=PacienteSeeder
```

### Producción

```bash
# Optimizar aplicación para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones en producción
php artisan migrate --seed
```

## 🔄 Integración con Frontend

El backend se integra con el frontend Vue.js disponible en:
- **Frontend:** [frontend_pacientes](https://github.com/Elian-Canas/frontend_pacientes.git)

### Configuración CORS

Asegúrate de configurar en `.env`:
```env
FRONTEND_URL=http://192.168.1.22:8080
```

Y verificar en `config/cors.php` que permita el origen del frontend.

## 📝 Credenciales de Prueba

Usuario por defecto creado con los seeders:
```
Email: admin@example.com
Password: 1234567890
```

**IMPORTANTE:** Cambiar estas credenciales en producción.

## 🐛 Troubleshooting

### Error de conexión a base de datos
```bash
# Verificar credenciales en .env
# Asegurar que el servidor MySQL esté corriendo
sudo systemctl status mysql
```

### Error de permisos en storage
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Token JWT inválido
```bash
# Regenerar secret JWT
php artisan jwt:secret --force
```

## 👨‍💻 Autor

**Elian Santiago Cañas**


## 🔗 Repositorios Relacionados

- **Frontend:** [frontend_pacientes](https://github.com/Elian-Canas/frontend_pacientes.git)

---

**Versión Backend:** 1.0.0  
**Laravel:** 12.x  
**PHP:** 8.2+
