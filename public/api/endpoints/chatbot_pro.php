<?php
// 1. CABECERAS DE SEGURIDAD (CORS) - Vital para Flutter Web
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Manejo de peticiones OPTIONS (Pre-flight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// 2. TU CONFIGURACIÓN
$apiKey = "TU_API_KEY_AQUÍ"; // REVISA QUE ESTÉ BIEN PEGADA
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

// 3. RECIBIR EL MENSAJE
$input = json_decode(file_get_contents("php://input"), true);
$mensajeUsuario = $input['mensaje'] ?? '';

// Si entras desde el navegador (GET), te dará este saludo
if ($_SERVER['REQUEST_METHOD'] == 'GET' || empty($mensajeUsuario)) {
    echo json_encode(["respuesta" => "Servidor de IA Activo. Esperando mensaje de Flutter..."]);
    exit;
}

// 4. PERSONALIDAD
$contexto = "Eres el asistente experto de Xtreme Performance. Responde de forma breve y amable.";

$data = [
    "contents" => [
        ["parts" => [["text" => $contexto . "\nUsuario: " . $mensajeUsuario]]]
    ]
];

// 5. LLAMADA CON MANEJO DE ERRORES
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita errores de certificados en algunos hostings

$response = curl_exec($ch);
$err = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 6. RESPUESTA DETALLADA
if ($err) {
    echo json_encode(["respuesta" => "Error de cURL: " . $err]);
} elseif ($httpCode !== 200) {
    echo json_encode(["respuesta" => "Google API devolvió error: " . $httpCode, "detalle" => json_decode($response)]);
} else {
    $resultado = json_decode($response, true);
    $respuestaIA = $resultado['candidates'][0]['content']['parts'][0]['text'] ?? "No obtuve respuesta de la IA.";
    echo json_encode(["respuesta" => $respuestaIA]);
}