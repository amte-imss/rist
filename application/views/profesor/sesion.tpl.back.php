<br>
<div class="row">
	<div class="col-sm-1"></div>
	<div class="col-sm-8" class="vertical-align: text-center">
		<p><strong><?php echo $sesiones['a_nombre']?></strong></p>
		<ul class="list-unstyled">
			<li>
				<i class="fa fa-chevron-circle-right pr-10"></i>
				<?php echo $sesiones['fecha']?>
			</li>
			<li>
				<i class="fa fa-chevron-circle-right pr-10"></i>
				<?php echo $sesiones['a_desc']?>
			</li>
		</ul>
	</div>
	<div class="col-sm-2 text-right">
	<?php
	echo form_open('/profesor/sendMessages', array('id'=>'form_message', 'class'=>'form-horizontal'));
	if(isset($students)){
		?>
		<input type="hidden" value="<?php echo $sesiones['agenda_id'] ?>" id="session_id" name="session_id" />
		<input type="submit" name="btn_submit" value="Enviar notificación" id="btn_submit" class="btn btn-primary  btn">
		<?php 
	}
	echo form_close();  ?>
	</div>
	<div class="col-sm-1"></div>
</div>
<div class="row">
	<div class="col-sm-1"></div>
	
	<div class="col-sm-1"></div>
</div>
<div class="row">
	<div class="col-sm-12">
	<?php
	if(isset($students)){
	?>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th>Matr&iacute;cula</th>
						<th>Nombre</th>
						<th>Delegaci&oacute;n</th>
						<th>Categor&iacute;a</th>
						<th>Adscripci&oacute;n</th>
						<th class="text-center"><?php echo $sesiones['a_inicio'] ?></th>
						<th class="text-center"><?php echo $sesiones['a_fin'] ?></th>
					</tr>
				</thead>
				<tbody>
				<?php	
				//pr($sesiones);
				foreach($students as $student){
					$data = "taller_id:{$student['taller_id']},sesion_id:{$sesiones['agenda_id']},";			
				?>
					<tr>
						<td><?php echo $student["usr_matricula"]?></td>
						<td><?php echo $student["fullname"]?></td>
						<td><?php echo $student["nom_delegacion"]?></td>
						<td><?php echo $student["nom_categoria"]?></td>
						<td><?php echo $student["cve_depto_adscripcion"]." - " . $student["nom_depto_adscripcion"]?></td>
						<td id="dayI_<?php echo $student['taller_id']?>" class="text-center">
							<script type="text/javascript">
								var data = {<?php echo $data."'tipo':'I'"?>};
								attendance_ajax("/profesor/attendance", "#dayI_<?php echo $student['taller_id']?>",data);
							</script>
						</td>
						<td id="dayF_<?php echo $student['taller_id']?>" class="text-center"> 
							<script type="text/javascript">
								var data = {<?php echo $data."'tipo':'F'"?>};
								attendance_ajax("/profesor/attendance", "#dayF_<?php echo $student['taller_id']?>",data);
							</script>
						</td>
					</tr>
				<?php
				}				
				?>					
				</tbody>			
			</table>
		</div>
	<?php
	}else{
	?>
		<h3 class="text-center">A&uacute;n no hay estudiantes registrados</h3>
	<?php
	}
	?>
	</div>
</div>