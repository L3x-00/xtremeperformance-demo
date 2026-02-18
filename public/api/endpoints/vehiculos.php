<?php
/**
 * Endpoints de Vehículos
 * GET /api/vehiculos
 * GET /api/vehiculos/{id}
 * POST /api/vehiculos
 * PUT /api/vehiculos/{id}
 * DELETE /api/vehiculos/{id}
 */

$usuarioId = Auth::check();
$db = new MySQLdb();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    
    if (!empty($id)) {
        // GET /api/vehiculos/{id}
        $sql = "SELECT v.*, c.nombres, c.apellidos 
                FROM vehiculos v
                LEFT JOIN clientes c ON v.idCliente = c.id
                WHERE v.id = ? AND v.baja = 0";
        
        $vehiculo = $db->querySelect($sql, [$id]);
        
        if (empty($vehiculo)) {
            Response::notFound('Vehículo no encontrado');
        }
        
        Response::success($vehiculo[0], 'Vehículo obtenido');
        
    } else {
        // GET /api/vehiculos (opcionalmente filtrar por cliente)
        $clienteId = $_GET['idCliente'] ?? null;
        $pagina = $_GET['pagina'] ?? 1;
        $limite = $_GET['limite'] ?? 10;
        $offset = ($pagina - 1) * $limite;
        
        $sql = "SELECT v.id, v.marca, v.modelo, v.anio, v.placas, v.color, 
                v.idCliente, CONCAT(c.apellidos, ', ', c.nombres) as cliente
                FROM vehiculos v
                LEFT JOIN clientes c ON v.idCliente = c.id
                WHERE v.baja = 0";
        
        if (!empty($clienteId)) {
            $sql .= " AND v.idCliente = " . intval($clienteId);
        }
        
        $sql .= " LIMIT $offset, $limite";
        
        $vehiculos = $db->querySelect($sql);
        
        // Contar total
        $sqlCount = "SELECT COUNT(*) as total FROM vehiculos WHERE baja = 0";
        if (!empty($clienteId)) {
            $sqlCount .= " AND idCliente = " . intval($clienteId);
        }
        $totalResult = $db->query($sqlCount);
        $total = $totalResult['total'] ?? 0;
        
        Response::success([
            'vehiculos' => $vehiculos,
            'pagina' => $pagina,
            'limite' => $limite,
            'total' => $total
        ], 'Vehículos obtenidos');
    }
    
} else if ($method === 'POST') {
    
    // POST /api/vehiculos
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data === null) {
        Response::error('JSON inválido', 400);
    }
    
    // Validar datos
    $errores = [];
    if (empty($data['marca'])) $errores[] = 'Marca requerida';
    if (empty($data['modelo'])) $errores[] = 'Modelo requerido';
    if (empty($data['anio'])) $errores[] = 'Año requerido';
    if (empty($data['idCliente'])) $errores[] = 'Cliente requerido';
    
    if (!empty($errores)) {
        Response::validation($errores);
    }
    
    // Insertar
    $sql = "INSERT INTO vehiculos 
            (marca, modelo, anio, color, placas, idCliente, alta_dt) 
            VALUES 
            (:marca, :modelo, :anio, :color, :placas, :idCliente, NOW())";
    
    $insertData = [
        'marca' => $data['marca'],
        'modelo' => $data['modelo'],
        'anio' => $data['anio'],
        'color' => $data['color'] ?? '',
        'placas' => $data['placas'] ?? '',
        'idCliente' => $data['idCliente']
    ];
    
    $resultado = $db->queryNoSelect($sql, $insertData);
    
    if ($resultado) {
        Response::success(null, 'Vehículo creado', 201);
    } else {
        Response::error('Error al crear vehículo', 500);
    }
    
} else if ($method === 'PUT' && !empty($id)) {
    
    // PUT /api/vehiculos/{id}
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data === null) {
        Response::error('JSON inválido', 400);
    }
    
    // Verificar que existe
    $sqlCheck = "SELECT id FROM vehiculos WHERE id = ? AND baja = 0";
    $existe = $db->querySelect($sqlCheck, [$id]);
    if (empty($existe)) {
        Response::notFound('Vehículo no encontrado');
    }
    
    // Actualizar
    $sql = "UPDATE vehiculos 
            SET marca = :marca, 
                modelo = :modelo, 
                anio = :anio, 
                color = :color, 
                placas = :placas,
                cambio_dt = NOW()
            WHERE id = :id";
    
    $updateData = [
        'id' => $id,
        'marca' => $data['marca'] ?? null,
        'modelo' => $data['modelo'] ?? null,
        'anio' => $data['anio'] ?? null,
        'color' => $data['color'] ?? null,
        'placas' => $data['placas'] ?? null
    ];
    
    $resultado = $db->queryNoSelect($sql, $updateData);
    
    if ($resultado) {
        Response::success(null, 'Vehículo actualizado', 200);
    } else {
        Response::error('Error al actualizar', 500);
    }
    
} else if ($method === 'DELETE' && !empty($id)) {
    
    // DELETE /api/vehiculos/{id}
    $sqlCheck = "SELECT id FROM vehiculos WHERE id = ? AND baja = 0";
    $existe = $db->querySelect($sqlCheck, [$id]);
    if (empty($existe)) {
        Response::notFound('Vehículo no encontrado');
    }
    
    // Eliminar (baja lógica)
    $sql = "UPDATE vehiculos SET baja = 1, baja_dt = NOW() WHERE id = ?";
    $resultado = $db->queryNoSelect($sql, [$id]);
    
    if ($resultado) {
        Response::success(null, 'Vehículo eliminado', 200);
    } else {
        Response::error('Error al eliminar', 500);
    }
    
} else {
    Response::error('Método no permitido', 405);
}
