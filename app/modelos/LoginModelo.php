<?php
/**
 * Modelo de Login - Corregido para Cloud SQL
 */
class LoginModelo
{
    private $db = "";
    
    function __construct()
    {
        $this->db = new MySQLdb();
    }
    
    public function actualizarClaveAcceso(array $data = []): bool
    {
        if (!empty($data)) {
            $sql = "UPDATE usuarios SET clave=:clave, estadoUsuario=:estadoUsuario WHERE id=:id";
            return $this->db->queryNoSelect($sql, $data);
        }
        return false;
    }
    
    public function actualizarClaveCliente(array $data = []): bool
    {
        if (!empty($data)) {
            $sql = "UPDATE clientes SET clave=:clave WHERE id=:id";
            return $this->db->queryNoSelect($sql, $data);
        }
        return false;
    }
    
    public function actualizarClaveMecanico(array $data = []): bool
    {
        if (!empty($data)) {
            $sql = "UPDATE mecanicos SET clave=:clave WHERE id=:id";
            return $this->db->queryNoSelect($sql, $data);
        }
        return false;
    }
    
    public function actualizarLogin(string $tabla, string $id = ''): bool
    {
        if (!empty($id)) {
            $sql = "UPDATE " . $tabla . " SET login_dt=NOW() WHERE id=:id";
            return $this->db->queryNoSelect($sql, ['id' => $id]);
        }
        return false;
    }
    
    public function buscarCorreo(string $correo = ''): array
    {
        if ($correo == "") return [];
        
        $sql = "SELECT id, tipoUsuario, nombres, apellidos, direccion, 
                       telefono, correo, clave, genero, estadoUsuario 
                FROM usuarios 
                WHERE correo = :correo AND baja=0";
        return $this->db->query($sql, ['correo' => $correo]);
    }
    
    public function buscarCorreoMecanico(string $correo = ''): array
    {
        if ($correo == "") return [];
        
        $sql = "SELECT id, nombres, apellidos, correo, clave, telefono, idTipoMecanico, estado 
                FROM mecanicos 
                WHERE correo = :correo AND baja=0";
                
        return $this->db->query($sql, ['correo' => $correo]);
    }
    
    public function buscarCorreoCliente(string $correo = ''): array
    {
        if ($correo == "") return [];
        
        $sql = "SELECT c.id, c.nombres, c.apellidos, c.razonSocial, c.direccion, c.telefono, c.ruc, c.correo, c.clave, ec.estado 
                FROM clientes as c, estadocliente as ec
                WHERE c.correo = :correo 
                AND c.baja = 0 
                AND c.id_estado_cliente = ec.id";
        return $this->db->query($sql, ['correo' => $correo]);
    }
}
?>
