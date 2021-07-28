<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="truck circle icon"></i>
      <h2>Pesar Vehículo</h2>
    </div>
    <p class="txtBg">Pesar Vehículo</p>
  </div>
</div>

<main class="page-content">
  <div class="container-fluid">
    <section class="addTruck">
      <h3 class="ui top grey inverted attached header">VEHÍCULO</h3>
      <form class="ui form attached segment buscarVehiculo" action="<?php echo base_url(); ?>Pedrera/weighTruck" method="post">
        <div class="field">
          <div class="ui action input">
            <input type="text" name="txt_plate" placeholder="Placa" id="txt_plate" value="<?php echo $plate; ?>">
            <button type="submit" class="ui button guardarUsuario" value="Buscar"><i class="search icon"></i>Buscar</button>
          </div>
        </div>
        <!-- <div class="ui divider"></div>                   -->
        <!-- <?php if ($error != NULL) { ?>
          <div class="ui red icon message errorMsg">
            <i class="exclamation triangle icon"></i>
            <div class="content">
              <div class="header">
                <?php echo $error; ?>
              </div>
              <p> Verifica que los datos sean correctos.</p>
            </div>
          </div>
        <?php } ?> -->
      </form>

      <form style="display: none" class="ui form attached segment dataInfo <?php echo $showInfo; ?>" action="<?php echo base_url(); ?>Pedrera/weighTruck" method="post">
        <input type="hidden" name="idLog" value="<?php echo $idLog; ?>">
        <div class="two fields">
          <div class="field">
            <label>Conductor:</label>
            <input disabled type="text" name="txt_driver" placeholder="Nombre del conductor" id="txt_driver" value="<?php echo $driver; ?>">
          </div>
          <div class="field">
            <label>Compañia fletera:</label>
            <input disabled type="text" name="txt_company" placeholder="Compañia fletera" id="txt_company" value="<?php echo $company; ?>">
          </div>
        </div>
        <div class="three fields">
          <div class="field">
            <label>Tipo de vehículo:</label>
            <input disabled type="text" name="txt_tipo" placeholder="Tipo de vehículo" id="txt_tipo" value="<?php echo $tipo; ?>">
          </div>
          <div class="field">
            <label>Color:</label>
            <input disabled type="text" name="txt_truckColor" placeholder="Color" id="txt_truckColor" value="<?php echo $truckColor; ?>">
          </div>
          <div class="field">
            <label>Color de Caja:</label>
            <input disabled type="text" name="txt_bedColor" placeholder="Color" id="txt_bedColor" value="<?php echo $bedColor; ?>">
          </div>
        </div>
        <button type="submit" class="ui positive button guardarUsuario" value="Guardar"><i class="save icon"></i>Guardar</button>
      </form>


    </section>
  </div>
</main>

<div class="ui mini modal <?php echo $mostrarModal; ?>">
  <div class="header">¡Registro Exitoso!</div>
  <div class="image content">
    <i class="huge green check circle icon"></i>
    <div class="description">
      <p><?php echo $txtModal; ?></p>
    </div>
  </div>
  <div class="actions">
    <a href="<?php echo base_url(); ?>Pedrera/" class="ui primary approve button"><i class="check icon"></i>Aceptar</a>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {





    if ($('.ui.form.dataInfo').hasClass('showInfo')) {
      $('.ui.form.dataInfo').css("display", "block");
    } else {
      $('.ui.form.dataInfo').css("display", "none");
    }

    if ($('.mini.modal').hasClass('mostrarModal')) {
      $('.mini.modal').modal('show');
    }


    <?php if ($error != '') { ?>
      $('.ui.form.buscarVehiculo').form('add prompt', 'txt_plate', '<?php echo $error; ?>');
    <?php } ?>

    $('.ui.form.buscarVehiculo')
      .form({
        fields: {
          txt_plate: {
            rules: [{
              type: 'empty',
              prompt: 'Ingresa la placa del vehículo'
            }]
          }
        },
        inline: true
      });

  });
</script>