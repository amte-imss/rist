<?php   echo form_open('buscador/index', array('id'=>'form_buscador')); ?>

<div class="row">
    <section class="col-sm-12">
        <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-amarillo">
			<div class="panel-heading clearfix breadcrumbs6">
				<h1 class="panel-title" style="padding-left:20px;">Buscador</h1>
                        </div>
                        <div class="panel-body">
                                <div class="row">
                                        <div class="col-lg-6 col-sm-6">
                                                <div class="panel-body  input-group input-group-sm">
                                                        <span class="input-group-addon">Tipo de Sesiones:</span>
                                                                <?php echo $this->form_complete->create_element(array('id'=>'tipo', 'type'=>'dropdown', 'options'=>$tipo, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'tipo[]', 'class'=>'form-control', 'placeholder'=>'Tipo de sesiones', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Tipo de Sesiones'))); ?>
                                                </div>
                                        </div>
                                       
                                        <div class="col-lg-6 col-sm-6">
                                                    <div class="panel-body input-group input-group-sm">
                                                            <span class="input-group-addon">Delegación:</span>
                                                                    <?php echo $this->form_complete->create_element(array('id'=>'delegacion', 'type'=>'dropdown', 'options'=>$delegacion, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'delegacion[]', 'class'=>'form-control', 'placeholder'=>'Delegación', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Delegación'))); ?>
                                                    </div>
                                        </div>
                                        
                                        
                                        <div class="col-lg-6 col-sm-6">
                                                <div class="panel-body  input-group input-group-sm">
                                                        <span class="input-group-addon">Sesiones:</span>
                                                                <?php echo $this->form_complete->create_element(array('id'=>'sesiones', 'type'=>'dropdown', 'options'=>$sesiones, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'sesiones[]', 'class'=>'form-control', 'placeholder'=>'Sesiones', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Sesiones'))); ?>
                                              </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-6">                                             
                                                <div class="panel-body input-group input-group-sm">
                                                        <span class="input-group-addon">Adscripción:</span>
                                                                <?php echo $this->form_complete->create_element(array('id'=>'adscripcion', 'type'=>'dropdown', 'options'=>$adscripcion, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'adscripcion[]', 'class'=>'form-control', 'placeholder'=>'Adscripción', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Adscripción'))); ?>
                                                </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-6">
                                                <div class="panel-body input-group input-group-sm">
                                                        <span class="input-group-addon">Categoría:</span>
                                                                <?php echo $this->form_complete->create_element(array('id'=>'categoria', 'type'=>'dropdown', 'options'=>$categoria, 'first'=>array(''=>'Seleccione...'), 'attributes'=>array('name'=>'categoria[]', 'class'=>'form-control', 'placeholder'=>'Categoría', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Categoría'))); ?>
                                                </div>
                                                
                                        </div>

                                        
                                </div> <!-- /Row -->
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">

                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                                <?php
                                                echo $this->form_complete->create_element(array('id'=>'btn_submit', 'type'=>'submit', 'value'=>'Buscar', 'attributes'=>array('class'=>'btn btn-info btn-sm espacio')));
                                                echo $this->form_complete->create_element(array('id'=>'btn_export', 'type'=>'submit', 'value'=>'Exportar', 'attributes'=>array('class'=>'btn btn-info btn-sm espacio', 'style'=>'display:none;')));
                                                 ?>
                                    </div>
                                </div>
                        </div>  <!-- /panel-body-->
                </div> <!-- /panel panel-amarillo-->
        </div> <!-- /col 12-->
        </div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-turqueza">
			<div class="panel-heading clearfix breadcrumbs6"><h1 class="panel-title" style="padding-left:20px;">Listado de registro a los talleres</h1></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-4 col-sm-4">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Número de registros a mostrar:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'per_page', 'type'=>'dropdown', 'options'=>array(10=>10, 20=>20, 50=>50, 100=>100), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Número de registros a mostrar', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Número de registros a mostrar', 'onchange'=>"data_ajax(site_url+'/buscador/get_data_ajax', '#form_buscador', '#listado_resultado')"))); ?>
						</div>
					</div>
					<div class="col-lg-4 col-sm-4">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Ordenar por:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'order', 'type'=>'dropdown', 'options'=>$order_columns, 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Ordernar por', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Ordenar por', 'onchange'=>"data_ajax(site_url+'/buscador/get_data_ajax', '#form_buscador', '#listado_resultado')"))); ?>
						</div>
					</div>
					<div class="col-lg-4 col-sm-4">
						<div class="panel-body input-group input-group-sm">
							<span class="input-group-addon">Tipo de orden:</span>
							<?php echo $this->form_complete->create_element(array('id'=>'order_type', 'type'=>'dropdown', 'options'=>array('ASC'=>'Ascendente', 'DESC'=>'Descendente'), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Ordernar por', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Ordenar por', 'onchange'=>"data_ajax(site_url+'/buscador/get_data_ajax', '#form_buscador', '#listado_resultado')"))); ?>
						</div>
					</div>
				</div>
				<div id="listado_resultado" class="row" style="padding:0 20px;">        </div>
			</div>
		</div>
	</div>
</div>
</section>
</div> <!-- row 12-->



<?php echo form_close(); ?>
<script type="text/javascript">
$( document ).ready(function() {
    var select_sesiones = $("#sesiones");
    data_ajax(site_url+"/buscador/get_data_ajax", "#form_buscador", "#listado_resultado");
    $("#btn_submit").click(function(event){
        data_ajax(site_url+"/buscador/get_data_ajax", "#form_buscador", "#listado_resultado");
        event.preventDefault();
    });
    $("#btn_reset").click(function(event){
        this.form.reset();
        data_ajax(site_url+"/buscador/get_data_ajax", "#form_buscador", "#listado_resultado");
        event.preventDefault();
    });
    $("#btn_export").click(function(event){
        event.preventDefault();
        $("#form_buscador").attr("action", site_url+"/buscador/export_data");
        $("#form_buscador").submit();
        //data_ajax(site_url+"/buscador/export_data", "#form_buscador", "#listado_resultado");        
    });
    $('[name="tipo"]').change(function(){
       console.log("Cambio del tipo seleccionado: "+ $(this).val());
       $.ajax({
           url: location.href+"/get_sesiones_ajax"
           , type: "post"
           , dataType: "json"
           , data: {
               tipo: $(this).val()
           }
           , error: function(){
               console.warn("No se ha podido pedir las sesiones");
           }
           , success: function(response){
               console.log("success: ", response.data);
                select_sesiones.html("");
                select_sesiones.append( $("<option>", { text: "Seleccione...", value: "" }));
                console.log("llenando..");
                for(var sesion in response.data){
                    console.log("iterando: ",response.data[sesion]);
                    select_sesiones.append($("<option>", { text: response.data[sesion]["a_nombre"], value: response.data[sesion]["agenda_id"] }));
                }
           }
       })
   }); 
});


</script>