<?php
/**
 * Endpoints de Tablero
 * GET /api/tablero?action=kpis
 * GET /api/tablero?action=ingresos_mensuales&meses=6
 */

define('ORDEN_ABIERTA', 1);
define('ORDEN_FACTURADA', 2);

// 1. IMPORTACIONES (Ya no pedimos Response.php)
require_once(__DIR__ . '/../../../app/libs/MySQLdb.php');
require_once(__DIR__ . '/../../../app/libs/Sesion.php');

// 2. FUNCIÓN NATIVA PARA ENVIAR JSON (Reemplaza a la clase Response)
function enviarRespuesta($status, $mensaje, $data = null, $codigoHttp = 200) {
    http_response_code($codigoHttp);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "status" => $status,
        "message" => $mensaje,
        "data" => $data
    ]);
    exit;
}

// 3. VALIDACIÓN DE SESIÓN
$sesion = new Sesion();
if (!$sesion->getLogin()) {
    // Si en Flutter te sale "No estás logueado", comenta estas dos líneas de abajo
    enviarRespuesta("error", "No estás logueado", null, 401);
}

// 4. LÓGICA DE LA BASE DE DATOS
$db = new MySQLdb();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

if ($method === 'GET') {
    
    if ($action === 'kpis') {
        $kpis = [
            "ordenes_abiertas" => 0,
            "ordenes_facturadas" => 0,
            "ordenes_totales" => 0,
            "ingresos_mes" => 0.0
        ];
        
        $r = $db->query("SELECT COUNT(*) AS c FROM ordenreparacion WHERE baja=0 AND estado=".ORDEN_ABIERTA);
        $kpis["ordenes_abiertas"] = isset($r["c"]) ? intval($r["c"]) : 0;
        
        $r = $db->query("SELECT COUNT(*) AS c FROM ordenreparacion WHERE baja=0 AND estado=".ORDEN_FACTURADA);
        $kpis["ordenes_facturadas"] = isset($r["c"]) ? intval($r["c"]) : 0;
        
        $r = $db->query("SELECT COUNT(*) AS c FROM ordenreparacion WHERE baja=0");
        $kpis["ordenes_totales"] = isset($r["c"]) ? intval($r["c"]) : 0;
        
        $r = $db->query("SELECT IFNULL(SUM(total),0) AS s FROM facturas WHERE baja=0 AND DATE_FORMAT(alta_dt,'%Y-%m')=DATE_FORMAT(CURDATE(),'%Y-%m')");
        $kpis["ingresos_mes"] = isset($r["s"]) ? floatval($r["s"]) : 0.0;
        
        // Usamos la nueva función en lugar de Response::success
        enviarRespuesta("success", "KPIs obtenidos", $kpis);
        
    } elseif ($action === 'ingresos_mensuales') {
        $meses = $_GET['meses'] ?? 6;
        $sql = "SELECT DATE_FORMAT(alta_dt,'%Y-%m') as ym, SUM(total) as total ".
               "FROM facturas WHERE baja=0 AND alta_dt >= DATE_SUB(CURDATE(), INTERVAL ".$meses." MONTH) ".
               "GROUP BY ym ORDER BY ym ASC";
               
        $rows = $db->querySelect($sql);
        $labels = [];
        $data = [];
        
        foreach ($rows as $row) {
            $labels[] = $row['ym'];
            $data[] = floatval($row['total']);
        }
        $ingresos = ["labels" => $labels, "data" => $data];
        
        // Usamos la nueva función
        enviarRespuesta("success", "Ingresos obtenidos", $ingresos);
        
    } else {
        enviarRespuesta("error", "Acción no válida", null, 400);
    }
    
} else {
    enviarRespuesta("error", "Método no permitido", null, 405);
}