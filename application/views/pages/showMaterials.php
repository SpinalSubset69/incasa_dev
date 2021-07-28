<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>

<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="boxes icon"></i>
      <h2>Materiales</h2>
    </div>
    <p class="txtBg">Materiales</p>
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
            <!-- <th class="sorted descending">Pedrera</th> -->
            <!-- <th class="sorted descending">Sitio</th> -->
            <th class="sorted descending">Material</th>
            <!-- <th>Activo</th> -->
            <th class="no-sort" style="width: 200px;">Acciones</th>
            <!-- <th class="no-sort" style="width: 400px;">Acciones</th> -->
          </tr>
        </thead>
        <tbody>
          <!-- INICIA FILA -->
          <?php foreach ($materials as $material): ?>            
          <tr>
            <!-- <td><?php echo $material['nameQuarry']; ?></td>                         -->
            <!-- <td><?php echo $material['nameBuilding']; ?></td> -->
            <td><?php echo $material['nameMaterial']; ?></td>
            <!-- <td>                      
                <input type="checkbox" class="form-check-input" id="active" name="<?php echo $material['idMaterial']; ?>" <?php if($material['active']==1)echo "checked";?>>
            </td> -->
            <td><div data-value="<?php echo $material['idMaterial']; ?>" name-value="<?php echo $material['nameMaterial']; ?>" class="ui green icon button btnEditar" name="button"><i class="edit icon"></i> </div></td>            
          </tr>
          <?php endforeach; ?>
          <!-- TERMINA FILA -->
        </tbody>
        <tfoot>
          <tr>
            <!-- <th class="sorted descending">Pedrera</th> -->
            <!-- <th class="sorted descending">Sitio</th> -->
            <th class="sorted descending">Material</th>
            <!-- <th>Activo</th> -->
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
    Agregar Material
  </div>
  <div class="content">
    <form class="ui form add" action="<?php echo base_url(); ?>Pedrera/addMaterial" method="post">
      <!-- <div class="two fields"> -->
        <!-- <div class="field">
          <label>Planta:</label>
          <div class="ui pointing dropdown txt_Planta">
            <input type="hidden" name="txt_Planta">                        
            <div class="default text">Planta</div>            
            <i class="dropdown icon"></i>
            <div class="menu">  
              <?php foreach($quarries as $quarry): ?> 
                <?php if(count($quarry['buildings'])>0){ ?>   
                  <div class="item">         
                    <i class="dropdown icon"></i>
                    <span class="text"><?php echo $quarry['nameQuarry']; ?></span>                
                    <div class="menu">
                      <?php foreach($quarry['buildings'] as $building): ?>                        
                        <div class="item" data-value="<?php echo $building['idBuilding']; ?>"><?php echo $building['nameBuilding']; ?></div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php } ?>
              <?php endforeach; ?>
            </div>
          </div>
        </div> -->
      <div class="field">
        <label>Nombre:</label>
          <input type="text" name="txt_name" placeholder="Nombre material" id="txt_name" value="">
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

<!-- Editar modal -->
<div class="ui tiny modal edit">
  <div class="header">Editar Material</div>
  <div class="content">
    <form class="ui form edit" action="<?php echo base_url(); ?>Pedrera/editMaterial" method="post">
      <div class="field">
        <label>Nombre:</label>
        <input type="hidden" name="txt_id" id="txt_id" value="">
        <input type="text" name="txt_name_edit" placeholder="Nombre del material" id="txt_name_edit" value="">
      </div>        
    </form>
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

    

    $('.tablaSites').tablesort();
    $('.tablaSites').DataTable({
      "language":{
        "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });

    $(document).on('change', '.form-check-input', function(){
      var status=this.checked;
      var active=0;
      if(status==true)
        active = 1;
      var idMaterial = this.name;
      $.ajax({
            type: 'POST',
            url: 'https://incasapac.com/Pedrera/activateMaterial/',
            data: {idMaterial:idMaterial, active: active},
            dataType: 'json',
            success: function(response){
            },
            error: function(response){
            }
      });
    });

    $('.dropdown.txt_Planta').dropdown();
    $('.dropdown.txt_tipoSitio').dropdown();

    $('.btnEditar').on('click',function(){
      var event=$(this);
      var data_id=$(this).attr("data-value");
      var name=$(this).attr("name-value");
      $('#txt_id').val(data_id);
      $('#txt_name_edit').val(name);
      $('.ui.tiny.modal.edit').modal({			
        onApprove : function() {
          $('.ui.form.edit').submit();
          return false;
        }
		  }).modal('show');      

    });

    $('.ui.form.edit')
      .form({
        fields: {
          txt_name_edit    : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa el nombre del material'
              }
            ]
          }          
        },          
        inline: true
      });

    $('.ui.form.add')
      .form({
        fields: {
          txt_name     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa el nombre del material'
              }
            ]
          },
          txt_Planta     : {
            rules: [
              {
                type: 'empty',
                prompt: 'Ingresa la plata en la que está el material'
              }
            ]
          }
        },          
        inline: true
      });


  });
</script>
