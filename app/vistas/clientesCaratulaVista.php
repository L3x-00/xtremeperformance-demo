<?php include_once("encabezado.php"); ?>
  
  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle" style="width: 100%; margin-bottom: 0;">
      <thead>
        <tr>
          <th>id</th>
          <th>Nombre</th>
          <th>Razón Social</th>
          <th>Estado</th>
          <th>Modificar</th>
          <th>Borrar</th>
        </tr>
      </thead>
      <tbody>
        <?php
        for($i=0; $i<count($datos['data']); $i++){
          print "<tr>";
          print "<td class='text-left' data-label='ID'>".$datos["data"][$i]['id']."</td>";
          print "<td class='text-left' data-label='Nombre'>".$datos["data"][$i]['nombre']."</td>";
          print "<td class='text-left' data-label='Razón Social'>".$datos["data"][$i]['razonSocial']."</td>";
          print "<td class='text-left' data-label='Estado'>".$datos["data"][$i]['estado']."</td>";
          print "<td data-label='Modificar'><a href='".RUTA."clientes/modificar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' class='btn btn-info'>Modificar</a></td>";
          print "<td data-label='Borrar'><a href='".RUTA."clientes/borrar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' class='btn btn-danger'>Borrar</a></td>";
          print "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    <?php include_once("paginacion.php"); ?>
    
    <a href="<?php print RUTA; ?>clientes/alta" class="btn btn-success mt-3">
      Dar de alta un cliente
    </a>
  </div>

<?php include_once("piepagina.php"); ?>