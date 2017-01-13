<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Calendario_model extends CI_Model {

    // Call the CI_Model constructor
    public $tipo_asistencia = array("I" => 1, "F" => 2);

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }

    public function get_calendario()
    {

        $this->db->where('a_tipo', 2);
        $this->db->where('a_estado', 1);
        $this->db->order_by('a_inicio', 'DESC');
        $query = $this->db->get('rist_agenda');

        $resultado = $query->result_array();

        return $resultado;
    }

    /**
     * @author Christian Garcia
     * @return lista de sesiones presenciales activas
     */
    public function get_calendario_presencial()
    {
        $this->db->where('a_tipo', 1);
        $this->db->where('a_estado', 1);
        $this->db->order_by('a_inicio', 'DESC');
        $query = $this->db->get('rist_agenda');

        $resultado = $query->result_array();

        return $resultado;
    }

}