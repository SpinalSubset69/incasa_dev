<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>

<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="building icon"></i>
      <h2>Pedreras</h2>
    </div>
    <p class="txtBg">Pedreras</p>
  </div>
</div>
<main class="page-content">
  <div class="container-fluid">      
    <section class="verPedreras">        
      <div class="ui horizontal divider"></div>
      <button type="button"  class="ui blue floated button" id="btnAdd"><i class="add icon"></i> Nuevo</button>       
      <div class="ui horizontal divider"></div>
      <table class="ui sortable celled table tablaPedreras">
        <thead>
          <tr>
            <th class="sorted descending">Nombre</th>
            <th class="sorted descending">Dentro de Pedrera</th>
            <th class="sorted descending">Atendidos</th>
            <th class="sorted descending">Total</th>
            <th class="no-sort" style="width: 200px;">Acciones</th>
            <!-- <th class="no-sort" style="width: 400px;">Acciones</th> -->
          </tr>
        </thead>
        <tbody>
          <!-- INICIA FILA -->
          <?php foreach ($pedreras as $pedrera): ?>            
          <tr>
            <td><?php echo $pedrera['nameQuarry']; ?></td>
            <td><?php echo $pedrera['dentro']; ?></td>
            <td><?php echo $pedrera['atendidos']; ?></td>
            <td><?php echo $pedrera['dentro']+$pedrera['atendidos']; ?></td>
            <td><div data-value="<?php echo $pedrera['idQuarry']; ?>" class="ui red icon button btnEliminar" name="button"><i class="remove icon"></i> </div></td>            
          </tr>
          <?php endforeach; ?>
          <!-- TERMINA FILA -->
        </tbody>
        <tfoot>
          <tr>
            <th class="sorted descending">Nombre</th>
            <th class="sorted descending">Dentro de Pedrera</th>
            <th class="sorted descending">Atendidos</th>
            <th class="sorted descending">Total</th>
            <th class="">Acciones</th>
          </tr>
        </tfoot>
      </table>
    </section>
  </div>
</main>

<!-- Agregar modal -->
<div class="ui tiny modal add">
  <i class="close icon"></i>
  <div class="header">
    Agregar Pedrera
  </div>
  <div class="content">
    <form class="ui form add" action="<?php echo base_url(); ?>Pedrera/addQuarry" method="post">
      <div class="field">
        <label>Nombre:</label>
          <input type="text" name="txt_name" placeholder="Nombre pedrera" id="txt_name" value="">
        </div>        
    </form>
  </div>
  <div class="actions">
    <div class="ui red deny button">
      <i class="remove icon"></i> Cancelar
    </div>
    <div class="ui positive right labeled icon button">
      Aceptar
      <i class="checkmark icon"></i>
    </div>
  </div>
</div>

<!-- Eliminar modal -->
<div class="ui mini modal del">
  <div class="header">Eliminar Pedrera</div>
  <div class="content">
    <p>??Esta seguro de eliminar esta pedrera?</p>
  </div>
  <div class="actions">
    <div class="ui positive button">Aceptar</div>
    <div class="ui red cancel button">Cancelar</div>
  </div>
</div>

<!-- Registro exitoso modal -->
<?php if(isset($_SESSION['success'])){ ?>  
  <div class="ui mini modal success">
    <div class="header">??Registro Exitoso!</div>
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
  $(document).ready(function(){

    <?php if(isset($_SESSION['success'])){?>
      $('.ui.mini.modal.success').modal('show');
    <?php unset($_SESSION['success']); }?>

    $('#btnAdd').on('click', function(){
      $('.ui.tiny.modal.add').modal({			
        onApprove : function() {
          $('.ui.form.add').submit();
          return false;
        }
		  }).modal('show');
    });
    

    $('.tablaPedreras').tablesort();
    $('.tablaPedreras').DataTable({
      "language":{
        "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });

    $('.btnEliminar').on('click',function(){
      var event=$(this);
      var data_id=$(this).attr("data-value");
      $(".mini.modal.del").modal("setting", {
        closable: false,
        onApprove: function () {
          $(location).attr('href', "<?php echo base_url(); ?>Pedrera/removeQuarry/" + data_id);
          return false;
        }
      }).modal("show");

    });

    $('.ui.form.add')
      .form({
        fields: {
          txt_name     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa el nombre completo de la pedrera'
              }
            ]
          }
        },          
        inline: true
      });


  });
</script>
