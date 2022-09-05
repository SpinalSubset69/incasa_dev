<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>

<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="truck circle icon"></i>
      <h2>Gestionar Camiones</h2>
    </div>
    <p class="txtBg">Gestionar Camiones</p>
  </div>
</div>

<main class="page-content">
  <div class="container-fluid">      
    <section class="gestionarCamiones">        
      <div class="ui horizontal divider"></div>
      <button type="button" class="ui blue floated button addBtn" id="btnAdd"><i class="add icon"></i>Nuevo</button>       
      <div class="ui horizontal divider"></div>
      <table class="ui sortable celled table trucksTable">
        <thead>
          <tr>
            <th class="sorted descending">Placa</th>
            <th class="no-sort">Tipo</th>
            <th class="no-sort">Estado</th>
            <th class="no-sort" style="width: 200px;">Acciones</th>
          </tr>
        </thead>
        <tbody>

          <?php foreach ($trucks as $truck): ?>
          <tr>
            <!-- truck plate column -->
            <td><?php echo $truck['idTruck']; ?></td>

            <!-- truck type column -->
            <td>
              <?php
                switch ( $truck['idType'] ) {
                  case 1: echo "Trailer"; break;
                  case 2: echo "Torton";  break;
                  case 3: echo "Rabón";   break;
                }
              ?>
            </td>

            <!-- truck status column -->
            <td>
              <?php
                // check if the truck has any block logs
                $logs = $blocked_trucks_log_model->getLog($truck['idTruck']);
                $logs_count = count($logs);
                $is_blocked = FALSE;

                // if the truck has no logs, then it has never been blocked
                if ($logs_count <= 0)
                  $is_blocked = FALSE;
                
                // otherwise, check the latest block log
                else {
                  $latest_log = $logs[$logs_count - 1];
                  $block_end_date = new DateTime( $latest_log['dateEnd'] );
                  $current_date = new DateTime( date('m/d/Y h:i:s') );

                  // check if the truck's block is still active
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
                <div data-value="<?php echo $truck['idTruck']; ?>"
                   name-value="<?php echo $latest_log['reason']; ?>"
                   class="ui blue icon button showReasonBtn"
                   name="button">
                <i class="search icon"></i>
              <?php } else
                echo "Activo";
              ?>
            </td>

            <!-- action buttons column: edit, block and delete -->
            <td>
              <!-- edit button !-->
              <div data-value="<?php echo $truck['idTruck']; ?>"
                   name-value="<?php echo $truck['idType']; ?>"
                   class="ui green icon button editBtn"
                   name="button">
                <i class="edit icon"></i>
              </div>
            
              <!-- block button !-->
              <div data-value="<?php echo $truck['idTruck']; ?>"
                   name-value="<?php echo $truck['idType']; ?>"
                   class="ui orange icon button blockBtn"
                   name="button">
                <i class="dont icon"></i>
              </div>

              <!-- delete button !-->
              <div data-value="<?php echo $truck['idTruck']; ?>"
                   name-value="<?php echo $truck['idType']; ?>"
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
            <th class="sorted descending">Placa</th>
            <th class="">Tipo</th>
            <th class="">Estado</th>
            <th class="">Acciones</th>
          </tr>
        </tfoot>
      </table>
    </section>
  </div>
</main>

<!-- modals -->

<!-- add truck modal -->
<div class="ui tiny modal add">
  <i class="close icon"></i>
  <div class="header">Agregar Camión</div>
  <div class="content">
    <form class="ui form add" action="<?php echo base_url(); ?>Pedrera/addTruckAdmin" method="post">
      <div class="field">
        <label>Placa:</label>
        <input type="text" name="txt_plate_add" placeholder="Placa del Camión" id="txt_plate_add" value="">
      </div>
      <div class="field">
        <label>Tipo:</label>
        <select name="dropdown_truck_type_add" id="dropdown_truck_type_add">
          <option value="1" selected>Trailer</option>
          <option value="2">Torton</option>
          <option value="3">Rabón</option>
        </select>
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

<!-- edit truck modal -->
<div class="ui tiny modal edit">
  <div class="header">Editar Camión</div>
  <div class="content">
    <form class="ui form edit" action="<?php echo base_url(); ?>Pedrera/editTruck" method="post">
      <div class="field">
        <label>Tipo:</label>
        <input type="hidden" name="txt_plate_edit" id="txt_plate_edit" value="">
        <select name="dropdown_truck_type_edit" id="dropdown_truck_type_edit">
          <option value="1" selected>Trailer</option>
          <option value="2">Torton</option>
          <option value="3">Rabón</option>
        </select>
      </div>        
    </form>
  </div>
  <div class="actions">
    <div class="ui positive button">Aceptar</div>
    <div class="ui red cancel button">Cancelar</div>
  </div>
</div>

<!-- block truck modal -->
<div class="ui tiny modal block">
  <div class="header">Bloquear Camión</div>
  <div class="content">
    <form class="ui form block" action="<?php echo base_url(); ?>Pedrera/blockTruck" method="post">
      <div class="field">
        <label>Bloquear hasta:</label>
        <input type="hidden" name="txt_plate_block" id="txt_plate_block" value="">
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

<!-- delete truck modal -->
<div class="ui mini modal del">
  <div class="header">Eliminar Camión</div>
  <div class="content">
    <p>¿Esta seguro de eliminar este camión?</p>
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

    $('.trucksTable').tablesort();
    $('.trucksTable').DataTable({
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
        var plate = $(this).attr("data-value");
        var truck_type = $(this).attr("name-value");

        $('#txt_plate_edit').val(plate);
        $('#drowpdown_truck_type_edit').val(truck_type);
        $('.ui.tiny.modal.edit').modal({
            onApprove : function() {
                $('.ui.form.edit').submit();
                return false;
            }
        }).modal('show');
    });

    $('.blockBtn').on('click', function() {
        var event = $(this);
        var plate = $(this).attr("data-value");

        $('#txt_plate_block').val(plate);
        $('.ui.tiny.modal.block').modal({
            onApprove : function() {
                $('.ui.form.block').submit();
                return false;
            }
        }).modal('show');
    });

    $('.deleteBtn').on('click', function() {
        var event = $(this);
        var plate = $(this).attr("data-value");

        $(".mini.modal.del").modal("setting", {
        closable: false,
        onApprove: function () {
            $(location).attr('href',"<?php echo base_url(); ?>Pedrera/removeTruck/" + plate);
            return false;
        }
        }).modal("show");
    });

    $('.ui.form.add').form({
        fields: {
            txt_plate_add: {
                rules: [{
                    type: 'empty',
                    prompt: 'Ingresa la placa del camión'
                }]
            },
        },
        inline: true
    });

    $('.ui.form.edit').form({
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
