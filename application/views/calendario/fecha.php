<div class="row">
<div class="breadcrumbs6">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-sm-4">
            <h1>
              Sesiones en línea
            </h1>
          </div>
          <div class="col-lg-8 col-sm-8">
            <ol class="breadcrumb pull-right">
              <li>
                <a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/index.html">
                  Inicio
                </a>
              </li>
              <li class="active">
                Sesiones en línea
              </li>
            </ol>
          </div>
        </div>
      </div>
</div>
<div class="property gray2-bg">
<div class="col-lg-12 col-md-12 col-sm-12  wow fadeInLeft animated" style="visibility: visible; animation-name: fadeInLeft;" data-wow-animation-name="fadeInLeft">
        <!--<img src="img/GIF-300x250.gif" class="img-responsive">-->
            <h1>Actualización para el aprovechamiento de recursos electrónicos de información en salud</h1>
        </div>
        <div class="container">
        <div class="col-lg-4"><h3>SEDE</h3><p>En línea</p></div>
        <div class="col-lg-4"><h4>Informes:</h4>
                <p>Dra. Sonia Aurora Gallardo Candelas<br>
                Teléfono: 5627 6900, extensión 21250<br>
                <em>email:</em> sonia.gallardoc@imss.gob.mx</p>
        </div>
        <div class="col-lg-4"> <h4>Requisitos Técnicos:</h4>
                <p>Actualizar adobe conect<br>
                Tener diadema con audífonos
                </p><br>
        </div>
        </div>
        <p>&nbsp;</p>


<div class="ui-60">

    <?php //pr($calendario);
        $meses = array('01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');

        //echo date("d-m-Y");
        //echo date("H:i", );
    ?>
    <div class="container">

            <?php

            $par = 1;// declaramos una variable para controlar el clearfix

            foreach ($calendario as $fecha) {
                $dia_actual = date("Y-m-d H:i:s");
                $fecha_termino = strtotime($fecha['a_registro_fin']);
                $mes = date("m", strtotime($fecha['a_inicio']));
                $dia = date("d", strtotime($fecha['a_inicio']));
                //pr($dia_actual);
                $nombre =  $fecha['a_nombre'];
                $hinicio = date("H:i",strtotime($fecha['a_hr_inicio']));
                $hfin = date("H:i",strtotime($fecha['a_hr_fin']));
                $duracion = $fecha['a_duracion'];
                $liga = $fecha['a_liga'];
                $status = $fecha['a_estado'];
                $liga = site_url('registro/registrodistancia/'.$fecha['agenda_id']);
                $circle='bg-grey';
                $boton = '<a href="'.$liga.'" target="_blank" class="btn btn-black" disabled="disabled">Cerrado</a>';
                if ($fecha_termino >= strtotime($dia_actual) && $fecha['a_estado'] == 1) {
                    $boton = '<a href="'.$liga.'" target="_blank" class="btn btn-info">Registrar</a>';
                    $circle='bg-green';
                }
                //<i class="fa fa-desktop orange"></i></h3>
                ?><div class="col-md-6">
                <!-- Pricing item -->
                <div class="ui-item clearfix">
                    <a href="#" class="ui-price <?php echo $circle; ?> circle"> <?php echo $dia; ?> </a>
                    <div class="ui-plan">
                        <!-- Plan name -->
                        <h3><?php echo $meses[$mes]; ?>
                        <!-- Plan details -->
                        <h3><?php echo $nombre; ?> </h3>
                        <p>HORA: <?php echo $hinicio; ?> - <?php echo $hfin; ?>  hr</p>
                        <!--<div class="col-lg-12 col-md-12 col-sm-12"><?php echo $fecha['a_desc']; ?></div>-->
                        <p>DURACIÓN: <?php echo $duracion; ?> hr</p>
                        <?php echo $boton; ?>
                    </div>
                </div>
                <!-- Pricing item -->
            </div>

        <?php
            if ($par%2==0) {
                echo '<div class="clearfix"></div>';
            }
            $par++;
        }

        ?>

</div>
</div>
</div>
</div>
