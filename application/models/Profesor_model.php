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
	
    public function getSesion($id = null) {
        if (is_null($id) || $id == 0) {
			return null;
		}
		$this->db->select("agenda_id,a_nombre,a_cupo,a_desc,a_inicio,a_fin,a_evaluacion_inicio,a_evaluacion_fin,CONCAT('Sesi&oacute;n del ',DATE_FORMAT(a_inicio,'%d-%m-%Y'),' al ',DATE_FORMAT(a_fin,'%d-%m-%Y'))as fecha, a_liga");
		$this->db->from("rist_agenda");
        $this->db->where("agenda_id", $id);
		$result = $this->db->get();
        if ($result->num_rows() == 1) {
			$sesion = $result->result_array()[0];
			$result->free_result();
			return $sesion;
        } else {
			return null;
		}
	}
	
    public function getSesionList() {
		$resultado = array();
		$this->db->select("agenda_id,CONCAT(a_nombre,'. Sesi&oacute;n del ',DATE_FORMAT(a_inicio,'%d-%m-%Y'),' al ',DATE_FORMAT(a_fin,'%d-%m-%Y'))as fecha");
		$this->db->from('rist_agenda');
        $this->db->where("a_estado", 1);
        $this->db->where("a_tipo", 1);
        $this->db->order_by("a_inicio", "ASC");
		$query = $this->db->get();
		
        $resultado['data'] = $query->result_array();
		// pr( $resultado);
        $query->free_result(); //Libera la memoria
        return dropdown_options($resultado['data'], 'agenda_id', 'fecha');
	}
	
    public function getStudents($id = null) {
        if (is_null($id) || $id == 0) {
			return null;
		}
		$sql = "SELECT 
			taller.taller_id,
			usr.usr_matricula ,CONCAT(usr.usr_nombre,' ',usr.usr_paterno,' ',usr.usr_materno)fullname,taller.t_hash_constancia,usr.usr_correo,
			cat.nom_categoria,
			dept.cve_depto_adscripcion,
			dept.nom_depto_adscripcion,
			dlg.nom_delegacion
		FROM rist_taller taller 
			JOIN rist_usuario usr ON (usr.usr_matricula = taller.usr_matricula)
			JOIN rist_categoria cat ON(cat.des_clave = taller.cve_categoria)
			JOIN rist_departamentos dept ON(dept.cve_depto_adscripcion = taller.cve_depto_adscripcion)
			JOIN rist_delegacion dlg ON(dlg.cve_delegacion = taller.cve_delegacion)
		WHERE taller.agenda_id ={$id} AND taller.t_estado = 1
		ORDER BY 3";
		
		$result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
			$students = $result->result_array();
			$result->free_result();
            foreach ($students as $id => $student) {
				$this->db->from("rist_asistencia");
                $this->db->where("taller_id", $student["taller_id"]);
				$result = $this->db->get();
				$students[$id]["asistencias"] = $result->result_array();
				$result->free_result();
			}
			return $students;
        } else {
			return null;
		}
	}
	
    public function getAsistencias($id = null, $type = "I") {
        if (is_null($id) || $id == 0) {
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
