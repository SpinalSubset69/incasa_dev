<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>

<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="users icon"></i>
      <h2>Pedreras</h2>
    </div>
    <p class="txtBg">Pedreras</p>
  </div>
</div>
<main class="page-content">
  <div class="container-fluid">    
    <section class="verPedreras">         
      <div class="ui horizontal divider"></div>
      <table class="ui sortable celled table tablaPedreras">
        <thead>
          <tr>
            <th class="sorted descending">Nombre</th>
            <th class="">Acciones</th>
            <!-- <th class="no-sort" style="width: 400px;">Acciones</th> -->
          </tr>
        </thead>
        <tbody>
          <!-- INICIA FILA -->
          <?php foreach ($pedreras as $pedrera): ?>            
          <tr>
            <td><?php echo $pedrera['nameQuarry']; ?></td>
            
            <!-- <td>
              <?php if($usuario['activo']==1){ ?>
                <div data-value="<?php echo $usuario['idEncriptado']; ?>" class="ui red icon button btnDesactivar" name="button"><i class="dont icon"></i> </div>
              <?php }else{ ?>
                <div data-value="<?php echo $usuario['idEncriptado']; ?>" class="ui positive icon button btnActivar" name="button"><i class="check icon"></i> </div>
              <?php } ?>
            </td> -->
          </tr>
          <?php endforeach; ?>
          <!-- TERMINA FILA -->
        </tbody>
        <tfoot>
          <tr>
            <th class="sorted descending">Nombre</th>
            <th class="">Usuario</th>
            <!-- <th class="">Ticket</th> -->
            <th class="">Tipo de usuario</th>
            <!-- <th class="">Acciones</th> -->
          </tr>
        </tfoot>
      </table>
    </section>
  </div>
</main>

<script type="text/javascript">
  $(document).ready(function(){

    
    

    $('.tablaPedreras').tablesort();
    $('.tablaPedreras').DataTable({
      "language":{
        "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });


  });
</script>
