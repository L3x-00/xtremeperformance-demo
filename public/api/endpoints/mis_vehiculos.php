<?php
/**
 * Endpoints para los vehículos del cliente
 * GET /api/mis_vehiculos
 */

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    
    // 1. Auth::check() valida el token del celular y nos devuelve el ID del cliente
    $clienteId = Auth::check(); 
    
    if (!$clienteId) {
        Response::unauthorized('Sesión inválida o expirada');
        exit;
    }
    
    try {
        // 2. Instanciamos tu clase de base de datos
        $db = new MySQLdb();
        
        // 3. Buscamos SOLO los vehículos que le pertenecen a este ID
        $sql = "SELECT id, marca, modelo, anio, color, placas 
                FROM vehiculos 
                WHERE idCliente = ? AND baja = 0 
                ORDER BY id DESC";
                
        $vehiculos = $db->querySelect($sql, [$clienteId]);
        
        // 4. Se lo enviamos a Flutter
        Response::success([
            'total' => count($vehiculos),
            'vehiculos' => $vehiculos
        ], 'Vehículos obtenidos correctamente');
        
    } catch (Exception $e) {
        Response::error('Error al obtener los vehículos: ' . $e->getMessage(), 500);
    }
    
} else {
    Response::error('Método no permitido', 405);
}
?>