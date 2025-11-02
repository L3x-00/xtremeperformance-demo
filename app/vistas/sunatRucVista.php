<?php include_once('encabezado.php'); ?>
<div class="container mt-3">
    <h3>Consulta RUC</h3>
    <p>Consulta rápida de RUC usando un servicio público.</p>

    <?php if (!empty($datos['errores'])) { ?>
        <div class="alert alert-danger">
            <ul>
            <?php foreach ($datos['errores'] as $e) { echo '<li>'.$e.'</li>'; } ?>
            </ul>
        </div>
    <?php } ?>

    <form method="post" action="<?php print RUTA; ?>Sunat/ruc">
        <div class="mb-3">
            <label for="ruc" class="form-label">RUC (11 dígitos)</label>
            <input id="ruc" name="ruc" class="form-control" maxlength="11" required value="<?php print isset($_POST['ruc'])?htmlspecialchars($_POST['ruc']):''; ?>">
        </div>
        <button class="btn btn-primary" type="submit">Consultar</button>
    </form>

    <?php if (!empty($datos['resultado'])) {
        $res = $datos['resultado'];
        if (isset($res['error']) && $res['error']==true) { ?>
            <div class="alert alert-warning mt-3"><?php print htmlspecialchars($res['message']); ?></div>
        <?php } else { ?>
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title"><?php print htmlspecialchars($res['nombre'] ?? $res['razonSocial'] ?? ''); ?></h5>
                    <p class="card-text">
                        <b>RUC:</b> <?php print htmlspecialchars($res['ruc'] ?? ''); ?><br>
                        <b>Estado:</b> <?php print htmlspecialchars($res['condition'] ?? $res['estado'] ?? $res['estado_del_contribuyente'] ?? ''); ?><br>
                        <b>Dirección:</b> <?php print htmlspecialchars($res['direccion'] ?? $res['domicilio_fiscal'] ?? ''); ?><br>
                        <b>Actividad:</b> <?php print htmlspecialchars($res['actividad_economica'] ?? $res['actividad'] ?? ''); ?><br>
                    </p>
                </div>
            </div>
        <?php }
    } ?>
</div>

<?php include_once('piepagina.php'); ?>
