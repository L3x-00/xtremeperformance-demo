<?php
/**
 * API REST Principal
 * Ruta: /api/
 */

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/Auth.php');

// Obtener el path de la solicitud
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/public/api';

// Enrutar solicitudes - Soportar tanto rutas como parámetros GET
$resource = $segments[0] ?? $_GET['resource'] ?? null;
$action = $segments[1] ?? $_GET['action'] ?? null;
$id = $segments[2] ?? $_GET['id'] ?? null;

// Debug
error_log("API Request: $resource/$action/$id - Method: " . $_SERVER['REQUEST_METHOD']);

// Enrutar solicitudes
switch ($resource) {
    
    case 'auth':
        require_once(__DIR__ . '/endpoints/auth.php');
        break;
    
    case 'clientes':
        require_once(__DIR__ . '/endpoints/clientes.php');
        break;
    
    case 'vehiculos':
        require_once(__DIR__ . '/endpoints/vehiculos.php');
        break;
    
    case 'ordenes':
        require_once(__DIR__ . '/endpoints/ordenes.php');
        break;
    
    case 'usuarios':
        require_once(__DIR__ . '/endpoints/usuarios.php');
        break;
    
    case 'tablero':
        require_once(__DIR__ . '/endpoints/tablero.php');
        break;
    
    case 'seguimientos':
        require_once(__DIR__ . '/endpoints/seguimientos.php');
        break;
    
    case 'status':
        Response::success(['version' => API_VERSION], 'API Online');
        break;
    
    default:
        Response::notFound('Endpoint no encontrado');
}
