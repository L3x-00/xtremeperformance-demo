<?php  
/**
 * Controlador para mantenimiento de facturas
 */
class MantenimientoFacturas extends Controlador
{
	private $modelo = "";
	private $usuario;
	private $sesion;
	
	function __construct()
	{
		//Creamos sesion
		$this->sesion = new Sesion();
		if ($this->sesion->getLogin()) {
			$this->modelo = $this->modelo("SalidasModelo");
			$this->usuario = $this->sesion->getUsuario();
			// Solo administradores pueden usar mantenimiento
			$tipo = $this->usuario["tipoUsuario"] ?? null;
			if ($tipo !== ADMON) {
				header("location:".RUTA);
				exit;
			}
		} else {
			header("location:".RUTA);
		}
	}

	public function diagnostico()
	{
		$datos = [
			"titulo" => "Diagnóstico de Facturas",
			"subtitulo" => "Herramientas de mantenimiento",
			"usuario" => $this->usuario,
			"menu" => true,
			"admon" => true,
			"activo" => "mantenimiento"
		];
		
		// Obtener estadísticas
		$datos["diagnostico"] = $this->obtenerDiagnostico();
		
		$this->vista("mantenimientoFacturasVista", $datos);
	}

	public function limpiarDuplicadas()
	{
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$resultado = $this->modelo->limpiarFacturasDuplicadas();
			
			if ($resultado["eliminadas"] > 0) {
				$mensaje = "Se eliminaron " . $resultado["eliminadas"] . " facturas duplicadas correctamente.";
				if (!empty($resultado["errores"])) {
					$mensaje .= "<br>Errores: " . implode(", ", $resultado["errores"]);
				}
				$this->mensaje(
					"Limpieza de duplicados", 
					"Limpieza de duplicados", 
					$mensaje,
					"mantenimientoFacturas/diagnostico",
					"success"
				);
			} else {
				$mensaje = "No se encontraron facturas duplicadas para eliminar.";
				if (!empty($resultado["errores"])) {
					$mensaje .= "<br>Errores: " . implode(", ", $resultado["errores"]);
				}
				$this->mensaje(
					"Limpieza de duplicados", 
					"Limpieza de duplicados", 
					$mensaje,
					"mantenimientoFacturas/diagnostico",
					"info"
				);
			}
		}
	}

	public function recalcularTotales()
	{
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$resultado = $this->modelo->recalcularTotalesFacturas();
			
			if ($resultado["corregidas"] > 0) {
				$mensaje = "Se corrigieron " . $resultado["corregidas"] . " facturas con totales incorrectos.";
				if (!empty($resultado["errores"])) {
					$mensaje .= "<br>Errores: " . implode(", ", $resultado["errores"]);
				}
				$this->mensaje(
					"Recálculo de totales", 
					"Recálculo de totales", 
					$mensaje,
					"mantenimientoFacturas/diagnostico",
					"success"
				);
			} else {
				$mensaje = "No se encontraron facturas con totales incorrectos.";
				if (!empty($resultado["errores"])) {
					$mensaje .= "<br>Errores: " . implode(", ", $resultado["errores"]);
				}
				$this->mensaje(
					"Recálculo de totales", 
					"Recálculo de totales", 
					$mensaje,
					"mantenimientoFacturas/diagnostico",
					"info"
				);
			}
		}
	}

	private function obtenerDiagnostico():array
	{
		$diagnostico = [];
		
		try {
			// Contar facturas duplicadas
			$sql = "SELECT COUNT(*) as total_duplicados FROM (
				SELECT idOrdenReparacion 
				FROM facturas 
				WHERE baja = 0 
				GROUP BY idOrdenReparacion 
				HAVING COUNT(*) > 1
			) AS duplicados";
			$resultado = $this->modelo->db->query($sql);
			$diagnostico["facturas_duplicadas"] = $resultado["total_duplicados"] ?? 0;
			
			// Contar facturas con totales incorrectos
			$sql = "SELECT COUNT(*) as total_incorrectos
					FROM facturas 
					WHERE baja = 0 
					AND total != (manoObra + materiales + otro + iva)";
			$resultado = $this->modelo->db->query($sql);
			$diagnostico["totales_incorrectos"] = $resultado["total_incorrectos"] ?? 0;
			
			// Total de facturas activas
			$sql = "SELECT COUNT(*) as total_activas FROM facturas WHERE baja = 0";
			$resultado = $this->modelo->db->query($sql);
			$diagnostico["facturas_activas"] = $resultado["total_activas"] ?? 0;
			
			// Clientes con posibles problemas
			$sql = "SELECT c.nombres, c.apellidos, 
						   COUNT(f.id) as total_facturas,
						   SUM(f.total) as suma_total
					FROM clientes c 
					INNER JOIN vehiculos v ON v.idCliente = c.id
					INNER JOIN ordenreparacion o ON o.idVehiculo = v.id
					INNER JOIN facturas f ON f.idOrdenReparacion = o.id
					WHERE f.baja = 0 AND c.baja = 0 AND v.baja = 0 AND o.baja = 0
					GROUP BY c.id
					HAVING total_facturas > 1
					ORDER BY total_facturas DESC";
			$diagnostico["clientes_multiples_facturas"] = $this->modelo->db->querySelect($sql);
			
		} catch (Exception $e) {
			error_log("Error en diagnóstico de facturas: " . $e->getMessage());
			$diagnostico["error"] = "Error al obtener datos de diagnóstico: " . $e->getMessage();
		}
		
		return $diagnostico;
	}
}
?>