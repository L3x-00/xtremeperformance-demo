<?php include_once("encabezado.php"); 
print '<div class="alert '.$datos["color"].'" mt-3>';
print '<h4>'.$datos["texto"].'</h4>';

// Mostrar QR de Yape si el mensaje es sobre facturación
if (stripos($datos["texto"], "factura") !== false) {
    print '<div class="text-center mt-4 mb-3">';
    print '<p class="mb-3"><strong>💳 Puedes pagar con Yape escaneando este código:</strong></p>';
    
    print '<p class="mt-2 text-muted small">Escanea con tu app de Yape para pagar de forma rápida y segura</p>';
    print '</div>';
}

print '</div>';
if (isset($datos["url2"]) && !empty($datos["url2"])) {
	print '<a href="'.RUTA.$datos["url2"].'" class="btn '.$datos["colorBoton2"].'" >';
	print $datos["textoBoton2"].'</a>&nbsp;';
}
print '<a href="'.RUTA.$datos["url"].'" class="btn '.$datos["colorBoton"].'" >';
print $datos["textoBoton"].'</a>';
include_once("piepagina.php"); ?>