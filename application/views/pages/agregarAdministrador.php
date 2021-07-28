  <div class="headerBack" style="background: linear-gradient(90deg, #9ebd13 0%, #008552 100%);">
    <div class="page-content miHeader">
      <div class="miTitulo">
        <i class="user circle icon"></i>
        <h2>Registrar Administrador</h2>
      </div>
      <p class="txtBg">Registrar Administrador</p>
    </div>
  </div>

  <main class="page-content">
    <div class="container-fluid">
      <section class="agregarUsuarios">
        <h3 class="ui top grey inverted attached header">ADMINISTRADOR</h3>
        <form class="ui form attached segment agregarAdministrador" id="miform" action="<?php echo base_url(); ?>GECOA/agregarAdministrador" method="post">
          <div class="field">
            <label>Nombre(s):</label>
            <input type="text" name="txt_nombre" placeholder="Nombre(s)" id="txt_nombre" value="<?php echo $txt_nombres; ?>">
          </div>
          <div class="field">
            <label>Corrreo electrónico:</label>
            <input type="email" name="txt_usuario" placeholder="Correo electrónico" id="txt_usuario" value="<?php echo $txt_usuario; ?>">
          </div>
          <button type="submit" class="ui positive button guardarUsuario" value="Guardar"><i class="save icon"></i>Guardar</button>
          <a class="ui right floated secondary  button" name="button" href="<?php echo base_url(); ?>GECOA/"><i class="arrow left icon"></i> Regresar</a>
          <button type="button"  class="ui right floated clear button " name="button"><i class="eraser icon"></i> Limpiar</button>

          <?php if ($error != NULL){ ?>
            <div class="ui red icon message">
              <i class="exclamation triangle icon"></i>
              <div class="content">
                <div class="header">
                  <?php echo $error; ?>
                </div>
                <p> Verifica que tus datos sean correctos.</p>
              </div>
            </div>
          <?php } ?>
        </form>
      </section>
    </div>
  </main>

  <div class="ui mini modal <?php echo $mostrarModal; ?>">
    <div class="header">¡Registro Exitoso!</div>
    <div class="image content">
      <i class="huge green check circle icon"></i>
      <div class="description">
        <p>El usuario se ha guardado correctamente.</p>
      </div>
    </div>
    <div class="actions">
      <a href="<?php echo base_url(); ?>GECOA/" class="ui primary approve button"><i class="check icon"></i>Aceptar</a>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(function(){
      if ($('.mini.modal').hasClass('mostrarModal')) {
        $('.mini.modal').modal('show');
      }
    });
  </script>
