<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>

<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="truck circle icon"></i>
      <h2>Gestionar Conductores</h2>
    </div>
    <p class="txtBg">Gestionar Conductores</p>
  </div>
</div>

<main class="page-content">
  <div class="container-fluid">      
    <section class="gestionarConductores">        
      <div class="ui horizontal divider"></div>
      <button type="button" class="ui blue floated button addBtn" id="btnAdd"><i class="add icon"></i>Nuevo</button>       
      <div class="ui horizontal divider"></div>
      <table class="ui sortable celled table driversTable">
        <thead>
          <tr>
            <th class="sorted descending">Conductor</th>
            <th class="no-sort">Estado</th>
            <th class="no-sort" style="width: 200px;">Acciones</th>
          </tr>
        </thead>
        <tbody>

          <?php foreach ($drivers as $driver): ?>
          <tr>
            <!-- driver name column -->
            <td><?php echo $driver['nameDriver']; ?></td>

            <!-- driver status column -->
            <td>
              <?php
                // check if the driver has any block logs
                $logs = $blocked_drivers_log_model->getLog($driver['idDriver']);
                $logs_count = count($logs);
                $is_blocked = FALSE;

                // if the driver has no logs, then they've never been blocked
                if ($logs_count <= 0)
                  $is_blocked = FALSE;
                
                // otherwise, check the latest block log
                else {
                  $latest_log = $logs[$logs_count - 1];
                  $block_end_date = new DateTime( $latest_log['dateEnd'] );
                  $current_date = new DateTime( date('m/d/Y h:i:s') );

                  // check if the driver's block is still active
                  if ( $block_end_date > $current_date )
                    $is_blocked = TRUE;
                  else
                    $is_blocked = FALSE;
                }
              ?>
              <?php if ($is_blocked) {
                echo "Bloqueado &nbsp;";
              ?>
                <!-- show blocked reason button -->
                <div data-value="<?php echo $driver['idDriver']; ?>"
                   name-value="<?php echo $latest_log['reason']; ?>"
                   class="ui blue icon button showReasonBtn"
                   name="button">
                <i class="search icon"></i>
              <?php } else
                echo "Activo";
              ?>
            </td>

            <!-- action buttons column: edit and delete -->
            <td>
              <!-- edit button !-->
              <div data-value="<?php echo $driver['idDriver']; ?>"
                   name-value="<?php echo $driver['nameDriver']; ?>"
                   class="ui green icon button editBtn"
                   name="button">
                <i class="edit icon"></i>
              </div>

              <!-- block button !-->
              <div data-value="<?php echo $driver['idDriver']; ?>"
                   name-value="<?php echo $driver['nameDriver']; ?>"
                   class="ui orange icon button blockBtn"
                   name="button">
                <i class="dont icon"></i>
              </div>

              <!-- delete button !-->
              <div data-value="<?php echo $driver['idDriver']; ?>"
                   name-value="<?php echo $driver['nameDriver']; ?>"
                   class="ui red icon button deleteBtn"
                   name="button">
                <i class="x icon"></i>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>

        </tbody>
        <tfoot>
          <tr>
            <th class="sorted descending">Driver</th>
            <th class="">Acciones</th>
          </tr>
        </tfoot>
      </table>
    </section>
  </div>
</main>

<!-- modals -->

<!-- add driver modal -->
<div class="ui tiny modal add">
  <i class="close icon"></i>
  <div class="header">Agregar Conductor</div>
  <div class="content">
    <form class="ui form add" action="<?php echo base_url(); ?>Pedrera/addDriver" method="post">
      <div class="field">
        <label>Nombre:</label>
        <input type="text" name="txt_driver_name" placeholder="Nombre del Conductor" id="txt_driver_name" value="">
      </div>
    </form>
  </div>
  <div class="actions">
    <div class="ui red deny button">Cancelar
      <i class="remove icon"></i>
    </div>
    <div class="ui positive right labeled icon button">Aceptar
      <i class="checkmark icon"></i>
    </div>
  </div>
</div>

<!-- show reason modal -->
<div class="ui mini modal reason">
  <div class="header">Razón</div>
  <div class="content">
    <div class="field">
      <p id="p_reason" class="p_reason">
        <!-- empty text -->
      </p>
    </div>        
  </div>
  <div class="actions">
    <div class="ui blue cancel button">Cerrar</div>
  </div>
</div>

<!-- edit driver modal -->
<div class="ui tiny modal edit">
  <div class="header">Editar Conductor</div>
  <div class="content">
    <form class="ui form edit" action="<?php echo base_url(); ?>Pedrera/editDriver" method="post">
      <div class="field">
        <label>Nombre:</label>
        <input type="hidden" name="txt_driver_id_edit" id="txt_driver_id_edit" value="">
        <input type="text" name="txt_driver_name" placeholder="Nombre del Conductor" id="txt_driver_name" value="">
      </div>        
    </form>
  </div>
  <div class="actions">
    <div class="ui positive button">Aceptar</div>
    <div class="ui red cancel button">Cancelar</div>
  </div>
</div>

<!-- block driver modal -->
<div class="ui tiny modal block">
  <div class="header">Bloquear Conductor</div>
  <div class="content">
    <form class="ui form block" action="<?php echo base_url(); ?>Pedrera/blockDriver" method="post">
      <div class="field">
        <label>Bloquear hasta:</label>
        <input type="hidden" name="txt_driver_id_block" id="txt_driver_id_block" value="">
        <input type="date" name="txt_end_date" id="txt_end_date" value="">
      </div>
      <div class="field">
        <label>Razón:</label>
        <input type="text" name="txt_reason" placeholder="Escriba la razón" id="txt_reason" value="">
      </div>
    </form>
  </div>
  <div class="actions">
    <div class="ui positive button">Aceptar</div>
    <div class="ui red cancel button">Cancelar</div>
  </div>
</div>

<!-- delete driver modal -->
<div class="ui mini modal del">
  <div class="header">Eliminar Conductor</div>
  <div class="content">
    <p>¿Esta seguro de eliminar este conductor?</p>
  </div>
  <div class="actions">
    <div class="ui positive button">Aceptar</div>
    <div class="ui red cancel button">Cancelar</div>
  </div>
</div>

<!-- register success modal -->
<?php if ( isset($_SESSION['success']) ) { ?>

  <div class="ui mini modal success">
  <div class="header">¡Registro Exitoso!</div>
  <div class="image content">
    <i class="huge green check circle icon"></i>
    <div class="description">
      <p><?php echo $_SESSION['success']; ?></p>
    </div>
  </div>
    <div class="actions">
      <div class="ui positive button">Aceptar</div>      
    </div>
  </div>

<?php
  }
?>

<script type="text/javascript">

$(document).ready(function() {

    <?php if ( isset($_SESSION['success']) ) { ?>
        $('.ui.mini.modal.success').modal('show');
    <?php unset($_SESSION['success']); } ?>

    $('.driversTable').tablesort();
    $('.driversTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
    });

    $('.addBtn').on('click', function() {
        $('.ui.tiny.modal.add').modal({
            onApprove : function() {
                $('.ui.form.add').submit();
                return false;
            }
        }).modal('show');
    });

    $('.showReasonBtn').on('click', function() {
        var p = document.getElementById("p_reason");
        var reason = $(this).attr("name-value");

        p.textContent = reason;

        $(".mini.modal.reason").modal("setting", {
            closable: true,
        }).modal("show");
    });

    $('.editBtn').on('click', function(){
        var event = $(this);
        var driver_id = $(this).attr("data-value");
        var driver_name = $(this).attr("name-value");

        $('#txt_driver_id_edit').val(driver_id);
        $('#txt_driver_name').val(driver_name);
        $('.ui.tiny.modal.edit').modal({
            onApprove : function() {
                $('.ui.form.edit').submit();
                return false;
            }
        }).modal('show');
    });

    $('.blockBtn').on('click', function() {
        var event = $(this);
        var driver_id = $(this).attr("data-value");

        $('#txt_driver_id_block').val(driver_id);
        $('.ui.tiny.modal.block').modal({
            onApprove : function() {
                $('.ui.form.block').submit();
                return false;
            }
        }).modal('show');
    });

    $('.deleteBtn').on('click', function() {
        var event = $(this);
        var driver_id = $(this).attr("data-value");

        $(".mini.modal.del").modal("setting", {
        closable: false,
        onApprove: function () {
            $(location).attr('href',"<?php echo base_url(); ?>Pedrera/removeDriver/" + driver_id);
            return false;
        }
        }).modal("show");
    });

    $('.ui.form.add').form({
        fields: {
            txt_driver_name: {
                rules: [{
                    type: 'empty',
                    prompt: 'Ingresa el nombre del conductor'
                }]
            },
        },
        inline: true
    });

    $('.ui.form.edit').form({
        fields: {
            txt_driver_name: {
                rules: [{
                    type: 'empty',
                    prompt: 'Ingresa el nombre del conductor'
                }]
            },
        },
        inline: true
    });

    $('.ui.form.block').form({
        fields: {
            txt_end_date: {
                rules: [{
                    type: 'empty',
                    prompt: 'Ingresar una fecha'
                }]
            },
            txt_reason: {
                rules: [{
                    type: 'empty',
                    prompt: 'Escribe la razón'
                }]
            },
        },
        inline: true
    });
});

</script>
