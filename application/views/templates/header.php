<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>INCASA</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/semantic/semantic.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/css/header.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/css/pedreraStyles.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets2/css/back.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.7.8/components/calendar.css">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets2/js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/semantic.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets2/js/gecoa.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets2/js/validaciones.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.7.8/components/calendar.min.js"></script>
  </head>
  <body>
    <a id="show-sidebar" class="" href="#">
      <i class="bars icon"></i>
      <p>MENÚ</p>
    </a>
    <div class="page-wrapper chiller-theme toggled">
      <nav id="sidebar" class="sidebar-wrapper">
        <div class="sidebar-content">
          <div class="sidebar-brand">
            <a href="<?php echo base_url(); ?>Pedrera/index">INCASA</a>
            <div id="close-sidebar">
              <i class="close icon"></i>
            </div>
          </div>

          <div class="sidebar-header">
            <div class="user-pic">
              <img class="img-responsive img-rounded" src="https://raw.githubusercontent.com/azouaoui-med/pro-sidebar-template/gh-pages/src/img/user.jpg"
                alt="User picture">
            </div>
            <div class="user-info">
              <span class="user-name"><?php echo $user; ?></span>
              <span class="user-role"><?php echo $type; ?></span>
            </div>
          </div>
          <!-- sidebar-header  -->
          <div class="sidebar-menu">
            <ul>
              <li class="header-menu">
                <span>General</span>
              </li>
              <!-- ejemplo de opcion  de menu -->
              <?php foreach ($menu as $men):?>
              <li class="sidebar-dropdown">
                <a href="#">
                  <i class="<?php echo $men['icono'];?> icon-color"></i>
                  <span><?php echo $men['nombre']; ?></span>
                </a>
                <div class="sidebar-submenu">
                  <ul>
                    <?php foreach($men['submenu'] as $sub): ?>
                    <li>
                      <a href="<?php echo base_url(); ?><?php echo $sub['direccion']; ?>"><?php echo $sub['nombre']; ?>
                        <!-- <span class="ui yellow tiny label">Pro</span> -->
                      </a>
                    </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </li>
              <?php endforeach; ?>
              <!-- termina ejemplo de  opcion de menu -->

              <!-- <li class="sidebar-dropdown">
                <a href="#">
                  <i class="shopping bag icon"></i>
                  <span>E-commerce</span>
                  <span class="ui red circular tiny label">3</span>
                </a>

                <div class="sidebar-submenu">
                  <ul>
                    <li>
                      <a href="#">Productos</a>
                    </li>
                  </ul>
                </div>
              </li> -->
              <!-- <li class="header-menu">
                <span>Extra</span>
              </li>
              <li>
                <a href="#">
                  <i class="book icon"></i>
                  <span>Documentación</span>
                </a>
              </li>
              <li>
                <a href="#">
                  <i class="list icon"></i>
                  <span>Proyectos</span>
                </a>
              </li> -->
            </ul>
          </div>
          <!-- sidebar-menu  -->
        </div>
        <!-- sidebar-content  -->
        <div class="sidebar-footer">
          <!-- <a href="#">
            <i class="bell icon"></i>
            <span class="floating ui yellow circular tiny label">3</span>
          </a>
          <a href="#">
            <i class="envelope icon"></i>
            <span class="floating ui green circular tiny label">7</span>
          </a>
          <a href="#">
            <i class="cog icon"></i>
            <span class="badge-sonar"></span>
          </a> -->
          <a href="<?php echo base_url(); ?>Pedrera/logout">
            <i class="power off icon"></i>
          </a>
        </div>
      </nav>
