<?php include_once("encabezado.php"); ?>
 <div class="container my-3">
  <div class="row g-3 mb-3">
    <div class="col-12 col-md-3">
      <div class="card text-bg-light h-100 xp-kpi-card">
        <div class="card-body">
          <div class="small text-uppercase text-muted fw-semibold mb-1">Mis abiertas</div>
          <div class="display-6 fw-bold text-primary"><?php print intval($datos['kpis']['abiertas']??0); ?></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card text-bg-light h-100 xp-kpi-card">
        <div class="card-body">
          <div class="small text-uppercase text-muted fw-semibold mb-1">Facturadas</div>
          <div class="display-6 fw-bold text-success"><?php print intval($datos['kpis']['facturadas']??0); ?></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card text-bg-light h-100 xp-kpi-card">
        <div class="card-body">
          <div class="small text-uppercase text-muted fw-semibold mb-1">Asignadas</div>
          <div class="display-6 fw-bold text-dark"><?php print intval($datos['kpis']['totales']??0); ?></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card text-bg-light h-100 xp-kpi-card">
        <div class="card-body">
          <div class="small text-uppercase text-muted fw-semibold mb-1">Órdenes este mes</div>
          <div class="display-6 fw-bold" style="color: var(--xp-red);"><?php print intval($datos['kpis']['este_mes']??0); ?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="table-responsive">
  <table class="table table-striped table-hover align-middle" width="100%">
  <thead>
    <tr>
    <th>id</th>
    <th>Vehículo</th>
    <th>Fecha Ingreso</th>
    <th>Fecha Salida</th>
    <th>Estado</th>
    <th>Mostrar</th>
  </tr>
  </thead>
  <tbody>
    <?php
    for($i=0; $i<count($datos['data']); $i++){
      print "<tr>";
      print "<td class='text-start'>".$datos["data"][$i]['id']."</td>";
      print "<td class='text-start'>".$datos["data"][$i]['vehiculo']."</td>";
      print "<td class='text-start'>".$datos["data"][$i]['fechaIngreso']."</td>";
      print "<td class='text-start'>".$datos["data"][$i]['fechaSalida']."</td>";
      print "<td class='text-start'>".$datos["data"][$i]['estado']."</td>";
      print "<td><a href='".RUTA."tableroMecanico/mostrar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' class='btn btn-warning'>Mostrar</a></td>";
      print "</tr>";
    }
    ?>
  </tbody>
  </table>
  <?php include_once("paginacion.php"); ?>
 </div>
 </div>
<?php include_once("piepagina.php"); ?>