<?php
/**
 * Test básico de la API
 * Acceder a: https://www.xtremeperformancepe.com/public/api/status
 */

require_once(__DIR__ . '/config.php');

// Verificar que la API está funcionando
Response::success([
    'version' => API_VERSION,
    'status' => 'online',
    'timestamp' => date('Y-m-d H:i:s'),
    'endpoints' => [
        'POST /auth/login',
        'GET /auth/verify',
        'GET /clientes',
        'POST /clientes',
        'GET /vehiculos',
        'POST /vehiculos',
        'GET /ordenes',
        'GET /usuarios/perfil'
    ]
], 'API Online');
