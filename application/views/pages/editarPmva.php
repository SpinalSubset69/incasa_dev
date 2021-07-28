<div class="headerBack" style="background: linear-gradient(90deg, #9ebd13 0%, #008552 100%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="user circle icon"></i>
      <h2>Editar Administrador</h2>
    </div>
    <p class="txtBg">Editar Administrador</p>
  </div>
</div>
<main class="page-content">
  <div class="container-fluid">
    <section class="agregarUsuarios">
      <h3 class="ui top grey inverted attached header">PMVA</h3>
      <form class="ui form attached segment agregarPmva" action="<?php echo base_url(); ?>GECOA/editarUsuario" method="post">
        <input type="hidden" name="idEnc" value="<?php echo $idEnc; ?>">
        <div class="three fields">
          <div class="required field">
            <label>Nombre(s):</label>
            <input type="text" name="txt_nombres" placeholder="Nombre(s)" id="txt_nombres" value="<?php echo $txt_nombres; ?>">
          </div>
          <div class="required field">
            <label>Apellido Paterno:</label>
            <input type="text" name="txt_apellidoP" placeholder="Apellido Paterno" id="txt_apellidoP" value="<?php echo $txt_apellidoP; ?>">
          </div>
          <div class="field">
            <label>Apellido Materno:</label>
            <input type="text" name="txt_apellidoM" placeholder="Apellido Materno" id="txt_apellidoM" value="<?php echo $txt_apellidoM; ?>">
          </div>
        </div>
        <div class="two fields">
          <div class="field">
            <label>Teléfono:</label>
            <input type="text" name="txt_telefono" placeholder="Teléfono" id="txt_telefono" value="<?php echo $txt_telefono; ?>">
          </div>
          <div class="required field">
            <label>Correo electrónico:</label>
            <input type="text" disabled name="txt_usuario" placeholder="Correo electrónico" id="txt_usuario" value="<?php echo $txt_usuario; ?>">
          </div>
        </div>
        <button type="submit" class="ui positive button" value="Guardar"><i class="save icon"></i>Guardar</button>
        <!-- <button type="button"  class="ui clear button " name="button"><i class="eraser icon"></i> Limpiar</button> -->
        <a class="ui secondary right floated button" name="button" href="<?php echo base_url(); ?>GECOA/verUsuarios"><i class="arrow left icon"></i> Regresar</a>

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
    <a href="<?php echo base_url(); ?>GECOA/verUsuarios" class="ui primary approve button"><i class="check icon"></i>Aceptar</a>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    if ($('.mini.modal').hasClass('mostrarModal')) {
      $('.mini.modal').modal('show');
    }
  });
</script>
