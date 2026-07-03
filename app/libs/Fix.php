<?php
/**
 * Archivo de correcciones para compatibilidad con Cloud SQL
 * No modifica el repositorio original
 */

// Definir constantes si no existen
if (!defined('BASEPATH')) {
    define('BASEPATH', dirname(__FILE__) . '/../');
}

// Corregir error de parámetros en LoginModelo
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

