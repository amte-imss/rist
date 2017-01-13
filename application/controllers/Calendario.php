<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendario extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
        $this->load->model('Calendario_model', 'cal_mod');
	}



	function index()
	{
		$data=array();
//                $data['calendario'] = $this->cal_mod->get_calendario(array('fields' => ''));
		$data['calendario'] = $this->cal_mod->get_calendario();
                $salida=$this->load->view('calendario/fecha',$data,TRUE);
		$this->template->setMainContent($salida);
		$this->template->getTemplate();
	}

    /**
     * Render para calendario presencial
     * @uthor Christian Garcia
     */
    function presenciales()
    {
        $data = array();
        $this->load->model("Profesor_model", "profe");
        $data['calendario'] = $this->cal_mod->get_calendario_presencial();
        $salida = $this->load->view('calendario/presenciales', $data, TRUE);
        $this->template->setMainContent($salida);
        $this->template->getTemplate();
    }


}