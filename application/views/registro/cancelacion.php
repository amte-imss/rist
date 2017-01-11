<?php //echo css("password.css"); ?>
<?php //echo js("modernizr.custom.js"); ?>
<?php //echo js("hideShowPassword.min.js"); ?>
<style type="text/css">
.rojo {
    color: #a94442;
}

.panel-body table{
    color: #000;
}
</style>

<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="row" style="margin:5px;">
            <div class="panel">
                <div class="breadcrumbs6 panel-heading" style="padding-left:20px; padding-top: 40px; padding-bottom: 50px;"><h1><small><span class="glyphicon glyphicon-info-sign"></span></small> Bienvenido</h1></div>
                <div class="panel-body">
                    <p>Sistema de registro a talleres de actualización en el uso de los recursos de información en salud.</p>
                    <p>Si desea <a href="<?php echo site_url('/registro/cancelacion'); ?>" style="font-weight: bold;">cancelar</a> su registro a los talleres lo puede hacer desde <a href="<?php echo site_url('/registro/cancelacion'); ?>" style="font-weight: bold;">aquí</a>.</p>
                    <p>Si desea re-agendar su asistencia debe primero <a href="<?php echo site_url('/registro/cancelacion'); ?>" style="font-weight: bold;">cancelar</a> su registro previo y posteriormente <a href="<?php echo site_url('/registro'); ?>" style="font-weight: bold;">registrarse</a> nuevamente. Considere las siguientes restricciones:<br>
                        a) S&oacute;lo puede estar inscrito en un taller por a&ntilde;o.<br>
                        b) S&oacute;lo es posible cancelar y reprogramar en 1 ocasi&oacute;n la fecha de su preferencia.</p>
                    
                    <p style="font-size:12px;">*Nota. Indispensable contar con equipo de cómputo personal.</p>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped" style="background-color: #AAA">
                                <thead>
                                    <tr class="success">
                                        <th>Sesiones programadas</th>
                                        <th>Fechas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($sesiones_programadas as $key_sp => $sesion) {
                                        echo '<tr><td>'.$sesion['a_nombre'].'</td><td>'.date("d-m-Y", strtotime($sesion['a_inicio'])).' y '.date("d-m-Y", strtotime($sesion['a_fin'])).'</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12">
                            <table class="table" style="background-color: #AAA">
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
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="row" style="margin:5px;">
            <div class="panel">
                <div class="breadcrumbs6 panel-heading" style="padding-left:20px;"><h1 id="titulo_registro"><small><span class="glyphicon glyphicon-info-sign"></span></small> Cancelar registro a los talleres de actualización de recursos electrónicos <font color="yellow">(Sesiones presenciales. Sede CENAIDS Centro Médico Siglo XXI)</font></h1></div>
    				<div class="panel-body">
    				<?php if(exist_and_not_null($error)){ ?>
    					<div class="row">
    						<div class="col-md-1 col-sm-1 col-xs-1"></div>
    							<div class="col-md-10 col-sm-10 col-xs-10 alert alert-danger">
    								<?php echo $error; ?>
    							</div>
    								<div class="col-md-1 col-sm-1 col-xs-1"></div>
    							</div>
    				<?php
    				}
    				echo form_open('', array('id'=>'form_cancelacion', 'class'=>'form-horizontal')); ?>
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
                        <label class="col-sm-4 control-label"><b class="rojo">*</b> Folio de registro <a href="#" data-toggle="tooltip" title="Conjunto de números y letras que le fueron asignadas y enviadas a su correo electrónico cuando realizó su registro."><span class="glyphicon glyphicon-question-sign"> </span></a> :</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon glyphicon glyphicon-pencil"> </span>
                                <?php 
                                echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'reg_folio', 
                                        'type'=>'text', 
                                        'attributes'=>array(
                                            'class'=>'form-control-personal', 
                                            'placeholder'=>'Folio de registro',
                                            'autocomplete'=>'off', 
                                            'data-placement'=>'bottom', 
                                            'title'=>'Folio de registro',
                                            'maxlength'=>80
                                            )
                                        )
                                    ); 
                                ?>                            
                            </div>
                             <?php echo form_error_format('reg_folio'); ?>
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
                            <?php
                            echo $this->form_complete->create_element(array(
                                'id'=>'btn_submit', 
                                'type'=>'submit', 
                                'value'=>'Enviar', 
                                'attributes'=>array(
                                    'class'=>'btn btn-primary'
                                    )
                                ));
                            
                            
                            echo form_close(); 
                            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
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



<script>
	$(document).ready(function(){
            //$('#myModal').modal('show');
            $("#txt_captcha").val("");
                
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