<?php
// 1. CABECERAS PARA FLUTTER WEB
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }

// IMPORTAMOS LA CLAVE SECRETA (Como lo hicimos con .gitignore)
require_once 'claves.php'; 
$apiKey = GEMINI_API_KEY; 
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent?key=" . trim($apiKey);

$input = json_decode(file_get_contents("php://input"), true);
$mensajeUsuario = $input['mensaje'] ?? '';

if (empty($mensajeUsuario)) {
    echo json_encode(["respuesta" => "¡Sistema de IA de Xtreme Performance en línea!"]);
    exit;
}

// ====================================================================
// NUEVA ZONA: EL CEREBRO DETECTIVE (Buscador de Órdenes)
// ====================================================================
$infoDelSistema = "";

// Usamos una Expresión Regular para detectar "orden" seguido de un número
if (preg_match('/orden\s*#?\s*(\d+)/i', $mensajeUsuario, $coincidencias)) {
    $idOrden = $coincidencias[1]; // Aquí atrapamos el número (ej: 19)

    // --- CONEXIÓN A TU BASE DE DATOS ---
    // ¡OJO LUCCIANO! Cambia estos datos por los reales de tu hosting
    $host = "localhost";
    $dbname = "u645180384_taller"; 
    $username = "u645180384_maxi";
    $password = "Maxi.123@123";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        
        // ¡OJO! Asegúrate de que tu tabla se llame "ordenes" y tenga las columnas correctas
        $stmt = $conn->prepare("SELECT estado FROM ordenes WHERE id = :id");
        $stmt->bindParam(':id', $idOrden);
        $stmt->execute();
        $orden = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($orden) {
            // Le "soplamos" la respuesta a Gemini
            $infoDelSistema = "INFORMACIÓN PRIVADA DEL SISTEMA: El cliente pregunta por la orden $idOrden. La base de datos dice que su estado real es: '" . $orden['estado'] . "'. Usa esta información para responderle al cliente de forma amable.";
        } else {
            $infoDelSistema = "INFORMACIÓN PRIVADA DEL SISTEMA: El cliente pregunta por la orden $idOrden, pero NO EXISTE en nuestra base de datos. Pídele que verifique el número amablemente.";
        }
    } catch(PDOException $e) {
        $infoDelSistema = "INFORMACIÓN PRIVADA: Hubo un error de conexión a la BD. Dile al cliente que el sistema está en mantenimiento.";
    }
}
// ====================================================================

// Armamos el "Prompt" final combinando las reglas, el estado de la BD y lo que dijo el usuario
$promptFinal = "Eres el asistente experto de Xtreme Performance. ";
if ($infoDelSistema !== "") {
    $promptFinal .= $infoDelSistema . " Mensaje original del usuario: " . $mensajeUsuario;
} else {
    $promptFinal .= "Responde amable y breve: " . $mensajeUsuario;
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

// 6. RESPUESTA AL FLUTTER
$resultadoIA = json_decode($response, true);

if ($httpCode === 200) {
    $texto = $resultadoIA['candidates'][0]['content']['parts'][0]['text'] ?? "Entendido, dime más.";
    echo json_encode(["respuesta" => $texto]);
} else {
    $errorMsg = $resultadoIA['error']['message'] ?? "Error desconocido";
    echo json_encode([
        "respuesta" => "Error $httpCode: El motor sigue sin arrancar.",
        "detalle_final" => $errorMsg
    ]);
}
?>