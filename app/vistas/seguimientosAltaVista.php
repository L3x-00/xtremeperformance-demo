<?php include_once("encabezado.php"); ?>
  <form action="<?php print RUTA; ?>seguimientos/alta/" method="POST" enctype="multipart/form-data">

  <div class="form-group text-left">
    <label for="fecha">* Fecha de seguimiento:</label>
    <input type="date" name="fecha" id="fecha" class="form-control" required min="2026-01-01" max="<?php echo date('Y').'-12-31'; ?>" value="<?php print isset($datos['data']['fecha'])?$datos['data']['fecha']:''; ?>" <?php if (isset($datos["baja"])) { print " disabled "; }?>>
  </div>

  <div class="form-group text-left">
      <label for="observacion">Observación:</label>
      <textarea class="form-control" id="observacion" name="observacion" rows="3" <?php if (isset($datos["baja"])) { print " disabled "; }?>><?php print isset($datos['data']['observacion'])?$datos['data']['observacion']:''; ?></textarea>
    </div>

  <div class="form-group text-left">
      <label for="fotos">Foto:</label>
      <input type="file" name="fotos[]" id="fotos" multiple class="form-control" <?php if (isset($datos["baja"])) { print " disabled "; }?>>
      <small class="form-text text-muted">Por seguridad del navegador, las imágenes existentes no pueden preseleccionarse en este campo. Puedes subir imágenes nuevas o borrar/visualizar las actuales abajo.</small>
    </div>

  <?php if (!isset($datos["baja"]) && isset($datos['data']['id']) && isset($datos['idOrdenReparacion'])) { 
    $carpeta = "fotos/".$datos['idOrdenReparacion']."/".$datos['data']['id']."/"; 
    if (file_exists($carpeta)) { 
      $archivos = scandir($carpeta); 
      if (count($archivos) > 2) { ?>
        <div class="mt-3">
          <label class="form-label">Imágenes actuales:</label>
          <div class="d-flex flex-wrap gap-2 align-items-start">
            <?php for ($i=2; $i<count($archivos); $i++) { 
              $src = RUTA."public/".$carpeta.$archivos[$i]; ?>
              <div class="text-center me-2 mb-2">
                <a href="<?php print RUTA.'seguimientos/mostrarImagen/'.$datos['data']['id'].'/'.$i; ?>" target="_blank">
                  <img src="<?php print $src; ?>" alt="Imagen <?php print $i; ?>" class="border rounded" style="height:60px; width:auto;">
                </a>
                <div>
                  <a class="btn btn-sm btn-outline-danger mt-1" href="<?php print RUTA.'seguimientos/borrarImagen/'.$datos['data']['id'].'/'.$i; ?>" onclick="return confirm('¿Borrar imagen?');">Borrar</a>
                </div>
              </div>
            <?php } ?>
          </div>
          <div class="mt-2">
            <a class="btn btn-secondary btn-sm" href="<?php print RUTA.'seguimientos/desplegarSeguimiento/'.$datos['data']['id'].'/'.$datos['pagina']; ?>">Ver todas</a>
          </div>
        </div>
      <?php } 
    } 
  } ?>

 
    <div class="form-group text-start mb-3">
      <input type="hidden" name="id" id="id" value="<?php if (isset($datos['data']['id'])) { print $datos['data']['id']; } else { print ""; } ?>">
      <input type="hidden" name="pagina" id="pagina" value="<?php if (isset($datos['pagina'])) { print $datos['pagina']; } else { print "1"; } ?>">
      <input type="hidden" name="idOrdenReparacion" id="idOrdenReparacion" value="<?php if (isset($datos['idOrdenReparacion'])) { print $datos['idOrdenReparacion']; } else { print ""; } ?>">
    </div>
    <?php if (isset($datos["baja"])) { ?>
        <a href="<?php print RUTA; ?>seguimientos/bajaLogica/<?php print $datos['data']['id']."/".$datos["pagina"]; ?>" class="btn btn-danger">Borrar</a>
        <a href="<?php print RUTA.'seguimientos/seguimiento/'.$datos['idOrdenReparacion']."/".$datos['pagina']; ?>" class="btn btn-danger">Regresar</a>
        <p><b>Advertencia: una vez borrado el registro, no podrá recuperar la información.</b></p>
      <?php } else { ?>
      <input type="submit" value="Enviar" class="btn btn-success">
      <a href="<?php print RUTA.'seguimientos/seguimiento/'.$datos['idOrdenReparacion']."/".$datos['pagina']; ?>" class="btn btn-danger">Regresar</a>
      <?php } ?> 
  </form>
<?php include_once("piepagina.php"); ?>