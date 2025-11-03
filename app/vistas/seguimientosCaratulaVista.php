<?php include_once("encabezado.php"); ?>
  <div class="table-responsive">
  <table class="table table-striped table-hover align-middle" width="100%">
  <thead>
    <tr>
    <th>id</th>
    <th>Vehículo</th>
    <th>Fecha ingreso</th>
    <th>Fecha salida</th>
    <th>Seguimiento</th>
  </tr>
  </thead>
  <tbody>
    <?php
    for($i=0; $i<count($datos['data']); $i++){
      print "<tr>";
      print "<td class='text-start' data-label='ID'>".$datos["data"][$i]['id']."</td>";
      print "<td class='text-start' data-label='Vehículo'>".$datos["data"][$i]['vehiculo']."</td>";
      print "<td class='text-start' data-label='Fecha Ingreso'>".$datos["data"][$i]['fechaIngreso']."</td>";
      print "<td class='text-start' data-label='Fecha Salida'>".$datos["data"][$i]['fechaSalida']."</td>";
  // Ir siempre a la página 1 del listado de seguimientos de esa orden
  print "<td data-label='Seguimiento'><a href='".RUTA."seguimientos/seguimiento/".$datos["data"][$i]["id"]."/1' class='btn btn-warning'>Seguimiento</a></td>";
      print "</tr>";
    }
    ?>
  </tbody>
  </table>
  <?php include_once("paginacion.php"); ?> 
<?php include_once("piepagina.php"); ?>					