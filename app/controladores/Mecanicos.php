<?php  
/**
 * 
 */
class Mecanicos extends Controlador
{
	private $modelo = "";
	private $usuario;
	private $sesion;
	
	function __construct()
	{
		//Creamos sesion
		$this->sesion = new Sesion();
		if ($this->sesion->getLogin()) {
			$this->modelo = $this->modelo("MecanicosModelo");
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
	      $tipoMecanico = Helper::cadena($_POST['tipoMecanico'] ?? "");
	      $estado = Helper::cadena($_POST['estado'] ?? "");
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
	      if($tipoMecanico=="void"){
	        array_push($errores,"El tipo de mecánico es obligatorio.");
	      }
	      if (Helper::correo($correo)==false) {
	      	array_push($errores,"El correo no tiene un formato válido.");
	      } else if(trim($id)==="" && $this->modelo->getCorreo($correo)!=false){
	        array_push($errores,"El correo ya existe en la base de datos.");
	      }
	      // Teléfono Perú: si se proporciona, validar formato 9 dígitos iniciando en 9
	      if ($telefono !== "" && !Helper::telefonoPE($telefono)) {
	        array_push($errores, "El teléfono debe iniciar con 9 y tener 9 dígitos (Perú).");
	      }
	      //
	      if (empty($errores)) { 
			// Crear arreglo de datos
			//
			$data = [
				"id" => $id,
				"nombres"=>$nombres,
				"apellidos"=>$apellidos,
				"telefono"=>$telefono,
				"correo"=>$correo,
				"clave"=>Helper::generarClave(10),
				"estado"=>$estado,
				"idTipoMecanico"=>$tipoMecanico
			];     
	        //Enviamos al modelo
	        if(trim($id)===""){
	          //Alta: enviar email de activación para que el mecánico cree su contraseña
				$idNuevo = $this->modelo->alta($data);
				if ($idNuevo) {
					// Enviar correo de activación
					if ($this->enviarCorreoMecanico(["id"=>$idNuevo, "correo"=>$correo])) {
						$this->mensaje(
							"Alta de un mecánico", 
							"Alta de un mecánico", 
							"Se añadió correctamente el mecánico: ".$nombres." ".$apellidos.". Se envió un correo de activación para que cree su contraseña.", 
							"mecanicos/".$pagina, 
							"success"
						);
					} else {
						$this->mensaje(
							"Error al enviar correo de activación.", 
							"Error al enviar correo de activación.", 
							"El mecánico fue creado pero no se pudo enviar el correo de activación. Favor de intentarlo nuevamente.", 
							"mecanicos/".$pagina,
							"warning"
						);
					}
		          } else {
		          	$this->mensaje(
		          		"Error al añadir el mecánico.", 
		          		"Error al añadir el mecánico.", 
		          		"Error al añadir el mecánico: ".$nombres." ".$apellidos, 
		          		"mecanicos/".$pagina,
		          		"danger"
		          	);
		          }
	        } else {
			  //Modificar
			  if ($this->modelo->modificar($data)) {
					$this->mensaje(
							"Modificar el mecánico", 
							"Modificar el mecánico", 
							"Se modificó correctamente el mecánico: ".$nombres." ".$apellidos,
							"mecanicos/".$pagina, 
							"success"
						);
				} else {
					$this->mensaje(
						"Error al modificar el mecánico.", 
						"Error al modificar el mecánico.", 
						"Error al modificar el mecánico: ".$nombres." ".$apellidos, 
						"mecanicos/".$pagina, 
						"danger"
					);
				}
	        }
	      }
	    }
	    if(!empty($errores) || $_SERVER['REQUEST_METHOD']!="POST" ){
	    	//Vista Alta
	    	$tipoMecanico = $this->modelo->getTipoMecanico();
	    	$estadoMecanico = $this->modelo->getEstadoMecanico();
		    $datos = [
		      "titulo" => "Alta de un mecánico",
		      "subtitulo" => "Alta de un mecánico",
		      "activo" => "mecanicos",
		      "menu" => true,
		      "admon" => true,
		      "usuario" => $this->usuario,
		      "errores" => $errores,
		      "tipoMecanico" => $tipoMecanico,
		      "estadoMecanico" => $estadoMecanico,
		      "data" => $data
		    ];
		    $this->vista("mecanicosAltaVista",$datos);
	    }
  	}

	public function borrar(string $id="",string $pagina="1"):void 
	{
		//Leemos los datos del registro del id
		$data = $this->modelo->getId($id);
		$tipoMecanico = $this->modelo->getTipoMecanico();
    	$estadoMecanico = $this->modelo->getEstadoMecanico();
    	//Integridad referencial
    	$ir_array = $this->modelo->getIntegridadReferencial($id);

		if ($ir_array[0]==0) {
			$datos = [
			  "titulo" => "Baja de un macánico",
			  "subtitulo" => "Baja de un macánico",
			  "menu" => true,
			  "admon" => true,
			  "usuario" => $this->usuario,
			  "errores" => [],
			  "activo" => 'mecanicos',
			  "data" => $data,
			  "pagina" => $pagina,
			  "tipoMecanico" => $tipoMecanico,
			  "estadoMecanico" => $estadoMecanico,
			  "baja" => true
			];
			$this->vista("mecanicosAltaVista",$datos);
		} else {
			$m = "No podemos eliminar al mecánico porque tiene:<ul>";
			if ($ir_array[1]==1) {
				$m.="<li>".$ir_array[1]." Orden de reparación.</li>";
			} else if ($ir_array[1]>1) {
				$m.="<li>".$ir_array[1]." Órdenes de reparación.</li>";
			}
			$m.="</ul>Primero debe eliminar esas referencias.";
			$this->mensaje(
	    		"Error al borrar al mecánico", 
	    		"Error al borrar al mecánico", 
	    		$m, 
	    		"mecanicos", 
	    		"danger"
	    	);
		}
	}

	public function bajaLogica(string $id='',string $pagina="1"):void
	{
		if (isset($id) && $id!="") {
			if ($this->modelo->bajaLogica($id)) {
				$this->mensaje(
					"Baja de un mecánico", 
					"Baja de un mecánico", 
					"Se borró correctamente al mecánico: ".$id, 
					"mecanicos/".$pagina, 
					"success"
				);
	        } else {
	        	$this->mensaje(
	        		"Baja de un mecánico", 
	        		"Baja de un mecánico", 
	        		"Error al borrar al mecánico: ".$id, 
	        		"mecanicos/".$pagina,
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
			"titulo" => "Mecánicos taller mecánico",
			"subtitulo" => "Mecánicos taller mecánico",
			"usuario"=>$this->usuario,
			"data"=>$data,
			"activo" => "mecanicos",
			"pag" => [
				"totalPaginas" => $totalPaginas,
				"regresa" => "mecanicos",
				"pagina" => $pagina
			],
			"menu" => true
		];
		$this->vista("mecanicosCaratulaVista",$datos);
	}

	public function modificar(string $id,string $pagina="1"):void
	{
		//Leemos los datos de la tabla
		$data = $this->modelo->getId($id);
    	$tipoMecanico = $this->modelo->getTipoMecanico();
    	$estadoMecanico = $this->modelo->getEstadoMecanico();
		$datos = [
			"titulo" => "Modificar un mecánico",
			"subtitulo" =>"Modificar un mecánico",
			"menu" => true,
			"admon" => true,
			"usuario" => $this->usuario,
			"activo" => "mecanicos",
			"tipoMecanico" => $tipoMecanico,
			"estadoMecanico" => $estadoMecanico,
			"pagina" => $pagina,
			"data" => $data
		];
		$this->vista("mecanicosAltaVista",$datos);
	}
  // ---------------------------------------------------------
  // MÉTODOS PARA EXPORTAR DATOS DE MECÁNICOS
  // ---------------------------------------------------------
  
  public function exportarCsv(): void
  {
    // Obtenemos todos los registros de mecánicos
    $num = $this->modelo->getNumRegistros();
    $rows = $this->modelo->getTabla(0, $num);
    
    // Configuramos las cabeceras para forzar la descarga del CSV
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="reporte_mecanicos.csv"');
    echo "\xEF\xBB\xBF"; // BOM para que Excel lea los acentos correctamente
    
    $out = fopen('php://output', 'w');
    
    // Escribimos los encabezados de las columnas
    fputcsv($out, ['ID', 'Nombre', 'Tipo', 'Estado']);
    
    // Recorremos los datos y escribimos fila por fila
    foreach ($rows as $r) {
      fputcsv($out, [
        $r['id'] ?? '',
        html_entity_decode($r['nombre'] ?? '', ENT_QUOTES, 'UTF-8'),
        html_entity_decode($r['tipo'] ?? '', ENT_QUOTES, 'UTF-8'),
        html_entity_decode($r['estado'] ?? '', ENT_QUOTES, 'UTF-8')
      ]);
    }
    fclose($out);
    exit;
  }

  public function exportarPdf(): void
  {
    // Obtenemos todos los registros de mecánicos
    $num = $this->modelo->getNumRegistros();
    $rows = $this->modelo->getTabla(0, $num);
    
    // Definimos las cabeceras para el PDF
    $headers = ['ID', 'Nombre Completo', 'Especialidad / Tipo', 'Estado'];
    $data = [];
    
    // Llenamos la data asegurando la decodificación de caracteres especiales
    foreach ($rows as $r) {
      $data[] = [
        $r['id'] ?? '',
        html_entity_decode($r['nombre'] ?? '', ENT_QUOTES, 'UTF-8'),
        html_entity_decode($r['tipo'] ?? '', ENT_QUOTES, 'UTF-8'),
        html_entity_decode($r['estado'] ?? '', ENT_QUOTES, 'UTF-8')
      ];
    }
    
    // Instanciamos la clase ReporteTabla en modo Horizontal ('L')
    // Asumiendo que esta clase existe y funciona como en OrdenAlmacen y Vehiculos
    $pdf = new ReporteTabla('L'); 
    $pdf->AliasNbPages();
    $pdf->setTitulos('Reporte de Mecánicos', 'Plantilla activa del taller Xtreme Performance');
    $pdf->AddPage();
    
    // NOTA: Como ReporteTabla ajusta el ancho de las celdas automáticamente, 
    // enviar solo estas 4 columnas debería dar un resultado ancho y muy limpio.
    $pdf->Tabla($headers, $data);
    
    // Forzamos la descarga del PDF
    $pdf->Output('D', 'reporte_mecanicos.pdf');
    exit;
  }
}
?>