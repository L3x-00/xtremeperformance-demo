<?php  
/**
 * 
 */
class OrdenReparacion extends Controlador
{
	private $modelo = "";
	private $usuario;
	private $sesion;
	
	function __construct()
	{
		//Creamos sesion
		$this->sesion = new Sesion();
		if ($this->sesion->getLogin()) {
			$this->modelo = $this->modelo("OrdenReparacionModelo");
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
	      $idVehiculo = Helper::cadena($_POST['idVehiculo'] ?? "");
	      $idMecanico = Helper::cadena($_POST['idMecanico'] ?? "");
	      $fechaIngreso = Helper::cadena($_POST['fechaIngreso'] ?? "");
	      $fechaSalida = Helper::cadena($_POST['fechaSalida'] ?? "");
	      $kilometraje = Helper::numero($_POST['kilometraje'] ?? "");
	      //
	      $gato = empty($_POST['gato'])? "0": "1";
	      $herramientas = empty($_POST['herramientas'])? "0": "1";
	      $triangulos = empty($_POST['triangulos'])? "0": "1";
	      $refaccion = empty($_POST['refaccion'])? "0": "1";
	      $extintor = empty($_POST['extintor'])? "0": "1";
	      $antena = empty($_POST['antena'])? "0": "1";
	      $emblemas = empty($_POST['emblemas'])? "0": "1";
	      $tapones = empty($_POST['tapones'])? "0": "1";
	      $cables = empty($_POST['cables'])? "0": "1";
	      $estereo = empty($_POST['estereo'])? "0": "1";
	      $encendedor = empty($_POST['encendedor'])? "0": "1";
	      $tapetes = empty($_POST['tapetes'])? "0": "1";
	      //
	      $pagina = $_POST['pagina'] ?? "1";

				//
				// Construimos el arreglo de datos con los valores enviados para poder
				// reinyectar los valores en la vista en caso de errores de validación.
				$data = [
					"id" => $id,
					"idVehiculo" => $idVehiculo,
					"idMecanico" => $idMecanico,
					"fechaIngreso" => $fechaIngreso,
					"fechaSalida" => $fechaSalida,
					"kilometraje" => $kilometraje,
					"gato" => $gato,
					"herramientas" => $herramientas,
					"triangulos" => $triangulos,
					"refaccion" => $refaccion,
					"extintor" => $extintor,
					"antena" => $antena,
					"emblemas" => $emblemas,
					"tapones" => $tapones,
					"cables" => $cables,
					"estereo" => $estereo,
					"encendedor" => $encendedor,
					"tapetes" => $tapetes
				];

				// Validamos la información
	      // 
	      $hoy = date("Y-m-d");
	      $hoy = new DateTime($hoy);
	      if(empty($idVehiculo) || $idVehiculo=="void"){
	        array_push($errores,"Debe de seleccionar un vehículo.");
	      }
	      if(empty($idMecanico) || $idMecanico=="void"){
	        array_push($errores,"Debe de seleccioar un mecánico disponible.");
	      }
	      if(empty($fechaIngreso)){
	        array_push($errores,"La fecha de ingreso es requerida.");
	      } else if(Helper::fecha($fechaIngreso)==false){
	      	array_push($errores,"El formato de la fecha de ingreso no es correcto.");
	      } else {
	      	// Construimos DateTime y validamos que la fecha sea la fecha actual o
	      	// pertenezca al año 2025, según la regla solicitada.
	      	$fechaIngreso_dt = new DateTime($fechaIngreso);
	      	$yearIngreso = (int)$fechaIngreso_dt->format('Y');
	      	$hoy_str = $hoy->format('Y-m-d');
	      	$fechaIngreso_str = $fechaIngreso_dt->format('Y-m-d');
	      	if ($yearIngreso !== 2025 && $fechaIngreso_str !== $hoy_str) {
	      		array_push($errores,"La fecha de ingreso debe ser la fecha actual o una fecha del año 2025.");
	      	}
	      }

	      if(empty($fechaSalida)){
	        array_push($errores,"La fecha de salida es requerida.");
	      } else if(Helper::fecha($fechaSalida)==false){
	      	array_push($errores,"El formato de la fecha de salida no es correcto.");
	      } else {
	      	// Validación similar: permitir solo la fecha actual o cualquier fecha del año 2025.
	      	$fechaSalida_dt = new DateTime($fechaSalida);
	      	$yearSalida = (int)$fechaSalida_dt->format('Y');
	      	$fechaSalida_str = $fechaSalida_dt->format('Y-m-d');
	      	if ($yearSalida !== 2025 && $fechaSalida_str !== $hoy->format('Y-m-d')) {
	      		array_push($errores,"La fecha de salida debe ser la fecha actual o una fecha del año 2025.");
	      	}
	      	// Si existe fechaIngreso_dt válida, verificamos que la salida no sea anterior.
	      	if (isset($fechaIngreso_dt)) {
	      		$diff = $fechaIngreso_dt->diff($fechaSalida_dt);
	      		if($diff->invert){
	      			array_push($errores,"La fecha de salida no puede ser inferior a la fecha de ingreso.");
	      		}
	      	}
	      }
	      if(empty($kilometraje)){
	        array_push($errores,"El kilometraje es obligatorio.");
	      } else if(intval($kilometraje)<0){
	      	array_push($errores,"El kilometraje no puede ser negativo.");
	      }
	      //
		  if (empty($errores)) { 
	        //Enviamos al modelo
	        if(trim($id)===""){
	          //Alta
				if ($this->modelo->alta($data)) {
					$this->mensaje(
							"Alta de una orden de reparación", 
							"Alta de una orden de reparación", 
							"Se añadió correctamente la orden de reparación.", 
			          		"ordenReparacion/".$pagina, 
			          		"success"
					);
		          } else {
		          	$this->mensaje(
		          		"Error al añadir la orden de reparación.", 
		          		"Error al añadir la orden de reparación.", 
		          		"Error al modificar la orden de reparación.", 
		          		"OrdenReparacion/".$pagina,
		          		"danger"
		          	);
		          }
	        } else {
			  //Modificar
			if ($this->modelo->modificar($data)) {
					$this->mensaje(
						"Modificar la orden de reparación.", 
						"Modificar la orden de reparación.", 
						"Se modificó correctamente la orden de reparación.",
						"OrdenReparacion/".$pagina, 
						"success"
					);
				} else {
					$this->mensaje(
						"Error al modificar el vehículo.", 
						"Error al modificar el vehículo.", 
						"Error al modificar el vehículo: ".$marca." ".$modelo, 
						"OrdenReparacion/".$pagina, 
						"danger"
					);
				}
	        }
	      }
	    }
	    if(!empty($errores) || $_SERVER['REQUEST_METHOD']!="POST" ){
	    	//Vista Alta
	    	$vehiculos = $this->modelo->getVehiculos();
	    	$mecanicos = $this->modelo->getMecanicos();
		    $datos = [
		      "titulo" => "Alta de una orden de reparación",
		      "subtitulo" => "Alta de una orden de reparación",
		      "activo" => "ordenreparacion",
		      "menu" => true,
		      "admon" => true,
		      "usuario" => $this->usuario,
		      "errores" => $errores,
		      "vehiculos" => $vehiculos,
		      "mecanicos" => $mecanicos,
			  "pagina" => (isset($pagina) ? $pagina : 1),
		      "data" => $data
		    ];
		    $this->vista("ordenReparacionAltaVista",$datos);
	    }
  	}

	public function borrar(string $id="",string $pagina="1"):void 
	{
		//Leemos los datos del registro del id
		$data = $this->modelo->getId($id);
		$vehiculos = $this->modelo->getVehiculos();
	   	$mecanicos = $this->modelo->getMecanicos();
		$datos = [
		  "titulo" => "Baja de una orden de reparación",
		  "subtitulo" => "Baja de una orden de reparación",
		  "menu" => true,
		  "admon" => true,
		  "usuario" => $this->usuario,
		  "errores" => [],
		  "activo" => 'ordenreparacion',
		  "data" => $data,
		  "pagina" => $pagina,
		  "vehiculos" => $vehiculos,
		  "mecanicos" => $mecanicos,
		  "baja" => true
		];
		$this->vista("ordenReparacionAltaVista",$datos);
	}

	public function bajaLogica(string $id='',string $pagina="1"):void
	{
		if (isset($id) && $id!="") {
			if ($this->modelo->bajaLogica($id)) {
				$this->mensaje(
					"Baja de una orden de reparación", 
					"Baja de una orden de reparación", 
					"Se borró correctamente la orden de reparación: ".$id, 
					"OrdenReparacion/".$pagina, 
					"success"
				);
	        } else {
	        	$this->mensaje(
	        		"Baja de una orden de reparación", 
	        		"Baja de una orden de reparación", 
	        		"Error al borrar la orden de reparación: ".$id, 
					"OrdenReparacion/".$pagina,
	        		"danger"
	        	);
	        }
	   }
	}

	public function caratula(string $pagina="1"):void
	{
		$num = $this->modelo->getNumRegistros();
		$inicio = ($pagina-1)*TAMANO_PAGINA;
		$totalPaginas = ceil($num/TAMANO_PAGINA);
		$data = $this->modelo->getTabla($inicio,TAMANO_PAGINA);
		$datos = [
			"titulo" => "Orden de reparación",
			"subtitulo" => "Orden de reparación",
			"usuario"=>$this->usuario,
			"data"=>$data,
			"activo" => "ordenreparacion",
			"pag" => [
					"totalPaginas" => $totalPaginas,
					"regresa" => "OrdenReparacion",
					"pagina" => $pagina
				],
			"menu" => true
		];
		$this->vista("ordenReparacionCaratulaVista",$datos);
	}

	public function exportarCsv(): void
	{
		// Exporta las órdenes de reparación (abiertas) visibles en la carátula en CSV
		$num = $this->modelo->getNumRegistros();
		$rows = $this->modelo->getTabla(0, $num);
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="ordenes_reparacion.csv"');
		echo "\xEF\xBB\xBF"; // BOM para Excel
		$out = fopen('php://output', 'w');
		fputcsv($out, ['ID', 'Vehículo', 'Fecha Ingreso', 'Fecha Salida']);
		foreach ($rows as $r) {
			fputcsv($out, [
				$r['id'] ?? '',
				$r['vehiculo'] ?? '',
				$r['fechaIngreso'] ?? '',
				$r['fechaSalida'] ?? '',
			]);
		}
		fclose($out);
		exit;
	}

	public function exportarPdf(): void
	{
		// Exporta las órdenes de reparación (abiertas) visibles en la carátula en PDF
		$num = $this->modelo->getNumRegistros();
		$rows = $this->modelo->getTabla(0, $num);
		$headers = ['ID', 'Vehículo', 'Fecha Ingreso', 'Fecha Salida'];
		$data = [];
		foreach ($rows as $r) {
			$data[] = [
				$r['id'] ?? '',
				$r['vehiculo'] ?? '',
				$r['fechaIngreso'] ?? '',
				$r['fechaSalida'] ?? '',
			];
		}
		$pdf = new ReporteTabla('L'); // Horizontal para más columnas
		$pdf->AliasNbPages();
		$pdf->setTitulos('Órdenes de reparación', 'Listado de órdenes abiertas');
		$pdf->AddPage();
		$pdf->Tabla($headers, $data);
		$pdf->Output('D', 'ordenes_reparacion.pdf');
		exit;
	}

	public function modificar(string $id,string $pagina="1"):void
	{
		// Si el formulario fue enviado mediante POST desde la vista de modificar,
		// delegamos el procesamiento a alta() para reusar la misma lógica y
		// mantener la URL /OrdenReparacion/modificar/{id}/{pagina} en caso de errores.
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->alta();
			return;
		}
		//Leemos los datos de la tabla
		$data = $this->modelo->getId($id);
		$vehiculos = $this->modelo->getVehiculos();
	    $mecanicos = $this->modelo->getMecanicos();
		$datos = [
			"titulo" => "Modificar una orden de reparación",
			"subtitulo" =>"Modificar una orden de reparación",
			"menu" => true,
			"admon" => true,
			"usuario" => $this->usuario,
			"activo" => "ordenreparacion",
			"vehiculos" => $vehiculos,
		     "mecanicos" => $mecanicos,
			"pagina" => $pagina,
			"data" => $data
		];
		$this->vista("ordenReparacionAltaVista",$datos);
	}

	public function mostrar(string $id,string $pagina="1"):void
	{
		//Leemos los datos de la tabla
		$data = $this->modelo->getId($id);
		$vehiculos = $this->modelo->getVehiculos();
	    $mecanicos = $this->modelo->getMecanicos();
	    $piezas = $this->modelo->getPiezas($id);
		$datos = [
			"titulo" => "Mostrar una orden de reparación",
			"subtitulo" =>"Mostrar una orden de reparación",
			"menu" => true,
			"admon" => true,
			"usuario" => $this->usuario,
			"activo" => "ordenreparacion",
			"vehiculos" => $vehiculos,
		    "mecanicos" => $mecanicos,
		    "piezas" => $piezas,
			"pagina" => $pagina,
			"data" => $data
		];
		$this->vista("ordenReparacionMostrarVista",$datos);
	}
}
?>