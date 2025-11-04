<?php  
/**
 * 
 */
class TableroModelo
{
	private $db="";
	
	function __construct()
	{
		$this->db = new MySQLdb();
	}

	public function getKpis(): array
	{
		$kpis = [
			"ordenes_abiertas" => 0,
			"ordenes_facturadas" => 0,
			"ordenes_totales" => 0,
			"ingresos_mes" => 0.0
		];
		// Ordenes abiertas
		$r = $this->db->query("SELECT COUNT(*) AS c FROM ordenreparacion WHERE baja=0 AND estado=".ORDEN_ABIERTA);
		$kpis["ordenes_abiertas"] = isset($r["c"]) ? intval($r["c"]) : 0;
		// Ordenes facturadas
		$r = $this->db->query("SELECT COUNT(*) AS c FROM ordenreparacion WHERE baja=0 AND estado=".ORDEN_FACTURADA);
		$kpis["ordenes_facturadas"] = isset($r["c"]) ? intval($r["c"]) : 0;
		// Ordenes totales
		$r = $this->db->query("SELECT COUNT(*) AS c FROM ordenreparacion WHERE baja=0");
		$kpis["ordenes_totales"] = isset($r["c"]) ? intval($r["c"]) : 0;
		// Ingresos del mes (desde facturas: incluye materiales + mano de obra + otros + IVA)
		$r = $this->db->query("SELECT IFNULL(SUM(total),0) AS s FROM facturas WHERE baja=0 AND DATE_FORMAT(alta_dt,'%Y-%m')=DATE_FORMAT(CURDATE(),'%Y-%m')");
		$kpis["ingresos_mes"] = isset($r["s"]) ? floatval($r["s"]) : 0.0;
		return $kpis;
	}

	public function getIngresosMensuales(int $meses = 6): array
	{
		// Ahora usa la tabla facturas para incluir el total real (materiales + mano de obra + otros + IVA)
		$sql = "SELECT DATE_FORMAT(alta_dt,'%Y-%m') as ym, SUM(total) as total ".
			   "FROM facturas WHERE baja=0 AND alta_dt >= DATE_SUB(CURDATE(), INTERVAL ".$meses." MONTH) ".
			   "GROUP BY ym ORDER BY ym ASC";
		$rows = $this->db->querySelect($sql);
		$labels = [];
		$data = [];
		foreach ($rows as $row) {
			$labels[] = $row['ym'];
			$data[] = floatval($row['total']);
		}
		return ["labels"=>$labels, "data"=>$data];
	}

	public function getTablas()
	{
		return $this->db->querySelect("SHOW TABLES");
	}

	public function respaldarTabla($tabla='',$fecha="",$id="")
	{
		if (empty($tabla) || empty($fecha)) return false;
		$db = $this->db->getBaseDatos();
		$salida = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `" . $db . "`\r\n--\r\n\r\n";
		$datos = $this->db->queryCrudo("SELECT * FROM ".$tabla);
		$campos = $datos->columnCount();
		$filas = $datos->rowCount();
		$esquemaTabla = $this->db->querySelect("SHOW CREATE TABLE ".$tabla);
		$esquema = $esquemaTabla[0]["Create Table"];
		$salida.= "\n\n".$esquema.";\n\n";
		$contador = 0;
		while ($fila = $datos->fetch(PDO::FETCH_NUM)) {
			// verificamos contador
			if ($contador%100==0||$contador==0) {
				if ($contador > 0) {
					$salida .= ";\n";
				}
				$salida .= "\nINSERT INTO ".$tabla." VALUES";
			}
			$salida .= "\n(";
			for ($j=0; $j < $campos; $j++) { 
				// Verificar que el índice existe antes de acceder
				$valorCampo = isset($fila[$j]) ? $fila[$j] : null;
				if ($valorCampo !== null) {
					$salida .= '"'.addslashes($valorCampo).'"';
				} else {
					$salida .= 'NULL';
				}
				if($j < ($campos-1)){
					$salida .= ',';
				}
			}
			$salida .= ")";
			//cada 100
			if ((($contador + 1)%100==0 && $contador !=0) || $contador+1==$filas) {
				$salida .= ";";
			} else {
				$salida .= ",";
			}
			$contador++;
		}
		$salida .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
		$carpeta = "respaldos/".$fecha."-".$id;
		if (!file_exists($carpeta)) {
			mkdir($carpeta);
		}
		$archivo = sprintf('%s/%s.sql',$carpeta,$tabla);
		return file_put_contents($archivo, $salida) !== false;
	}

	public function setUsuario($id, $nombres, $apellidos, $clave)
	{
		$sql = "UPDATE usuarios SET ";
		$sql.= "nombres='".$nombres."', ";
		$sql.= "apellidos='".$apellidos."' ";
		if ($clave!="") {
			$clave = hash_hmac("sha512", $clave, CLAVE);
			$sql.= ", clave='".$clave."' ";
		}
		$sql.= "WHERE id=".$id;
		return $this->db->queryNoSelect($sql);
	}

	public function getUsuarioId($id='')
	{
		$sql = "SELECT * FROM usuarios WHERE id=? AND baja=0";
		return $this->db->query($sql, [$id]);
	}
}

?>