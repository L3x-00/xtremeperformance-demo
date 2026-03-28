<?php include_once("encabezado.php"); ?>

  <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="btn-group" role="group" aria-label="Exportar">
      <a class="btn btn-outline-secondary" href="<?php print RUTA; ?>clientes/exportarCsv">Exportar CSV</a>
      <a class="btn btn-outline-secondary" href="<?php print RUTA; ?>clientes/exportarPdf">Exportar PDF</a>
    </div>
    <input type="search" id="filterClientes" class="form-control" style="max-width:320px" placeholder="Buscar en esta página...">
  </div>

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

<script>
(function(){
  const input = document.getElementById('filterClientes');
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