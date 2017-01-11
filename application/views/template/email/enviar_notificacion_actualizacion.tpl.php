<div class="row">

	<div class="col-sm-1 col-md-1 col-lg-1 col-xl-"></div>
	<div class="col-sm-10 col-md-10 col-lg-10 col-xl-10">
		<div class="row">
			<div class="panel panel-azul">
				<div class="breadcrumbs6">
					<div class="container">
						<h1 >
						Registro de envío de correos de actualización
						</h1>
					</div>
				</div>
				<div class="panel-body">
					<?php
					if(isset($notificaciones) && !empty($notificaciones)){ ?>
						<h3>Se ha enviado notificaci&oacute;n a los siguientes usuarios:</h3>
						<ul class="list-unstyled">
						<?php
						foreach($notificaciones as $student){
							?>
							<li>
								<i class="fa fa-chevron-circle-right pr-10"></i>
							<?php 
							echo $student["usr_matricula"].", ".$student["fullname"]." al correo electr&oacute;nico: "
								.$student["usr_correo"]."."
							?>
							</li>
						<?php
						}
						?>
						</ul>
					<?php } else { ?>
						<h3>No se han enviado notificaciones, ya que la sesión se encuentra inactiva.
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-1 col-md-1 col-lg-1 col-xl-"></div>
</div>
<?php
// pr($notificaciones);
?>