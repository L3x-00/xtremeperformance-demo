<?php
require_once 'app/libs/MySQLdb.php';

// Crear conexión a la base de datos
$db = new MySQLdb();

echo "<h1>Diagnóstico de Facturas - Cliente Nataly Barzola Olivares</h1>";

// 1. Buscar cliente por nombre
$sql = "SELECT id, nombres, apellidos FROM clientes WHERE nombres LIKE '%Nataly%' AND apellidos LIKE '%Barzola%'";
$cliente = $db->querySelect($sql);
if (empty($cliente)) {
    echo "<p>Cliente no encontrado</p>";
    exit;
}

$idCliente = $cliente[0]['id'];
echo "<h2>Cliente encontrado: ID = {$idCliente}</h2>";
echo "<p>Nombre: {$cliente[0]['nombres']} {$cliente[0]['apellidos']}</p>";

// 2. Obtener órdenes de reparación del cliente
$sql = "SELECT o.id, o.fechaIngreso, o.fechaSalida, o.estado, v.marca, v.modelo, v.anio 
        FROM ordenreparacion o 
        INNER JOIN vehiculos v ON o.idVehiculo = v.id 
        WHERE v.idCliente = {$idCliente} AND o.baja = 0";
$ordenes = $db->querySelect($sql);
echo "<h2>Órdenes de reparación ({" . count($ordenes) . "}):</h2>";
foreach ($ordenes as $orden) {
    echo "<p>Orden ID: {$orden['id']}, Estado: {$orden['estado']}, Vehículo: {$orden['marca']} {$orden['modelo']} {$orden['anio']}</p>";
}

// 3. Obtener facturas del cliente
$sql = "SELECT f.id, f.idOrdenReparacion, f.manoObra, f.materiales, f.otro, f.iva, f.total, f.alta_dt
        FROM facturas f
        INNER JOIN ordenreparacion o ON f.idOrdenReparacion = o.id
        INNER JOIN vehiculos v ON o.idVehiculo = v.id
        WHERE v.idCliente = {$idCliente} AND f.baja = 0";
$facturas = $db->querySelect($sql);
echo "<h2>Facturas ({" . count($facturas) . "}):</h2>";
$totalFacturas = 0;
foreach ($facturas as $factura) {
    echo "<p>Factura ID: {$factura['id']}, Orden: {$factura['idOrdenReparacion']}, Total: S/ {$factura['total']}, Fecha: {$factura['alta_dt']}</p>";
    echo "<ul>";
    echo "<li>Materiales: S/ {$factura['materiales']}</li>";
    echo "<li>Mano de obra: S/ {$factura['manoObra']}</li>";
    echo "<li>Otros: S/ {$factura['otro']}</li>";
    echo "<li>IVA: S/ {$factura['iva']}</li>";
    echo "</ul>";
    $totalFacturas += $factura['total'];
}
echo "<h3>Total acumulado de todas las facturas: S/ " . number_format($totalFacturas, 2) . "</h3>";

// 4. Verificar KPI que calcula el tablero del cliente
$sql = "SELECT COALESCE(SUM(f.total),0) AS gasto_total 
        FROM clientes c, vehiculos v, ordenreparacion o 
        LEFT JOIN facturas f ON f.idOrdenReparacion=o.id AND f.baja=0 
        WHERE c.id={$idCliente} AND v.idCliente=c.id AND o.idVehiculo=v.id AND o.baja=0";
$kpi = $db->query($sql);
echo "<h2>KPI calculado por el sistema:</h2>";
echo "<p>Gasto total: S/ " . number_format($kpi['gasto_total'], 2) . "</p>";

// 5. Verificar si hay órdenes sin facturas
$sql = "SELECT o.id, o.fechaIngreso, o.estado, v.marca, v.modelo 
        FROM ordenreparacion o 
        INNER JOIN vehiculos v ON o.idVehiculo = v.id 
        LEFT JOIN facturas f ON f.idOrdenReparacion = o.id AND f.baja = 0
        WHERE v.idCliente = {$idCliente} AND o.baja = 0 AND f.id IS NULL";
$ordenesSinFactura = $db->querySelect($sql);
echo "<h2>Órdenes sin factura ({" . count($ordenesSinFactura) . "}):</h2>";
foreach ($ordenesSinFactura as $orden) {
    echo "<p>Orden ID: {$orden['id']}, Estado: {$orden['estado']}, Vehículo: {$orden['marca']} {$orden['modelo']}</p>";
}

?>