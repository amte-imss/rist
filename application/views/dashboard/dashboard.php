<style type="text/css">
.page-header{
    color:#000; border-bottom: 1px solid #000; margin-top: 20px;
}
</style>
<div class="row">
    <div class="container">
        <div class="breadcrumbs6 panel-heading" style="padding-left:20px; padding-top: 40px; padding-bottom: 50px; background-size: 100% 100%;">
            <h1><small><span class="glyphicon glyphicon-info-sign"></span></small> Talleres presenciales de actualización de recursos electrónicos</h1></div>
        <div class="panel-body">
            <div class="col-lg-12">
                <div class="col-sm-6">
                    <div class="table-responsive">
                    <table class="table" style="background-color: #AAA; color:#000;">
                        <thead>
                            <tr class="success">
                                <th>Recursos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Conricyt</td></tr>
                            <tr><td>Summon</td></tr>
                            <tr><td>Scopus</td></tr>
                            <tr><td>Web of Science</td></tr>
                            <tr><td>Clinical Key</td></tr>
                            <tr><td>Up to date</td></tr>
                            <tr><td>Access Medicine</td></tr>
                            <tr><td>EBSCO</td></tr>
                            <tr><td>Ovid</td></tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="table-responsive">
                    <table class="table table-striped" style="background-color: #AAA; color:#000;">
                        <thead>
                            <tr class="success">
                                <th colspan="3">Fechas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($sesiones_programadas as $key_sp => $sesion) {
                                echo '<tr><td>'.$sesion['a_nombre'].'</td><td>'.date("d-m-Y", strtotime($sesion['a_inicio'])).'</td><td>'.date("d-m-Y", strtotime($sesion['a_fin'])).'</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                <table class="table table-condensed">
                    <tr>
                        <td>Total:</td>
                        <td><?php //echo ; ?></td>
                    </tr>
                    <tr>
                        <td>Validados:</td>
                        <td><?php //echo $mis_validados; ?></td>
                    </tr>
                    <tr>
                        <td>Sin validar:</td>
                        <td><?php //echo $mis_sin_validar; ?></td>
                    </tr>
                </table>
            </div>
            
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h3>&Uacute;ltimo recurso actualizado</h3>
                <table class="table table-condensed">
                    <tr>
                        <td>Fecha de actualizaci&oacute;n:</td>
                        <td><?php //echo ($val_fecha!="-") ? get_fecha(3, new DateTime($val_fecha)) : "-"; ?></td>
                    </tr>
                    <tr>
                        <td>ISSN:</td>
                        <td><?php //echo $pub_issn; ?></td>
                    </tr>
                    <tr>
                        <td>T&iacute;tulo:</td>
                        <td><?php //echo $t_titulo; ?></td>
                    </tr>
                    </tr>
                </table>
                
            </div> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="container">
        <div class="breadcrumbs6 panel-heading" style="padding-left:20px; padding-top: 40px; padding-bottom: 50px; background-size: 100% 100%;">
            <h1><small><span class="glyphicon glyphicon-info-sign"></span></small> Sesiones en línea</h1></div>
        <div class="panel-body">
            <div class="col-lg-12">
                  <div class="col-sm-12">
                      <div class="table-responsive">
                    <table class="table table-striped" style="background-color: #AAA; color:#000">
 
                                <thead>
                                    <tr class="success">
                                        <th>Sesiones programadas</th>
                                        <th>Fecha y hora inicio </th>
                                        <th>Fecha y hora fin </th>
                                        <th>Fecha  inicio de inscripción</th>
                                        <th>Fecha  cierre de inscripción</th>
                                        <th>Duración</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    

                                    foreach ($sesiones_programadas_distancia as $key_sp => $sesion) {
                                        echo '<tr><td>'.$sesion['a_nombre'].'</td>
                                        <td>'.date("d-m-Y", strtotime($sesion['a_inicio'])).'  '.date('H:i', strtotime($sesion['a_hr_inicio'])).'</td>
                                        <td>'.date("d-m-Y", strtotime($sesion['a_fin'])).' '.date('H:i', strtotime($sesion['a_hr_fin'])).'</td>
                                        <td>'.date("d-m-Y", strtotime($sesion['a_registro'])).'</td>
                                        <td>'.date("d-m-Y", strtotime($sesion['a_registro_fin'])).'</td>
                                        <td>'.number_format($sesion['a_duracion']).'</td>
                                        
                                        </tr>';
                                    }
                                    ?>
                               

                                </tbody>
                            </table>
                      </div>
                </div>
            </div>

            <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                <table class="table table-condensed">
                    <tr>
                        <td>Total:</td>
                        <td><?php //echo ; ?></td>
                    </tr>
                    <tr>
                        <td>Validados:</td>
                        <td><?php //echo $mis_validados; ?></td>
                    </tr>
                    <tr>
                        <td>Sin validar:</td>
                        <td><?php //echo $mis_sin_validar; ?></td>
                    </tr>
                </table>
            </div>
            
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h3>&Uacute;ltimo recurso actualizado</h3>
                <table class="table table-condensed">
                    <tr>
                        <td>Fecha de actualizaci&oacute;n:</td>
                        <td><?php //echo ($val_fecha!="-") ? get_fecha(3, new DateTime($val_fecha)) : "-"; ?></td>
                    </tr>
                    <tr>
                        <td>ISSN:</td>
                        <td><?php //echo $pub_issn; ?></td>
                    </tr>
                    <tr>
                        <td>T&iacute;tulo:</td>
                        <td><?php //echo $t_titulo; ?></td>
                    </tr>
                    </tr>
                </table>
                
            </div> -->
        </div>
    </div>
</div>
<br>
<script type="text/javascript">
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});
</script>
