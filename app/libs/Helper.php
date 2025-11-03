<?php  
/**
 * 
 */
class Helper
{
	
	function __construct(){}

	public static function cadena($cadena, $maxLength = 500){
		if (!is_string($cadena)) return '';
		
		// Límite de longitud
		if (strlen($cadena) > $maxLength) {
			$cadena = substr($cadena, 0, $maxLength);
		}
		
		// Limpiar caracteres de control peligrosos
		$cadena = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $cadena);
		
		// Filtros básicos de seguridad SQL
		$buscar  = array('^', 'delete', 'drop','truncate','exec','system', 'union', 'select', 'insert', 'update', '--', '/*', '*/', 'xp_', 'sp_');
		$reemplazar = array('-', 'dele*te', 'dr*op','truneca*te','ex*ec','syst*em', 'uni*on', 'sele*ct', 'inse*rt', 'upda*te', '-*-', '/ *', '* /', 'x*p_', 's*p_');
		$cadena = str_ireplace($buscar, $reemplazar, $cadena);
		
		// Trim y codificación HTML
		$cadena = trim($cadena);
		$cadena = htmlspecialchars($cadena, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		
		return $cadena;
	}

	public static function correo($correo='')
	{
		return filter_var($correo, FILTER_VALIDATE_EMAIL);
	}

	public static function encriptar(string $data):string
	{
		return base64_encode(LLAVE1.$data.LLAVE2);
	}

	public static function desencriptar(string $data):string
	{
		$cadena = base64_decode($data);
		if (str_contains($cadena, LLAVE1)) {
			$cadena = str_replace(LLAVE1,"",$cadena);
			if (str_contains($cadena, LLAVE2)) {
				$cadena = str_replace(LLAVE2,"",$cadena);
			} else{
				$cadena = "error";
			}
		} else{
			$cadena = "error";
		}
		return $cadena;
	}

	public static function fecha(string $cadena=""):bool{
		//ISO AAAA-MM-DD
		$salida = false;
		if ($cadena!="") {
		$fecha_array = explode("-", $cadena);
		$salida = checkdate($fecha_array[1], $fecha_array[2], $fecha_array[0]);
		}
		return $salida;
	}

	public static function generarClave(int $lon):string
	{
		$llave = "";
		$cadena = "1234567890ABCDEFGHIJKLMNOPQRSTUVXYZabcdefghijklmnopqrstuvwxyz+*-_";
		$max = strlen($cadena)-1;
		for($i = 0; $i < $lon; $i++){
		  $llave .= substr($cadena, mt_rand(0,$max), 1);
		}
		return $llave;
	}

	public static function numero(string $cadena):string
	{
		$buscar  = array(' ', '$', ',');
		$reemplazar = array('', '', '');
		$numero = str_replace($buscar, $reemplazar, $cadena);
		$numero = filter_var($numero, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		return $numero;
	}

	public static function mostrar($data,$detener=true):void
	{
		print "<pre>";
		var_dump($data);
		print "</pre>";
		if ($detener) {
			exit();
		}
	}

	public static function medidaSize(string $bytes):string
  	{
      $bytes = floatval($bytes);
      $bytes_array = array(
          0 => array(
              "UNIT" => "TB",
              "VALUE" => pow(1024, 4)
          ),
          1 => array(
              "UNIT" => "GB",
              "VALUE" => pow(1024, 3)
          ),
          2 => array(
              "UNIT" => "MB",
              "VALUE" => pow(1024, 2)
          ),
          3 => array(
              "UNIT" => "KB",
              "VALUE" => 1024
          ),
          4 => array(
              "UNIT" => "B",
              "VALUE" => 1
          ),
      );
      foreach($bytes_array as $item)
      {
          if($bytes >= $item["VALUE"])
          {
              $salida = $bytes / $item["VALUE"];
              $salida = strval(round($salida, 2))." ".$item["UNIT"];
              break;
          }
      }
      return $salida;
  }

	/**
	 * Valida teléfono móvil de Perú: debe iniciar con 9 y tener 9 dígitos en total.
	 * Ejemplo válido: 9XXXXXXXX
	 */
	public static function telefonoPE(string $tel=""): bool
	{
		if ($tel === "") return false;
		// Quitar espacios/blancos accidentales
		$tel = preg_replace('/\s+/', '', $tel);
		return (bool)preg_match('/^9\d{8}$/', $tel);
	}

	/**
	 * Valida entrada numérica con límites de seguridad
	 */
	public static function validarId($id, $min = 1, $max = PHP_INT_MAX): int
	{
		$id = filter_var($id, FILTER_VALIDATE_INT);
		if ($id === false || $id < $min || $id > $max) {
			return 0; // ID inválido
		}
		return $id;
	}

	/**
	 * Limpia y valida texto libre con longitud máxima
	 */
	public static function textoLibre($texto, $maxLength = 1000): string
	{
		if (!is_string($texto)) return '';
		
		// Remover tags HTML/PHP
		$texto = strip_tags($texto);
		
		// Límite de longitud
		if (strlen($texto) > $maxLength) {
			$texto = substr($texto, 0, $maxLength);
		}
		
		// Limpiar caracteres de control
		$texto = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $texto);
		
		return trim($texto);
	}

	/**
	 * Validación mejorada de correos con filtros adicionales
	 */
	public static function correoSeguro($correo = ''): string
	{
		if (empty($correo)) return '';
		
		$correo = trim(strtolower($correo));
		
		// Validar formato básico
		if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
			return '';
		}
		
		// Verificar longitud razonable
		if (strlen($correo) > 100) return '';
		
		// Verificar caracteres permitidos
		if (!preg_match('/^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,}$/', $correo)) {
			return '';
		}
		
		return $correo;
	}

	
}



?>