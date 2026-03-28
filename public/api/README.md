# API REST - Xtreme Performance

API REST para consumir desde aplicaciones móviles (Flutter, React Native, etc.)

## Base URL

```
https://www.xtremeperformancepe.com/public/api
```

## Autenticación

Todos los endpoints excepto `/auth/login` requieren un token JWT en el header:

```
Authorization: Bearer {token}
```

## Endpoints

### Autenticación

#### Login

```
POST /auth/login
Content-Type: application/json

{
  "correo": "usuario@example.com",
  "clave": "contraseña"
}

Respuesta:
{
  "success": true,
  "code": 200,
  "message": "Login exitoso",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "usuario": {
      "id": 1,
      "nombres": "Juan",
      "apellidos": "Pérez",
      "correo": "juan@example.com",
      "tipo": "CLIENTE"
    }
  }
}
```

#### Verificar Token

```
GET /auth/verify
Authorization: Bearer {token}

Respuesta:
{
  "success": true,
  "code": 200,
  "message": "Token válido",
  "data": {
    "usuarioId": 1
  }
}
```

### Clientes

#### Listar Clientes

```
GET /clientes?pagina=1&limite=10
Authorization: Bearer {token}

Respuesta:
{
  "success": true,
  "data": {
    "clientes": [
      {
        "id": 1,
        "nombre": "Pérez, Juan",
        "telefono": "912345678",
        "correo": "juan@example.com",
        "estado": "Activo",
        "ruc": "20123456789"
      }
    ],
    "pagina": 1,
    "limite": 10,
    "total": 25,
    "totalPaginas": 3
  }
}
```

#### Obtener Cliente

```
GET /clientes/{id}
Authorization: Bearer {token}
```

#### Crear Cliente

```
POST /clientes
Authorization: Bearer {token}
Content-Type: application/json

{
  "nombres": "Juan",
  "apellidos": "Pérez",
  "correo": "juan@example.com",
  "telefono": "912345678",
  "direccion": "Av. Principal 123",
  "ruc": "20123456789",
  "razonSocial": "Empresa X",
  "id_estado_cliente": 1
}
```

#### Actualizar Cliente

```
PUT /clientes/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "nombres": "Juan",
  "apellidos": "Pérez",
  "telefono": "987654321",
  "id_estado_cliente": 1
}
```

#### Eliminar Cliente

```
DELETE /clientes/{id}
Authorization: Bearer {token}
```

### Vehículos

#### Listar Vehículos

```
GET /vehiculos?pagina=1&limite=10&idCliente=1
Authorization: Bearer {token}

Parámetros opcionales:
- idCliente: Filtrar por cliente
- pagina: Número de página
- limite: Registros por página
```

#### Obtener Vehículo

```
GET /vehiculos/{id}
Authorization: Bearer {token}
```

#### Crear Vehículo

```
POST /vehiculos
Authorization: Bearer {token}
Content-Type: application/json

{
  "marca": "Toyota",
  "modelo": "Corolla",
  "anio": "2020",
  "color": "Blanco",
  "placas": "ABC-1234",
  "idCliente": 1
}
```

#### Actualizar Vehículo

```
PUT /vehiculos/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "marca": "Toyota",
  "modelo": "Corolla",
  "anio": "2021",
  "color": "Negro"
}
```

#### Eliminar Vehículo

```
DELETE /vehiculos/{id}
Authorization: Bearer {token}
```

### Órdenes de Reparación

#### Listar Órdenes

```
GET /ordenes?pagina=1&limite=10&idVehiculo=1
Authorization: Bearer {token}
```

#### Obtener Orden

```
GET /ordenes/{id}
Authorization: Bearer {token}
```

### Usuarios

#### Obtener Perfil

```
GET /usuarios/perfil
Authorization: Bearer {token}
```

### Status

#### Verificar API Online

```
GET /status
```

## Códigos de Respuesta

- `200`: Exitoso
- `201`: Creado exitosamente
- `400`: Solicitud inválida
- `401`: No autorizado (token inválido)
- `403`: Acceso denegado
- `404`: Recurso no encontrado
- `405`: Método no permitido
- `422`: Validación fallida
- `500`: Error interno del servidor

## Estructura de Respuesta

### Éxito

```json
{
  "success": true,
  "code": 200,
  "message": "Mensaje descriptivo",
  "data": {
    /* datos */
  },
  "timestamp": "2024-02-17 10:30:45"
}
```

### Error

```json
{
  "success": false,
  "code": 400,
  "message": "Mensaje de error",
  "data": null,
  "timestamp": "2024-02-17 10:30:45"
}
```

## Ejemplo cURL

```bash
# Login
curl -X POST https://www.xtremeperformancepe.com/public/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "correo": "usuario@example.com",
    "clave": "contraseña"
  }'

# Obtener clientes
curl -X GET https://www.xtremeperformancepe.com/public/api/clientes \
  -H "Authorization: Bearer {token}"
```

## Notas

- Los tokens JWT expiran en 7 días
- Todas las respuestas están en JSON
- Se aplica baja lógica (soft delete) al eliminar registros
- Los registros eliminados no se devuelven en las consultas
