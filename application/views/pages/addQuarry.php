<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="user circle icon"></i>
      <h2>Agregar Pedrera</h2>
    </div>
    <p class="txtBg">Agregar Pedrera</p>
  </div>
</div>

<main class="page-content">
  <div class="container-fluid">
    <section class="addTruck">
      <h3 class="ui top grey inverted attached header">PEDRERA</h3>
      <form class="ui form attached segment agregarSupervisor" action="<?php echo base_url(); ?>Pedrera/addUsers" method="post">
        <!-- <div class="two fields"> -->
          <div class="field">
              <label>Nombre:</label>
              <input type="text" name="txt_userName" placeholder="Nombre completo" id="txt_userName" value="<?php echo $userName; ?>">
          </div>
          <!-- <div class="field">
            <label>Pedrera:</label>
            <div class="ui selection dropdown txt_Pedrera">
              <input type="hidden" name="txt_Pedrera">
              <i class="dropdown icon"></i>
              <div class="default text">Pedrera</div>
              <div class="menu">  
                <?php foreach($quarries as $quarry): ?>              
                  <div class="item" data-value="<?php echo $quarry['idQuarry']; ?>"><?php echo $quarry['nameQuarry']; ?></div>                
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>         -->
         
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

    
    $('.dropdown.txt_tipoUsuario').dropdown();
    $('.dropdown.txt_Pedrera').dropdown();
    

    if ($('.mini.modal').hasClass('mostrarModal')) {
      $('.mini.modal').modal('show');
    }

    <?php if($error!=''){ ?>
        $('.ui.form').form('add prompt', 'txt_user', '<?php echo $error; ?>');
    <?php } ?>  

    <?php if($tipoUsuario!=''){?>
      $('.txt_tipoUsuario').dropdown('set selected', <?php echo $tipoUsuario; ?>);
    <?php } ?>
    <?php if($pedrera!=''){?>
      $('.txt_Pedrera').dropdown('set selected', <?php echo $pedrera; ?>);
    <?php } ?>  

    $('.ui.form')
      .form({
        fields: {
          txt_userName     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa el nombre completo del usuario'
              }
            ]
          },
          txt_user     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa el nombre de usuario'
              }
            ]
          },
          txt_password     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa la contraseña'
              }
            ]
          },
          txt_tipoUsuario     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa el tipo de usuario'
              }
            ]
          },
          txt_Pedrera     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa la pedrera del usuario'
              }
            ]
          }
        },
        inline: true
      });

  });
</script>
