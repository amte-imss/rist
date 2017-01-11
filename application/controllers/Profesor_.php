<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Clase que contiene las acciones del profesor
 * @version 	: 1.0.0
 * @author      : Miguel Guagnelli
 **/

class Profesor extends CI_Controller {	
	
	public function __construct(){
		parent::__construct();
		$this->load->library('form_complete');
		$this->load->library('form_validation');
		$this->load->model("Profesor_model","profe");

	}
	
	/***********Página de inicio
	 * @method: void index()
	 * @author: Miguel Ángel González Guagnelli, Cambios de seguridad.
	 * 
	 */
	function index(){
		$data["error"]=null;
		
		$data["sesiones"] = $this->profe->getSesionList();
		$this->template->setTitle("Registro de asistencia");
		$main_contet = $this->load->view('profesor/index.tpl.php',$data,true);
		$this->template->setMainContent($main_contet);		
		$this->template->getTemplate();
	}
	
	function sesion(){
		if($this->input->is_ajax_request()){
			$resultado = array('resultado'=>FALSE, 'error'=>'', 'data'=>'');
			//pr($datos);
			//$this->load->model("Profesor_model","profe");
			$sesion_id = $this->input->post("campo");
			
			$datos["sesiones"] = $this->profe->getSesion($sesion_id);			
			$datos["students"] = $this->profe->getStudents($sesion_id);
			

			if(is_null($datos["sesiones"])){
				$resultado['error'] = "No se han encontrado resultados, seleccione otra opción por favor.";
			}else{
				$resultado['resultado'] = true;
				$resultado['data'] = $this->load->view('/profesor/sesion.tpl.php', $datos, TRUE);//cargar vista
			}
			// pr($resultado);							
			// echo "ok world ";
			echo json_encode($resultado);
			exit();
		}else{
			redirect(site_url());
		}
	}
<<<<<<< .mine
	
=======
	
	public function attendance(){
		if($this->input->is_ajax_request()){
			$fields = $this->input->post(NULL,TRUE);
			// pr($fields);
			$this->load->model("Profesor_model","profe");
			$datos["sesion"] = $this->profe->getSesion($fields["sesion_id"]);
			$datos["sesion"]["tipo"] = $fields["tipo"];
			$datos["sesion"]["taller_id"] = $fields["taller_id"];
			$datos["asistencia"] = $this->profe->getAsistencias($fields["taller_id"],$fields["tipo"]);
			$resultado['error']='';
			$resultado['resultado'] = true;
			// $resultado['data'] = "";
			$resultado['data'] = $this->load->view('/profesor/attendance_field.tpl.php', 
						$datos, 
						TRUE);//cargar vista
			
			echo json_encode($resultado);
			exit();
		}else{
			redirect(site_url());
		}
	}
	
	public function save_attendance(){
		if($this->input->is_ajax_request()){
			$fields = $this->input->post(NULL,TRUE);
			
			$this->load->model("Profesor_model","profe");
			
			$datos["sesion"] = $this->profe->getSesion($fields["sesion_id"]);
			
			$resultado = array('resultado'=>FALSE, 'error'=>'Error inesperado.', 'data'=>'');
			$tipo = array("I"=>"a_inicio","F"=>"a_fin")[$fields["tipo"]];
			
			if (strtotime($datos["sesion"][$tipo]) != strtotime(date("Y-m-d"))){
				$resultado['error']="Not today men, keep waiting 'tll the big day";
				// $resultado['error']='Hoy es '.date("Y-m-d").' no coincide con la fecha programa para la sesión '. $sesion['a_inicio'].', favor de verificarlo con el responsable del programa.';
			}else{
				$datos["sesion"]["tipo"] = $fields["tipo"];
				$datos["sesion"]["taller_id"] = $fields["taller_id"];
				
				//si la fecha de hoy es igual a la dse la sesión
				$tosave = array(
					"as_asistencia"=>$fields["tipo"],
					"as_fecha"=>date("Y-m-d"),
					"taller_id"=>$fields["taller_id"]
				);
				$tosave = $this->profe->saveAsistencias($tosave);
				if(is_array($tosave)){
					$datos["asistencia"]=$tosave;
					$resultado['error']='';
					$resultado['resultado'] = true;
					$resultado['data'] = $this->load->view('/profesor/attendance_field.tpl.php', 
								$datos, 
								TRUE);//cargar vista
					// $resultado['data'] = "ok";//cargar vista
				}else{
					$resultado['error']="Not today men, you have the wrong info!";
				}
			}			
			echo json_encode($resultado);
			exit();	
		}else{
			redirect(site_url());
		}
	}
>>>>>>> .r82
}