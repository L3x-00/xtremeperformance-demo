<?php
/**
 * Endpoints de Clientes
 * GET /api/clientes
 * GET /api/clientes/{id}
 * POST /api/clientes
 * PUT /api/clientes/{id}
 * DELETE /api/clientes/{id}
 */

$usuarioId = Auth::check();
$db = new MySQLdb();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    
    if (!empty($id)) {
        // GET /api/clientes/{id}
        $sql = "SELECT c.*, ec.estado 
                FROM clientes c
                LEFT JOIN estadocliente ec ON c.id_estado_cliente = ec.id
                WHERE c.id = ? AND c.baja = 0";
        
        $cliente = $db->querySelect($sql, [$id]);
        
        if (empty($cliente)) {
            Response::notFound('Cliente no encontrado');
        }
        
        Response::success($cliente[0], 'Cliente obtenido');
        
    } else {
        // GET /api/clientes
        $pagina = $_GET['pagina'] ?? 1;
        $limite = $_GET['limite'] ?? 10;
        $offset = ($pagina - 1) * $limite;
        
        $sql = "SELECT c.id, CONCAT(c.apellidos, ', ', c.nombres) as nombre, 
                c.telefono, c.correo, ec.estado, c.ruc
                FROM clientes c
                LEFT JOIN estadocliente ec ON c.id_estado_cliente = ec.id
                WHERE c.baja = 0
                LIMIT $offset, $limite";
        
        $clientes = $db->querySelect($sql);
        
        // Contar total
        $sqlCount = "SELECT COUNT(*) as total FROM clientes WHERE baja = 0";
        $totalResult = $db->query($sqlCount);
        $total = $totalResult['total'] ?? 0;
        
        Response::success([
            'clientes' => $clientes,
            'pagina' => $pagina,
            'limite' => $limite,
            'total' => $total,
            'totalPaginas' => ceil($total / $limite)
        ], 'Clientes obtenidos');
    }
    
} else if ($method === 'POST') {
    
    // POST /api/clientes
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data === null) {
        Response::error('JSON inválido', 400);
    }
    
    // Validar datos requeridos
    $errores = [];
    if (empty($data['nombres'])) $errores[] = 'Nombre requerido';
    if (empty($data['apellidos'])) $errores[] = 'Apellidos requeridos';
    if (empty($data['correo'])) $errores[] = 'Correo requerido';
    if (empty($data['id_estado_cliente'])) $errores[] = 'Estado requerido';
    
    if (!empty($errores)) {
        Response::validation($errores);
    }
    
    // Verificar email único
    $sqlCheck = "SELECT id FROM clientes WHERE correo = ? AND baja = 0";
    $existe = $db->querySelect($sqlCheck, [$data['correo']]);
    if (!empty($existe)) {
        Response::error('El correo ya está registrado', 400);
    }
    
    // Insertar cliente
    $sql = "INSERT INTO clientes 
            (nombres, apellidos, correo, telefono, direccion, ruc, razonSocial, 
             clave, id_estado_cliente, alta_dt) 
            VALUES 
            (:nombres, :apellidos, :correo, :telefono, :direccion, :ruc, :razonSocial,
             :clave, :id_estado_cliente, NOW())";
    
    $claveTemp = Helper::generarClave(10);
    $insertData = [
        'nombres' => $data['nombres'],
        'apellidos' => $data['apellidos'],
        'correo' => $data['correo'],
        'telefono' => $data['telefono'] ?? '',
        'direccion' => $data['direccion'] ?? '',
        'ruc' => $data['ruc'] ?? '',
        'razonSocial' => $data['razonSocial'] ?? '',
        'clave' => $claveTemp,
        'id_estado_cliente' => $data['id_estado_cliente']
    ];
    
    $resultado = $db->queryNoSelect($sql, $insertData);
    
    if ($resultado) {
        Response::success(['mensaje' => 'Cliente creado exitosamente'], 'Cliente creado', 201);
    } else {
        Response::error('Error al crear cliente', 500);
    }
    
} else if ($method === 'PUT' && !empty($id)) {
    
    // PUT /api/clientes/{id}
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data === null) {
        Response::error('JSON inválido', 400);
    }
    
    // Verificar que el cliente exista
    $sqlCheck = "SELECT id FROM clientes WHERE id = ? AND baja = 0";
    $existe = $db->querySelect($sqlCheck, [$id]);
    if (empty($existe)) {
        Response::notFound('Cliente no encontrado');
    }
    
    // Actualizar
    $sql = "UPDATE clientes 
            SET nombres = :nombres, 
                apellidos = :apellidos, 
                correo = :correo, 
                telefono = :telefono, 
                direccion = :direccion, 
                ruc = :ruc, 
                razonSocial = :razonSocial,
                id_estado_cliente = :id_estado_cliente,
                cambio_dt = NOW()
            WHERE id = :id";
    
    $updateData = [
        'id' => $id,
        'nombres' => $data['nombres'] ?? null,
        'apellidos' => $data['apellidos'] ?? null,
        'correo' => $data['correo'] ?? null,
        'telefono' => $data['telefono'] ?? null,
        'direccion' => $data['direccion'] ?? null,
        'ruc' => $data['ruc'] ?? null,
        'razonSocial' => $data['razonSocial'] ?? null,
        'id_estado_cliente' => $data['id_estado_cliente'] ?? null
    ];
    
    $resultado = $db->queryNoSelect($sql, $updateData);
    
    if ($resultado) {
        Response::success(null, 'Cliente actualizado', 200);
    } else {
        Response::error('Error al actualizar cliente', 500);
    }
    
} else if ($method === 'DELETE' && !empty($id)) {
    
    // DELETE /api/clientes/{id}
    $sqlCheck = "SELECT id FROM clientes WHERE id = ? AND baja = 0";
    $existe = $db->querySelect($sqlCheck, [$id]);
    if (empty($existe)) {
        Response::notFound('Cliente no encontrado');
    }
    
    // Eliminar (baja lógica)
    $sql = "UPDATE clientes SET baja = 1, baja_dt = NOW() WHERE id = ?";
    $resultado = $db->queryNoSelect($sql, [$id]);
    
    if ($resultado) {
        Response::success(null, 'Cliente eliminado', 200);
    } else {
        Response::error('Error al eliminar cliente', 500);
    }
    
} else {
    Response::error('Método no permitido', 405);
}
