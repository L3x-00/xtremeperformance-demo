<?php
/**
 * Endpoints de Seguimientos
 * GET /api/seguimientos?action=listar&idOrden=X
 * POST /api/seguimientos?action=alta (con idOrdenReparacion, fecha, observacion)
 */

// Definir constantes necesarias
define('ORDEN_ABIERTA', 1);
define('ORDEN_FACTURADA', 2);

// Incluir MySQLdb
require_once(__DIR__ . '/../../../app/libs/MySQLdb.php');
require_once(__DIR__ . '/../../../app/libs/PusherHelper.php');

$usuarioId = Auth::check();
$db = new MySQLdb();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

if ($method === 'GET') {
    
    if ($action === 'listar') {
        $idOrden = $_GET['idOrden'] ?? null;
        if (!$idOrden) {
            Response::badRequest('idOrden requerido');
            exit;
        }
        
        // Obtener seguimientos de la orden
        $sql = "SELECT s.id, s.fecha, s.observacion, CONCAT(v.marca,' ',v.modelo,' ',v.anio) as vehiculo 
                FROM seguimientos as s, ordenreparacion as o, vehiculos as v 
                WHERE s.idOrdenReparacion=o.id AND o.idVehiculo=v.id AND s.baja=0 AND s.idOrdenReparacion=? 
                ORDER BY s.fecha DESC";
        $seguimientos = $db->querySelect($sql, [$idOrden]);
        
        Response::success($seguimientos, 'Seguimientos obtenidos');
        
    } else {
        Response::badRequest('Acción no válida para seguimientos');
    }
    
} elseif ($method === 'POST') {
    
    if ($action === 'alta') {
        $idOrdenReparacion = $_POST['idOrdenReparacion'] ?? null;
        $fecha = $_POST['fecha'] ?? date('Y-m-d H:i:s');
        $observacion = $_POST['observacion'] ?? null;
        
        if (!$idOrdenReparacion || !$observacion) {
            Response::badRequest('idOrdenReparacion y observacion requeridos');
            exit;
        }

        // ========================================================================
        // 🛡️ CANDADO DE SEGURIDAD (API FLUTTER): BLOQUEO DE ÓRDENES FACTURADAS
        // ========================================================================
        $sqlCheck = "SELECT estado FROM ordenreparacion WHERE id = ?";
        $resultadoCheck = $db->querySelect($sqlCheck, [$idOrdenReparacion]);
        
        if ($resultadoCheck && !empty($resultadoCheck)) {
            if ($resultadoCheck[0]['estado'] == ORDEN_FACTURADA) {
                // Usamos la clase Response de tu API para devolver el error a Flutter
                Response::error('Acción denegada: La orden ya está facturada y cerrada.');
                exit; // Detiene la ejecución para proteger la base de datos
            }
        }
        // ========================================================================
        
        // Insertar seguimiento
        $sql = "INSERT INTO seguimientos VALUES(0, ?, ?, ?, 0)";
        $params = [$idOrdenReparacion, $fecha, $observacion];
        if ($db->queryNoSelect($sql, $params)) {
            $id = $db->query("SELECT LAST_INSERT_ID() as id");
            Response::success(['id' => $id['id']], 'Seguimiento agregado');
            
            // Disparar evento Pusher
            $result = PusherHelper::trigger(
                'orden-' . $idOrdenReparacion,
                'nuevo-seguimiento',
                [
                    'id' => $id['id'],
                    'idOrden' => $idOrdenReparacion,
                    'fecha' => $fecha,
                    'observacion' => $observacion
                ]
            );
            error_log("Pusher trigger result: " . ($result ? 'success' : 'failed') . " for orden-$idOrdenReparacion");
        } else {
            Response::error('Error al agregar seguimiento');
        }
        
    } else {
        Response::badRequest('Acción no válida para seguimientos');
    }
    
} else {
    Response::methodNotAllowed('Método no permitido');
}