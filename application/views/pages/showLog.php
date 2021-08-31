<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/jquery.metadata.js"></script> -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>

<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
    <div class="page-content miHeader">
        <div class="miTitulo">
            <i class="history icon"></i>
            <h2>Historial</h2>
        </div>
        <p class="txtBg">Historial</p>
    </div>
</div>



<main class="page-content">
    <div class="container-fluid">
        <section class="verUsuarios">
            <div class="tiposUsuarios">
                <form class="ui form attached segment agregarPromovente" action="<?php echo base_url(); ?>GECOA/agregarPromovente" method="post">
                    <div class="ui equal width grid stackable">
                        <div class="column">
                            <div class="field fechainicio">
                                <label>Fecha de Inicio:</label>
                                <div class="ui calendar" id="rangestart">
                                    <div class="ui input left icon">
                                        <i class="calendar icon"></i>
                                        <input readonly='true' type="text" class="datepicker" name="txt_fechainicio" id="txt_fechainicio" placeholder="Inicio">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field fechafin">
                                <label>Fecha de Termino:</label>
                                <div class="ui calendar" id="rangeend">
                                    <div class="ui input left icon">
                                        <i class="calendar icon"></i>
                                        <input readonly='true' type="text" class="datepicker" name="txt_fechafin" id="txt_fechafin" placeholder="Termino">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label><br></label>
                                <button type="button" class="ui fluid button" id="btnLimpiar" name="button"><i class="eraser icon"></i> Limpiar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <br>
            <table class="ui sortable celled table tablaUsuarios">
                <thead>
                    <tr>
                        <th class="sorted">Pedrera</th>
                        <th class="sorted">Placa</th>
                        <th class="sorted">Compañia</th>
                        <th class="sorted">GPS</th>
                        <!-- <th class="">Planta</th> -->
                        <th class="sorted">Conductor</th>
                        <th class="sorted">Llegada</th>
                        <th class="sorted">Salida</th>
                        <th class="tiempo" class="sorted">Tiempo (min)</th>
                        <th class="no-sort">Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- INICIA FILA -->
                    <?php $i=0; ?>
                    <?php foreach ($log as $lo) : ?>
                        <tr <?php if ($lo['departure']!=NULL){ if ($lo['time'] >= 45) echo 'class="warning"'; } else {echo 'class="positive"';} ?>>
                            <td><?php echo $lo['nameQuarry']; ?></td>
                            <td><?php echo $lo['idTruck']; ?></td>
                            <td><?php echo $lo['nameCompany']; ?></td>
                            <!-- <td><?php echo $lo['nameMaterial']; ?></td> -->
                            <td><?php echo $lo['idGPS2']; ?></td>
                            
                            <!-- En horario de verano debo de adelantar dos horas; si no una hora -->
                            <td><?php echo $lo['nameDriver']; ?></td>
                            <td>
                                <?php 
                                    date_default_timezone_set('America/Monterrey');
                                    $dateu = mysql_to_unix($lo['arrival']);
                                    if(date('I')==1) {
                                        $dateu = gmt_to_local($dateu, "UP2", FALSE);
                                    }else
                                        $dateu = gmt_to_local($dateu, "UP1", FALSE);
                                    echo unix_to_human($dateu); 
                                ?>
                            </td>
                            <td>
                                <?php 
                                    if($lo['departure']!=NULL){
                                        date_default_timezone_set('America/Monterrey');
                                        $dateu = mysql_to_unix($lo['departure']);
                                        if(date('I')==1) {
                                            $dateu = gmt_to_local($dateu, "UP2", FALSE);
                                        }else
                                            $dateu = gmt_to_local($dateu, "UP1", FALSE);
                                        echo unix_to_human($dateu);
                                    }else{
                                        echo "No ha salido";
                                    }
                                    #echo ($lo['departure']!=NULL)?$lo['departure']:"No ha salido";
                                ?>
                            </td>
                            <td><?php echo $lo['time']; ?></td>
                            <td><div data-value="<?php echo $i; ?>" class="ui blue icon button btnDetails" name="button"><i class="search icon"></i> </div></td>                            
                        </tr>
                        <?php $i=$i+1; ?>
                    <?php endforeach; ?>                    
                    <!-- TERMINA FILA -->
                </tbody>
                <tfoot>
                    <tr>
                        <th class="sorted">Pedrera</th>
                        <th class="sorted">Placa</th>
                        <th class="sorted">Compañia</th>
                        <th class="sorted">GPS</th>
                        <!-- <th class="">Planta</th> -->
                        <th class="sorted">Conductor</th>
                        <th class="sorted">Llegada</th>
                        <th class="sorted">Salida</th>
                        <th class="sorted">Tiempo</th>
                        <th class="sorted">Detalles</th>
                    </tr>
                </tfoot>
            </table>
        </section>
    </div>
</main>

<div class="ui modal">
  <i class="close icon"></i>
  <div class="header">
      Detalles
  </div>
  <div class="description">
  <main class="page-content">
    <div class="container-fluid">
        <section class="verUsuarios">
            <div class="tiposUsuarios">
                <form class="ui form attached segment agregarPromovente" action="<?php echo base_url(); ?>GECOA/agregarPromovente" method="post">
                    <div class="ui equal width grid stackable">
                        <div class="column">
                            <div class="field">
                                <label><?php echo $this->session->userdata('id'); ?>Placa:</label>
                                <input disabled type="text" value="<?php echo $log[0]['log2']->idTruck; ?>">
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label>GPS:</label>
                                <input disabled type="text" value="<?php echo $log->idGPS2; ?>">
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label>Conductor:</label>
                                <input disabled type="text" value="<?php echo $log->nameDriver; ?>">
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label>Compañia fletera:</label>
                                <input disabled type="text" value="<?php echo $log->nameCompany; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="ui equal width grid stackable">
                        <div class="column">
                            <div class="field">
                                <label>Fecha:</label>
                                <input disabled type="text" value="<?php echo explode(" ",$log->arrival)[0]; ?>">
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label>Hora de llegada:</label>
                                <input disabled type="text" value="<?php 
                                    date_default_timezone_set('America/Monterrey');
                                    $dateu = mysql_to_unix($log->arrival);
                                    if(date('I')==1) {
                                        $dateu = gmt_to_local($dateu, "UP2", FALSE);
                                    }else
                                        $dateu = gmt_to_local($dateu, "UP1", FALSE);
                                    $dateu = unix_to_human($dateu); 
                                    echo explode(" ",$dateu)[1]." ".explode(" ",$dateu)[2]; 
                                ?>">
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label>Hora de salida:</label>
                                <input disabled type="text" value="<?php 
                                    if($log->departure!=NULL){
                                        date_default_timezone_set('America/Monterrey');
                                        $dateu = mysql_to_unix($log->departure);
                                        if(date('I')==1) {
                                            $dateu = gmt_to_local($dateu, "UP2", FALSE);
                                        }else
                                            $dateu = gmt_to_local($dateu, "UP1", FALSE);
                                        $dateu = unix_to_human($dateu); 
                                        echo explode(" ",$dateu)[1]." ".explode(" ",$dateu)[2];
                                    }else
                                        echo "No ha salido"; 
                                ?>">
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label><?php echo ($log->departure!=NULL)?"Tiempo total":"Tiempo al momento"; ?></label>
                                <input disabled type="text" value="<?php echo $log->time; ?> minutos">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <br>
            <table class="ui sortable celled table tablaUsuarios">
                <thead>
                    <tr>
                        <th class="sorted descending"></th>
                        <th class="">Descripción</th>
                        <th class="">Hora</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- INICIA FILA -->
                    <?php $i=$total; ?>
                    <?php foreach ($incidents as $incident) : ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $incident['description']; ?></td>
                            <td><?php 
                                date_default_timezone_set('America/Monterrey');
                                $dateu = mysql_to_unix($incident['date']);
                                if(date('I')==1) {
                                    $dateu = gmt_to_local($dateu, "UP2", FALSE);
                                }else
                                    $dateu = gmt_to_local($dateu, "UP1", FALSE);
                                $dateu = unix_to_human($dateu); 
                                echo explode(" ",$dateu)[1]." ".explode(" ",$dateu)[2];
                                //echo explode(" ",$incident['date'])[1]; 
                            ?></td>
                        </tr>
                    <?php $i=$i-1; ?>
                    <?php endforeach; ?>
                    <!-- TERMINA FILA -->
                </tbody>
                <tfoot>
                    <tr>
                        <th class="sorted descending"></th>
                        <th class="">Descripción</th>
                        <th class="">Hora</th>
                    </tr>
                </tfoot>
            </table>
        </section>
    </div>
</main>
  </div>
  <div class="actions">
    <div class="ui black deny button">
      Nope
    </div>
    <div class="ui positive right labeled icon button">
      Yep, that's me
      <i class="checkmark icon"></i>
    </div>
  </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        var minDate = null;
        var maxDate = null;

        const yymmddUTC = str => new Date(...str.split('/').map((value, index) => index == 1 ? value-- : value));

        $('.btnDetails').on('click', function() {
            var event = $(this);
            var data_id = $(this).attr("data-value");
            document.cookie = "id="+data_id;
            //$(location).attr('href', "https://incasapac.com/Pedrera/showDetails/" + data_id);
            //window.open("https://incasapac.com/Pedrera/showDetails/" + data_id, '_blank');
            $('.ui.modal').modal('show');
        });
        

        $('#btnLimpiar').click(function() {
            minDate = null;
            maxDate = null;
            mitabla.draw();
        });

        $('.tablaUsuarios').tablesort();

        $('thead th.tiempo').data(
        'sortBy', 
        function(th, td, tablesort) {
            return parseInt(td.text());
        }
    );

        var mitabla = $('.tablaUsuarios').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }            
        });        

        $('#rangestart').calendar({
            type: 'date',
            text: {
                days: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
            },
            formatter: {
                date: function(date, settings) {
                    if (!date) return '';
                    var day = date.getDate() + '';
                    if (day.length < 2) {
                        day = '0' + day;
                    }
                    var month = (date.getMonth() + 1) + '';
                    if (month.length < 2) {
                        month = '0' + month;
                    }
                    var year = date.getFullYear();
                    return year + '-' + month + '-' + day;
                }
            },
            onSelect: function(date) {
                if (!date) return '';
                var day = date.getDate() + '';
                if (day.length < 2) {
                    day = '0' + day;
                }
                var month = (date.getMonth() + 1) + '';
                if (month.length < 2) {
                    month = '0' + month;
                }
                var year = date.getFullYear();

                minDate = yymmddUTC(year + "/" + month + "/" + day);
                mitabla.draw();
            },
            endCalendar: $('#rangeend')
        });
        $('#rangeend').calendar({
            type: 'date',
            text: {
                days: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
            },
            formatter: {
                date: function(date, settings) {
                    if (!date) return '';
                    var day = date.getDate() + '';
                    if (day.length < 2) {
                        day = '0' + day;
                    }
                    var month = (date.getMonth() + 1) + '';
                    if (month.length < 2) {
                        month = '0' + month;
                    }
                    var year = date.getFullYear();
                    return year + '-' + month + '-' + day;
                }
            },
            onSelect: function(date) {            
                if (!date) return '';
                var day = date.getDate() + '';
                if (day.length < 2) {
                    day = '0' + day;
                }
                var month = (date.getMonth() + 1) + '';
                if (month.length < 2) {
                    month = '0' + month;
                }
                var year = date.getFullYear();

                maxDate = yymmddUTC(year + "/" + month + "/" + day);
                mitabla.draw();
            },
            startCalendar: $('#rangestart')
        });


        $.fn.DataTable.ext.search.push((settings, row) => {
            var date = row[5];
            date = date.split(' ')[0];
            date = date.replace(/-/g, '/');
            let rowDate = yymmddUTC(date);            
            return (rowDate >= minDate || minDate == null) && (rowDate <= maxDate || maxDate == null);
        });


    });
</script>