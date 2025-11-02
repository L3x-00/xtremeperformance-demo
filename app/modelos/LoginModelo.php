<?php
/**
 *
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
        // ... (Esta función ya era segura, no necesita cambios)
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

    public function actualizarLogin(string $id = '', string $tabla): bool
    {
        // Corregido para ser seguro
        if (!empty($id)) {
            // Se usa un placeholder para el ID para prevenir inyección SQL
            $sql = "UPDATE " . $tabla . " SET login_dt=NOW() WHERE id=:id";
            return $this->db->queryNoSelect($sql, ['id' => $id]);
        }
        return false;
    }

    public function buscarCorreo(string $correo = ''): array
    {
        if ($correo == "") return [];
        
        // Corregido para ser seguro
        $sql = "SELECT id, tipoUsuario, nombres, apellidos, direccion, 
                       telefono, correo, clave, genero, estadoUsuario 
                FROM usuarios 
                WHERE correo = :correo AND baja=0";

        return $this->db->query($sql, ['correo' => $correo]);
    }

    public function buscarCorreoMecanico(string $correo = ''): array
    {
        if ($correo == "") return [];

        // Corregido para ser seguro
        // Nota: Asume que la tabla 'mecanicos' SÍ tiene una columna 'estado'. Si no, hay que cambiarlo.
        $sql = "SELECT id, nombres, apellidos, correo, clave, telefono, idTipoMecanico, estado 
                FROM mecanicos 
                WHERE correo = :correo AND baja=0";
                
        return $this->db->query($sql, ['correo' => $correo]);
    }

    public function buscarCorreoCliente(string $correo = ''): array
    {
        if ($correo == "") return [];
        
        // ----> FUNCIÓN TOTALMENTE CORREGIDA <----
        // 1. Usa JOIN para obtener el nombre del estado.
        // 2. Selecciona la columna correcta (ec.estado).
        // 3. Usa placeholders para ser segura.
    $sql = "SELECT c.id, c.nombres, c.apellidos, c.razonSocial, c.direccion, c.telefono, c.ruc, c.correo, c.clave, ec.estado 
        FROM clientes as c, estadocliente as ec
                WHERE c.correo = :correo 
                AND c.baja = 0 
                AND c.id_estado_cliente = ec.id";

        return $this->db->query($sql, ['correo' => $correo]);
    }
}
?>