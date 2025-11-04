<?php
// Script temporal para limpiar caché y forzar recarga
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Forzar recarga completa
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script>
        // Limpiar caché del navegador y recargar
        if ('caches' in window) {
            caches.keys().then(function(names) {
                names.forEach(function(name) {
                    caches.delete(name);
                });
            });
        }
        
        // Forzar recarga en 2 segundos
        setTimeout(function() {
            window.location.href = '/';
        }, 2000);
    </script>
</head>
<body>
    <h1>Limpiando caché...</h1>
    <p>Redirigiendo a la página principal en 2 segundos...</p>
    <p><a href="/">Ir ahora a la página principal</a></p>
</body>
</html>