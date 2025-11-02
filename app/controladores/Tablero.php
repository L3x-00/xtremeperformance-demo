<?php  
/**
 * 
 */
class Tablero extends Controlador
{
	protected $usuario="";
	protected $modelo = "";
	protected $sesion;
	
	function __construct()
	{
		//Creamos sesion
		$this->sesion = new Sesion();
		if ($this->sesion->getLogin()) {
			$this->modelo = $this->modelo("TableroModelo");
			$this->usuario = $this->sesion->getUsuario();
			// Solo administradores pueden entrar aquí
			$tipo = $this->usuario["tipoUsuario"] ?? null;
			if ($tipo !== ADMON) {
				if ($tipo === MECANICO) {
					header("location:".RUTA."TableroMecanico");
				} else if ($tipo === CLIENTE) {
					header("location:".RUTA."TableroCliente");
				} else {
					header("location:".RUTA);
				}
				exit;
			}
		} else {
			header("location:".RUTA);
		}
	}

	public function caratula()
	{
		$kpis = $this->modelo->getKpis();
		$serie = $this->modelo->getIngresosMensuales(6);
		$datos = [
			"titulo" => "Sistema de taller mecánico",
			"subtitulo" => $this->usuario["nombres"]." ".$this->usuario["apellidos"],
			"usuario"=>$this->usuario,
			"data"=>["kpis"=>$kpis, "serie"=>$serie],
			"menu" => true
		];
		$this->vista("tableroCaratulaVista",$datos);
	}

	public function logout()
	{
		if (isset($_SESSION['usuario'])) {
			$this->sesion->finalizarLogin();
		}
		header("location:".RUTA);
	}

	public function respaldar()
	{
		$m = "Cuidado: Este proceso realiza el respaldo de la base de datos. Puede tardar algunos minutos.";
    	$this->mensaje(
	  		"Respaldar la base de datos", 
	  		"Respaldar la base de datos", 
	  		$m, 
	  		"tablero",
	  		"danger",
	  		"tablero/respaldarEjecutar/",
	  		"success",
	  		"Respaldar"
	  	);
	}

	public function respaldarEjecutar()
	{
		$fecha = date("Ymdhis");
		$id = uniqid();
		$tablas = $this->modelo->getTablas();
		foreach ($tablas as $tabla) {
			// SHOW TABLES returns an associative array with a key like
			// 'Tables_in_<dbname>' which changes per server. Extract the
			// table name by taking the first value of the row to be robust.
			$nombreTabla = '';
			$vals = array_values($tabla);
			if (count($vals) > 0) $nombreTabla = $vals[0];
			if ($nombreTabla!="") {
				$this->modelo->respaldarTabla($nombreTabla,$fecha,$id);
			}
		}
		$this->mensaje("Respaldo de base de datos",
		"Respaldo de base de datos",
		"El respaldo de base de datos fue exitosa.<br>En la carpeta:<br>respaldos/".$fecha."-".$id,
		"tablero",
		"success");
	}
}
?>