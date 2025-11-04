<?php

class TableroOperadorModelo extends MySQLdb
{
    public function __construct()
    {
        parent::__construct();
    }

    // Estadísticas básicas para el operador (solo lectura)
    public function getEstadisticasBasicas()
    {
        $stats = [];
        
        // Total de clientes activos
        $sql = "SELECT COUNT(*) as total FROM clientes WHERE estado = " . CLIENTE_ACTIVO;
        $stats['clientesActivos'] = $this->consultaUnica($sql)['total'];
        
        // Total de órdenes en proceso
        $sql = "SELECT COUNT(*) as total FROM ordenreparacion WHERE estado IN (1, 2, 3)"; // Estados: pendiente, en proceso, esperando piezas
        $stats['ordenesEnProceso'] = $this->consultaUnica($sql)['total'];
        
        // Mecánicos disponibles
        $sql = "SELECT COUNT(*) as total FROM mecanicos WHERE estado = " . MECANICO_DISPONIBLE;
        $stats['mecanicosDisponibles'] = $this->consultaUnica($sql)['total'];
        
        // Total de vehículos registrados
        $sql = "SELECT COUNT(*) as total FROM vehiculos";
        $stats['totalVehiculos'] = $this->consultaUnica($sql)['total'];
        
        // Órdenes completadas este mes
        $sql = "SELECT COUNT(*) as total FROM ordenreparacion WHERE estado = 4 AND MONTH(fechaInicio) = MONTH(CURDATE()) AND YEAR(fechaInicio) = YEAR(CURDATE())";
        $stats['ordenesCompletadasMes'] = $this->consultaUnica($sql)['total'];
        
        return $stats;
    }
    
    // Últimas órdenes de reparación (solo lectura)
    public function getUltimasOrdenes($limite = 10)
    {
        $sql = "SELECT 
                    or.id,
                    or.fechaInicio,
                    or.descripcion,
                    c.nombres as clienteNombres,
                    c.apellidos as clienteApellidos,
                    v.marca,
                    v.modelo,
                    v.placa,
                    er.estado as estadoTexto
                FROM ordenreparacion or
                LEFT JOIN clientes c ON or.cliente = c.id
                LEFT JOIN vehiculos v ON or.vehiculo = v.id
                LEFT JOIN estadoreparacion er ON or.estado = er.id
                ORDER BY or.fechaInicio DESC
                LIMIT $limite";
        
        return $this->consulta($sql);
    }
    
    // Mecánicos con su estado actual (solo lectura)
    public function getMecanicosEstado()
    {
        $sql = "SELECT 
                    m.id,
                    m.nombres,
                    m.apellidos,
                    m.telefono,
                    tm.tipomecanico,
                    em.estado as estadoTexto
                FROM mecanicos m
                LEFT JOIN tipomecanico tm ON m.tipomecanico = tm.id
                LEFT JOIN estadomecanico em ON m.estado = em.id
                WHERE m.estado IN (1, 2) -- Disponible u Ocupado
                ORDER BY m.estado ASC, m.nombres ASC";
        
        return $this->consulta($sql);
    }
    
    // Clientes recientes (solo lectura)
    public function getClientesRecientes($limite = 10)
    {
        $sql = "SELECT 
                    c.id,
                    c.nombres,
                    c.apellidos,
                    c.telefono,
                    c.correo,
                    c.razonSocial,
                    ec.estado as estadoTexto
                FROM clientes c
                LEFT JOIN estadocliente ec ON c.estado = ec.id
                ORDER BY c.id DESC
                LIMIT $limite";
        
        return $this->consulta($sql);
    }
    
    // Buscar órdenes por diferentes criterios (solo lectura)
    public function buscarOrdenes($termino)
    {
        $termino = $this->antiinyeccion($termino);
        
        $sql = "SELECT 
                    or.id,
                    or.fechaInicio,
                    or.descripcion,
                    c.nombres as clienteNombres,
                    c.apellidos as clienteApellidos,
                    v.marca,
                    v.modelo,
                    v.placa,
                    er.estado as estadoTexto
                FROM ordenreparacion or
                LEFT JOIN clientes c ON or.cliente = c.id
                LEFT JOIN vehiculos v ON or.vehiculo = v.id
                LEFT JOIN estadoreparacion er ON or.estado = er.id
                WHERE 
                    or.id LIKE '%$termino%' OR
                    or.descripcion LIKE '%$termino%' OR
                    c.nombres LIKE '%$termino%' OR
                    c.apellidos LIKE '%$termino%' OR
                    v.placa LIKE '%$termino%' OR
                    v.marca LIKE '%$termino%' OR
                    v.modelo LIKE '%$termino%'
                ORDER BY or.fechaInicio DESC
                LIMIT 20";
        
        return $this->consulta($sql);
    }
    
    // Buscar clientes (solo lectura)
    public function buscarClientes($termino)
    {
        $termino = $this->antiinyeccion($termino);
        
        $sql = "SELECT 
                    c.id,
                    c.nombres,
                    c.apellidos,
                    c.telefono,
                    c.correo,
                    c.razonSocial,
                    ec.estado as estadoTexto
                FROM clientes c
                LEFT JOIN estadocliente ec ON c.estado = ec.id
                WHERE 
                    c.nombres LIKE '%$termino%' OR
                    c.apellidos LIKE '%$termino%' OR
                    c.telefono LIKE '%$termino%' OR
                    c.correo LIKE '%$termino%' OR
                    c.razonSocial LIKE '%$termino%'
                ORDER BY c.nombres ASC
                LIMIT 20";
        
        return $this->consulta($sql);
    }
}

?>