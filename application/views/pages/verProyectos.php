<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>

<div class="headerBack" style="background: linear-gradient(90deg, #9ebd13 0%, #008552 100%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="users icon"></i>
      <h2>Ver Proyectos</h2>
    </div>
    <p class="txtBg">Ver Proyectos</p>
  </div>
</div>
<main class="page-content">
  <div class="container-fluid">
    <section class="verUsuarios">
      <!-- <div class="tiposUsuarios">
        <div class="ui message">
          <div class="ui five column grid">
            <div class="column">
              <div class="">
                <i class="large icons">
                  <i class="user icon"></i>
                  <i class="inverted corner cog icon"></i>
                </i>
                Administrador
              </div>
            </div>
            <div class="column">
              <div class="">
                <i class="large icons">
                  <i class="user icon"></i>
                  <i class="inverted corner search icon"></i>
                </i>
                Supervisor
              </div>
            </div>
            <div class="column">
              <div class="">
                <i class="large icons">
                  <i class="user icon"></i>
                  <i class="inverted corner folder icon"></i>
                </i>
                PMVA
              </div>
            </div>
            <div class="column">
              <div class="">
                <i class="large building icon"></i>
                Promovente
              </div>
            </div>
            <div class="column">
              <div class="">
                <i class="large icons">
                  <i class="user icon"></i>
                  <i class="inverted corner check circle icon"></i>
                </i>
                Supervisor General
              </div>
            </div>
          </div>
        </div>
      </div> -->
      <br>
      <table class="ui sortable celled table tablaUsuarios">
        <thead>
          <tr>
            <th class="sorted descending">Nombre</th>
            <?php if(esPromovente($tipos)==FALSE){ ?>
              <th class="">Promovente</th>
            <?php } ?>
            <th class="no-sort" style="width: 400px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <!-- INICIA FILA -->
          <?php foreach ($proyectos as $proyecto): ?>
          <tr>
            <td><?php echo $proyecto['nombreProyecto']; ?></td>
            <?php if(esPromovente($tipos)==FALSE){ ?>
              <td><?php echo $proyecto['nombre']; ?></td>
            <?php } ?>
            <td >
              <div data-value="" class="ui primary icon button btnEditar" name="button"><i class="edit icon"></i></div>
            </td>
          </tr>
          <?php endforeach; ?>
          <!-- TERMINA FILA -->
        </tbody>
        <tfoot>
          <tr>
            <th class="sorted descending">Nombre</th>
            <?php if(esPromovente($tipos)==FALSE){ ?>
              <th class="">Promovente</th>
            <?php } ?>
            <th class="">Acciones</th>
          </tr>
        </tfoot>
      </table>
    </section>
  </div>
</main>


<script type="text/javascript">
  $(document).ready(function(){

    $('.btnEditar').on('click',function(){
      var event=$(this);
      var data_id=$(this).attr("data-value");
      $(location).attr('href',"http://margam.mx/GECOA/editarUsuario/"+data_id);
    });
    $('.btnDesactivar').on('click',function(){
      var event=$(this);
      var data_id=$(this).attr("data-value");
      $(location).attr('href',"http://margam.mx/GECOA/desactivarUsuario/"+data_id);
    });

    $('.btnActivar').on('click',function(){
      var event=$(this);
      var data_id=$(this).attr("data-value");
      $(location).attr('href',"http://margam.mx/GECOA/activarUsuario/"+data_id);
    });

    $('.btnAsp').on('click',function(){
      var event=$(this);
      var data_id=$(this).attr("data-value");
      $(location).attr('href',"http://margam.mx/GECOA/hacerSupervisorGeneral/"+data_id);
    });

    $('.btnRsp').on('click',function(){
      var event=$(this);
      var data_id=$(this).attr("data-value");
      $(location).attr('href',"http://margam.mx/GECOA/quitarSupervisorGeneral/"+data_id);
    });

    $('.tablaUsuarios').tablesort();
    $('.tablaUsuarios').DataTable({
      "language":{
        "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });

    $('.btnAsp').popup({inline: true});
    $('.btnRsp').popup({inline: true});

  });
</script>
