<?php
/**
 * Clase para manejar respuestas de la API
 */
class Response {
    
    /**
     * Enviar respuesta exitosa
     */
    public static function success($data, $message = 'Success', $code = 200) {
        http_response_code($code);
        echo json_encode([
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit();
    }
    
    /**
     * Enviar respuesta de error
     */
    public static function error($message, $code = 400, $data = null) {
        http_response_code($code);
        echo json_encode([
            'success' => false,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit();
    }
    
    /**
     * Enviar respuesta de no encontrado
     */
    public static function notFound($message = 'Recurso no encontrado') {
        self::error($message, 404);
    }
    
    /**
     * Enviar respuesta no autorizado
     */
    public static function unauthorized($message = 'No autorizado') {
        self::error($message, 401);
    }
    
    /**
     * Enviar respuesta sin permiso
     */
    public static function forbidden($message = 'Acceso denegado') {
        self::error($message, 403);
    }
    
    /**
     * Enviar respuesta validación fallida
     */
    public static function validation($errors) {
        self::error('Validación fallida', 422, $errors);
    }
}
