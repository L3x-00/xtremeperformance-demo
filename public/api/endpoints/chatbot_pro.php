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
    // CASO 1: El usuario pregunta por una orden específica (ej: "orden 19")
    if (preg_match('/orden\s*#?\s*(\d+)/i', $mensajeUsuario, $coincidencias)) {
        $idOrden = $coincidencias[1]; 

        try {
            $stmt = $conn->prepare("SELECT estado, fechaIngreso, fechaSalida, kilometraje, baja FROM ordenreparacion WHERE id = :id");
            $stmt->bindParam(':id', $idOrden);
            $stmt->execute();
            $orden = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($orden) {
                if ($orden['baja'] == 1) {
                    $infoDelSistema = "INFORMACIÓN PRIVADA DEL SISTEMA: El cliente pregunta por la orden $idOrden. Sin embargo, esta orden aparece como DADA DE BAJA o Cancelada. Informa esto amablemente.";
                } else {
                    $textoEstado = "";
                    if ($orden['estado'] == 1) {
                        $textoEstado = "Abierta / En proceso de reparación en el taller"; 
                    } elseif ($orden['estado'] == 2) {
                        $textoEstado = "Facturada / Terminada y lista para entrega"; 
                    } else {
                        $textoEstado = "En revisión";
                    }

                    $infoDelSistema = "INFORMACIÓN CONFIDENCIAL PARA TI (ÚSALO PARA ARMAR TU RESPUESTA): 
                    El cliente pregunta por la orden número $idOrden. 
                    - Estado actual: **$textoEstado**.
                    - Fecha de ingreso: " . $orden['fechaIngreso'] . ". 
                    - Fecha estimada de salida: " . $orden['fechaSalida'] . ". 
                    - Kilometraje registrado: " . $orden['kilometraje'] . " km.
                    Instrucción: Informa al cliente sobre su estado y fechas de forma muy amable y profesional.";
                }
            } else {
                $infoDelSistema = "INFORMACIÓN PRIVADA DEL SISTEMA: El cliente pregunta por la orden $idOrden, pero NO EXISTE en nuestra base de datos. Pídele que verifique el número amablemente.";
            }
        } catch(PDOException $e) {
            $infoDelSistema = "INFORMACIÓN PRIVADA: Error al consultar la orden.";
        }

    // 📊 CASO 2 (Dona): El usuario pregunta por TOTALES de órdenes (ej: "¿Cuántas órdenes hay?")
    } elseif (preg_match('/(cuántas|cuantas|total|resumen|estadística|estadisticas|dashboard).*(ordenes|órdenes|estado|pedidos|vehículos|autos)/i', $mensajeUsuario)) {
        try {
            $stmtStats = $conn->prepare("SELECT estado, COUNT(*) as total FROM ordenreparacion WHERE baja = 0 GROUP BY estado");
            $stmtStats->execute();
            $resStats = $stmtStats->fetchAll(PDO::FETCH_ASSOC);

            // Asignamos a las variables globales para la dona
            $countAbiertas = 0;
            $countFacturadas = 0;

            foreach ($resStats as $row) {
                if ($row['estado'] == 1) $countAbiertas = (int)$row['total'];
                if ($row['estado'] == 2) $countFacturadas = (int)$row['total'];
            }

            $infoDelSistema = "INFORMACIÓN ESTADÍSTICA DEL TALLER: El usuario pide un resumen. 
            Actualmente tenemos en el sistema:
            - $countAbiertas órdenes ABIERTAS (En proceso de reparación).
            - $countFacturadas órdenes FACTURADAS (Terminadas/Listas).
            Instrucción: Dáselo como un resumen gerencial muy profesional e infla el pecho de orgullo por Xtreme Performance.";
        } catch(PDOException $e) {
            $infoDelSistema = "INFORMACIÓN PRIVADA: No se pudo obtener la estadística de la base de datos.";
        }
// 📋 CASO 4: El usuario pide listar cuáles son las órdenes pendientes
} elseif (preg_match('/(cuales|cuáles|lista|mostrar|dime).*(pendientes|activas|abiertas|proceso)/i', $mensajeUsuario) || preg_match('/ordenes.*pendientes/i', $mensajeUsuario)) {
    try {
        // Buscamos las órdenes en estado 1 (Abiertas)
        $sqlPendientes = "SELECT o.id, v.marca, v.modelo, c.nombres, c.apellidos
                          FROM ordenreparacion o
                          LEFT JOIN vehiculos v ON o.idVehiculo = v.id
                          LEFT JOIN clientes c ON v.idCliente = c.id
                          WHERE o.estado = 1 AND o.baja = 0
                          ORDER BY o.fechaIngreso ASC LIMIT 10";
        
        $stmtPend = $conn->prepare($sqlPendientes);
        $stmtPend->execute();
        $resPend = $stmtPend->fetchAll(PDO::FETCH_ASSOC);

        if ($resPend && count($resPend) > 0) {
            $lista = "";
            foreach ($resPend as $row) {
                $lista .= "- Orden #" . $row['id'] . " (" . $row['marca'] . " " . $row['modelo'] . ") del cliente " . $row['nombres'] . " " . $row['apellidos'] . ".\n";
            }
            
            $infoDelSistema = "INFORMACIÓN PRIVADA DEL SISTEMA (ÚSALO PARA ARMAR TU RESPUESTA): 
            El usuario pregunta cuáles son las órdenes pendientes o activas. 
            Aquí tienes la lista real de los vehículos que están en proceso de reparación en este momento:
            \n" . $lista . "\n
            Instrucción: Lee esta lista y menciónale al usuario los vehículos y dueños de forma muy amigable, natural y profesional. No parezcas un robot leyendo una tabla.";
        } else {
            $infoDelSistema = "INFORMACIÓN DEL SISTEMA: El usuario pregunta por órdenes pendientes, pero actualmente NO HAY ninguna orden pendiente (estado 1) en el taller. Todas están facturadas o el taller está vacío. Informa esto amablemente.";
        }
    } catch(PDOException $e) {
        $infoDelSistema = "INFORMACIÓN PRIVADA: Error al consultar las órdenes pendientes en la base de datos.";
    }
   // 💰 CASO 3 (Barras): El usuario pregunta por GANANCIAS o Dinero
    } elseif (preg_match('/(ganancias|dinero|ingresos|ventas|dinero|plata|lucro)/i', $mensajeUsuario)) {
        try {
            // MATEMÁTICAS GERENCIALES CLONADAS DEL DASHBOARD (Usando la tabla 'facturas')
            $sqlDinero = "SELECT 
                            DATE_FORMAT(alta_dt, '%Y-%m') as ym, 
                            DATE_FORMAT(alta_dt, '%b %Y') as mes_label,
                            SUM(total) as total_ingreso
                          FROM facturas 
                          WHERE baja = 0 
                            AND alta_dt >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                          GROUP BY ym, mes_label
                          ORDER BY ym ASC";

            $stmtDinero = $conn->prepare($sqlDinero);
            $stmtDinero->execute();
            $resDinero = $stmtDinero->fetchAll(PDO::FETCH_ASSOC);

            if ($resDinero) {
                // Preparamos los datos para las barras en Flutter
                $barLabels = [];
                $barData = [];
                $resumenTexto = "";

                foreach ($resDinero as $row) {
                    // Traducimos el mes de inglés a español básico
                    $meses = ['Jan'=>'Ene', 'Feb'=>'Feb', 'Mar'=>'Mar', 'Apr'=>'Abr', 'May'=>'May', 'Jun'=>'Jun', 'Jul'=>'Jul', 'Aug'=>'Ago', 'Sep'=>'Sep', 'Oct'=>'Oct', 'Nov'=>'Nov', 'Dec'=>'Dic'];
                    $partes = explode(' ', $row['mes_label']);
                    $mesLabelEspanol = (isset($meses[$partes[0]]) ? $meses[$partes[0]] : $partes[0]) . ' ' . $partes[1];

                    $barLabels[] = $mesLabelEspanol; 
                    $barData[] = (double)$row['total_ingreso'];
                    $resumenTexto .= "- " . $mesLabelEspanol . ": S/ " . number_format($row['total_ingreso'], 2) . "\n";
                }

                $infoDelSistema = "INFORMACIÓN FINANCIERA DEL TALLER (ÚSALO PARA ARMAR TU RESPUESTA): 
                El usuario (administrador) pregunta por el flujo de ingresos.
                Aquí tienes el resumen real de los últimos meses, incluyendo mano de obra, piezas e impuestos:\n
                $resumenTexto
                Instrucción: Haz un análisis gerencial muy profesional. Usa términos como 'flujo de caja', 'optimización' y 'alto rendimiento'.";
            } else {
                $infoDelSistema = "INFORMACIÓN FINANCIERA: No se registraron facturas pagadas en los últimos 6 meses.";
            }

        } catch(PDOException $e) {
            $infoDelSistema = "INFORMACIÓN PRIVADA: Error al consultar los ingresos en la base de datos de facturas.";
        }
    }
}
// ====================================================================

// Armamos el "Prompt" final con REGLAS ESTRICTAS
$promptFinal = "Eres el asistente experto de 'Xtreme Performance', un taller mecánico de alto rendimiento. \n";
$promptFinal .= "REGLAS ESTRICTAS QUE DEBES CUMPLIR OBLIGATORIAMENTE:\n";
$promptFinal .= "1. Tu ÚNICO tema de conversación es sobre autos, mecánica, repuestos, y los servicios de Xtreme Performance.\n";
$promptFinal .= "2. Si el usuario te pregunta por recetas, chistes, política, o cualquier tema que NO sea de autos, DEBES NEGARTE CORTÉSMENTE.\n";
$promptFinal .= "3. TIENES PERMISO EXPRESO para hablar de estadísticas, totales, o flujo de ingresos financieros del taller si la información te es proporcionada en este prompt.\n";

if ($infoDelSistema !== "") {
    $promptFinal .= "\n" . $infoDelSistema . "\n\nMensaje original del usuario: " . $mensajeUsuario;
} else {
    $promptFinal .= "\nResponde amable, breve y siempre dispuesto a ayudar. Mensaje del usuario: " . $mensajeUsuario;
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