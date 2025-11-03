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
          <button class="btn btn-outline-secondary" onclick="exportData()" title="Exportar datos">
            <i class="fas fa-download me-2"></i>Exportar
          </button>
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
              echo "    <button onclick='confirmDeleteClient(".$datos["data"][$i]["id"].", \"".$datos["data"][$i]["nombre"]."\", ".$datos["pag"]["pagina"].")' ";
              echo "            class='btn btn-outline-danger microinteraction hover-grow' title='Eliminar cliente'>";
              echo "      <i class='fas fa-trash'></i>";
              echo "    </button>";
              echo "    <button onclick='viewClientDetails(".$datos["data"][$i]["id"].")' ";
              echo "            class='btn btn-outline-info microinteraction' title='Ver detalles'>";
              echo "      <i class='fas fa-eye hover-float'></i>";
              echo "    </button>";
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
  const message = `¿Está seguro que desea eliminar al cliente <strong>${clientName}</strong>?<br><small class="text-muted">Esta acción no se puede deshacer</small>`;
  
  confirmDelete(message, function() {
    // Mostrar loading en el botón y fila
    const row = document.querySelector(`tr[data-client-id="${clientId}"]`);
    const button = row.querySelector('.btn-outline-danger');
    
    // Efectos visuales
    setButtonLoading(button, true);
    row.style.opacity = '0.6';
    row.style.transform = 'scale(0.98)';
    
    // Mostrar notificación de proceso
    showFloatingNotification('Eliminando cliente...', 'warning', 2000);
    
    // Simular proceso y redirigir
    setTimeout(() => {
      window.location.href = `<?php echo RUTA; ?>clientes/borrar/${clientId}/${page}`;
    }, 1000);
  });
}

// Ver detalles del cliente en modal
function viewClientDetails(clientId) {
  // Aquí harías una petición AJAX para obtener los detalles
  // Por ahora mostramos un modal de ejemplo
  const content = `
    <div class="row">
      <div class="col-md-6">
        <h6><i class="fas fa-info-circle me-2"></i>Información General</h6>
        <p><strong>ID:</strong> ${clientId}</p>
        <p><strong>Fecha de registro:</strong> Cargando...</p>
        <p><strong>Última modificación:</strong> Cargando...</p>
      </div>
      <div class="col-md-6">
        <h6><i class="fas fa-chart-bar me-2"></i>Estadísticas</h6>
        <p><strong>Órdenes totales:</strong> Cargando...</p>
        <p><strong>Gasto total:</strong> Cargando...</p>
        <p><strong>Última visita:</strong> Cargando...</p>
      </div>
    </div>
    <div class="alert alert-info mt-3">
      <i class="fas fa-info-circle me-2"></i>
      Para ver información completa, use el botón "Modificar"
    </div>
  `;
  
  showDetailsModal(`Detalles del Cliente ID: ${clientId}`, content, 'modal-lg');
}

// Exportar datos CSV
function exportCSV() {
  showToast('info', 'Preparando exportación CSV...', 'Exportar');
  
  // Obtener datos de la tabla
  const rows = document.querySelectorAll('#clientsTable tbody tr[style=""], #clientsTable tbody tr:not([style])');
  let csvContent = "ID,Nombre,Razón Social,Estado\n";
  
  rows.forEach(row => {
    const cells = row.querySelectorAll('td');
    if (cells.length >= 4) {
      const id = cells[0].textContent.trim();
      const nombre = cells[1].querySelector('.fw-semibold')?.textContent.trim() || cells[1].textContent.trim();
      const razonSocial = cells[2].textContent.trim();
      const estado = cells[3].querySelector('.badge')?.textContent.trim() || cells[3].textContent.trim();
      
      // Escapar comillas y agregar fila
      csvContent += `"${id}","${nombre}","${razonSocial}","${estado}"\n`;
    }
  });
  
  // Descargar archivo
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  const url = URL.createObjectURL(blob);
  link.setAttribute('href', url);
  link.setAttribute('download', `clientes_${new Date().toISOString().split('T')[0]}.csv`);
  link.style.visibility = 'hidden';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  
  showToast('success', 'Archivo CSV descargado correctamente', 'Exportación completada');
}

// Exportar datos PDF
function exportPDF() {
  showToast('info', 'Preparando exportación PDF...', 'Exportar');
  
  // Crear ventana de impresión con los datos
  const printWindow = window.open('', '_blank');
  const rows = document.querySelectorAll('#clientsTable tbody tr[style=""], #clientsTable tbody tr:not([style])');
  
  let tableHTML = `
    <html>
    <head>
      <title>Listado de Clientes - Xtreme Performance</title>
      <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #c62828; margin: 0; }
        .header p { color: #666; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 12px; }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>XTREME PERFORMANCE</h1>
        <p>Listado de Clientes</p>
        <p>Generado el: ${new Date().toLocaleDateString('es-ES', { 
          year: 'numeric', 
          month: 'long', 
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        })}</p>
      </div>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Razón Social</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
  `;
  
  rows.forEach(row => {
    const cells = row.querySelectorAll('td');
    if (cells.length >= 4) {
      const id = cells[0].textContent.trim();
      const nombre = cells[1].querySelector('.fw-semibold')?.textContent.trim() || cells[1].textContent.trim();
      const razonSocial = cells[2].textContent.trim();
      const estado = cells[3].querySelector('.badge')?.textContent.trim() || cells[3].textContent.trim();
      
      tableHTML += `
        <tr>
          <td>${id}</td>
          <td>${nombre}</td>
          <td>${razonSocial}</td>
          <td>${estado}</td>
        </tr>
      `;
    }
  });
  
  tableHTML += `
        </tbody>
      </table>
      <div class="footer">
        <p>Total de registros: ${rows.length}</p>
        <p>Xtreme Performance - Sistema de Gestión Automotriz</p>
      </div>
    </body>
    </html>
  `;
  
  printWindow.document.write(tableHTML);
  printWindow.document.close();
  
  // Esperar a que se cargue y abrir diálogo de impresión
  printWindow.onload = function() {
    printWindow.print();
    setTimeout(() => {
      printWindow.close();
    }, 1000);
  };
  
  showToast('success', 'Documento PDF preparado para impresión', 'Exportación completada');
}

// Función general de exportación que muestra opciones
function exportData() {
  const content = `
    <div class="d-grid gap-2">
      <button type="button" class="btn btn-outline-success btn-lg" onclick="exportCSV(); closeModal();">
        <i class="fas fa-file-csv me-2"></i>
        Exportar como CSV
        <small class="d-block text-muted">Archivo de Excel compatible</small>
      </button>
      <button type="button" class="btn btn-outline-danger btn-lg" onclick="exportPDF(); closeModal();">
        <i class="fas fa-file-pdf me-2"></i>
        Exportar como PDF
        <small class="d-block text-muted">Documento para impresión</small>
      </button>
    </div>
  `;
  
  showDetailsModal('Exportar Datos de Clientes', content, 'modal-sm');
}

// Función para cerrar modal (helper)
function closeModal() {
  const modal = document.querySelector('.modal.show');
  if (modal) {
    const modalInstance = bootstrap.Modal.getInstance(modal);
    if (modalInstance) {
      modalInstance.hide();
    }
  }
}

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