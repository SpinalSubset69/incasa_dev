<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>

<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="users icon"></i>
      <h2>Usuarios</h2>
    </div>
    <p class="txtBg">Usuarios</p>
  </div>
</div>
<main class="page-content">
  <div class="container-fluid">    
    <section class="verUsuarios">         
      <div class="ui horizontal divider"></div>
      <table class="ui sortable celled table tablaUsuarios">
        <thead>
          <tr>
            <th class="sorted descending">Pedrera</th>
            <th class="sorted descending">Nombre</th>
            <th class="">Usuario</th>
            <th class="">Tipo de usuario</th>
            <th class="no-sort" style="width: 100px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <!-- INICIA FILA -->
          <?php foreach ($usuarios as $usuario): ?>
            <?php if($usuario['username']==$userConnected) continue; ?>
          <tr>
            <td><?php echo $usuario['nameQuarry']; ?></td>
            <td><?php echo $usuario['nameUser']; ?></td>
            <td><?php echo $usuario['username']; ?></td>
            <td>
              <?php
                switch($usuario['usertype']){
                    case 1: echo "Administrador";break;
                    case 2: echo "Vigilante";break;
                    case 3: echo "Operador Pala";break;
                    case 4: echo "Supervisor";break;
                    case 5: echo "Báscula";break;
                }
              ?>
            </td>
            <!-- <td>
              <?php if($usuario['activo']==1){ ?>
                <div data-value="<?php echo $usuario['idEncriptado']; ?>" class="ui red icon button btnDesactivar" name="button"><i class="dont icon"></i> </div>
              <?php }else{ ?>
                <div data-value="<?php echo $usuario['idEncriptado']; ?>" class="ui positive icon button btnActivar" name="button"><i class="check icon"></i> </div>
              <?php } ?>
            </td> -->
            <td>
              <!-- genearate a delete button for every user row -->
              <div data-value=<?php echo $usuario['idUser'];?>
                   name-value=<?php echo $usuario['nameUser'];?>
                   class="ui red icon button btnEliminar"
                   name="button">
                <i class="x icon"></i>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          <!-- TERMINA FILA -->
        </tbody>
        <tfoot>
          <tr>
            <th class="sorted descending">Pedrera</th>
            <th class="sorted descending">Nombre</th>
            <th class="">Usuario</th>
            <!-- <th class="">Ticket</th> -->
            <th class="">Tipo de usuario</th>
            <!-- <th class="">Acciones</th> -->
            <th class="">Acciones</th>
          </tr>
        </tfoot>
      </table>
    </section>
  </div>
</main>

<!-- Eliminar modal -->
<div class="ui mini modal del">
  <div class="header">Eliminar Usuario</div>
  <div class="content">
    <p>¿Esta seguro de eliminar este usuario?</p>
  </div>
  <div class="actions">
    <div class="ui positive button">Aceptar</div>
    <div class="ui red cancel button">Cancelar</div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.tablaUsuarios').tablesort();
    $('.tablaUsuarios').DataTable({
      "language":{
        "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });

    $('.btnEliminar').on('click', function() {
      var event = $(this);
      var user_id = $(this).attr("data-value");

      $(".mini.modal.del").modal("setting", {
        closable: false,
        onApprove: function () {
          $(location).attr('href',"<?php echo base_url(); ?>Pedrera/removeUser/" + user_id);
          return false;
        }
      }).modal("show");
    });

  });
</script>
