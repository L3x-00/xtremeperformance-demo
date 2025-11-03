<?php  
/**
 * 
 */
class Controlador
{
	
	function __construct(){}

	public function enviarCorreo(array $data=[]):bool
	{
		$salida = false;
		if (!empty($data)) {
			$id = Helper::encriptar($data["id"]);
			$link = rtrim(SITE_URL, '/')."/login/cambiarclave/".$id;
			$msg = "Entra a la siguiente liga para cambiar tu clave de acceso al sistema de Xtreme Performance:<br>";
			$msg.= "<a href='".$link."'>Cambiar tu clave de acceso</a><br><br>";
			$msg.= "Si el enlace no funciona, copia y pega esta URL en tu navegador: ".$link;

			$from = MAIL_FROM_NAME.' <'.MAIL_FROM.'>';
			$headers = "MIME-Version: 1.0\r\n"; 
			$headers.= "Content-type: text/html; charset=UTF-8\r\n"; 
			$headers.= "From: $from\r\n"; 
			$headers.= "Reply-To: ".MAIL_REPLY_TO."\r\n";
			$headers.= "X-Mailer: PHP/".phpversion()."\r\n";

			$asunto = "Cambiar clave de acceso";
			// Intentar enviar correo con envelope sender para mejorar entrega (SPF)
			$extraParams = "-f".MAIL_FROM;
			$salida = @mail($data["correo"],$asunto,$msg, $headers, $extraParams);
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

	// Correo genérico para notificaciones simples HTML
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
		//
		if ($_SERVER['REQUEST_METHOD']=="POST") {
			//
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
			//
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
		//
		$datos = [
			"titulo"=> "Perfil del usuario",
			"subtitulo" => "Perfil del usuario",
			"admon" => $this->usuario["tipoUsuario"],
			"menu" => true,
			"regreso" => $regreso,
			"activo" => "perfil",
			"errores" => $errores,
			"data" => $this->usuario
		];
		$this->vista("tableroPerfilVista",$datos);
	}
}

?>