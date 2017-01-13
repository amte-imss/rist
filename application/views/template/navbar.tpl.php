<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span>Menú</span>
            </button>
            <!--<a class="navbar-brand" href="index.html">Inicio</a>-->
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php
                $usuario_logueado = $this->session->userdata('usuario_logeado');
                $tipo_admin = $this->session->userdata('tipo_admin'); //Tipo de usuario almacenado en sesión
                $tipo_admin_config = $this->config->item('rol_admin'); //Identificador de administrador
                //pr($tipo_admin);
                if (exist_and_not_null($usuario_logueado)) { ///Validar si usuario inicio sesión
                    if ($tipo_admin == $tipo_admin_config['SUPERADMIN']['id']) { ///Administrador 
                        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Talleres&nbsp;<i class="glyphicon glyphicon-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo site_url('registro'); ?>">Registro talleres presenciales</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('registro/registrosagenda'); ?>">Registro sesiones en línea</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('catalogos'); ?>">Agenda talleres presenciales</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('catalogosdistancia'); ?>">Agenda sesiones en línea</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?php echo site_url('profesor'); ?>">Asistencia</a>
                        </li>

                        <li>
                            <a href="<?php echo site_url('buscador'); ?>">Buscador</a>
                        </li>
                        <!--<li>
                            <a href="<?php //echo site_url('usuarios');  ?>">Usuarios</a>
                        </li>-->


                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Catálogos&nbsp;<i class="glyphicon glyphicon-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo site_url('usuarios'); ?>">Lista usuarios</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('talleres'); ?>">Lista registro a talleres</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('asistencia'); ?>">Lista asistencias</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('usuarios/admin'); ?>">Admin</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('catalogos/categorias'); ?>">Categoría</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('catalogos/departamentos'); ?>">Departamentos</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('catalogos/delegaciones'); ?>">Delegación</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?php echo site_url('login/cerrar_session'); ?>"><span class="glyphicon glyphicon-log-out"></span> Cerrar sesión</a>
                        </li>
    <?php } elseif ($tipo_admin == $tipo_admin_config['ADMIN']['id']) { ///Administrador  ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Talleres&nbsp;<i class="glyphicon glyphicon-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo site_url('registro'); ?>">Registro talleres presenciales</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('registro/registrosagenda'); ?>">Registro sesiones en línea</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('catalogos'); ?>">Agenda talleres presenciales</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('catalogosdistancia'); ?>">Agenda sesiones en línea</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?php echo site_url('profesor'); ?>">Asistencia</a>
                        </li>

                        <li>
                            <a href="<?php echo site_url('buscador'); ?>">Buscador</a>
                        </li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?php echo site_url('login/cerrar_session'); ?>"><span class="glyphicon glyphicon-log-out"></span> Cerrar sesión</a>
                        </li>
    <?php } else { //Docente  ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Talleres&nbsp;<i class="glyphicon glyphicon-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo site_url('registro'); ?>">Registro</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?php echo site_url('profesor'); ?>">Asistencia</a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?php echo site_url('login/cerrar_session'); ?>"><span class="glyphicon glyphicon-log-out"></span> Cerrar sesión</a>
                        </li>
                    <?php }
                } else { ///Usuario sin sesión 
                    ?>
                    <li class="active">
                        <a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/index.html"> <span class="glyphicon glyphicon-home"> </span> Inicio</a>
                    </li>
                    <li>
                        <a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/que.html">Conéctate al Conocimiento</a>
                    </li>
                    <li class="">
                        <a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/recursos.html">Recursos de Información</a>
                    </li>
                    <li class="dropdown">
                        <a  href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" >Editoriales <span class="caret"></span> </a>
                        <ul class="dropdown-menu">
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/jama.html">American Medical Association</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/ebsco.html">Ebsco México</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/elsevier.html">Elsevier B.V.</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/gale.html">Gale Databases</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/lippincott.html">Lippincott Williams & Wilkins</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/access.html">McGrawHill</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/nature.html">Nature America Inc.</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/new_england.html">New England Journal of Medicine Group </a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/oxford.html">Oxford University Press</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/springer.html">Springer Science + Business Media</a></li>           
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/thomson.html">Thomson Reuters</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/wiley.html">Wiley Blackwell</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/walters.html">Wolters Kluwer Health</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a  href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Capacitación <span class="caret"></span> </a>
                        <ul class="dropdown-menu">
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/materiales.html">Materiales de apoyo</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/talleres.html">Talleres de actualización presenciales</a></li>
                            <li><a href="<?php echo site_url('calendario'); ?>">Sesiones de actualización en línea</a></li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/recursos_prueba.html">Recursos prueba</a>
                    </li>
                    <li class="">
                        <a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/faq.html">Preguntas Frecuentes</a>
                    </li>
                    <li class="">
                        <a href="" data-toggle="modal" data-target="#myModal">Contacto</a>
                    </li>

                    <!-- <li class="active">
                         <a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/index.html"> <span class="glyphicon glyphicon-home"> </span> Inicio</a>
                    </li>
                    <li>
                        <a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/que.html">Conéctate al Conocimiento</a>
                    </li>
                    <li class="">
                        <a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/recursos.html">Recursos de Información</a>
                    </li>

                     <li class="dropdown">
                        <a  href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" >Editoriales <span class="caret"></span> </a>
                         <ul class="dropdown-menu">
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/jama.html">American Medical Association</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/ebsco.html">Ebsco México</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/elsevier.html">Elsevier B.V.</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/lippincott.html">Lippincott Williams & Wilkins</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/nature.html">Nature America Inc.</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/oxford.html">Oxford University Press</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/springer.html">Springer Science + Business Media</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/access.html">Access Medicine</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/thomson.html">Thomson Reuters</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/wiley.html">Wiley Blackwell</a></li>
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/walters.html">Walters Kluwer Health</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a  href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Capacitación <span class="caret"></span> </a>
                        <ul class="dropdown-menu">
                            <li><a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/materiales.html">Materiales de apoyo</a></li>
                            <li><a href="http://educacionensalud.imss.gob.mx/rist/index.php/registro">Talleres de actualización</a></li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/recursos_prueba.html">Recursos prueba</a>
                    </li>
                    <li class="">
                        <a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/faq.html">Preguntas Frecuentes</a>
                    </li>
                    <li class="">
                        <a href="" data-toggle="modal" data-target="#myModal">Contacto</a>
                    </li> -->
<?php } ?>            
            </ul>
        </div>
    </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h2 class="modal-title" id="myModalLabel">Contacto</h2>
            </div>
            <div class="modal-body">
                <div class="media">
                    <div class="pull-left">
                      <!-- <img class="img-responsive thumbnail" src="img/mesa.png"> -->
<?php echo img("mesa.png", array("class" => "img-responsive thumbnail")); ?>
                    </div>
                    <div class="media-body">
                        <h3 class="media-heading">Mesa de ayuda</h3>
                        <p>No dude en ponerse en contacto con nosotros.</p>
                        <p><i class="fa fa-phone"></i> 
                            <abbr title="Phone">Tel</abbr>: 56 27 69 00 <strong> Exts.</strong> 21146, 21147 y 21148 </p>
                        <p><i class="fa fa-arrow-circle-right"></i> 
                            <abbr title="red">Red</abbr>: 865021146, 865021147, 865021148</p>
                        <p><i class="fa fa-envelope-o"></i> 
                            <abbr title="Email">Email</abbr>: <a href="mailto:acceso.edumed@imss.gob.mx">acceso.edumed@imss.gob.mx</a>
                        </p>
                        <p><i class="fa fa-clock-o"></i> 
                            <abbr title="Hours">Horario</abbr>: lunes - viernes: 8:00 AM a 16:00 Hrs</p>
                    </div>
                </div>
            </div><!--cierra modal-body-->

            <div class="modal-body">
                <div class="media">
                    <div class="pull-left">
                      <!-- <img class="img-responsive thumbnail" src="img/disponibilidad.png"> -->
<?php echo img("disponibilidad.png", array("class" => "img-responsive thumbnail")); ?>
                    </div>
                    <div class="media-body">
                        <h3 class="media-heading">Disponibilidad de Recursos</h3>
                        <p><strong>Lic. Verónica Sánchez Castillo </strong></p>
                        <p>División de Innovación Educativa </p>
                        <p><i class="fa fa-phone"></i> 
                            <abbr title="Phone">Tel</abbr>:  (55) 5627 6900   <strong> Exts.</strong> 21250</p>
                        <p><i class="fa fa-envelope-o"></i> 
                            <abbr title="Email">Email</abbr>: <a href="mailto:veronica.sanchezc@imss.gob.mx">veronica.sanchezc@imss.gob.mx</a>
                        </p>
                        <p><i class="fa fa-envelope-o"></i> 
                            <abbr title="Email">Email</abbr>: <a href="mailto:imss.recursoselectronicos@gmail.com">imss.recursoselectronicos@gmail.com</a>
                        </p>
                        <p><i class="fa fa-clock-o"></i> 
                            <abbr title="Hours">Horario</abbr>: lunes - viernes: 8:00 AM a 16:00 Hrs</p>
                    </div>
                </div>
            </div><!--cierra modal-body-->
            <div class="modal-body">
                <div class="media">
                    <div class="pull-left">
                      <!-- <img class="img-responsive thumbnail" src="img/capa.png"> -->
<?php echo img("capa.png", array("class" => "img-responsive thumbnail")); ?>
                    </div>
                    <div class="media-body">
                        <h3 class="media-heading">Capacitación</h3>
                        <p><strong>Dra. Sonia Aurora Gallardo Candelas</strong> </p>
                        <p>División de Innovación Educativa </p>
                        <p><i class="fa fa-phone"></i> 
                            <abbr title="Phone">Tel</abbr>:  (55) 5627 6900   <strong> Exts.</strong> 21250</p>
                        <p><i class="fa fa-envelope-o"></i> 
                            <abbr title="Email">Email</abbr>: <a href="mailto:sonia.gallardoc@imss.gob.mx">sonia.gallardoc@imss.gob.mx</a>
                        </p>
                        <p><i class="fa fa-clock-o"></i> 
                            <abbr title="Hours">Horario</abbr>: lunes - viernes: 8:00 AM a 16:00 Hrs</p>
                    </div>
                </div>
            </div><!--cierra modal-body-->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
