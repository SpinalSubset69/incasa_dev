<div class="headerBack" style="background: linear-gradient(90deg, rgba(26, 43, 64,0.9) 100% ,rgb(24,30,51) 0%);">
  <div class="page-content miHeader">
    <div class="miTitulo">
      <i class="truck circle icon"></i>
      <h2>Plantas</h2>
    </div>
    <p class="txtBg">Plantas</p>
  </div>
</div>

<main class="page-content">
  <div class="container-fluid">
    <section class="showPlants">
        <table style="margin: 30px auto; width: 90%;" class="ui fixed table">
            <thead>
                <tr>
                    <th>Planta</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plants as $plant): ?>
                    <tr>
                        <td>
                            <h4 class="ui image header" style="display: flex; align-items: center; justify-content:flex-start;" >
                                <a style="margin-right: 10px;" class="ui grey circular label"><?php echo $plant['total']; ?></a>
                                <div class="content">
                                    <?php echo $plant['nameBuilding']; ?>
                                    <div class="sub header"><?php echo $plant['time']; ?> minutos
                                    </div>
                                </div>
                            </h4>
                        </td>
                        <td style="text-align:right;"><i class="truck icon"></i></td>
                    </tr>
                    <?php foreach($plant['v2plant'] as $v2plant): ?>
                    <tr style="background-color: gray">
                        <td>                        
                        </td>
                        <td style="text-align:right;">
                            <?php $v2plant['idTruck']; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php foreach($plant['vInplant'] as $vInplant): ?>
                    <tr style=<?php if($vInplant['good']==0) echo "background-color: green"; else echo "background-color: red"; ?>>
                        <td>                        
                        </td>
                        <td style="text-align:right;">
                            <?php $vInplant['idTruck']; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
  </div>
</main>



<script type="text/javascript">
  $(document).ready(function(){

    

  });
</script>
