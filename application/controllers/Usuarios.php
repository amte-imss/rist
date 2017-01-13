<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
		$this->load->library('form_validation');
	}

    public function _example_output($output = null) {
        $salida = $this->load->view('usuario/usuarios.php', $output);
		//$this->template->setMainContent($salida);
		//$this->template->getTemplate();
	}

    function index() {
	    $crud = new grocery_CRUD();

	    $crud->set_table('rist_usuario')
	        ->set_subject('Usuario')
                ->columns('usr_matricula', 'usr_nombre', 'usr_paterno', 'usr_materno', 'usr_correo')
                ->display_as('usr_matricula', 'Matrícula')
                ->display_as('usr_nombre', 'Nombre (s)')
                ->display_as('usr_paterno', 'Apellido paterno')
                ->display_as('usr_materno', 'Apellido materno')
                ->display_as('usr_correo', 'Correo electrónico');

	 
	    
        $crud->fields('usr_matricula', 'usr_nombre', 'usr_paterno', 'usr_materno', 'usr_correo');
        $crud->required_fields('usr_matricula', 'usr_nombre', 'usr_paterno', 'usr_materno', 'usr_correo');
     
        $crud->set_rules('usr_matricula', 'Matrícula', 'max_length[20]');
        $crud->set_rules('usr_nombre', 'Nombre', 'max_length[20]');
        $crud->set_rules('usr_paterno', 'Apellido paterno', 'max_length[50]');
        $crud->set_rules('usr_materno', 'Apellido materno', 'max_length[50]');
        $crud->set_rules('usr_correo', 'Correo electrónico', 'max_length[50]');


       	  
	    
        //$crud->field_type('a_estado','dropdown',array('1' => 'Activo', '0' => 'Inactivo'));
        //$crud->field_type('a_desc','text');
        //$crud->unset_texteditor('a_desc','full_text');

        $crud->order_by('usr_paterno', 'ASC');

	    $output = $crud->render();

        $this->template->setMainContent($this->load->view('Usuario/Usuarios.php', $output, TRUE));
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

    /**
     * Grud para admin
     * @author Christian Garcia
     * @version 6 enero 2016
     * */
    public function admin() {
        $crud = new grocery_CRUD();
        $crud->set_table('rist_admin')
                ->display_as('usr_matricula', 'Matricula')
                ->display_as('usr_nombre', 'Nombre')
                ->display_as('usr_paterno', 'Ap. paterno')
                ->display_as('usr_materno', 'Ap. materno')
                ->display_as('usr_correo', 'email')
                ->display_as('usr_activo', 'Activo')
                ->display_as('usr_passwd', 'Password')
                ->display_as('usr_rol_admin', 'Rol');
        $output = $crud->render();

        $this->template->setMainContent($this->load->view('Usuario/Admins.php', $output, TRUE));
        $this->template->getTemplate();
    }

}
