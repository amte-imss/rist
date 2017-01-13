<div class="row">
    <div class="breadcrumbs6" style="background-size: 100% 100%;">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-4">
                    <h1>
                        Talleres presenciales de actualización 
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
                            Talleres presenciales de actualización 
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="property gray2-bg">
        <div class="col-lg-12 col-md-12 col-sm-12  wow fadeInLeft animated" style="visibility: visible; animation-name: fadeInLeft;" data-wow-animation-name="fadeInLeft">
                <!--<img src="img/GIF-300x250.gif" class="img-responsive">-->
            <h1>Talleres presenciales de actualización para el aprovechamiento de recursos electrónicos de información en salud</h1>
        </div>
        <div class="container">
            <div class="col-lg-4"><h3>SEDE</h3><p>Centro Nacional de Investigación<br/>
                    Documental en Salud (CENAIDS),<br />sótano de la Unidad de Congresos, CMN SXXI </p></div>
            <div class="col-lg-4"><h4>Informes:</h4>
                <p>Dra. Sonia Aurora Gallardo Candelas<br>
                    Teléfono: 5627 6900, extensión 21250<br>
                    <em>email:</em> sonia.gallardoc@imss.gob.mx</p>
            </div>
            <div class="col-lg-4"> <h4>Horario:</h4>
                <p>de 8:00 a 14:00 horas
                </p><br>
            </div>
            
            <div class="row">
            <div data-wow-animation-name="fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;" class="col-lg-12 col-md-12 col-sm-12  wow fadeInLeft animated"> <a href="http://educacionensalud.imss.gob.mx/rist/index.php/registro" target="_blank" class="btn btn-primary btn-lg btn-block">¡Regístrate!</a></div>


        </div>
        </div>
        <p>&nbsp;</p>

        

        <div class="ui-60">

            <?php
            //pr($calendario);
            $meses = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');

            //echo date("d-m-Y");
            //echo date("H:i", );
            ?>
            <div class="container">

                <?php
                $par = 1; // declaramos una variable para controlar el clearfix

                foreach ($calendario as $fecha)
                {
                    $dia_actual = date("Y-m-d");
                    $fecha_inicio = date('Y-m-d', strtotime($fecha['a_inicio']));
                    $mes = date("m", strtotime($fecha['a_inicio']));
                    $dia = date("d", strtotime($fecha['a_inicio']));
                    $fin = date("d", strtotime($fecha['a_fin']));
                    $hinicio = date("H:i", strtotime($fecha['a_inicio']));
                    $hfin = date("H:i", strtotime($fecha['a_fin']));
                    //pr($dia_actual);
                    $nombre = $fecha['a_nombre'];
                    $duracion = number_format($fecha['a_duracion']);
                    $status = $fecha['a_estado'];
                    $liga = site_url('registro/');
                    $circle = 'bg-grey';
                    $boton = '<a href="' . $liga . '" target="_blank" class="btn btn-black" disabled="disabled">Cerrado</a>';
                    if ($fecha_inicio > $dia_actual && $fecha['a_estado'] == 1)
                    {
                        $boton = '<a href="' . $liga . '" target="_blank" class="btn btn-info">Registrar</a>';
                        $circle = 'bg-green';
                    }
                    //<i class="fa fa-desktop orange"></i></h3>
                    ?><div class="col-md-6">
                        <!-- Pricing item -->
                        <div class="ui-item clearfix">
                            <a href="#" class="ui-price <?php echo $circle; ?> circle"> <?php echo $dia . '<b> y </b><br />' . $fin; ?> </a>
                            <div class="ui-plan">
                                <!-- Plan name -->
                                <h3><?php echo $meses[$mes]; ?>
                                    <!-- Plan details -->
                                    <h3>CONRICyT, Clinical Key, Summon, Up to Date, Ovid, Scopus, Access Medicine, Web of Science, EBSCO</h3>
    <?php echo $boton; ?>
                            </div>
                        </div>
                        <!-- Pricing item -->
                    </div>

                    <?php
                    if ($par % 2 == 0)
                    {
                        echo '<div class="clearfix"></div>';
                    }
                    $par++;
                }
                ?>

            </div>
        </div>
    </div>
</div>
