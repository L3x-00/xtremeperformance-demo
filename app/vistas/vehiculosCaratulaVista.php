<?php include_once("encabezado.php"); ?>

  <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="btn-group" role="group" aria-label="Exportar">
      <a class="btn btn-outline-secondary" href="<?php print RUTA; ?>vehiculos/exportarCsv">Exportar CSV</a>
      <a class="btn btn-outline-secondary" href="<?php print RUTA; ?>vehiculos/exportarPdf">Exportar PDF</a>
    </div>
    <input type="search" id="filterVehiculos" class="form-control" style="max-width:320px" placeholder="Buscar en esta página...">
  </div>

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
  </div> 

  <div class="mt-4">
    <?php include_once("paginacion.php"); ?>
    
    <a href="<?php print RUTA; ?>vehiculos/alta" class="btn btn-success mt-3">
      Dar de alta un vehículo
    </a>
  </div>

<?php include_once("piepagina.php"); ?>

<script>
(function(){
  const input = document.getElementById('filterVehiculos');
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