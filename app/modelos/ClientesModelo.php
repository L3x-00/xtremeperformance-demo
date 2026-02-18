<?php  
/**
 * 
 */
class ClientesModelo
{
	private $db="";
	
	function __construct()
	{
		$this->db = new MySQLdb();
	}

	// C:\xampp\htdocs\taller\app\modelos\ClientesModelo.php


public function alta(array $data): int
{
    // CORRECTO: Tiene 9 columnas y 9 placeholders, incluyendo la clave
    $sql = "INSERT INTO clientes (
                nombres, 
                apellidos, 
                razonSocial, 
                direccion, 
                telefono, 
                ruc, 
                correo,
                clave,
                id_estado_cliente 
            ) VALUES (
                :nombres, 
                :apellidos, 
                :razonSocial, 
                :direccion, 
                :telefono, 
                :ruc, 
                :correo,
                :clave,
                :id_estado_cliente
            )";
    
    return $this->db->queryNoSelect($sql, $data);
}
	public function bajaLogica(string $id):bool
	{
		$salida = false;
		$sql = "UPDATE clientes SET baja=1, baja_dt=(NOW()) WHERE id=".$id;
		$salida = $this->db->queryNoSelect($sql);
		return $salida;
	}

	public function eliminarFisico(string $id):bool
	{
		error_log("MODELO DEBUG: eliminarFisico llamado con ID: '$id'");
		
		if (empty($id) || !is_numeric($id)) {
			error_log("MODELO DEBUG: ID inválido (vacío o no numérico)");
			return false;
		}
		
		// Primero verificar si el cliente existe
		$existe = "SELECT id, baja FROM clientes WHERE id = ?";
		$cliente = $this->db->querySelect($existe, [$id]);
		error_log("MODELO DEBUG: Cliente existe: " . json_encode($cliente));
		
		$sql = "DELETE FROM clientes WHERE id = ? AND baja = 0";
		$data = [$id];
		error_log("MODELO DEBUG: Ejecutando SQL: $sql con datos: " . json_encode($data));
		
		$resultado = $this->db->queryNoSelect($sql, $data);
		error_log("MODELO DEBUG: Resultado de eliminación: " . ($resultado ? 'true' : 'false'));
		
		// Verificar si realmente se eliminó
		$verificar = "SELECT COUNT(*) as count FROM clientes WHERE id = ?";
		$cuenta = $this->db->querySelect($verificar, [$id]);
		error_log("MODELO DEBUG: Clientes restantes con ese ID: " . json_encode($cuenta));
		
		return $resultado;
	}



public function getId(string $id = ''): array
{
    if (empty($id)) return [];

    // Consulta corregida con JOIN para obtener el nombre del estado
    $sql = "SELECT c.*, ec.estado 
            FROM clientes as c, estadocliente as ec 
            WHERE c.id = :id 
            AND c.baja = 0 
            AND c.id_estado_cliente = ec.id";

    // Usamos un array para pasar el ID de forma segura
    $data = ['id' => $id];
    
    // Asumiendo que tu función query() puede manejar parámetros
    // Si no, necesitarás una función específica para esto.
    // Por ahora, lo adaptamos a como probablemente funciona tu librería.
    $sql = "SELECT c.*, ec.estado 
            FROM clientes as c, estadocliente as ec 
            WHERE c.id = '".$id."' 
            AND c.baja = 0 
            AND c.id_estado_cliente = ec.id";

    return $this->db->query($sql);
}

	public function getIntegridadReferencial($id)
	{
		$ir_array = array(
			'total' => 0,
			'vehiculos' => 0,
			'salidas' => 0,
			'facturas' => 0,
			'seguimientos' => 0
		);
		
		// Verificar vehículos
		$sql = "SELECT COUNT(*) as cnt FROM vehiculos WHERE baja=0 AND idCliente=".$id;
		$salida = $this->db->query($sql);
		if (!empty($salida)) {
			$ir_array['vehiculos'] = isset($salida['cnt']) ? $salida['cnt'] : $salida['COUNT(*)'];
		}
		
		// Verificar salidas
		$sql = "SELECT COUNT(*) as cnt FROM salidas WHERE baja=0 AND idCliente=".$id;
		$salida = $this->db->query($sql);
		if (!empty($salida)) {
			$ir_array['salidas'] = isset($salida['cnt']) ? $salida['cnt'] : $salida['COUNT(*)'];
		}
		
		// Verificar facturas
		$sql = "SELECT COUNT(*) as cnt FROM facturas WHERE baja=0 AND idCliente=".$id;
		$salida = $this->db->query($sql);
		if (!empty($salida)) {
			$ir_array['facturas'] = isset($salida['cnt']) ? $salida['cnt'] : $salida['COUNT(*)'];
		}
		
		// Verificar seguimientos
		$sql = "SELECT COUNT(*) as cnt FROM seguimientos WHERE baja=0 AND idCliente=".$id;
		$salida = $this->db->query($sql);
		if (!empty($salida)) {
			$ir_array['seguimientos'] = isset($salida['cnt']) ? $salida['cnt'] : $salida['COUNT(*)'];
		}
		
		// Calcular total
		$ir_array['total'] = $ir_array['vehiculos'] + $ir_array['salidas'] + $ir_array['facturas'] + $ir_array['seguimientos'];
		
		return $ir_array;
	}

	public function getNumRegistros():int
	{
		$sql = "SELECT COUNT(*) FROM clientes WHERE baja=0";
		$salida = $this->db->query($sql);
		return $salida["COUNT(*)"];
	}

	public function getTabla(int $inicio=1, int $tamano=0):array
{
  // Asegurar que $inicio no sea negativo
  $inicio = max(0, $inicio);
  
  // La consulta SQL correcta para unir las dos tablas
  $sql = "SELECT c.id, CONCAT(c.apellidos,', ',c.nombres) as nombre, ";
  $sql.= "c.razonSocial, ec.estado ";
  $sql.= "FROM clientes as c, estadocliente as ec ";
  $sql.= "WHERE c.baja=0 AND "; // Condición 1: El cliente no está dado de baja
  $sql.= "c.id_estado_cliente = ec.id "; // Condición 2: Une las tablas correctamente

  if ($tamano > 0) {
    $sql.= " LIMIT ".$inicio.", ".$tamano;
  }

  return $this->db->querySelect($sql);
}

	public function getEstadoCliente()
	{
		//
		$sql = "SELECT id, estado FROM estadocliente";
		return $this->db->querySelect($sql);
	}

	public function getCorreo(string $correo=""):array
	{
        // Check if the email exists in clientes or usuarios (avoid duplicate emails)
        if (empty($correo)) return [];
        $sql = "SELECT id FROM clientes WHERE correo=? AND baja=0";
        $salida = $this->db->query($sql, [$correo]);
        if (!empty($salida)) return $salida;
        // If not found in clientes, check usuarios
        $sql2 = "SELECT id FROM usuarios WHERE correo=? AND baja=0";
        return $this->db->query($sql2, [$correo]);
	}

	// C:\xampp\htdocs\taller\app\modelos\ClientesModelo.php

public function modificar(array $data): bool
{
    if (empty($data["id"])) {
        return false;
    }

    // Consulta SQL segura usando placeholders
    $sql = "UPDATE clientes SET 
                nombres = :nombres,
                apellidos = :apellidos,
                telefono = :telefono,
                correo = :correo,
                id_estado_cliente = :id_estado_cliente,
                direccion = :direccion,
                razonSocial = :razonSocial,
                ruc = :ruc,
                cambio_dt = NOW()
            WHERE id = :id";

    // Enviamos la consulta y los datos a la base de datos de forma segura
    return $this->db->queryNoSelect($sql, $data);
}

	public function getTodos(): array
	{
		// Consulta para obtener todos los clientes activos con su estado
		$sql = "SELECT c.id, c.nombres, c.apellidos, c.telefono, c.correo, 
				c.direccion, c.ruc, c.razonSocial, ec.estado 
				FROM clientes c 
				INNER JOIN estadocliente ec ON c.id_estado_cliente = ec.id 
				WHERE c.baja = 0 
				ORDER BY c.apellidos, c.nombres";
		
		return $this->db->querySelect($sql);
	}

	public function getEstadisticasCliente(string $id): array
	{
		// Optimización: Una sola consulta que obtiene todas las estadísticas
		$sql = "SELECT 
				-- Contar vehículos
				(SELECT COUNT(*) FROM vehiculos WHERE idCliente = :id AND baja = 0) as total_vehiculos,
				
				-- Estadísticas de órdenes en una subconsulta
				COALESCE(ord_stats.total_ordenes, 0) as total_ordenes,
				COALESCE(ord_stats.ordenes_completadas, 0) as ordenes_completadas, 
				COALESCE(ord_stats.ultima_visita, 'Nunca') as ultima_visita,
				COALESCE(ord_stats.gasto_total, 0) as gasto_total
				
				FROM (SELECT 1) dummy
				LEFT JOIN (
					SELECT 
						COUNT(or_rep.id) as total_ordenes,
						SUM(CASE WHEN or_rep.fechaSalida IS NOT NULL THEN 1 ELSE 0 END) as ordenes_completadas,
						MAX(or_rep.fechaIngreso) as ultima_visita,
						COALESCE(SUM(oa.costo), 0) as gasto_total
					FROM ordenreparacion or_rep 
					INNER JOIN vehiculos v ON or_rep.idVehiculo = v.id 
					LEFT JOIN ordenalmacen oa ON oa.idOrdenReparacion = or_rep.id
					WHERE v.idCliente = :id
				) ord_stats ON 1=1";
		
		$resultado = $this->db->querySelect($sql, ['id' => $id]);
		
		if (!empty($resultado)) {
			return [
				'total_vehiculos' => (int)$resultado[0]['total_vehiculos'],
				'total_ordenes' => (int)$resultado[0]['total_ordenes'], 
				'ordenes_completadas' => (int)$resultado[0]['ordenes_completadas'],
				'ultima_visita' => $resultado[0]['ultima_visita'],
				'gasto_total' => (float)$resultado[0]['gasto_total']
			];
		}
		
		// Fallback si no hay datos
		return [
			'total_vehiculos' => 0,
			'total_ordenes' => 0, 
			'ordenes_completadas' => 0,
			'ultima_visita' => 'Nunca',
			'gasto_total' => 0
		];
	}
}

?>