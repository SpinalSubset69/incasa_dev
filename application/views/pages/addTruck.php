<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="truck circle icon"></i>
      <h2>Registrar Vehículo</h2>
    </div>
    <p class="txtBg">Registrar Vehículo</p>
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
            <label>Conductor:</label>
            <div class="ui search driver">              
              <div class="ui icon input">
                <input class="prompt" name="txt_driver" type="text" placeholder="Nombre del conductor" id="txt_driver" value="<?php echo $driver; ?>">
              </div>
              <div class="results"></div>
            </div>            
          </div>
          <div class="field">
            <label>Compañia fletera:</label>
            <div class="ui search company">              
              <div class="ui icon input">
                <input class="prompt" type="text" name="txt_company" placeholder="Compañia fletera" id="txt_company" value="<?php echo $company; ?>">
              </div>
              <div class="results"></div>
            </div>             
          </div> 
        </div>
        <div class="two fields">
          <div class="field">
            <label>GPS:</label>
            <div class="ui selection dropdown txt_gps">
              <input type="hidden" name="txt_gps">
              <i class="dropdown icon"></i>
              <div class="default text">GPS:</div>
              <div class="menu">
                <?php foreach ($gps as $gp): ?>
                  <div class="item" data-value="<?php echo $gp['idGPS']; ?>"><?php echo $gp['idGPS']; ?></div>
                <?php endforeach; ?>
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
                  <div class="item" data-value="<?php echo $material['idMaterial']; ?>"><?php echo $material['nameMaterial']; ?></div>                  
                <?php endforeach; ?>
              </div>
          </div>
          </div>          
        </div>               
        <div class="ui divider"></div>
        <button type="submit" class="ui positive button guardarUsuario" value="Guardar"><i class="save icon"></i>Guardar</button>
        <a class="ui right floated secondary  button" name="button" href="<?php echo base_url(); ?>Pedrera/"><i class="arrow left icon"></i> Regresar</a>
        <button type="button"  class="ui right floated clear button " name="button"><i class="eraser icon"></i> Limpiar</button>        
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
    $('.dropdown.txt_gps').dropdown();
    $('.dropdown.txt_material').dropdown();

    var contentDriver = [
      <?php foreach($drivers as $driver): ?>
          { title: '<?php echo $driver['name']; ?>' },
      <?php endforeach; ?>    
    ];

    var contentCompany = [
      <?php foreach($companies as $company): ?>
          { title: '<?php echo $company['name']; ?>' },
      <?php endforeach; ?>  
    ];

    $('.ui.search.driver')
     .search({
      source: contentDriver,
      templates: {
        message: function(type, message) {
          html = '';
        return html;
        }
      }
    });

    $('.ui.search.company')
     .search({
      source: contentCompany,
      templates: {
        message: function(type, message) {
          html = '';
        return html;
        }
      }
    });

    <?php if($error!=''){ ?>
        $('.ui.form').form('add prompt', 'txt_plate', '<?php echo $error; ?>');
    <?php } ?>  

    <?php if($tipo!=''){?>
      $('.txt_tipo').dropdown('set selected', <?php echo $tipo; ?>);
    <?php } ?>

    <?php if($gps2!=''){?>
      $('.txt_gps').dropdown('set selected', <?php echo $gps2; ?>);
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
          txt_gps     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Selecciona un gps'
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
