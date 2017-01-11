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
		$this->load->config('email');
	}
	
	/***********Página de inicio
	 * @method: void index()
	 * @author: Miguel Ángel González Guagnelli
	 * 
	 */
	function index(){
		$data["error"]=null;
		$this->load->model("Profesor_model","profe");
		$data["sesiones"] = $this->profe->getSesionList();
		$this->template->setTitle("Registro de asistencia");
		$main_contet = $this->load->view('profesor/index.tpl.php',$data,true);
		$this->template->setMainContent($main_contet);		
		$this->template->getTemplate();
	}
	
	/***********Lista de Talleres
	 * Función que cgenera el listado de cursos/talleres/sesiones programadas
	 * @method: sesión
	 * @author: Miguel Ángel González Guagnelli
	 */
	function sesion(){
		if($this->input->is_ajax_request()){
			$resultado = array('resultado'=>FALSE, 'error'=>'', 'data'=>'');
			
			$this->load->model("Profesor_model","profe");
			$sesion_id = $this->input->post("campo");
			
			$datos["sesiones"] = $this->profe->getSesion($sesion_id);			
			$datos["students"] = $this->profe->getStudents($sesion_id);
			
			if(is_null($datos["sesiones"])){
				$resultado['error'] = "No se han encontrado resultados, seleccione otra opción por favor.";
			}else{
				$resultado['resultado'] = true;
				$resultado['data'] = $this->load->view('/profesor/sesion.tpl.php', 
							$datos, 
							TRUE);//cargar vista
			}
			// pr($resultado);							
			// echo "ok world ";
			echo json_encode($resultado);
			exit();
		}else{
			redirect(site_url());
		}
	}
	
	/***********Lista de asistencia
	 * Función genera la lista de asitencia
	 * @method: void attendance()
	 * @author: Miguel Ángel González Guagnelli
	 */
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
	
	/***********Registro de asistencia
	 * Función que permite al profesor registrar la asistencia de un asistente
	 * @method: void save_attendance()
	 * @author: Miguel Ángel González Guagnelli
	 */
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
	
	/***********Envío de notificación de evaluaciones
	 * Función envío de notificaciones a los usuarios que acreditaron el taller
	 * @method: void sendMessages()
	 * @author: Miguel Ángel González Guagnelli
	 */
	public function sendMessages(){
		$sesion_id = $this->input->post("session_id");
		
		$this->load->model("Profesor_model","profe");
		$datos["sesiones"] = $this->profe->getSesion($sesion_id);
		
		
		$this->load->library('My_phpmailer');
		
		//$mail->IsSMTP(); // establecemos que utilizaremos SMTP
        //$mail->Host = "172.16.23.18";
		
		$students = $this->profe->getStudents($sesion_id);
		
		$notificaciones = $errores = array();
		
		foreach($students as $id=>$student){
			$mail = $this->my_phpmailer->phpmailerclass();
			unset($student["asistencias"]);
			$datos["student"] = $student;
			$asistI = $this->profe->getAsistencias($student["taller_id"]);
			$asistF = $this->profe->getAsistencias($student["taller_id"],"F");
			// echo $student["usr_matricula"], "|agenda:$sesion_id" ;
			// pr($asistI);
			// pr($asistF);
			if(	strtotime($datos["sesiones"]['a_inicio']) == strtotime($asistI["as_fecha"]) &&
				strtotime($datos["sesiones"]['a_fin']) == strtotime($asistF["as_fecha"])){ ///Envío de correo a usuarios que hallan tomado las 2 sesiones
				$email = $this->load->view('profesor/mail_evaluation.tpl.php',$datos,true);
				$mail->addAddress($student["usr_correo"], $student["fullname"]);
				$mail->addBCC('jesusz.unam@gmail.com');
				$mail->Subject = 'Evaluación de los talleres :: Talleres IMSS';
				$mail->msgHTML(utf8_decode($email));
				//$mail->AltBody = 'Mensaje de prueba';
				//$this->session->set_flashdata('success', $email);
				if (!$mail->send()) { 
					$errores[] = $student;
					//pr($mail->ErrorInfo);
				}
				$notificaciones[$id] = $student;
			}
			unset($asistI);
			unset($asistF);
			unset($datos["student"]);
			
		}
		if(!empty($errores)){
			$htmlError = "<p>Por alg&uacute;n control interno no se env&iacute;o correo a los siguientes usuarios:</p>";
			foreach ($errores as $keyE => $valueE) {
				$htmlError .= "- ".$valueE['fullname']."<br>";
			}
			$this->session->set_flashdata('success', $htmlError."<br>Por favor verifique con el administrador.");
		}
		$datos["notificaciones"]=$notificaciones ;
		$template = $this->load->view('profesor/notificaciones.tpl.php',$datos,true);
		$this->template->setMainContent($template);
		$this->template->getTemplate();
	}
}
