<?php
/**
 *
 */
class MySQLdb
{
    private $host = "localhost";
    private $usuario = "u645180384_maxi";
    private $clave = "Maxi.123@123";
    private $db = "u645180384_taller";
    private $puerto = "";
    private $conn;

    function __construct()
    {
        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db,
                $this->usuario,
                $this->clave
            );
        } catch (Exception $e) {
            die("No se pudo conectar: " . $e->getMessage());
        }
    }

    /**
     * Función MEJORADA: Ahora acepta consultas preparadas y seguras.
     * Devuelve solo la primera fila del resultado.
     */
    public function query(string $sql = '', array $data = []): array
    {
        if (empty($sql)) return [];

        if (empty($data)) {
            // Si no hay datos, es una consulta simple (comportamiento antiguo)
            $stmt = $this->conn->query($sql);
        } else {
            // Si hay datos, usamos una consulta preparada y segura
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
        }
        
        $salida = $stmt->fetch(PDO::FETCH_ASSOC);

        return $salida ? $salida : [];
    }

    /**
     * Función MEJORADA: Ahora acepta consultas preparadas y seguras.
     * Devuelve todas las filas del resultado.
     */
    public function querySelect(string $sql = '', array $data = []): array
    {
        if (empty($sql)) return [];
        
        if (empty($data)) {
            // Si no hay datos, es una consulta simple (comportamiento antiguo)
            $stmt = $this->conn->query($sql);
        } else {
            // Si hay datos, usamos una consulta preparada y segura
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
        }

        $salida = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $salida ? $salida : [];
    }

    //Update, Delete, Insert
    public function queryNoSelect(string $sql, array $data = []): bool
    {
        $salida = false;
        if (empty($data)) {
            if ($this->conn->query($sql)) $salida = true;
        } else {
            if ($this->conn->prepare($sql)->execute($data)) $salida = true;
        }
        return $salida;
    }

    public function queryCrudo($sql = "")
    {
        return $this->conn->query($sql);
    }

    public function getBaseDatos()
    {
        return $this->db;
    }
}
?>