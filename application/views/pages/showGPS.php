<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>

<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="map marker alternate icon"></i>
      <h2>GPS</h2>
    </div>
    <p class="txtBg">GPS</p>
  </div>
</div>
<main class="page-content">
  <div class="container-fluid">      
    <section class="verPedreras">        
      <div class="ui horizontal divider"></div>
      <button type="button"  class="ui blue floated button" id="btnAdd"><i class="add icon"></i> Nuevo</button>       
      <div class="ui horizontal divider"></div>
      <table class="ui sortable celled table tablaSites">
        <thead>
          <tr>
            <th class="sorted descending">GPS</th>
            <th class="no-sort">Acciones</th>
            <!-- <th class="no-sort" style="width: 400px;">Acciones</th> -->
          </tr>
        </thead>
        <tbody>
          <!-- INICIA FILA -->
          <?php foreach ($gps as $gp): ?>            
          <tr>
            <td><?php echo $gp['idGPS']; ?></td>            
            <td><div data-value="<?php echo $gp['idGPS']; ?>" class="ui red icon button btnEliminar" name="button"><i class="remove icon"></i> </div></td>            
          </tr>
          <?php endforeach; ?>
          <!-- TERMINA FILA -->
        </tbody>
        <tfoot>
          <tr>
            <th class="sorted descending">GPS</th>
            <!-- <th class="">Ticket</th> -->
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
    Agregar GPS
  </div>
  <div class="content">
    <form class="ui form add" action="<?php echo base_url(); ?>Pedrera/addGPS" method="post">            
      <div class="field">
        <label>Identificador GPS:</label>
          <input type="text" name="txt_gps" placeholder="Identificador GPS" id="txt_gps" value="">
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
  <div class="header">Eliminar GPS</div>
  <div class="content">
    <p>¿Esta seguro de eliminar este GPS?</p>
  </div>
  <div class="actions">
    <div class="ui positive button">Aceptar</div>
    <div class="ui red cancel button">Cancelar</div>
  </div>
</div>

<!-- Registro exitoso modal -->
<?php if(isset($_SESSION['success'])){ ?>  
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

<!-- Registro exitoso modal -->
<?php if(isset($_SESSION['error'])){ ?>  
  <div class="ui mini modal error">
    <div class="header">¡Error!</div>
    <div class="image content">
      <i class="huge red times circle icon"></i>
      <div class="description">
        <p><?php echo $_SESSION['error']; ?></p>
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

    <?php if(isset($_SESSION['error'])){?>
      $('.ui.mini.modal.error').modal('show');
    <?php unset($_SESSION['error']); }?>

    $('#btnAdd').on('click', function(){
      $('.ui.tiny.modal.add').modal({			
        onApprove : function() {
          $('.ui.form.add').submit();
          return false;
        }
		  }).modal('show');
    });
    

    $('.tablaSites').tablesort();
    $('.tablaSites').DataTable({
      "language":{
        "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });

    $('.dropdown.txt_Pedrera').dropdown();
    $('.dropdown.txt_tipoSitio').dropdown();

    $('.btnEliminar').on('click',function(){
      var event=$(this);
      var data_id=$(this).attr("data-value");
      $(".mini.modal.del").modal("setting", {
        closable: false,
        onApprove: function () {
          $(location).attr('href',"http://lapedrera.margam.mx/Pedrera/removeGPS/"+data_id);
          return false;
        }
      }).modal("show");

    });

    $('.ui.form.add')
      .form({
        fields: {
          txt_gps     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa el identificador del GPS'
              }
            ]
          }
        },          
        inline: true
      });


  });
</script>
