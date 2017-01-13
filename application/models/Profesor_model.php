<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profesor_model extends CI_Model {
	// Call the CI_Model constructor
    public $tipo_asistencia = array("I" => 1, "F" => 2);

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }

    public function getSesion($id = null)
    {
        if (is_null($id) || $id == 0)
        {
            return null;
        }
        $this->db->select("agenda_id,a_nombre,a_cupo,a_desc,a_inicio,a_fin,a_evaluacion_inicio,a_evaluacion_fin,CONCAT('Sesi&oacute;n del ',DATE_FORMAT(a_inicio,'%d-%m-%Y'),' al ',DATE_FORMAT(a_fin,'%d-%m-%Y'))as fecha, a_liga");
        $this->db->from("rist_agenda");
        $this->db->where("agenda_id", $id);
        $result = $this->db->get();
        if ($result->num_rows() == 1)
        {
            $sesion = $result->result_array()[0];
            $result->free_result();
            return $sesion;
        } else
        {
            return null;
        }
    }

    public function getSesionList($mes = 0)
    {
        $resultado = array();
        $this->db->select("agenda_id,CONCAT(a_nombre,'. Sesi&oacute;n del ',DATE_FORMAT(a_inicio,'%d-%m-%Y'),' al ',DATE_FORMAT(a_fin,'%d-%m-%Y'))as fecha");
        $this->db->from('rist_agenda');
        $this->db->where("a_estado", 1);
        $this->db->where("a_tipo", 1);
        if ($mes > 0 && $mes < 13)
        {
            $this->db->where("EXTRACT(MONTH FROM a_inicio) = {$mes}");
        }
        $this->db->order_by("a_inicio", "ASC");
		$query = $this->db->get();
		
        $resultado['data'] = $query->result_array();
		// pr( $resultado);
        $query->free_result(); //Libera la memoria
        return dropdown_options($resultado['data'], 'agenda_id', 'fecha');
    }

    public function getStudents($id = null)
    {
        $salida = null;
        if (!is_null($id) && $id != 0)
        {
            $this->db->select("rist_taller.taller_id, rist_usuario.usr_matricula, CONCAT(rist_usuario.usr_nombre,' ',rist_usuario.usr_paterno,' ',rist_usuario.usr_materno) fullname, rist_taller.t_hash_constancia, rist_usuario.usr_correo, rist_categoria.nom_categoria, rist_departamentos.cve_depto_adscripcion, rist_departamentos.nom_depto_adscripcion, rist_delegacion.nom_delegacion,  rasist1.as_asistencia asist_inicio,  rasist2.as_asistencia asist_final");
            $this->db->from("rist_taller");
            $this->db->join("rist_usuario", "rist_usuario.usr_matricula = rist_taller.usr_matricula", "inner");
            $this->db->join("rist_categoria", "rist_categoria.des_clave = rist_taller.cve_categoria", "inner");
            $this->db->join("rist_departamentos", "rist_departamentos.cve_depto_adscripcion = rist_taller.cve_depto_adscripcion", "inner");
            $this->db->join("rist_delegacion", "rist_delegacion.cve_delegacion = rist_taller.cve_delegacion", "inner");
            $this->db->join("rist_asistencia rasist1", "rasist1.taller_id = rist_taller.taller_id and rasist1.as_asistencia = 1", "left");
            $this->db->join("rist_asistencia rasist2", "rasist2.taller_id = rist_taller.taller_id and rasist2.as_asistencia = 2", "left");
            $this->db->where("rist_taller.agenda_id", $id);
            $this->db->where("rist_taller.t_estado", 1);
            $this->db->group_by("rist_taller.taller_id");
            $this->db->order_by("3");
            $query = $this->db->get();
            if ($query->num_rows() > 0)
            {
                $salida = $query->result_array();
            }
            $query->free_result();
        }
        return $salida;
    }

    public function getAsistencias($id = null, $type = "I")
    {
        if (is_null($id) || $id == 0)
        {
            return null;
        }
        $tipo = array("I" => 1, "F" => 2);
		// as_asistenca 1=> Inicio, 2=> FIN
		$this->db->from("rist_asistencia");
        $this->db->where("taller_id", $id);
        $this->db->where("as_asistencia", $tipo[$type]);
		$result = $this->db->get();
        if ($result->num_rows() == 1) {
			$asistencias = $result->result_array()[0];
			$result->free_result();
			return $asistencias;
        } else {
			// echo "null";
			return null;
		}
	}
	
    public function saveAsistencias($data = null) {
        if (is_null($data) || empty($data)) {
			return FALSE;
		}
        $tipo = array("I" => 1, "F" => 2);
		$data["as_asistencia"] = $tipo[$data["as_asistencia"]];
		$this->db->insert('rist_asistencia', $data);
		
		$this->db->from("rist_asistencia");
        $this->db->where("as_id", $this->db->insert_id());
		$result = $this->db->get();
		$data = $result->result_array()[0];
		$result->free_result();		
		return $data;		
		// return false;		
	}
	
    /*
     * @method: getCountInscritos
     * @author: Christian Garcia
     * 
     */

    public function getCountInscritos($id = null) {
        $cantidad = 0;
        if (!is_null($id) && !empty($id)) {
            $this->db->select("count(*) as cantidad");
            $this->db->from("rist_taller");
            $this->db->where("agenda_id", $id);
            $this->db->where("t_estado", 1);
            $result = $this->db->get();
            if ($result->num_rows() > 0) {
                $cantidad = $result->result_array()[0]["cantidad"];
                // only debug //echo json_encode($cantidad);
                $result->free_result();
            }
        }
        return $cantidad;
    }
    
    /*
     * @method: getCountRegulares
     * @author: Christian Garcia
     * Retorna la cantidad de alumnos que asistieron los dos dias a un taller
     */

    public function getCountRegulares($id = null) {
        $cantidad = 0;
        if (!is_null($id) && !empty($id)) {
            $this->db->select("count(rist_taller.taller_id)");
            $this->db->from("rist_taller");
            $this->db->join("rist_asistencia", "rist_asistencia.taller_id = rist_taller.taller_id", "inner");
            $this->db->where("rist_taller.agenda_id", $id);
            $this->db->where("t_estado", 1);
            $this->db->group_by("rist_taller.taller_id");
            $this->db->having("count(as_asistencia)", 2);
            $result = $this->db->get();
            if ($result->num_rows() > 0) {
                $cantidad = $result->num_rows();
                // only debug //echo json_encode($cantidad);
                $result->free_result();
            }
        }
        return $cantidad;
    }

}
