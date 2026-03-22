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
// VARIABLES GLOBALES PARA EL GRÁFICO
// ==========================================
$infoDelSistema = "";
$abiertas = null;    // Inicializamos en null para saber si el usuario pidió estadísticas
$facturadas = null;

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

                    $infoDelSistema = "INFORMACIÓN CONFIDENCIAL PARA TI: 
                    El cliente pregunta por la orden número $idOrden. 
                    - Estado actual: **$textoEstado**.
                    - Fecha de ingreso: " . $orden['fechaIngreso'] . ". 
                    - Fecha de salida: " . $orden['fechaSalida'] . ". 
                    - Kilometraje: " . $orden['kilometraje'] . " km.
                    Instrucción: Informa al cliente sobre su estado y fechas. Si está en estado 1 (Abierta), dile que siguen trabajando en él. Si está en estado 2 (Facturada), dile que ya puede recogerlo.";
                }
            } else {
                $infoDelSistema = "INFORMACIÓN PRIVADA DEL SISTEMA: El cliente pregunta por la orden $idOrden, pero NO EXISTE en nuestra base de datos. Pídele que verifique el número amablemente.";
            }
        } catch(PDOException $e) {
            $infoDelSistema = "INFORMACIÓN PRIVADA: Error al consultar la orden.";
        }

    // CASO 2: El usuario pregunta por ESTADÍSTICAS o resumen del taller
    } elseif (preg_match('/(cuántas|cuantas|total|resumen|estadística|estadisticas|dashboard).*(ordenes|órdenes|estado|pedidos|vehículos|autos)/i', $mensajeUsuario)) {
        try {
            $stmtStats = $conn->prepare("SELECT estado, COUNT(*) as total FROM ordenreparacion WHERE baja = 0 GROUP BY estado");
            $stmtStats->execute();
            $resStats = $stmtStats->fetchAll(PDO::FETCH_ASSOC);

            // Asignamos a las variables globales
            $abiertas = 0;
            $facturadas = 0;

            foreach ($resStats as $row) {
                if ($row['estado'] == 1) $abiertas = (int)$row['total'];
                if ($row['estado'] == 2) $facturadas = (int)$row['total'];
            }

            $infoDelSistema = "INFORMACIÓN ESTADÍSTICA DEL TALLER: El usuario pide un resumen. 
            Actualmente tenemos:
            - $abiertas órdenes ABIERTAS (En proceso).
            - $facturadas órdenes FACTURADAS (Terminadas).
            Instrucción: Dáselo como un resumen gerencial muy profesional e infla el pecho de orgullo por Xtreme Performance.";
        } catch(PDOException $e) {
            $infoDelSistema = "INFORMACIÓN PRIVADA: No se pudo obtener la estadística de la base de datos.";
        }
    }
}
// ====================================================================

// Armamos el "Prompt" final con REGLAS ESTRICTAS
$promptFinal = "Eres el asistente experto de 'Xtreme Performance', un taller mecánico de alto rendimiento. \n";
$promptFinal .= "REGLAS ESTRICTAS:\n";
$promptFinal .= "1. Tu ÚNICO tema de conversación es sobre autos, mecánica, repuestos, y los servicios de Xtreme Performance.\n";
$promptFinal .= "2. Si te preguntan de otra cosa, niégate cortésmente.\n";
$promptFinal .= "3. TIENES PERMISO EXPRESO para hablar de estadísticas o totales del taller si la información te es proporcionada en este prompt.\n";

if ($infoDelSistema !== "") {
    $promptFinal .= "\n" . $infoDelSistema . "\n\nMensaje del usuario: " . $mensajeUsuario;
} else {
    $promptFinal .= "\nResponde amable y breve. Mensaje del usuario: " . $mensajeUsuario;
}

// ENVÍO A GEMINI
$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => $promptFinal]
            ]
        ]
    ]
];

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
// RESPUESTA FINAL AL FLUTTER (AQUÍ INYECTAMOS EL GRÁFICO)
// ====================================================================
$resultadoIA = json_decode($response, true);

if ($httpCode === 200) {
    $texto = $resultadoIA['candidates'][0]['content']['parts'][0]['text'] ?? "Entendido, dime más.";
    
    // Armamos la respuesta base
    $respuestaFinal = ["respuesta" => $texto];

    // 📊 Si el CASO 2 se activó, $abiertas y $facturadas ya no serán null
    // y enviamos el JSON del gráfico al Flutter
    if ($abiertas !== null && $facturadas !== null) {
        $respuestaFinal["chart"] = [
            "tipo" => "pastel",
            "titulo" => "Órdenes de Reparación",
            "series" => [
                ["label" => "Abiertas", "value" => $abiertas, "color" => "blue"],
                ["label" => "Facturadas", "value" => $facturadas, "color" => "green"]
            ]
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