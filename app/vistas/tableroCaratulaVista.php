<?php include_once("encabezado.php"); ?>
	<div class="container my-3">
		<div class="row g-3">
			<div class="col-12 col-md-3">
				<div class="card text-bg-light h-100 xp-kpi-card">
					<div class="card-body">
						<div class="small text-uppercase text-muted fw-semibold mb-1">Órdenes abiertas</div>
						<div class="display-6 fw-bold text-primary"><?php print intval($datos['data']['kpis']['ordenes_abiertas']??0); ?></div>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-3">
				<div class="card text-bg-light h-100 xp-kpi-card">
					<div class="card-body">
						<div class="small text-uppercase text-muted fw-semibold mb-1">Órdenes facturadas</div>
						<div class="display-6 fw-bold text-success"><?php print intval($datos['data']['kpis']['ordenes_facturadas']??0); ?></div>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-3">
				<div class="card text-bg-light h-100 xp-kpi-card">
					<div class="card-body">
						<div class="small text-uppercase text-muted fw-semibold mb-1">Órdenes totales</div>
						<div class="display-6 fw-bold text-dark"><?php print intval($datos['data']['kpis']['ordenes_totales']??0); ?></div>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-3">
				<div class="card text-bg-light h-100 xp-kpi-card">
					<div class="card-body">
						<div class="small text-uppercase text-muted fw-semibold mb-1">Ingresos mes (S/)</div>
						<div class="display-6 fw-bold" style="color: var(--xp-red);">S/ <?php print number_format(floatval($datos['data']['kpis']['ingresos_mes']??0),2); ?></div>
					</div>
				</div>
			</div>
		</div>
		<div class="card mt-4">
			<div class="card-header">Ingresos por mes</div>
			<div class="card-body">
				<canvas id="ingresosChart" height="80"></canvas>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" integrity="sha256-XG5HibzR+g6gVng7Hn5y7m0WFMG8D4BO31K5yC13l7M=" crossorigin="anonymous"></script>
	<script>
	(function(){
		const ctx = document.getElementById('ingresosChart');
		if (!ctx) return;
		const labels = <?php print json_encode($datos['data']['serie']['labels']??[]); ?>;
		const data = <?php print json_encode($datos['data']['serie']['data']??[]); ?>;
		new Chart(ctx, {
			type: 'bar',
			data: {
				labels: labels,
				datasets: [{
					label: 'Ingresos (S/)',
					data: data,
					backgroundColor: 'rgba(198,40,40,0.6)',
					borderColor: 'rgba(198,40,40,1)',
					borderWidth: 1,
				}]
			},
			options: {
				plugins: { legend: { display: false } },
				scales: { y: { beginAtZero: true } }
			}
		});
	})();
	</script>

<?php include_once("piepagina.php"); ?>