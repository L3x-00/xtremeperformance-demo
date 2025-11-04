<?php include_once("encabezado.php"); ?>

<div class="container-fluid mt-4">
	<!-- Header del Panel -->
	<div class="row mb-4">
		<div class="col-12">
			<div class="card bg-gradient-primary text-white">
				<div class="card-body">
					<div class="row align-items-center">
						<div class="col-md-8">
							<h2 class="mb-1">
								<i class="fas fa-user-tie me-2"></i>
								Panel de Operador
							</h2>
							<p class="mb-0 opacity-75">
								Bienvenido <?php echo $datos['usuario']['nombres']; ?> - Vista de solo lectura del sistema
							</p>
						</div>
						<div class="col-md-4 text-end">
							<div class="d-flex justify-content-end align-items-center">
								<span class="badge bg-light text-primary fs-6 me-2">
									<i class="fas fa-eye me-1"></i>Solo Lectura
								</span>
								<span class="text-light small">
									<?php echo date('d/m/Y H:i'); ?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Estadísticas Rápidas -->
	<div class="row mb-4">
		<div class="col-lg-3 col-md-6 mb-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body text-center">
					<div class="mb-3">
						<i class="fas fa-users fa-2x text-primary"></i>
					</div>
					<h3 class="fw-bold text-primary mb-1"><?php echo $datos['estadisticas']['clientesActivos']; ?></h3>
					<p class="text-muted mb-0 small">Clientes Activos</p>
				</div>
			</div>
		</div>
		
		<div class="col-lg-3 col-md-6 mb-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body text-center">
					<div class="mb-3">
						<i class="fas fa-wrench fa-2x text-warning"></i>
					</div>
					<h3 class="fw-bold text-warning mb-1"><?php echo $datos['estadisticas']['ordenesEnProceso']; ?></h3>
					<p class="text-muted mb-0 small">Órdenes en Proceso</p>
				</div>
			</div>
		</div>
		
		<div class="col-lg-3 col-md-6 mb-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body text-center">
					<div class="mb-3">
						<i class="fas fa-user-check fa-2x text-success"></i>
					</div>
					<h3 class="fw-bold text-success mb-1"><?php echo $datos['estadisticas']['mecanicosDisponibles']; ?></h3>
					<p class="text-muted mb-0 small">Mecánicos Disponibles</p>
				</div>
			</div>
		</div>
		
		<div class="col-lg-3 col-md-6 mb-3">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body text-center">
					<div class="mb-3">
						<i class="fas fa-car fa-2x text-info"></i>
					</div>
					<h3 class="fw-bold text-info mb-1"><?php echo $datos['estadisticas']['totalVehiculos']; ?></h3>
					<p class="text-muted mb-0 small">Vehículos Registrados</p>
				</div>
			</div>
		</div>
	</div>

	<!-- Búsquedas Rápidas -->
	<div class="row mb-4">
		<div class="col-md-6 mb-3">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-light">
					<h5 class="card-title mb-0">
						<i class="fas fa-search me-2 text-primary"></i>Buscar Órdenes
					</h5>
				</div>
				<div class="card-body">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Buscar por ID, descripción, cliente, placa..." id="buscarOrdenes">
						<button class="btn btn-primary" type="button" onclick="buscarOrdenes()">
							<i class="fas fa-search"></i>
						</button>
					</div>
					<div id="resultadosOrdenes" class="mt-3"></div>
				</div>
			</div>
		</div>
		
		<div class="col-md-6 mb-3">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-light">
					<h5 class="card-title mb-0">
						<i class="fas fa-search me-2 text-success"></i>Buscar Clientes
					</h5>
				</div>
				<div class="card-body">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Buscar por nombre, teléfono, correo..." id="buscarClientes">
						<button class="btn btn-success" type="button" onclick="buscarClientes()">
							<i class="fas fa-search"></i>
						</button>
					</div>
					<div id="resultadosClientes" class="mt-3"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Contenido Principal -->
	<div class="row">
		<!-- Últimas Órdenes -->
		<div class="col-lg-6 mb-4">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-warning text-white">
					<h5 class="card-title mb-0">
						<i class="fas fa-clipboard-list me-2"></i>Últimas Órdenes de Reparación
					</h5>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover mb-0">
							<thead class="table-light">
								<tr>
									<th class="border-0">ID</th>
									<th class="border-0">Cliente</th>
									<th class="border-0">Vehículo</th>
									<th class="border-0">Estado</th>
									<th class="border-0">Fecha</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($datos['ultimasOrdenes'])): ?>
									<?php foreach ($datos['ultimasOrdenes'] as $orden): ?>
										<tr>
											<td class="fw-bold text-primary">#<?php echo $orden['id']; ?></td>
											<td>
												<?php echo $orden['clienteNombres'] . ' ' . $orden['clienteApellidos']; ?>
											</td>
											<td>
												<small class="text-muted">
													<?php echo $orden['marca'] . ' ' . $orden['modelo']; ?><br>
													<strong><?php echo $orden['placa']; ?></strong>
												</small>
											</td>
											<td>
												<span class="badge bg-secondary"><?php echo $orden['estadoTexto']; ?></span>
											</td>
											<td>
												<small><?php echo date('d/m/Y', strtotime($orden['fechaInicio'])); ?></small>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="5" class="text-center text-muted py-4">
											<i class="fas fa-inbox fa-2x mb-2"></i><br>
											No hay órdenes recientes
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Estado de Mecánicos -->
		<div class="col-lg-6 mb-4">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-success text-white">
					<h5 class="card-title mb-0">
						<i class="fas fa-users-cog me-2"></i>Estado de Mecánicos
					</h5>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover mb-0">
							<thead class="table-light">
								<tr>
									<th class="border-0">Mecánico</th>
									<th class="border-0">Especialidad</th>
									<th class="border-0">Teléfono</th>
									<th class="border-0">Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($datos['mecanicos'])): ?>
									<?php foreach ($datos['mecanicos'] as $mecanico): ?>
										<tr>
											<td class="fw-semibold">
												<?php echo $mecanico['nombres'] . ' ' . $mecanico['apellidos']; ?>
											</td>
											<td>
												<span class="badge bg-info"><?php echo $mecanico['tipomecanico']; ?></span>
											</td>
											<td>
												<small class="text-muted"><?php echo $mecanico['telefono']; ?></small>
											</td>
											<td>
												<?php if ($mecanico['estadoTexto'] == 'Disponible'): ?>
													<span class="badge bg-success">Disponible</span>
												<?php else: ?>
													<span class="badge bg-warning">Ocupado</span>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="4" class="text-center text-muted py-4">
											<i class="fas fa-user-slash fa-2x mb-2"></i><br>
											No hay mecánicos registrados
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Clientes Recientes -->
	<div class="row">
		<div class="col-12">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-info text-white">
					<h5 class="card-title mb-0">
						<i class="fas fa-user-plus me-2"></i>Clientes Recientes
					</h5>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover mb-0">
							<thead class="table-light">
								<tr>
									<th class="border-0">ID</th>
									<th class="border-0">Cliente</th>
									<th class="border-0">Teléfono</th>
									<th class="border-0">Correo</th>
									<th class="border-0">Razón Social</th>
									<th class="border-0">Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($datos['clientesRecientes'])): ?>
									<?php foreach ($datos['clientesRecientes'] as $cliente): ?>
										<tr>
											<td class="fw-bold text-primary">#<?php echo $cliente['id']; ?></td>
											<td class="fw-semibold">
												<?php echo $cliente['nombres'] . ' ' . $cliente['apellidos']; ?>
											</td>
											<td><?php echo $cliente['telefono']; ?></td>
											<td>
												<small class="text-muted"><?php echo $cliente['correo']; ?></small>
											</td>
											<td><?php echo $cliente['razonSocial']; ?></td>
											<td>
												<span class="badge bg-success"><?php echo $cliente['estadoTexto']; ?></span>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="6" class="text-center text-muted py-4">
											<i class="fas fa-user-slash fa-2x mb-2"></i><br>
											No hay clientes registrados
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
// Función para buscar órdenes
function buscarOrdenes() {
    const termino = document.getElementById('buscarOrdenes').value.trim();
    const resultadosDiv = document.getElementById('resultadosOrdenes');
    
    if (termino.length < 2) {
        resultadosDiv.innerHTML = '<small class="text-muted">Escribe al menos 2 caracteres</small>';
        return;
    }
    
    resultadosDiv.innerHTML = '<small class="text-muted"><i class="fas fa-spinner fa-spin"></i> Buscando...</small>';
    
    fetch('<?php echo RUTA; ?>tablerooperador/buscarOrdenes', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'termino=' + encodeURIComponent(termino)
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            resultadosDiv.innerHTML = '<small class="text-danger">' + data.error + '</small>';
            return;
        }
        
        if (data.length === 0) {
            resultadosDiv.innerHTML = '<small class="text-muted">No se encontraron resultados</small>';
            return;
        }
        
        let html = '<div class="list-group">';
        data.forEach(orden => {
            html += `
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">Orden #${orden.id} - ${orden.clienteNombres} ${orden.clienteApellidos}</h6>
                        <small>${orden.fechaInicio}</small>
                    </div>
                    <p class="mb-1">${orden.marca} ${orden.modelo} - ${orden.placa}</p>
                    <small class="text-muted">${orden.descripcion}</small>
                </div>
            `;
        });
        html += '</div>';
        
        resultadosDiv.innerHTML = html;
    })
    .catch(error => {
        resultadosDiv.innerHTML = '<small class="text-danger">Error en la búsqueda</small>';
        console.error('Error:', error);
    });
}

// Función para buscar clientes
function buscarClientes() {
    const termino = document.getElementById('buscarClientes').value.trim();
    const resultadosDiv = document.getElementById('resultadosClientes');
    
    if (termino.length < 2) {
        resultadosDiv.innerHTML = '<small class="text-muted">Escribe al menos 2 caracteres</small>';
        return;
    }
    
    resultadosDiv.innerHTML = '<small class="text-muted"><i class="fas fa-spinner fa-spin"></i> Buscando...</small>';
    
    fetch('<?php echo RUTA; ?>tablerooperador/buscarClientes', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'termino=' + encodeURIComponent(termino)
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            resultadosDiv.innerHTML = '<small class="text-danger">' + data.error + '</small>';
            return;
        }
        
        if (data.length === 0) {
            resultadosDiv.innerHTML = '<small class="text-muted">No se encontraron resultados</small>';
            return;
        }
        
        let html = '<div class="list-group">';
        data.forEach(cliente => {
            html += `
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${cliente.nombres} ${cliente.apellidos}</h6>
                        <small>ID: #${cliente.id}</small>
                    </div>
                    <p class="mb-1">${cliente.telefono} - ${cliente.correo}</p>
                    <small class="text-muted">${cliente.razonSocial}</small>
                </div>
            `;
        });
        html += '</div>';
        
        resultadosDiv.innerHTML = html;
    })
    .catch(error => {
        resultadosDiv.innerHTML = '<small class="text-danger">Error en la búsqueda</small>';
        console.error('Error:', error);
    });
}

// Búsqueda al presionar Enter
document.getElementById('buscarOrdenes').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        buscarOrdenes();
    }
});

document.getElementById('buscarClientes').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        buscarClientes();
    }
});
</script>

<?php include_once("piepagina.php"); ?>