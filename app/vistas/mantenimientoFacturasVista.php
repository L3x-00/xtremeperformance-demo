<?php include_once("encabezado.php"); ?>

<div class="container-fluid my-4">
	<div class="row mb-4">
		<div class="col-12">
			<h2 class="mb-1">
				<i class="fas fa-tools me-2" style="color: var(--xp-red);"></i>
				Mantenimiento de Facturas
			</h2>
			<p class="text-muted mb-0">Herramientas para detectar y corregir problemas en facturas</p>
		</div>
	</div>

	<?php if (isset($datos['diagnostico']['error'])): ?>
	<div class="alert alert-danger">
		<i class="fas fa-exclamation-triangle me-2"></i>
		<?php echo $datos['diagnostico']['error']; ?>
	</div>
	<?php else: ?>

	<!-- Estadísticas de Diagnóstico -->
	<div class="row mb-4">
		<div class="col-md-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body text-center">
					<div class="mb-3">
						<i class="fas fa-file-invoice fa-2x text-primary"></i>
					</div>
					<h3 class="fw-bold text-primary mb-1"><?php echo $datos['diagnostico']['facturas_activas']; ?></h3>
					<p class="text-muted mb-0 small">Facturas Activas</p>
				</div>
			</div>
		</div>
		
		<div class="col-md-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body text-center">
					<div class="mb-3">
						<i class="fas fa-copy fa-2x text-warning"></i>
					</div>
					<h3 class="fw-bold text-warning mb-1"><?php echo $datos['diagnostico']['facturas_duplicadas']; ?></h3>
					<p class="text-muted mb-0 small">Órdenes con Duplicados</p>
				</div>
			</div>
		</div>
		
		<div class="col-md-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body text-center">
					<div class="mb-3">
						<i class="fas fa-calculator fa-2x text-danger"></i>
					</div>
					<h3 class="fw-bold text-danger mb-1"><?php echo $datos['diagnostico']['totales_incorrectos']; ?></h3>
					<p class="text-muted mb-0 small">Totales Incorrectos</p>
				</div>
			</div>
		</div>
		
		<div class="col-md-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body text-center">
					<div class="mb-3">
						<i class="fas fa-users fa-2x text-info"></i>
					</div>
					<h3 class="fw-bold text-info mb-1"><?php echo count($datos['diagnostico']['clientes_multiples_facturas']); ?></h3>
					<p class="text-muted mb-0 small">Clientes con Múltiples Facturas</p>
				</div>
			</div>
		</div>
	</div>

	<!-- Herramientas de Corrección -->
	<div class="row mb-4">
		<div class="col-md-6">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-warning text-dark">
					<h5 class="mb-0">
						<i class="fas fa-broom me-2"></i>
						Limpiar Facturas Duplicadas
					</h5>
				</div>
				<div class="card-body">
					<p class="mb-3">
						Esta herramienta elimina facturas duplicadas, manteniendo solo la más reciente para cada orden de reparación.
					</p>
					<?php if ($datos['diagnostico']['facturas_duplicadas'] > 0): ?>
					<div class="alert alert-warning">
						<i class="fas fa-exclamation-triangle me-2"></i>
						Se encontraron <strong><?php echo $datos['diagnostico']['facturas_duplicadas']; ?></strong> órdenes con facturas duplicadas.
					</div>
					<form method="POST" action="<?php echo RUTA; ?>mantenimientoFacturas/limpiarDuplicadas" 
						  onsubmit="return confirm('¿Está seguro de que desea eliminar las facturas duplicadas? Esta acción no se puede deshacer.');">
						<button type="submit" class="btn btn-warning">
							<i class="fas fa-trash-alt me-2"></i>
							Limpiar Duplicados
						</button>
					</form>
					<?php else: ?>
					<div class="alert alert-success">
						<i class="fas fa-check-circle me-2"></i>
						No se encontraron facturas duplicadas.
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-danger text-white">
					<h5 class="mb-0">
						<i class="fas fa-calculator me-2"></i>
						Recalcular Totales
					</h5>
				</div>
				<div class="card-body">
					<p class="mb-3">
						Esta herramienta recalcula los totales de facturas que tengan sumas incorrectas.
					</p>
					<?php if ($datos['diagnostico']['totales_incorrectos'] > 0): ?>
					<div class="alert alert-danger">
						<i class="fas fa-exclamation-triangle me-2"></i>
						Se encontraron <strong><?php echo $datos['diagnostico']['totales_incorrectos']; ?></strong> facturas con totales incorrectos.
					</div>
					<form method="POST" action="<?php echo RUTA; ?>mantenimientoFacturas/recalcularTotales"
						  onsubmit="return confirm('¿Está seguro de que desea recalcular los totales? Esta acción modificará los datos existentes.');">
						<button type="submit" class="btn btn-danger">
							<i class="fas fa-sync me-2"></i>
							Recalcular Totales
						</button>
					</form>
					<?php else: ?>
					<div class="alert alert-success">
						<i class="fas fa-check-circle me-2"></i>
						Todos los totales son correctos.
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Clientes con Múltiples Facturas -->
	<?php if (!empty($datos['diagnostico']['clientes_multiples_facturas'])): ?>
	<div class="row">
		<div class="col-12">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-info text-white">
					<h5 class="mb-0">
						<i class="fas fa-list me-2"></i>
						Clientes con Múltiples Facturas
					</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>Cliente</th>
									<th>Total de Facturas</th>
									<th>Suma Total</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($datos['diagnostico']['clientes_multiples_facturas'] as $cliente): ?>
								<tr>
									<td><?php echo htmlspecialchars($cliente['nombres'] . ' ' . $cliente['apellidos']); ?></td>
									<td>
										<span class="badge bg-secondary"><?php echo $cliente['total_facturas']; ?></span>
									</td>
									<td>
										<strong>S/ <?php echo number_format($cliente['suma_total'], 2); ?></strong>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php endif; ?>

	<!-- Botones de Acción -->
	<div class="row mt-4">
		<div class="col-12">
			<div class="d-flex gap-2">
				<a href="<?php echo RUTA; ?>mantenimientoFacturas/diagnostico" class="btn btn-primary">
					<i class="fas fa-sync me-2"></i>
					Actualizar Diagnóstico
				</a>
				<a href="<?php echo RUTA; ?>tablero" class="btn btn-secondary">
					<i class="fas fa-arrow-left me-2"></i>
					Volver al Tablero
				</a>
			</div>
		</div>
	</div>
</div>

<?php include_once("piepagina.php"); ?>