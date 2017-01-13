<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CatalogosDistancia extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
		$this->load->library('form_validation');
	}


	public function _example_output($output = null)
	{
		$salida=$this->load->view('agenda/agenda.php',$output);
		//$this->template->setMainContent($salida);
		//$this->template->getTemplate();
	}


	function index()
	{
	    $crud = new grocery_CRUD();

	    $crud->set_table('rist_agenda')
	    	->where('a_tipo','2')
	        ->set_subject('Sesión en línea')
	        ->columns('a_nombre','a_registro','a_registro_fin','a_inicio','a_hr_inicio','a_fin','a_hr_fin','a_duracion','a_desc','a_liga','a_estado','texto_liga','id_conferencia')
	        ->display_as('a_nombre','Nombre(Presentación a usuario)')
	        ->display_as('a_registro','Fecha de inicio de registro')
	        ->display_as('a_registro_fin','Fecha fin de registro')
	        ->display_as('a_inicio','Fecha inicial de taller')
	        ->display_as('a_hr_inicio','Hora inicial de taller')
	        ->display_as('a_fin','Fecha final de taller')
	        ->display_as('a_hr_fin','Hora final de taller')
	        ->display_as('a_duracion','Duración')
	        ->display_as('a_estado','Estado')
	        ->display_as('a_liga','Liga de la sesión')
	        ->display_as('texto_liga','Texto de la liga')
	        ->display_as('id_conferencia','ID conferencia')
	        ->display_as('a_desc','Descripción');



	    $crud->fields('a_nombre','a_registro','a_tipo','a_registro_fin','a_inicio','a_hr_inicio','a_fin','a_hr_fin','a_duracion','a_desc','a_liga','a_estado','texto_liga','id_conferencia');
	    $crud->required_fields('a_nombre','a_registro','a_registro_fin','a_inicio','a_hr_inicio','a_fin','a_hr_fin','a_estado');


		$crud->set_rules('a_registro', 'Fecha de inicio de registro', 'callback_fechas_registros['.$this->input->post('a_registro_fin').']');/*, 'callback_fechas_registros['.$this->input->post('a_registro_fin').']'*/
		//$crud->set_rules('a_registro_fin', 'Fecha de fin de registro', 'callback_fechas_registro['.$this->input->post('a_inicio').']');/*, 'callback_fechas_registro['.$this->input->post('a_inicio').']'*/
        $crud->set_rules('a_inicio', 'Fecha de inicio del taller', 'callback_fechas_sesion['.$this->input->post('a_fin').']');
        $crud->set_rules('a_fin', 'Fecha final del taller', 'callback_fechas_sesion_fin['.$this->input->post('a_inicio').']');


	    $crud->add_action('Notificacion',asset_url().'/grocery_crud/themes/flexigrid/css/images/message.png','','', array($this,'notificar_link'));


        $crud->field_type('a_tipo', 'hidden', '2');

        $crud->field_type('a_estado','dropdown',array('1' => 'Activo', '0' => 'Inactivo'));
        $crud->field_type('a_desc','text');
        $crud->unset_texteditor('a_desc','full_text');

        $crud->order_by('a_inicio','desc');
		//$this->output->enable_profiler(TRUE);
	    $output = $crud->render();

	 	$this->template->setMainContent($this->load->view('agenda/agenda.php',$output, TRUE));
	 	$this->template->getTemplate();

	    //$this->_example_output($output);
	}

	public function fechas_registros($pre,$prefin)
	{

		//pr($pre);
		//pr($prefin);

	  $fecha1=strtotime($pre);
	  $fecha2=strtotime($prefin);

	  //$fecha1=  DateTime::createFromFormat('d/m/Y', $pre);
	  //$fecha2=DateTime::createFromFormat('d/m/Y', $prefin);

	  //pr($fecha1);
	  //pr($fecha2);
	  //exit();
	  if ( $fecha1 >= $fecha2)
	  {


	      $this->form_validation->set_message('fechas_registros', 'La {field} debe ser menor a la fecha final del registro del taller. Por favor verifíquelo');
	      return  FALSE;
	  }
	  else
	  {
	       return TRUE;
	  }
	}


	public function fechas_registro($prefin,$inicio)
	{


	  $fecha1=strtotime($prefin);
	  $fecha2=strtotime($inicio);
	  //$fecha1=DateTime::createFromFormat('d/m/Y', $prefin);
	  //$fecha2=DateTime::createFromFormat('d/m/Y', $inicio);

	  if ( $fecha1 >= $fecha2)
	  {


	      $this->form_validation->set_message('fechas_registro', 'La {field} debe ser menor a la fecha de inicio del taller. Por favor verifíquelo');
	      return  FALSE;
	  }
	  else
	  {
	       return TRUE;
	  }
	}


	public function fechas_sesion($inicio,$fin)
	{

	  $fecha1=strtotime($inicio);
  	  $fecha2=strtotime($fin);
	  //$fecha1=DateTime::createFromFormat('d/m/Y', $inicio);
	  //$fecha2=DateTime::createFromFormat('d/m/Y', $fin);

	  if ( $fecha1 > $fecha2)
	  {


	      $this->form_validation->set_message('fechas_sesion', 'La {field} debe ser menor a la fecha de fin de la sesión. Por favor verifíquelo');
	      return  FALSE;
	  }
	  else
	  {
	       return TRUE;
	  }
	}

	public function fechas_sesion_fin($fin,$inicio)
	{

		$fecha1=strtotime($fin);
    	$fecha2=strtotime($inicio);
	  //$fecha1=DateTime::createFromFormat('d/m/Y', $fin);
	  //$fecha2=DateTime::createFromFormat('d/m/Y', $inicio);
	  if ( $fecha1 < $fecha2)
	  {


	      $this->form_validation->set_message('fechas_sesion_fin', 'La {field} debe ser mayor a la fecha inicial del taller. Por favor verifíquelo');
	      return  FALSE;
	  }
	  else
	  {
	       return TRUE;
	  }
	}



	function notificar_link($primary_key , $row)
	{

		return site_url('catalogosdistancia/correoactualizacion/'.$row->agenda_id);
    }


    public function correoactualizacion($sesion_id){


		$this->load->model("Profesor_model","profe");
		$datos["sesiones"] = $this->profe->getSesion($sesion_id);


		$this->load->library('My_phpmailer');




		$notificaciones = $errores = array();
		$this->load->model('Registro_model','mod_registro');
        $students = $this->mod_registro->getStudents($sesion_id);
        $agendaData = $this->mod_registro->getSesion(array('conditions'=>array('agenda_id'=>$sesion_id))); //Datos de la fecha programada



        if(isset($students) && !empty($students)){
			foreach($students as $id=>$student){
				$mail = $this->my_phpmailer->phpmailerclass();
				$datos["student"] = $student;
				$datose= array('usuario'=>$student, 'agenda'=>$agendaData);

				$email = $this->load->view('template/email/enviar_actualizacion.tpl.php',$datose,true);

				$mail->addAddress($student["usr_correo"], $student["fullname"]);
				$mail->Subject = 'SESIONES EN LINEA  ::  IMSS';
				$mail->msgHTML(utf8_decode($email));
				if (!$mail->send()) {
						$errores[] = $student;
						//pr($mail->ErrorInfo);
				  }
				  $notificaciones[$id] = $student;
				}
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
		$template = $this->load->view('template/email/enviar_notificacion_actualizacion.tpl.php',$datos,true);
		$this->template->setMainContent($template);
		$this->template->getTemplate();
	}

}
