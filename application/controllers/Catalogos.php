<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
		$this->load->library('form_validation');
	}

    public function _example_output($output = null) {
        $salida = $this->load->view('agenda/agenda.php', $output);
		//$this->template->setMainContent($salida);
		//$this->template->getTemplate();
	}

    function index() {
	    $crud = new grocery_CRUD();
//            $fecha_valida_unix = time() + ( 60 * 60 * 24);
//            $fecha_valida = date('Y-m-d', $fecha_valida_unix);
	    $crud->set_table('rist_agenda')
                ->where('a_tipo', '1')
//                ->where('a_estado','1')
//                ->where('a_inicio >', $fecha_valida)
	        ->set_subject('Taller')
                ->columns('a_nombre', 'a_registro', 'a_inicio', 'a_fin', 'a_evaluacion_inicio', 'a_evaluacion_fin', 'a_cupo', 'a_desc', 'a_estado')
                ->display_as('a_nombre', 'Nombre(Presentación a usuario)')
                ->display_as('a_registro', 'Fecha de inicio de registro')
                ->display_as('a_inicio', 'Fecha inicial de taller')
                ->display_as('a_fin', 'Fecha final de taller')
                ->display_as('a_evaluacion_inicio', 'Fecha inicial de evaluación')
                ->display_as('a_evaluacion_fin', 'Fecha final de evaluación')
                ->display_as('a_cupo', 'Cupo máximo')
                ->display_as('a_estado', 'Estado')
                ->display_as('a_desc', 'Descripción');

        $crud->fields('a_nombre', 'a_registro', 'a_inicio', 'a_fin', 'a_evaluacion_inicio', 'a_evaluacion_fin', 'a_cupo', 'a_desc', 'a_estado', 'a_tipo');
        $crud->required_fields('a_nombre', 'a_registro', 'a_inicio', 'a_fin', 'a_evaluacion_inicio', 'a_evaluacion_fin', 'a_cupo', 'a_estado');

        $crud->set_rules('a_cupo', '"Cupo"', 'integer|trim|max_length[3]');
        $crud->set_rules('a_registro', 'Fecha de inicio de registro', 'callback_fechas_registro[' . $this->input->post('a_inicio') . ']');
        $crud->set_rules('a_inicio', 'Fecha de inicio del taller', 'callback_fechas_sesion[' . $this->input->post('a_fin') . ']');
        $crud->set_rules('a_fin', 'Fecha final del taller', 'callback_fechas_evaluacion_i[' . $this->input->post('a_evaluacion_inicio') . ']');
        $crud->set_rules('a_evaluacion_inicio', 'Fecha inicial de evaluación', 'callback_fechas_evaluacion_f[' . $this->input->post('a_evaluacion_fin') . ']');

	    $crud->field_type('a_tipo', 'hidden', '1');
        $crud->field_type('a_estado', 'dropdown', array('1' => 'Activo', '0' => 'Inactivo'));
        $crud->field_type('a_desc', 'text');
        $crud->unset_texteditor('a_desc', 'full_text');

        $crud->order_by('a_inicio', 'desc');

	    $output = $crud->render();

        $this->template->setMainContent($this->load->view('agenda/agenda.php', $output, TRUE));
            $this->template->getTemplate();

	    //$this->_example_output($output);
	}

    public function fechas_registro($pre, $inicio) {


	  //$fecha1=strftime($pre);
	  //$fecha2=strftime($inicio);
        $fecha1 = strtotime($pre);
        $fecha2 = strtotime($inicio);
          
	  //$fecha1=DateTime::createFromFormat('d/m/Y', $pre);
	  //$fecha2=DateTime::createFromFormat('d/m/Y', $inicio);

        if ($fecha1 >= $fecha2) {


	      $this->form_validation->set_message('fechas_registro', 'La {field} debe ser menor a la fecha de inicio del taller. Por favor verifíquelo');
	      return  FALSE;
        } else {
	       return TRUE;
	  }
	}

    public function fechas_sesion($inicio, $fin) {

	  //$fecha1=strftime($inicio);
	  //$fecha2=strftime($fin);
	  //$fecha1=DateTime::createFromFormat('d/m/Y', $inicio);
	  //$fecha2=DateTime::createFromFormat('d/m/Y', $fin);
        $fecha1 = strtotime($inicio);
        $fecha2 = strtotime($fin);
        if ($fecha1 >= $fecha2) {


	      $this->form_validation->set_message('fechas_sesion', 'La {field} debe ser menor a la fecha de fin de la sesión. Por favor verifíquelo');
	      return  FALSE;
        } else {
	       return TRUE;
	  }
	}

    public function fechas_evaluacion_i($inicio_eva, $fin_eva) {

	  //$fecha1=strftime($inicio_eva);
	  //$fecha2=strftime($fin_eva);
        $fecha1 = strtotime($inicio_eva);
        $fecha2 = strtotime($fin_eva);
	  //$fecha1=DateTime::createFromFormat('d/m/Y', $inicio_eva);
	  //$fecha2=DateTime::createFromFormat('d/m/Y', $fin_eva);
        if ($fecha1 >= $fecha2) {


	      $this->form_validation->set_message('fechas_evaluacion_i', 'La {field} debe ser menor a la fecha inicial de la evaluación. Por favor verifíquelo');
	      return  FALSE;
        } else {
	       return TRUE;
	  }
	}

    public function fechas_evaluacion_f($inicio_eva, $fin_eva) {

	  //$fecha1=strftime($inicio_eva);
	  //$fecha2=strftime($fin_eva);
        $fecha1 = strtotime($inicio_eva);
        $fecha2 = strtotime($fin_eva);
	  //$fecha1=DateTime::createFromFormat('d/m/Y', $inicio_eva);
	  //$fecha2=DateTime::createFromFormat('d/m/Y', $fin_eva);
        if ($fecha1 >= $fecha2) {


	      $this->form_validation->set_message('fechas_evaluacion_f', 'La {field} debe ser menor a la fecha final de la evaluación. Por favor verifíquelo');
	      return  FALSE;
        } else {
	       return TRUE;
	  }
	}

    /**
     * Grud para rist_categoria
     * @author Christian Garcia
     */
    public function categorias() {
        $crud = new grocery_CRUD();
        $crud->set_table('rist_categoria')
                ->display_as("nom_categoria", "Categoría")
                ->display_as("des_clave", "Clave")
                ->display_as("cve_tipo_categoria", "Clave Tipo")
                ->display_as("nom_tipo_cat", "Tipo");

        $output = $crud->render();

        $this->template->setMainContent($this->load->view('catalogos/categorias.php', $output, TRUE));
        $this->template->getTemplate();
    }

    /**
     * Grud para rist_departamentos
     * @author Christian Garcia
     */
    public function departamentos() {
        $crud = new grocery_CRUD();
        $crud->set_table('rist_departamentos');

        $output = $crud->render();

        /* uso la misma vista que categorias ya que no solo sirve para mostrar el grud */
        $this->template->setMainContent($this->load->view('catalogos/categorias.php', $output, TRUE));
        $this->template->getTemplate();
    }
    
    /**
     * Grud para rist_delegacion
     * @author Christian Garcia
     */
    public function delegaciones() {
        $crud = new grocery_CRUD();
        $crud->set_table('rist_delegacion');

        $output = $crud->render();

        /* uso la misma vista que categorias ya que no solo sirve para mostrar el grud */
        $this->template->setMainContent($this->load->view('catalogos/categorias.php', $output, TRUE));
        $this->template->getTemplate();
    }
}
