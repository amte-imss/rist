<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asistencia extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
		$this->load->library('form_validation');
	}

	/*public function _example_output($output = null)
	{
		$salida=$this->load->view('usuario/usuarios.php',$output);
		//$this->template->setMainContent($salida);
		//$this->template->getTemplate();
	}*/


	function index()
	{
	    $crud = new grocery_CRUD();

	    $crud->set_table('rist_asistencia')
	        ->set_subject('Asistencia')
                ->columns('taller_id', 'as_fecha', 'as_asistencia')
	        //->display_as('as_id','Identificador')
                ->display_as('taller_id', 'Taller')
                ->display_as('as_fecha', 'Fecha de asistencia')
                ->display_as('as_asistencia', 'Asistencia');

	    //$crud->set_relation_n_n('user', 'rist_taller', 'rist_usuario', 'taller_id', 'usr_matricula', 'usr_nombre');
	    //$crud->set_relation('taller_id','rist_taller','agenda_id');
	 
        $crud->fields('taller_id', 'as_fecha', 'as_asistencia');
	    //$crud->required_fields('taller_id','as_fecha','as_asistencia');

        $crud->field_type('as_asistencia', 'dropdown', array('1' => 'Asistencia Inicial', '2' => 'Asistencia Final'));
        //$crud->field_type('a_desc','text');
        //$crud->unset_texteditor('a_desc','full_text');

        //$crud->order_by('usr_paterno','ASC');

	    $output = $crud->render();

        $this->template->setMainContent($this->load->view('Usuario/Usuarios.php', $output, TRUE));
	 	$this->template->getTemplate();

	    //$this->_example_output($output);
	}

}
