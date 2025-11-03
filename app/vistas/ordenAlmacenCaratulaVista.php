<?php include_once("encabezado.php"); ?>
  <div class="mb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="btn-group" role="group" aria-label="Exportar">
      <a class="btn btn-outline-secondary" href="<?php print RUTA; ?>OrdenAlmacen/exportarCsv">Exportar CSV</a>
      <a class="btn btn-outline-secondary" href="<?php print RUTA; ?>OrdenAlmacen/exportarPdf">Exportar PDF</a>
    </div>
    <input type="search" id="filterOrdenAlmacen" class="form-control" style="max-width:320px" placeholder="Buscar en la tabla...">
  </div>
  <div class="table-responsive">
  <table class="table table-striped table-hover align-middle" width="100%">
  <thead>
    <tr>
    <th>id</th>
    <th>Orden Reparación</th>
    <th>Costo</th>
    <th>Fecha</th>
    <th>Estado</th>
    <th>Mostrar</th>
    <th>Borrar</th>
  </tr>
  </thead>
  <tbody>
    <?php
    for($i=0; $i<count($datos['data']); $i++){
      print "<tr>";
      print "<td class='text-start'>".$datos["data"][$i]['id']."</td>";
      print "<td class='text-start'>".$datos["data"][$i]['vehiculo']."</td>";
  print "<td class='text-start'>S/ ".number_format($datos["data"][$i]['costo'],2)."</td>";
      print "<td class='text-start'>".$datos["data"][$i]['alta_dt']."</td>";
      print "<td class='text-start'>".$datos["data"][$i]['estado']."</td>";
  print "<td><a href='".RUTA."OrdenAlmacen/desplegarOrdenAlmacen/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' class='btn btn-warning'>Mostrar</a></td>";
  print "<td><a href='".RUTA."OrdenAlmacen/borrar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' class='btn btn-danger ";
      if ($datos["data"][$i]["idEstado"]==ORDEN_FACTURADA) {
        print " disabled ";
      }
      print "'>Borrar</a></td>";
      print "</tr>";
    }
    ?>
  </tbody>
  </table>
  <?php include_once("paginacion.php"); ?> 
<a href="<?php print RUTA; ?>OrdenAlmacen/alta" class="btn btn-success">
  Dar de alta una orden de almacén</a>
<?php include_once("piepagina.php"); ?>					
<script>
(function(){
  const input = document.getElementById('filterOrdenAlmacen');
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