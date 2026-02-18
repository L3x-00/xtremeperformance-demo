<?php include_once("encabezado.php"); ?>

<div class="container-fluid my-4">
  <!-- Header mejorado -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h2 class="mb-1">
            <i class="fas fa-users me-2" style="color: var(--xp-red);"></i>
            Gestión de Clientes
          </h2>
          <p class="text-muted mb-0">Total de registros: <?php echo count($datos['data']); ?></p>
        </div>
        <div class="d-flex gap-2">
          <div class="btn-group" role="group" aria-label="Exportar">
            <a class="btn btn-outline-secondary" href="<?php print RUTA; ?>clientes/exportarCsv" title="Exportar CSV">
              <i class="fas fa-file-csv me-2"></i>CSV
            </a>
            <a class="btn btn-outline-secondary" href="<?php print RUTA; ?>clientes/exportarPdf" title="Exportar PDF">
              <i class="fas fa-file-pdf me-2"></i>PDF
            </a>
          </div>
          <a href="<?php print RUTA; ?>clientes/alta" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Nuevo Cliente
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Filtros mejorados -->
  <div class="card mb-4">
    <div class="card-body py-3">
      <div class="row g-3 align-items-center">
        <div class="col-md-4">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar cliente...">
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select" id="statusFilter">
            <option value="">Todos los estados</option>
            <option value="Activo">Activos</option>
            <option value="Inactivo">Inactivos</option>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-outline-danger btn-sm w-100" onclick="clearFilters()">
            <i class="fas fa-times me-1"></i>Limpiar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabla mejorada -->
  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 microinteraction" id="clientsTable">
          <thead>
            <tr>
              <th class="text-center" style="width: 80px;">
                <i class="fas fa-hashtag"></i>
              </th>
              <th>
                <i class="fas fa-user me-2"></i>Nombre
              </th>
              <th>
                <i class="fas fa-building me-2"></i>Razón Social
              </th>
              <th class="text-center">
                <i class="fas fa-toggle-on me-2"></i>Estado
              </th>
              <th class="text-center" style="width: 200px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            for($i=0; $i<count($datos['data']); $i++){
              $statusClass = $datos["data"][$i]['estado'] == 'Activo' ? 'success' : 'secondary';
              $statusIcon = $datos["data"][$i]['estado'] == 'Activo' ? 'check-circle' : 'times-circle';
              
              echo "<tr data-client-id='".$datos["data"][$i]['id']."'>";
              echo "<td class='text-center fw-bold' data-label='ID'>".$datos["data"][$i]['id']."</td>";
              echo "<td data-label='Nombre'>";
              echo "  <div class='d-flex align-items-center'>";
              echo "    <div class='avatar-circle me-2'>";
              echo "      <i class='fas fa-user'></i>";
              echo "    </div>";
              echo "    <div>";
              echo "      <div class='fw-semibold'>".$datos["data"][$i]['nombre']."</div>";
              echo "      <small class='text-muted'>ID: ".$datos["data"][$i]['id']."</small>";
              echo "    </div>";
              echo "  </div>";
              echo "</td>";
              echo "<td data-label='Razón Social'>";
              echo "  <span class='text-truncate d-block' style='max-width: 200px;' title='".$datos["data"][$i]['razonSocial']."'>";
              echo "    ".$datos["data"][$i]['razonSocial'];
              echo "  </span>";
              echo "</td>";
              echo "<td class='text-center' data-label='Estado'>";
              echo "  <span class='badge bg-{$statusClass}'>";
              echo "    <i class='fas fa-{$statusIcon} me-1'></i>";
              echo "    ".$datos["data"][$i]['estado'];
              echo "  </span>";
              echo "</td>";
              echo "<td class='text-center' data-label='Acciones'>";
              echo "  <div class='btn-group btn-group-sm animate-in-right' role='group'>";
              echo "    <a href='".RUTA."clientes/modificar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' ";
              echo "       class='btn btn-outline-primary microinteraction hover-lift' title='Modificar cliente'>";
              echo "      <i class='fas fa-edit'></i>";
              echo "    </a>";
              echo "    <a href='".RUTA."clientes/borrar/".$datos["data"][$i]["id"]."/".$datos["pag"]["pagina"]."' ";
              echo "       class='btn btn-outline-danger microinteraction hover-grow' title='Eliminar cliente'>";
              echo "      <i class='fas fa-trash'></i>";
              echo "    </a>";
              echo "  </div>";
              echo "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Paginación mejorada -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted">
      Mostrando <?php echo count($datos['data']); ?> registros
    </div>
    <?php include_once("paginacion.php"); ?>
  </div>
</div>

<!-- JavaScript específico para clientes -->
<script>
// Funciones específicas para la gestión de clientes

// Búsqueda en tiempo real
document.getElementById('searchInput').addEventListener('input', function() {
  const searchTerm = this.value.toLowerCase();
  filterTable(searchTerm);
});

// Filtro por estado
document.getElementById('statusFilter').addEventListener('change', function() {
  const statusFilter = this.value;
  filterTableByStatus(statusFilter);
});

// Función de filtrado con microinteracciones
function filterTable(searchTerm) {
  const rows = document.querySelectorAll('#clientsTable tbody tr');
  const table = document.getElementById('clientsTable');
  
  // Si hay búsqueda, mostrar skeleton brevemente
  if (searchTerm.length > 0) {
    table.style.opacity = '0.7';
    
    setTimeout(() => {
      let visibleCount = 0;
      
      rows.forEach((row, index) => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          row.style.display = '';
          row.classList.add('animate-in');
          setTimeout(() => row.style.transform = 'translateX(0)', index * 50);
          visibleCount++;
        } else {
          row.style.display = 'none';
          row.classList.remove('animate-in');
        }
      });
      
      table.style.opacity = '1';
      updateResultsCount();
      
      // Mostrar notificación de resultados
      if (visibleCount === 0) {
        showFloatingNotification('No se encontraron clientes', 'warning', 2000);
      } else if (visibleCount < rows.length) {
        showFloatingNotification(`${visibleCount} cliente(s) encontrado(s)`, 'info', 2000);
      }
    }, 200);
  } else {
    // Mostrar todos inmediatamente
    rows.forEach((row, index) => {
      row.style.display = '';
      row.classList.add('animate-in');
      setTimeout(() => row.style.transform = 'translateX(0)', index * 30);
    });
    updateResultsCount();
  }
}

// Filtrar por estado
function filterTableByStatus(status) {
  const rows = document.querySelectorAll('#clientsTable tbody tr');
  
  rows.forEach(row => {
    const statusBadge = row.querySelector('.badge');
    const rowStatus = statusBadge ? statusBadge.textContent.trim() : '';
    
    if (!status || rowStatus.includes(status)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
  
  updateResultsCount();
}

// Limpiar filtros
function clearFilters() {
  document.getElementById('searchInput').value = '';
  document.getElementById('statusFilter').value = '';
  
  const rows = document.querySelectorAll('#clientsTable tbody tr');
  rows.forEach(row => {
    row.style.display = '';
  });
  
  updateResultsCount();
  showToast('info', 'Filtros limpiados');
}

// Actualizar contador de resultados
function updateResultsCount() {
  const visibleRows = document.querySelectorAll('#clientsTable tbody tr[style=""]');
  const totalRows = document.querySelectorAll('#clientsTable tbody tr');
  
  // Actualizar el contador si existe
  const countElement = document.querySelector('.text-muted');
  if (countElement) {
    countElement.textContent = `Mostrando ${visibleRows.length} de ${totalRows.length} registros`;
  }
}

// Confirmar eliminación con modal personalizado y efectos
function confirmDeleteClient(clientId, clientName, page) {
  const message = `¿Está seguro que desea eliminar al cliente <strong>${clientName}</strong>?<br><small class="text-muted">Esta acción no se puede deshacer.</small>`;
  
  confirmDelete(message, function() {
    // Mostrar loading en el botón y fila
    const row = document.querySelector(`tr[data-client-id="${clientId}"]`);
    const button = row && row.querySelector('.btn-outline-danger');
    
    // Efectos visuales
    if (button) setButtonLoading(button, true);
    if (row) {
      row.style.opacity = '0.6';
      row.style.transform = 'scale(0.98)';
    }
    
    // Mostrar notificación de proceso
    showFloatingNotification('Eliminando cliente...', 'warning', 2000);
    
    // Redirigir directamente (sin timeout)
    const baseUrl = '<?php echo RUTA; ?>';
    const url = baseUrl + 'clientes/eliminar/' + clientId + '/' + page;
    console.log('DEBUG: Redirigiendo a URL de eliminación:', url);
    window.location.href = url;
  });
}

// Función de ver detalles eliminada - ahora solo se usa el botón "Modificar"

// Las funciones de exportación ahora se manejan del lado del servidor
// Los enlaces de CSV y PDF redirigen a los controladores PHP correspondientes

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
  // Mostrar mensaje de bienvenida si es necesario
  <?php if (isset($_GET['success'])): ?>
    showToast('success', 'Operación realizada correctamente');
  <?php endif; ?>
  
  <?php if (isset($_GET['error'])): ?>
    showToast('error', 'Ha ocurrido un error en la operación');
  <?php endif; ?>
  
  // Agregar animación de entrada a las filas
  setTimeout(() => {
    document.querySelectorAll('#clientsTable tbody tr').forEach((row, index) => {
      setTimeout(() => {
        row.classList.add('fade-in');
      }, index * 50);
    });
  }, 300);
});
</script>

<style>
/* Estilos específicos para la vista de clientes */
.avatar-circle {
  width: 35px;
  height: 35px;
  background: linear-gradient(135deg, var(--xp-red), var(--xp-red-hover));
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 0.875rem;
}

.fade-in {
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.btn-group-sm .btn {
  padding: 0.25rem 0.5rem;
}
  Dar de alta un cliente</a>
<?php include_once("piepagina.php"); ?>					