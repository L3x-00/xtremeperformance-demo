<?php

class TableroOperador extends Controlador
{
	public function __construct()
	{
		parent::__construct();
		
		// Verificar que el usuario esté logueado
		if (!isset($this->usuario)) {
			header("Location: " . RUTA . "login");
			exit;
		}
		
		// Verificar que sea operador
		$tipo = $this->usuario["tipoUsuario"] ?? null;
		if ($tipo != OPERADOR) {
			header("Location: " . RUTA . "login");
			exit;
		}
		
		// Cargar modelo
		$this->modelo = $this->cargar_modelo("TableroOperadorModelo");
	}
	
	public function caratula()
	{
		// Obtener estadísticas básicas
		$estadisticas = $this->modelo->getEstadisticasBasicas();
		
		// Obtener últimas órdenes
		$ultimasOrdenes = $this->modelo->getUltimasOrdenes(8);
		
		// Obtener estado de mecánicos
		$mecanicos = $this->modelo->getMecanicosEstado();
		
		// Obtener clientes recientes
		$clientesRecientes = $this->modelo->getClientesRecientes(6);
		
		$data = [
			"titulo" => "Panel de Operador - Xtreme Performance",
			"usuario" => $this->usuario,
			"estadisticas" => $estadisticas,
			"ultimasOrdenes" => $ultimasOrdenes,
			"mecanicos" => $mecanicos,
			"clientesRecientes" => $clientesRecientes
		];
		
		$this->vista("tableroOperadorCaratulaVista", $data);
	}
	
	// Búsqueda AJAX para órdenes
	public function buscarOrdenes()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			exit;
		}
		
		$termino = $_POST['termino'] ?? '';
		
		if (strlen($termino) < 2) {
			echo json_encode(['error' => 'Mínimo 2 caracteres']);
			exit;
		}
		
		$resultados = $this->modelo->buscarOrdenes($termino);
		
		header('Content-Type: application/json');
		echo json_encode($resultados);
		exit;
	}
	
	// Búsqueda AJAX para clientes
	public function buscarClientes()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			exit;
		}
		
		$termino = $_POST['termino'] ?? '';
		
		if (strlen($termino) < 2) {
			echo json_encode(['error' => 'Mínimo 2 caracteres']);
			exit;
		}
		
		$resultados = $this->modelo->buscarClientes($termino);
		
		header('Content-Type: application/json');
		echo json_encode($resultados);
		exit;
	}
	
	// Ver detalles de una orden (solo lectura)
	public function verOrden($id = "")
	{
		if (empty($id)) {
			$this->mensaje(
				"Error",
				"Orden no encontrada",
				"No se especificó una orden válida",
				"tablerooperador",
				"danger"
			);
			return;
		}
		
		// Aquí se podría cargar un modelo de OrdenReparacion para mostrar detalles
		// Por ahora redirigimos con mensaje informativo
		$this->mensaje(
			"Vista de Solo Lectura",
			"Información de Orden #$id",
			"Como operador, puedes consultar la información pero no modificarla. Para editar, contacta a un administrador.",
			"tablerooperador",
			"info"
		);
	}
	
	// Ver detalles de un cliente (solo lectura)
	public function verCliente($id = "")
	{
		if (empty($id)) {
			$this->mensaje(
				"Error",
				"Cliente no encontrado",
				"No se especificó un cliente válido",
				"tablerooperador",
				"danger"
			);
			return;
		}
		
		// Similar a verOrden, por ahora mostramos mensaje informativo
		$this->mensaje(
			"Vista de Solo Lectura",
			"Información de Cliente #$id",
			"Como operador, puedes consultar la información pero no modificarla. Para editar, contacta a un administrador.",
			"tablerooperador",
			"info"
		);
	}
}

?>