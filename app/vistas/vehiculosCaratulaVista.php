<?php include_once("encabezado.php"); ?>
  
  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle" style="width: 100%; margin-bottom: 0;">
      <thead>
        <tr>
          <th>id</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>Año</th>
          <th>Placas</th>
          <th>Modificar</th>
          <th>Borrar</th>
        </tr>
      </thead>
      <tbody>
        <?php
        for($i=0; $i<count($datos['data']); $i++){
          print "<tr>";
          print "<td class='text-left' data-label='ID'>".$datos["data"][$i]['id']."</td>";
          print "<td class='text-left' data-label='Marca'>".$datos["data"][$i]['marca']."</td>";
          print "<td class='text-left' data-label='Modelo'>".$datos["data"][$i]['modelo']."</td>";
          print "<td class='text-left' data-label='Año'>".$datos["data"][$i]['anio']."</td>";
          print "<td class='text-left' data-label='Placas'>".$datos["data"][$i]['placas']."</td>";
          print "<td data-label='Modificar'><a href='".RUTA."vehiculos/modificar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' class='btn btn-info'>Modificar</a></td>";
          print "<td data-label='Borrar'><a href='".RUTA."vehiculos/borrar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' class='btn btn-danger'>Borrar</a></td>";
          print "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div> <div class="mt-4">
    <?php include_once("paginacion.php"); ?>
    
    <a href="<?php print RUTA; ?>vehiculos/alta" class="btn btn-success mt-3">
      Dar de alta un vehículo
    </a>
  </div>

<?php include_once("piepagina.php"); ?>