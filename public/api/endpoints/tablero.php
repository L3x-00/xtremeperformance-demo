<?php
/**
 * Endpoints de Tablero
 * GET /api/tablero?action=kpis
 * GET /api/tablero?action=ingresos_mensuales&meses=6
 */

// Definir constantes necesarias
define('ORDEN_ABIERTA', 1);
define('ORDEN_FACTURADA', 2);

// IMPORTACIONES CORREGIDAS
// Subimos 3 niveles: public/api/endpoints -> public/api -> public -> raíz -> app/libs
require_once(__DIR__ . '/../../../app/libs/MySQLdb.php');
require_once(__DIR__ . '/../../../app/libs/Sesion.php');
$sesion = new Sesion();
if (!$sesion->getLogin()) {
    Response::badRequest('No estás logueado');
    exit;
}    // Necesario para Auth::check()
require_once(__DIR__ . '/../../../app/libs/Response.php');  // Necesario para Response::success() y errores


$db = new MySQLdb();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

if ($method === 'GET') {
    
    if ($action === 'kpis') {
        // Obtener KPIs
        $kpis = [
            "ordenes_abiertas" => 0,
            "ordenes_facturadas" => 0,
            "ordenes_totales" => 0,
            "ingresos_mes" => 0.0
        ];
        // Ordenes abiertas
        $r = $db->query("SELECT COUNT(*) AS c FROM ordenreparacion WHERE baja=0 AND estado=".ORDEN_ABIERTA);
        $kpis["ordenes_abiertas"] = isset($r["c"]) ? intval($r["c"]) : 0;
        
        // Ordenes facturadas
        $r = $db->query("SELECT COUNT(*) AS c FROM ordenreparacion WHERE baja=0 AND estado=".ORDEN_FACTURADA);
        $kpis["ordenes_facturadas"] = isset($r["c"]) ? intval($r["c"]) : 0;
        
        // Ordenes totales
        $r = $db->query("SELECT COUNT(*) AS c FROM ordenreparacion WHERE baja=0");
        $kpis["ordenes_totales"] = isset($r["c"]) ? intval($r["c"]) : 0;
        
        // Ingresos del mes (desde facturas: incluye materiales + mano de obra + otros + IVA)
        $r = $db->query("SELECT IFNULL(SUM(total),0) AS s FROM facturas WHERE baja=0 AND DATE_FORMAT(alta_dt,'%Y-%m')=DATE_FORMAT(CURDATE(),'%Y-%m')");
        $kpis["ingresos_mes"] = isset($r["s"]) ? floatval($r["s"]) : 0.0;
        
        Response::success($kpis, 'KPIs obtenidos');
        
    } elseif ($action === 'ingresos_mensuales') {
        // Obtener ingresos mensuales
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
        $ingresos = ["labels"=>$labels, "data"=>$data];
        
        Response::success($ingresos, 'Ingresos mensuales obtenidos');
        
    } else {
        Response::badRequest('Acción no válida para tablero');
    }
    
} else {
    Response::methodNotAllowed('Método no permitido');
}