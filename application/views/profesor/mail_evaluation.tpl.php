<h2>Estimado(a) <b><?php echo $student["fullname"]?></b>.</h2>
<p>
	En seguimiento al taller de Actualizaci&oacute;n en el uso de los recursos de informaci&oacute;n en salud que se llev&oacute; a cabo en el Centro de Investigaci&oacute;n Documental en Salud.
	<br>A continuaci&oacute;n, le hacemos llegar la direcci&oacute;n electr&oacute;nica donde podr&aacute; realizar la evaluaci&oacute;n correspondiente <b><a target="_blank" href="http://innovacioneducativa.imss.gob.mx/evacap" style="font-color:#37bc9b;">Evaluaci&oacute;n de la capacitaci&oacute;n.</a></b>
<p>
	Para ingresar proporcione sus claves de acceso como sigue:<br>
	Usuario: <b>N&uacute;mero de matr&iacute;cula<?php //echo $student["usr_matricula"]; ?></b><br>
	Contrase&ntilde;a: <b><?php echo $student["t_hash_constancia"]; ?></b><br>
</p>
<p>
	Le recordamos que el periodo de evaluaci&oacute;n, impresi&oacute;n y descarga de constancia es del <b><?php echo date("d-m-Y", strtotime($sesiones["a_evaluacion_inicio"])); ?> a las 00:00 hrs al <?php echo date("d-m-Y", strtotime($sesiones["a_evaluacion_fin"])); ?> a las 23:55 </b>,
	por lo que si usted no realiza la evaluaci&oacute;n durante las fechas establecidas, el curso ser&aacute; considerado como <b>"no aprobado"</b> y deber&aacute; cursarlo nuevamente.
<br>	
	<h3 style="text-align: center;">¡Agradecemos su inter&eacute;s y participaci&oacute;n!</h3>
</p>
<p>
	<b>Contacto:</b> Dra Sonia Aurora Gallardo Candelas <br>
	<b>Correo electr&oacute;nico:</b> <a href="mailto:sonia.gallardoc@imss.gob.mx">sonia.gallardoc@imss.gob.mx</a><br>
	<b>Teléfono:</b> 5627 6900 ext. 21250<bR>
	<a href="http://innovacioneducativa.imss.gob.mx/imss_conricyt/">Sitio Web del convenio IMSS CONRICYT</a>
</p>
<p style="text-align: right;">
	ATTE: Divisi&oacute;n de Innovaci&oacute;n Educativa, IMSS.<br>
	&Aacute;rea de Documentaci&oacute;n en Salud.
</p><br>
<p>*Nota:</p>
<p><b>- Es necesaria la puntualidad, las 2 asistencias y la evaluaci&oacute;n para recibir constancia.</b></p>
<?php
// <Pre>

// var_dump($sesiones);
// var_dump($student);
// </pre>
?>