<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json; charset=UTF-8");

require_once '../../../app/libs/MySQLdb.php'; 

$input = json_decode(file_get_contents("php://input"), true);
$mensaje = strtolower($input['mensaje'] ?? '');

$respuesta = "Lo siento, no entendí bien. ¿Podrías preguntarme por el estado de una orden específica? (Ejemplo: 'estado de la orden 19')";

if (strpos($mensaje, 'orden') !== false || strpos($mensaje, 'estado') !== false) {
    preg_match('/\d+/', $mensaje, $matches);
    
    if (!empty($matches)) {
        $idOrden = $matches[0];
        $db = new MySQLdb();
        
        $sql = "SELECT s.observacion, s.fecha, v.placas 
                FROM seguimientos s 
                JOIN ordenreparacion o ON s.idOrdenReparacion = o.id 
                JOIN vehiculos v ON o.idVehiculo = v.id 
                WHERE s.idOrdenReparacion = $idOrden AND s.baja = 0 
                ORDER BY s.id DESC LIMIT 1";
        
        $resultado = $db->query($sql);

        if ($resultado && !empty($resultado['observacion'])) {
            $respuesta = "¡Hola! Revisé la orden #$idOrden (Placa: " . $resultado['placas'] . ").\n\nEl último avance registrado el " . $resultado['fecha'] . " es:\n\"" . $resultado['observacion'] . "\"";
        } else {
            $respuesta = "Buscando en mis registros... No encontré seguimientos o una orden activa con el número #$idOrden. Verifica si el número es correcto.";
        }
    } else {
        $respuesta = "Entiendo que quieres saber sobre una orden. Por favor, incluye el número de la orden en tu mensaje (Ejemplo: 'orden 19').";
    }
} elseif (strpos($mensaje, 'hola') !== false) {
    $respuesta = "¡Hola! Soy el Mecánico Virtual de Xtreme Performance. ¿Qué orden de reparación deseas consultar hoy?";
}

echo json_encode(["respuesta" => $respuesta]);
?>