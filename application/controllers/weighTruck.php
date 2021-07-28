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
      <form class="ui form attached segment agregarSupervisor" action="<?php echo base_url(); ?>Pedrera/addTruck" method="post">
        <div class="three fields">
          <div class="field">
            <label>Placa:</label>
            <input type="text" name="txt_plate" placeholder="Placa" id="txt_plate" value="<?php echo $plate; ?>">
          </div>
          <div class="field">
            <label>Color vehículo:</label>
            <input type="text" name="txt_truckColor" placeholder="Color vehículo" id="txt_truckColor" value="<?php echo $truckColor; ?>">
          </div>
          <div class="field">
            <label>Color de la caja:</label>
            <input type="text" name="txt_bedColor" placeholder="Color caja" id="txt_bedColor" value="<?php echo $bedColor; ?>">
          </div>
        </div>
        <div class="two fields">
          <div class="field">
            <label>Tipo de vehículo:</label>
            <div class="ui selection dropdown txt_tipo">
              <input type="hidden" name="txt_tipo">
              <i class="dropdown icon"></i>
              <div class="default text">Tipo de vehículo:</div>
              <div class="menu">
                
                  <div class="item" data-value="1">Trailer</div>
                  <div class="item" data-value="2">Torton</div>
                  <div class="item" data-value="3">Rabón</div>
              </div>
          </div>
          </div>   
          <div class="field">
            <label>Material:</label>
            <div class="ui selection dropdown txt_material">
              <input type="hidden" name="txt_material">
              <i class="dropdown icon"></i>
              <div class="default text">Tipo de material:</div>
              <div class="menu">
                <?php foreach ($materials as $material): ?>
                  <div class="item" data-value="<?php echo $material['id']; ?>"><?php echo $material['nameMaterial']; ?></div>                  
                <?php endforeach; ?>
              </div>
          </div>
          </div>          
        </div>  
        <div class="two fields">
          <div class="field">
            <label>Conductor:</label>
            <input type="text" name="txt_driver" placeholder="Nombre del conductor" id="txt_driver" value="<?php echo $driver; ?>">
          </div>
          <div class="field">
            <label>Compañia fletera:</label>
            <input type="text" name="txt_company" placeholder="Compañia fletera" id="txt_company" value="<?php echo $company; ?>">
          </div>          
        </div>      
        <div class="ui divider"></div>
        <button type="submit" class="ui positive button guardarUsuario" value="Guardar"><i class="save icon"></i>Guardar</button>
        <a class="ui right floated secondary  button" name="button" href="<?php echo base_url(); ?>Pedrera/"><i class="arrow left icon"></i> Regresar</a>
        <button type="button"  class="ui right floated clear button " name="button"><i class="eraser icon"></i> Limpiar</button>
        <?php if ($error != NULL){ ?>
          <div class="ui red icon message errorMsg">
            <i class="exclamation triangle icon"></i>
            <div class="content">
              <div class="header">
                <?php echo $error; ?>
              </div>
              <p> Verifica que los datos sean correctos.</p>
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
      <p><?php echo $txtModal; ?></p>
    </div>
  </div>
  <div class="actions">
    <a href="<?php echo base_url(); ?>Pedrera/" class="ui primary approve button"><i class="check icon"></i>Aceptar</a>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    $('.dropdown.txt_tipo').dropdown();
    $('.dropdown.txt_material').dropdown();

    $('#txt_plate').on('keyup', function() {
      $('.errorMsg').css("display", "none");
    });

    <?php if($tipo!=''){?>
      $('.txt_tipo').dropdown('set selected', <?php echo $tipo; ?>);
    <?php } ?>

    <?php if($materialSel!=-1){?>
      $('.txt_material').dropdown('set selected', <?php echo $materialSel; ?>);
    <?php } ?>

    if ($('.mini.modal').hasClass('mostrarModal')) {
      $('.mini.modal').modal('show');
    }

    $('.ui.form')
      .form({
        fields: {
          txt_plate     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa la placa del vehículo'
              }
            ]
          },
          txt_material     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa el material requerido'
              }
            ]
          },
          txt_bedColor     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa el color de la caja del vehículo'
              }
            ]
          },
          txt_tipo     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Selecciona un tipo de vehículo'
              }
            ]
          },
          txt_truckColor     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingrega el color de vehículo'
              }
            ]
          }
        },
        inline: true
      });

  });
</script>
