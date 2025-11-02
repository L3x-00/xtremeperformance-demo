<?php include_once("encabezado.php"); ?>
  <form action="<?php print RUTA; ?>clientes/alta/" method="POST">

    <div class="form-group text-left">
      <label for="nombres">* Nombres:</label>
      <input type="text" name="nombres" id="nombres" class="form-control" required value="<?php print isset($datos['data']['nombres'])?$datos['data']['nombres']:''; ?>" <?php if (isset($datos["baja"])) { print " disabled "; }?>>
    </div>

    <div class="form-group text-left">
      <label for="apellidos">* Apellidos:</label>
      <input type="text" name="apellidos" id="apellidos" class="form-control" required value="<?php print isset($datos['data']['apellidos'])?$datos['data']['apellidos']:''; ?>" <?php if (isset($datos["baja"])) { print " disabled "; }?>>
    </div>

    <div class="form-group text-left">
      <label for="razonSocial">Razon social:</label>
      <input type="text" name="razonSocial" id="razonSocial" class="form-control" value="<?php print isset($datos['data']['razonSocial'])?$datos['data']['razonSocial']:''; ?>" <?php if (isset($datos["baja"])) { print " disabled "; }?>>
    </div>

    <div class="form-group text-left">
      <label for="direccion">Dirección:</label>
      <input type="text" name="direccion" id="direccion" class="form-control" value="<?php print isset($datos['data']['direccion'])?$datos['data']['direccion']:''; ?>" <?php if (isset($datos["baja"])) { print " disabled "; }?>>
    </div>

    <div class="form-group text-left">
      <label for="telefono">Teléfono:</label>
      <input type="tel" name="telefono" id="telefono" class="form-control" placeholder="9XXXXXXXX" pattern="^9\d{8}$" minlength="9" maxlength="9" inputmode="numeric" value="<?php print isset($datos['data']['telefono'])?$datos['data']['telefono']:''; ?>" <?php if (isset($datos["baja"])) { print " disabled "; }?>>
      <small class="form-text text-muted">Si lo ingresa, debe iniciar con 9 y tener 9 dígitos (Perú).</small>
    </div>

    <div class="form-group text-left">
      <label for="ruc">RUC:</label>
      <div class="input-group">
        <input type="text" name="ruc" id="ruc" class="form-control" placeholder="11 dígitos" pattern="^\d{11}$" minlength="11" maxlength="11" inputmode="numeric" value="<?php print isset($datos['data']['ruc'])?$datos['data']['ruc']:''; ?>" <?php if (isset($datos["baja"])) { print " disabled "; }?>>
        <button type="button" id="btnBuscarRuc" class="btn btn-outline-secondary">Buscar RUC</button>
      </div>
      <small class="form-text text-muted">Si lo ingresas, debe contener solo números y 11 dígitos.</small>
    </div>

    <div class="form-group text-left">
      <label for="correo">* Correo:</label>
      <input type="email" name="correo" id="correo" class="form-control" required value="<?php print isset($datos['data']['correo'])?$datos['data']['correo']:''; ?>" <?php if (isset($datos["baja"])) { print " disabled "; }?>>
    </div>

   <div class="form-group text-left">
  <label for="estado">* Estado del cliente:</label>
  <select class="form-control" name="id_estado_cliente" id="estado" <?php if (isset($datos["baja"])) { print " disabled "; } ?>>
    <option value="void">---Selecciona un estado---</option>
    <?php
      foreach ($datos["estadoCliente"] as $estado) { 
        print "<option value='".$estado["id"]."'";
        // Condición corregida: ahora busca 'id_estado_cliente'
        if(isset($datos["data"]["id_estado_cliente"]) && $datos["data"]["id_estado_cliente"] == $estado["id"]){
          print " selected ";
        }
        print ">".$estado["estado"]."</option>";
      } 
    ?>
  </select>
</div>
    <div class="form-group text-start">
      <input type="hidden" name="id" id="id" value="<?php if (isset($datos['data']['id'])) { print $datos['data']['id']; } else { print ""; } ?>">
      <input type="hidden" name="pagina" id="pagina" value="<?php if (isset($datos['pagina'])) { print $datos['pagina']; } else { print "1"; } ?>">
      
      <?php if (isset($datos["baja"])) { ?>
        <a href="<?php print RUTA; ?>clientes/bajaLogica/<?php print $datos['data']['id']."/".$datos["pagina"]; ?>" class="btn btn-danger">Borrar</a>
        <a href="<?php print RUTA.'clientes/'.$datos['pagina']; ?>" class="btn btn-danger">Regresar</a>
        <p><b>Advertencia: una vez borrado el registro, no podrá recuperar la información.</b></p>
      <?php } else { ?>
      <input type="submit" value="Enviar" class="btn btn-success">
      <a href="<?php print RUTA; ?>clientes" class="btn btn-info">Regresar</a>
      <?php } ?> 
    </div>
  </form>
  <script>
  (function(){
    const btn = document.getElementById('btnBuscarRuc');
    if (!btn) return;
    btn.addEventListener('click', function(){
      const ruc = document.getElementById('ruc').value.trim();
      if (!/^[0-9]{11}$/.test(ruc)) {
        alert('Ingrese un RUC válido de 11 dígitos');
        return;
      }
      btn.disabled = true;
      btn.innerText = 'Buscando...';
      const form = new FormData();
      form.append('ruc', ruc);
      fetch('<?php print RUTA; ?>Sunat/rucAjax', {
        method: 'POST',
        body: form,
        credentials: 'same-origin'
      }).then(r=>r.text()).then(text=>{
        btn.disabled = false; btn.innerText = 'Buscar RUC';
        if (!text) { alert('Respuesta vacía'); return; }
        let data;
        try {
          data = JSON.parse(text);
        } catch(e) {
          // Show raw response to help debugging
          alert('Respuesta inválida del servidor:\n'+text);
          console.error('JSON parse error:', e, text);
          return;
        }
        if (data.error) { alert(data.message || 'Error al consultar RUC'); return; }
        // Map known fields to the form
        const razon = data.razonSocial || data.nombre || data.razon_social || data['nombre_comercial'] || '';
        const direccion = data.direccion || data.domicilio_fiscal || data['direccion'] || '';
        const correo = '';
        if (razon) document.getElementById('razonSocial').value = razon;
        if (direccion) document.getElementById('direccion').value = direccion;
        if (correo) document.getElementById('correo').value = correo;
      }).catch(err=>{
        btn.disabled = false; btn.innerText = 'Buscar RUC';
        alert('Error en la petición: '+err.message);
      });
    });
  })();
  </script>
<?php include_once("piepagina.php"); ?>