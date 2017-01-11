<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Talleres extends CI_Controller {

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
		$salida=$this->load->view('usuario/usuarios.php',$output);
		//$this->template->setMainContent($salida);
		//$this->template->getTemplate();
	}


	function index()
	{
	    $crud = new grocery_CRUD();

	    $crud->set_table('rist_taller')
	        ->set_subject('Usuarios registrados a los talleres')

	        ->columns('taller_id','usr_matricula','agenda_id','cve_depto_adscripcion','cve_categoria','cve_delegacion','t_folio','t_fecha_registro','t_hash_constancia','t_estado')
	        ->display_as('taller_id','ID Taller')
	        ->display_as('usr_matricula','Usuario')
	        ->display_as('agenda_id','Taller')
	        ->display_as('cve_depto_adscripcion','Adscripción')
	        ->display_as('cve_categoria','Categoría')
	        ->display_as('cve_delegacion','Delegación')
	        ->display_as('t_folio','Folio')
	        ->display_as('t_fecha_registro','Fecha de registro')
	        ->display_as('t_hash_constancia','Hash constancia')
	        ->display_as('t_estado','Estado');


	    $crud->set_relation('usr_matricula','rist_usuario','{usr_nombre} {usr_paterno} {usr_materno}');
     	$crud->set_relation('agenda_id','rist_agenda','a_nombre');
     	$crud->set_relation('cve_depto_adscripcion','rist_departamentos','{cve_depto_adscripcion} - {nom_depto_adscripcion}');
     	$crud->set_primary_key('des_clave','rist_categoria');
     	$crud->set_relation('cve_categoria', 'rist_categoria', ' {des_clave} - {nom_categoria}');
     	$crud->set_relation('cve_delegacion','rist_delegacion','nom_delegacion');

	    $crud->fields('usr_matricula','agenda_id','cve_depto_adscripcion','cve_delegacion','cve_categoria','t_folio','t_fecha_registro','t_hash_constancia','t_estado');
	    $crud->required_fields('usr_matricula','agenda_id','cve_depto_adscripcion','cve_delegacion','t_estado');

	    
        //$crud->set_rules('t_estado', 'Estado', 'max_length[6]');
              	  
	    
        $crud->field_type('t_estado','dropdown',array('1' => 'Activo', '0' => 'Cancelado'));
        //$crud->field_type('a_desc','text');
        //$crud->unset_texteditor('a_desc','full_text');

        //$crud->order_by('usr_paterno','ASC');

        $crud->unset_edit_fields('usr_matricula','t_fecha_registro');
	 	//$crud->unset_delete();

	    $output = $crud->render();

	 	$this->template->setMainContent($this->load->view('Usuario/Usuarios.php',$output, TRUE));
	 	$this->template->getTemplate();

	    //$this->_example_output($output);
	}


	/*
	public function fechas_registro($pre,$inicio)
	{

	  $fecha1=strftime($pre);
	  $fecha2=strftime($inicio);
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

	  $fecha1=strftime($inicio);
	  $fecha2=strftime($fin);
	  if ( $fecha1 >= $fecha2)
	  {
	     

	      $this->form_validation->set_message('fechas_sesion', 'La {field} debe ser menor a la fecha de fin de la sesión. Por favor verifíquelo');
	      return  FALSE;
	  }
	  else
	  {
	       return TRUE;
	  }
	}
	*/

}