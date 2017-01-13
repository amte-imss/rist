$(document).ready(function(){
    var combobox = $("#rist_sesiones");
    var lista = $("#list_usuarios");
    var lista_meses = $("#rist_lista_meses");
    combobox.change(function(){
        if($(this).val() != 0){
            dropdown("#rist_sesiones","#list_usuarios","/profesor/sesion");
        }else{
            lista.html("");
        }
    });
    $("input[name=rdSesionTipo]:radio").change(function () {
        
    });
    lista_meses.change(function(){
        sesiones_ajax($(this).val());
    });
});

function sesiones_ajax(mes){
    
    $.ajax({
        url: site_url + "/profesor/sesiones_ajax"
        , data: {
           mes:mes
        }
        , method: "post"
        , dataType: "json"
        , success: function(response){
            var comboBoxSesiones = $("#rist_sesiones");
            comboBoxSesiones.html("");
            comboBoxSesiones.append($("<option>", {
                text: "Seleccione una sesión"
                , value: 0
            }))
            for(x in response){
                comboBoxSesiones.append($("<option>", {
                    text: (""+response[x].texto).replace("&oacute;", "ó")
                    , value: response[x].valor
                }));
            }
        }
        , error: function(){
            console.warn("No se pudo realizar la conexión");
        }
    });
}


function attendance_ajax(path, elemento_resultado,datos,despues){
	$.ajax({
		url: site_url+path,
		data: datos,
		method: 'POST',
		dataType: 'JSON',
		beforeSend: function( xhr ) {
			$(elemento_resultado).html(create_loader());
		}
	})
	.done(function(response) {
		// alert(response.resultado);
		if(response.resultado==true){
			$(elemento_resultado).html(response.data);
			$('[data-toggle="tooltip"]').tooltip();
			despues;
		} else {
			$(elemento_resultado).html(html_message(response.error, 'danger'));
		}
	})
	.fail(function( jqXHR, textStatus ) {
		// alert(textStatus)
		$(elemento_resultado).html("Ocurri? un error durante el proceso de registro de asistencia, int&eacute;ntelo m&acute;s tarde.");
	})
	.always(function() {
		remove_loader();
	});
}

function saveAttandance(field){
	if($(field).is(':checked')){
		var path="/profesor/save_attandance";
		var tipo = $(field).data("tipo");
		var sesion = $(field).data("sesion");
		var taller = $(field).data("taller");
		var datos = {'taller_id':taller,'sesion_id':sesion,'tipo':tipo}
		// alert("Tipo: "+tipo+"; Taller:"+taller);
		attendance_ajax("/profesor/save_attendance", 
						"#day"+tipo+"_"+taller,
						datos);
	}
}