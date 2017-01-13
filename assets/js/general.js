$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip(); //Llamada a tooltip
});

/**
 *	Método que muestra una imagen (gif animado) que indica que algo esta cargando
 *	@return	string	Contenedor e imagen del cargador.
*/
function create_loader(){
	return '<div id="ajax_loader" align="center" style="padding-top:200px; padding-bottom:200px;"><img src="'+img_url_loader+'" alt="Cargando..." title="Cargando..." /></div>';
}

/**
 *	Método que remueve el contenedor e imagen de cargando
*/
function remove_loader(){
	$("#ajax_loader").remove();
}

/**	Método que muestra un mensaje con formato de alertas de boostrap
 * @param	string	message 	Mensaje que será mostrado
 * @param 	string 	tipo 		Posibles valores('success','info','warning','danger')
*/
function html_message(message, tipo){
	tipo = (typeof(tipo) === "undefined") ? 'danger' : tipo;
	return "<div class='alert alert-"+tipo+"' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"+message+"</div>";
}


function data_ajax(path, form_recurso, elemento_resultado){
	$.ajax({
		url: path,
		data: $(form_recurso).serialize(),
		method: 'POST',
		beforeSend: function( xhr ) {
			$(elemento_resultado).html(create_loader());
		}
	})
	.done(function(response) {
		$(elemento_resultado).html(response);
	})
	.fail(function( jqXHR, textStatus ) {
		$(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
	})
	.always(function() {
		remove_loader();
	});
}

/**
 *	Método que válida con javascript la extensión del archivo que se desea subir
 *	@param 	string	fileName 	Nombre del archivo
 *	@param	array	extension 	Arreglo de extensiones permitidas
 *	@return	boolean				true en caso de que la extensión del archivo se encuentre dentro de las permitidas
*/
function validate_extension(fileName, extension){
    var file_extension = fileName.split('.').pop(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.

    for(var i = 0; i <= extension.length; i++) {
        if(extension[i]==file_extension) {
            return true; // valid file extension
        }
    }

    return false;
}

/**
 *	Método que crea un modal que muestra un mensaje
 *	@attribute 	title 			Título que se le colocará al modal
 *	@attribute 	mensaje			Mensaje que mostrará
 *	@return	false
*/
function mensaje_modal(mensaje, title){
	if($('#dataMessageModal').length>0) {
		$('#dataMessageModal').remove();
	}
	var html = "<div class='modal fade' id='dataMessageModal' role='dialog' aria-labelledby='dataConfirmLabel'>"+
			"<div class='modal-dialog'>"+
				"<div class='modal-content'>"+
					"<div class='modal-header'>"+
						"<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>"+
						"<h4 class='modal-title'>"+title+"</h4>"+
					"</div>"+
					"<div class='modal-body'>"+
						"<p>"+mensaje+"</p>"+
					"</div>"+
					"<div class='modal-footer'>"+
						"<button type='button' class='btn btn-default' aria-hidden='true' data-dismiss='modal'>Aceptar</button>"+
					"</div>"+
				"</div>"+
			"</div>"+
		"</div>";
	$('body').append(html);
	$('#dataMessageModal').modal({show:true});
	
	return false;
}

function dropdown(id,tag,act){
	var val = $(id).val();
	// alert(val);
	$.ajax({
		url: site_url+act,
		data: {'campo':val},
		method: 'POST',
		dataType: 'JSON',
		beforeSend: function( xhr ) {
			$(tag).html(create_loader());
		}
		
	})
	.done(function(response) {
		// alert(response.resultado);
		if(response.resultado==true){
			$(tag).html(response.data);
			$('[data-toggle="tooltip"]').tooltip();
		} else {
			$(tag).html(html_message(response.error, 'danger'));
		}
	})
	.fail(function( jqXHR, textStatus ) {
		// alert(textStatus)
		$(tag).html(html_message("Ocurri&oacute; un error durante el proceso, inténtelo m&aacute;s tarde.", 'danger'));//+textStatus
	})
	.always(function() {
		remove_loader();
	});
}