<?php
/**
 * Configuración de API REST
 */

// Permitir CORS para acceso desde aplicaciones móviles
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

// Si es una solicitud OPTIONS, responder y terminar
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Incluir archivos necesarios
require_once(__DIR__ . '/../../app/libs/MySQLdb.php');
require_once(__DIR__ . '/../../app/libs/Helper.php');
require_once(__DIR__ . '/Response.php');

// Definir constantes
define('API_VERSION', '1.0');
define('API_KEY_HEADER', 'X-API-Key');
