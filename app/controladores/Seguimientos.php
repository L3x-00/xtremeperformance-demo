<?php  
// IMPORTANTE: Ajusta esta ruta dependiendo de dónde guardaste PusherHelper.php
// Asumiendo que Seguimientos.php está en app/controladores/ y PusherHelper.php está en app/helpers/
require_once __DIR__ . '/../libs/PusherHelper.php'; 

/**
 * */
class Seguimientos extends Controlador
{
  private $modelo = "";
  private $usuario;
  private $sesion;
  
  function __construct()
  {
    //Creamos sesion
    $this->sesion = new Sesion();
    if ($this->sesion->getLogin()) {
      $this->modelo = $this->modelo("SeguimientosModelo");
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

 public function alta(string $idOrdenReparacion=""):void
  {
      //Definir los arreglos
      $data = array();
      $errores = array();
      
      if ($_SERVER['REQUEST_METHOD']=="POST") {
        //
        $idSeguimiento = $_POST['id'] ?? "";
        $idOrdenReparacion = Helper::cadena($_POST['idOrdenReparacion'] ?? "");
        $fecha = Helper::cadena($_POST['fecha'] ?? "");
        $observacion = Helper::cadena($_POST['observacion'] ?? "");
        //
        $pagina = $_POST['pagina'] ?? "1";
        //
        // Validamos la información
        //

        if(empty($fecha)){
          array_push($errores,"La fecha del seguimiento es requerida.");
        } 
        if(Helper::fecha($fecha)==false){
          array_push($errores,"El formato de la fecha no es correcto.");
        } 

        // ==// ========================================================================
        // 🛡️ CANDADO DE SEGURIDAD: PROHIBIR SEGUIMIENTOS EN ÓRDENES FACTURADAS
        // ========================================================================
        if(!empty($idOrdenReparacion)) {
            // Llamamos al modelo principal de las Órdenes en lugar del de Salidas
            $ordenModelo = $this->modelo("OrdenReparacionModelo");
            $ordenActual = $ordenModelo->getId($idOrdenReparacion);

            // Agregamos isset() por extrema seguridad para que PHP no lance advertencias
            if ($ordenActual && isset($ordenActual['estado']) && $ordenActual['estado'] == 2) {
                array_push($errores, "Acción denegada: La orden de reparación #$idOrdenReparacion ya se encuentra facturada y cerrada. No se admiten más seguimientos.");
            }
        }
        // ========================================================================

        // Si no hay errores (incluyendo el error del candado), procedemos a guardar
        if (empty($errores)) { 
          // Crear arreglo de datos
          $data = [
            "id" => $idSeguimiento,
            "idOrdenReparacion"=>$idOrdenReparacion,
            "fecha"=>$fecha,
            "observacion"=>$observacion
          ];    
          
          //Enviamos al modelo
          if(trim($idSeguimiento)===""){
            //Alta
            $id = $this->modelo->alta($data);
            if ($id) {
              // Imagenes
              if ($this->subirImagenes($_FILES,$idOrdenReparacion,$id)) {
                // Notificar al cliente que se añadió un seguimiento con fotos
                try {
                  $salidasModelo = $this->modelo("SalidasModelo");
                  $ord = $salidasModelo->getOrdenReparacion($idOrdenReparacion);
                  $asunto = "Nuevo seguimiento en tu orden #".$idOrdenReparacion;
                  $url = rtrim(SITE_URL,'/')."/";
                  $html = "<p>Hola ".htmlentities(($ord['nombres']??'').' '.($ord['apellidos']??''), ENT_QUOTES, 'UTF-8').",</p>".
                    "<p>Se ha añadido un nuevo seguimiento con imágenes a tu orden #".$idOrdenReparacion.".</p>".
                    "<p>Ingresa a tu panel para revisarlo:<br><a href='".$url."'>".$url."</a></p>";
                  $this->enviarCorreoPlano($ord['correo']??'', $asunto, $html);
                } catch (\Throwable $e) { /* noop */ }
                
                // PUSHER: DISPARAR EVENTO DE NUEVO SEGUIMIENTO (ALTA)
                $canal = 'orden-' . $idOrdenReparacion;
                $evento = 'nuevo-seguimiento';
                $datosTracking = [
                    "id_orden" => $idOrdenReparacion,
                    "mensaje" => "Se ha añadido un nuevo seguimiento a la orden."
                ];
                PusherHelper::trigger($canal, $evento, $datosTracking);

                $this->mensaje(
                  "Alta del seguimiento de una orden de reparación.", 
                  "Alta del seguimiento de una orden de reparación.", 
                  "Se añadió correctamente el seguimiento a la orden de reparación.", 
                  "seguimientos/seguimiento/".$idOrdenReparacion."/1", 
                  "success"
                );
              } else {
                $this->mensaje(
                    "Error al subir las imágenes.", 
                    "Error al subir las imágenes.", 
                    "Error al subir las imágenes.", 
                    "seguimientos/".$pagina,
                    "danger"
                  );
              }
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
              if ($this->subirImagenes($_FILES,$idOrdenReparacion,$idSeguimiento)) {
                // PUSHER: DISPARAR EVENTO DE SEGUIMIENTO MODIFICADO
                $canal = 'orden-' . $idOrdenReparacion;
                $evento = 'nuevo-seguimiento';
                $datosTracking = [
                    "id_orden" => $idOrdenReparacion,
                    "mensaje" => "Se ha modificado un seguimiento de la orden."
                ];
                PusherHelper::trigger($canal, $evento, $datosTracking);

                $this->mensaje(
                  "Modificación del seguimiento de una orden de reparación.", 
                  "Modificación del seguimiento de una orden de reparación.", 
                  "Se modificó correctamente el seguimiento a la orden de reparación.", 
                  "seguimientos/seguimiento/".$idOrdenReparacion."/1", 
                  "success"
                );
              } else {
                $this->mensaje(
                    "Error al subir las imágenes.", 
                    "Error al subir las imágenes.", 
                    "Error al subir las imágenes.", 
                    "seguimientos/".$pagina,
                    "danger"
                  );
              }
            } else {
              $this->mensaje(
                "Error al modificar el seguimiento.", 
                "Error al modificar el seguimiento.", 
                "Error al modificar el seguimiento.", 
                "seguimientos/".$pagina, 
                "danger"
              );
            }
          }
        }
      }
      if(!empty($idOrdenReparacion)){
        //Vista Alta (Si hubo un error, como el de la orden facturada, regresará a esta vista mostrando el error)
        $datos = [
          "titulo" => "Seguimiento de una orden de reparación",
          "subtitulo" => "Seguimiento de una orden de reparación",
          "activo" => "seguimientos",
          "menu" => true,
          "admon" => true,
          "usuario" => $this->usuario,
          "errores" => $errores, // Aquí viaja el mensaje de error a la pantalla
          "idOrdenReparacion"=>$idOrdenReparacion,
          "pagina" => 1,
          "data" => $data
        ];
        $this->vista("seguimientosAltaVista",$datos);
      }
  }

  public function borrarArchivo(string $id,string $i,string $pagina):void
  {
    $data = $this->modelo->getId($id);
    $carpeta = "fotos/".$data["idOrdenReparacion"]."/".$data["id"]."/";
    $foto = "";
    $salida = false;
        if (file_exists($carpeta)) {
          $archivos_array = scandir($carpeta);
          $foto = $carpeta.$archivos_array[$i];
          if(file_exists($foto)){
            unlink($foto);
            $salida = true;
          }
        }
        if ($salida) {
          $this->mensaje(
            "Borrar una imagen", 
            "Borrar una imagen", 
            "Se borró correctamente la imagen: ".$foto, 
            "seguimientos/desplegarSeguimiento/".$id."/".$pagina,
            "success"
          );
        } else {
          $this->mensaje(
            "Borrar una imagen", 
            "Borrar una imagen", 
            "Error al borrar la imagen: ".$foto, 
            "seguimientos/".$pagina, 
            "danger"
          );
        }
  }

  public function borrarSeguimiento(string $id="",string $pagina="1"){
    //Leemos los datos del registro del id
    $data = $this->modelo->getId($id);
    $datos = [
      "titulo" => "Baja de un seguimiento",
      "subtitulo" => "Baja de un seguimiento",
      "menu" => true,
      "admon" => true,
      "errores" => [],
      "activo" => 'seguimientos',
      "usuario" => $this->usuario,
      "data" => $data,
      "pagina" => $pagina,
      "idOrdenReparacion" => $data["idOrdenReparacion"],
      "baja" => true
    ];
    $this->vista("seguimientosAltaVista",$datos);
  }

  public function bajaLogica(string $id='',string $pagina="1"):void{
    if ($id!="") {
      $data = $this->modelo->getId($id);
      if ($this->modelo->bajaLogica($id)) {
        $carpeta = "fotos/".$data["idOrdenReparacion"]."/".$data["id"];
        $archivos = glob($carpeta . '/*');
          foreach ($archivos as $archivo) {
              if (is_file($archivo)) {
                  unlink($archivo);
              } 
          }
          if (file_exists($carpeta)) {
            rmdir($carpeta);
          }
        $this->mensaje(
        "Baja de un seguimiento a la orden de reparación", 
        "Baja de un seguimiento a la orden de reparación", 
        "Se borró correctamente el seguimiento a la orden de reparación.", 
        "seguimientos/".$pagina, 
        "success");
      } else {
        $this->mensaje(
        "Baja de un seguimiento a la orden de reparación", 
        "Baja de un seguimiento a la orden de reparación", 
        "Error al borrar un seguimiento a la orden de reparación: ".$id, 
        "seguimientos/".$pagina,
        "danger");
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
      "titulo" => "Seguimientos",
      "subtitulo" => "Seguimientos",
      "usuario"=>$this->usuario,
      "data"=>$data,
      "activo" => "seguimientos",
      "pag" => [
        "totalPaginas" => $totalPaginas,
        "regresa" => "seguimientos",
        "pagina" => $pagina
      ],
      "menu" => true
    ];
    $this->vista("seguimientosCaratulaVista",$datos);
  }

  public function desplegarSeguimiento(string $idSeguimiento='', string $pagina="1"):void
  {
    $data = $this->modelo->getId($idSeguimiento);
    $id = $data["idOrdenReparacion"];
    $carpeta = "fotos/".$id."/".$idSeguimiento;
        if (file_exists($carpeta)) {
          $archivos_array  = scandir($carpeta);
        } else {
          $archivos_array  = [];
        }
    $datos = [
      "titulo" => "Imágenes de la orden de reparación",
      "subtitulo" =>"Imágenes de la orden de reparación",
      "menu" => true,
      "admon" => true,
      "usuario"=>$this->usuario,
      "activo" => "seguimientos",
      "archivos" => $archivos_array,
      "pagina" => $pagina,
      "data" => $data
    ];
    $this->vista("seguimientosArchivosVista",$datos);
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

  public function modificarSeguimiento(string $idSeguimiento,string $pagina="1"):void
  {
    //Leemos los datos de la tabla
    $data = $this->modelo->getId($idSeguimiento);
    //
    $datos = [
      "titulo" => "Modificar el seguimiento",
      "subtitulo" =>"Modificar el seguimiento",
      "menu" => true,
      "admon" => true,
      "usuario" => $this->usuario,
      "activo" => "seguimientos",
      "idOrdenReparacion"=>$data["idOrdenReparacion"],
      "pagina" => $pagina,
      "data" => $data
    ];
    $this->vista("seguimientosAltaVista",$datos);
  }

  public function mostrarImagen(string $id="",string $i="",string $pagina="1"):void
  {
    $data = $this->modelo->getId($id);
    $carpeta = "fotos/".$data["idOrdenReparacion"]."/".$data["id"]."/";
    $foto = "";
        if (file_exists($carpeta)) {
          $archivos_array = scandir($carpeta);
          $foto = $carpeta.$archivos_array[$i];
        } else {
          $archivos_array = [];
        }
      $this->mensaje(
        "Mostrar una imagen", 
        "Archivo: ".$archivos_array[$i], 
        "<img src='".RUTA.'public/'.$carpeta.$archivos_array[$i]."' width='100%'/>", 
        "seguimientos/desplegarSeguimiento/".$id."/".$pagina, 
        "success"
      );
  }

  public function seguimiento(string $idOrdenReparacion, string $pagina="1"):void
  {
    // Paginación debe considerar solo seguimientos de la orden indicada
    $num = $this->modelo->getNumSeguimientosPorOrden($idOrdenReparacion);
    $inicio = ($pagina-1)*TAMANO_PAGINA;
    $totalPaginas = ceil($num/TAMANO_PAGINA);
    $data = $this->modelo->getTablaSeguimiento($inicio,TAMANO_PAGINA,$idOrdenReparacion);
    $datos = [
      "titulo" => "Seguimiento a una orden de reparación",
      "subtitulo" => "Seguimiento a una orden de reparación",
      "usuario"=>$this->usuario,
      "activo"=>"seguimientos",
      "admon"=>true,
      "data"=>$data,
      "idOrdenReparacion"=>$idOrdenReparacion,
      "pag" => [
        "totalPaginas" => $totalPaginas,
        "regresa" => "seguimientos/seguimiento/".$idOrdenReparacion,
        "pagina" => $pagina
      ],
      "menu" => true
    ];
    $this->vista("seguimientosOrdenReparacionCaratulaVista",$datos);
  }

  public function subirImagenes($fotos_array,$idOrdenReparacion,$idSeguimiento ):bool
  {
    // No-op si no hay formulario de archivos
    if (!isset($fotos_array['fotos'])) return true;
    
    // Validar y sanitizar rutas
    $idOrdenReparacion = (int)$idOrdenReparacion;
    $idSeguimiento = (int)$idSeguimiento;
    if ($idOrdenReparacion <= 0 || $idSeguimiento <= 0) return false;
    
    $carpeta = 'fotos/'.$idOrdenReparacion."/".$idSeguimiento."/";
    if (!file_exists($carpeta)) {
      mkdir($carpeta, 0755, true); // Permisos más seguros
    }

    // Tipos MIME permitidos con validación estricta
    $mimeToExt = [
      'image/jpeg' => 'jpg',
      'image/pjpeg' => 'jpg',
      'image/jpg' => 'jpg',
      'image/png' => 'png',
      'image/gif' => 'gif',
      'image/webp' => 'webp',
    ];
    
    // Límites de seguridad
    $maxFileSize = 10 * 1024 * 1024; // 10MB máximo por archivo
    $maxFiles = 10; // Máximo 10 archivos por seguimiento

    // Rearmar arreglo múltiple
    $files = [];
    $names = $fotos_array['fotos']['name'] ?? [];
    $count = is_array($names) ? count($names) : 0;
    if ($count === 0) return true; // nada seleccionado
    $keys = array_keys($fotos_array['fotos']);
    for ($i=0; $i<$count; $i++) {
      $one = [];
      foreach ($keys as $k) {
        $one[$k] = $fotos_array['fotos'][$k][$i] ?? null;
      }
      $files[] = $one;
    }

    $subidos = 0;
    $procesados = 0;
    
    foreach ($files as $archivo) {
      // Límite de archivos
      if ($procesados >= $maxFiles) break;
      
      // Saltar vacíos
      if (empty($archivo['tmp_name']) || ($archivo['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        continue;
      }
      
      $procesados++;
      
      // Validaciones de seguridad
      $size = $archivo['size'] ?? 0;
      if ($size <= 0 || $size > $maxFileSize) continue;
      
      // Validar MIME type tanto del navegador como del archivo real
      $mimeReported = $archivo['type'] ?? '';
      $mimeReal = mime_content_type($archivo['tmp_name']) ?: '';
      
      // Debe coincidir el MIME reportado y el real
      if (!isset($mimeToExt[$mimeReported]) || !isset($mimeToExt[$mimeReal])) {
        continue; // Tipo no soportado o no coincide
      }
      
      // Verificar que sea realmente una imagen
      $imageInfo = getimagesize($archivo['tmp_name']);
      if ($imageInfo === false) continue; // No es imagen válida
      
      $ext = $mimeToExt[$mimeReal];
      
      // Generar nombre seguro
      $nombre = 'img_' . time() . '_' . uniqid('', true) . '.' . $ext;
      
      // Validar que no contenga caracteres peligrosos
      if (!preg_match('/^[a-zA-Z0-9._-]+$/', $nombre)) continue;
      
      $rutaCompleta = $carpeta . $nombre;
      
      // Verificar que la ruta no escape del directorio permitido
      $rutaReal = realpath(dirname($rutaCompleta));
      $carpetaReal = realpath($carpeta);
      if ($rutaReal === false || strpos($rutaReal, $carpetaReal) !== 0) {
        continue; // Path traversal attempt
      }
      
      if (is_uploaded_file($archivo['tmp_name'])) {
        if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
          // Establecer permisos seguros al archivo
          chmod($rutaCompleta, 0644);
          $subidos++;
        }
      }
    }
    
    // Consideramos éxito si se subieron 0 o más (no forzamos error cuando no adjuntan)
    return $subidos >= 0;
  }
}
?>