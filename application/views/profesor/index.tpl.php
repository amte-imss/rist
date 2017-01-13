<div class="row">
    <?php echo js("profesor/profesor.js"); ?>
    <div class="col-sm-1 col-md-1 col-lg-1 col-xl-"></div>
    <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10">
        <div class="row">
            <div class="panel panel-azul">
                <div class="breadcrumbs6">
                    <div class="container">
                        <h1 >
                            Registro de asistencias
                        </h1>
                    </div>
                </div>
                <div class="panel-body">	
                    <?php
                    if (exist_and_not_null($error))
                    {
                        ?>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-1"></div>
                            <div class="col-md-10 col-sm-10 col-xs-10 alert alert-danger">
                                <?php echo $error; ?>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1"></div>
                        </div>
                        <?php
                    }
                    ?>
                    <!--                                        <div class="row">
                                                                <div class="col col-sm-12 col-md-8 col-md-push-4">
                                                                    <form class="form-inline">
                                                                            <div class="col-sm-3">
                                                                              <div class="checkbox">
                                                                                <label>
                                                                                  <input type="radio" name="rdSesionTipo" value="1" checked="checked"> Activas
                                                                                </label>
                                                                              </div>
                                                                            </div>
                                                                              <div class="col-sm-3">
                                                                              <div class="checkbox">
                                                                                <label>
                                                                                  <input type="radio" name="rdSesionTipo" value="0"> Inactivas
                                                                                </label>
                                                                              </div>
                                                                            </div>
                                                                              <div class="col-sm-3">
                                                                              <div class="checkbox">
                                                                                <label>
                                                                                  <input type="radio" name="rdSesionTipo" value=""> Todas
                                                                                </label>
                                                                              </div>
                                                                            </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        <br>-->
                    <?php echo form_open('', array('id' => 'form_registro')); ?>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 text-right"><b class="rojo">*</b> Sesiones programadas:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon glyphicon glyphicon-pencil"> </span>
                                    <?php
                                    $lista_meses = array("", "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");

                                    echo $this->form_complete->create_element(
                                            array(
                                                'id' => 'rist_lista_meses',
                                                'type' => 'dropdown',
                                                'options' => $lista_meses,
                                                'first' => array('0' => 'Seleccione un mes de inicio'),
                                                'attributes' => array(
                                                    'class' => 'form-control-personal',
                                                    'data-placement' => 'bottom',
                                                    'title' => 'Sesiones programadas',
                                                )
                                            )
                                    );

                                    echo $this->form_complete->create_element(
                                            array(
                                                'id' => 'rist_sesiones',
                                                'type' => 'dropdown',
                                                'options' => $sesiones,
                                                'first' => array('0' => 'Seleccione una sesi&oacute;n'),
                                                'attributes' => array(
                                                    'class' => 'form-control-personal',
                                                    'data-placement' => 'bottom',
                                                    'title' => 'Sesiones programadas',
                                                )
                                            )
                                    );
                                    ?>                        
                                </div>
                                <?php echo form_error_format('rist_sesiones'); ?>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <div class="row">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10" id="list_usuarios" >

                        </div>
                        <div class="col-sm-1"></div>									
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1"></div>
</div>