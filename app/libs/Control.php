<?php  
/**
 * 
 */
class Control
{
	private $controlador ="Inicio";
	private $metodo = "caratula";
	private $parametros = [1,2,3];
	
	function __construct()
	{
		$url = $this->separarURL();
		error_log("ROUTING DEBUG: URL separada: " . json_encode($url));
		
		if ($url!=[] && file_exists("../app/controladores/".ucwords($url[0]).".php")) {
			$this->controlador = ucwords($url[0]);
			error_log("ROUTING DEBUG: Controlador encontrado: " . $this->controlador);
			unset($url[0]);
		}
		//
		//Cargar la clase controladora
		//
		$nombreControlador = $this->controlador;
		require_once("../app/controladores/".ucwords($nombreControlador).".php");
		//
		//Crear instancia
		//
		$this->controlador = new $nombreControlador;
		//
		//Metodo
		//
		if (isset($url[1])) {
			if (method_exists($this->controlador, $url[1])) {
				$this->metodo = $url[1];
				error_log("ROUTING DEBUG: Método encontrado: " . $this->metodo);
				unset($url[1]);
			}
		}
		//
		//Parámetros
		//
		$this->parametros = $url ? array_values($url) : [];
		error_log("ROUTING DEBUG: Parámetros: " . json_encode($this->parametros));
		//
		//Ejecutar método
		//
		$nombreClase = get_class($this->controlador);
		error_log("ROUTING DEBUG: Ejecutando {$nombreClase}->{$this->metodo} con parámetros: " . json_encode($this->parametros));
		call_user_func_array([$this->controlador,$this->metodo], $this->parametros);
	}

	public function separarURL():array
	{
		$url = [];
		if (isset($_GET['url'])) {
			//eliminamos el caracter final
			$url = rtrim($_GET['url'],"/");
			$url = rtrim($_GET['url'],"\\");
			//Sanitizar
			$url = filter_var($url,FILTER_SANITIZE_URL);
			//
			$url = explode("/",$url);
		}
		return $url;
	}
}


?>
