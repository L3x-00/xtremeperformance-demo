<?php  
/**
 * 
 */
class OrdenAlmacenModelo
{
	private $db="";
	
	function __construct()
	{
		$this->db = new MySQLdb();
	}

	public function altaOrdenAlmacen(string $idOrdenReparacion='',string $observacion=''):int
	{
		//
		$salida = 0;
		$sql = "INSERT INTO ordenalmacen VALUES(0,"; //1. id
		$sql.= "'".$idOrdenReparacion."', ";//2. tipo movimiento
		$sql.= "0, ";	 					//3. costo
		$sql.= "'".$observacion."', ";		//4. cantidad
		//
		$sql.= "0, ";                   	 //5. baja
		$sql.= "NOW(), ";               	 //6. fecha alta
		$sql.= "'', ";                  	 //7. fecha baja 
		$sql.= "'')";                   	 //8. fecha cambio
		if($this->db->queryNoSelect($sql)){
			$salida = $this->db->query("SELECT LAST_INSERT_ID()");
			$salida = $salida["LAST_INSERT_ID()"];
		}
		return $salida;
	}

	public function altaOrdenAlmacenDetalle(array $data,array $pieza):bool
	{
		$sql = "INSERT INTO ordenalmacendetalle VALUES(0,"; //1. id
		$sql.= "'".$data["id"]."', ";		//2. idOrdenAlmacen
		$sql.= "'".$pieza["id"]."', ";		//3. idPieza
		$sql.= "'".$data["cantidad"]."', ";	//4. cantidad
		$sql.= "'".$data["costo"]."')";		//5. costo
		return $this->db->queryNoSelect($sql);
	}

	public function actualizarTotal(string $idOrdenAlmacen,float $total):bool
	{
		$sql = "UPDATE ordenalmacen ";
		$sql.= "SET costo=".$total." ";
		$sql.= "WHERE id=".$idOrdenAlmacen;
		return $this->db->queryNoSelect($sql);
	}

	public function actualizarInventario(string $idPieza,float $cantidad):bool
	{
		$sql = "UPDATE piezas ";
		$sql.= "SET stock=stock-".$cantidad;
		$sql.= " WHERE id=".$idPieza;
		return $this->db->queryNoSelect($sql);
	}

	public function bajaLogica(string $id):bool
	{
		$salida = false;
		$sql = "UPDATE ordenalmacen SET baja=1, baja_dt=(NOW()) WHERE id=".$id;
		$salida = $this->db->queryNoSelect($sql);
		return $salida;
	}

	public function borrarPiezasOrdenAlmacen(string $idOrdenAlmacen):bool
	{
		$sql = "DELETE FROM ordenalmacendetalle WHERE idOrdenAlmacen=".$idOrdenAlmacen;
		return $this->db->queryNoSelect($sql);
	}

	public function bajaPiezaLogica(string $id):bool
	{
		$sql = "DELETE FROM ordenalmacendetalle WHERE id=".$id;
		return $this->db->queryNoSelect($sql);
	}

	public function calcularTotal(string $idOrdenAlmacen):float
	{
		$sql = "SELECT SUM(o.costo) as total ";
		$sql.= "FROM ordenalmacendetalle as o ";
		$sql.= "WHERE o.idOrdenAlmacen=".$idOrdenAlmacen;
		$salida = $this->db->query($sql);
		return $salida["total"];
	}


	public function getId(string $id=''):array
	{
		if(empty($id)) return [];
		$sql = "SELECT id, idOrdenReparacion, costo, observacion, alta_dt  ";
		$sql.= "FROM ordenalmacen ";
		$sql.= "WHERE id='".$id."' AND baja=0";
		return $this->db->query($sql);
	}

	public function getOrdenAlmacenDetalle(string $idOrdenAlmacen=''):array
	{
		$sql = "SELECT o.id, o.idOrdenAlmacen, o.idPieza, o.cantidad, ";
		$sql.= "p.nombrePieza, p.costo ";
		$sql.= "FROM ordenalmacendetalle as o, piezas as p ";
		$sql.= "WHERE o.idOrdenAlmacen=".$idOrdenAlmacen." AND ";
		$sql.= "o.idPieza=p.id";
		return $this->db->querySelect($sql);
	}

	public function getOrdenesReparacion()
	{
		// Importante: alias distintos para evitar colisión de nombres de columna (id)
		// Si se seleccionan dos columnas 'id' sin alias, el último 'id' (v.id) puede
		// sobreescribir al primero (o.id) en el arreglo asociativo de PHP, provocando
		// que se envíe el id del vehículo en lugar del id de la orden.
		$sql = "SELECT o.id AS id, v.id AS idVehiculo, ";
		$sql.= "CONCAT(v.marca,' ',v.modelo,' ',v.color,' ',v.anio) AS auto ";
		$sql.= "FROM ordenreparacion AS o, vehiculos AS v ";
		$sql.= "WHERE o.baja=0 AND o.estado=".ORDEN_ABIERTA;
		$sql.= " AND o.idVehiculo=v.id";
		return $this->db->querySelect($sql);
	}

	public function getPiezas():array
	{
		$sql = "SELECT id, nombrePieza, stock ";
		$sql.= "FROM piezas ";
		$sql.= "WHERE baja=0 AND stock > 0";
		return $this->db->querySelect($sql);
	}

	public function getPieza(string $id=''):array
	{
		if(empty($id)) return [];
		$sql = "SELECT  id, nombrePieza, stock, costo ";
		$sql.= "FROM piezas ";
		$sql.= "WHERE id='".$id."' AND baja=0";
		return $this->db->query($sql);
	}

	public function getPiezaDetalle(string $id=''):array
	{
		if(empty($id)) return [];
		$sql = "SELECT o.id, o.idOrdenAlmacen, o.idPieza, o.cantidad, o.costo, ";
		$sql.= "p.nombrePieza, a.idOrdenReparacion ";
		$sql.= "FROM ordenalmacendetalle as o, ordenalmacen as a, piezas as p ";
		$sql.= "WHERE o.id='".$id."' AND ";
		$sql.= "o.idOrdenAlmacen = a.id AND ";
		$sql.= "o.idPieza = p.id";
		return $this->db->query($sql);
	}

	public function getNumRegistros():int
	{
		$sql = "SELECT COUNT(*) FROM ordenalmacen WHERE baja=0";
		$salida = $this->db->query($sql);
		return $salida["COUNT(*)"];
	}

	public function getTabla(int $inicio=1, int $tamano=0):array
	{
		$sql = "SELECT o.id, o.idOrdenReparacion, o.costo, o.alta_dt, r.estado as idEstado, e.estado, ";
		$sql.= "CONCAT(o.idOrdenReparacion,') ',v.marca,' ',v.modelo,' ',v.anio) as vehiculo ";
		$sql.= "FROM ordenalmacen as o, ordenreparacion as r, vehiculos as v, estadoordenreparacion as e ";
		$sql.= "WHERE o.baja=0 AND ";
		$sql.= "o.idOrdenReparacion=r.id AND ";
		$sql.= "r.idVehiculo=v.id AND r.estado=e.id";
		if ($tamano>0) {
			$sql.= " LIMIT ".$inicio.", ".$tamano;
		}
		return $this->db->querySelect($sql);
	}

	public function regresarPiezasOrdenAlmacen(string $idPieza, string $cantidad):bool
	{
		$sql = "UPDATE piezas ";
		$sql.= "SET stock=stock+".intval($cantidad);
		$sql.= " WHERE id=".$idPieza;
		return $this->db->queryNoSelect($sql);
	}
}
?>