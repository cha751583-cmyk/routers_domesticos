# API Router Doméstico - Documentación

## Descripción General

API REST para gestión de registro de usuarios y operaciones CRUD (Crear, Leer, Actualizar, Eliminar) de routers domésticos. Esta API proporciona autenticación segura mediante JWT y encriptación de contraseñas con bcrypt.

**URL Base:** `https://apirouterdomestico-production.up.railway.app`

---

## Tabla de Contenidos

1. [Autenticación](#autenticación)
2. [Gestión de Usuarios](#gestión-de-usuarios)
3. [Gestión de Routers](#gestión-de-routers)
4. [Códigos de Respuesta](#códigos-de-respuesta)
5. [Variables de Entorno](#variables-de-entorno)
6. [Ejemplos de Uso](#ejemplos-de-uso)

---

## Autenticación

La API utiliza **JWT (JSON Web Tokens)** para autenticación. Todos los endpoints protegidos requieren un token válido en el header `Authorization`.

### Header de Autenticación

\`\`\`
Authorization: Bearer <jwt_token>
\`\`\`

### Características de Seguridad

- **JWT_SECRET:** Secreto para generar y validar tokens
- **Expiración de Sesión:** 24 horas
- **Encriptación de Contraseñas:** bcrypt con 10 rondas
- **Límite de Intentos:** Máximo 5 intentos fallidos de login

---

## Gestión de Usuarios

### 1. Registrar Usuario

**Endpoint:** \`POST /api/auth/register\`

**Descripción:** Crea una nueva cuenta de usuario

**Body (JSON):**
\`\`\`json
{
  "email": "usuario@example.com",
  "password": "password_segura",
  "nombre": "Juan",
  "apellido": "Pérez"
}
\`\`\`

**Response (201 Created):**
\`\`\`json
{
  "id": "uuid-del-usuario",
  "email": "usuario@example.com",
  "nombre": "Juan",
  "apellido": "Pérez",
  "createdAt": "2026-09-18T10:30:00Z"
}
\`\`\`

**Errores:**
- \`400\` - Email ya registrado
- \`400\` - Campos obligatorios faltantes
- \`500\` - Error del servidor

---

### 2. Login

**Endpoint:** \`POST /api/auth/login\`

**Descripción:** Autentica un usuario y devuelve un token JWT

**Body (JSON):**
\`\`\`json
{
  "email": "usuario@example.com",
  "password": "password_segura"
}
\`\`\`

**Response (200 OK):**
\`\`\`json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "usuario": {
    "id": "uuid-del-usuario",
    "email": "usuario@example.com",
    "nombre": "Juan",
    "apellido": "Pérez"
  }
}
\`\`\`

**Errores:**
- \`401\` - Credenciales inválidas
- \`429\` - Demasiados intentos fallidos (bloqueado por 15 minutos)
- \`500\` - Error del servidor

---

### 3. Obtener Perfil

**Endpoint:** \`GET /api/auth/perfil\`

**Descripción:** Obtiene los datos del usuario autenticado

**Headers:**
\`\`\`
Authorization: Bearer <jwt_token>
\`\`\`

**Response (200 OK):**
\`\`\`json
{
  "id": "uuid-del-usuario",
  "email": "usuario@example.com",
  "nombre": "Juan",
  "apellido": "Pérez",
  "createdAt": "2026-09-18T10:30:00Z"
}
\`\`\`

**Errores:**
- \`401\` - Token inválido o expirado
- \`404\` - Usuario no encontrado

---

### 4. Actualizar Perfil

**Endpoint:** \`PUT /api/auth/perfil\`

**Descripción:** Actualiza los datos del usuario autenticado

**Headers:**
\`\`\`
Authorization: Bearer <jwt_token>
\`\`\`

**Body (JSON):**
\`\`\`json
{
  "nombre": "Juan Carlos",
  "apellido": "Pérez García"
}
\`\`\`

**Response (200 OK):**
\`\`\`json
{
  "id": "uuid-del-usuario",
  "email": "usuario@example.com",
  "nombre": "Juan Carlos",
  "apellido": "Pérez García",
  "updatedAt": "2026-09-18T11:30:00Z"
}
\`\`\`

**Errores:**
- \`401\` - Token inválido o expirado
- \`400\` - Datos inválidos

---

### 5. Cambiar Contraseña

**Endpoint:** \`POST /api/auth/cambiar-password\`

**Descripción:** Cambia la contraseña del usuario autenticado

**Headers:**
\`\`\`
Authorization: Bearer <jwt_token>
\`\`\`

**Body (JSON):**
\`\`\`json
{
  "passwordActual": "password_anterior",
  "passwordNueva": "password_nueva_segura"
}
\`\`\`

**Response (200 OK):**
\`\`\`json
{
  "mensaje": "Contraseña actualizada correctamente"
}
\`\`\`

**Errores:**
- \`401\` - Contraseña actual incorrecta
- \`400\` - La nueva contraseña es igual a la anterior

---

## Gestión de Routers

### 1. Crear Router

**Endpoint:** \`POST /api/routers\`

**Descripción:** Crea un nuevo router doméstico

**Headers:**
\`\`\`
Authorization: Bearer <jwt_token>
\`\`\`

**Body (JSON):**
\`\`\`json
{
  "nombre": "Router Sala",
  "modelo": "TP-Link Archer A6",
  "macAddress": "00:1A:2B:3C:4D:5E",
  "ubicacion": "Sala Principal",
  "velocidadMaxima": "1200 Mbps"
}
\`\`\`

**Response (201 Created):**
\`\`\`json
{
  "id": "uuid-del-router",
  "usuarioId": "uuid-del-usuario",
  "nombre": "Router Sala",
  "modelo": "TP-Link Archer A6",
  "macAddress": "00:1A:2B:3C:4D:5E",
  "ubicacion": "Sala Principal",
  "velocidadMaxima": "1200 Mbps",
  "estado": "activo",
  "createdAt": "2026-09-18T12:00:00Z"
}
\`\`\`

**Errores:**
- \`401\` - Token inválido
- \`400\` - Campos obligatorios faltantes
- \`409\` - MAC Address ya registrada

---

### 2. Obtener Todos los Routers

**Endpoint:** \`GET /api/routers\`

**Descripción:** Obtiene todos los routers del usuario autenticado

**Headers:**
\`\`\`
Authorization: Bearer <jwt_token>
\`\`\`

**Query Parameters:**
- \`page\` (opcional): Número de página (default: 1)
- \`limit\` (opcional): Cantidad por página (default: 10)
- \`estado\` (opcional): Filtrar por estado (activo/inactivo)

**Response (200 OK):**
\`\`\`json
{
  "data": [
    {
      "id": "uuid-router-1",
      "nombre": "Router Sala",
      "modelo": "TP-Link Archer A6",
      "macAddress": "00:1A:2B:3C:4D:5E",
      "ubicacion": "Sala Principal",
      "velocidadMaxima": "1200 Mbps",
      "estado": "activo",
      "createdAt": "2026-09-18T12:00:00Z"
    }
  ],
  "total": 1,
  "page": 1,
  "limit": 10
}
\`\`\`

---

### 3. Obtener Router por ID

**Endpoint:** \`GET /api/routers/:id\`

**Descripción:** Obtiene los detalles de un router específico

**Headers:**
\`\`\`
Authorization: Bearer <jwt_token>
\`\`\`

**Path Parameters:**
- \`id\`: ID del router

**Response (200 OK):**
\`\`\`json
{
  "id": "uuid-del-router",
  "usuarioId": "uuid-del-usuario",
  "nombre": "Router Sala",
  "modelo": "TP-Link Archer A6",
  "macAddress": "00:1A:2B:3C:4D:5E",
  "ubicacion": "Sala Principal",
  "velocidadMaxima": "1200 Mbps",
  "estado": "activo",
  "createdAt": "2026-09-18T12:00:00Z",
  "updatedAt": "2026-09-18T12:00:00Z"
}
\`\`\`

**Errores:**
- \`401\` - Token inválido
- \`404\` - Router no encontrado

---

### 4. Actualizar Router

**Endpoint:** \`PUT /api/routers/:id\`

**Descripción:** Actualiza los datos de un router

**Headers:**
\`\`\`
Authorization: Bearer <jwt_token>
\`\`\`

**Path Parameters:**
- \`id\`: ID del router

**Body (JSON):**
\`\`\`json
{
  "nombre": "Router Sala Actualizado",
  "ubicacion": "Sala Principal - Piso 2",
  "estado": "activo"
}
\`\`\`

**Response (200 OK):**
\`\`\`json
{
  "id": "uuid-del-router",
  "nombre": "Router Sala Actualizado",
  "modelo": "TP-Link Archer A6",
  "macAddress": "00:1A:2B:3C:4D:5E",
  "ubicacion": "Sala Principal - Piso 2",
  "velocidadMaxima": "1200 Mbps",
  "estado": "activo",
  "updatedAt": "2026-09-18T13:30:00Z"
}
\`\`\`

**Errores:**
- \`401\` - Token inválido
- \`404\` - Router no encontrado
- \`409\` - MAC Address ya registrada

---

### 5. Eliminar Router

**Endpoint:** \`DELETE /api/routers/:id\`

**Descripción:** Elimina un router del sistema

**Headers:**
\`\`\`
Authorization: Bearer <jwt_token>
\`\`\`

**Path Parameters:**
- \`id\`: ID del router

**Response (200 OK):**
\`\`\`json
{
  "mensaje": "Router eliminado correctamente"
}
\`\`\`

**Errores:**
- \`401\` - Token inválido
- \`404\` - Router no encontrado

---

## Códigos de Respuesta

| Código | Descripción |
|--------|-------------|
| \`200\` | OK - Solicitud exitosa |
| \`201\` | Created - Recurso creado exitosamente |
| \`400\` | Bad Request - Datos inválidos |
| \`401\` | Unauthorized - Autenticación requerida o inválida |
| \`404\` | Not Found - Recurso no encontrado |
| \`409\` | Conflict - El recurso ya existe |
| \`429\` | Too Many Requests - Demasiados intentos |
| \`500\` | Internal Server Error - Error del servidor |

---

## Variables de Entorno

\`\`\`env
# Base de Datos
DB_HOST=<host-postgresql>
DB_PORT=5432
DB_USER=<usuario-postgresql>
DB_PASSWORD=<password-postgresql>
DB_NAME=routers_domesticos_db
DATABASE_URL=postgresql://<user>:<password>@<host>:<port>/<db>

# API
API_PORT=3000
NODE_ENV=production
CORS_ORIGIN=*

# Seguridad
JWT_SECRET=<secreto-jwt>
BCRYPT_ROUNDS=10
SESSION_TIMEOUT=86400
MAX_LOGIN_ATTEMPTS=5

# Logging
LOG_LEVEL=info
\`\`\`

---

## Ejemplos de Uso

### Ejemplo 1: Registro e Inicio de Sesión (cURL)

\`\`\`bash
# Registrar usuario
curl -X POST https://apirouterdomestico-production.up.railway.app/api/auth/register \\
  -H "Content-Type: application/json" \\
  -d '{
    "email": "usuario@example.com",
    "password": "MiPassword123!",
    "nombre": "Juan",
    "apellido": "Pérez"
  }'

# Login
curl -X POST https://apirouterdomestico-production.up.railway.app/api/auth/login \\
  -H "Content-Type: application/json" \\
  -d '{
    "email": "usuario@example.com",
    "password": "MiPassword123!"
  }'
\`\`\`

---

### Ejemplo 2: Crear Router (JavaScript/Fetch)

\`\`\`javascript
const token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...";

fetch('https://apirouterdomestico-production.up.railway.app/api/routers', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': \`Bearer \${token}\`
  },
  body: JSON.stringify({
    nombre: 'Router Sala',
    modelo: 'TP-Link Archer A6',
    macAddress: '00:1A:2B:3C:4D:5E',
    ubicacion: 'Sala Principal',
    velocidadMaxima: '1200 Mbps'
  })
})
.then(res => res.json())
.then(data => console.log(data));
\`\`\`

---

### Ejemplo 3: Obtener Routers (Python)

\`\`\`python
import requests

token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
headers = {
    'Authorization': f'Bearer {token}'
}

response = requests.get(
    'https://apirouterdomestico-production.up.railway.app/api/routers',
    headers=headers,
    params={'page': 1, 'limit': 10}
)

print(response.json())
\`\`\`

---

### Ejemplo 4: Actualizar Router (Dart/Flutter)

\`\`\`dart
import 'package:http/http.dart' as http;
import 'dart:convert';

Future<void> updateRouter(String routerId, String token) async {
  final response = await http.put(
    Uri.parse('https://apirouterdomestico-production.up.railway.app/api/routers/$routerId'),
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer $token',
    },
    body: jsonEncode({
      'nombre': 'Router Actualizado',
      'ubicacion': 'Nueva Ubicación',
      'estado': 'activo'
    }),
  );

  if (response.statusCode == 200) {
    print('Router actualizado: ${response.body}');
  }
}
\`\`\`

---

## Notas Importantes

✅ **Seguridad:**
- Siempre usa HTTPS en producción
- Guarda el JWT en almacenamiento seguro en tu app móvil
- No compartas tu JWT_SECRET
- Implementa refresh tokens para sesiones prolongadas

✅ **Rate Limiting:**
- Máximo 5 intentos fallidos de login antes de bloqueo temporal
- Las sesiones expiran después de 24 horas

✅ **CORS:**
- Actualmente permite solicitudes de cualquier origen (\`*\`)
- Recomendamos restringir a tu dominio en producción:
  \`\`\`
  CORS_ORIGIN=https://tuapp.example.com
  \`\`\`

---

## Soporte

Para reportar problemas o preguntas:
- Revisa los logs de la API: \`LOG_LEVEL=debug\` para más detalles
- Verifica la configuración de base de datos
- Asegúrate de que el JWT no está expirado

**Versión API:** 1.0.0  
**Última actualización:** 2026-09-18
