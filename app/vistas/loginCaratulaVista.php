<?php include_once("encabezado.php"); ?>

<div class="container-fluid bg-light">
    <div class="row g-0 vh-100 justify-content-center align-items-center">
        <div class="col-lg-4 d-none d-lg-block">
            <img src="./public/img/footer-shape-1.png" alt="Taller Mecánico Xtreme Performance" class="img-fluid h-100" style="object-fit: cover; object-position: center;">
        </div>

        <div class="col-lg-8 px-4 py-5">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-8 col-xl-7">

                    <div class="text-center mb-4">
                        <img src="./public/img/LogoLow.png" alt="Logo Xtreme Performance" style="width: 180px;" class="mb-3">
                        <h1 class="h3 fw-bold">Acceso al Sistema</h1>
                        <p class="text-muted">Ingresa tus credenciales para continuar.</p>
                    </div>

                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4 p-sm-5">
                            <form action="<?php print RUTA; ?>login/verificar" method="POST">

                                <div class="form-floating mb-3">
                                    <input id="usuario" name="usuario" type="email" class="form-control rounded-3" placeholder="Escribe tu usuario" value="<?php print isset($datos['data']['usuario']) ? htmlspecialchars($datos['data']['usuario']) : ''; ?>" required>
                                    <label for="usuario"><i class="bi bi-envelope-fill me-2"></i>Correo Electrónico</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input id="clave" name="clave" type="password" class="form-control rounded-3" placeholder="Escribe tu clave" value="<?php print isset($datos['data']['clave']) ? htmlspecialchars($datos['data']['clave']) : ''; ?>" required>
                                    <label for="clave"><i class="bi bi-key-fill me-2"></i>Clave de Acceso</label>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="recordar" name="recordar" <?php print isset($datos['data']['usuario']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="recordar">
                                            Recordar
                                        </label>
                                    </div>
                                    <a href="<?php print RUTA; ?>login/olvido" class="small text-decoration-none">¿Olvidaste tu clave?</a>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Enviar
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                    
                    <p class="text-center text-muted small mt-4">
                        &copy; 2026 Xtreme Performance. Todos los derechos reservados.
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
