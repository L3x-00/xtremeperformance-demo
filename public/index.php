<?php  
// Deshabilitar cache en páginas dinámicas para evitar ver paneles tras logout via back/URL
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');
if (!isset($_GET['url']) && isset($_SERVER['REQUEST_URI'])) {
    $ruta = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $_GET['url'] = ltrim($ruta, '/');
}
require_once("../app/inicio.php");
$control = new Control();
?>
