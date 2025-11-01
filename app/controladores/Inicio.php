<?php
/**
 * Controlador para la página de bienvenida pública
 */
class Inicio extends Controlador
{
    function __construct()
    {
        // No necesita constructor con sesión porque es una página pública
    }

    // Este es el método que se ejecutará por defecto
    public function caratula()
    {
        // Preparamos los datos para enviar a la vista
        $datos = [
            "titulo" => "Bienvenido a Xtreme Performance",
            "subtitulo" => "Tu taller mecánico de confianza"
        ];

        // Llamamos a la vista que crearemos en el siguiente paso
        $this->vista("inicioVista", $datos);
    }
}
?>