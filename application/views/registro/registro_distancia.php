<?php //echo css("password.css"); ?>
<?php //echo js("modernizr.custom.js"); ?>
<?php //echo js("hideShowPassword.min.js"); ?>
<?php

if( $sesiones_programadas_distancia_disponibles != 0)
{
    $col_ficha_informativa_clases = "col-sm-12 col-md-6 col-lg-6";
}else{
    $col_ficha_informativa_clases = "col-sm-12";
}
?>
<style type="text/css">
.rojo {
    color: #a94442;
}

.panel-body table{
    color: #000;
}
</style>

<div class="row">
    <div class="<?php echo $col_ficha_informativa_clases ?>">
        <div class="row" style="margin:5px;">
            <div class="panel">
                <div class="breadcrumbs6 panel-heading" style="padding-left:20px; padding-top: 40px; padding-bottom: 50px; background-size: 100% 100%;">
                    <h1><small><span class="glyphicon glyphicon-info-sign"></span></small> Bienvenido</h1></div>
                <div class="panel-body">
                    <div class="row">
                         <div class="col-sm-12">
                            <table class="table" style="background-color: #AAA">
                                <thead>
                                    <tr class="success">
                                        <th>Ficha informativa de taller</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                 <?php
                                    foreach ($sesiones_programadas_distancia as $key_sp => $sesion) {

                                 ?>

                                    <tr><td>Nombre de taller:</td><td><?php echo $sesion['a_nombre']?></td></tr>
                                    <tr><td>Fecha de inicio de incripciones: </td><td><?php echo date("d-m-Y - H:i",strtotime($sesion['a_registro']));?></td></tr>
                                    <tr><td>Fecha de cierre de inscripciones:</td><td><?php echo date("d-m-Y - H:i",strtotime($sesion['a_registro_fin']));?></td></tr>
                                    <tr><td>Fecha y hora de inicio del taller:</td><td><?php echo date("d-m-Y - H:i",strtotime($sesion['a_inicio']));?></td></tr>
                                    <tr><td>Fecha y hora de fin del taller:</td><td><?php echo date("d-m-Y - H:i",strtotime($sesion['a_fin']));?></td></tr>
                                    <tr><td>Duración:</td><td><?php echo $sesion['a_duracion']?> h</td></tr>
                                    <?php
                                    if (isset($sesion['a_desc']) && !empty($sesion['a_desc'])) {
                                        ?>
                                    <tr><td>Descripción:</td><td><?php echo $sesion['a_desc']?></td></tr>

                                 <?php
                                        }

                                   }
                                   ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <?php

   if( $sesiones_programadas_distancia_disponibles != 0)
   {
       $dia_actual = date("Y-m-d H:i:s");
       $fecha_termino = strtotime($sesion['a_registro_fin']);


   ?>


    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="row" style="margin:5px;">
            <div class="panel">
                <div class="breadcrumbs6 panel-heading" style="padding-left:20px;"><h1 id="titulo_registro">
                    <small><span class="glyphicon glyphicon-info-sign"></span></small> Registro a los talleres de actualización de recursos electrónicos </h1></div>

                    <div class="panel-body">
                    <?php
                     if ($fecha_termino >= strtotime($dia_actual)) {
                     if(exist_and_not_null($error)){ ?>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-1"></div>
                                <div class="col-md-10 col-sm-10 col-xs-10 alert alert-danger">
                                    <?php echo $error; ?>
                                </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1"></div>
                                </div>
                    <?php
                    }
                    echo form_open('', array('id'=>'form_registro', 'class'=>'form-horizontal')); ?>
                    <b class="rojo">*</b> Documentación obligatoria.<br><br>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b class="rojo">*</b> Matr&iacute;cula:</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon glyphicon glyphicon-pencil"> </span>
                            <?php
                            echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'reg_matricula',
                                        'type'=>'text',
                                        'attributes'=>array(
                                            'class'=>'form-control-personal',
                                            'placeholder'=>'Matr&iacute;cula',
                                            'autocomplete'=>'off',
                                            'data-toggle'=>'tooltip',
                                            'data-placement'=>'bottom',
                                            'title'=>'Matr&iacute;cula',
                                            'maxlength'=>20
                                            )
                                        )
                                    );
                            //'<br><div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>','</div>'
                            ?>
                            </div>
                            <?php   echo form_error_format('reg_matricula'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b class="rojo">*</b> Delegaci&oacute;n IMSS:</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon glyphicon glyphicon-chevron-down"> </span>
                            <?php
                            echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'reg_delegacion',
                                        'type'=>'dropdown',
                                        'options'=>$delegaciones,
                                        'first'=>array(''=>'Seleccione la delegaci&oacute;n'),
                                        'attributes'=>array(
                                            'class'=>'form-control-personal',
                                            'data-placement'=>'bottom',
                                            'title'=>'Delegaci&oacute;n de trabajo',
                                            )
                                        )
                                    );
                            ?>
                            </div>
                            <?php   echo form_error_format('reg_delegacion'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b class="rojo">*</b> Correo electr&oacute;nico:</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon"><strong>@</strong> </span>
                                <?php
                                echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'reg_email',
                                        'type'=>'email',
                                        'attributes'=>array(
                                            'class'=>'form-control-personal form-control',
                                            'placeholder'=>'alguien@imss.gob.mx',
                                            'autocomplete'=>'off',
                                            'data-placement'=>'bottom',
                                            'title'=>'Correo electr&oacute;nico del aspirante',
                                            'maxlength'=>80
                                            )
                                        )
                                    );
                                ?>
                            </div>
                             <?php echo form_error_format('reg_email'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label"><b class="rojo">*</b> C&oacute;digo de seguridad:</label>
                        <div class="col-sm-8">
                            <div class="text-center">   <?php echo $captcha['image']; ?></div><br>
                            <div class="input-group">
                                <span class="input-group-addon glyphicon glyphicon-lock"> </span>
                            <?php
                            echo $this->form_complete->create_element(
                                array(
                                    'id'=>'txt_captcha',
                                    'type'=>'text',
                                    'attributes'=>array(
                                        'class'=>'form-control-personal ',
                                        'placeholder'=>'Escribe el texto de la imagen...',
                                        'autocomplete'=>'off',
                                        'data-toggle'=>'tooltip',
                                        'data-placement'=>'top',
                                        'title'=>'C&oacute;digo de seguridad',
                                        'maxlength'=>6
                                        )
                                    )
                                );
                            ?>

                            </div>
                            <?php echo form_error_format('txt_captcha'); ?>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <input type="hidden" id="token" name="token" value="<?php echo (exist_and_not_null($this->session->userdata('token')) ? $this->session->userdata('token') : ''); ?>">
                        <input type="hidden" id="reg_sesion" name="reg_sesion" value="<?php echo $agenda_id ?>">
                            <?php
                            echo $this->form_complete->create_element(array(
                                'id'=>'btnn_submit',
                                'type'=>'submit',
                                'value'=>'Registrar',
                                'attributes'=>array(
                                    'class'=>'btn btn-primary'
                                    )
                                ));


                            echo form_close();

                            ?>
                    </div>
                    <?php
                }else {
                    echo "El registro a la sesión ha terminado.";
                }

                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php

        } ?>
</div>


<!-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Registro de aspirantes para Beca IMSS 2015</h4>
      </div>
      <div class="modal-body">
          <br><p class="aviso text-muted text-justify">La Coordinación de Educación en Salud pone a su disposición el presente formulario de pre-registro en línea, diseñado para registrar al personal institucional interesado en la participación de los cursos a distancia que año con año se elaboran para usted.</p>
          <br><p class="aviso text-muted text-justify">Para que su solicitud a este pre-registro pueda ser aceptada es requisito indispensable y obligatorio nos otorgue datos válidos y verídicos, para lo cual deberá apoyarse de su tarjetón de pago estrictamente vigente. En caso contrario esta Coordinación se reservará el derecho de inscripción una vez validada su información. </p>
          <br><p class="aviso text-muted text-justify">A continuación ingrese su matricula institucional y la Delegación en la que actualmente se encuentre adscrito. Los usuarios de Nivel Central favor de seleccionar la opción "Oficinas Centrales". Los usuarios de Nómina de mando favor de seleccionar la opción Mando (Delegaciones y Oficinas Centrales).</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div> -->


<?php
$data_del_cen = 'del!="'.implode('" && del!="', $delegacion_centro).'"';
?>
<script>
    $(document).ready(function(){
            //$('#myModal').modal('show');
            $("#txt_captcha").val("");
            $("#btn_submit").click(function() {
                var r = confirm("Le recordamos que estos talleres son 'PRESENCIALES' y que no existen\nviáticos destinados para esta capacitación.\n\nSi tiene alguna duda por favor comuniquese con nosotros antes de continuar.\n\nTeléfono : 56 27 69 00 Exts. 21146, 21147 y 21148\nRed: 865021146, 865021147, 865021148\nCorreo electrónico : acceso.edumed@imss.gob.mx\nHorario: lunes - viernes: 8:00 AM a 16:00 hrs ");
                if (r == true) {
                    var del = $("#reg_delegacion").val();
                    if(<?php echo $data_del_cen; ?>){
                        var c = confirm("¿Esta seguro de querer continuar con su registro?\n\nLe recordamos que:\na)Sólo puede estar inscrito en un taller por año.\nb) Sólo es posible cancelar y reprogramar en 1 ocasión la fecha de su preferencia.\nc) Es necesaria la puntualidad, las 2 asistencias y la evaluación para recibir constancia.");
                        if (c == false) {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            });
    });
    /*$('#reg_password').hideShowPassword({
      // Creates a wrapper and toggle element with minimal styles.
      innerToggle: true,
      // Makes the toggle functional in touch browsers without
      // the element losing focus.
      touchSupport: Modernizr.touch
    });
    $('#reg_password_again').hideShowPassword({
      // Creates a wrapper and toggle element with minimal styles.
      innerToggle: true,
      // Makes the toggle functional in touch browsers without
      // the element losing focus.
      touchSupport: Modernizr.touch
    });*/
</script>
