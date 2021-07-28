<div class="headerBack" style="background: linear-gradient(90deg, #9ebd13 0%, #008552 100%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="user circle icon"></i>
      <h2>Editar Promovente</h2>
    </div>
    <p class="txtBg">Editar Promovente</p>
  </div>
</div>
<main class="page-content">
  <div class="container-fluid">
    <section class="editarPromovente">
      <h3 class="ui top grey inverted attached header">PROMOVENTE</h3>
      <form class="ui form attached segment agregarPromovente" action="<?php echo base_url(); ?>GECOA/editarUsuario" method="post">
        <input type="hidden" name="idEnc" value="<?php echo $idEnc; ?>">
        <div class="three fields">
          <div class="required field">
            <label>Nombre/Razón Social:</label>
            <input type="text" disabled name="txt_nombre" placeholder="Nombre/Razón Social"  id="txt_nombre" value="<?php echo $txt_nombre; ?>">
          </div>
          <div class="required field">
            <label>Abreviatura:</label>
            <input type="text" disabled name="txt_display" placeholder=""  id="txt_display" value="<?php echo $txt_display; ?>">
          </div>
          <div class="required field">
            <label>RFC:</label>
            <input type="text" disabled name="txt_rfc" placeholder=""  id="txt_rfc" value="<?php echo $txt_rfc; ?>">
          </div>
        </div>
        <h4 class="ui dividing header">DIRECCIÓN</h4>
        <div class="three fields">
          <div class="required field">
            <label>Calle:</label>
            <input type="text" name="txt_calle" placeholder="Calle" id="txt_calle" value="<?php echo $txt_calle; ?>">
          </div>
          <div class="field">
            <label>Número Exterior:</label>
            <input type="text" name="txt_numExt" placeholder="Número Exterior" id="txt_numExt" value="<?php echo $txt_numExt; ?>">
          </div>
          <div class="field">
            <label>Número Interior:</label>
            <input type="text" name="txt_numInt" placeholder="Número Interior" id="txt_numInt" value="<?php echo $txt_numInt; ?>">
          </div>
        </div>
        <div class="three fields">
          <div class="required field">
            <label>Colonia:</label>
            <input type="text" name="txt_colonia" placeholder="Colonia" id="txt_colonia" value="<?php echo $txt_colonia; ?>">
          </div>
          <div class="required field">
            <label>País</label>
            <input type="text" name="txt_pais" placeholder="País" id="txt_pais" value="<?php echo $txt_pais; ?>">
          </div>
          <div class="required field">
            <label>Estado:</label>
            <input type="text" name="txt_estado" placeholder="Estado" id="txt_estado" value="<?php echo $txt_estado; ?>">
          </div>
        </div>
        <div class="three fields">
          <div class="field">
            <label>Celular:</label>
            <input type="text" name="txt_celular" placeholder="Celular" id="txt_celular" value="<?php echo $txt_celular; ?>">
          </div>
          <div class="field">
            <label>Teléfono Oficina:</label>
            <input type="text" name="txt_teloficina" placeholder="Oficina" id="txt_teloficina" value="<?php echo $txt_teloficina; ?>">
          </div>
          <div class="field">
            <label>Extensión:</label>
            <input type="text" name="txt_ext" placeholder="Extensión" id="txt_ext" value="<?php echo $txt_ext; ?>">
          </div>
        </div>
        <h4 class="ui dividing header">REPRESENTANTE LEGAL</h4>
        <div class="two fields">
          <div class="required field">
            <label>Nombre:</label>
            <input type="text" name="txt_nombreRep" placeholder="Nombre" id="txt_nombreRep" value="<?php echo $txt_nombreRep; ?>">
          </div>
          <div class="required field">
            <label>Correo electrónico:</label>
            <input type="text" name="txt_usuario" placeholder="Correo electrónico" id="txt_usuario" value="<?php echo $txt_usuario; ?>">
          </div>
        </div>
        <div class="ui divider"></div>
        <!-- <div class="field">
          <label>Logotipo:</label>
          <input type="file" name="txt_logo" id="txt_logo">
        </div>
        <div class="field">
          <label>INE:</label>
          <input type="file" name="txt_ine" id="txt_ine">
        </div>
        <div class="field">
          <label>Acta Constitutiva:</label>
          <input type="file" name="txt_actaConst" id="txt_actaConst">
        </div>
        <div class="field">
          <label>Carta Poder:</label>
          <input type="file" name="txt_cartaPoder" id="txt_cartaPoder">
        </div> -->
        <div class="field">
          <label>Página Web:</label>
          <input type="text" name="txt_pagweb" placeholder="Página web" id="txt_pagweb" value="<?php echo $txt_pagweb; ?>">
        </div>
        <div class="field">
          <div class="ui checkbox">
            <input type="checkbox" name="esAdministrador" id="esAdministrador" <?php echo $esAdministrador; ?>>
            <label>Hacer Administrador</label>
          </div>
        </div>
        <!-- FECHAS con DATEPICKER-->
        <div class="row" id="fechas_fields" style="display: none;">
          <div class="two fields">
            <div class="field fechainicio">
              <label>Fecha de Inicio:</label>
              <div class="ui calendar" id="rangestart">
                <div class="ui input left icon">
                  <i class="calendar icon"></i>
                  <input type="text" name="txt_fechainicio" id="txt_fechainicio" placeholder="Inicio" value="<?php echo $txt_fechainicio; ?>">
                </div>
              </div>
            </div>
            <div class="field fechafin">
              <label>Fecha de Termino:</label>
              <div class="ui calendar" id="rangeend">
                <div class="ui input left icon">
                  <i class="calendar icon"></i>
                  <input type="text" name="txt_fechafin" id="txt_fechafin" placeholder="Termino" value="<?php echo $txt_fechafin; ?>">
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--  -->
        <button type="submit" class="ui positive button" value="Guardar"><i class="save icon"></i>Guardar</button>
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

    $('#rangestart').calendar("set date",new Date(<?php echo '"'.$txt_fechainicio.'"'; ?>.replace(/-/g, '\/')));
    $('#rangeend').calendar("set date",new Date(<?php echo '"'.$txt_fechafin.'"'; ?>.replace(/-/g, '\/')));
    //console.log($('#esAdministrador').prop('checked'));
    if($('#esAdministrador').prop('checked')) {
      $('#fechas_fields').css('display', 'block');
      $('.fechainicio').addClass('required');
      $('.fechafin').addClass('required');
    }else{
      $('#fechas_fields').css('display', 'none');
      $('.fechainicio').removeClass('required');
      $('.fechafin').removeClass('required');
    }
  });
</script>
