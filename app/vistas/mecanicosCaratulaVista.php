<?php include_once("encabezado.php"); ?>

  <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="btn-group" role="group" aria-label="Exportar">
      <a class="btn btn-outline-secondary" href="<?php print RUTA; ?>mecanicos/exportarCsv">Exportar CSV</a>
      <a class="btn btn-outline-secondary" href="<?php print RUTA; ?>mecanicos/exportarPdf">Exportar PDF</a>
    </div>
    <input type="search" id="filterMecanicos" class="form-control" style="max-width:320px" placeholder="Buscar en esta página...">
  </div>

  <div class="table-responsive">
    <table id="tablaMecanicos" class="table table-striped table-hover align-middle" style="width: 100%; margin-bottom: 0;">
      <thead>
        <tr>
          <th>id</th>
          <th>Nombre</th>
          <th>Tipo</th>
          <th>Estado</th>
          <th>Modificar</th>
          <th>Borrar</th>
        </tr>
      </thead>
      <tbody>
        <?php
        for($i=0; $i<count($datos['data']); $i++){
          print "<tr>";
          print "<td class='text-start' data-label='ID'>".$datos["data"][$i]['id']."</td>";
          print "<td class='text-start' data-label='Nombre'>".$datos["data"][$i]['nombre']."</td>";
          print "<td class='text-start' data-label='Tipo'>".$datos["data"][$i]['tipo']."</td>";
          print "<td class='text-start' data-label='Estado'>".$datos["data"][$i]['estado']."</td>";
          print "<td data-label='Modificar'><a href='".RUTA."mecanicos/modificar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' class='btn btn-info'>Modificar</a></td>";
          print "<td data-label='Borrar'><a href='".RUTA."mecanicos/borrar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' class='btn btn-danger'>Borrar</a></td>";
          print "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    <?php include_once("paginacion.php"); ?> 
    
    <a href="<?php print RUTA; ?>mecanicos/alta" class="btn btn-success mt-3">
      Dar de alta un mecánico
    </a>
  </div>

<?php include_once("piepagina.php"); ?>         

<script>
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('filterMecanicos');
  const table = document.getElementById('tablaMecanicos'); 
  
  if (!input || !table) return; 
  
  const tbody = table.querySelector('tbody');
  if (!tbody) return;
  const rows = Array.from(tbody.rows);
  
  input.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    
    rows.forEach(tr => {
      // textContent es mucho más estable para leer lo que hay dentro de la fila
      const text = tr.textContent.toLowerCase();
      tr.style.display = text.includes(q) ? '' : 'none';
    });
  });
});
</script>