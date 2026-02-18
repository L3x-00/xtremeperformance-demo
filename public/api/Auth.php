<?php
/**
 * Autenticación de API
 */

// Incluir constantes
require_once(__DIR__ . '/../../app/inicio.php');

class Auth {
    
    private static $db;
    
    public static function init() {
        self::$db = new MySQLdb();
    }
    
    /**
     * Intentar login con correo y contraseña
     */
    public static function login($correo, $clave) {
        self::init();
        
        if (empty($correo) || empty($clave)) {
            Response::error('Correo y contraseña son requeridos', 400);
        }
        
        // Hashear contraseña con el mismo método que el sistema
        $claveHasheada = hash_hmac("sha512", $clave, CLAVE);
        
        // Buscar usuario por correo (puede ser cliente o usuario del sistema)
        $sql = "SELECT id, nombres, apellidos, correo, clave, tipoUsuario 
                FROM usuarios 
                WHERE correo = ? AND baja = 0 
                LIMIT 1";
        
        $usuario = self::$db->querySelect($sql, [$correo]);
        
        if (empty($usuario)) {
            // Buscar en clientes
            $sql = "SELECT id, nombres, apellidos, correo, clave, 'CLIENTE' as tipoUsuario 
                    FROM clientes 
                    WHERE correo = ? AND baja = 0 
                    LIMIT 1";
            $usuario = self::$db->querySelect($sql, [$correo]);
        }
        
        if (empty($usuario)) {
            Response::unauthorized('Correo o contraseña incorrectos');
        }
        
        // Verificar contraseña
        $usuarioData = $usuario[0];
        if ($claveHasheada !== $usuarioData['clave']) {
            Response::unauthorized('Correo o contraseña incorrectos');
        }
        
        // Generar token JWT simple (base64)
        $token = self::generateToken($usuarioData['id']);
        
        Response::success([
            'token' => $token,
            'usuario' => [
                'id' => $usuarioData['id'],
                'nombres' => $usuarioData['nombres'],
                'apellidos' => $usuarioData['apellidos'],
                'correo' => $usuarioData['correo'],
                'tipo' => $usuarioData['tipoUsuario']
            ]
        ], 'Login exitoso', 200);
    }
    
    /**
     * Generar token (JWT simple)
     */
    private static function generateToken($usuarioId) {
        $payload = [
            'userId' => $usuarioId,
            'iat' => time(),
            'exp' => time() + (7 * 24 * 60 * 60) // 7 días
        ];
        
        return base64_encode(json_encode($payload));
    }
    
    /**
     * Verificar y decodificar token
     */
    public static function verifyToken($token) {
        if (empty($token)) {
            return null;
        }
        
        try {
            $payload = json_decode(base64_decode($token), true);
            
            if ($payload === null) {
                return null;
            }
            
            // Verificar expiración
            if (isset($payload['exp']) && $payload['exp'] < time()) {
                return null;
            }
            
            return $payload;
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Obtener token del header
     */
    public static function getToken() {
        // Intenta obtener desde Authorization header
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            if (isset($headers['Authorization'])) {
                $matches = [];
                if (preg_match('/Bearer\s+(.+)/', $headers['Authorization'], $matches)) {
                    return $matches[1];
                }
            }
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            // Fallback para servidores sin getallheaders()
            $matches = [];
            if (preg_match('/Bearer\s+(.+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
                return $matches[1];
            }
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            // Otra alternativa
            $matches = [];
            if (preg_match('/Bearer\s+(.+)/', $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
    
    /**
     * Verificar si el usuario está autenticado
     */
    public static function check() {
        $token = self::getToken();
        if (empty($token)) {
            Response::unauthorized('Token requerido');
        }
        
        $payload = self::verifyToken($token);
        if ($payload === null) {
            Response::unauthorized('Token inválido o expirado');
        }
        
        return $payload['userId'];
    }
}
