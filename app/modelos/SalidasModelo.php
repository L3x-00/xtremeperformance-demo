<?php  
/**
 * 
 */
class SalidasModelo
{
	private $db="";
	
	function __construct()
	{
		$this->db = new MySQLdb();
	}

	public function altaFactura($data, $manoObra, $otro, $materiales, $iva, $total,  $observacion):int
	{
		$salida = 0;
		
		// Verificar si ya existe una factura para esta orden de reparación
		$verificarSQL = "SELECT id FROM facturas WHERE idOrdenReparacion = '".$data['id']."' AND baja = 0";
		$facturaExistente = $this->db->query($verificarSQL);
		
		if ($facturaExistente) {
			// Ya existe una factura activa para esta orden, devolver el ID existente
			error_log("ADVERTENCIA: Intento de crear factura duplicada para orden " . $data['id'] . ". Factura existente ID: " . $facturaExistente['id']);
			return intval($facturaExistente['id']);
		}
		
		$sql = "INSERT INTO facturas VALUES(0,";//1. id 
		$sql.= "'".$data['idCliente']."', ";	//2. idCliente
		$sql.= "'".$data['id']."', "; 			//3. idOrdenReparacion
		$sql.= "'".$manoObra."', "; 			//4. manoObra
		$sql.= "'".$materiales."', "; 			//5. materiales
		$sql.= "'".$otro."', "; 				//6. otro
		$sql.= "'".$iva."', "; 					//7. iva
		$sql.= "'".$total."', "; 				//8. total
		$sql.= "'".$observacion."', "; 			//9. observacion
		//
		$sql.= "0, ";                   //10. baja
		$sql.= "NOW(), ";               //11. fecha alta
		$sql.= "'', ";                  //12. fecha baja 
		$sql.= "'')";                   //13. fecha cambio
		if($this->db->queryNoSelect($sql)){
			$salida = $this->db->query("SELECT LAST_INSERT_ID()");
			$salida = $salida["LAST_INSERT_ID()"];
		}
	   return $salida;
	}

	public function limpiarFacturasDuplicadas():array
	{
		$resultado = ["eliminadas" => 0, "errores" => []];
		
		// Encontrar facturas duplicadas por idOrdenReparacion
		$sql = "SELECT idOrdenReparacion, COUNT(*) as duplicados 
				FROM facturas 
				WHERE baja = 0 
				GROUP BY idOrdenReparacion 
				HAVING COUNT(*) > 1";
		
		$duplicados = $this->db->querySelect($sql);
		
		foreach ($duplicados as $duplicado) {
			try {
				// Para cada orden con duplicados, mantener solo la factura más reciente
				$sqlEliminar = "UPDATE facturas 
								SET baja = 1, baja_dt = NOW() 
								WHERE idOrdenReparacion = '".$duplicado['idOrdenReparacion']."' 
								AND baja = 0 
								AND id NOT IN (
									SELECT * FROM (
										SELECT MAX(id) 
										FROM facturas 
										WHERE idOrdenReparacion = '".$duplicado['idOrdenReparacion']."' 
										AND baja = 0
									) AS temp
								)";
				
				if ($this->db->queryNoSelect($sqlEliminar)) {
					$resultado["eliminadas"] += ($duplicado['duplicados'] - 1);
				} else {
					$resultado["errores"][] = "Error al eliminar duplicados para orden " . $duplicado['idOrdenReparacion'];
				}
			} catch (Exception $e) {
				$resultado["errores"][] = "Excepción al procesar orden " . $duplicado['idOrdenReparacion'] . ": " . $e->getMessage();
			}
		}
		
		return $resultado;
	}

	public function recalcularTotalesFacturas():array
	{
		$resultado = ["corregidas" => 0, "errores" => []];
		
		// Encontrar facturas con totales incorrectos
		$sql = "SELECT id, manoObra, materiales, otro, iva, total 
				FROM facturas 
				WHERE baja = 0 
				AND total != (manoObra + materiales + otro + iva)";
		
		$facturasIncorrectas = $this->db->querySelect($sql);
		
		foreach ($facturasIncorrectas as $factura) {
			$totalCorrecto = $factura['manoObra'] + $factura['materiales'] + $factura['otro'] + $factura['iva'];
			
			$sqlCorregir = "UPDATE facturas 
							SET total = '".$totalCorrecto."', 
								cambio_dt = NOW() 
							WHERE id = '".$factura['id']."'";
			
			if ($this->db->queryNoSelect($sqlCorregir)) {
				$resultado["corregidas"]++;
			} else {
				$resultado["errores"][] = "Error al corregir factura ID " . $factura['id'];
			}
		}
		
		return $resultado;
	}

	public function bajaLogica(string $id):bool
	{
		$salida = false;
		$sql = "UPDATE vehiculos SET baja=1, baja_dt=(NOW()) WHERE id=".$id;
		$salida = $this->db->queryNoSelect($sql);
		return $salida;
	}

	public function cambiarEstadoOrdenReparacion($id='')
	{
		$sql = "UPDATE ordenreparacion SET estado=".ORDEN_FACTURADA." ";
		$sql.= "WHERE id=".$id;
		return $this->db->queryNoSelect($sql);
	}

	public function getClientes():array
	{
		$sql = "SELECT id, CONCAT(nombres,' ',apellidos,', ',razonSocial) as cliente ";
		$sql.= "FROM clientes WHERE baja=0 AND estado=".CLIENTE_ACTIVO." ";
		$sql.= "ORDER BY nombres, apellidos, razonSocial";
		return $this->db->querySelect($sql);
	}


	public function getId(string $id=''):array
	{
		if(empty($id)) return [];
		$sql = "SELECT id, marca, modelo, color, ";
		$sql.= "anio, placas, idCliente ";
		$sql.= "FROM vehiculos ";
		$sql.= "WHERE id=? AND baja=0";
		return $this->db->query($sql, [$id]);
	}

	public function getNumRegistros(string $tabla):int
	{
		$sql = "SELECT COUNT(*) FROM ".$tabla." WHERE baja=0";
		$salida = $this->db->query($sql);
		return $salida["COUNT(*)"];
	}

	public function getTablaOrdenReparacion(int $inicio=1, int $tamano=0):array
	{
		$sql = "SELECT o.id, o.idVehiculo, o.fechaIngreso, o.fechaSalida,  ";
		$sql.= "CONCAT(v.marca,' ',v.modelo,' ',v.anio) as vehiculo, e.estado, o.estado as edo ";
		$sql.= "FROM ordenreparacion as o, vehiculos as v, estadoordenreparacion as e ";
		$sql.= "WHERE o.baja=0 AND ";
		$sql.= "o.idVehiculo=v.id AND ";
		$sql.= "o.estado=e.id ";
		if ($tamano>0) {
			$sql.= " LIMIT ".$inicio.", ".$tamano;
		}
		return $this->db->querySelect($sql);
	}

	public function getOrdenReparacion(string $idOrdenReparacion=''):array
	{
		if(empty($idOrdenReparacion)) return [];
		$sql = "SELECT  o.id, o.fechaIngreso, o.fechaSalida, o.kilometraje, ";
		$sql.= "c.id as idCliente, c.ruc, v.id as idVehiculo, ";
		$sql.= "c.nombres, c.apellidos, c.razonsocial, c.direccion, c.correo, c.telefono,";
		$sql.= "v.marca, v.modelo, v.color, v.anio, v.placas ";
		$sql.= "FROM ordenreparacion as o, clientes as c, vehiculos as v ";
		$sql.= "WHERE o.id=".$idOrdenReparacion." AND ";
		$sql.= "o.idVehiculo=v.id AND ";
		$sql.= "v.idCliente=c.id";
		return $this->db->query($sql);
	}

	public function getPiezas(string $idOrdenReparacion=''):array
	{
		if(empty($idOrdenReparacion)) return [];
		$sql = "SELECT  a.idOrdenReparacion, p.nombrePieza, d.cantidad, p.costo ";
		$sql.= "FROM ordenalmacen as a, ordenalmacendetalle as d, piezas as p ";
		$sql.= "WHERE a.idOrdenReparacion=".$idOrdenReparacion." AND ";
		$sql.= "a.id=d.idOrdenAlmacen AND ";
		$sql.= "d.idPieza=p.id";
		return $this->db->querySelect($sql);
	}

	public function getRazonSocial():array
	{
		$sql = "SELECT * ";
		$sql.= "FROM configuracion ";
		$sql.= "WHERE id=1";
		return $this->db->query($sql);
	}

	public function modificar(array $data):bool
	{
		$salida = false;
	    if (!empty($data["id"])) {
		    $sql = "UPDATE vehiculos SET "; 
			$sql.= "marca='".$data['marca']."', ";
			$sql.= "modelo='".$data['modelo']."', ";
			$sql.= "color='".$data['color']."', ";
			$sql.= "anio='".$data['anio']."', ";
			$sql.= "placas='".$data['placas']."', ";
			$sql.= "idCliente='".$data['idCliente']."', ";;
			$sql.= "cambio_dt=(NOW()) ";
			$sql.= "WHERE id=".$data['id'];
		    //Enviamos a la base de datos
		    $salida = $this->db->queryNoSelect($sql);
	    }
	    return $salida;
	}
}

?>