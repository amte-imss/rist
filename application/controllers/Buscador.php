<?php 	defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que gestiona las publicaciones
 * @version 	: 1.0.0
 * @autor 		: Pablo José D.
 */
class Buscador extends CI_Controller {
	/**
     * Carga de clases para el acceso a base de datos y para la creación de elementos del formulario
	 * @access 		: public
	 * @modified 	:
     */
	var $sessionData;
	public function __construct() {
            parent::__construct();
            $this->load->library('form_complete');
            $this->load->model('Buscador_model', 'buscador');

            $this->config->load('general');
            $this->tipo_sesion = $this->config->item('tipo_sesion');

        }

    /**
     * Método que carga el formulario de búsqueda y el listado de asistencia a talleres.
     * @autor 		: Pablo José D.
	 * @modified 	:
	 * @access 		: public
     */
	public function index()	{

		$sesiones = $this->buscador->listado_sesiones(array('conditions'=>array('a_estado'=>1)));
		$categoria = $this->buscador->listado_categoria();
		$delegacion = $this->buscador->listado_delegacion();
		$adscripcion = $this->buscador->listado_adscripcion();
		$tipo = $this->tipo_sesion;

        $datos['sesiones'] = dropdown_options($sesiones['data'], 'agenda_id', 'a_nombre');
		$datos['categoria'] = dropdown_options($categoria['data'], 'des_clave', 'nom_categoria');
		$datos['delegacion'] = dropdown_options($delegacion['data'], 'cve_delegacion', 'nom_delegacion');
		$datos['adscripcion'] = dropdown_options($adscripcion['data'], 'cve_depto_adscripcion', 'nom_depto_adscripcion');
		$datos['tipo'] = dropdown_options($tipo, 'id', 'text');


		$datos['order_columns'] = array('usr_matricula'=>'Matrícula', 'fullname'=>'Nombre usuario');



		$datos[''] = null;

		$this->template->setMainContent( $this->load->view('buscador/formulario', $datos, TRUE) );
		$this->template->getTemplate();
	}

        /*
         *  Cambio de tipo de sesiones combo box
         *  Este método regresa las sesiones filtrando por tipo (presenciales o en linea) para llenado dinámico de combo box          
         */
        public function get_sesiones_ajax(){
            if($this->input->is_ajax_request()){
                $request = $this->input->post();
                // Si "tipo" es nulo se puden todos, en caso contrario se discrimina la petición
                if($request["tipo"] == null){
                    $sesiones = $this->buscador->listado_sesiones(array('conditions'=>array('a_estado'=>1 )));
                }else{
                    $sesiones = $this->buscador->listado_sesiones(array('conditions'=>array('a_estado'=>1, 'a_tipo' => $request['tipo'] )));
                }
                echo json_encode($sesiones);
            }
        }
        
	/********************************************* Inicio paginación ajax ************************************************/
	/**
     * Método que a través de una petición ajax muestra un listado de publicaciones, estos pueden ser filtrados de acuerdo a parámetros seleccionados
     * @autor 		: Jesús Díaz P.
	 * @modified 	:
	 * @access 		: public
	 * @param 		: integer - $current_row - Registro actual, donde iniciará la visualización de registros
     */
	public function get_data_ajax($current_row=null){
		if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
			if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post

                                //aqui va la nueva conexion a la base de datos del buscador

                                //Se guarda lo que se busco asi como la matricula de quien realizo la busqueda

				$datos_busqueda = $this->input->post(null, TRUE); //Datos del formulario se envían para generar la consulta

				$datos_busqueda['current_row'] = (isset($current_row) && !empty($current_row)) ? $current_row : 0; //Registro actual, donde inicia la visualización de registros
				$data_sesiones['tipo']=$this->input->post('tipo');
				$data_sesiones['sesiones'] = $this->buscador->getSesion($datos_busqueda);
				$data_sesiones['alumnos'] = $this->buscador->listado($datos_busqueda); //Obtener datos de la publicación
				//pr($data_sesiones);
				$data_sesiones['current_row'] = $datos_busqueda['current_row'];
				$data_sesiones['per_page'] = $this->input->post('per_page'); //Número de registros a mostrar por página
				if($data_sesiones['alumnos']['total'] > 0){

					$this->listado_resultado($data_sesiones, array('form_recurso'=>'#form_buscador', 'elemento_resultado'=>'#listado_resultado')); //Generar listado en caso de obtener datos
				} else {
					echo data_not_exist('No han sido encontrados datos con los criterios seleccionados. <script> $("#btn_export").hide(); </script>'); //Mostrar mensaje de datos no existentes
				}
			}
		} else {
			redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
		}
	}

	public function export_data(){
		if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
			$datos_busqueda = $this->input->post(null, TRUE); //Datos del formulario se envían para generar la consulta

			$datos_busqueda['export'] = TRUE;
			$data_sesiones['tipo']=$this->input->post('tipo');
			$data_sesiones['sesiones'] = $this->buscador->getSesion($datos_busqueda);
			$data_sesiones['alumnos'] = $this->buscador->listado($datos_busqueda); //Obtener datos de la publicación

			if($data_sesiones['alumnos']['total'] > 0){
				//$this->listado_resultado($data_sesiones, array('form_recurso'=>'#form_buscador', 'elemento_resultado'=>'#listado_resultado')); //Generar listado en caso de obtener datos
				$filename="Export_".date("d-m-Y_H-i-s").".xls";
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=$filename");
				header("Pragma: no-cache");
				header("Expires: 0");
				echo $this->load->view('buscador/resultado_busqueda', $data_sesiones, TRUE);
			} else {
				echo data_not_exist('No han sido encontrados datos con los criterios seleccionados. <script> $("#btn_export").hide(); </script>'); //Mostrar mensaje de datos no existentes
			}
		}
	}


	/**
        * Método que imprime el listado de publicaciones, se agrega paginación.
        * @autor 		: Jesús Díaz P.
            * @modified 	:
            * @access 		: private
            * @param 		: mixed[] $data Arreglo de publicaciones y de información necesaria para generar los links para la paginación
            * @param 		: mixed[] $form Arreglo asociativo con 2 elementos.
            *					form_recurso -> identificador del formulario que contiene los elementos de filtración
            *					elemento_resultado -> identificador del elemento donde se mostrará el listado
        */
	private function listado_resultado($data, $form){
		$pagination = $this->template->pagination_data_buscador($data); //Crear mensaje y links de paginación
		$links = "<div class='col-sm-5 dataTables_info' style='line-height: 50px;'>".$pagination['total']."</div>
				<div class='col-sm-7 text-right'>".$pagination['links']."</div>";
		echo $links.$this->load->view('buscador/resultado_busqueda', $data, TRUE).$links.'
			<script>
			$("ul.pagination li a").click(function(event){
		        data_ajax(this, "'.$form['form_recurso'].'", "'.$form['elemento_resultado'].'");
		        event.preventDefault();
		    });
			</script>';
	}

	/**
        * Método que imprime el listado de publicaciones, se agrega paginación. No utiliza view, solo hace uso de elemento 'table'
        * @autor 		: Jesús Díaz P.
            * @modified 	:
            * @access 		: private
            * @param 		: mixed[] $data Arreglo de publicaciones y de información necesaria para generar los links para la paginación
            * @param 		: mixed[] $form Arreglo asociativo con 2 elementos.
            *					form_recurso -> identificador del formulario que contiene los elementos de filtración
            *					elemento_resultado -> identificador del elemento donde se mostrará el listado
        */
	private function template_ajax($datos, $form){
		$pagination = $this->template->pagination_data_buscador($datos); //Crear mensaje y links de paginación
		$links = "<div class='col-sm-5 dataTables_info' style='line-height: 50px;'>".$pagination['total']."</div>
				<div class='col-sm-7 text-right'>".$pagination['links']."</div>";
		$template = array('table_open' => '<table class="table table-striped table-bordered dataTable no-footer" cellspacing="0">'); //Creación de tabla con datos
		$this->table->set_template($template); //Modifcar template de la tabla
		$this->table->set_heading($datos['columns']); //Encabezado de columnas
		foreach ($datos['data'] as $key => $value) {
			$this->table->add_row($value);
		}

		echo $links."<div class='col-sm-12 center'>".$this->table->generate()."</div>".$links.'
			<script>
			$("ul.pagination li a").click(function(event){
		        data_ajax(this, "'.$form['form_recurso'].'", "'.$form['elemento_resultado'].'");
		        event.preventDefault();
		    });
			</script>';
	}


}
