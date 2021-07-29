<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
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
                        <th class="sorted descending">Pedrera</th>
                        <th class="sorted descending">Placa</th>
                        <th class="sorted descending">Compañia</th>
                        <th class="sorted descending">Material</th>
                        <!-- <th class="">Planta</th> -->
                        <th class="sorted descending">Conductor</th>
                        <th class="sorted descending">Llegada</th>
                        <th class="sorted descending">Salida</th>
                        <th class="sorted descending">Tiempo (min)</th>
                        <th class="no-sort">Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- INICIA FILA -->
                    <?php foreach ($log as $lo) : ?>
                        <tr <?php if ($lo['time'] > 45) echo 'class="warning"'; ?>>
                            <td><?php echo $lo['nameQuarry']; ?></td>
                            <td><?php echo $lo['idTruck']; ?></td>
                            <td><?php echo $lo['nameCompany']; ?></td>
                            <!-- <td><?php echo $lo['nameMaterial']; ?></td> -->
                            <td><?php echo $lo['idGPS2']; ?></td>
                            <!-- <td><?php echo $lo['nameBuilding']; ?></td> -->
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
                            <td><?php echo ($lo['departure']!=NULL)?$lo['time']:$lo['time']." al momento"; ?></td>
                            <td><div data-value="<?php echo $lo['idLog']; ?>" class="ui blue icon button btnDetails" name="button"><i class="search icon"></i> </div></td>                            
                        </tr>
                    <?php endforeach; ?>
                    <!-- TERMINA FILA -->
                </tbody>
                <tfoot>
                    <tr>
                        <th class="sorted descending">Pedrera</th>
                        <th class="sorted descending">Placa</th>
                        <th class="sorted descending">Compañia</th>
                        <th class="sorted descending">Material</th>
                        <!-- <th class="">Planta</th> -->
                        <th class="sorted descending">Conductor</th>
                        <th class="sorted descending">Llegada</th>
                        <th class="sorted descending">Salida</th>
                        <th class="sorted descending">Tiempo</th>
                        <th class="sorted descending">Detalles</th>
                    </tr>
                </tfoot>
            </table>
        </section>
    </div>
</main>


<script type="text/javascript">
    $(document).ready(function() {

        var minDate = null;
        var maxDate = null;

        const yymmddUTC = str => new Date(...str.split('/').map((value, index) => index == 1 ? value-- : value));

        $('.btnDetails').on('click', function() {
            var event = $(this);
            var data_id = $(this).attr("data-value");
            $(location).attr('href', "https://incasapac.com/Pedrera/showDetails/" + data_id);
        });
        

        $('#btnLimpiar').click(function() {
            minDate = null;
            maxDate = null;
            mitabla.draw();
        });


        $('.tablaUsuarios').tablesort();
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
                console.log(date);
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
            console.log(row[5]);
            var date = row[5];
            date = date.split(' ')[0];
            date = date.replace(/-/g, '/');
            let rowDate = yymmddUTC(date);            
            return (rowDate >= minDate || minDate == null) && (rowDate <= maxDate || maxDate == null);
        });


    });
</script>