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
                        <div class="column">
                            <div class="field">
                                <label><br></label>
                                <button type="button" class="ui fluid blue button" id="btnDescargar" name="button"><i class="download icon"></i> Descargar</button>
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
                        <th class="sorted">Material</th>
                        <th class="sorted">Mica</th>
                        <th class="sorted">Conductor</th>
                        <th class="sorted default-sort">Llegada</th>
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
                            <td><?php echo $lo['idGPS2']; ?></td>
                            <td><?php echo $lo['nameMaterial']; ?></td>
                            <td><?php echo $lo['mica']; ?></td>
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
                                    //echo unix_to_human($dateu); 
                                    $date = date_create(unix_to_human($dateu));
                                    echo date_format($date, 'Y-m-d H:i');
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
                                        //echo unix_to_human($dateu);
                                        $date = date_create(unix_to_human($dateu));
                                        echo date_format($date, 'Y-m-d H:i');
                                    }else{
                                        echo "No ha salido";
                                    }
                                    #echo ($lo['departure']!=NULL)?$lo['departure']:"No ha salido";
                                ?>
                            </td>
                            <td><?php echo $lo['time']; ?></td>
                            <td><div data-value="<?php echo $lo['idLog']; ?>" class="ui blue icon button btnDetails" name="button"><i class="search icon"></i> </div></td>                            
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
                        <th class="sorted">Material</th>
                        <th class="sorted">Mica</th>
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
  <div class="description" id="description">
  
  </div>
  <!-- <div class="actions">
    <div class="ui black deny button">
      Nope
    </div>
    <div class="ui positive right labeled icon button">
      Yep, that's me
      <i class="checkmark icon"></i>
    </div>
  </div> -->
</div>


<script type="text/javascript">
    $(document).ready(function() {

        var minDate = null;
        var maxDate = null;

        const yymmddUTC = str => new Date(...str.split('/').map((value, index) => index == 1 ? value-1 : value));        

        $('.btnDetails').on('click', function() {
            var event = $(this);
            var data_id = $(this).attr("data-value");
            document.cookie = 'id_log='+data_id;
            //$(location).attr('href', "https://incasapac.com/Pedrera/showDetails/" + data_id);
            //window.open("https://incasapac.com/Pedrera/showDetails/" + data_id, '_blank');
            $('#description').load("https://incasapac.com/Pedrera/showDetails/" + data_id, function() {
                 //$('#bootstrap-modal').modal({show:true});
                 $('.ui.modal').modal('show');
            });
            
        });

        $('#btnDescargar').click(function() {   
            f1 = null;
            f2 = null;
            if(minDate!=null && maxDate!=null){
                minDate.setTime(minDate.getTime() - 7 * 60 * 60 * 1000);
                maxDate.setTime(maxDate.getTime() + 21 * 60 * 60 * 1000);

                f1 = minDate.toISOString().slice(2, 19).replace('T', ' ');
                f2 = maxDate.toISOString().slice(2, 19).replace('T', ' ');
                //console.log(minDate.toISOString().slice(0, 10));
                //console.log(maxDate.toISOString().slice(0, 10));
                /*day1 = minDate.toISOString().slice(0, 2);
                day2 = minDate.toISOString().slice(0, 2);
                day1 = parseInt(day1)-1;
                day2 = parseInt(day2)+1;
                f1 = day1.toString()+f1 + " 22:00:00";
                f2 = day1.toString()+f2 + " 02:00:00";*/
                //minDate.setTime(minDate.getTime() - 2 * 60 * 60 * 1000);
                //maxDate.setTime(maxDate.getTime() - 2 * 60 * 60 * 1000);
                console.log(f1);
                console.log(f2);
            }
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>Pedrera/downloadReport',
                data: {minDate:f1, maxDate:f2},
                dataType: "json",                
                success: function(response) {
                    var win = window.open("", "_blank");
                    win.location.href = response.file;
                },
                error: function (jqXHR, exception) {
                    console.log(jqXHR);
                    console.log(exception);
                    console.log(jqXHR.responseText);
                }
            });
        });
        

        $('#btnLimpiar').click(function() {
            minDate = null;
            maxDate = null;
            mitabla.draw();
        });

        

        var mitabla = $('.tablaUsuarios').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            order: [[7, 'desc']],         
        });   
        
        //$('.tablaUsuarios').tablesort();        
        //var tablesort = $('.tablaUsuarios').data('tablesort'); 
        /*tablesort.sort($("th.default-sort"));
        tablesort.sort($("th.default-sort"));*/

        $('thead th.tiempo').data(
        'sortBy', 
            function(th, td, tablesort) {
                return parseInt(td.text());
            }
        );

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
            var date = row[7];
            date = date.split(' ')[0];
            date = date.replace(/-/g, '/');
            let rowDate = yymmddUTC(date);              
            return (rowDate >= minDate || minDate == null) && (rowDate <= maxDate || maxDate == null);
        });


    });
</script>