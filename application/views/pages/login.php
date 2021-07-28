<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content= "width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <title>INCASA</title>
  <!-- <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets2/imgs/favicon.png"> -->
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/css/pedreraStyles.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/semantic/components/form.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/semantic/components/input.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/semantic/components/message.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/semantic/components/button.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/semantic/components/icon.min.css">
  <script type="text/javascript" src="<?php echo base_url(); ?>assets2/js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/form.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets2/js/sha256.min.js"></script>
</head>
<body>
  <section class="bgLogin" style="background-image:url('<?php echo base_url(); ?>assets2/imgs/bg.jpg');">
    <div class="containerLogin">
      <div class="side side1">
        <div class="containerForm">
          <!-- <img src="<?php echo base_url(); ?>assets2/imgs/incasa-76.png" alt=""> -->
          <div class="formLogin">
            <h1>¡Bienvenido!</h1>
            <h4>Inicia sesión en tu cuenta</h4>
            <form action="<?php echo base_url(); ?>Pedrera/login" method="post" class="ui form loginForm">
              <input type="hidden" id="txt_contrasena" name="txt_contrasena">
              <div class="field">
                <div class="ui left icon input">
                  <i class="user icon" style="color: rgb(41, 50, 82)"></i>
                  <input type="text" id="txt_usuario" name="txt_usuario"  placeholder="Usuario" >
                </div>
              </div>
              <div class="field">
                <div class="ui left icon input">
                  <i class="lock icon" style="color: rgb(41, 50, 82)"></i>
                  <input type="password" id="txt_contrasena1" name="txt_contrasena1" placeholder="Contraseña" >
                </div>
              </div>
              <button type="submit" class="ui submit button loginButton" style="background-color: rgb(41, 50, 82); color: white">ENTRAR</button>
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
          </div>
          <div class="txtLogin">
            <p></p>
          </div>
        </div>
      </div>
      <div class="side side2 bg" style="background-image:url('<?php echo base_url(); ?>assets2/imgs/bg.jpg');"></div>
    </div>
    <div class="overlay"></div>
  </section>
</body>
</html>
<script type="text/javascript">
  $(document).ready(function(){

    $('.loginForm').on('submit',function(e){
      pass=$("#txt_contrasena1").val();
      passCifrado=sha256(pass);
      $("#txt_contrasena").val(passCifrado);
      $("#txt_contrasena1").prop("disabled",true);
      console.log(passCifrado);
    });

    /*$('.loginButton').click(function(){

      $('.loginForm').submit();
    });*/
  });
</script>
