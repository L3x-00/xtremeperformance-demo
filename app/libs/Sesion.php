<?php  
/**
 * 
 */
class Sesion
{
	private $login = false;
	private $usuario;
	
	function __construct()
	{
		@session_start();
		if (isset($_SESSION['usuario'])) {
			$this->usuario = $_SESSION['usuario'];
			$this->login = true;
		} else {
			unset($this->usuario);
			$this->login = false;
		}
	}

	public function iniciarLogin(array $usuario=[]):void
	{
		if ($usuario) {
			$this->usuario = $_SESSION['usuario'] = $usuario;
			$this->login = true;
		}
	}

	public function finalizarLogin():void
	{
		// Eliminar datos de sesión del usuario
		unset($this->usuario);
		if (isset($_SESSION['usuario'])) {
			unset($_SESSION['usuario']);
		}
		$this->login = false;
		// Limpiar y destruir la sesión
		if (session_status() === PHP_SESSION_ACTIVE) {
			// Vaciar variables de sesión
			$_SESSION = [];
			// Borrar cookie de sesión si aplica
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}
			// Destruir la sesión y regenerar ID para evitar fijación
			session_destroy();
		}
		@session_start();
		session_regenerate_id(true);
	}

	public function getLogin():bool
	{
		return $this->login;
	}

	public function getUsuario():array
	{
		return $this->usuario;
	}

	public function setUsuario(array $data=[]):void
	{
		$this->usuario=$_SESSION['usuario']=$data;
	}
}
?>