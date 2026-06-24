<?php
// 1. CABECERAS PARA FLUTTER Y WEB
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }

// IMPORTAMOS LA CLAVE SECRETA
require_once 'claves.php'; 
$apiKey = GEMINI_API_KEY; 
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . trim($apiKey);
$input = json_decode(file_get_contents("php://input"), true);
$mensajeUsuario = $input['mensaje'] ?? '';

// 1. CAPTURAMOS EL ROL Y EL ID DEL USUARIO
// Si no envían rol, asumimos por seguridad que es un visitante sin privilegios
$rolUsuario = $input['rol'] ?? 'VISITANTE'; 
$idUsuarioActivo = $input['id_usuario'] ?? 0;
if (empty($mensajeUsuario)) {
    echo json_encode(["respuesta" => "¡Sistema de IA de Xtreme Performance en línea!"]);
    exit;
}

// ====================================================================
// VARIABLES GLOBALES PARA LA IA Y GRÁFICOS
// ==========================================
$infoDelSistema = "";

// Variables para el gráfico de DONA (Órdenes)
$countAbiertas = null;    
$countFacturadas = null;

// Variables para el gráfico de BARRAS (Dinero)
$barLabels = null;
$barData = null;

// --- CONEXIÓN A TU BASE DE DATOS ---
$host = "localhost";
$dbname = "u645180384_taller"; 
$username = "u645180384_maxi";
$password = "Maxi.123@123";
$conn = null;

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $infoDelSistema = "INFORMACIÓN PRIVADA: Hubo un error de conexión a la BD. Dile al cliente que el sistema está en mantenimiento en este momento.";
}

if ($conn) {
    
    // -------------------------------------------------------------------------
    // 🛡️ ROL 1: ADMINISTRADOR (Tiene acceso a TODO)
    // -------------------------------------------------------------------------
    if ($rolUsuario === 'ADMON') {
        
        // CASO 1: Orden específica
        if (preg_match('/orden\s*#?\s*(\d+)/i', $mensajeUsuario, $coincidencias)) {
            $idOrden = $coincidencias[1]; 
            try {
                $stmt = $conn->prepare("SELECT estado, fechaIngreso, fechaSalida, kilometraje, baja FROM ordenreparacion WHERE id = :id");
                $stmt->bindParam(':id', $idOrden);
                $stmt->execute();
                $orden = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($orden) {
                    if ($orden['baja'] == 1) {
                        $infoDelSistema = "INFORMACIÓN: La orden $idOrden está DADA DE BAJA o Cancelada.";
                    } else {
                        $textoEstado = ($orden['estado'] == 1) ? "Abierta" : (($orden['estado'] == 2) ? "Facturada" : "En revisión");
                        $infoDelSistema = "INFORMACIÓN CONFIDENCIAL: El administrador pregunta por la orden $idOrden. Estado: **$textoEstado**. Ingreso: {$orden['fechaIngreso']}. Salida: {$orden['fechaSalida']}. Km: {$orden['kilometraje']} km.";
                    }
                } else {
                    $infoDelSistema = "INFORMACIÓN: La orden $idOrden NO EXISTE en la base de datos.";
                }
            } catch(PDOException $e) {
                $infoDelSistema = "INFORMACIÓN: Error al consultar la orden.";
            }
        }
        
        // CASO 2: Dona / Totales
        elseif (preg_match('/(cuántas|cuantas|total|resumen|estadística|estadisticas|dashboard).*(ordenes|órdenes|estado|pedidos|vehículos|autos)/i', $mensajeUsuario)) {
            try {
                $stmtStats = $conn->prepare("SELECT estado, COUNT(*) as total FROM ordenreparacion WHERE baja = 0 GROUP BY estado");
                $stmtStats->execute();
                $resStats = $stmtStats->fetchAll(PDO::FETCH_ASSOC);
                $countAbiertas = 0; $countFacturadas = 0;
                foreach ($resStats as $row) {
                    if ($row['estado'] == 1) $countAbiertas = (int)$row['total'];
                    if ($row['estado'] == 2) $countFacturadas = (int)$row['total'];
                }
                $infoDelSistema = "INFORMACIÓN ESTADÍSTICA: Tenemos $countAbiertas órdenes ABIERTAS y $countFacturadas FACTURADAS. Dáselo como resumen gerencial.";
            } catch(PDOException $e) {}
        }
        
        // CASO 3: Listar pendientes
        elseif (preg_match('/(cuales|cuáles|lista|mostrar|dime).*(pendientes|activas|abiertas|proceso)/i', $mensajeUsuario) || preg_match('/ordenes.*pendientes/i', $mensajeUsuario)) {
            try {
                $sqlPendientes = "SELECT o.id, v.marca, v.modelo, c.nombres, c.apellidos
                                  FROM ordenreparacion o
                                  LEFT JOIN vehiculos v ON o.idVehiculo = v.id
                                  LEFT JOIN clientes c ON v.idCliente = c.id
                                  WHERE o.estado = 1 AND o.baja = 0 ORDER BY o.fechaIngreso ASC LIMIT 10";
                $stmtPend = $conn->prepare($sqlPendientes);
                $stmtPend->execute();
                $resPend = $stmtPend->fetchAll(PDO::FETCH_ASSOC);

                if ($resPend && count($resPend) > 0) {
                    $lista = "";
                    foreach ($resPend as $row) {
                        $lista .= "- Orden #" . $row['id'] . " (" . $row['marca'] . " " . $row['modelo'] . ") de " . $row['nombres'] . " " . $row['apellidos'] . ".\n";
                    }
                    $infoDelSistema = "INFORMACIÓN: Lista de pendientes:\n" . $lista . "\nMenciónale al administrador los vehículos de forma natural.";
                } else {
                    $infoDelSistema = "INFORMACIÓN: NO HAY órdenes pendientes (estado 1) en este momento.";
                }
            } catch(PDOException $e) {}
        }

        // CASO 4: Ganancias (Barras)
        elseif (preg_match('/(ganancias|dinero|ingresos|ventas|plata|lucro)/i', $mensajeUsuario)) {
            try {
                $sqlDinero = "SELECT DATE_FORMAT(alta_dt, '%Y-%m') as ym, DATE_FORMAT(alta_dt, '%b %Y') as mes_label, SUM(total) as total_ingreso
                              FROM facturas WHERE baja = 0 AND alta_dt >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) GROUP BY ym, mes_label ORDER BY ym ASC";
                $stmtDinero = $conn->prepare($sqlDinero);
                $stmtDinero->execute();
                $resDinero = $stmtDinero->fetchAll(PDO::FETCH_ASSOC);

                if ($resDinero) {
                    $barLabels = []; $barData = []; $resumenTexto = "";
                    $meses = ['Jan'=>'Ene', 'Feb'=>'Feb', 'Mar'=>'Mar', 'Apr'=>'Abr', 'May'=>'May', 'Jun'=>'Jun', 'Jul'=>'Jul', 'Aug'=>'Ago', 'Sep'=>'Sep', 'Oct'=>'Oct', 'Nov'=>'Nov', 'Dec'=>'Dic'];
                    foreach ($resDinero as $row) {
                        $partes = explode(' ', $row['mes_label']);
                        $mesLabelEspanol = ($meses[$partes[0]] ?? $partes[0]) . ' ' . $partes[1];
                        $barLabels[] = $mesLabelEspanol; 
                        $barData[] = (double)$row['total_ingreso'];
                        $resumenTexto .= "- $mesLabelEspanol: S/ " . number_format($row['total_ingreso'], 2) . "\n";
                    }
                    $infoDelSistema = "INFORMACIÓN FINANCIERA: Flujo de ingresos de los últimos meses:\n$resumenTexto\nHaz un análisis gerencial.";
                } else {
                    $infoDelSistema = "INFORMACIÓN FINANCIERA: No se registraron facturas pagadas en los últimos 6 meses.";
                }
            } catch(PDOException $e) {}
        }
    }

    // -------------------------------------------------------------------------
    // 🛡️ ROL 2: CLIENTE (Solo puede consultar sus propios vehículos)
    // -------------------------------------------------------------------------
    elseif ($rolUsuario === 'CLIENTE') {
        
        // CASO 1: Orden específica (Obligatorio cruzar con su ID de cliente)
        if (preg_match('/orden\s*#?\s*(\d+)/i', $mensajeUsuario, $coincidencias)) {
            $idOrden = $coincidencias[1]; 
            try {
                // NOTA: Se añadió INNER JOIN para validar que el auto sea suyo
                $stmt = $conn->prepare("SELECT o.estado, o.fechaIngreso, o.fechaSalida, o.kilometraje, o.baja 
                                        FROM ordenreparacion o
                                        INNER JOIN vehiculos v ON o.idVehiculo = v.id
                                        WHERE o.id = :id AND v.idCliente = :idCliente");
                $stmt->bindParam(':id', $idOrden);
                $stmt->bindParam(':idCliente', $idUsuarioActivo);
                $stmt->execute();
                $orden = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($orden) {
                    if ($orden['baja'] == 1) {
                        $infoDelSistema = "INFORMACIÓN: Su orden aparece como DADA DE BAJA.";
                    } else {
                        $textoEstado = ($orden['estado'] == 1) ? "Abierta" : (($orden['estado'] == 2) ? "Facturada" : "En revisión");
                        $infoDelSistema = "INFORMACIÓN: El cliente pregunta por SU orden $idOrden. Estado: **$textoEstado**. Ingreso: {$orden['fechaIngreso']}. Salida: {$orden['fechaSalida']}. Km: {$orden['kilometraje']}. Informa muy amablemente.";
                    }
                } else {
                    $infoDelSistema = "REGLA DE SEGURIDAD: El cliente preguntó por la orden $idOrden, pero NO EXISTE o NO LE PERTENECE. Dile amablemente que por seguridad solo puede consultar sus propios vehículos.";
                }
            } catch(PDOException $e) {}
        }
        
        // Bloqueo de seguridad si pregunta por finanzas o listados globales
        elseif (preg_match('/(ganancias|dinero|estadistica|cuantas|pendientes|cuales|todas)/i', $mensajeUsuario)) {
            $infoDelSistema = "REGLA DE SEGURIDAD: El usuario es un CLIENTE. No tiene permisos para ver finanzas, estadísticas globales ni vehículos de otras personas. Rechaza su solicitud de forma cortés indicando que solo puedes informarle sobre sus propias órdenes.";
        }
    }

    // -------------------------------------------------------------------------
    // 🛡️ ROL 3: MECÁNICO (Limitado, sin finanzas)
    // -------------------------------------------------------------------------
    elseif ($rolUsuario === 'MECANICO') {
        if (preg_match('/orden\s*#?\s*(\d+)/i', $mensajeUsuario, $coincidencias)) {
            // Mismo código de consulta general que el Administrador (Caso 1)
            // (El mecánico sí puede consultar autos en el taller)
            $idOrden = $coincidencias[1]; 
            try {
                $stmt = $conn->prepare("SELECT estado, fechaIngreso, fechaSalida, kilometraje, baja FROM ordenreparacion WHERE id = :id");
                $stmt->bindParam(':id', $idOrden);
                $stmt->execute();
                $orden = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($orden) {
                    $textoEstado = ($orden['estado'] == 1) ? "Abierta" : (($orden['estado'] == 2) ? "Facturada" : "En revisión");
                    $infoDelSistema = "INFORMACIÓN: El mecánico consulta la orden $idOrden. Estado: **$textoEstado**. Ingreso: {$orden['fechaIngreso']}. Salida: {$orden['fechaSalida']}. Km: {$orden['kilometraje']}.";
                }
            } catch(PDOException $e) {}
        }
        
        // Bloqueo de seguridad si pregunta por finanzas
        elseif (preg_match('/(ganancias|dinero|ingresos|ventas|plata|lucro)/i', $mensajeUsuario)) {
            $infoDelSistema = "REGLA DE SEGURIDAD: El usuario es un MECÁNICO. No tiene permisos de administrador para ver las finanzas. Rechaza la solicitud cortésmente.";
        }
    }
}
// ====================================================================

// Armamos el "Prompt" final con REGLAS ESTRICTAS
// Armamos el "Prompt" final con REGLAS ESTRICTAS
$promptFinal = "Eres el asistente experto de 'Xtreme Performance', un taller mecánico de alto rendimiento. \n";
$promptFinal .= "REGLAS ESTRICTAS QUE DEBES CUMPLIR OBLIGATORIAMENTE:\n";
$promptFinal .= "1. Tu ÚNICO tema de conversación es sobre autos, mecánica, repuestos, y los servicios del taller.\n";

// REGLA 2 MODIFICADA: Límite estricto para temas fuera de contexto
$promptFinal .= "2. Si el usuario pregunta por recetas, chistes, política o cualquier tema que NO sea de autos, NIÉGATE DE FORMA DIRECTA EN UNA SOLA LÍNEA. No pidas disculpas largas ni ofrezcas introducciones. (Ejemplo aceptado: 'Lo siento, solo puedo ayudarte con consultas mecánicas y de tu vehículo.').\n";

$promptFinal .= "3. TIENES PERMISO EXPRESO para hablar de estadísticas, totales, o flujo de ingresos financieros del taller si la información te es proporcionada en este prompt.\n";

// NUEVA REGLA: Brevedad general
$promptFinal .= "4. Tus respuestas generales deben ser concisas, al grano y fáciles de leer en una pantalla pequeña. Evita párrafos de relleno.\n";

if ($infoDelSistema !== "") {
    $promptFinal .= "\n" . $infoDelSistema . "\n\nMensaje original del usuario: " . $mensajeUsuario;
} else {
    // Ajuste en el mensaje final
    $promptFinal .= "\nResponde directo y sin rodeos. Mensaje del usuario: " . $mensajeUsuario;
}

// 4. ESTRUCTURA DE DATOS PARA GEMINI
$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => $promptFinal]
            ]
        ]
    ]
];

// 5. ENVÍO POR CURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// ====================================================================
// RESPUESTA FINAL AL FLUTTER (AQUÍ INYECTAMOS LOS GRÁFICOS)
// ====================================================================
$resultadoIA = json_decode($response, true);

if ($httpCode === 200) {
    $texto = $resultadoIA['candidates'][0]['content']['parts'][0]['text'] ?? "Entendido, dime más.";
    
    // Armamos la respuesta base
    $respuestaFinal = ["respuesta" => $texto];

    // 📊 INYECCIÓN DEL GRÁFICO DE DONA (Órdenes)
    if ($countAbiertas !== null && $countFacturadas !== null) {
        $respuestaFinal["chart"] = [
            "tipo" => "pastel",
            "titulo" => "Resumen de Órdenes",
            "series" => [
                ["label" => "Abiertas", "value" => $countAbiertas, "color" => "blue"],
                ["label" => "Facturadas", "value" => $countFacturadas, "color" => "green"]
            ]
        ];
    }

    // 💰 INYECCIÓN DEL GRÁFICO DE BARRAS (Dinero)
    if ($barLabels !== null && $barData !== null) {
        $respuestaFinal["chart"] = [
            "tipo" => "barras",
            "titulo" => "Flujo de Ingresos (S/)",
            "labels" => $barLabels,
            "data" => $barData
        ];
    }

    echo json_encode($respuestaFinal);
} else {
    $errorMsg = $resultadoIA['error']['message'] ?? "Error desconocido";
    echo json_encode([
        "respuesta" => "Error $httpCode: El motor sigue sin arrancar.",
        "detalle_final" => $errorMsg
    ]);
}
?>