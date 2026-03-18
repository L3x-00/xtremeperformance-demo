<?php
// 1. CABECERAS CORS (Indispensables para que Flutter Web no se queje)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// 2. CONFIGURACIÓN (Usando la versión V1 que es la estándar de producción)
$apiKey = "AIzaSyB5oAkxY6IF0ZcBIsHty4KAjeJgk-uSkEM"; 
$url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

// 3. RECIBIR EL MENSAJE
$input = json_decode(file_get_contents("php://input"), true);
$mensajeUsuario = $input['mensaje'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'GET' || empty($mensajeUsuario)) {
    echo json_encode(["respuesta" => "¡Sistema de IA listo! Xtreme Performance está en línea."]);
    exit;
}

// 4. ESTRUCTURA DE DATOS (Formato oficial Gemini 1.5)
$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => "Eres el asistente experto de 'Xtreme Performance'. Responde de forma amable y profesional: " . $mensajeUsuario]
            ]
        ]
    ]
];

// 5. LLAMADA AL CEREBRO DE GOOGLE
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);

// 6. PROCESAR RESPUESTA
if ($err) {
    echo json_encode(["respuesta" => "Error de comunicación: " . $err]);
} else {
    $resultado = json_decode($response, true);
    
    if ($httpCode === 200) {
        // Extraemos el texto de la respuesta
        $textoIA = $resultado['candidates'][0]['content']['parts'][0]['text'] ?? "No obtuve una respuesta clara, ¿puedes repetir?";
        echo json_encode(["respuesta" => $textoIA]);
    } else {
        // Si hay error, te mostramos qué dijo Google exactamente
        $errorMsg = $resultado['error']['message'] ?? "Error desconocido en la dirección de la API.";
        echo json_encode([
            "respuesta" => "Error 404 o problema de dirección en la IA.",
            "detalle" => $errorMsg,
            "codigo" => $httpCode
        ]);
    }
}