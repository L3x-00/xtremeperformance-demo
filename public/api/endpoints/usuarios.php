<?php
/**
 * Endpoints de Usuarios
 * GET /api/usuarios/perfil
 */

$usuarioId = Auth::check();
$db = new MySQLdb();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    
    $action = $action ?? null;
    
    if ($action === 'perfil') {
        // GET /api/usuarios/perfil
        $sql = "SELECT id, nombres, apellidos, correo, tipoUsuario, alta_dt
                FROM usuarios
                WHERE id = ? AND baja = 0
                LIMIT 1";
        
        $usuario = $db->querySelect($sql, [$usuarioId]);
        
        if (empty($usuario)) {
            Response::notFound('Usuario no encontrado');
        }
        
        Response::success($usuario[0], 'Perfil obtenido');
        
    } else {
        Response::error('Acción no válida', 400);
    }
    
} else {
    Response::error('Método no permitido', 405);
}
