<script type="text/javascript" src="<?php echo base_url(); ?>assets2/semantic/components/tablesort.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>

<!-- <div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
    <div class="page-content miHeader">
        <div class="miTitulo">
            <i class="users icon"></i>
            <h2>Detalles</h2>
        </div>
        <p class="txtBg">Detalles</p>
    </div>
</div> -->



<main class="page-content">
    <div class="container-fluid">
        <section class="verUsuarios">
            <div class="tiposUsuarios">
                <form class="ui form attached segment agregarPromovente" action="<?php echo base_url(); ?>GECOA/agregarPromovente" method="post">
                    <div class="ui equal width grid stackable">
                        <div class="column">
                            <div class="field">
                                <label>Placa:</label>
                                <input disabled type="text" value="<?php echo $log->idTruck; ?>">
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
                    <div class="ui equal width grid stackable">
                        <div class="column">
                            <div class="field">
                                <label>Remisión:</label>
                                <input disabled type="text" value="<?php echo ($log->remision!=NULL)?$log->remision:"No disponible"; ?>">
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label>Observaciones:</label>
                                <button type="button" class="ui fluid blue button btnObservaciones" id="btnObservaciones" name="button"
                                    <?php echo ($log->observaciones!=NULL)?"":" disabled";?> > 
                                    <?php echo ($log->observaciones!=NULL)?"Mostrar observaciones":"No hay observaciones";?>
                                </button> <!-- TODO -->
                            </div>
                        </div>
                        <div class="column">
                            <!-- Empty column used to align the fields -->
                        </div>
                        <div class="column">
                            <!-- Empty column used to align the fields -->
                        </div>
                    </div>
                </form>
            </div>
            <br>
            <div id="observationsField" class="observationsField" style="display:none">
                <h4>Observaciones</h4>
                <div class="ui segment attached">
                    <p><?php echo ($log->observaciones!=NULL)?$log->observaciones:"" ?></p>
                </div>
            </div>
            <table class="ui sortable celled table tablaUsuarios">
                <thead>
                    <tr>
                        <th class="sorted descending"></th>
                        <th class="">Descripción</th>
                        <th class="">Hora</th>
                        <th class="">Tiempo</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- INICIA FILA -->
                    <?php $i=0; $j=0;?>
                    <?php foreach ($incidents as $incident) : ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
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
                            ?>
                            </td>
                            <td><?php 
                                    if($i>0){
                                        $start_date = new DateTime($incidents[$i-1]['date']);
                                        $since_start = $start_date->diff(new DateTime($incidents[$i]['date']));
                                        //$minutes = $since_start->days * 24 * 60;
                                        $minutes = $since_start->h * 60;
                                        $minutes += $since_start->i;
                                        //echo $incidents[$j]['date']." ";
                                        //echo $incidents[$j+1]['date']." ";
                                        if($minutes != 0)
                                            echo $minutes;
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php $i=$i+1; $j=$j+1;?>
                    <?php endforeach; ?>
                    <!-- TERMINA FILA -->
                </tbody>
                <tfoot>
                    <tr>
                        <th class="sorted descending"></th>
                        <th class="">Descripción</th>
                        <th class="">Hora</th>
                        <th class="">Tiempo</th>
                    </tr>
                </tfoot>
            </table>
        </section>
    </div>
</main>

<script type="text/javascript">
    $(document).ready(function() {
    
        $('.btnObservaciones').on('click', function() {
            var obsField = document.getElementById("observationsField")
            var obsButton = document.getElementById("btnObservaciones")

            if (obsField.style.display === 'none') {
                obsField.style.display = 'block'
                obsButton.textContent = 'Ocultar observaciones'
            }
            else {
                obsField.style.display = 'none'
                obsButton.textContent = 'Mostrar observaciones'
            }
        });       

    })
</script>