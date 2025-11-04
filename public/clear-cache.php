<?php
// LIMPIEZA AGRESIVA DE CACHÉ - SOLUCIÓN INMEDIATA
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("Clear-Site-Data: \"cache\", \"storage\"");

// Forzar recarga completa
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Clear-Site-Data" content="cache,storage">
    <script>
        console.log('🔧 INICIANDO LIMPIEZA AGRESIVA DE CACHÉ...');
        
        // Limpiar TODO el caché del navegador
        if ('caches' in window) {
            caches.keys().then(function(names) {
                console.log('📁 Eliminando cachés:', names);
                names.forEach(function(name) {
                    caches.delete(name);
                });
            });
        }
        
        // Limpiar localStorage y sessionStorage
        if (typeof(Storage) !== "undefined") {
            localStorage.clear();
            sessionStorage.clear();
            console.log('🗑️ Storage limpiado');
        }
        
        // Mensaje visual
        document.addEventListener('DOMContentLoaded', function() {
            let countdown = 3;
            const counter = document.getElementById('countdown');
            const interval = setInterval(function() {
                counter.textContent = countdown;
                countdown--;
                if (countdown < 0) {
                    clearInterval(interval);
                    console.log('🚀 Redirigiendo a página principal...');
                    window.location.href = '/?nocache=' + Date.now();
                }
            }, 1000);
        });
    </script>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            text-align: center; 
            padding: 50px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 0 auto;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        #countdown {
            font-size: 2em;
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 REPARANDO DISEÑO WEB</h1>
        <div class="spinner"></div>
        <p><strong>Limpiando caché del navegador...</strong></p>
        <p>Redirigiendo en <span id="countdown">3</span> segundos</p>
        <p><a href="/?nocache=<?php echo time(); ?>" style="color: #3498db; text-decoration: none; font-weight: bold;">🚀 IR INMEDIATAMENTE A LA PÁGINA PRINCIPAL</a></p>
    </div>
</body>
</html>