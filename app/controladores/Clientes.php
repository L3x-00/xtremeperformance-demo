<?php  
/**
 * 
 */
class Clientes extends Controlador
{
	private $modelo = "";
	private $usuario;
	private $sesion;
	
	function __construct()
	{
		//Creamos sesion
		$this->sesion = new Sesion();
		if ($this->sesion->getLogin()) {
			$this->modelo = $this->modelo("ClientesModelo");
			$this->usuario = $this->sesion->getUsuario();
			// Solo administradores pueden entrar aquí
			$tipo = $this->usuario["tipoUsuario"] ?? null;
			if ($tipo !== ADMON) {
				if ($tipo === MECANICO) {
					header("location:".RUTA."TableroMecanico");
				} else if ($tipo === CLIENTE) {
					header("location:".RUTA."TableroCliente");
				} else {
					header("location:".RUTA);
				}
				exit;
			}
		} else {
			header("location:".RUTA);
		}
	}

	public function alta(){
	   //Definir los arreglos
	    $data = array();
	    $errores = array();
	    if ($_SERVER['REQUEST_METHOD']=="POST") {
	      //
	      $id = $_POST['id'] ?? "";
	      $nombres = Helper::cadena($_POST['nombres'] ?? "");
	      $apellidos = Helper::cadena($_POST['apellidos'] ?? "");
	      $telefono = Helper::cadena($_POST['telefono'] ?? "");
	      $correo = Helper::cadena($_POST['correo'] ?? "");
	      $direccion = Helper::cadena($_POST['direccion'] ?? "");
	      $ruc = Helper::cadena($_POST['ruc'] ?? "");

//...
$razonSocial = Helper::cadena($_POST['razonSocial'] ?? "");
$estado = Helper::cadena($_POST['id_estado_cliente'] ?? ""); 
//...
	      //
	      $pagina = $_POST['pagina'] ?? "1";
	      //
	      // Validamos la información
	      // 
	      if(empty($nombres)){
	        array_push($errores,"El nombre del usuario es requerido.");
	      }
	      if(empty($apellidos)){
	        array_push($errores,"Los apellidos del usuario son requeridos.");
	      }
	      if(empty($correo)){
	        array_push($errores,"El correo del usuario es requerido.");
	      }
	      if($estado=="void"){
	        array_push($errores,"El estado es obligatorio.");
	      }
					// Teléfono Perú: si se proporciona, validar formato 9 dígitos iniciando en 9
					if ($telefono !== "" && !Helper::telefonoPE($telefono)) {
						array_push($errores, "El teléfono debe iniciar con 9 y tener 9 dígitos (Perú).");
					}
							// RUC: si se proporciona, solo números y 11 dígitos
							if ($ruc !== "" && !preg_match('/^\d{11}$/', $ruc)) {
								array_push($errores, "El RUC debe contener solo números y tener 11 dígitos.");
							}
	      if (Helper::correo($correo)==false) {
	      	array_push($errores,"El correo no tiene un formato válido.");
	      } else if(trim($id)==="" && $this->modelo->getCorreo($correo)){
	        array_push($errores,"El correo ya existe en la base de datos.");
	      }
	      //
	     // Reemplaza el bloque if(empty($errores)) en Clientes.php con esto

if (empty($errores)) {
    // 1. Crear un array base con los datos comunes del formulario
    $data = [
        "nombres"           => $nombres,
        "apellidos"         => $apellidos,
        "telefono"          => $telefono,
        "direccion"         => $direccion,
        "ruc"               => $ruc,
        "razonSocial"       => $razonSocial,
        "correo"            => $correo,
        "id_estado_cliente" => $estado
    ];

    // 2. Comprobar si es un ALTA o una MODIFICACIÓN
	if (trim($id) === "") {
		// Es un ALTA: generamos una clave temporal y luego enviamos activación para que la cambie
		$data["clave"] = Helper::generarClave(10);
		if ($this->modelo->alta($data)) {
			// Obtener ID del nuevo cliente para el enlace de activación
			$nuevo = $this->modelo->getCorreo($correo);
			if (!empty($nuevo) && isset($nuevo['id'])) {
				$this->enviarCorreoCliente(["id"=>$nuevo['id'], "correo"=>$correo]);
			}
			$this->mensaje(
				"Alta de un cliente",
				"Alta de un cliente",
				"Se añadió correctamente el cliente: " . $nombres . " " . $apellidos . ". Enviamos un correo de activación para que cree su contraseña.",
				"clientes/" . $pagina,
				"success"
			);
		} else {
			$this->mensaje(
				"Error al añadir el cliente.",
				"Error al añadir el cliente.",
				"No se pudo añadir el cliente: " . $nombres . " " . $apellidos,
				"clientes/" . $pagina,
				"danger"
			);
		}
	} else {
        // Es una MODIFICACIÓN: añadimos el ID al paquete de datos
        $data["id"] = $id;

        if ($this->modelo->modificar($data)) {
            $this->mensaje(
                "Modificar el cliente",
                "Modificar el cliente",
                "Se modificó correctamente el cliente: " . $nombres . " " . $apellidos,
                "clientes/" . $pagina,
                "success"
            );
        } else {
            $this->mensaje(
                "Error al modificar el cliente.",
                "Error al modificar el cliente.",
                "No se pudo modificar el cliente: " . $nombres . " " . $apellidos,
                "clientes/" . $pagina,
                "danger"
            );
        }
    }
}
	    }
	    if(!empty($errores) || $_SERVER['REQUEST_METHOD']!="POST" ){
	    	//Vista Alta
	    	$estadoCliente = $this->modelo->getEstadoCliente();
		    $datos = [
		      "titulo" => "Alta de un cliente",
		      "subtitulo" => "Alta de un cliente",
		      "activo" => "clientes",
		      "menu" => true,
		      "admon" => true,
		      "usuario" => $this->usuario,
		      "errores" => $errores,
		      "estadoCliente" => $estadoCliente,
		      "data" => $data
		    ];
		    $this->vista("clientesAltaVista",$datos);
	    }
  	}

	public function borrar(string $id="",string $pagina="1"):void 
	{
		//Leemos los datos del registro del id
		$data = $this->modelo->getId($id);
		$estadoCliente = $this->modelo->getEstadoCliente();
		//Integridad referencial
    	$ir_array = $this->modelo->getIntegridadReferencial($id);

		if ($ir_array['total']==0) {
			$datos = [
			  "titulo" => "Baja de un cliente",
			  "subtitulo" => "Baja de un cliente",
			  "menu" => true,
			  "admon" => true,
			  "usuario" => $this->usuario,
			  "errores" => [],
			  "activo" => 'clientes',
			  "data" => $data,
			  "pagina" => $pagina,
			  "estadoCliente" => $estadoCliente,
			  "baja" => true
			];
			$this->vista("clientesAltaVista",$datos);
		} else {
			$m = "No podemos eliminar al cliente porque tiene:<ul>";
			if ($ir_array['vehiculos'] > 0) {
				$m .= "<li>" . (($ir_array['vehiculos'] == 1) ? "Un vehículo" : $ir_array['vehiculos'] . " Vehículos") . ".</li>";
			}
			if ($ir_array['facturas'] > 0) {
				$m .= "<li>" . (($ir_array['facturas'] == 1) ? "Una factura" : $ir_array['facturas'] . " Facturas") . ".</li>";
			}
			$m.="</ul>Primero debe eliminar esas referencias.";
			$this->mensaje(
	    		"Error al borrar al cliente", 
	    		"Error al borrar al cliente", 
	    		$m, 
	    		"clientes", 
	    		"danger"
	    	);
		}
	}

	public function bajaLogica(string $id='',string $pagina="1"):void
	{
		if (isset($id) && $id!="") {
			if ($this->modelo->bajaLogica($id)) {
				$this->mensaje(
					"Baja de un cliente", 
					"Baja de un cliente", 
					"Se borró correctamente al cliente: ".$id, 
					"clientes/".$pagina, 
					"success"
				);
	        } else {
	        	$this->mensaje(
	        		"Baja de un cliente", 
	        		"Baja de un cliente", 
	        		"Error al borrar al cliente: ".$id, 
	        		"clientes/".$pagina,
	        		"danger"
	        	);
	        }
	   }
	}

	public function eliminar(string $id='', string $pagina="1"):void
	{
		// Redirige a bajaLogica (igual que Vehículos)
		$this->bajaLogica($id, $pagina);
	}

	public function caratula(string $pagina="1"):void
	{
		$num = $this->modelo->getNumRegistros();
		$paginaInt = max(1, (int)$pagina); // Asegurar que la página sea al menos 1
		$inicio = ($paginaInt-1)*TAMANO_PAGINA;
		$totalPaginas = ceil($num/TAMANO_PAGINA);
		$data = $this->modelo->getTabla($inicio,TAMANO_PAGINA);
		$datos = [
			"titulo" => "Clientes",
			"subtitulo" => "Clientes",
			"usuario"=>$this->usuario,
			"data"=>$data,
			"activo" => "clientes",
			"pag" => [
				"totalPaginas" => $totalPaginas,
				"regresa" => "clientes",
				"pagina" => $paginaInt
			],
			"menu" => true
		];
		$this->vista("clientesCaratulaVista",$datos);
	}

	public function modificar(string $id,string $pagina="1"):void
	{
		//Leemos los datos de la tabla
		$data = $this->modelo->getId($id);
	    $estadoCliente = $this->modelo->getEstadoCliente();
		$datos = [
			"titulo" => "Modificar un cliente",
			"subtitulo" =>"Modificar un cliente",
			"menu" => true,
			"admon" => true,
			"usuario" => $this->usuario,
			"activo" => "clientes",
			"estadoCliente" => $estadoCliente,
			"pagina" => $pagina,
			"data" => $data
		];
		$this->vista("clientesAltaVista",$datos);
	}

	public function exportarCsv(): void
	{
		// Obtener todos los datos de clientes
		$data = $this->modelo->getTodos();
		
		// Configurar headers para descarga CSV
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=clientes_' . date('Y-m-d') . '.csv');
		header('Pragma: no-cache');
		header('Expires: 0');
		
		// Crear output handle
		$output = fopen('php://output', 'w');
		
		// BOM para UTF-8
		fputs($output, chr(0xEF).chr(0xBB).chr(0xBF));
		
		// Headers del CSV
		fputcsv($output, ['ID', 'Nombres', 'Apellidos', 'Telefono', 'Correo', 'Direccion', 'RUC', 'Razon Social', 'Estado'], ';');
		
		// Datos
		foreach ($data as $row) {
			fputcsv($output, [
				$row['id'],
				$row['nombres'],
				$row['apellidos'],
				$row['telefono'],
				$row['correo'],
				$row['direccion'],
				$row['ruc'],
				$row['razonSocial'],
				$row['estado']
			], ';');
		}
		
		fclose($output);
		exit;
	}

	public function exportarPdf(): void
  {
    // Obtener todos los datos
    $data = $this->modelo->getTodos();
    
    // Inicializar FPDF
    require_once(__DIR__ . '/../libs/fpdf.php');
    
    $pdf = new FPDF('L', 'mm', 'A4'); // Orientación horizontal
    $pdf->AddPage();
    
    // Título
    $pdf->SetFont('Arial', 'B', 16);
    $titulo = iconv('UTF-8', 'ISO-8859-1//IGNORE', 'XTREME PERFORMANCE - LISTADO DE CLIENTES');
    $pdf->Cell(0, 10, $titulo, 0, 1, 'C');
    $pdf->Ln(5);
    
    // Fecha
    $pdf->SetFont('Arial', '', 10);
    $fecha = iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Generado el: ' . date('d/m/Y H:i:s'));
    $pdf->Cell(0, 6, $fecha, 0, 1, 'C');
    $pdf->Ln(10);
    
    // Headers de tabla (Ajustados a 4 columnas anchas)
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetFillColor(200, 200, 200);
    $pdf->Cell(20, 8, 'ID', 1, 0, 'C', true);
    $pdf->Cell(100, 8, 'Nombre Completo', 1, 0, 'C', true);
    $pdf->Cell(110, 8, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Razón Social'), 1, 0, 'C', true);
    $pdf->Cell(40, 8, 'Estado', 1, 1, 'C', true);
    
    // Datos (Ajustados a 4 columnas anchas)
    $pdf->SetFont('Arial', '', 10);
    $fill = false;
    foreach ($data as $row) {
      // Concatenamos nombre y apellido como en la vista web
      $nombreCompleto = iconv('UTF-8', 'ISO-8859-1//IGNORE', $row['nombres'] . ' ' . $row['apellidos']);
      $razonSocial = iconv('UTF-8', 'ISO-8859-1//IGNORE', $row['razonSocial']);
      $estado = iconv('UTF-8', 'ISO-8859-1//IGNORE', $row['estado']);
      
      $pdf->Cell(20, 8, $row['id'], 1, 0, 'C', $fill);
      $pdf->Cell(100, 8, substr($nombreCompleto, 0, 45), 1, 0, 'L', $fill);
      $pdf->Cell(110, 8, substr($razonSocial, 0, 50), 1, 0, 'L', $fill);
      $pdf->Cell(40, 8, substr($estado, 0, 15), 1, 1, 'C', $fill);
      $fill = !$fill;
    }
    
    // Footer
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 8);
    $totalRegistros = iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Total de registros: ' . count($data));
    $footer = iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Xtreme Performance - Sistema de Gestión Automotriz');
    $pdf->Cell(0, 6, $totalRegistros, 0, 1, 'L');
    $pdf->Cell(0, 6, $footer, 0, 1, 'C');
    
    // Output
    $pdf->Output('D', 'clientes_' . date('Y-m-d') . '.pdf');
    exit;
  }
	public function detalles(string $id = ""): void
	{
		// Configurar headers de performance
		header('Content-Type: application/json; charset=utf-8');
		header('Cache-Control: private, max-age=300'); // Cache 5 minutos
		
		// Verificar que se proporcionó un ID válido
		if (empty($id) || !is_numeric($id)) {
			http_response_code(400);
			echo json_encode(['error' => 'ID de cliente no válido'], JSON_UNESCAPED_UNICODE);
			exit;
		}

		try {
			// Obtener datos básicos del cliente
			$cliente = $this->modelo->getId($id);
			
			if (empty($cliente)) {
				http_response_code(404);
				echo json_encode(['error' => 'Cliente no encontrado'], JSON_UNESCAPED_UNICODE);
				exit;
			}

			// Obtener estadísticas adicionales
			$estadisticas = $this->modelo->getEstadisticasCliente($id);
			
			// Preparar respuesta JSON optimizada
			$respuesta = [
				'success' => true,
				'cliente' => $cliente,
				'estadisticas' => $estadisticas,
				'timestamp' => time()
			];

			// Enviar respuesta JSON comprimida si es posible
			if (function_exists('gzencode') && strpos($_SERVER['HTTP_ACCEPT_ENCODING'] ?? '', 'gzip') !== false) {
				header('Content-Encoding: gzip');
				echo gzencode(json_encode($respuesta, JSON_UNESCAPED_UNICODE));
			} else {
				echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
			}
			
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode([
				'error' => 'Error interno del servidor',
				'message' => 'Contacte al administrador del sistema'
			], JSON_UNESCAPED_UNICODE);
		}
		
		exit;
	}
}
?>