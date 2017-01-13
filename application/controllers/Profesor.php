<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Clase que contiene las acciones del profesor
 * @version 	: 1.0.0
 * @author      : Miguel Guagnelli
 * */
class Profesor extends CI_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->library('form_complete');
		$this->load->library('form_validation');
		$this->load->config('email');
	}

    /*     * *********P�gina de inicio
	 * @method: void index()
	 * @author: Miguel �ngel Gonz�lez Guagnelli
	 *
	 */

    function index() {
        $data["error"] = null;
        $this->load->model("Profesor_model", "profe");
                $sesiones = $this->profe->getSesionList();
		$data["sesiones"] = array_reverse($sesiones, true);
		$this->template->setTitle("Registro de asistencia");
                $main_contet = $this->load->view('profesor/index.tpl.php', $data, true);
		$this->template->setMainContent($main_contet);
		$this->template->getTemplate();
	}

    /*
     * Lista de talleres para el combo-box de selección filtrado por activos e inactivos
     * @params: id tipo de sesión 1 = activo, 2 = vacio, null = todas
     * @author: David Pérez Gordillo
     * * */

    function sesiones_ajax()
    {
        if ($this->input->is_ajax_request())
        {
            $this->load->model("Profesor_model", "profe");
            $sesiones = $this->profe->getSesionList();
            $mes = $this->input->post('mes');
            $sesiones = $this->profe->getSesionList($mes);
            $sesiones = array_reverse($sesiones, true);
            $i=0;
            $resultado = [];
            foreach ($sesiones as $key=>$value)
            {
                $resultado[$i]["texto"] = $value;
                $resultado[$i]["valor"] = $key; 
                $i++;
            }
            
            echo json_encode($resultado);
            exit();
        } else
        {
            redirect(site_url());
        }
    }    
        
    /*     * *********Lista de Talleres
	 * Funci�n que cgenera el listado de cursos/talleres/sesiones programadas
	 * @method: sesi�n
	 * @author: Miguel �ngel Gonz�lez Guagnelli
	 */
    function sesion() {
        if ($this->input->is_ajax_request()) {
            $resultado = array('resultado' => FALSE, 'error' => '', 'data' => '');
            $this->load->model("Profesor_model", "profe");
			$sesion_id = $this->input->post("campo");
			$datos["sesiones"] = $this->profe->getSesion($sesion_id);
			$datos["students"] = $this->profe->getStudents($sesion_id);
            $datos["num_students"]["inscritos"] = $this->profe->getCountInscritos($sesion_id); //obtiene el número de alumnos inscritos
            $datos["num_students"]["regulares"] = $this->profe->getCountRegulares($sesion_id); //obtiene el número de alumnos regulares
            if (is_null($datos["sesiones"])) {
                $resultado['error'] = "No se han encontrado resultados, seleccione otra opci&oacute;n por favor.";
            } else {
				$resultado['resultado'] = true;
                $resultado['data'] = $this->load->view('/profesor/sesion.tpl.php', $datos, TRUE); //cargar vista
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
	 * Funci�n genera la lista de asitencia
	 * @method: void attendance()
	 * @author: Miguel �ngel Gonz�lez Guagnelli
	 */
    public function attendance() {
        if ($this->input->is_ajax_request()) {
            $fields = $this->input->post(NULL, TRUE);
			// pr($fields);
            $this->load->model("Profesor_model", "profe");
			$datos["sesion"] = $this->profe->getSesion($fields["sesion_id"]);
			$datos["sesion"]["tipo"] = $fields["tipo"];
			$datos["sesion"]["taller_id"] = $fields["taller_id"];
            $datos["asistencia"] = $this->profe->getAsistencias($fields["taller_id"], $fields["tipo"]);
            $resultado['error'] = '';
			$resultado['resultado'] = true;
			// $resultado['data'] = "";
            $resultado['data'] = $this->load->view('/profesor/attendance_field.tpl.php', $datos, TRUE); //cargar vista

			echo json_encode($resultado);
			exit();
        } else {
			redirect(site_url());
		}
	}

	/***********Registro de asistencia
	 * Funci�n que permite al profesor registrar la asistencia de un asistente
	 * @method: void save_attendance()
	 * @author: Miguel �ngel Gonz�lez Guagnelli
	 */
    public function save_attendance() {
        if ($this->input->is_ajax_request()) {
            $fields = $this->input->post(NULL, TRUE);

            $this->load->model("Profesor_model", "profe");

			$datos["sesion"] = $this->profe->getSesion($fields["sesion_id"]);

			$resultado = array('resultado'=>FALSE, 'error'=>'Error inesperado.', 'data'=>'');
                        $tipo = "";
                        if($fields["tipo"] == "I"){
                            $tipo = "a_inicio";
                        }else if($fields["tipo"] == "F"){
                            $tipo = "a_fin";
                        }
//                        $tipo = array("I"=>"a_inicio","F"=>"a_fin")[$fields["tipo"]];

            if (strtotime(date("Y-m-d", strtotime($datos["sesion"][$tipo]))) != strtotime(date("Y-m-d"))) {
                $resultado['error'] = "Not today men, keep waiting 'tll the big day";
				// $resultado['error']='Hoy es '.date("Y-m-d").' no coincide con la fecha programa para la sesi�n '. $sesion['a_inicio'].', favor de verificarlo con el responsable del programa.';
            } else {
				$datos["sesion"]["tipo"] = $fields["tipo"];
				$datos["sesion"]["taller_id"] = $fields["taller_id"];

				//si la fecha de hoy es igual a la dse la sesi�n
				$tosave = array(
                    "as_asistencia" => $fields["tipo"],
                    "as_fecha" => date("Y-m-d"),
                    "taller_id" => $fields["taller_id"]
				);
				$tosave = $this->profe->saveAsistencias($tosave);
                if (is_array($tosave)) {
                    $datos["asistencia"] = $tosave;
                    $resultado['error'] = '';
					$resultado['resultado'] = true;
                    $resultado['data'] = $this->load->view('/profesor/attendance_field.tpl.php', $datos, TRUE); //cargar vista
					// $resultado['data'] = "ok";//cargar vista
                } else {
                    $resultado['error'] = "Not today men, you have the wrong info!";
				}
			}
			echo json_encode($resultado);
			exit();
        } else {
			redirect(site_url());
		}
	}

    /*     * *********Env�o de notificaci�n de evaluaciones
	 * Funci�n env�o de notificaciones a los usuarios que acreditaron el taller
	 * @method: void sendMessages()
	 * @author: Miguel �ngel Gonz�lez Guagnelli
	 */

    public function sendMessages() {
		$sesion_id = $this->input->post("session_id");
		$this->load->model("Profesor_model","profe");
		$datos["sesiones"] = $this->profe->getSesion($sesion_id);


		$this->load->library('My_phpmailer');

		//$mail->IsSMTP(); // establecemos que utilizaremos SMTP
        //$mail->Host = "172.16.23.18";

        $mailStatus = $this->my_phpmailer->phpmailerclass();
       
		$students = $this->profe->getStudents($sesion_id);

        $notificaciones = $errores = $asistenciasCompletas = array();

        foreach ($students as $id => $student) {
			$mail = $this->my_phpmailer->phpmailerclass();
			unset($student["asistencias"]);
			$datos["student"] = $student;
			$asistI = $this->profe->getAsistencias($student["taller_id"]);
            $asistF = $this->profe->getAsistencias($student["taller_id"], "F");
			// echo $student["usr_matricula"], "|agenda:$sesion_id" ;
			// pr($asistI);
			// pr($asistF);
            if (strtotime(date("Y-m-d", strtotime($datos["sesiones"]['a_inicio']))) == strtotime($asistI["as_fecha"]) &&
                    strtotime(date("Y-m-d", strtotime($datos["sesiones"]['a_fin']))) == strtotime($asistF["as_fecha"])) { ///Env�o de correo a usuarios que hallan tomado las 2 sesiones
                $email = $this->load->view('profesor/mail_evaluation.tpl.php', $datos, true);
				$mail->addAddress($student["usr_correo"], $student["fullname"]);
                //$mail->addBCC('jesusz.unam@gmail.com');
				$mail->Subject = 'Evaluaci�n de los talleres :: Talleres IMSS';
				$mail->msgHTML(utf8_decode($email));
				//$mail->AltBody = 'Mensaje de prueba';
				//$this->session->set_flashdata('success', $email);
                
				if (!$mail->send()) {
					$errores[] = $student;
					//pr($mail->ErrorInfo);
				}
               
                $asistenciasCompletas[$student["usr_matricula"]] = true;
				$notificaciones[$id] = $student;
			}
			unset($asistI);
			unset($asistF);
			unset($datos["student"]);

		}
        if (!empty($errores)) {
			$htmlError = "<p>Por alg&uacute;n control interno no se env&iacute;o correo a los siguientes usuarios:</p>";
			foreach ($errores as $keyE => $valueE) {
                $htmlError .= "- " . $valueE['fullname'] . "<br>";
			}
            $this->session->set_flashdata('success', $htmlError . "<br>Por favor verifique con el administrador.");
		}
        
        
        $emailStatus = $this->load->view('profesor/mail_lista_asistentes.tpl.php', $datos, true);
//        $mailStatus->addAddress('zurgcom@gmail.com'); //pruebas chris
        $mailStatus->addAddress('ingrid.soto@imss.gob.mx');
        $mailStatus->addBCC('jesusz.unam@gmail.com');
        $mailStatus->Subject = 'Lista de asistentes al taller :: Talleres IMSS';
        $mailStatus->msgHTML(utf8_decode($emailStatus));
        
        $datos["students"] = $students;
        $datos["asistenciasCompletas"] = $asistenciasCompletas;
        
        $this->load->helper('file');
        $tabla = $this->load->view('profesor/tabla_inscritos.tpl.php', $datos, TRUE);
        $pathTabla = './xls/listado'.rand(0, 10000).'.xls';
        write_file("$pathTabla", $tabla);
        
        $mailStatus->addAttachment($pathTabla, 'Lista Inscritos');
        
        $datos["mail_lista_asistencia"] = $mailStatus->send();
        
        unlink($pathTabla);
        
        $datos["notificaciones"] = $notificaciones;
        
        $template = $this->load->view('profesor/notificaciones.tpl.php', $datos, true);
		$this->template->setMainContent($template);
		$this->template->getTemplate();
	}
}
