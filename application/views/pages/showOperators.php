<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>

<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="truck icon"></i>
      <h2>Operadores</h2>
    </div>
    <p class="txtBg">Operadores</p>
  </div>
</div>
<main class="page-content">
  <div class="container-fluid">      
    <section class="verPedreras">        
      <div class="ui horizontal divider"></div>      
      <div class="ui horizontal divider"></div>
      <table class="ui sortable celled table tablaSites">
        <thead>
          <tr>
            <th class="sorted descending">Usuario</th>
            <th class="sorted descending">Nombre</th>
            <th class="sorted descending">GPS</th>
            <th>Plantas asignadas</th>
            <!-- <th class="no-sort" style="width: 200px;">Acciones</th> -->
            <!-- <th class="no-sort" style="width: 400px;">Acciones</th> -->
          </tr>
        </thead>
        <tbody>
          <!-- INICIA FILA -->
          <?php foreach ($operators as $operator): ?>            
          <tr>
            <td><?php echo $operator['username']; ?></td>                        
            <td><?php echo $operator['nameUser']; ?></td>
            <td>
              <select name="gps" class="ui fluid search selection dropdown gps">
                <option value="">GPS</option>
                  <?php foreach($operator['gps'] as $gp): ?>
                    <option operator-value="<?php echo $operator['idUser']; ?>" value="<?php echo $gp['idGPS']; ?>" <?php if ($operator['idGPS']==$gp['idGPS']) echo "selected"; ?>><?php echo $gp['idGPS']; ?></option>                  
                  <?php endforeach; ?>
              </select>
            </td>
            <td> 
              <?php if(count($operator['buildings'])>0){ ?>
                <select name="plantas" multiple="" class="ui fluid dropdown">
                  <option value="">Plantas</option>
                  <?php foreach($operator['buildings'] as $building): ?>                                    
                    <option operator-value="<?php echo $operator['idUser']; ?>" value="<?php echo $building['idBuilding']; ?>" <?php if ($building['active']>0) echo "selected"; ?>><?php echo $building['nameBuilding']; ?></option>
                  <?php endforeach; ?>                  
                  <!-- <option value="angular">Angular</option>                  
                  <option value="design">Graphic Design</option>
                  <option value="ember">Ember</option>
                  <option value="html">HTML</option> -->
                </select>                     
              <?php } ?>
            </td>
          </tr>
          <?php endforeach; ?>
          <!-- TERMINA FILA -->
        </tbody>
        <tfoot>
          <tr>
            <th class="sorted descending">Usuario</th>
            <th class="sorted descending">Nombre</th>
            <th class="sorted descending">GPS</th>
            <th>Plantas asignadas</th>
            <!-- <th class="sorted descending">Material</th>
            <th>Activo</th>
            <th class="">Acciones</th> -->
          </tr>
        </tfoot>
      </table>
    </section>
  </div>
</main>



<script type="text/javascript">
  $(document).ready(function(){
    
    $('.tablaSites').tablesort();
    $('.tablaSites').DataTable({
      "language":{
        "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });
    

    $('.ui.fluid.dropdown').dropdown();    

    $('.ui.fluid.dropdown.gps').dropdown('setting', 'onChange', 
      function(val, text, choice){
        var idOperator = $(this).children('option[value=' + val + ']').attr('operator-value');        
        console.log(idOperator);
        console.log(val);
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>Pedrera/addGPSOperator/',
            data: {idGPS:val, idOperator: idOperator},
            dataType: 'json',
            success: function(response){

            },
            error: function(response){
            }
        });
    });
    
    $('.ui.fluid.dropdown').dropdown('setting','onAdd',
      function(val,text,choice) {
        var idOperator = $(this).children('option[value=' + val + ']').attr('operator-value');        
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>Pedrera/addPlantOperator/',
            data: {idBuilding:val, idOperator: idOperator},
            dataType: 'json',
            success: function(response){

            },
            error: function(response){
            }
        });
    });

    $('.ui.fluid.dropdown').dropdown('setting','onRemove',
      function(val,text,choice) {
        var idOperator = $(this).children('option[value=' + val + ']').attr('operator-value');
        
        $.ajax({
            type: 'POST',
            url: 'https://incasapac.com/Pedrera/removePlantOperator/',
            data: {idBuilding:val, idOperator: idOperator},
            dataType: 'json',
            success: function(response){

            },
            error: function(response){
            }
        });
    });


  });    
</script>
