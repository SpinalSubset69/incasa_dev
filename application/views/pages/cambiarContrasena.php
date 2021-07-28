<!DOCTYPE html>
<html >
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content= "width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <!-- <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets2/imgs/favicon.png"> -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/semantic/semantic.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/css/header.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/css/gecoaStyles.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/css/back.min.css">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets2/js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/semantic.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets2/js/gecoa.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets2/js/sha256.min.js"></script>
  </head>
  <body>
    <section class="bgLogin" style="background-image:url('<?php echo base_url(); ?>assets2/imgs/gecoa1.jpeg');">
      <div class="containerCambiarPass">
        <div class="passTitulo">
          <div class="bgIcon">
            <i class="lock icon"></i>
          </div>
          <div class="titulo">
            <h1>Inicia sesión en tu cuenta</h1>
            <p>Es necesario agregar una contraseña para ingresar a <span>GECOA</span>.</p>
          </div>
        </div>
        <div class="nombreUsuario">
          <div class="nombre">
            <p><i class="user icon"></i> USUARIO: </p>
            <div class=""><?php echo $usuario; ?></div>
          </div>
        </div>
        <div class="formPass">
          <form action="<?php echo base_url(); ?>GECOA/cambiarContrasena/<?php echo $ticket; ?>" method="post" class="ui form formContrasena">
            <div class="field">
              <label for="txt_contrasena1">Contraseña</label>
              <div class="ui left icon input">
                <i class="lock icon"></i>
                <input type="password" name="txt_contrasena1" id="txt_contrasena1" placeholder="Contraseña" >
              </div>
            </div>
            <div class="field">
              <label for="txt_contrasena2">Confirmar Contraseña</label>
              <div class="ui left icon input">
                <i class="lock icon"></i>
                <input type="password" name="txt_contrasena2" id="txt_contrasena2" placeholder="Contraseña" >
              </div>
            </div>
            <button type="submit" class="ui fluid green icon button guardarContrasena"><i class="save icon"></i>Guardar</button>
            <div class="ui red icon message msgContrasena" style="display:none;">
              <i class="exclamation triangle icon"></i>
              <div class="content">
                <div class="header"> </div>
                <p> Verifica que tus datos sean correctos.</p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
    <div class="ui mini modal <?php echo $mostrarModal; ?>">
      <div class="header">¡Contraseña Guardada!</div>
      <div class="image content">
        <i class="huge green check circle icon"></i>
        <div class="description">
          <p>La contraseña se ha guardado con exito.</p>
        </div>
      </div>
      <div class="actions">
        <a href="<?php echo base_url(); ?>GECOA/" class="ui primary approve button"><i class="check icon"></i>Aceptar</a>
      </div>
    </div>
  </body>
</html>

<script type="text/javascript">
  $(document).ready(function(){

    if ($('.mini.modal').hasClass('mostrarModal')) {
      $('.mini.modal').modal('show');
    }

    $('.formContrasena').on('submit',function(e){
      pass1 = $('#txt_contrasena1').val();
      pass2 = $('#txt_contrasena2').val();
      //TO DO
      //CAMBIAR MENSAJES DE VALIDACIONES
      if (pass2 != pass1) {
        $('.msgContrasena .header').text('Contraseña diferente');
        e.preventDefault();
        return false;
        // $('.msgContrasena').css('display','inline-flex');
      }else if(pass1 == ""){
        $('.msgContrasena .header').text('Campos Vacios');
        $('.msgContrasena').css('display','inline-flex');
        e.preventDefault();
        return false;
      } else{
        $('#txt_contrasena1').val(sha256($('#txt_contrasena1').val()));
      }
    });

    /*$('.guardarContrasena').click(function(){
      pass1 = $('#txt_contrasena1').val();
      pass2 = $('#txt_contrasena2').val();
      //TO DO
      //CAMBIAR MENSAJES DE VALIDACIONES
      if (pass2 != pass1) {
        $('.msgContrasena .header').text('Contraseña diferente');
        // $('.msgContrasena').css('display','inline-flex');
      }else if(pass1 == ""){
        $('.msgContrasena .header').text('Campos Vacios');
        $('.msgContrasena').css('display','inline-flex');
      } else{
        $('#txt_contrasena1').val(sha256($('#txt_contrasena1').val()));
        $('.formContrasena').submit();
      }
    });*/
  });
</script>
