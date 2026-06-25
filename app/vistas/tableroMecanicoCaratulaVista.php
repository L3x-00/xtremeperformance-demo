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
<?php include_once("piepagina.php"); ?><?php include_once("encabezado.php"); ?>

<style>
/* ═══════════════════════════════════════════
   CAPA 1 — TABLERO CLIENTE · XTREME PERFORMANCE
   Solo estilos visuales, sin tocar lógica
   ═══════════════════════════════════════════ */

/* Tokens de color */
:root {
  --xp-red:       #C62828;
  --xp-red-soft:  #EF5350;
  --xp-dark:      #1E2230;
  --xp-card-bg:   rgba(255, 255, 255, 0.72);
  --xp-card-blur: blur(14px);
  --xp-border:    rgba(255, 255, 255, 0.45);
  --xp-shadow:    0 4px 24px rgba(30, 34, 48, 0.10);
  --xp-radius:    16px;
  --xp-radius-sm: 10px;
}

[data-theme="dark"] {
  --xp-card-bg:   rgba(30, 34, 48, 0.72);
  --xp-border:    rgba(255, 255, 255, 0.08);
  --xp-shadow:    0 4px 24px rgba(0, 0, 0, 0.35);
}

/* ── Fondo general ── */
.xp-client-wrapper {
  padding: 2rem 1.25rem;
  min-height: 100vh;
}

/* ── KPI Cards ── */
.xp-kpi-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
  gap: 1.1rem;
  margin-bottom: 2.2rem;
}

.xp-kpi {
  background: var(--xp-card-bg);
  backdrop-filter: var(--xp-card-blur);
  -webkit-backdrop-filter: var(--xp-card-blur);
  border: 1px solid var(--xp-border);
  border-radius: var(--xp-radius);
  box-shadow: var(--xp-shadow);
  padding: 1.4rem 1.25rem 1.2rem;
  position: relative;
  overflow: hidden;
  transition: transform 0.22s ease, box-shadow 0.22s ease;
}

.xp-kpi:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 32px rgba(30, 34, 48, 0.15);
}

/* Acento de color superior (línea fina) */
.xp-kpi::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  border-radius: var(--xp-radius) var(--xp-radius) 0 0;
}

.xp-kpi--blue::before   { background: linear-gradient(90deg, #448AFF, #82B1FF); }
.xp-kpi--dark::before   { background: linear-gradient(90deg, #546E7A, #90A4AE); }
.xp-kpi--green::before  { background: linear-gradient(90deg, #2E7D32, #66BB6A); }
.xp-kpi--red::before    { background: linear-gradient(90deg, #C62828, #EF5350); }

/* Icono de fondo decorativo */
.xp-kpi__bg-icon {
  position: absolute;
  right: 1rem; bottom: 0.5rem;
  font-size: 3.5rem;
  opacity: 0.06;
  pointer-events: none;
  line-height: 1;
}

.xp-kpi__label {
  font-size: 0.7rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #888;
  margin-bottom: 0.45rem;
}

.xp-kpi__value {
  font-size: 1.9rem;
  font-weight: 800;
  line-height: 1;
  margin: 0;
}

.xp-kpi--blue  .xp-kpi__value { color: #448AFF; }
.xp-kpi--dark  .xp-kpi__value { color: #546E7A; }
.xp-kpi--green .xp-kpi__value { color: #2E7D32; }
.xp-kpi--red   .xp-kpi__value { color: #C62828; }

[data-theme="dark"] .xp-kpi--dark .xp-kpi__value { color: #90A4AE; }
[data-theme="dark"] .xp-kpi__label { color: #aaa; }

/* ── Sección de tabla ── */
.xp-table-card {
  background: var(--xp-card-bg);
  backdrop-filter: var(--xp-card-blur);
  -webkit-backdrop-filter: var(--xp-card-blur);
  border: 1px solid var(--xp-border);
  border-radius: var(--xp-radius);
  box-shadow: var(--xp-shadow);
  overflow: hidden;
}

.xp-table-card__header {
  padding: 1.2rem 1.5rem 1rem;
  display: flex;
  align-items: center;
  gap: 0.65rem;
  border-bottom: 1px solid var(--xp-border);
}

.xp-table-card__header h5 {
  margin: 0;
  font-size: 0.95rem;
  font-weight: 700;
  letter-spacing: 0.02em;
}

.xp-table-card__header .xp-dot {
  width: 8px; height: 8px;
  border-radius: 50%;
  background: var(--xp-red);
  flex-shrink: 0;
}

/* Tabla limpia */
.xp-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.875rem;
}

.xp-table thead tr {
  border-bottom: 1px solid var(--xp-border);
}

.xp-table thead th {
  padding: 0.9rem 1.25rem;
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 0.09em;
  text-transform: uppercase;
  color: #999;
  white-space: nowrap;
}

.xp-table tbody tr {
  border-bottom: 1px solid var(--xp-border);
  transition: background 0.15s ease;
}

.xp-table tbody tr:last-child {
  border-bottom: none;
}

.xp-table tbody tr:hover {
  background: rgba(68, 138, 255, 0.04);
}

.xp-table tbody td {
  padding: 1rem 1.25rem;
  vertical-align: middle;
}

/* Columna ID */
.xp-table .xp-id {
  font-size: 0.75rem;
  font-weight: 700;
  color: #999;
  font-variant-numeric: tabular-nums;
}

/* Vehículo con ícono */
.xp-vehicle {
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.xp-vehicle__icon {
  width: 34px; height: 34px;
  border-radius: 8px;
  background: linear-gradient(135deg, #448AFF22, #448AFF11);
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem;
  flex-shrink: 0;
}

.xp-vehicle__name {
  font-weight: 600;
  font-size: 0.875rem;
  line-height: 1.3;
}

/* Fechas */
.xp-date {
  font-size: 0.8rem;
  color: #777;
  font-variant-numeric: tabular-nums;
}

/* Badges de estado */
.xp-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.3rem 0.75rem;
  border-radius: 100px;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.04em;
  white-space: nowrap;
}

.xp-badge::before {
  content: '';
  width: 6px; height: 6px;
  border-radius: 50%;
  flex-shrink: 0;
}

.xp-badge--open {
  background: rgba(68, 138, 255, 0.12);
  color: #1565C0;
}
.xp-badge--open::before { background: #448AFF; }

.xp-badge--invoiced {
  background: rgba(46, 125, 50, 0.12);
  color: #2E7D32;
}
.xp-badge--invoiced::before { background: #43A047; }

.xp-badge--other {
  background: rgba(120, 120, 120, 0.12);
  color: #666;
}
.xp-badge--other::before { background: #999; }

[data-theme="dark"] .xp-badge--open     { background: rgba(68,138,255,0.2); color: #82B1FF; }
[data-theme="dark"] .xp-badge--invoiced { background: rgba(46,125,50,0.2);  color: #81C784; }
[data-theme="dark"] .xp-badge--other    { background: rgba(120,120,120,0.2); color: #bbb; }

/* Botón mostrar */
.xp-btn-show {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.4rem 0.9rem;
  border-radius: var(--xp-radius-sm);
  background: linear-gradient(135deg, #448AFF, #1565C0);
  color: #fff;
  font-size: 0.78rem;
  font-weight: 600;
  text-decoration: none;
  border: none;
  transition: opacity 0.18s ease, transform 0.18s ease;
  white-space: nowrap;
}

.xp-btn-show:hover {
  color: #fff;
  opacity: 0.88;
  transform: scale(1.03);
}

/* Responsive: en móvil ocultar columna ID y fechas */
@media (max-width: 640px) {
  .xp-table .col-id,
  .xp-table .col-fecha-entrada,
  .xp-table .col-fecha-salida { display: none; }
  .xp-kpi__value { font-size: 1.6rem; }
}

/* Estado vacío */
.xp-empty {
  text-align: center;
  padding: 3rem 1rem;
  color: #aaa;
}
.xp-empty svg { opacity: 0.3; margin-bottom: 0.75rem; }
</style>

<div class="xp-client-wrapper">

  <!-- ── KPI Cards ── -->
  <div class="xp-kpi-grid">

    <div class="xp-kpi xp-kpi--blue">
      <div class="xp-kpi__label">Órdenes activas</div>
      <p class="xp-kpi__value"><?php print intval($datos['kpis']['activas'] ?? 0); ?></p>
      <span class="xp-kpi__bg-icon">⚙</span>
    </div>

    <div class="xp-kpi xp-kpi--dark">
      <div class="xp-kpi__label">Órdenes totales</div>
      <p class="xp-kpi__value"><?php print intval($datos['kpis']['totales'] ?? 0); ?></p>
      <span class="xp-kpi__bg-icon">📋</span>
    </div>

    <div class="xp-kpi xp-kpi--green">
      <div class="xp-kpi__label">Gasto total (S/)</div>
      <p class="xp-kpi__value">S/&nbsp;<?php print number_format(floatval($datos['kpis']['gasto_total'] ?? 0), 2); ?></p>
      <span class="xp-kpi__bg-icon">💰</span>
    </div>

    <div class="xp-kpi xp-kpi--red">
      <div class="xp-kpi__label">Gasto este mes (S/)</div>
      <p class="xp-kpi__value">S/&nbsp;<?php print number_format(floatval($datos['kpis']['gasto_mes'] ?? 0), 2); ?></p>
      <span class="xp-kpi__bg-icon">📅</span>
    </div>

  </div>

  <!-- ── Tabla de Órdenes ── -->
  <div class="xp-table-card">

    <div class="xp-table-card__header">
      <span class="xp-dot"></span>
      <h5>Mis órdenes de reparación</h5>
    </div>

    <div class="table-responsive">
      <table class="xp-table">
        <thead>
          <tr>
            <th class="col-id">#</th>
            <th>Vehículo</th>
            <th class="col-fecha-entrada">Ingreso</th>
            <th class="col-fecha-salida">Salida</th>
            <th>Estado</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($datos['data']) === 0): ?>
            <tr>
              <td colspan="6">
                <div class="xp-empty">
                  <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>
                  <p>No hay órdenes registradas</p>
                </div>
              </td>
            </tr>
          <?php else: ?>
            <?php for ($i = 0; $i < count($datos['data']); $i++): ?>
              <?php
                $estado   = $datos['data'][$i]['estado'] ?? '';
                $badgeClass = 'xp-badge--other';
                if (stripos($estado, 'abierta') !== false)   $badgeClass = 'xp-badge--open';
                if (stripos($estado, 'facturada') !== false) $badgeClass = 'xp-badge--invoiced';
              ?>
              <tr>
                <td class="col-id">
                  <span class="xp-id"><?php print $datos['data'][$i]['id']; ?></span>
                </td>
                <td>
                  <div class="xp-vehicle">
                    <div class="xp-vehicle__icon">🚗</div>
                    <span class="xp-vehicle__name"><?php print $datos['data'][$i]['vehiculo']; ?></span>
                  </div>
                </td>
                <td class="col-fecha-entrada">
                  <span class="xp-date"><?php print $datos['data'][$i]['fechaIngreso']; ?></span>
                </td>
                <td class="col-fecha-salida">
                  <span class="xp-date"><?php print $datos['data'][$i]['fechaSalida']; ?></span>
                </td>
                <td>
                  <span class="xp-badge <?php print $badgeClass; ?>">
                    <?php print $estado; ?>
                  </span>
                </td>
                <td>
                  <a href="<?php print RUTA; ?>tableroCliente/mostrar/<?php print $datos['data'][$i]['id']; ?>" class="xp-btn-show">
                    Ver detalle
                  </a>
                </td>
              </tr>
            <?php endfor; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div><!-- /xp-table-card -->

</div><!-- /xp-client-wrapper -->

<?php include_once("piepagina.php"); ?>