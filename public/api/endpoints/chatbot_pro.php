<?php
// 1. CABECERAS PARA FLUTTER WEB
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }

// 2. CONFIGURACIÓN (URL V1BETA - LA MÁS COMPATIBLE CON FLASH)
$apiKey = "AIzaSyB5oAkxY6IF0ZcBIsHty4KAjeJgk-uSkEM"; 
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

// 3. RECIBIR DATOS
$input = json_decode(file_get_contents("php://input"), true);
$mensajeUsuario = $input['mensaje'] ?? '';

if (empty($mensajeUsuario)) {
    echo json_encode(["respuesta" => "¡Hola! El motor de Xtreme Performance está listo. ¿En qué te ayudo?"]);
    exit;
}

// 4. ESTRUCTURA MINIMALISTA (Para evitar Error 400)
$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => "Eres el asistente de Xtreme Performance. Responde breve: " . $mensajeUsuario]
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

// 6. RESPUESTA Y DIAGNÓSTICO
$resultadoIA = json_decode($response, true);

if ($httpCode === 200) {
    $texto = $resultadoIA['candidates'][0]['content']['parts'][0]['text'] ?? "Entendido, dime más.";
    echo json_encode(["respuesta" => $texto]);
} else {
    // Si falla, te dirá exactamente por qué
    $errorMsg = $resultadoIA['error']['message'] ?? "Error desconocido de Google";
    echo json_encode([
        "respuesta" => "Error $httpCode: No se encontró el modelo o la clave es incorrecta.",
        "detalle" => $errorMsg
    ]);
}