<?php include_once("encabezado.php"); ?>
  <div class="mb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="btn-group" role="group" aria-label="Exportar">
      <a class="btn btn-outline-secondary" href="<?php print RUTA; ?>ordenReparacion/exportarCsv">Exportar CSV</a>
      <a class="btn btn-outline-secondary" href="<?php print RUTA; ?>ordenReparacion/exportarPdf">Exportar PDF</a>
    </div>
    <input type="search" id="filterOrdenReparacion" class="form-control" style="max-width:320px" placeholder="Buscar en la tabla...">
  </div>
  <div class="table-responsive">
  <table class="table table-striped table-hover align-middle" width="100%">
  <thead>
    <tr>
    <th>id</th>
    <th>Vehículo</th>
    <th>Fecha Ingreso</th>
    <th>Fecha Salida</th>
    <th>Mostrar</th>
    <th>Modificar</th>
    <th>Borrar</th>
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
      print "<td data-label='Mostrar'><a href='".RUTA."ordenReparacion/mostrar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' class='btn btn-warning'>Mostrar</a></td>";
      print "<td data-label='Modificar'><a href='".RUTA."ordenReparacion/modificar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' class='btn btn-info'>Modificar</a></td>";
      print "<td data-label='Borrar'><a href='".RUTA."ordenReparacion/borrar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' class='btn btn-danger'>Borrar</a></td>";
      print "</tr>";
    }
    ?>
  </tbody>
  </table>
  <?php include_once("paginacion.php"); ?> 
<a href="<?php print RUTA; ?>ordenReparacion/alta" class="btn btn-success">
  Dar de alta una orden de reparación</a>
<?php include_once("piepagina.php"); ?>
<script>
(function(){
  const input = document.getElementById('filterOrdenReparacion');
  const table = document.querySelector('table.table');
  if (!input || !table) return;
  const rows = Array.from(table.tBodies[0].rows);
  input.addEventListener('input', function(){
    const q = this.value.toLowerCase();
    rows.forEach(tr => {
      const text = tr.innerText.toLowerCase();
      tr.style.display = text.indexOf(q) !== -1 ? '' : 'none';
    });
  });
})();
</script>