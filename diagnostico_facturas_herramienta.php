<?php
/**
 * Script de Diagnóstico y Corrección de Facturas
 * Ejecutar desde navegador o línea de comandos
 */

// Configuración de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Herramienta de Diagnóstico de Facturas</h1>";
echo "<p><strong>Problema:</strong> Discrepancia entre panel del cliente (S/ 322.48) y factura real (S/ 385.12)</p>";

// Instrucciones para usar el diagnóstico manual
echo "<h2>📋 Instrucciones de Diagnóstico Manual</h2>";
echo "<p>Como no se puede conectar automáticamente a la base de datos, siga estos pasos:</p>";

echo "<h3>1️⃣ Buscar Cliente</h3>";
echo "<code>SELECT id, nombres, apellidos FROM clientes WHERE nombres LIKE '%Nataly%' AND apellidos LIKE '%Barzola%';</code>";

echo "<h3>2️⃣ Ver Vehículos del Cliente (reemplazar X con ID del cliente)</h3>";
echo "<code>SELECT id, marca, modelo, anio, placas, idCliente FROM vehiculos WHERE idCliente = X AND baja = 0;</code>";

echo "<h3>3️⃣ Ver Órdenes de Reparación del Cliente</h3>";
echo "<code>SELECT o.id, o.fechaIngreso, o.fechaSalida, o.estado, v.marca, v.modelo FROM ordenreparacion o INNER JOIN vehiculos v ON o.idVehiculo = v.id WHERE v.idCliente = X AND o.baja = 0;</code>";

echo "<h3>4️⃣ Ver TODAS las Facturas del Cliente</h3>";
echo "<code>SELECT f.id, f.idOrdenReparacion, f.manoObra, f.materiales, f.otro, f.iva, f.total, f.baja, f.alta_dt FROM facturas f INNER JOIN ordenreparacion o ON f.idOrdenReparacion = o.id INNER JOIN vehiculos v ON o.idVehiculo = v.id WHERE v.idCliente = X ORDER BY f.alta_dt DESC;</code>";

echo "<h3>5️⃣ Calcular Total como lo Hace el Sistema</h3>";
echo "<code>SELECT COALESCE(SUM(f.total),0) AS gasto_calculado FROM clientes c, vehiculos v, ordenreparacion o LEFT JOIN facturas f ON f.idOrdenReparacion=o.id AND f.baja=0 WHERE c.id=X AND v.idCliente=c.id AND o.idVehiculo=v.id AND o.baja=0;</code>";

echo "<h2>🎯 Posibles Problemas y Soluciones</h2>";

echo "<h3>Problema A: Facturas Duplicadas</h3>";
echo "<p><strong>Síntoma:</strong> Múltiples facturas para la misma orden de reparación</p>";
echo "<p><strong>Solución:</strong></p>";
echo "<code>UPDATE facturas SET baja = 1 WHERE id IN (ids_de_facturas_duplicadas);</code>";

echo "<h3>Problema B: IVA Mal Calculado</h3>";
echo "<p><strong>Síntoma:</strong> El total no coincide con materiales + mano_obra + otros + iva</p>";
echo "<p><strong>Verificar:</strong></p>";
echo "<code>SELECT *, (manoObra + materiales + otro + iva) AS total_deberia_ser FROM facturas WHERE id = Y;</code>";
echo "<p><strong>Corregir:</strong></p>";
echo "<code>UPDATE facturas SET total = (manoObra + materiales + otro + iva) WHERE id = Y;</code>";

echo "<h3>Problema C: Facturas No Eliminadas Correctamente</h3>";
echo "<p><strong>Síntoma:</strong> Facturas con baja=0 que deberían estar eliminadas</p>";
echo "<p><strong>Verificar:</strong></p>";
echo "<code>SELECT * FROM facturas WHERE baja = 0;</code>";

echo "<h2>🔍 Análisis de Datos Específicos</h2>";
echo "<p>Basado en la información proporcionada:</p>";
echo "<ul>";
echo "<li><strong>Cliente:</strong> Nataly Barzola Olivares</li>";
echo "<li><strong>Vehículo:</strong> Hyundai Elantra 2019</li>";
echo "<li><strong>Orden ID:</strong> 13</li>";
echo "<li><strong>Panel muestra:</strong> S/ 322.48</li>";
echo "<li><strong>Factura real:</strong> S/ 385.12</li>";
echo "<li><strong>Diferencia:</strong> S/ 62.64</li>";
echo "</ul>";

echo "<h2>✅ Pasos de Validación</h2>";
echo "<ol>";
echo "<li>Ejecute la consulta #4 para ver todas las facturas del cliente</li>";
echo "<li>Sume manualmente todos los totales de facturas con baja=0</li>";
echo "<li>Compare con lo que muestra el panel (S/ 322.48)</li>";
echo "<li>Si hay diferencias, identifique qué factura(s) causan el problema</li>";
echo "<li>Aplique la corrección correspondiente</li>";
echo "</ol>";

echo "<h2>🚨 Script de Corrección Automática</h2>";
echo "<p>Una vez identificado el problema, puede usar estos scripts de corrección:</p>";

echo "<h3>Si hay facturas duplicadas:</h3>";
echo "<textarea rows='5' cols='80' readonly>";
echo "-- Encontrar duplicados por idOrdenReparacion
SELECT idOrdenReparacion, COUNT(*) as duplicados 
FROM facturas 
WHERE baja = 0 
GROUP BY idOrdenReparacion 
HAVING COUNT(*) > 1;

-- Eliminar duplicados (mantener solo la más reciente)
UPDATE facturas f1 
SET baja = 1 
WHERE f1.baja = 0 
AND EXISTS (
    SELECT 1 FROM facturas f2 
    WHERE f2.idOrdenReparacion = f1.idOrdenReparacion 
    AND f2.baja = 0 
    AND f2.id > f1.id
);";
echo "</textarea>";

echo "<h3>Si hay error en cálculo de total:</h3>";
echo "<textarea rows='3' cols='80' readonly>";
echo "UPDATE facturas 
SET total = (manoObra + materiales + otro + iva) 
WHERE (total != (manoObra + materiales + otro + iva)) AND baja = 0;";
echo "</textarea>";

echo "<h2>📞 Soporte</h2>";
echo "<p>Si después de ejecutar estos diagnósticos no encuentra la causa, proporcione:</p>";
echo "<ul>";
echo "<li>Resultado de la consulta #4 (todas las facturas del cliente)</li>";
echo "<li>Resultado de la consulta #5 (total calculado por el sistema)</li>";
echo "<li>Captura de pantalla del panel del cliente</li>";
echo "</ul>";

?>