<?php
/**
 * Clase de conexión a base de datos con configuración mejorada
 */
require_once(__DIR__ . '/Config.php');

class MySQLdb
{
    private $host;
    private $usuario;
    private $clave;
    private $db;
    private $puerto;
    private $conn;

    function __construct()
    {
        // Cargar configuración desde .env o usar valores por defecto
        Config::load();
        
        $this->host = Config::get('DB_HOST', 'localhost');
        $this->usuario = Config::get('DB_USER', 'u645180384_maxi');
        $this->clave = Config::get('DB_PASS', 'Maxi.123@123');
        $this->db = Config::get('DB_NAME', 'u645180384_taller');
        $this->puerto = Config::get('DB_PORT', '3306');
        
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db . ';charset=utf8mb4';
            if (!empty($this->puerto)) {
                $dsn .= ';port=' . $this->puerto;
            }
            
            $this->conn = new PDO(
                $dsn,
                $this->usuario,
                $this->clave,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_FOUND_ROWS => true,
                    PDO::ATTR_PERSISTENT => false
                ]
            );
        } catch (Exception $e) {
            // Log error securely instead of exposing details
            error_log("Database connection failed: " . $e->getMessage());
            die("Error de conexión a la base de datos. Contacte al administrador.");
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

    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }
}
?>