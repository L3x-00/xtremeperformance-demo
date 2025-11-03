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
		$this->configurarSesion();
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
	$this->configurarSesion();
	@session_start();
	session_regenerate_id(true);
}

/**
 * Configura parámetros de seguridad para las sesiones
 */
private function configurarSesion(): void
{
	// Cargar configuración de seguridad
	require_once(__DIR__ . '/Config.php');
	Config::load();
	
	// Configurar parámetros de cookies de sesión
	$secure = Config::get('SESSION_SECURE', 'false') === 'true';
	$httponly = Config::get('SESSION_HTTPONLY', 'true') === 'true';
	$samesite = Config::get('SESSION_SAMESITE', 'Strict');
	
	// Para desarrollo local, secure debe ser false si no hay HTTPS
	if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
		$secure = false;
	}
	
	// Configurar parámetros de sesión antes de iniciarla
	if (session_status() === PHP_SESSION_NONE) {
		ini_set('session.cookie_httponly', $httponly ? '1' : '0');
		ini_set('session.cookie_secure', $secure ? '1' : '0');
		ini_set('session.cookie_samesite', $samesite);
		ini_set('session.use_only_cookies', '1');
		ini_set('session.cookie_lifetime', '0'); // Cookie de sesión
	}
}	public function getLogin():bool
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