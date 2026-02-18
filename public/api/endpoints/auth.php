<?php
/**
 * Endpoints de Autenticación
 * POST /api/auth/login
 */

$method = $_SERVER['REQUEST_METHOD'];
$action = $action ?? null;

if ($method === 'POST' && $action === 'login') {
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data === null) {
        Response::error('JSON inválido', 400);
    }
    
    $correo = $data['correo'] ?? '';
    $clave = $data['clave'] ?? '';
    
    Auth::login($correo, $clave);
    
} else if ($method === 'GET' && $action === 'verify') {
    
    $usuarioId = Auth::check();
    Response::success(['usuarioId' => $usuarioId], 'Token válido');
    
} else {
    
    Response::error('Método no permitido', 405);
    
}
