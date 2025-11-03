<?php include_once("encabezado.php"); ?>
 <div class="container my-3">
  <div class="row g-3 mb-3">
    <div class="col-12 col-md-3">
      <div class="card text-bg-light h-100">
        <div class="card-body">
          <div class="small text-uppercase text-muted">Órdenes activas</div>
          <div class="display-6 fw-bold"><?php print intval($datos['kpis']['activas']??0); ?></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card text-bg-light h-100">
        <div class="card-body">
          <div class="small text-uppercase text-muted">Órdenes totales</div>
          <div class="display-6 fw-bold"><?php print intval($datos['kpis']['totales']??0); ?></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card text-bg-light h-100">
        <div class="card-body">
          <div class="small text-uppercase text-muted">Gasto total (S/)</div>
          <div class="display-6 fw-bold">S/ <?php print number_format(floatval($datos['kpis']['gasto_total']??0),2); ?></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card text-bg-light h-100">
        <div class="card-body">
          <div class="small text-uppercase text-muted">Gasto este mes (S/)</div>
          <div class="display-6 fw-bold">S/ <?php print number_format(floatval($datos['kpis']['gasto_mes']??0),2); ?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="table-responsive">
  <table class="table table-striped" width="100%">
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
      print "<td><a href='".RUTA."tableroCliente/mostrar/".$datos["data"][$i]["id"]."' class='btn btn-warning'>Mostrar</a></td>";
      print "</tr>";
    }
    ?>
  </tbody>
  </table>
  </div> 
 </div>
<?php include_once("piepagina.php"); ?>