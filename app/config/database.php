<?php
/**
 * Configuración de base de datos para Cloud SQL
 * Este archivo NO modifica el repositorio original
 */

// Cargar variables de entorno
function loadEnv($file) {
    if (!file_exists($file)) {
        return;
    }
    
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Cargar .env
loadEnv(__DIR__ . '/.env');

// Configuración para Cloud SQL
return [
    'DB_HOST' => $_ENV['DB_HOST'] ?? 'localhost',
    'DB_NAME' => $_ENV['DB_NAME'] ?? 'taller',
    'DB_USER' => $_ENV['DB_USER'] ?? 'appuser',
    'DB_PASS' => $_ENV['DB_PASS'] ?? 'AppUser123!',
    'DB_PORT' => $_ENV['DB_PORT'] ?? '3306'
];
