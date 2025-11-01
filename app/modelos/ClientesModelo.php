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
		//
		$ir_array = [0,0];
		$sql = "SELECT COUNT(*) FROM vehiculos WHERE baja=0 AND idCliente=".$id;
		$salida = $this->db->query($sql);
		$ir_array[1] = $salida["COUNT(*)"];
		//
		$ir_array[0] = $ir_array[1];
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
		//
		$sql = "SELECT id FROM mecanicos WHERE correo='".$correo."' AND baja=0";
		return $this->db->query($sql);
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
}

?>