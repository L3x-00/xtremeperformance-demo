<?php
/**
 * Endpoints de Órdenes de Reparación
 * GET /api/ordenes
 * GET /api/ordenes/{id}
 */

$usuarioId = Auth::check();
$db = new MySQLdb();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    
    if (!empty($id)) {
        // GET /api/ordenes/{id}
        $sql = "SELECT o.*, v.marca, v.modelo, c.nombres, c.apellidos, e.nombre as estado_nombre
                FROM ordenreparacion o
                LEFT JOIN vehiculos v ON o.idVehiculo = v.id
                LEFT JOIN clientes c ON v.idCliente = c.id
                LEFT JOIN estado e ON o.estado = e.id
                WHERE o.id = ? AND o.baja = 0";
        
        $orden = $db->querySelect($sql, [$id]);
        
        if (empty($orden)) {
            Response::notFound('Orden no encontrada');
        }
        
        Response::success($orden[0], 'Orden obtenida');
        
    } else {
        // GET /api/ordenes
        $vehiculoId = $_GET['idVehiculo'] ?? null;
        $idCliente = $_GET['idCliente'] ?? null; // 1. Recibimos el parámetro de Flutter
        $pagina = $_GET['pagina'] ?? 1;
        $limite = $_GET['limite'] ?? 10;
        $offset = ($pagina - 1) * $limite;
        
        $sql = "SELECT o.id, o.fechaIngreso, o.fechaSalida, o.estado, 
                v.marca, v.modelo, v.placas,
                CONCAT(c.apellidos, ', ', c.nombres) as cliente
                FROM ordenreparacion o
                LEFT JOIN vehiculos v ON o.idVehiculo = v.id
                LEFT JOIN clientes c ON v.idCliente = c.id
                WHERE o.baja = 0";
        
        if (!empty($vehiculoId)) {
            $sql .= " AND o.idVehiculo = " . intval($vehiculoId);
        }
        
        // 2. Aplicamos el filtro si detectamos que viene un cliente
        if (!empty($idCliente)) {
            $sql .= " AND c.id = " . intval($idCliente);
        }
        
        $sql .= " ORDER BY o.fechaIngreso DESC LIMIT $offset, $limite";
        
        $ordenes = $db->querySelect($sql);
        
        // Contar total (3. Agregamos los JOIN aquí también para que el filtro funcione)
        $sqlCount = "SELECT COUNT(o.id) as total 
                     FROM ordenreparacion o
                     LEFT JOIN vehiculos v ON o.idVehiculo = v.id
                     LEFT JOIN clientes c ON v.idCliente = c.id
                     WHERE o.baja = 0";
                     
        if (!empty($vehiculoId)) {
            $sqlCount .= " AND o.idVehiculo = " . intval($vehiculoId);
        }
        if (!empty($idCliente)) {
            $sqlCount .= " AND c.id = " . intval($idCliente);
        }
        
        $totalResult = $db->query($sqlCount);
        $total = $totalResult['total'] ?? 0;
        
        Response::success([
            'ordenes' => $ordenes,
            'pagina' => $pagina,
            'limite' => $limite,
            'total' => $total
        ], 'Órdenes obtenidas');
    }
    
} else {
    Response::error('Método no permitido', 405);
}
