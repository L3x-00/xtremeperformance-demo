<?php  
// Deshabilitar cache en páginas dinámicas para evitar ver paneles tras logout via back/URL
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');
require_once("../app/inicio.php");
$control = new Control();
?>