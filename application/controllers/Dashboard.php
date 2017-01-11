<?php   defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que gestiona EL DASHBOARD
 * @version 	: 1.0.0
 * @autor 		: Pablo José D.
 */

class Dashboard extends CI_Controller {
    /**
     * Carga de clases para el acceso a base de datos y obtencion de las variables de session
	 * @access 		: public
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model', 'ds_model');
        $this->load->model('Registro_model', 'mod_registro');
    }
    
    /**
     * Método que carga el formulario de búsqueda y el listado de publicaciones.
     * @autor 		: Jesús Díaz P.
	 * @modified 	: 
	 * @access 		: public
     */
    public function index()	{
        $this->config->load('general');
        $estado_agenda = $this->config->item('estado_agenda');
        $estado_taller = $this->config->item('estado_taller');
        //$matricula = $this->session->userdata('matricula');
        $datos['sesiones_programadas'] = $this->mod_registro->getSesion(array('conditions' => 'a_estado = ' . $estado_agenda['ACTIVO']['id'] . ' AND a_tipo=1', 'order' => array('field' => 'a_inicio', 'type' => 'ASC'))); //buscamos las sesiones programadas
        $datos['sesiones_programadas_distancia'] = $this->mod_registro->getSesion(array('conditions' => 'a_estado = ' . $estado_agenda['ACTIVO']['id'] . ' AND YEAR(a_inicio)=YEAR(NOW()) and a_tipo=2', 'order' => array('field' => 'a_inicio', 'type' => 'ASC'))); //buscamos las sesiones programadas a distancia

        $this->template->setMainContent($this->load->view('dashboard/dashboard', $datos, TRUE));
        $this->template->getTemplate();
	}

}
