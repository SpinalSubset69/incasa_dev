<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>


<div class="headerBack" style="background: linear-gradient(90deg, #9ebd13 0%, #008552 100%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="users icon"></i>
      <h2>Agregar Proyecto</h2>
    </div>
    <p class="txtBg">Agregar Proyecto</p>
  </div>
</div>
<main class="page-content">
  <div class="container-fluid">
    <?php if(!$esPromovente){?>
    <section class="tablaPromoventes">
      <br>
      <table id="example" class="ui sortable celled table tablaUsuarios">
        <thead>
          <tr>
            <th class="sorted descending">Nombre</th>
            <th class="">Representante Legal</th>
            <th class="">Correo electrónico</th>
            <th class="no-sort" style="width: 140px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <!-- INICIA FILA -->
          <?php foreach ($usuarios as $usuario): ?>
            <?php if($usuario['usuario']==$user) continue; ?>
          <tr>
            <td><?php echo $usuario['display']; ?></td>
            <td><?php echo $usuario['nombreRep']; ?></td>
            <td><?php echo $usuario['usuario']; ?></td>
            <td>
              <div data-name="<?php echo $usuario['nombre']; ?>" data-value="<?php echo $usuario['idEncriptado']; ?>" class="ui primary icon button btnCrear" name="button"><i class="edit icon"></i></div>
            </td>
          </tr>
          <?php endforeach; ?>
          <!-- TERMINA FILA -->
        </tbody>
        <tfoot>
          <tr>
            <th class="sorted descending">Nombre</th>
            <th class="">Representante Legal</th>
            <th class="">Correo electrónico</th>
            <th class="">Acciones</th>
          </tr>
        </tfoot>
      </table>
    </section>
  <?php } ?>
    <section class="crearProyecto" style="<?php if(!$esPromovente) echo 'display: none'; ?>">
      <h3 class="ui top grey inverted attached header">PROYECTO</h3>
      <form class="ui form attached segment agregarProyecto" action="<?php echo base_url(); ?>GECOA/agregarProyecto" method="post">
        <div class="three fields">
          <div class="required field">
            <label>Nombre del Proyecto:</label>
            <input type="text" name="txt_nombre" placeholder="Nombre del Proyecto">
          </div>
          <div class="field">
            <label>Giro:</label>
            <input type="text" name="txt_giro" value="" placeholder="Giro">
          </div>
          <?php if(!$esPromovente){ ?>
          <div class="field">
            <label>Promovente:</label>
            <input type="text" id="txt_nombrePromovente" name="txt_nombrePromovente" placeholder="Promovente" disabled>
          </div>

        <?php } ?>
        </div>
        <h4 class="ui dividing header">INFORMACIÓN GENERAL</h4>
        <input type="hidden" id="dir_alt" name="dir_alt" value="<?php echo $idEnc; ?>">
        <div class="three fields">
          <div class="field">
            <label>Calle:</label>
            <input type="text" name="txt_calle" value="" placeholder="Calle">
          </div>
          <div class="field">
            <label>Número Exterior:</label>
            <input type="text" name="txt_numExt" value="" placeholder="Número Exterior">
          </div>
          <div class="field">
            <label>Número Interior:</label>
            <input type="text" name="txt_numInt" value="" placeholder="Número Interior">
          </div>
        </div>
        <div class="four fields">
          <div class="field">
            <label>Colonia:</label>
            <input type="text" name="txt_colonia" value="" placeholder="Colonia">
          </div>
          <div class="field">
            <label>Código Postal:</label>
            <input type="text" name="txt_cp" value="" placeholder="Código Postal">
          </div>
          <div class="field">
            <label>País:</label>
            <input type="text" name="txt_pais" value="" placeholder="País">
          </div>
          <div class="field">
            <label>Estado:</label>
            <input type="text" name="txt_estado" value="" placeholder="Estado">
          </div>
        </div>
        <div class="four fields">
          <div class="field">
            <label>Ciudad:</label>
            <input type="text" name="txt_ciudad" value="" placeholder="Ciudad">
          </div>
          <div class="field">
            <label>Latitud:</label>
            <input type="text" name="txt_latitud" value="" placeholder="Latitud">
          </div>
          <div class="field">
            <label>Longitud:</label>
            <input type="text" name="txt_longitud" value="" placeholder="Longitud">
          </div>
          <div class="field">
            <label>Dimensión:</label>
            <input type="text" name="txt_dimension" value="" placeholder="Dimensión">
          </div>
        </div>
        <div class="field">
          <label>Descripción:</label>
          <textarea type="text" name="txt_descripcion" value="" placeholder="Describe brevemente el proyecto..."></textarea>
        </div>
        <div class="row" id="fechas_fields">
          <div class="two fields">
            <div class="field fechainicio">
              <label>Fecha de Inicio:</label>
              <div class="ui calendar" id="rangestart">
                <div class="ui input left icon">
                  <i class="calendar icon"></i>
                  <input type="text" name="txt_fechainicio" id="txt_fechainicio" placeholder="Inicio">
                </div>
              </div>
            </div>
            <div class="field fechafin">
              <label>Fecha de Termino:</label>
              <div class="ui calendar" id="rangeend">
                <div class="ui input left icon">
                  <i class="calendar icon"></i>
                  <input type="text" name="txt_fechafin" id="txt_fechafin" placeholder="Termino">
                </div>
              </div>
            </div>
          </div>
        </div>

        <button type="submit" class="ui positive button" value="Guardar"><i class="save icon"></i>Guardar</button>
        <button type="button" class="ui secondary right floated button btnRegresar" name="button"><i class="arrow left icon"></i> Regresar</button>
        <button type="button"  class="ui clear right floated button" name="button"><i class="eraser icon"></i> Limpiar</button>

      </form>
    </section>
  </div>
</main>

<div class="ui mini modal <?php echo $mostrarModal; ?>">
  <div class="header">¡Registro Exitoso!</div>
  <div class="image content">
    <i class="huge green check circle icon"></i>
    <div class="description">
      <p>El proyecto se ha guardado correctamente.</p>
    </div>
  </div>
  <div class="actions">
    <a href="<?php echo base_url(); ?>GECOA/" class="ui primary approve button"><i class="check icon"></i>Aceptar</a>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    if ($('.mini.modal').hasClass('mostrarModal')) {
      $('.mini.modal').modal('show');
    }

    $('.tablaUsuarios').tablesort();
    var table=$('.tablaUsuarios').DataTable({
      "language":{
        "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });

    $('.btnCrear').on('click',function(){
      var event=$(this);
      var data_id=$(this).attr("data-value");
      var data_name=$(this).attr("data-name");
      $('#txt_nombrePromovente').val(data_name);
      $('#dir_alt').val(data_id);
      $('.tablaPromoventes').css('display','none');
      $('.crearProyecto').css('display','block');
    });

    $('.btnRegresar').on('click', function(){
      <?php if(!$esPromovente){ ?>
      $('.tablaPromoventes').css('display','block');
      $('.crearProyecto').css('display','none');
      $('.agregarProyecto').form('clear');
      <?php }else{ ?>
        window.location.href = "http://margam.mx";

        <?php } ?>

    });

  });
</script>
