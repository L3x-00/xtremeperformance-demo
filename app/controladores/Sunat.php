<?php
/**
 * Controlador mínimo para consultas SUNAT (RUC)
 */
class Sunat extends Controlador
{
    private $sesion;
    private $usuario;

    public function __construct()
    {
        $this->sesion = new Sesion();
        if ($this->sesion->getLogin()) {
            $this->usuario = $this->sesion->getUsuario();
        } else {
            header('location:'.RUTA);
        }
    }

    public function ruc()
    {
        $errores = [];
        $resultado = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ruc = Helper::cadena($_POST['ruc'] ?? '');
            if ($ruc === '' || !ctype_digit($ruc) || strlen($ruc) !== 11) {
                $errores[] = 'Debe ingresar un RUC válido de 11 dígitos.';
            } else {
                require_once __DIR__ . '/../libs/SunatRuc.php';
                $resultado = SunatRuc::consultar($ruc);
            }
        }

        $datos = [
            'titulo' => 'Consulta RUC',
            'subtitulo' => 'Consulta de RUC (SUNAT)',
            'menu' => true,
            'usuario' => $this->usuario,
            'errores' => $errores,
            'resultado' => $resultado
        ];
        $this->vista('sunatRucVista', $datos);
    }
}

?>
