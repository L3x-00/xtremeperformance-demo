<?php
/**
 * Endpoints de Tablero
 * GET /api/tablero?action=kpis
 * GET /api/tablero?action=ingresos_mensuales&meses=6
 */

// Definir constantes necesarias (de app/inicio.php)
define('ORDEN_ABIERTA', 1);
define('ORDEN_FACTURADA', 2);

$usuarioId = Auth::check();
$db = new MySQLdb();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

if ($method === 'GET') {
    
    // Incluir el modelo de Tablero
    require_once(__DIR__ . '/../../app/modelos/TableroModelo.php');
    $tableroModelo = new TableroModelo();
    
    if ($action === 'kpis') {
        // Obtener KPIs
        $kpis = $tableroModelo->getKpis();
        Response::success($kpis, 'KPIs obtenidos');
        
    } elseif ($action === 'ingresos_mensuales') {
        // Obtener ingresos mensuales
        $meses = $_GET['meses'] ?? 6;
        $ingresos = $tableroModelo->getIngresosMensuales((int)$meses);
        Response::success($ingresos, 'Ingresos mensuales obtenidos');
        
    } else {
        Response::badRequest('Acción no válida para tablero');
    }
    
} else {
    Response::methodNotAllowed('Método no permitido');
}