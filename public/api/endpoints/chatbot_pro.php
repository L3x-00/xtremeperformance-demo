<?php
// 1. CABECERAS DE SEGURIDAD (CORS) - Vital para Flutter Web
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Manejo de peticiones pre-flight de los navegadores
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// 2. CONFIGURACIÓN CON TU API KEY
$apiKey = "AIzaSyB5oAkxY6IF0ZcBIsHty4KAjeJgk-uSkEM"; 
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

// 3. RECIBIR EL MENSAJE DE FLUTTER
$input = json_decode(file_get_contents("php://input"), true);
$mensajeUsuario = $input['mensaje'] ?? '';

// Respuesta rápida si no hay mensaje (o si entras desde el navegador)
if ($_SERVER['REQUEST_METHOD'] == 'GET' || empty($mensajeUsuario)) {
    echo json_encode(["respuesta" => "¡Hola! El sistema de IA de Xtreme Performance está activo. Envía un mensaje desde la App."]);
    exit;
}

// 4. PREPARAR LA ESTRUCTURA PARA GEMINI
// Hemos simplificado el JSON para evitar el error 400
$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => "Eres el asistente experto de 'Xtreme Performance', un taller mecánico de alta precisión. Responde de forma amable, breve y profesional: " . $mensajeUsuario]
            ]
        ]
    ]
];

// 5. ENVIAR PETICIÓN A GOOGLE USANDO CURL
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

// 6. PROCESAR Y ENTREGAR LA RESPUESTA
if ($err) {
    echo json_encode(["respuesta" => "Error de conexión local: " . $err]);
} else {
    $resultadoIA = json_decode($response, true);
    
    if ($httpCode === 200) {
        // Extraemos el texto de la respuesta de Gemini
        $textoFinal = $resultadoIA['candidates'][0]['content']['parts'][0]['text'] ?? "Entendido, ¿en qué más puedo ayudarte?";
        echo json_encode(["respuesta" => $textoFinal]);
    } else {
        // Si Google da error, mostramos el detalle para diagnosticar
        $errorMsg = $resultadoIA['error']['message'] ?? "Error desconocido en la API de Google";
        echo json_encode([
            "respuesta" => "Lo siento, mi cerebro de IA tuvo un problema (Error $httpCode).",
            "detalle" => $errorMsg
        ]);
    }
}