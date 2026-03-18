<?php
header("Content-Type: application/json");

// 1. TU CONFIGURACIÓN
$apiKey = "AIzaSyB5oAkxY6IF0ZcBIsHty4KAjeJgk-uSkEM"; // Pega aquí la clave que obtuviste
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

// 2. RECIBIR EL MENSAJE DE FLUTTER
$input = json_decode(file_get_contents("php://input"), true);
$mensajeUsuario = $input['mensaje'] ?? '';

if (empty($mensajeUsuario)) {
    echo json_encode(["respuesta" => "¡Hola! Soy el asistente de Xtreme Performance. ¿En qué puedo ayudarte con tu vehículo?"]);
    exit;
}

// 3. EL "SYSTEM PROMPT" (La personalidad del bot)
$contextoPersonalidad = "Eres el asistente técnico experto de Xtreme Performance, un taller mecánico de alta precisión. 
Tu tono es profesional, amable y conocedor. Si te preguntan cosas fuera de mecánica, intenta ser educado pero regresa al tema automotriz. 
IMPORTANTE: Si el usuario pregunta por una 'orden' o 'estado de vehículo', dile que por ahora solo puedes charlar, pero que pronto podrás consultar la base de datos directamente.";

// 4. PREPARAR LA PETICIÓN PARA GEMINI
$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => $contextoPersonalidad . "\n\nUsuario: " . $mensajeUsuario]
            ]
        ]
    ]
];

// 5. ENVIAR A GOOGLE
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Solo para pruebas si tienes problemas de SSL

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 6. PROCESAR LA RESPUESTA
if ($httpCode == 200) {
    $resultado = json_decode($response, true);
    $respuestaIA = $resultado['candidates'][0]['content']['parts'][0]['text'] ?? "Lo siento, mi motor se detuvo un momento. ¿Podrías repetir eso?";
    echo json_encode(["respuesta" => $respuestaIA]);
} else {
    echo json_encode(["respuesta" => "Error de conexión con el cerebro de IA (Código: $httpCode)."]);
}