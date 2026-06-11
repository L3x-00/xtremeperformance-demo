<?php  
/**
 * 
 */
class Salidas extends Controlador
{
	private $modelo = "";
	private $usuario;
	private $sesion;
	
	function __construct()
	{
		//Creamos sesion
		$this->sesion = new Sesion();
		if ($this->sesion->getLogin()) {
			$this->modelo = $this->modelo("SalidasModelo");
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
	      $marca = Helper::cadena($_POST['marca'] ?? "");
	      $modelo = Helper::cadena($_POST['modelo'] ?? "");
	      $color = Helper::cadena($_POST['color'] ?? "");
	      $anio = Helper::numero(Helper::cadena($_POST['anio'] ?? ""));
	      $placas = Helper::cadena($_POST['placas'] ?? "");
	      $idCliente = Helper::cadena($_POST['idCliente'] ?? "");
	      //
	      $pagina = $_POST['pagina'] ?? "1";
	      //
	      // Validamos la información
	      // 
	      if(empty($marca)){
	        array_push($errores,"La marca del vehículo es requerida.");
	      }
	      if(empty($modelo)){
	        array_push($errores,"El modelo del vehículo es requerido.");
	      }
	      if(empty($anio)){
	        array_push($errores,"El año del vehículo es requerido.");
	      }
	      if($color=="void"){
	        array_push($errores,"El color del vehículo es obligatorio.");
	      }
	      if($idCliente=="void"){
	        array_push($errores,"El cliente es obligatorio.");
	      }
	      //
	      if (empty($errores)) { 
			// Crear arreglo de datos
			//
			$data = [
				"id" => $id,
				"marca"=>$marca,
				"modelo"=>$modelo,
				"anio"=>$anio,
				"color"=>$color,
				"placas"=>$placas,
				"idCliente"=>$idCliente
			];     
	        //Enviamos al modelo
	        if(trim($id)===""){
	          //Alta
				if ($this->modelo->alta($data)) {
					$this->mensaje(
							"Alta de un vehículo", 
							"Alta de un vehículo", 
							"Se añadió correctamente el vehículo: ".$marca." ".$modelo, 
							"vehiculos/".$pagina, 
							"success"
					);
		          } else {
		          	$this->mensaje(
		          		"Error al añadir el vehículo.", 
		          		"Error al añadir el vehículo.", 
		          		"Error al modificar el vehículo: ".$marca." ".$modelo, 
		          		"vehiculos/".$pagina,
		          		"danger"
		          	);
		          }
	        } else {
			  //Modificar
			  if ($this->modelo->modificar($data)) {
					$this->mensaje(
							"Modificar el vehículo", 
							"Modificar el vehículo", 
							"Se modificó correctamente el vehículo: ".$marca." ".$modelo,
							"vehiculos/".$pagina, 
							"success"
						);
				} else {
					$this->mensaje(
						"Error al modificar el vehículo.", 
						"Error al modificar el vehículo.", 
						"Error al modificar el vehículo: ".$marca." ".$modelo, 
						"vehiculos/".$pagina, 
						"danger"
					);
				}
	        }
	      }
	    }
	    if(!empty($errores) || $_SERVER['REQUEST_METHOD']!="POST" ){
	    	//Vista Alta
	    	$clientes = $this->modelo->getClientes();
		    $datos = [
		      "titulo" => "Alta de un vehículo",
		      "subtitulo" => "Alta de un vehículo",
		      "activo" => "vehiculos",
		      "menu" => true,
		      "admon" => true,
		      "usuario" => $this->usuario,
		      "errores" => $errores,
		      "clientes" => $clientes,
		      "data" => $data
		    ];
		    $this->vista("vehiculosAltaVista",$datos);
	    }
  	}

	public function borrar(string $id="",string $pagina="1"):void 
	{
		//Leemos los datos del registro del id
		$data = $this->modelo->getId($id);
		$clientes = $this->modelo->getClientes();
		$datos = [
		  "titulo" => "Baja de un vehículo",
		  "subtitulo" => "Baja de un vehículo",
		  "menu" => true,
		  "admon" => true,
		  "usuario" => $this->usuario,
		  "errores" => [],
		  "activo" => 'vehiculos',
		  "data" => $data,
		  "pagina" => $pagina,
		  "clientes" => $clientes,
		  "baja" => true
		];
		$this->vista("vehiculosAltaVista",$datos);
	}

	public function bajaLogica(string $id='',string $pagina="1"):void
	{
		if (isset($id) && $id!="") {
			if ($this->modelo->bajaLogica($id)) {
				$this->mensaje(
					"Baja de un vehículo", 
					"Baja de un vehículo", 
					"Se borró correctamente al vehículo: ".$id, 
					"vehiculos/".$pagina, 
					"success"
				);
	        } else {
	        	$this->mensaje(
	        		"Baja de un vehículo", 
	        		"Baja de un vehículo", 
	        		"Error al borrar al vehículo: ".$id, 
	        		"vehiculos/".$pagina,
	        		"danger"
	        	);
	        }
	   }
	}

	public function caratula(string $pagina="1"):void
	{
		$num = $this->modelo->getNumRegistros("ordenreparacion");
		$inicio = ($pagina-1)*TAMANO_PAGINA;
		$totalPaginas = ceil($num/TAMANO_PAGINA);
		$data = $this->modelo->getTablaOrdenReparacion($inicio,TAMANO_PAGINA);
		$datos = [
			"titulo" => "Salida de una orden de reparación",
			"subtitulo" => "Salida de una orden de reparación",
			"usuario"=>$this->usuario,
			"data"=>$data,
			"activo" => "salidas",
			"pag" => [
				"totalPaginas" => $totalPaginas,
				"regresa" => "salidas",
				"pagina" => $pagina
			],
			"menu" => true
		];
		$this->vista("salidasCaratulaVista",$datos);
	}

	public function imprimirFactura(string $id,string $pagina,string $manoObra,string $otro,string $observacion):void{
		//
		$observacion = html_entity_decode(Helper::desencriptar($observacion));
		$data = $this->modelo->getOrdenReparacion($id);
		$piezas = $this->modelo->getPiezas($id);
		$razonSocial = $this->modelo->getRazonSocial();
		$materiales = 0;
		for ($i=0; $i < count($piezas); $i++) { 
			$materiales+=floatval($piezas[$i]["costo"]);
		}
		$iva = ($materiales+$otro+$manoObra)*($razonSocial["iva"]/100);
		$total = $materiales+$otro+$manoObra+$iva;
		$factura = $this->modelo->altaFactura($data, $manoObra, $otro, $materiales, $iva, $total, $observacion);
		if ($factura) {
			if ($this->modelo->cambiarEstadoOrdenReparacion($id)) {
				$encabezado = $razonSocial["razon"]."\n";
				$encabezado.= $razonSocial["direccion"]."\nTeléfonos: ";
				$encabezado.= $razonSocial["telefonos"]."\nruc: ".$razonSocial["ruc"];
				$encabezado.= "\nCorreo: ".$razonSocial["correo"];
				$encabezado.= "\nFactura: ".$factura;
				$encabezado.= "\nFecha: ".date("d/m/Y")." ".date("h:s A");
				//
				$cliente = "Cliente: ".$data["nombres"]." ".$data["apellidos"]."\n";
				$cliente.= "Razón social: ".$data["razonsocial"]."\n";
				$cliente.= "ruc: ".$data["ruc"]."\n";
				$cliente.= "Teléfonos: ".$data["telefono"]."\n";
				$cliente.= "Correo: ".$data["correo"];
				$cliente = html_entity_decode($cliente);
				//
				$vehiculo = "Marca: ".$data["marca"]."\n";
				$vehiculo.= "Modelo: ".$data["modelo"]."\n";
				$vehiculo.= "Color: ".$data["color"]."\n";
				$vehiculo.= "Año: ".$data["anio"]."\n";
				$vehiculo.= "Placas: ".$data["placas"];
				//
				$this->factura = new Imprimir($encabezado,$cliente,$vehiculo);
				$this->factura->AliasNbPages(); 
				$this->factura->AddPage();
				$this->factura->cuerpoDocumento($piezas,$manoObra,$otro,$razonSocial["iva"],$observacion);
				$this->factura->Output('D', 'factura_'.str_pad($factura, 5, "0", STR_PAD_LEFT).'.pdf');
				// Notificación por correo al cliente (y copia a correo del taller) por cambio de estado
				try {
                
                    $asunto = "🧾 Tu orden #" . $data['id'] . " ha sido facturada";
                    $urlPanel = rtrim(SITE_URL,'/') . "/";
                    $nombreCliente = htmlentities($data['nombres'] . ' ' . $data['apellidos'], ENT_QUOTES, 'UTF-8');
                    $montoTotal = number_format($total, 2);
                    
          
                    $html = "
                    <div style='font-family: Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; color: #333333;'>
                        
                        <div style='background-color: #12171D; padding: 25px; text-align: center;'>
                            <h1 style='color: #448AFF; margin: 0; font-size: 24px; letter-spacing: 1px;'>Xtreme Performance</h1>
                        </div>
                        
                        <div style='padding: 30px; background-color: #ffffff;'>
                            <h2 style='color: #12171D; margin-top: 0; font-size: 20px;'>¡Hola, {$nombreCliente}!</h2>
                            
                            <p style='font-size: 16px; line-height: 1.6; color: #555555;'>
                                Te informamos que el proceso de tu vehículo ha finalizado y tu <strong>Orden de Reparación #{$data['id']}</strong> ha sido facturada exitosamente.
                            </p>
                            
                            <div style='background-color: #F8F9FA; border-left: 4px solid #448AFF; padding: 15px 20px; margin: 25px 0; border-radius: 0 6px 6px 0; text-align: center;'>
                                <p style='margin: 0; font-size: 14px; color: #777777; text-transform: uppercase; letter-spacing: 1px;'>Total a pagar</p>
                                <p style='margin: 5px 0 0 0; font-size: 28px; color: #12171D; font-weight: bold;'>
                                    S/ {$montoTotal}
                                </p>
                            </div>
                            
                            <p style='font-size: 16px; line-height: 1.6; color: #555555;'>
                                Ya puedes ingresar a tu panel de cliente para ver el detalle completo de los servicios realizados y descargar tus comprobantes de pago.
                            </p>
                            
                            <div style='text-align: center; margin: 35px 0;'>
                                <a href='{$urlPanel}' style='background-color: #448AFF; color: #ffffff; padding: 14px 28px; text-decoration: none; font-size: 16px; font-weight: bold; border-radius: 6px; display: inline-block;'>
                                    Ver Detalle y Factura
                                </a>
                            </div>
                            
                            <hr style='border: 0; border-top: 1px solid #eeeeee; margin: 20px 0;'>
                            <p style='font-size: 13px; color: #888888; text-align: center; margin-bottom: 0;'>
                                Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                                <a href='{$urlPanel}' style='color: #448AFF; text-decoration: none; word-break: break-all;'>{$urlPanel}</a>
                            </p>
                        </div>
                        
                        <div style='background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #999999;'>
                            &copy; " . date('Y') . " Xtreme Performance. Todos los derechos reservados.
                        </div>
                        
                    </div>
                    ";
                    
                    // 3. Envío de correos usando la nueva plantilla HTML
                    $this->enviarCorreoPlano($data['correo'], $asunto, $html);
                    
                    if (!empty($razonSocial['correo'])) {
                        $this->enviarCorreoPlano($razonSocial['correo'], "[Copia] " . $asunto, $html);
                    }
                    
                } catch (\Throwable $e) { 
                    // Reemplazado el /* noop */ por un registro de error silencioso
                    error_log("Error enviando correo de facturación: " . $e->getMessage()); 
                }
				$this->mensaje(
				"Impresión de una orden de reparación", 
				"Impresión de una orden de reparación", 
				"Se generó correctamente la factura: ".$factura, 
				"salidas/".$pagina, 
				"success");
			} else {
				$this->mensaje(
				"Error en la generación de la orden de reparación", 
				"Error en la generación de la orden de reparación", 
				"Error en la generación de la orden de reparación", 
				"salidas/".$pagina, 
				"danger");
			}
		}

  	}

	public function modificar(string $id,string $pagina="1"):void
	{
		//Leemos los datos de la tabla
		$data = $this->modelo->getId($id);
	    $clientes = $this->modelo->getClientes();
		$datos = [
			"titulo" => "Modificar un vehículo",
			"subtitulo" =>"Modificar un vehículo",
			"menu" => true,
			"admon" => true,
			"usuario" => $this->usuario,
			"activo" => "vehiculos",
			"clientes" => $clientes,
			"pagina" => $pagina,
			"data" => $data
		];
		$this->vista("vehiculosAltaVista",$datos);
	}

	public function mensajeFacturar()
	{
		if ($_SERVER['REQUEST_METHOD']=="POST") {
			//
			$id = Helper::cadena($_POST['idOrdenReparacion'] ?? "");
			$manoObra = $_POST['manoObra'] ?? "";
			$otro = Helper::cadena($_POST['otro'] ?? "");
			$observacion = Helper::encriptar(Helper::cadena($_POST['observacion'] ?? ""));
			$pagina = $_POST['pagina'] ?? "1";
			//
			// Verificar si existe la imagen del QR
			$qrImagePath = "../public/img/yape-qr.png";
			$qrImageHTML = "";
			if (file_exists($qrImagePath)) {
				$qrImageHTML = "<br><div style='text-align: center; margin: 15px 0; padding: 10px; background: #f8f9fa; border-radius: 8px;'>" .
							   "<img src='".RUTA."public/img/yape-qr.png?v=".time()."' style='max-width: 180px; height: auto; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>" .
							   "<br><small style='color: #666; font-size: 12px; margin-top: 8px; display: block; font-weight: bold;'>💳 Escanea para pagar con Yape</small></div>";
			}
			
			$this->mensaje(
				"Facturar orden de reparación.", 
				"Facturar orden de reparación.", 
				"¿Desea generar la factura? Una vez creada no podrá modificarla." . $qrImageHTML, 
				"salidas/".$pagina,
				"danger",
				"salidas/imprimirFactura/".$id."/".$pagina."/".$manoObra."/".$otro."/".$observacion,
				"success",
				"Facturar"
			);
		}
	}

	public function salida(string $idOrdenReparacion="",string $pagina="1"){
		//Leemos los datos del registro del id
		$data = $this->modelo->getOrdenReparacion($idOrdenReparacion);
		$piezas = $this->modelo->getPiezas($idOrdenReparacion);
		$datos = [
		  "titulo" => "Salida de una órden de reparación",
		  "subtitulo" => "Salida de una órden de reparación",
		  "menu" => true,
		  "admon" => true,
		  "errores" => [],
		  "usuario" => $this->usuario,
		  "activo" => 'salidas',
		  "data" => $data,
		  "piezas" => $piezas,
		  "pagina" => $pagina
		];
		$this->vista("salidasAltaVista",$datos);
	}
}
?>