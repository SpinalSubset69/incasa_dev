<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>

<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="warehouse icon"></i>
      <h2>Plantas</h2>
    </div>
    <p class="txtBg">Plantas</p>
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
            <th class="sorted descending">Pedrera</th>
            <th class="sorted descending">Planta</th>
            <th>Plantas asignados</th>
            <!-- <th class="no-sort" style="width: 200px;">Acciones</th> -->
            <!-- <th class="no-sort" style="width: 400px;">Acciones</th> -->
          </tr>
        </thead>
        <tbody>
          <!-- INICIA FILA -->
          <?php foreach ($buildings as $building): ?>            
          <tr>
            <td><?php echo $building['nameQuarry']; ?></td>                        
            <td><?php echo $building['nameBuilding']; ?></td>
            <td> 
              <?php if(count($building['materials'])>0){ ?>
                <select name="materials" multiple="" class="ui fluid dropdown">
                  <option value="">Materiales</option>
                  <?php foreach($building['materials'] as $material): ?>                                    
                    <option building-value="<?php echo $building['idBuilding']; ?>" value="<?php echo $material['idMaterial']; ?>" <?php if ($material['active']>0) echo "selected"; ?>><?php echo $material['nameMaterial']; ?></option>
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
            <th class="sorted descending">Pedrera</th>
            <th class="sorted descending">Planta</th>
            <th>Materiales asignados</th>
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
    
    $('.ui.fluid.dropdown').dropdown('setting','onAdd',
      function(val,text,choice) {
        var idBuilding = $(this).children('option[value=' + val + ']').attr('building-value');
        console.log(val);
        console.log(idBuilding);
        $.ajax({
            type: 'POST',
            url: 'https://incasapac.com/Pedrera/addMaterialPlant/',
            data: {idBuilding:idBuilding, idMaterial: val},
            dataType: 'json',
            success: function(response){
              console.log("Exito!");
              console.log(response);
            },
            error: function(response){
              console.log("Error");
              console.log(response);
            }
        });
    });

    $('.ui.fluid.dropdown').dropdown('setting','onRemove',
      function(val,text,choice) {
        var idBuilding = $(this).children('option[value=' + val + ']').attr('building-value');
        
        $.ajax({
            type: 'POST',
            url: 'https://incasapac.com/Pedrera/removeMaterialPlant/',
            data: {idBuilding:idBuilding, idMaterial: val},
            dataType: 'json',
            success: function(response){

            },
            error: function(response){
            }
        });
    });


  });    
</script>
