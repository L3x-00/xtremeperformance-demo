<?php  
/**
 * 
 */
class TableroClienteModelo
{
	private $db="";
	
	function __construct()
	{
		$this->db = new MySQLdb();
	}

	public function getNumRegistros(string $tabla, string $id):int
	{
		$sql = "SELECT COUNT(*) FROM ".$tabla." WHERE baja=0 AND idCliente=".$id;
		$salida = $this->db->query($sql);
		return $salida["COUNT(*)"];
	}

	public function getId(string $id=''):array
	{
		if(empty($id)) return [];
		$sql = "SELECT o.id, o.idVehiculo, o.idMecanico, o.fechaIngreso, o.fechaSalida, ";
		$sql.= "o.kilometraje, o.gato, o.herramientas, o.triangulos, o.refaccion, o.extintor, ";
		$sql.= "o.antena, o.emblemas, o.tapones, o.cables, o.estereo, o.encendedor, o.tapetes, ";
		$sql.= "o.estado, CONCAT(v.marca,' ',v.modelo,' ',v.anio) as vehiculo, ";
		$sql.= "CONCAT(m.nombres,' ',m.apellidos) as mecanico, e.estado as edo ";
		$sql.= "FROM ordenreparacion as o, vehiculos as v, mecanicos as m, estadoordenreparacion as e ";
		$sql.= "WHERE o.id=? AND o.baja=0 AND o.estado=e.id AND ";
		$sql.= "o.idVehiculo=v.id AND o.idMecanico=m.id";
		return $this->db->query($sql, [$id]);
	}

	public function getSeguimientoId(string $id=''):array
	{
		if(empty($id)) return [];
		$sql = "SELECT id, idOrdenReparacion, fecha, observacion ";
		$sql.= "FROM seguimientos ";
		$sql.= "WHERE id=? AND baja=0";
		return $this->db->query($sql, [$id]);
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

	public function getSeguimientos(string $idOrdenReparacion):array
	{
		$sql = "SELECT s.id, s.fecha, SUBSTRING(s.observacion, 1, 50) as observacion, ";
		$sql.= "CONCAT(v.marca,' ',v.modelo,' ',v.anio) as vehiculo ";
		$sql.= "FROM seguimientos as s, ordenreparacion as o, vehiculos as v ";
		$sql.= "WHERE s.idOrdenReparacion=o.id AND ";
		$sql.= "o.idVehiculo=v.id AND s.baja=0 AND s.idOrdenReparacion=".$idOrdenReparacion;
		return $this->db->querySelect($sql);
	}

	public function getTablaOrdenReparacion(string $id):array
	{
		$sql = "SELECT o.id, o.idVehiculo, o.fechaIngreso, o.fechaSalida,  ";
		$sql.= "CONCAT(v.marca,' ',v.modelo,' ',v.anio) as vehiculo, e.estado, o.estado as edo ";
		$sql.= "FROM clientes as c, ordenreparacion as o, vehiculos as v, estadoordenreparacion as e ";
		$sql.= "WHERE o.baja=0 AND c.id=".$id." AND ";
		$sql.= "o.idVehiculo=v.id AND ";
		$sql.= "o.estado=e.id AND v.idCliente=c.id";
		return $this->db->querySelect($sql);
	}

	public function getKpis(string $idCliente): array
	{
		$kpis = [
			"activas" => 0,
			"totales" => 0,
			"gasto_total" => 0.0,
			"gasto_mes" => 0.0,
		];
		// Activas (abiertas)
		$sql = "SELECT COUNT(*) AS c FROM clientes c, vehiculos v, ordenreparacion o WHERE c.id=".$idCliente." AND v.idCliente=c.id AND o.idVehiculo=v.id AND o.baja=0 AND o.estado=".ORDEN_ABIERTA;
		$row = $this->db->query($sql);
		$kpis["activas"] = intval($row['c'] ?? 0);
		// Totales
		$sql = "SELECT COUNT(*) AS c FROM clientes c, vehiculos v, ordenreparacion o WHERE c.id=".$idCliente." AND v.idCliente=c.id AND o.idVehiculo=v.id AND o.baja=0";
		$row = $this->db->query($sql);
		$kpis["totales"] = intval($row['c'] ?? 0);
		// Gasto total (suma desde tabla facturas que incluye materiales + mano de obra + otros + iva)
		// Si no hay factura aún para una orden, esa orden no suma al gasto
		$sql = "SELECT COALESCE(SUM(f.total),0) AS s FROM clientes c, vehiculos v, ordenreparacion o LEFT JOIN facturas f ON f.idOrdenReparacion=o.id AND f.baja=0 WHERE c.id=".$idCliente." AND v.idCliente=c.id AND o.idVehiculo=v.id AND o.baja=0";
		$row = $this->db->query($sql);
		$kpis["gasto_total"] = floatval($row['s'] ?? 0);
		// Gasto del mes (por fecha de alta de factura)
		$sql = "SELECT COALESCE(SUM(f.total),0) AS s FROM clientes c, vehiculos v, ordenreparacion o LEFT JOIN facturas f ON f.idOrdenReparacion=o.id AND f.baja=0 WHERE c.id=".$idCliente." AND v.idCliente=c.id AND o.idVehiculo=v.id AND o.baja=0 AND MONTH(f.alta_dt)=MONTH(CURDATE()) AND YEAR(f.alta_dt)=YEAR(CURDATE())";
		$row = $this->db->query($sql);
		$kpis["gasto_mes"] = floatval($row['s'] ?? 0);
		return $kpis;
	}

	public function setUsuario($id, $nombres, $apellidos, $clave)
	{
		$sql = "UPDATE clientes SET ";
		$sql.= "nombres='".$nombres."', ";
		$sql.= "apellidos='".$apellidos."' ";
		if ($clave!="") {
			$sql.= ", clave='".$clave."' ";
		}
		$sql.= "WHERE id=".$id;
		return $this->db->queryNoSelect($sql);
	}

	public function getUsuarioId($id='')
	{
		$sql = "SELECT * FROM clientes WHERE id=? AND baja=0";
		return $this->db->query($sql, [$id]);
	}
}

?>