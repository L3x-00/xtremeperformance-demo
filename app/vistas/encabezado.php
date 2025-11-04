<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if(defined('RUTA')){ echo "    <base href='".RUTA."'>\n"; } ?>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
	<link href="./public/css/admin.css?v=20251102" rel="stylesheet">
	<link href="<?php echo RUTA; ?>public/css/dark-theme-override.css?v=<?php echo time(); ?>" rel="stylesheet">
	
	<!-- Toast Notifications -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
	
	<!-- Font Awesome for Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
	
	<!-- jQuery and Toastr -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	
	<!-- Enhanced UI System -->
	<script src="./public/js/enhanced-ui.js?v=<?php echo time(); ?>"></script>
	
    <link rel="shortcut icon" href="./public/img/favicon.png" type="image/svg+xml" />

	<title>XTREME PERFORMANCE | <?php print $datos["titulo"]; ?></title>

	<!-- Notification System -->
	<script>
		// Configuración global de Toastr
		$(document).ready(function() {
			toastr.options = {
				"closeButton": true,
				"debug": false,
				"newestOnTop": true,
				"progressBar": true,
				"positionClass": "toast-top-right",
				"preventDuplicates": true,
				"onclick": null,
				"showDuration": "300",
				"hideDuration": "1000",
				"timeOut": "4000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			};
		});

		// Funciones globales para notificaciones
		window.showToast = function(type, message, title = '') {
			switch(type) {
				case 'success':
					toastr.success(message, title || '✅ Éxito');
					break;
				case 'error':
					toastr.error(message, title || '❌ Error');
					break;
				case 'warning':
					toastr.warning(message, title || '⚠️ Advertencia');
					break;
				case 'info':
					toastr.info(message, title || 'ℹ️ Información');
					break;
			}
		};

		// Función para mostrar notificación de operación exitosa
		window.showSuccess = function(operation = 'Operación') {
			showToast('success', operation + ' realizada correctamente');
		};

		// Función para mostrar error de validación
		window.showValidationError = function(field = 'campo') {
			showToast('error', 'Por favor verifica el ' + field + ' ingresado');
		};

		// Función para confirmar acciones destructivas
		window.confirmAction = function(message, callback) {
			toastr.warning(
				message + '<br><br>' +
				'<button type="button" class="btn btn-sm btn-success me-2" onclick="' + callback + '(); toastr.clear();">Confirmar</button>' +
				'<button type="button" class="btn btn-sm btn-secondary" onclick="toastr.clear();">Cancelar</button>',
				'⚠️ Confirmación requerida',
				{
					allowHtml: true,
					timeOut: 0,
					closeButton: false,
					tapToDismiss: false
				}
			);
		};
	</script>
	
	<!-- CSS Override para Toggle -->
	<link href="<?php echo RUTA; ?>public/css/toggle-override.css?v=<?php echo time(); ?>" rel="stylesheet">
	
	<!-- Sistema de Toggle de Tema -->
	<script src="<?php echo RUTA; ?>public/js/theme-toggle.js?v=<?php echo time(); ?>"></script>
	
	<!-- CSS ESPECÍFICO PARA PÁGINAS ADMINISTRATIVAS -->
	<?php 
	// Solo cargar CSS administrativo en páginas del sistema, NO en la página principal
	$currentUrl = isset($_GET['url']) ? $_GET['url'] : '';
	$currentController = empty($currentUrl) ? 'inicio' : explode('/', $currentUrl)[0];
	if($currentController !== 'inicio' && !empty($currentController)): 
	?>
	<link href="<?php echo RUTA; ?>public/css/admin-scroll-fix.css?v=<?php echo time(); ?>" rel="stylesheet">
	<?php endif; ?>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark admin-navbar shadow-sm">
		<?php
			$brandHref = RUTA;
			$tipo = null;
			if (isset($datos["usuario"]["tipoUsuario"])) {
				$tipo = $datos["usuario"]["tipoUsuario"]; 
			} else {
				// Fallback: obtener tipo de usuario desde la sesión para vistas de mensaje u otras que no pasen $datos['usuario']
				if (class_exists('Sesion')) {
					$__ses = new Sesion();
					if ($__ses->getLogin()) {
						$__u = $__ses->getUsuario();
						if (isset($__u['tipoUsuario'])) { $tipo = $__u['tipoUsuario']; }
					}
				}
			}
			if ($tipo!==null) {
				if ($tipo==ADMON) {
					$brandHref = RUTA.'Tablero';
				} else if (defined('OPERADOR') && $tipo==OPERADOR) {
					$brandHref = RUTA.'TableroOperador';
				} else if (defined('MECANICO') && $tipo==MECANICO) {
					$brandHref = RUTA.'TableroMecanico';
				} else if (defined('CLIENTE') && $tipo==CLIENTE) {
					$brandHref = RUTA.'TableroCliente';
				}
			}
		?>
		<div class="container-fluid">
			<a href="<?php print $brandHref; ?>" class="navbar-brand d-flex align-items-center">
				<img src="./public/img/LogoGray.png" alt="Xtreme Performance" class="brand-logo" height="38">
			</a>
			
			<!-- Botón hamburger para móvil -->
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
					aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="navbarNav">
	<?php
		if (isset($datos["menu"]) && $datos["menu"]==true) {
			if (isset($datos["usuario"]["tipoUsuario"]) && $datos["usuario"]["tipoUsuario"]==ADMON) {
				print "<ul class='navbar-nav me-auto'>";
				//
				print "<li class='nav-item'>";
				print "<a href='".RUTA."salidas' class='nav-link ";
				if(isset($datos["activo"]) && $datos["activo"]=="salidas") print "active";
				print "'>Salidas</a>";
				print "</li>";
				//
				print "<li class='nav-item'>";
				print "<a href='".RUTA."seguimientos' class='nav-link ";
				if(isset($datos["activo"]) && $datos["activo"]=="seguimientos") print "active";
				print "'>Seguimiento</a>";
				print "</li>";
				//
				print "<li class='nav-item'>";
				print "<a href='".RUTA."OrdenReparacion' class='nav-link ";
				if(isset($datos["activo"]) && $datos["activo"]=="ordenreparacion") print "active";
				print "'>Orden de reparación</a>";
				print "</li>";
				//
				print "<li class='nav-item'>";
				print "<a href='".RUTA."OrdenAlmacen' class='nav-link ";
				if(isset($datos["activo"]) && $datos["activo"]=="ordenalmacen") print "active";
				print "'>Orden de almacén</a>";
				print "</li>";
				//
				print "<li class='nav-item'>";
				print "<a href='".RUTA."vehiculos' class='nav-link ";
				if(isset($datos["activo"]) && $datos["activo"]=="vehiculos") print "active";
				print "'>Vehículos</a>";
				print "</li>";
				//
				print "<li class='nav-item'>";
				print "<a href='".RUTA."clientes' class='nav-link ";
				if(isset($datos["activo"]) && $datos["activo"]=="clientes") print "active";
				print "'>Clientes</a>";
				print "</li>";
				//
				print "<li class='nav-item'>";
				print "<a href='".RUTA."mecanicos' class='nav-link ";
				if(isset($datos["activo"]) && $datos["activo"]=="mecanicos") print "active";
				print "'>Mecánicos</a>";
				print "</li>";
				//
				print "<li class='nav-item'>";
				print "<a href='".RUTA."usuarios' class='nav-link ";
				if(isset($datos["activo"]) && $datos["activo"]=="usuarios") print "active";
				print "'>Usuarios</a>";
				print "</li>";
				//
			    print "<li class='nav-item'>";
			    print "<a href='".RUTA."tablero/respaldar' class='nav-link'>Respaldar</a>";
			    print "</li>";
				//
				print "</ul>";
				//
			} else if (isset($datos["usuario"]["tipoUsuario"]) && $datos["usuario"]["tipoUsuario"]==OPERADOR) {
				// Menú limitado para operadores
				print "<ul class='navbar-nav me-auto'>";
				//
				print "<li class='nav-item'>";
				print "<a href='".RUTA."tablerooperador' class='nav-link ";
				if(isset($datos["activo"]) && $datos["activo"]=="tablerooperador") print "active";
				print "'><i class='fas fa-tachometer-alt me-1'></i>Dashboard</a>";
				print "</li>";
				//
				print "<li class='nav-item'>";
				print "<a href='#' class='nav-link text-muted' onclick='showToast(\"info\", \"Solo tienes permisos de lectura\", \"Consulta de Órdenes\")'>";
				print "<i class='fas fa-eye me-1'></i>Ver Órdenes</a>";
				print "</li>";
				//
				print "<li class='nav-item'>";
				print "<a href='#' class='nav-link text-muted' onclick='showToast(\"info\", \"Solo tienes permisos de lectura\", \"Consulta de Clientes\")'>";
				print "<i class='fas fa-users me-1'></i>Ver Clientes</a>";
				print "</li>";
				//
				print "<li class='nav-item'>";
				print "<a href='#' class='nav-link text-muted' onclick='showToast(\"info\", \"Solo tienes permisos de lectura\", \"Consulta de Mecánicos\")'>";
				print "<i class='fas fa-user-cog me-1'></i>Ver Mecánicos</a>";
				print "</li>";
				//
				print "<li class='nav-item'>";
				print "<a href='#' class='nav-link text-muted' onclick='showToast(\"info\", \"Función disponible próximamente\", \"Reportes\")'>";
				print "<i class='fas fa-chart-bar me-1'></i>Reportes</a>";
				print "</li>";
				//
				print "</ul>";
				//
			}
		}
		print "<ul class='nav navbar-nav ms-auto'>";
			// Theme Toggle
		print "<li class='nav-item me-4'>";
		print "<div class='theme-toggle'>";
		print "<input type='checkbox' class='theme-toggle-checkbox' id='theme-toggle' />";
		print "<label class='theme-toggle-label' for='theme-toggle'>";
		print "<i class='fas fa-sun theme-icon-sun' style='font-size: 18px !important;'></i>";
		print "<span class='theme-toggle-button'></span>";
		print "<i class='fas fa-moon theme-icon-moon' style='font-size: 18px !important;'></i>";
		print "</label>";
		print "</div>";
		print "<script>";
		print "(function() {";
		print "  // Aplicar tema inmediatamente";
		print "  const savedTheme = localStorage.getItem('theme') || 'light';";
		print "  document.documentElement.setAttribute('data-theme', savedTheme);";
		print "  document.body.setAttribute('data-theme', savedTheme);";
		print "  document.body.className += ' theme-' + savedTheme;";
		print "  ";
		print "  if (savedTheme === 'dark') {";
		print "    document.body.style.backgroundColor = '#1a1a1a';";
		print "    document.body.style.color = '#ffffff';";
		print "  }";
		print "  ";
		print "  document.addEventListener('DOMContentLoaded', function() {";
		print "    const toggle = document.getElementById('theme-toggle');";
		print "    if (toggle) {";
		print "      toggle.checked = savedTheme === 'dark';";
		print "      toggle.addEventListener('change', function() {";
		print "        const theme = this.checked ? 'dark' : 'light';";
		print "        document.documentElement.setAttribute('data-theme', theme);";
		print "        document.body.setAttribute('data-theme', theme);";
		print "        document.body.className = document.body.className.replace(/theme-\\w+/g, '') + ' theme-' + theme;";
		print "        localStorage.setItem('theme', theme);";
		print "        ";
		print "        if (theme === 'dark') {";
		print "          document.body.style.backgroundColor = '#1a1a1a';";
		print "          document.body.style.color = '#ffffff';";
		print "        } else {";
		print "          document.body.style.backgroundColor = '#ffffff';";
		print "          document.body.style.color = '#212529';";
		print "        }";
		print "        ";
		print "        if (typeof toastr !== 'undefined') {";
		print "          toastr.info(theme === 'dark' ? '🌙 Modo oscuro activado' : '☀️ Modo claro activado');";
		print "        }";
		print "      });";
		print "    }";
		print "  });";
		print "})();";
		print "</script>";
		print "</li>";
			//
		print "<li class='nav-item'>";
		print "<a href='".RUTA."tablero/perfil' class='nav-link'>";
		print '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
</svg>';
	    print "</a>";
	    print "</li>";

	    print "<li class='nav-item'>";
	    print "<a href='".RUTA."tablero/logout' class='nav-link'>"; 
	    print '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
<path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
<path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
</svg>';
      	print "</a></li>";
	    print "</ul>";
	?>
			</div><!-- /navbar-collapse -->
		</div><!-- /container-fluid -->
	</nav>
	<div class="container-fluid">
		<div class="row content">
			<div class="col-sm-1"></div>
			<div class="col-sm-10">
				<?php
				if (isset($datos["errores"])) {
					if (count($datos["errores"])>0) {
						print "<div class='alert alert-danger mt-3'><ul>";
						foreach ($datos["errores"] as $valor) {
							print "<li>".$valor."</li>";
						}
						print "</ul></div>";
					}
				}
				?>
				<div class="card p-4 mt-3 bg-info-subtle">
					<div class="card-header text-center">
						<h2><?php print $datos["subtitulo"]; ?></h2>
					</div>
					<div class="card-body">