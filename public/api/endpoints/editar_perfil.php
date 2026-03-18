<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: POST, OPTIONS");         
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}
header("Content-Type: application/json; charset=UTF-8");

require_once '../../../app/libs/MySQLdb.php'; 

$input = json_decode(file_get_contents("php://input"), true);

$idUsuario = $input['id'] ?? null;
$nombres = $input['nombres'] ?? '';
$apellidos = $input['apellidos'] ?? '';
$telefono = $input['telefono'] ?? '';
$correo = $input['correo'] ?? '';

if ($idUsuario) {
    $db = new MySQLdb();
    
    $sql = "UPDATE usuarios SET nombres = '$nombres', apellidos = '$apellidos', telefono = '$telefono', correo = '$correo' WHERE id = $idUsuario";
    
    $resultado = $db->queryNoSelect($sql);

    if ($resultado) {
        echo json_encode(["success" => true, "mensaje" => "Perfil actualizado correctamente."]);
    } else {
        echo json_encode(["success" => false, "error" => "No se pudo actualizar el perfil."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Falta el ID del usuario."]);
}
?>