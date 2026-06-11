<?php  

class Controlador
{
    function __construct(){}

   public function enviarCorreo(array $data=[]):bool
    {
        $salida = false;
        if (!empty($data)) {
            $id = Helper::encriptar($data["id"]);
            $link = rtrim(SITE_URL, '/')."/login/cambiarclave/".$id;
            
            // Plantilla HTML profesional con CSS en línea
            $html = "
            <div style='font-family: Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; color: #333333;'>
                
                <div style='background-color: #12171D; padding: 25px; text-align: center;'>
                    <h1 style='color: #448AFF; margin: 0; font-size: 24px; letter-spacing: 1px;'>Xtreme Performance</h1>
                </div>
                
                <div style='padding: 30px; background-color: #ffffff;'>
                    <h2 style='color: #12171D; margin-top: 0; font-size: 20px;'>Recuperación de contraseña</h2>
                    
                    <p style='font-size: 16px; line-height: 1.6; color: #555555;'>
                        Hemos recibido una solicitud para restablecer la clave de acceso de tu cuenta en el sistema.
                    </p>
                    
                    <div style='text-align: center; margin: 35px 0;'>
                        <a href='{$link}' style='background-color: #448AFF; color: #ffffff; padding: 14px 28px; text-decoration: none; font-size: 16px; font-weight: bold; border-radius: 6px; display: inline-block;'>
                            Cambiar mi clave de acceso
                        </a>
                    </div>
                    
                    <p style='font-size: 14px; line-height: 1.6; color: #777777;'>
                        Si no solicitaste este cambio, puedes ignorar este correo de forma segura. Tu contraseña actual seguirá siendo válida.
                    </p>
                    
                    <hr style='border: 0; border-top: 1px solid #eeeeee; margin: 20px 0;'>
                    <p style='font-size: 13px; color: #888888; text-align: center; margin-bottom: 0;'>
                        Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                        <a href='{$link}' style='color: #448AFF; text-decoration: none; word-break: break-all;'>{$link}</a>
                    </p>
                </div>
                
                <div style='background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #999999;'>
                    &copy; " . date('Y') . " Xtreme Performance. Todos los derechos reservados.
                </div>
                
            </div>
            ";

            $from = MAIL_FROM_NAME.' <'.MAIL_FROM.'>';
            $headers = "MIME-Version: 1.0\r\n"; 
            $headers.= "Content-type: text/html; charset=UTF-8\r\n"; 
            $headers.= "From: $from\r\n"; 
            $headers.= "Reply-To: ".MAIL_REPLY_TO."\r\n";
            $headers.= "X-Mailer: PHP/".phpversion()."\r\n";

            $asunto = "🔐 Instrucciones para cambiar tu clave de acceso";
            $extraParams = "-f".MAIL_FROM;
            $salida = @mail($data["correo"], $asunto, $html, $headers, $extraParams);
        }
        return $salida;
    }

    public function enviarCorreoCliente(array $data=[]):bool
    {
        $salida = false;
        if (!empty($data)) {
            $id = Helper::encriptar($data["id"]);
            $link = rtrim(SITE_URL, '/')."/login/cambiarclavecliente/".$id;
            $msg = "Has sido registrado como cliente en Xtreme Performance. Para activar tu acceso, crea tu contraseña en el siguiente enlace:<br>";
            $msg.= "<a href='".$link."'>Crear mi contraseña</a><br><br>";
            $msg.= "Si el enlace no funciona, copia y pega esta URL en tu navegador: ".$link;

            $from = MAIL_FROM_NAME.' <'.MAIL_FROM.'>';
            $headers = "MIME-Version: 1.0\r\n"; 
            $headers.= "Content-type: text/html; charset=UTF-8\r\n"; 
            $headers.= "From: $from\r\n"; 
            $headers.= "Reply-To: ".MAIL_REPLY_TO."\r\n";
            $headers.= "X-Mailer: PHP/".phpversion()."\r\n";

            $asunto = "Activación de acceso (cliente)";
            $extraParams = "-f".MAIL_FROM;
            $salida = @mail($data["correo"],$asunto,$msg, $headers, $extraParams);
        }
        return $salida;
    }

    public function enviarCorreoMecanico(array $data=[]):bool
    {
        $salida = false;
        if (!empty($data)) {
            $id = Helper::encriptar($data["id"]);
            $link = rtrim(SITE_URL, '/')."/login/cambiarclavemecanico/".$id;
            $msg = "Has sido registrado como mecánico en Xtreme Performance. Para activar tu acceso, crea tu contraseña en el siguiente enlace:<br>";
            $msg.= "<a href='".$link."'>Crear mi contraseña</a><br><br>";
            $msg.= "Si el enlace no funciona, copia y pega esta URL en tu navegador: ".$link;

            $from = MAIL_FROM_NAME.' <'.MAIL_FROM.'>';
            $headers = "MIME-Version: 1.0\r\n"; 
            $headers.= "Content-type: text/html; charset=UTF-8\r\n"; 
            $headers.= "From: $from\r\n"; 
            $headers.= "Reply-To: ".MAIL_REPLY_TO."\r\n";
            $headers.= "X-Mailer: PHP/".phpversion()."\r\n";

            $asunto = "Activación de acceso (mecánico)";
            $extraParams = "-f".MAIL_FROM;
            $salida = @mail($data["correo"],$asunto,$msg, $headers, $extraParams);
        }
        return $salida;
    }

    public function enviarCorreoPlano(string $para, string $asunto, string $html): bool
    {
        $salida = false;
        if (filter_var($para, FILTER_VALIDATE_EMAIL)) {
            $from = MAIL_FROM_NAME.' <'.MAIL_FROM.'>';
            $headers = "MIME-Version: 1.0\r\n"; 
            $headers.= "Content-type: text/html; charset=UTF-8\r\n"; 
            $headers.= "From: $from\r\n"; 
            $headers.= "Reply-To: ".MAIL_REPLY_TO."\r\n";
            $headers.= "X-Mailer: PHP/".phpversion()."\r\n";
            $extraParams = "-f".MAIL_FROM;
            $salida = @mail($para,$asunto,$html, $headers, $extraParams);
        }
        return $salida;
    }

    public function modelo(string $modelo='')
    {
        if (file_exists("../app/modelos/".$modelo.".php")) {
            require_once("../app/modelos/".$modelo.".php");
            return new $modelo;
        } else {
            die("El modelo ".$modelo." no existe");
        }
    }

    public function vista($vista='',$datos=[])
    {
        if (file_exists("../app/vistas/".$vista.".php")) {
            require_once("../app/vistas/".$vista.".php");
        } else {
            die("La vista ".$vista." no existe");
        }
    }

    public function mensaje($titulo='',$subtitulo,$texto,$url,$color,$url2="",$color2="",$texto2="")
    {
        $datos = [
            "titulo" => $titulo,
            "menu" => true,
            "errores" => [],
            "data" => [],
            "subtitulo" => $subtitulo,
            "texto" => $texto,
            "url" => $url,
            "color" => "alert-".$color,
            "colorBoton" => "btn-".$color,
            "textoBoton" => "Regresar",
            "url2" => $url2,
            "colorBoton2" => "btn-".$color2,
            "textoBoton2" => $texto2
        ];
        $this->vista("mensaje",$datos);
        exit;
    }

    public function perfil()
    {
        $errores = [];
        if ($this->usuario["tipoUsuario"]==ADMON) {
            $regreso = "tablero";
        } else if ($this->usuario["tipoUsuario"]==MECANICO) {
            $regreso = "tableroMecanico";
        } else if ($this->usuario["tipoUsuario"]==CLIENTE) {
            $regreso = "tableroCliente";
        }
        
        if ($_SERVER['REQUEST_METHOD']=="POST") {
            $id = $_POST['id']??"";
            $nombres = Helper::cadena($_POST['nombres']??"");
            $apellidos = Helper::cadena($_POST['apellidos']??"");
            $nueva = $_POST['clave']??"";
            $verifica = $_POST['verifica']??"";

            if(empty($nombres)){
                array_push($errores, "El nombre del usuario no puede estar vacío.");
            }
            if(empty($apellidos)){
                array_push($errores, "El apellido paterno no puede estar vacío.");
            }
            if(!(empty($nueva) && empty($verifica)) ){
                if(empty($verifica)){
                    array_push($errores, "La nueva clave de acceso de verificación no puede estar vacía.");
                }
                if($nueva!=$verifica){
                    array_push($errores, "Las claves de acceso no coinciden.");
                }
            }
            
            if (empty($errores)) {
                if ($this->modelo->setUsuario($id, $nombres, $apellidos,$nueva)) {
                    $data = $this->modelo->getUsuarioId($id);
                    $data["tipoUsuario"] = $this->usuario["tipoUsuario"];
                    $this->sesion->setUsuario($data);
                    $this->mensaje(
                        "Modificación del perfil exitoso", 
                        "Modificación del perfil exitoso", 
                        "Modificación del perfil exitoso ", 
                        $regreso, 
                        "success"
                    );
                } else {
                    $this->mensaje(
                        "Error al modificar del perfil", 
                        "Error al modificar del perfil", 
                        "Error al modificar del perfil", 
                        $regreso, 
                        "danger"
                    );
                }
            }
        }
        
        // 1. Buscamos los datos más recientes en MySQL de forma segura
        if (isset($this->usuario["id"]) && $this->modelo) {
            $datosFrescos = $this->modelo->getUsuarioId($this->usuario["id"]);
            
            // 2. Si encontró datos, actualizamos la sesión
            if ($datosFrescos) {
                $datosFrescos["tipoUsuario"] = $this->usuario["tipoUsuario"];
                $this->sesion->setUsuario($datosFrescos);
                $this->usuario = $datosFrescos;
            }
        }

        $datos = [
            "titulo"=> "Perfil del usuario",
            "subtitulo" => "Perfil del usuario",
            "admon" => $this->usuario["tipoUsuario"],
            "menu" => true,
            "regreso" => $regreso,
            "activo" => "perfil",
            "errores" => $errores,
            "data" => $this->usuario // ¡Datos actualizados!
        ];
        $this->vista("tableroPerfilVista",$datos);
    }
}
?>