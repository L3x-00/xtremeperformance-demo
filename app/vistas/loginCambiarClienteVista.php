<?php include("encabezado.php"); ?>
	<form action="<?php print RUTA; ?>login/cambiarClaveCliente" method="POST">
		<div class="form-group text-left">
			<label for="clave">* Crea tu contraseña:</label>
			<input type="password" name="clave" class="form-control" placeholder="Escribe tu nueva contraseña." required>
		</div>
		<div class="form-group text-left">
			<label for="verifica">* Confirma tu contraseña:</label>
			<input type="password" name="verifica" class="form-control" placeholder="Repite tu nueva contraseña." required>
		</div>
		<div class="form-group text-left mt-2">
			<input type="submit" value="Guardar" class="btn btn-success">
			<input type="hidden" name="id" id="id" value="<?php print $datos['data']; ?>">
		</div>
	</form>
<?php include("piepagina.php"); ?>
